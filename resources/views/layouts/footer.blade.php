<style>
    /* フッターここから */
    .site-footer {
        --site-color-brand: #c5a067;
        flex-shrink: 0;
        background-color: var(--site-color-brand, #c5a067);
        color: white;
        padding: 24px 0;
        margin-top: 60px;
    }

    .footer-container {
        /* 内容の左右位置は、ヘッダー・本文と同じ共通コンテナで揃える。 */
        text-align: center;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        gap: 8px 24px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .footer-links a {
        display: inline-flex;
        align-items: center;
        min-height: 44px;
        white-space: nowrap;
        color: white;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }

    .footer-links a:hover {
        text-decoration: underline;
        opacity: 0.9;
    }

    .copyright {
        font-size: 13px;
        opacity: 0.9;
    }
    /* フッター ここまで */
</style>

<footer class="site-footer">
    <div class="container footer-container">
        <div class="footer-links">
            <a href="/site/terms">利用規約</a>
            <a href="/site/privacypolicy">プライバシーポリシー</a>
            <a href="/site/contact">お問い合わせ</a>
        </div>
        <p class="copyright">© {{ date('Y') }} ナウポン</p>
    </div>
</footer>
