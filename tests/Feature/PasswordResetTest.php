<?php

namespace Tests\Feature;

use App\Http\Middleware\AuthenticateMemberSession;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\AdminUser;
use App\Models\StoreUser;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bootstrap\RegisterProviders;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    /**
     * プロバイダーの起動前に、DB・メール・セッションをテスト用に切り替える。
     * 実環境のDBを更新したり、実際のメールを送信したりしないようにする。
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->beforeBootstrapping(RegisterProviders::class, function ($app) {
            $app['config']->set([
                'app.env' => 'testing',
                'app.url' => 'http://localhost:8080',
                'app.key' => 'base64:'.base64_encode(str_repeat('t', 32)),
                'database.default' => 'password_reset_test',
                'database.connections' => ['password_reset_test' => [
                    'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
                ]],
                'auth.defaults.guard' => 'web',
                'auth.defaults.passwords' => 'users',
                'auth.passwords.users.connection' => 'password_reset_test',
                'mail.default' => 'array',
                'mail.mailers' => ['array' => ['transport' => 'array']],
                'mail.from' => ['address' => 'noreply@example.test', 'name' => 'NowPon'],
                'session.driver' => 'array',
                'cache.default' => 'array',
                'queue.default' => 'sync',
                'logging.default' => 'null',
                'hashing.bcrypt.rounds' => 4,
            ]);
        });
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * 各テストに必要な一般会員テーブルと再設定用テーブルを用意する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        ResetPassword::$createUrlCallback = null;
        ResetPassword::$toMailCallback = null;

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        // 再設定用テーブルは、既存のマイグレーションそのものをメモリ内DBに適用する。
        (require database_path('migrations/2023_12_12_100000_create_password_resets_table.php'))->up();
    }

    /**
     * 固定した時刻と通知のコールバックを戻し、次のテストへの影響を防ぐ。
     */
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        ResetPassword::$createUrlCallback = null;
        ResetPassword::$toMailCallback = null;
        parent::tearDown();
    }

    /**
     * 実在しないメールアドレスで、テスト用の一般会員を作成する。
     */
    private function member(string $email = 'member@example.test'): User
    {
        return User::create([
            'name' => 'Test member',
            'email' => $email,
            'password' => Hash::make('original-password'),
        ]);
    }

    /**
     * 本番と同じCSRF検証を行うテストに切り替える。
     */
    private function enforceCsrf(): void
    {
        // Laravelがテスト時に省略するCSRF検証を有効にし、実際の期限切れを再現する。
        $this->app->bind(VerifyCsrfToken::class, function ($app) {
            return new class($app, $app['encrypter']) extends VerifyCsrfToken {
                protected function runningUnitTests()
                {
                    return false;
                }
            };
        });
    }

    /**
     * 期限切れのフォームでは送信せず、メールアドレスだけを戻して再試行できる。
     */
    public function test_expired_csrf_returns_to_a_fresh_form_without_sending_and_allows_retry(): void
    {
        Notification::fake();
        $user = $this->member();
        $this->enforceCsrf();
        $this->withSession(['_token' => 'current-session-csrf']);

        foreach (['', 'expired-form-csrf'] as $oldToken) {
            $this->post('/password/email', [
                'email' => $user->email,
                '_token' => $oldToken,
                'password' => 'never-flash-this',
                'password_confirmation' => 'never-flash-this',
            ])->assertRedirect(route('password.request'))
                ->assertSessionHasErrors(['email' => trans('passwords.session_expired', [], 'ja')])
                ->assertSessionHasInput('email', $user->email);

            Notification::assertNothingSent();
            $this->assertSame(0, DB::table('password_resets')->count());
            $this->assertSame(['email' => $user->email], session()->getOldInput());
        }

        $page = $this->get('/password/reset')->assertOk()
            ->assertSee(trans('passwords.session_expired', [], 'ja'));
        $this->assertSame(1, preg_match('/name="_token" value="([^"]+)"/', $page->getContent(), $matches));
        $this->post('/password/email', ['email' => $user->email, '_token' => $matches[1]])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', trans('passwords.sent', [], 'ja'));
        Notification::assertSentToTimes($user, PasswordResetNotification::class, 1);
        $this->assertSame(1, DB::table('password_resets')->count());
    }

    /**
     * JSONでのCSRF検証失敗は、従来どおり419を返して送信しない。
     */
    public function test_expired_csrf_json_remains_419_without_sending(): void
    {
        Notification::fake();
        $user = $this->member();
        $this->enforceCsrf();
        $this->postJson('/password/email', ['email' => $user->email])->assertStatus(419);
        Notification::assertNothingSent();
        $this->assertSame(0, DB::table('password_resets')->count());
    }

    /**
     * 問い合わせとパスワード変更では、CSRF検証を引き続き必須とする。
     */
    public function test_csrf_is_still_required_for_other_form_posts(): void
    {
        $this->enforceCsrf();
        $this->post('/site/contact', [])->assertStatus(419);
        $this->post('/password/reset', [])->assertStatus(419);
    }

    /**
     * 実際のメール送信ルートを通し、通知に渡された再設定トークンを取得する。
     */
    private function issueToken(User $user): string
    {
        Notification::fake();
        $this->from('/password/reset')->post('/password/email', ['email' => $user->email])
            ->assertRedirect('/password/reset')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', trans('passwords.sent', [], 'ja'));

        $notifications = Notification::sent($user, PasswordResetNotification::class);
        $this->assertCount(1, $notifications);

        return $notifications->first()->token;
    }

    /**
     * 再設定フォームで送信する必須項目を組み立てる。
     */
    private function resetPayload(User $user, string $token, string $password = 'replacement-password'): array
    {
        return [
            'email' => $user->email,
            'token' => $token,
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }

    /**
     * 利用者向けの応答に日本語が含まれることを確認する。
     */
    private function assertJapanese(string $text): void
    {
        $this->assertMatchesRegularExpression('/[\x{3040}-\x{30ff}\x{4e00}-\x{9fff}]/u', $text);
    }

    /**
     * 登録済み会員への再設定トークンは1件だけ発行され、DBにはハッシュで保存される。
     */
    public function test_registered_member_receives_a_hashed_single_reset_token(): void
    {
        $user = $this->member();
        $token = $this->issueToken($user);

        $row = DB::table('password_resets')->sole();
        $this->assertSame($user->email, $row->email);
        $this->assertNotSame($token, $row->token);
        $this->assertTrue(Hash::check($token, $row->token));
        $this->assertTrue(Password::tokenExists($user, $token));
        $this->assertJapanese(session('status'));
    }

    /**
     * 実送信せずにメール生成を通し、HTML・テキスト両方の日本語とNowPon表記を確認する。
     */
    public function test_real_notification_channel_renders_html_and_text_without_external_delivery(): void
    {
        app()->setLocale('en');
        config(['app.name' => 'Laravel']);
        $user = $this->member();
        $transport = Mail::mailer()->getSwiftMailer()->getTransport();
        $this->assertInstanceOf(ArrayTransport::class, $transport);

        $this->post('http://attacker.invalid/password/email', ['email' => $user->email])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', trans('passwords.sent', [], 'ja'));
        $this->assertSame('en', app()->getLocale());

        $messages = $transport->messages();
        $this->assertCount(1, $messages);
        $message = $messages->first();
        $this->assertSame('【NowPon】パスワード再設定のご案内', $message->getSubject());
        $this->assertSame([$user->email], array_keys($message->getTo()));
        $this->assertSame(['noreply@example.test'], array_keys($message->getFrom()));

        // SwiftMailerではHTML本文はメッセージ本体、テキスト本文は子パートに格納される。
        $bodies = ['text/html' => $message->getBody()];
        foreach ($message->getChildren() as $part) {
            $bodies[$part->getContentType()] = $part->getBody();
        }
        $this->assertArrayHasKey('text/html', $bodies);
        $this->assertArrayHasKey('text/plain', $bodies);
        $this->assertStringContainsString('<html', $bodies['text/html']);
        foreach ($bodies as $body) {
            $decoded = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
            $this->assertStringContainsString('パスワードを再設定する', $decoded);
            $this->assertStringContainsString('60分', $decoded);
            $this->assertStringContainsString('http://localhost:8080/password/reset/', $decoded);
            $this->assertStringContainsString('NowPon', $decoded);
            $this->assertStringNotContainsString('Laravel', $decoded);
            $this->assertStringNotContainsString('laravel.com', $decoded);
            $this->assertStringNotContainsString('迷惑メール', $decoded);
            $this->assertStringNotContainsString("If you're having trouble clicking", $decoded);
            $this->assertStringNotContainsString('All rights reserved.', $decoded);
            $this->assertStringNotContainsString('original-password', $decoded);
        }
    }

    /**
     * 不正なHostでも設定済みのURLを使い、メールアドレスの記号も正しく引き継ぐ。
     */
    public function test_reset_url_uses_application_url_and_ignores_an_untrusted_request_host(): void
    {
        config(['app.url' => 'https://nowpon.example.test']);
        URL::forceRootUrl('http://attacker.invalid');
        $user = $this->member('member+tag@example.test');
        $notification = new PasswordResetNotification('dummy-reset-token');
        $mail = $notification->toMail($user);

        $this->assertSame('nowpon.example.test', parse_url($mail->actionUrl, PHP_URL_HOST));
        $this->assertSame('https', parse_url($mail->actionUrl, PHP_URL_SCHEME));
        $this->assertSame('/password/reset/dummy-reset-token', parse_url($mail->actionUrl, PHP_URL_PATH));
        parse_str(parse_url($mail->actionUrl, PHP_URL_QUERY), $query);
        $this->assertSame($user->email, $query['email']);
    }

    /**
     * 本文に記載する有効期限は、ブローカーの設定変更に追従する。
     */
    public function test_mail_expiration_follows_broker_configuration(): void
    {
        config(['auth.passwords.users.expire' => 30]);
        $mail = (new PasswordResetNotification('dummy-token'))->toMail($this->member());
        $lines = implode("\n", array_merge($mail->introLines, $mail->outroLines));
        $this->assertStringContainsString('30分', $lines);
    }

    /**
     * 対象会員だけを更新し、自動ログインせず、新しいパスワードでのログインと再利用拒否を確認する。
     */
    public function test_valid_link_changes_only_target_password_and_cannot_be_reused(): void
    {
        Event::fake([Login::class]);
        $user = $this->member();
        $other = $this->member('other@example.test');
        $otherHash = $other->password;
        $user->forceFill(['remember_token' => 'old-remember-token'])->save();
        $token = $this->issueToken($user);

        $this->get('/password/reset/'.$token.'?'.http_build_query(['email' => $user->email]))
            ->assertOk()
            ->assertSee('パスワードを再設定する');
        $this->withSession(['_token' => 'old-csrf-token']);
        $previousSessionId = session()->getId();
        $this->post('/password/reset', $this->resetPayload($user, $token))
            ->assertRedirect('/login')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', trans('passwords.reset', [], 'ja'));
        $this->assertNotSame($previousSessionId, session()->getId());
        $this->assertNotSame('old-csrf-token', session()->token());
        Event::assertNotDispatched(Login::class);

        $user->refresh();
        $this->assertTrue(Hash::check('replacement-password', $user->password));
        $this->assertFalse(Hash::check('original-password', $user->password));
        $this->assertNotSame('old-remember-token', $user->remember_token);
        $this->assertSame($otherHash, $other->fresh()->password);
        $this->assertDatabaseMissing('password_resets', ['email' => $user->email]);
        $this->assertGuest('web');
        $this->assertFalse(Auth::guard('web')->validate(['email' => $user->email, 'password' => 'original-password']));
        $this->assertTrue(Auth::guard('web')->validate(['email' => $user->email, 'password' => 'replacement-password']));
        $this->assertJapanese(session('status'));

        $this->get('/login')->assertOk()
            ->assertSee('パスワードを変更しました。新しいパスワードでログインしてください。');
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'original-password'])
            ->assertRedirect('/login')->assertSessionHasErrors('login_error');
        $this->assertGuest('web');
        $this->post('/login', ['email' => $user->email, 'password' => 'replacement-password'])
            ->assertRedirect('/');
        $this->assertAuthenticatedAs($user, 'web');
        Event::assertDispatched(Login::class);

        Auth::guard('web')->logout();
        $this->from('/password/reset')->post('/password/reset', $this->resetPayload($user, $token, 'replayed-password'))
            ->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('replacement-password', $user->fresh()->password));
    }

    /**
     * ログイン済みのブラウザでも、再設定後は認証を解除してセッションIDを更新する。
     */
    public function test_successful_reset_logs_out_an_already_authenticated_browser(): void
    {
        $user = $this->member();
        $token = $this->issueToken($user);
        $this->actingAs($user, 'web')->withSession([
            AuthenticateMemberSession::PASSWORD_HASH_KEY => $user->password,
        ]);
        $previousSessionId = session()->getId();

        $this->post('/password/reset', $this->resetPayload($user, $token))
            ->assertRedirect('/login')
            ->assertSessionHas('status', trans('passwords.reset', [], 'ja'));
        $this->assertNotSame($previousSessionId, session()->getId());
        $this->assertGuest('web');
        $this->assertTrue(Hash::check('replacement-password', $user->fresh()->password));
        $this->assertDatabaseMissing('password_resets', ['email' => $user->email]);
    }

    /**
     * JSONで再設定しても自動ログインせず、使用済みトークンを削除する。
     */
    public function test_json_reset_does_not_automatically_authenticate(): void
    {
        Event::fake([Login::class]);
        $user = $this->member();
        $token = $this->issueToken($user);

        $this->postJson('/password/reset', $this->resetPayload($user, $token))
            ->assertOk()
            ->assertJson(['message' => trans('passwords.reset', [], 'ja')]);
        $this->assertGuest('web');
        Event::assertNotDispatched(Login::class);
        $this->assertDatabaseMissing('password_resets', ['email' => $user->email]);
    }

    /**
     * 認証済みの一般会員だけが到達できる、テスト専用ルートを登録する。
     */
    private function sessionProbe(): string
    {
        Route::middleware(['web', 'auth:web'])->get('/_test/member-session', function () {
            return response('member-only:'.Auth::guard('web')->id());
        });

        return '/_test/member-session';
    }

    /**
     * 保存しておいた別ブラウザのセッションへ切り替える。
     */
    private function useBrowserSession(array $session): void
    {
        // ガードのキャッシュと保存済みセッションを破棄し、別ブラウザの状態を復元する。
        // flushだけでは、保存済みデータがセッションの再開始時に読み直される。
        Auth::forgetGuards();
        session()->invalidate();
        $this->withSession($session);
    }

    /**
     * 変更前の別端末だけを失効させ、変更後のログインと他会員のセッションは維持する。
     */
    public function test_reset_revokes_another_browser_but_preserves_a_new_login_and_other_members(): void
    {
        $probe = $this->sessionProbe();
        $user = $this->member();
        $other = $this->member('other@example.test');
        $this->post('/login', ['email' => $other->email, 'password' => 'original-password'])->assertRedirect('/');
        $otherBrowser = session()->all();
        $this->useBrowserSession([]);
        $this->post('/login', ['email' => $user->email, 'password' => 'original-password'])
            ->assertRedirect('/');
        $this->assertSame($user->password, session(AuthenticateMemberSession::PASSWORD_HASH_KEY));
        $oldBrowser = session()->all();
        $this->get($probe)->assertOk()->assertSee('member-only:'.$user->id);

        $this->useBrowserSession([]);
        $token = $this->issueToken($user);
        $this->post('/password/reset', $this->resetPayload($user, $token))->assertRedirect('/login');
        $this->assertGuest('web');
        $this->post('/login', ['email' => $user->email, 'password' => 'replacement-password', 'remember' => true])
            ->assertRedirect('/');
        $newBrowser = session()->all();
        $newRememberToken = $user->fresh()->remember_token;

        $this->useBrowserSession($oldBrowser);
        $this->get($probe)->assertRedirect('/login')
            ->assertSessionHas('status', trans('passwords.login_required', [], 'ja'));
        $this->assertGuest('web');
        $this->assertSame($newRememberToken, $user->fresh()->remember_token);

        $this->useBrowserSession($newBrowser);
        $this->get($probe)->assertOk()->assertSee('member-only:'.$user->id);

        $this->useBrowserSession($otherBrowser);
        $this->get($probe)->assertOk()->assertSee('member-only:'.$other->id);
    }

    /**
     * 導入前の一般会員には一度だけ再ログインを求め、同居する店舗・管理者の認証は保持する。
     */
    public function test_pre_upgrade_member_session_requires_login_once_and_preserves_other_guard_sessions(): void
    {
        $user = $this->member();
        $store = (new StoreUser)->forceFill(['id' => 17, 'name' => 'Store']);
        $admin = (new AdminUser)->forceFill(['id' => 19, 'name' => 'Admin']);
        Auth::guard('store_user')->setUser($store);
        Auth::guard('admin_user')->setUser($admin);
        $storeKey = Auth::guard('store_user')->getName();
        $adminKey = Auth::guard('admin_user')->getName();
        $this->actingAs($user, 'web')->withSession([$storeKey => 17, $adminKey => 19]);
        $this->get('/login')->assertRedirect('/login')
            ->assertSessionHas($storeKey, 17)->assertSessionHas($adminKey, 19);
        $this->assertGuest('web');
        $this->assertAuthenticatedAs($store, 'store_user');
        $this->assertAuthenticatedAs($admin, 'admin_user');

        Auth::guard('store_user')->logoutCurrentDevice();
        Auth::guard('admin_user')->logoutCurrentDevice();
        $this->post('/login', ['email' => $user->email, 'password' => 'original-password'])->assertRedirect('/');
        $this->get($this->sessionProbe())->assertOk();
    }

    /**
     * 古いセッションのJSONリクエストは401となり、認証が必要な処理には到達しない。
     */
    public function test_stale_session_json_is_401_and_never_reaches_protected_action(): void
    {
        $user = $this->member();
        $this->actingAs($user, 'web')->withSession([AuthenticateMemberSession::PASSWORD_HASH_KEY => 'old-password-hash']);
        $this->getJson($this->sessionProbe())->assertStatus(401)
            ->assertJson(['message' => trans('passwords.login_required', [], 'ja')])
            ->assertDontSee('member-only');
        $this->assertGuest('web');
    }

    /**
     * 店舗・管理者の画面は、それぞれのガードで利用できる。
     */
    public function test_member_session_check_does_not_apply_to_store_or_admin_areas(): void
    {
        $member = $this->member();
        foreach (['store' => ['store_user', new StoreUser], 'admin' => ['admin_user', new AdminUser]] as $area => [$guard, $model]) {
            $user = $model->forceFill(['id' => 25, 'name' => $area]);
            Route::middleware(['web', 'auth:'.$guard])->get('/'.$area.'/_test/session', function () {
                return response('independent-guard');
            });
            Auth::guard('web')->setUser($member);
            $this->actingAs($user, $guard);
            $this->get('/'.$area.'/_test/session')->assertOk()->assertSee('independent-guard');
            $this->assertAuthenticatedAs($user, $guard);
        }
    }

    /**
     * 有効な保持用Cookieでは復帰でき、再設定前のCookieでは復帰できない。
     */
    public function test_remember_cookie_restores_a_valid_session_but_is_rejected_after_reset(): void
    {
        $probe = $this->sessionProbe();
        $user = $this->member();
        $this->post('/login', ['email' => $user->email, 'password' => 'original-password', 'remember' => true])
            ->assertRedirect('/');
        $user->refresh();
        $recallerName = Auth::guard('web')->getRecallerName();
        $recaller = implode('|', [$user->id, $user->remember_token, $user->password]);

        $this->useBrowserSession([]);
        $this->withCookie($recallerName, $recaller)->get($probe)->assertOk();
        $this->assertSame($user->password, session(AuthenticateMemberSession::PASSWORD_HASH_KEY));
        $this->assertTrue(Auth::guard('web')->viaRemember());

        $this->useBrowserSession([]);
        $this->defaultCookies = [];
        $token = $this->issueToken($user);
        $this->post('/password/reset', $this->resetPayload($user, $token))->assertRedirect('/login');
        $this->useBrowserSession([]);
        $this->withCookie($recallerName, $recaller)->get($probe)->assertRedirect('/login');
        $this->assertGuest('web');
    }

    /**
     * トークンが一致してもハッシュが古い保持用Cookieを拒否し、現在のトークンは維持する。
     */
    public function test_remember_cookie_with_a_stale_password_hash_cannot_create_a_member_session(): void
    {
        $user = $this->member();
        $this->post('/login', ['email' => $user->email, 'password' => 'original-password', 'remember' => true])
            ->assertRedirect('/');
        $user->refresh();
        $recallerName = Auth::guard('web')->getRecallerName();
        $recaller = implode('|', [$user->id, $user->remember_token, 'stale-password-hash']);
        $this->useBrowserSession([]);
        $this->withCookie($recallerName, $recaller)->get($this->sessionProbe())->assertRedirect('/login');
        $this->assertGuest('web');
        $this->assertSame($user->remember_token, $user->fresh()->remember_token);
    }

    /**
     * 不正なトークンや他会員のトークンでは、どの会員のパスワードも変更しない。
     */
    public function test_invalid_token_or_other_members_token_cannot_change_password(): void
    {
        $user = $this->member();
        $other = $this->member('other@example.test');
        $hash = $user->password;
        $otherHash = $other->password;
        $token = $this->issueToken($user);

        $this->from('/password/reset')->post('/password/reset', $this->resetPayload($user, 'invalid-token'))
            ->assertSessionHasErrors('email');
        $this->assertJapanese(session('errors')->first('email'));
        $this->post('/password/reset', $this->resetPayload($other, $token))
            ->assertSessionHasErrors('email');
        $this->assertSame($hash, $user->fresh()->password);
        $this->assertSame($otherHash, $other->fresh()->password);
        $this->assertTrue(Password::tokenExists($user, $token));
    }

    /**
     * 有効期限を過ぎたトークンでは、パスワードを変更しない。
     */
    public function test_expired_token_cannot_change_password(): void
    {
        $user = $this->member();
        $hash = $user->password;
        $token = $this->issueToken($user);
        $this->travel(61)->minutes();

        $this->from('/password/reset')->post('/password/reset', $this->resetPayload($user, $token))
            ->assertSessionHasErrors('email');
        $this->assertJapanese(session('errors')->first('email'));
        $this->assertSame($hash, $user->fresh()->password);
        $this->assertFalse(Password::tokenExists($user, $token));
    }

    /**
     * 同一メールアドレスへの再送を60秒間制限し、再送後は以前のリンクを無効にする。
     */
    public function test_resend_is_throttled_then_replaces_previous_link(): void
    {
        $user = $this->member();
        $token = $this->issueToken($user);
        $this->post('/password/email', ['email' => $user->email])
            ->assertSessionHasErrors('email');
        $this->assertJapanese(session('errors')->first('email'));
        Notification::assertSentToTimes($user, PasswordResetNotification::class, 1);
        $this->assertTrue(Password::tokenExists($user, $token));

        $this->travel(61)->seconds();
        $replacementToken = $this->issueToken($user);
        $this->assertNotSame($token, $replacementToken);
        $this->assertFalse(Password::tokenExists($user, $token));
        $this->assertTrue(Password::tokenExists($user, $replacementToken));
        $this->assertSame(1, DB::table('password_resets')->count());
    }

    /**
     * 未登録のメールアドレスには、トークンを発行せずメールも送信しない。
     */
    public function test_unknown_email_does_not_create_token_or_send_mail(): void
    {
        Notification::fake();
        $this->from('/password/reset')->post('/password/email', ['email' => 'unknown@example.test'])
            ->assertSessionHasErrors('email');
        $this->assertJapanese(session('errors')->first('email'));
        Notification::assertNothingSent();
        $this->assertSame(0, DB::table('password_resets')->count());
    }

    /**
     * 未入力や不正なメールアドレスは、日本語の入力エラーで案内する。
     */
    public function test_invalid_email_input_is_rejected_in_japanese(): void
    {
        Notification::fake();
        foreach (['', 'not-an-email'] as $email) {
            $this->post('/password/email', ['email' => $email])->assertSessionHasErrors('email');
            $this->assertJapanese(session('errors')->first('email'));
        }
        Notification::assertNothingSent();
    }

    /**
     * 文字数不足や確認欄の不一致では更新せず、パスワードを再入力用に保存しない。
     */
    public function test_short_or_unconfirmed_password_preserves_password_and_token(): void
    {
        $user = $this->member();
        $hash = $user->password;
        $token = $this->issueToken($user);
        foreach ([['short', 'short'], ['replacement-password', 'different-password']] as [$password, $confirmation]) {
            $payload = $this->resetPayload($user, $token, $password);
            $payload['password_confirmation'] = $confirmation;
            $this->from('/password/reset')->post('/password/reset', $payload)
                ->assertSessionHasErrors('password');
            $this->assertJapanese(session('errors')->first('password'));
            $this->assertArrayNotHasKey('password', session()->getOldInput());
            $this->assertArrayNotHasKey('password_confirmation', session()->getOldInput());
            $this->assertSame($hash, $user->fresh()->password);
            $this->assertTrue(Password::tokenExists($user, $token));
        }
    }

    /**
     * 同一IPの6回目を拒否し、別IPと制限解除後の送信は受け付ける。
     */
    public function test_ip_limit_blocks_sixth_recipient_preserves_only_email_and_recovers(): void
    {
        Notification::fake();
        Carbon::setTestNow('2026-10-18 00:00:00');
        $members = [];
        for ($index = 1; $index <= 7; $index++) {
            $members[] = $this->member('burst-'.$index.'@example.test');
        }
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.10']);

        foreach (array_slice($members, 0, 5) as $user) {
            $this->from('/password/reset')->post('/password/email', ['email' => $user->email])
                ->assertRedirect('/password/reset')
                ->assertSessionHasNoErrors();
            Notification::assertSentToTimes($user, PasswordResetNotification::class, 1);
        }

        $blocked = $members[5];
        $this->post('/password/email', [
            'email' => $blocked->email,
            'password' => 'must-not-be-flashed',
            'unexpected' => 'must-not-be-flashed',
        ])
            ->assertRedirect('/password/reset')
            ->assertSessionHasErrors('email')
            ->assertSessionHasInput('email', $blocked->email)
            ->assertHeader('Retry-After');
        $this->assertSame(trans('passwords.request_throttled', [], 'ja'), session('errors')->first('email'));
        $this->assertJapanese(session('errors')->first('email'));
        $this->assertSame(['email' => $blocked->email], session()->getOldInput());
        Notification::assertNotSentTo($blocked, PasswordResetNotification::class);
        $this->assertDatabaseMissing('password_resets', ['email' => $blocked->email]);
        $this->get('/password/reset')->assertOk();

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.11'])
            ->post('/password/email', ['email' => $blocked->email])
            ->assertSessionHasNoErrors();
        Notification::assertSentToTimes($blocked, PasswordResetNotification::class, 1);

        $this->travel(61)->seconds();
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.10'])
            ->post('/password/email', ['email' => $members[6]->email])
            ->assertSessionHasNoErrors();
        Notification::assertSentToTimes($members[6], PasswordResetNotification::class, 1);
        Notification::assertTimesSent(7, PasswordResetNotification::class);
    }

    /**
     * IP制限時のJSONは429・日本語の案内・再試行までの待ち時間を返す。
     */
    public function test_ip_limit_returns_japanese_json_429_with_retry_header(): void
    {
        Notification::fake();
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.20']);
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->postJson('/password/email', ['email' => 'unknown@example.test'])
                ->assertStatus(422);
        }
        $response = $this->postJson('/password/email', ['email' => 'unknown@example.test'])
            ->assertStatus(429)
            ->assertHeader('Retry-After')
            ->assertJson(['message' => trans('passwords.request_throttled', [], 'ja')]);
        $this->assertJapanese($response->json('message'));
        Notification::assertNothingSent();
        $this->assertSame(0, DB::table('password_resets')->count());
    }
}
