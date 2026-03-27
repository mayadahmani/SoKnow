<?php
// views/VueMap.php

class VueMap extends Vue {
    
    protected $titre = "Annuaire & Carte - SoKnow";

    protected function afficherContenu($donnees) {
        $membres = $donnees['membres'] ?? [];
        $allSkills = $donnees['allSkills'] ?? []; 
        ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <div class="map-layout">
            <div id="map" class="map-container"></div>

            <div class="map-sidebar">
                <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 24px;">Annuaire & Carte</h2>

                <div id="map-list-panel">
                    <div class="search-filters">
                        <div class="search-box" style="margin-bottom: 12px;">
                            <span class="search-icon">🔍</span>
                            <input type="text" id="search-input" placeholder="Rechercher un nom, ville...">
                        </div>

                        <div style="display: flex; gap: 8px; margin-bottom: 20px;">
                            <select id="filter-skill" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #ddd; background: white; font-size: 13px;">
                                <option value="">Compétences</option>
                                <?php foreach ($allSkills as $skill): ?>
                                    <option value="<?php echo htmlspecialchars($skill['name_fr']); ?>">
                                        <?php echo htmlspecialchars($skill['name_fr']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select id="filter-lang" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #ddd; background: white; font-size: 13px;">
                                <option value="">Langues</option>
                                <option value="Français">Français</option>
                                <option value="Anglais">Anglais</option>
                                <option value="Espagnol">Espagnol</option>
                                <option value="Arabe">Arabe</option>
                            </select>
                        </div>
                    </div>

                    <div class="results-count"><span id="count-text"><?php echo count($membres); ?></span> résultat(s)</div>

                    <div class="member-list">
                        <?php if (empty($membres)): ?>
                            <p style="color: #666; font-size: 14px;">Aucun membre localisé pour le moment.</p>
                        <?php else: ?>
                            <?php foreach ($membres as $index => $membre): ?>
                                <div class="member-card" 
                                     data-skills="<?php echo htmlspecialchars($membre['skills_list'] ?? ''); ?>"
                                     data-langs="<?php echo htmlspecialchars($membre['spoken_languages'] ?? ''); ?>"
                                     onclick="showUserDetail(<?php echo $membre['id']; ?>)">
                                    <div class="member-card-header">
                                        <div class="member-avatar-default" style="width: 50px; height: 50px; border-radius: 50%; background: #f0f2f5; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #4F2EE8; border: 2px solid #e0e0e0; margin-right: 15px;">
                                            👤 
                                        </div>
                                        <div class="member-info">
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                                <h4 class="member-name"><?php echo htmlspecialchars($membre['first_name']); ?></h4>
                                                <span class="role-badge"><?php echo htmlspecialchars($membre['user_type']); ?></span>
                                            </div>
                                            <div class="member-location">📍 <?php echo htmlspecialchars($membre['location_name']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div><div id="map-detail-panel" style="display: none; padding-top: 10px;">
                    <button class="back-to-list-btn" onclick="closeUserDetail()" style="background: none; border: none; color: #4F2EE8; cursor: pointer; font-weight: 600; font-size: 14px; margin-bottom: 20px;">
                        ← Retour à la liste
                    </button>
                    
                    <div class="detail-panel-content">
                        <div class="header" style="display: flex; align-items: center; margin-bottom: 25px;">
                            <div class="member-avatar-default" style="width: 65px; height: 65px; border-radius: 50%; background: #f0f2f5; display: flex; align-items: center; justify-content: center; font-size: 26px; color: #4F2EE8; border: 2px solid #e0e0e0; margin-right: 20px;">
                                👤
                            </div>
                            <div class="member-basics">
                                <h3 id="detail-name" style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0 0 5px 0;">Jane Doe</h3>
                                <span id="detail-role" class="role-badge" style="text-transform: capitalize; background: #eef2fe; color: #4F2EE8; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">senior</span>
                                <div id="detail-location" class="member-location" style="font-size: 13px; color: #666; margin-top: 5px;">📍 Bobigny, France</div>
                            </div>
                        </div>

                        <div class="profile-section" style="margin-bottom: 25px;">
                            <h4 style="font-size: 15px; font-weight: 600; color: #1a1a1a; margin-bottom: 10px;">À propos de moi</h4>
                            <p id="detail-bio" style="font-size: 14px; color: #4a4a4a; line-height: 1.6; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eee;">Aucune description disponible pour le moment.</p>
                        </div>

                        <div class="profile-section" style="margin-bottom: 25px;">
                            <h4 style="font-size: 15px; font-weight: 600; color: #1a1a1a; margin-bottom: 10px;">Langues parlées</h4>
                            <p id="detail-langs" style="font-size: 14px; color: #4a4a4a; font-weight: 500;">Français, Anglais</p>
                        </div>

                        <div class="profile-section" style="margin-bottom: 30px;">
                            <h4 style="font-size: 15px; font-weight: 600; color: #1a1a1a; margin-bottom: 12px;">Compétences</h4>
                            <div id="detail-skills" class="member-skills-badges" style="display: flex; flex-wrap: wrap; gap: 6px;">
                                </div>
                        </div>

                        <button class="contact-btn" style="width: 100%; padding: 12px; background: #4F2EE8; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                            Contacter
                        </button>
                    </div>
                </div></div>
        </div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
       <script>
    // 1. Initialisation Carte
    var map = L.map('map', { zoomControl: true, minZoom: 2, maxZoom: 18 }).setView([46.603354, 1.888334], 5);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap',
        subdomains: 'abcd'
    }).addTo(map);

    // 2. Récupération des données PHP
    const membres = <?php echo json_encode($membres); ?>;
    const markers = [];

    // --- DEBUG : Supprime cette ligne après test ---
    console.log("Données reçues du PHP :", membres); 
    // -----------------------------------------------

   membres.forEach((membre, index) => {
        if (membre.lat && membre.lng) {
            let marker = L.marker([membre.lat, membre.lng]).addTo(map);
            
            // On utilise ici les classes CSS .popup-name et .btn-popup
            marker.bindPopup(`
                <div style="text-align: center;">
                    <span class="popup-name">${membre.first_name} ${membre.last_name || ''}</span>
                    <div style="font-size: 12px; color: #666; margin-bottom: 5px;">
                        ${membre.user_type} • ${membre.location_name}
                    </div>
                    <button onclick="showUserDetail(${membre.id})" class="btn-popup">
                        Voir le profil 
                    </button>
                </div>
            `);
            markers[index] = marker;
        }
    });

    // 3. LA FONCTION QUI AFFICHE LA BIO
    function showUserDetail(userId) {
        // On cherche le membre dans le tableau JS
        const user = membres.find(m => parseInt(m.id) === parseInt(userId));
        
        if (user) {
            console.log("Détails de l'utilisateur cliqué :", user);

            // Mise à jour des textes
            document.getElementById('detail-name').innerText = user.first_name + " " + (user.last_name || "");
            document.getElementById('detail-role').innerText = user.user_type;
            document.getElementById('detail-location').innerText = "📍 " + user.location_name;
            
            // AFFICHAGE DE LA BIO (Ici on utilise bien 'bio')
            const bioElement = document.getElementById('detail-bio');
            if (bioElement) {
                bioElement.innerText = user.bio || "Cet utilisateur n'a pas encore rédigé de bio.";
            }

            // AFFICHAGE DES LANGUES
            const langElement = document.getElementById('detail-langs');
            if (langElement) {
                langElement.innerText = user.spoken_languages || "Non précisé";
            }

            // AFFICHAGE DES COMPÉTENCES (Badges)
            const skillsDiv = document.getElementById('detail-skills');
            skillsDiv.innerHTML = '';
            if (user.skills_list) {
                user.skills_list.split(', ').forEach(skill => {
                    const span = document.createElement('span');
                    span.innerText = skill;
                    span.style = "font-size: 10px; background: #eef2fe; color: #4F2EE8; padding: 4px 10px; border-radius: 12px; margin-right: 5px;";
                    skillsDiv.appendChild(span);
                });
            }

            // Gestion des panneaux
            document.getElementById('map-list-panel').style.display = 'none';
            document.getElementById('map-detail-panel').style.display = 'block';
            
            map.setView([user.lat, user.lng], 13);
        }
    }

    function closeUserDetail() {
        document.getElementById('map-detail-panel').style.display = 'none';
        document.getElementById('map-list-panel').style.display = 'block';
    }

    // 4. LOGIQUE DE FILTRAGE
    function applyFilters() {
        const term = document.getElementById('search-input').value.toLowerCase();
        const selectedSkill = document.getElementById('filter-skill').value.toLowerCase();
        const selectedLang = document.getElementById('filter-lang').value.toLowerCase();
        
        const cards = document.querySelectorAll('.member-card');

        membres.forEach((membre, index) => {
            const skills = (membre.skills_list || "").toLowerCase();
            const langs = (membre.spoken_languages || "").toLowerCase();
            const fullName = (membre.first_name + " " + (membre.last_name || "")).toLowerCase();

            const matchSearch = fullName.includes(term);
            const matchSkill = selectedSkill === "" || skills.includes(selectedSkill);
            const matchLang = selectedLang === "" || langs.includes(selectedLang);

            const isVisible = matchSearch && matchSkill && matchLang;

            cards[index].style.display = isVisible ? "block" : "none";
            
            if (markers[index]) {
                if (isVisible) map.addLayer(markers[index]);
                else map.removeLayer(markers[index]);
            }
        });
    }

    document.getElementById('search-input').addEventListener('input', applyFilters);
    document.getElementById('filter-skill').addEventListener('change', applyFilters);
    document.getElementById('filter-lang').addEventListener('change', applyFilters);
</script>
<style>
    /* Style global du Popup Leaflet */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        padding: 5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .leaflet-popup-content {
        margin: 15px;
        font-family: 'Inter', sans-serif;
    }

    /* Le titre du nom dans le popup */
    .popup-name {
        color: #4F2EE8;
        font-size: 16px;
        font-weight: 700;
        display: block;
        margin-bottom: 4px;
    }

    /* Le bouton "Voir Profil" stylisé */
    .btn-popup {
        display: inline-block;
        width: 100%;
        margin-top: 10px;
        padding: 10px 0;
        background-color: #4F2EE8;
        color: white !important; /* Force le blanc */
        text-align: center;
        text-decoration: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-popup:hover {
        background-color: #3a22b0;
    }

    .map-layout {
        display: flex;
        height: calc(100vh - 80px); /* Ajuste selon la taille de ton header */
        width: 100%;
    }
    .map-container {
        flex: 1;
        height: 100% !important;
        min-height: 500px;
    }
    .map-sidebar {
        width: 350px;
        overflow-y: auto;
        padding: 20px;
        background: white;
        border-left: 1px solid #eee;
    }
    /* Style pour les badges dans la liste */
    .role-badge {
        background: #eef2fe;
        color: #4F2EE8;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 10px;
        text-transform: uppercase;
    }
</style>
        <?php

        
    }
}