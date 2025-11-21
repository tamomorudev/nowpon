<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>利用規約 | ナウポン</title>

    <style>
        /* ▼▼ 利用規約ページ専用スタイル ▼▼ */

        /* ★ 外枠を完全に広げる（最重要） */
        .terms-page {
            max-width: 1200px;   /* ← ここで幅を決定（必須） */
            margin: 0 auto;
            padding: 24px 16px 80px;
            box-sizing: border-box;
        }

        .terms-page__title {
            margin: 24px 0 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b4e3d;
        }
        .terms-page__title-icon {
            font-size: 24px;
        }
        .terms-page__title-text {
            font-size: 24px;
            margin: 0;
            font-weight: 700;
        }

        /* ★ カード本体も広げる（外枠に合わせる） */
        .terms-page__card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            line-height: 1.8;
            color: #374151;
            font-size: 14px;

            max-width: 1200px;   /* ← 外枠と同じ（必須） */
            margin-left: auto;
            margin-right: auto;
        }

        /* PC時の余白を自然に広げる */
        @media (min-width: 1024px) {
            .terms-page__card {
                padding: 32px 28px;
            }
        }

        .terms-page__section {
            margin-top: 24px;
        }

        .terms-page__section-title {
            font-size: 18px;
            margin: 0 0 8px;
            color: #6b4e3d;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .terms-page__section-title::before {
            content: "■";
            font-size: 10px;
            color: #b08968;
        }

        .terms-page__section p {
            margin: 0 0 8px;
        }

        .terms-page__section ol {
            margin: 0 0 8px 1.2em;
            padding: 0;
        }

        .terms-page__back {
            margin-top: 32px;
            text-align: center;
        }
        .terms-page__back-link {
            display: inline-block;
            padding: 10px 28px;
            border-radius: 999px;
            background: #d2a679;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
        .terms-page__back-link:hover {
            opacity: 0.9;
        }

        @media (max-width: 767px) {
            .terms-page {
                padding: 16px 12px 60px;
            }
            .terms-page__card {
                padding: 18px 14px;
                border-radius: 12px;
            }
            .terms-page__title-text {
                font-size: 20px;
            }
        }

        /* ▲▲ ここまで専用スタイル ▲▲ */
    </style>
</head>

<body>
<div class="container">
    @include('layouts.header')

    <main class="terms-page">

        <div class="terms-page__title">
            <h1 class="terms-page__title-text">ナウポン 利用規約・プライバシーポリシー</h1>
        </div>

        <section class="terms-page__card">

            <div class="terms-page__section">
                <h2 class="terms-page__section-title">ナウポン 利用規約（Ver.1.0）</h2>
            </div>

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
