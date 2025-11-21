<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>利用規約 | ナウポン</title>

    <style>
        /* ▼▼ 利用規約ページ専用スタイル ▼▼ */

        /* ★ 外枠（中央寄せ & 幅1200px） */
        .terms-page {
            max-width: 1200px;  /* ← ここで幅決定 */
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

        /* ★ カード（幅1200px） */
        .terms-page__card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            line-height: 1.8;
            color: #374151;
            font-size: 14px;

            max-width: 1200px;  /* ← カード幅 */
            margin-left: auto;
            margin-right: auto;
        }

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

        /* ▲▲ここまで専用スタイル ▲▲ */
    </style>
</head>

<body>
<div class="container">
    @include('layouts.header')

    <main class="terms-page">

        <div class="terms-page__title">
            <h1 class="terms-page__title-text">ナウポン 利用規約</h1>
        </div>

        <section class="terms-page__card">

            <!-- 第1条 -->
            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第1条（本規約の適用）</h2>
                <p>
                    本利用規約（以下「本規約」といいます。）は、ナウポン（以下「当社」といいます。）が運営する
                    クーポンサービス「ナウポン」（以下「本サービス」といいます。）の利用条件を定めるものです。
                    本サービスを利用するすべてのユーザー（以下「ユーザー」といいます。）に適用されます。
                </p>
            </div>

            <!-- 第2条 -->
            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第2条（利用登録）</h2>
                <p>本サービスの利用を希望する者は、当社が定める方法により利用登録を行うものとします。</p>
                <p>当社は、以下のいずれかに該当する場合、登録を拒否することがあります。</p>
                <ol>
                    <li>虚偽の情報を提出した場合</li>
                    <li>過去に本規約違反等により利用停止処分を受けた場合</li>
                    <li>その他、当社が不適切と判断した場合</li>
                </ol>
            </div>

            <!-- 第3条 -->
            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第3条（アカウント管理）</h2>
                <p>
                    ユーザーは、アカウント情報（メールアドレス・パスワード等）を自己の責任で管理するものとします。
                    第三者による不正利用により損害が生じた場合でも、当社は一切責任を負いません。
                </p>
            </div>

            <!-- 第4条 -->
            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第4条（禁止事項）</h2>
                <p>ユーザーは、以下の行為を行ってはなりません。</p>
                <ol>
                    <li>法令または公序良俗に反する行為</li>
                    <li>虚偽情報の登録、なりすまし行為</li>
                    <li>クーポンの転売、譲渡、共有</li>
                    <li>本サービスの運営を妨害する行為</li>
                    <li>不正アクセス、システム解析、リバースエンジニアリング</li>
                    <li>その他、当社が不適切と判断する行為</li>
                </ol>
            </div>

            <!-- 第5条 -->
            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第5条（クーポンの利用）</h2>
                <p>
                    本サービスで提供されるクーポンは、対象店舗が指定する条件の範囲でのみ利用できます。
                    クーポン利用後の返金や再発行はできません。
                </p>
            </div>

            <!-- 第6条 -->
            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第6条（サービス内容の変更・停止）</h2>
                <p>
                    当社は、ユーザーへの事前通知なくサービス内容の変更・中断・終了を行う場合があります。
                    これによりユーザーに損害が発生しても、当社は責任を負いません。
                </p>
            </div>

            <!-- 第7条 -->
            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第7条（免責事項）</h2>
                <p>
                    本サービスに関して発生したユーザーの損害について、当社は一切の責任を負いません。
                    また、加盟店舗が提供するサービス内容についても責任を負いません。
                </p>
            </div>

            <!-- 第8条 -->
            <div class="terms-page__section">
                <h2 class="terms-page__section-title">第8条（利用規約の変更）</h2>
                <p>
                    当社は、必要と判断した場合、本規約を変更することがあります。
                    変更後の規約は、本サービス上に掲載された時点で効力を生じます。
                </p>
            </div>

            <!-- 第9条 -->
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
