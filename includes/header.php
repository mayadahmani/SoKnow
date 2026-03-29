<header class="sk-header">
    <div class="sk-header-inner">
        
        <a href="index.php?page=home" class="sk-logo">SoKnow</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php $currentPage = $_GET['page'] ?? 'home'; ?>
            <button class="sk-burger" id="skBurger" aria-label="Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="6"  x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <div class="sk-nav-overlay" id="skNavOverlay"></div>
            <nav class="sk-nav" id="skNav">
                <!-- Profile section -->
                <div class="sk-nav-profile">
                    <a href="index.php?page=profile" class="sk-nav-avatar">
                        <svg width="28" height="28" viewBox="0 0 20 20" fill="none">
                            <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" fill="#A0A0A0"/>
                            <path d="M10 12C6.66667 12 0 13.6667 0 17V20H20V17C20 13.6667 13.3333 12 10 12Z" fill="#A0A0A0"/>
                        </svg>
                    </a>
                    <div class="sk-nav-profile-info">
                        <span class="sk-nav-profile-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur') ?></span>
                        <span class="sk-nav-profile-sub"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></span>
                    </div>
                </div>

                <!-- Menu links -->
                <div class="sk-nav-links">
                    <a href="index.php?page=dashboard" class="sk-nav-item <?= $currentPage === 'dashboard' ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="index.php?page=map" class="sk-nav-item <?= $currentPage === 'map' ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
                        <span>Annuaire & Carte</span>
                    </a>
                    <a href="index.php?page=calendar" class="sk-nav-item <?= ($currentPage === 'calendar' || $currentPage === 'agenda') ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>Mon Calendrier</span>
                    </a>
                    <a href="index.php?page=messages" class="sk-nav-item <?= $currentPage === 'messages' ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>Messages</span>
                    </a>
                    <a href="index.php?page=profile" class="sk-nav-item <?= $currentPage === 'profile' ? 'sk-nav-active' : '' ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Mon Profil</span>
                    </a>
                    <a href="index.php?page=logout" class="sk-nav-item sk-nav-item-logout">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        <span>Déconnexion</span>
                    </a>
                </div>

                <!-- Close button -->
                <button class="sk-nav-close" id="skNavClose">Fermer</button>
            </nav>
        <?php endif; ?>

        <div class="sk-right">
            
            <select onchange="location = this.value;" class="sk-lang">
                <option value="index.php?lang=fr" <?php echo ($_SESSION['lang'] ?? '') == 'fr' ? 'selected' : ''; ?>>FR 🇫🇷</option>
                <option value="index.php?lang=vi" <?php echo ($_SESSION['lang'] ?? '') == 'vi' ? 'selected' : ''; ?>>VI 🇻🇳</option>
                <option value="index.php?lang=al" <?php echo ($_SESSION['lang'] ?? '') == 'al' ? 'selected' : ''; ?>>AL 🇦🇱</option>
            </select>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="sk-user-actions">
                    <a href="index.php?page=profile" title="Mon Profil" class="sk-profile-link">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" fill="#A0A0A0"/>
                            <path d="M10 12C6.66667 12 0 13.6667 0 17V20H20V17C20 13.6667 13.3333 12 10 12Z" fill="#A0A0A0"/>
                        </svg>
                    </a>
                    <a href="index.php?page=logout" class="sk-logout">Déconnexion</a>
                </div>
            <?php else: ?>
                <div class="sk-guest">
                    <a href="index.php?page=login" class="sk-guest-login">Se connecter</a>
                    <a href="index.php?page=register" class="sk-guest-register">S'inscrire</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</header>
<script>
(function(){
    var b = document.getElementById('skBurger');
    var n = document.getElementById('skNav');
    var o = document.getElementById('skNavOverlay');
    var c = document.getElementById('skNavClose');
    function openNav() {
        if (n) n.classList.add('sk-nav-open');
        if (o) o.classList.add('sk-nav-overlay-visible');
    }
    function closeNav() {
        if (n) n.classList.remove('sk-nav-open');
        if (o) o.classList.remove('sk-nav-overlay-visible');
    }
    if (b) b.addEventListener('click', openNav);
    if (o) o.addEventListener('click', closeNav);
    if (c) c.addEventListener('click', closeNav);
})();
</script>