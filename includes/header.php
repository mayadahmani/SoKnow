<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>SoKnow - Entraide Intergénérationnelle</title>
</head>
<body style="margin: 0; background-color: #F8F8FF; font-family: 'Inter', sans-serif;">
    <header style="padding: 15px 0; background: white; border-bottom: 1px solid #eee;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            
            <a href="index.php?page=home" style="font-size: 24px; font-weight: 900; color: #4F2EE8; text-decoration: none;">SoKnow</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <nav style="display: flex; gap: 30px;">
                    <a href="index.php?page=dashboard" style="color: #4F2EE8; font-weight: bold; border-bottom: 2px solid #4F2EE8; padding-bottom: 5px; text-decoration: none;">Tableau de bord</a>
                    <a href="index.php?page=map" style="color: #666; text-decoration: none; font-weight: 500;">Annuaire & Carte</a>
                    <a href="index.php?page=agenda" style="color: #666; text-decoration: none; font-weight: 500;">Mon Calendrier</a>
                    <a href="index.php?page=messages" style="color: #666; text-decoration: none; font-weight: 500;">Messages</a>
                </nav>
            <?php endif; ?>

            <div style="display: flex; align-items: center; gap: 20px;">
                
                <select onchange="location = this.value;" style="border: none; background: none; cursor: pointer; color: #666; font-weight: 500; outline: none;">
                    <option value="index.php?lang=fr" <?php echo ($_SESSION['lang'] ?? '') == 'fr' ? 'selected' : ''; ?>>FR 🇫🇷</option>
                    <option value="index.php?lang=vi" <?php echo ($_SESSION['lang'] ?? '') == 'vi' ? 'selected' : ''; ?>>VI 🇻🇳</option>
                    <option value="index.php?lang=al" <?php echo ($_SESSION['lang'] ?? '') == 'al' ? 'selected' : ''; ?>>AL 🇦🇱</option>
                </select>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div style="display: flex; align-items: center; gap: 15px; margin-left: 20px;">
    
   

    <a href="index.php?page=profile" title="Mon Profil" style="width: 35px; height: 35px; border-radius: 50%; background-color: #E6E6E6; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 2px solid transparent; text-decoration: none; transition: 0.2s;" onmouseover="this.style.borderColor='#4F2EE8'" onmouseout="this.style.borderColor='transparent'">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" fill="#A0A0A0"/>
            <path d="M10 12C6.66667 12 0 13.6667 0 17V20H20V17C20 13.6667 13.3333 12 10 12Z" fill="#A0A0A0"/>
        </svg>
    </a>

    <a href="index.php?page=logout" style="background: #4F2EE8; color: white; padding: 5px 10px; border-radius: 8px; font-weight: bold; text-decoration: none;">Déconnexion</a>
</div>
                <?php else: ?>
                    <div style="display: flex; align-items: center; gap: 15px; padding-left: 15px; border-left: 1px solid #eee;">
                        <a href="index.php?page=login" style="text-decoration: none; color: #131313; font-weight: 600;">Se connecter</a>
                        <a href="index.php?page=register" style="background: #4F2EE8; color: white; padding: 10px 20px; border-radius: 8px; font-weight: bold; text-decoration: none;">S'inscrire</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </header>