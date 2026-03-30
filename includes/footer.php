<footer class="sk-footer">
        <div class="container">
            <div class="sk-footer-grid">
                <div class="sk-footer-brand">
                    <h3 style="color: var(--primary);">SoKnow</h3>
                    <p style="color: var(--text-muted); max-width: 300px;"><?= __('footer_desc') ?></p>
                </div>
                <div class="sk-footer-links">
                    <h4><?= __('footer_links') ?></h4>
                    <ul style="list-style: none; padding: 0; line-height: 2;">
                        <li><a href="index.php?page=login" style="text-decoration: none; color: var(--text-muted);"><?= __('nav_login') ?></a></li>
                        <li><a href="index.php?page=register" style="text-decoration: none; color: var(--text-muted);"><?= __('nav_register') ?></a></li>
                        <li><a href="index.php?page=agenda" style="text-decoration: none; color: var(--text-muted);"><?= __('footer_events') ?></a></li>
                    </ul>
                </div>
                <div class="sk-footer-contact">
                    <h4><?= __('footer_contact') ?></h4>
                    <ul style="list-style: none; padding: 0; line-height: 2; color: var(--text-muted);">
                        <li>hello@soknow.com</li>
                        <li><?= __('footer_terms') ?></li>
                        <li><?= __('footer_privacy') ?></li>
                    </ul>
                </div>
            </div>
            <div style="text-align: center; border-top: 1px solid #eee; padding-top: 20px; font-size: 14px; color: #999;">
                © 2026 SoKnow. <?= __('footer_rights') ?>
            </div>
        </div>
    </footer>
</body>
</html>