<?php
// views/VueProfil.php

class VueProfil extends Vue {
    
    protected $titre = "Mon Profil - SoKnow";

    protected function afficherContenu($donnees) {
        // On récupère les infos de l'utilisateur (soit de la session, soit de la DB via le contrôleur)
        $user = $donnees['user'] ?? $_SESSION;
        $nom_complet = ($user['first_name'] ?? 'Alexandre') . ' ' . ($user['last_name'] ?? '');
        ?>

        <style>
            /* Variables exactes de la maquette */
            :root {
                --bg-color: #F8F8FF;
                --card-bg: #FFFFFF;
                --primary: #4F2EE8;
                --primary-light: #F0EDFF;
                --text-dark: #131313;
                --text-muted: #888888;
                --border-color: #EAEAEA;
                --card-shadow: 0px 4px 20px rgba(0, 0, 0, 0.03);
            }

            .profile-container { background-color: var(--bg-color); padding: 40px 0; min-height: 100vh; }
            .profile-grid { display: flex; gap: 30px; max-width: 1000px; margin: 0 auto; padding: 0 20px; }
            .col-main { flex: 2; display: flex; flex-direction: column; gap: 24px; }
            .col-side { flex: 1; display: flex; flex-direction: column; gap: 24px; }

            .card { background: var(--card-bg); border-radius: 16px; padding: 24px; box-shadow: var(--card-shadow); border: 1px solid rgba(0,0,0,0.02); }
            .card-title { font-size: 18px; font-weight: 800; color: var(--text-dark); margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px; }

            /* En-tête */
            .profile-header { background: var(--card-bg); border-radius: 16px; overflow: hidden; box-shadow: var(--card-shadow); margin-bottom: 24px; max-width: 1000px; margin-left: auto; margin-right: auto; }
            .cover-photo { height: 160px; background: linear-gradient(135deg, #4F2EE8 0%, #8E78FF 100%); position: relative; }
            .profile-info-bar { padding: 20px 30px 30px 30px; display: flex; justify-content: space-between; align-items: flex-end; margin-top: -60px; }
            .profile-avatar { width: 120px; height: 120px; border-radius: 50%; border: 4px solid white; object-fit: cover; background: white; z-index: 2; position: relative; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
            
            .hashtag { background: var(--primary-light); color: var(--primary); padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-right: 8px; margin-bottom: 8px; }
            .stat-box { text-align: center; padding: 15px; background: #F9F9FB; border-radius: 12px; border: 1px solid var(--border-color); }
            .stat-number { font-size: 24px; font-weight: 900; color: var(--primary); margin-bottom: 4px; }
            .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
            
            .btn-edit { background: white; border: 1px solid var(--border-color); color: var(--text-dark); padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; text-decoration: none; display: inline-block; }
            .btn-edit:hover { border-color: var(--primary); color: var(--primary); }
        </style>

        <div class="profile-container">
            
            <div style="padding: 0 20px;">
                <div class="profile-header">
                    <div class="cover-photo">
                        <button style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.2); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; backdrop-filter: blur(5px);">📷 Modifier</button>
                    </div>
                    <div class="profile-info-bar">
                        <div style="display: flex; gap: 20px; align-items: flex-end;">
                            <img src="<?php echo $user['avatar'] ?? 'https://i.pravatar.cc/150?img=11'; ?>" alt="Avatar" class="profile-avatar">
                            <div style="padding-bottom: 5px;">
                                <h1 style="margin: 0; font-size: 28px; font-weight: 900; color: var(--text-dark);">
                                    <?php echo htmlspecialchars($nom_complet); ?>
                                    <span style="font-size: 20px;">👋</span>
                                </h1>
                                <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 15px;">
                                    <?php echo $user['user_type'] == 'senior' ? '👴 Sénior en quête de savoir' : '🎓 Étudiant en Informatique'; ?> • 📍 <?php echo htmlspecialchars($user['location_name'] ?? 'Bobigny, France'); ?>
                                </p>
                            </div>
                        </div>
                        <a href="index.php?page=edit_profile" class="btn-edit">✏️ Éditer le profil</a>
                    </div>
                </div>
            </div>

            <div class="profile-grid">
                
                <div class="col-main">
                    <div class="card">
                        <h3 class="card-title">À propos de moi</h3>
                        <p style="color: #555; line-height: 1.6; font-size: 15px; margin: 0;">
                            <?php echo htmlspecialchars($user['bio'] ?? "Passionné par les nouvelles technologies, j'aime rendre l'informatique accessible à tous. J'ai rejoint SoKnow pour partager mes connaissances et aider les seniors à ne plus avoir peur du numérique !"); ?>
                        </p>
                    </div>

                    <div class="card">
                        <h3 class="card-title">Mes Compétences</h3>
                        <div>
                            <?php if (!empty($donnees['skills'])): ?>
                                <?php foreach ($donnees['skills'] as $skill): ?>
                                    <span class="hashtag">#<?php echo strtoupper(htmlspecialchars($skill)); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="hashtag">#ANDROID</span>
                                <span class="hashtag">#TABLETTE</span>
                                <span class="hashtag">#WIFISETUP</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card">
                        <h3 class="card-title">Dernières interventions</h3>
                        <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <div style="font-weight: 700; font-size: 14px;">Aidé Eleanor & John (Imprimante Wi-Fi)</div>
                                <div style="color: #F59E0B; font-size: 14px;">⭐⭐⭐⭐⭐</div>
                            </div>
                            <p style="color: #666; font-size: 14px; margin: 0; font-style: italic;">"Super patient et très clair dans ses explications."</p>
                        </div>
                    </div>
                </div>

                <div class="col-side">
                    <div class="card">
                        <h3 class="card-title">Impact</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div class="stat-box">
                                <div class="stat-number">14</div>
                                <div class="stat-label">Aidés</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">4.9</div>
                                <div class="stat-label">Note</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <h3 class="card-title">Mes Badges</h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px; background: #FFF9E6; padding: 12px; border-radius: 12px; border: 1px solid #FFE58F;">
                                <div style="font-size: 24px;">🏆</div>
                                <div style="font-weight: 800; font-size: 13px; color: #D97706;">Mentor de l'année</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <h3 class="card-title">Langues</h3>
                        <div style="display: flex; gap: 10px;">
                            <span style="border: 1px solid var(--border-color); padding: 5px 12px; border-radius: 8px; font-size: 13px;">🇫🇷 Français</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}