<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        return view('account.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 都道府県・性別の許容キー
        $prefKeys = array_keys(config('commons.prefectures', []));
        $sexKeys  = array_keys(config('commons.sexs', []));

        // 軽い正規化
        $input = $request->all();
        if (isset($input['postal_code'])) {
            $input['postal_code'] = preg_replace('/[^0-9\-]/', '', (string)$input['postal_code']);
        }
        if (isset($input['phone_number'])) {
            $input['phone_number'] = preg_replace('/[^\d\-\+\(\) ]/', '', (string)$input['phone_number']);
        }

        // バリデーションルール
        $rules = [
            'name'         => ['required','string','max:30'],
            'nickname'     => ['required','string','max:30'],
            'email'        => ['required','string','email:filter','max:255', Rule::unique('users','email')->ignore($user->id)],
            'postal_code'  => ['required','string','max:20'],
            'prefecture'   => ['required', Rule::in($prefKeys)],
            'city'         => ['required','string','max:255'],
            'phone_number' => ['required','string','max:50'],
            'sex'          => ['required', Rule::in($sexKeys)],
            'birth_date'   => ['required','date'],
            'password'     => ['nullable','min:8'], // 未入力なら更新しない
        ];

        $messages = [
            'required' => ':attribute は必須です。',
            'string'   => ':attribute は文字列で入力してください。',
            'max'      => ':attribute は :max 文字以下で入力してください。',
            'email'    => ':attribute の形式が正しくありません。',
            'unique'   => ':attribute は既に使用されています。',
            'date'     => ':attribute は正しい日付で入力してください。',
            'min'      => ':attribute は :min 文字以上で入力してください。',
            'in'       => ':attribute の選択が不正です。',
            'password.min'  => 'パスワードは8文字以上で入力してください。',
            'prefecture.in' => '都道府県の選択が不正です。',
            'sex.in'        => '性別の選択が不正です。',
        ];

        // 属性名（日本語化）
        $attributes = [
            'name'         => 'ユーザー名',
            'nickname'     => 'ニックネーム',
            'email'        => 'メールアドレス',
            'postal_code'  => '郵便番号',
            'prefecture'   => '都道府県',
            'city'         => '市区町村',
            'phone_number' => '電話番号',
            'sex'          => '性別',
            'birth_date'   => '生年月日',
            'password'     => 'パスワード',
        ];

        $validated = validator($input, $rules, $messages, $attributes)->validate();

        $emailChanged = isset($validated['email']) && $validated['email'] !== $user->email;

        // パスワード未入力なら更新しない
        if (empty($validated['password'] ?? '')) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        // 空文字は null に
        foreach (['prefecture','sex','postal_code','city','phone_number','birth_date','nickname'] as $nullable) {
            if (array_key_exists($nullable, $validated) && $validated[$nullable] === '') {
                $validated[$nullable] = null;
            }
        }

        // メール変更かつ MustVerifyEmail ユーザーなら再検証フロー
        if ($emailChanged && $user instanceof MustVerifyEmail) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
            $user->fill(collect($validated)->except(['email'])->toArray());
            $user->save();

            $user->sendEmailVerificationNotification();

            return redirect()
                ->route('account.index')
                ->with('status', 'プロフィールを更新しました。メールアドレスの確認メールを送信しました。');
        }

        // 通常更新
        $user->fill($validated)->save();

        return redirect()
            ->route('account.index')
            ->with('status', 'プロフィールを更新しました。');
    }
}
