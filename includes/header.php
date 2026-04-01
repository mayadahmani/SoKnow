<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/messages.css">
    <link rel="stylesheet" href="assets/css/ViewProfile.css">
    <title>SoKnow - Entraide Intergénérationnelle</title>
</head>
<body style="margin: 0; background-color: #F8F8FF; font-family: 'Inter', sans-serif;">
    <header style="padding: 15px 0; background: white; border-bottom: 1px solid #eee; position: relative; z-index: 1000;">
        <style>
            :root {
                --neon-color: #4F2EE8;
                --neon-glow: 0 0 10px rgba(79, 46, 232, 0.4);
                --text-dark: #666;
            }
            .lang-switcher-container { position: relative; display: inline-block; font-family: 'Inter', sans-serif; }
            .lang-switcher-btn { background: #f4f4f9; border: 1px solid #eee; color: var(--text-dark); padding: 8px 15px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); outline: none; }
            .lang-switcher-btn:hover { border-color: var(--neon-color); box-shadow: var(--neon-glow); color: var(--neon-color); transform: translateY(-1px); }
            .lang-chevron { transition: transform 0.3s ease; stroke: currentColor; }
            .lang-dropdown { position: absolute; top: calc(100% + 8px); right: 0; background: white; border: 1px solid #eee; border-radius: 8px; min-width: 140px; list-style: none; padding: 6px; margin: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.1); opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.2s ease; }
            .lang-dropdown.open { opacity: 1; visibility: visible; transform: translateY(0); border-color: var(--neon-color); }
            .lang-dropdown li a { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: var(--text-dark); text-decoration: none; font-size: 13px; border-radius: 5px; transition: all 0.2s ease; }
            .lang-dropdown li a:hover { background: rgba(79, 46, 232, 0.05); color: var(--neon-color); padding-left: 15px; }
            .lang-dropdown li.selected a { background: rgba(79, 46, 232, 0.1); color: var(--neon-color); font-weight: bold; }
        </style>

        <div class="container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            
            <a href="index.php?page=home" style="text-decoration: none; display: flex; align-items: center;">
                <img src="assets/img/logo.png" alt="Logo SoKnow" style="height: 40px; width: auto;">
            </a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php 
                    // Gestion dynamique du menu actif
                    $page_actuelle = $_GET['page'] ?? 'home'; 
                    $style_actif = "color: #4F2EE8; font-weight: bold; border-bottom: 2px solid #4F2EE8; padding-bottom: 5px; text-decoration: none;";
                    $style_inactif = "color: #666; text-decoration: none; font-weight: 500;";
                ?>
                <nav style="display: flex; gap: 30px;">
                    <a href="index.php?page=dashboard" style="<?= $page_actuelle == 'dashboard' ? $style_actif : $style_inactif ?>"><?= __('nav_dashboard') ?></a>
                    <a href="index.php?page=map" style="<?= $page_actuelle == 'map' ? $style_actif : $style_inactif ?>"><?= __('nav_map') ?></a>
                    <a href="index.php?page=agenda" style="<?= $page_actuelle == 'agenda' ? $style_actif : $style_inactif ?>"><?= __('nav_calendar') ?></a>
                    <a href="index.php?page=messages" style="<?= $page_actuelle == 'messages' ? $style_actif : $style_inactif ?>"><?= __('nav_messages') ?></a>
                </nav>
            <?php endif; ?>

            <div style="display: flex; align-items: center; gap: 20px;">
                
                <div class="lang-switcher-container">
                    <?php 
                        $current_lang = $_SESSION['lang'] ?? 'en';
                        $current_page = $_GET['page'] ?? 'home'; 

                        $langs = [
                            'en' => ['label' => 'EN', 'flag' => '🇬🇧'],
                            'fr' => ['label' => 'FR', 'flag' => '🇫🇷'],
                            'vi' => ['label' => 'VI', 'flag' => '🇻🇳'],
                            'sq' => ['label' => 'SQ', 'flag' => '🇦🇱']
                        ];
                    ?>
                    <button class="lang-switcher-btn" id="langBtn" style="font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif;">
    <span style="font-size: 1.2em; line-height: 1;"><?= $langs[$current_lang]['flag'] ?></span>
    <span style="margin-left: 5px;"><?= $langs[$current_lang]['label'] ?></span>
    <svg class="lang-chevron" width="10" height="6" viewBox="0 0 10 6" fill="none" style="margin-left: 8px;">
        <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>
                    
                    <ul class="lang-dropdown" id="langDropdown">
    <li class="<?= $current_lang == 'en' ? 'selected' : '' ?>">
        <a href="index.php?page=<?= $current_page ?>&lang=en">English</a>
    </li>
    <li class="<?= $current_lang == 'fr' ? 'selected' : '' ?>">
        <a href="index.php?page=<?= $current_page ?>&lang=fr">Français</a>
    </li>
    <li class="<?= $current_lang == 'vi' ? 'selected' : '' ?>">
        <a href="index.php?page=<?= $current_page ?>&lang=vi">Tiếng Việt</a>
    </li>
    <li class="<?= $current_lang == 'sq' ? 'selected' : '' ?>">
        <a href="index.php?page=<?= $current_page ?>&lang=sq">Shqip</a>
    </li>
</ul>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <a href="index.php?page=profile" title="<?= __('nav_profile') ?>" style="width: 35px; height: 35px; border-radius: 50%; background-color: #E6E6E6; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 2px solid transparent; text-decoration: none; transition: 0.2s;" onmouseover="this.style.borderColor='#4F2EE8'" onmouseout="this.style.borderColor='transparent'">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" fill="#A0A0A0"/>
                                <path d="M10 12C6.66667 12 0 13.6667 0 17V20H20V17C20 13.6667 13.3333 12 10 12Z" fill="#A0A0A0"/>
                            </svg>
                        </a>
                        <a href="index.php?page=logout" style="background: #4F2EE8; color: white; padding: 7px 15px; border-radius: 8px; font-weight: bold; text-decoration: none; font-size: 14px;"><?= __('nav_logout') ?></a>
                    </div>
                <?php else: ?>
                    <div style="display: flex; align-items: center; gap: 15px; padding-left: 15px; border-left: 1px solid #eee;">
                        <a href="index.php?page=login" style="text-decoration: none; color: #131313; font-weight: 600;"><?= __('nav_login') ?></a>
                        <a href="index.php?page=register" style="background: #4F2EE8; color: white; padding: 10px 20px; border-radius: 8px; font-weight: bold; text-decoration: none;"><?= __('nav_register') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const langBtn = document.getElementById('langBtn');
                const langDropdown = document.getElementById('langDropdown');

                if(langBtn && langDropdown) {
                    langBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        langDropdown.classList.toggle('open');
                        const chevron = this.querySelector('.lang-chevron');
                        chevron.style.transform = langDropdown.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
                    });

                    document.addEventListener('click', function() {
                        langDropdown.classList.remove('open');
                        langBtn.querySelector('.lang-chevron').style.transform = 'rotate(0deg)';
                    });
                }
            });
        </script>
    </header>