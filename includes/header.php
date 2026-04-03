<header class="sk-header">
    <div class="sk-header-inner">

        <!-- ── Logo ── -->
        <a href="index.php?page=home" class="sk-logo">
            <img src="assets/img/logo.png" alt="Logo SoKnow" style="height: 40px; width: auto;">
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php $currentPage = $_GET['page'] ?? 'home'; ?>

            <!-- ── Burger (mobile) ── -->
            <button class="sk-burger" id="skBurger" aria-label="Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="6"  x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <!-- ── Overlay ── -->
            <div class="sk-nav-overlay" id="skNavOverlay"></div>

            <!-- ── Slide-in nav (mobile) ── -->
            <nav class="sk-nav" id="skNav">
                <!-- Profile section -->
                <div class="sk-nav-profile">
                    <a href="index.php?page=profile" class="sk-nav-avatar">
                        <img src="<?= htmlspecialchars($_SESSION['avatar_url'] ?? '') ?: 'assets/img/default-avatar.svg' ?>" alt="Avatar" class="sk-nav-avatar-img">
                    </a>
                    <div class="sk-nav-profile-info">
                        <span class="sk-nav-profile-name"><?= htmlspecialchars($_SESSION['user_name'] ?? __('nav_profile')) ?></span>
                        <span class="sk-nav-profile-sub"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></span>
                    </div>
                </div>

                <!-- Menu links -->
                <div class="sk-nav-links">
                    <a href="index.php?page=dashboard" class="sk-nav-item <?= $currentPage === 'dashboard' ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <span><?= __('nav_dashboard') ?></span>
                    </a>
                    <a href="index.php?page=map" class="sk-nav-item <?= $currentPage === 'map' ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
                            <line x1="8" y1="2" x2="8" y2="18"/>
                            <line x1="16" y1="6" x2="16" y2="22"/>
                        </svg>
                        <span><?= __('nav_map') ?></span>
                    </a>
                    <a href="index.php?page=calendar" class="sk-nav-item <?= ($currentPage === 'calendar' || $currentPage === 'agenda') ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8"  y1="2" x2="8"  y2="6"/>
                            <line x1="3"  y1="10" x2="21" y2="10"/>
                        </svg>
                        <span><?= __('nav_calendar') ?></span>
                    </a>
                    <a href="index.php?page=messages" class="sk-nav-item <?= $currentPage === 'messages' ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <span><?= __('nav_messages') ?></span>
                    </a>
                    <a href="index.php?page=profile" class="sk-nav-item <?= $currentPage === 'profile' ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span><?= __('nav_profile') ?></span>
                    </a>
                    <a href="index.php?page=logout" class="sk-nav-item sk-nav-item-logout">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span><?= __('nav_logout') ?></span>
                    </a>
                </div>

                <!-- Close button -->
                <button class="sk-nav-close" id="skNavClose"><?= __('nav_close') ?></button>
            </nav>

        <?php endif; ?>

        <!-- ── Right section: lang switcher + user actions ── -->
        <div class="sk-right">

            <?php
                $currentLang = $_SESSION['lang'] ?? 'fr';
                $currentPage = $_GET['page'] ?? 'home';
                $langs = [
                    'fr' => ['label' => 'FR', 'flag' => 'fr', 'name' => 'Français'],
                    'en' => ['label' => 'EN', 'flag' => 'gb', 'name' => 'English'],
                    'sq' => ['label' => 'SQ', 'flag' => 'al', 'name' => 'Shqip'],
                    'vi' => ['label' => 'VI', 'flag' => 'vn', 'name' => 'Tiếng Việt'],
                ];
            ?>
            <!-- ── Fancy language switcher ── -->
            <div class="sk-lang-wrap">
                <button class="sk-lang-btn" id="skLangBtn" aria-haspopup="listbox" aria-expanded="false">
                    <img class="sk-lang-flag" src="https://flagcdn.com/w40/<?= $langs[$currentLang]['flag'] ?>.png" alt="<?= $langs[$currentLang]['label'] ?>" width="20" height="15">
                    <span class="sk-lang-label"><?= $langs[$currentLang]['label'] ?></span>
                    <svg class="sk-lang-chevron" width="10" height="6" viewBox="0 0 10 6" fill="none">
                        <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <ul class="sk-lang-dropdown" id="skLangDropdown" role="listbox">
                    <?php foreach ($langs as $code => $info): ?>
                        <li role="option" class="<?= $currentLang === $code ? 'sk-lang-selected' : '' ?>">
                            <a href="index.php?page=<?= htmlspecialchars($currentPage) ?>&lang=<?= $code ?>">
                                <img class="sk-lang-flag" src="https://flagcdn.com/w40/<?= $info['flag'] ?>.png" alt="<?= $info['label'] ?>" width="20" height="15">
                                <?= $info['name'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="sk-user-actions">
                    <a href="index.php?page=profile" title="<?= __('nav_profile') ?>" class="sk-profile-link">
                        <img src="<?= htmlspecialchars($_SESSION['avatar_url'] ?? '') ?: 'assets/img/default-avatar.svg' ?>" alt="Avatar" class="sk-profile-avatar-img">
                    </a>
                    <a href="index.php?page=logout" class="sk-logout"><?= __('nav_logout') ?></a>
                </div>
            <?php else: ?>
                <div class="sk-guest">
                    <a href="index.php?page=login"    class="sk-guest-login"><?= __('nav_login') ?></a>
                    <a href="index.php?page=register" class="sk-guest-register"><?= __('nav_register') ?></a>
                </div>
            <?php endif; ?>

        </div><!-- /.sk-right -->
    </div><!-- /.sk-header-inner -->
</header>

<script>
(function () {
    /* ── Burger / slide nav ── */
    var burger  = document.getElementById('skBurger');
    var nav     = document.getElementById('skNav');
    var overlay = document.getElementById('skNavOverlay');
    var close   = document.getElementById('skNavClose');

    function openNav()  { if (nav) nav.classList.add('sk-nav-open');    if (overlay) overlay.classList.add('sk-nav-overlay-visible'); }
    function closeNav() { if (nav) nav.classList.remove('sk-nav-open'); if (overlay) overlay.classList.remove('sk-nav-overlay-visible'); }

    if (burger)  burger.addEventListener('click', openNav);
    if (overlay) overlay.addEventListener('click', closeNav);
    if (close)   close.addEventListener('click', closeNav);

    /* ── Language switcher dropdown ── */
    var langBtn      = document.getElementById('skLangBtn');
    var langDropdown = document.getElementById('skLangDropdown');

    function openLang() {
        langDropdown.classList.add('sk-lang-open');
        langBtn.setAttribute('aria-expanded', 'true');
        langBtn.querySelector('.sk-lang-chevron').style.transform = 'rotate(180deg)';
    }
    function closeLang() {
        langDropdown.classList.remove('sk-lang-open');
        langBtn.setAttribute('aria-expanded', 'false');
        langBtn.querySelector('.sk-lang-chevron').style.transform = 'rotate(0deg)';
    }

    if (langBtn && langDropdown) {
        langBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            langDropdown.classList.contains('sk-lang-open') ? closeLang() : openLang();
        });
        document.addEventListener('click', closeLang);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeLang();
        });
    }
})();
</script>