{{-- 標準のメール部品を使い、ヘッダーとフッターのブランド表記をNowPonに揃える --}}
@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
NowPon
@endcomponent
@endslot

# {{ $greeting }}

@foreach ($introLines as $line)
{{ $line }}

@endforeach

@component('mail::button', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent

@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{ $salutation }}

{{-- ボタンを利用できないメール環境向けに、同じ再設定URLを文字でも表示する --}}
@slot('subcopy')
@component('mail::subcopy')
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    ['actionText' => $actionText]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endcomponent
@endslot

@slot('footer')
@component('mail::footer')
© {{ date('Y') }} NowPon. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
