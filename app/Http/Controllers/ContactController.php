<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    private const INQUIRY_TYPES = [
        'service' => 'サービスについて',
        'coupon' => 'クーポン・予約について',
        'account' => 'アカウントについて',
        'report' => '不具合のご報告',
        'other' => 'その他',
    ];

    public function create(): View
    {
        return view('site.contact', [
            'inquiryTypes' => self::INQUIRY_TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'inquiry_type' => ['nullable', Rule::in(array_keys(self::INQUIRY_TYPES))],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'name.required' => 'お名前を入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスの形式が正しくありません。',
            'message.required' => 'お問い合わせ内容を入力してください。',
        ]);

        $rateLimitKey = 'contact-form:'.$request->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $retryAfter = max(1, (int) ceil(RateLimiter::availableIn($rateLimitKey) / 60));

            return back()
                ->withInput()
                ->with('contact_error', "短時間に送信が集中しています。{$retryAfter}分ほど時間をおいて、もう一度お試しください。");
        }

        RateLimiter::hit($rateLimitKey, 60);

        $contact = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'inquiry_type' => $validated['inquiry_type'] ?? null,
            'inquiry_type_label' => self::INQUIRY_TYPES[$validated['inquiry_type'] ?? ''] ?? '未選択',
            'message' => $validated['message'],
        ];

        try {
            Mail::to(config('mail.contact_recipient'))->send(new ContactMessage($contact));
        } catch (\Throwable $exception) {
            Log::error('Contact form email delivery failed.', [
                'exception' => $exception,
            ]);

            return back()
                ->withInput()
                ->with('contact_error', '送信に失敗しました。時間をおいて、もう一度お試しください。');
        }

        return redirect()
            ->route('contact')
            ->with('contact_status', 'お問い合わせを受け付けました。内容を確認のうえ、ご連絡します。');
    }
}
