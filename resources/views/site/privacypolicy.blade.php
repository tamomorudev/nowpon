<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/site/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/content-page.css') }}">
    <title>利用規約 | ナウポン</title>
</head>

<body>
<div class="container">
    @include('layouts.header')

    <main class="terms-page">

        <div class="terms-page__title">
            <h1 class="terms-page__title-text">ナウポン プライバシーポリシー</h1>
        </div>

        <section class="terms-page__card">

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">ナウポン プライバシーポリシー（個人情報保護方針）</h2>
                <p>
                    ナウポン（以下「当社」といいます。）は、当社が運営するクーポンサービス「ナウポン」
                    （以下「本サービス」といいます。）における利用者の個人情報を適切に保護し、
                    以下の方針に基づき取り扱います。
                </p>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第1条（適用範囲）</h2>
                <p>
                    本ポリシーは、本サービスを利用するすべてのユーザーおよび加盟店舗
                    （以下「ユーザー等」といいます。）に適用されます。
                </p>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第2条（個人情報の定義）</h2>
                <p>
                    本ポリシーにおける「個人情報」とは、個人情報保護法に定める「個人情報」をいい、
                    氏名、メールアドレス、住所、電話番号、決済情報、その他特定の個人を識別できる情報を含みます。
                </p>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第3条（個人情報の収集方法）</h2>
                <p>
                    当社は、ユーザー登録・クーポン利用・お問い合わせ・決済・アンケート等の際に、
                    必要な範囲で個人情報を取得します。
                </p>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第4条（利用目的）</h2>
                <p>当社は、取得した個人情報を以下の目的で利用します。</p>
                <ol>
                    <li>本サービスの提供・運営</li>
                    <li>本人確認、アカウント管理、サポート対応</li>
                    <li>料金請求、決済処理</li>
                    <li>メール配信・お知らせ・キャンペーン情報の通知</li>
                    <li>不正利用防止・トラブル対応・利用規約違反調査</li>
                    <li>本サービスの改善・新サービス開発</li>
                    <li>法令・ガイドライン等による開示・提供が必要な場合</li>
                </ol>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第5条（第三者提供）</h2>
                <p>当社は、次の場合を除き、個人情報を第三者に提供しません。</p>
                <ol>
                    <li>本人の同意がある場合</li>
                    <li>法令に基づく場合</li>
                    <li>人の生命・身体・財産保護のために必要がある場合</li>
                    <li>業務委託先に機密保持契約を締結の上、業務遂行のため提供する場合</li>
                </ol>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第6条（委託先管理）</h2>
                <p>
                    当社は、個人情報の取扱いを外部業者に委託する場合、
                    機密保持契約を締結し、適切な監督を行います。
                </p>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第7条（個人情報の開示・訂正・削除）</h2>
                <p>
                    ユーザーは、当社所定の手続きにより、自身の個人情報の開示・訂正・削除・利用停止を求めることができます。
                    当社は合理的な範囲で速やかに対応します。
                </p>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第8条（セキュリティ対策）</h2>
                <p>
                    当社は、個人情報への不正アクセス、紛失、漏えい等を防止するため、
                    SSL通信、アクセス制限等の安全管理措置を講じます。
                </p>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第9条（クッキー等の利用）</h2>
                <p>
                    当社は、サービス向上のためクッキー（Cookie）・アクセス解析ツール等を使用します。
                    これにより個人を特定する情報を取得することはありません。
                </p>
            </div>

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第10条（プライバシーポリシーの変更）</h2>
                <p>
                    本ポリシーの内容は、必要に応じて変更される場合があります。
                    変更後の内容は、本サービス上に表示された時点で効力を生じます。
                </p>
            </div>

            <div class="terms-page__section">
                <p>制定日：2025年11月1日</p>
                <p>ナウポン</p>
            </div>

        </section>

        <div class="terms-page__back">
            <a href="/" class="terms-page__back-link">TOPページへ戻る</a>
        </div>

    </main>

    @include('layouts.footer')
</div>
</body>
</html>
