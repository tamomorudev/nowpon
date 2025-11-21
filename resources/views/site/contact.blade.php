<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>お問い合わせ | ナウポン</title>

    <style>
        /* ▼▼ Contactページ専用スタイル ▼▼ */

        /* 外枠（利用規約と同じ世界観） */
        .contact-page {
            max-width: 1200px;  /* ← 外枠の最大幅 */
            margin: 0 auto;
            padding: 24px 16px 80px;
            box-sizing: border-box;
        }

        .contact-page__title {
            margin: 24px 0 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b4e3d;
        }
        .contact-page__title-icon {
            font-size: 24px;
        }
        .contact-page__title-text {
            font-size: 24px;
            margin: 0;
            font-weight: 700;
        }

        /* カード本体（利用規約カードと同デザイン） */
        .contact-page__card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            color: #374151;
            font-size: 14px;
            line-height: 1.8;

            max-width: 1200px;  /* ← カード幅 */
            margin-left: auto;
            margin-right: auto;
        }

        @media (min-width: 1024px) {
            .contact-page__card {
                padding: 32px 28px;
            }
        }

        .contact-page__intro {
            margin-bottom: 16px;
        }

        /* Googleフォーム埋め込み用ラッパー */
        .contact-page__form-wrapper {
            width: 100%;
            /* iframe があふれないように */
            overflow: hidden;
        }

        .contact-page__iframe {
            width: 100%;
            min-height: 900px;   /* ← フォームの高さに応じて調整してください */
            border: none;
        }

        .contact-page__back {
            margin-top: 32px;
            text-align: center;
        }
        .contact-page__back-link {
            display: inline-block;
            padding: 10px 28px;
            border-radius: 999px;
            background: #d2a679;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
        .contact-page__back-link:hover {
            opacity: 0.9;
        }

        @media (max-width: 767px) {
            .contact-page {
                padding: 16px 12px 60px;
            }
            .contact-page__card {
                padding: 18px 14px;
                border-radius: 12px;
            }
            .contact-page__title-text {
                font-size: 20px;
            }
            .contact-page__iframe {
                min-height: 1000px; /* スマホは少し余裕を見てもよいです */
            }
        }

        /* ▲▲ Contactページ専用スタイル ▲▲ */
    </style>
</head>

<body>
<div class="container">
    @include('layouts.header')

    <main class="contact-page">

        <div class="contact-page__title">
            <span class="contact-page__title-icon">✉️</span>
            <h1 class="contact-page__title-text">お問い合わせ</h1>
        </div>

        <section class="contact-page__card">
            <p class="contact-page__intro">
                ナウポンに関するご質問・ご意見・不具合のご連絡などは、以下のフォームよりお問い合わせください。
            </p>
            <p class="contact-page__intro">
                内容を確認のうえ、必要に応じて担当者よりご連絡いたします。
            </p>

            <div class="contact-page__form-wrapper">
                <!-- ▼▼ Googleフォーム埋め込み ▼▼
                     Googleフォーム編集画面の「送信」→「埋め込む</>」から発行される
                     iframe の src を以下にコピペしてください。
                -->
                <iframe
                    class="contact-page__iframe"
                    src="https://docs.google.com/forms/d/e/1FAIpQLSe3fSLkpdXLU3iZcFRDzdZpWCjNw4QQEHPSkjPQTleqd2pThA/viewform?usp=dialog">
                    読み込んでいます…
                </iframe>
                <!-- ▲▲ Googleフォーム埋め込み ▲▲ -->
            </div>
        </section>

        <div class="contact-page__back">
            <a href="/site/top" class="contact-page__back-link">TOPページへ戻る</a>
        </div>

    </main>

    @include('layouts.footer')
</div>
</body>
</html>
