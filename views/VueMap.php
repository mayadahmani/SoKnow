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
                <h2 style="font-size: 24px; font-weight: 800; color: #4F2EE8; margin-bottom: 24px;"><?= __('map_title') ?></h2>

                <div id="map-list-panel">
                    
                    <div class="search-filters" style="margin-bottom: 25px;">
                        
                        <div style="display: flex; align-items: center; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 15px; margin-bottom: 15px;">
                            <img src="assets/img/search.png" alt="Recherche" style="width: 18px; height: 18px; opacity: 0.5; margin-right: 10px;">
                            <input type="text" id="search-input" placeholder="<?= __('map_search_ph') ?>" style="border: none; background: transparent; outline: none; width: 100%; font-size: 14px; color: #333;">
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <div style="flex: 1;">
                                <label style="display: block; font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;"><?= __('map_lang_filter') ?></label>
                                <select id="filter-lang" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 13px; color: #333; outline: none;">
                                    <option value=""><?= __('map_all') ?></option>
                                    <option value="français"><?= __('map_lang_fr') ?></option>
                                    <option value="anglais"><?= __('map_lang_en') ?></option>
                                    <option value="vietnamien"><?= __('map_lang_vi') ?></option>
                                    <option value="albanais"><?= __('map_lang_sq') ?></option>
                                </select>
                            </div>
                            
                            <div style="flex: 1;">
                                <label style="display: block; font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;"><?= __('map_skill_filter') ?></label>
                                <select id="filter-skill" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 13px; color: #333; outline: none;">
                                    <option value=""><?= __('map_all') ?></option>
                                    <?php foreach ($allSkills as $skill): ?>
                                        <option value="<?= htmlspecialchars($skill['name_fr']); ?>">
                                            <?= htmlspecialchars($skill['name_fr']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 15px;">
                        <span id="count-text"><?= count($membres); ?></span> <?= __('map_results') ?>
                    </div>

                    <div class="member-list" style="display: flex; flex-direction: column; gap: 15px;">
                        <?php if (empty($membres)): ?>
                            <p style="color: #64748b; font-size: 14px; text-align: center; padding: 20px;"><?= __('map_no_members') ?></p>
                        <?php else: ?>
                            <?php foreach ($membres as $index => $membre): 
                                $avatarUrl = !empty($membre['avatar']) ? htmlspecialchars($membre['avatar']) : 'assets/img/default-avatar.svg';
                                $skills = !empty($membre['skills_list']) ? explode(',', $membre['skills_list']) : [];
                            ?>
                                <div class="member-card" 
                                     data-skills="<?= htmlspecialchars($membre['skills_list'] ?? ''); ?>"
                                     data-langs="<?= htmlspecialchars($membre['spoken_languages'] ?? ''); ?>"
                                     onclick="showUserDetail(<?= $membre['id']; ?>)">
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                        <div style="display: flex; gap: 12px;">
                                            <img src="<?= $avatarUrl ?>" alt="Avatar" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 1px solid #e2e8f0; background: #f8fafc;">
                                            <div>
                                                <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;"><?= htmlspecialchars($membre['first_name'] . ' ' . ($membre['last_name'] ?? '')); ?></h4>
                                                
                                                <div style="font-size: 12px; color: #64748b; margin-top: 4px; margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                                                    <img src="assets/img/location.png" alt="Lieu" style="width: 12px; height: 12px;"> 
                                                    <?= htmlspecialchars($membre['location_name']); ?>
                                                </div>
                                                
                                                <div>
                                                    <?php if (($membre['user_type'] ?? '') === 'student'): ?>
                                                        <span style="display: inline-flex; align-items: center; gap: 4px; color: #059669; background: #e7f5ed; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 10px; text-transform: uppercase;">
                                                         <?= __('map_sharing') ?>
                                                        </span>
                                                    <?php elseif (($membre['user_type'] ?? '') === 'senior'): ?>
                                                        <span style="display: inline-flex; align-items: center; gap: 4px; color: #d97706; background: #fff7ed; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 10px; text-transform: uppercase;">
                                                         <?= __('map_seeking') ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($skills)): ?>
                                        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 15px;">
                                            <?php foreach (array_slice($skills, 0, 4) as $skill): ?>
                                                <span style="background: #f4f0ff; color: #4F2EE8; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase;">
                                                    #<?= htmlspecialchars(trim($skill)); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="map-detail-panel" style="display: none;">
                    <button onclick="closeUserDetail()" style="display: flex; align-items: center; gap: 6px; background: none; border: none; color: #4F2EE8; cursor: pointer; font-weight: 600; font-size: 14px; margin-bottom: 20px; padding: 0;">
                        <span style="font-size: 18px; line-height: 1;">←</span> <?= __('map_back') ?>
                    </button>
                    
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img id="detail-avatar" src="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; margin-bottom: 10px;">
                        <h3 id="detail-name" style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 5px 0;">Nom</h3>
                        
                        <div id="detail-role" style="margin-top: 8px;"></div>
                        
                        <div id="detail-location" style="display: flex; align-items: center; justify-content: center; gap: 4px; font-size: 13px; color: #64748b; margin-top: 10px;">
                            <img src="assets/img/location.png" alt="Lieu" style="width: 12px; height: 12px;"> <span>Localisation</span>
                        </div>
                    </div>

                    <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?= __('map_about') ?></h4>
                        <p id="detail-bio" style="font-size: 13px; color: #475569; line-height: 1.6; margin: 0;"></p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?= __('map_languages') ?></h4>
                        <p id="detail-langs" style="font-size: 13px; color: #475569; margin: 0;"></p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?= __('map_skills') ?></h4>
                        <div id="detail-skills" style="display: flex; flex-wrap: wrap; gap: 6px;"></div>
                    </div>

                    <button class="contact-btn" style="width: 100%; padding: 12px; background: #4F2EE8; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.2s;">
                        <?= __('map_send_msg') ?>
                    </button>
                </div>

            </div>
        </div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
        var MAP_LANG = {
            sharing: <?= json_encode(__('map_sharing')) ?>,
            seeking: <?= json_encode(__('map_seeking')) ?>,
            noBio: <?= json_encode(__('map_no_bio')) ?>,
            notSpecified: <?= json_encode(__('map_not_specified')) ?>,
            viewProfile: <?= json_encode(__('map_view_profile')) ?>
        };
        var map = L.map('map', { zoomControl: true, minZoom: 2, maxZoom: 18 }).setView([46.603354, 1.888334], 5);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap', subdomains: 'abcd'
        }).addTo(map);

        const membres = <?php echo json_encode($membres); ?>;
        const markers = [];

        function getRoleBadge(roleType) {
            if (roleType === 'student') {
                return '<span style="display: inline-flex; align-items: center; gap: 4px; color: #059669; background: #e7f5ed; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 10px; text-transform: uppercase;">🤝 ' + MAP_LANG.sharing + '</span>';
            } else if (roleType === 'senior') {
                return '<span style="display: inline-flex; align-items: center; gap: 4px; color: #d97706; background: #fff7ed; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 10px; text-transform: uppercase;">❓ ' + MAP_LANG.seeking + '</span>';
            }
            return '';
        }

        membres.forEach((membre, index) => {
            if (membre.lat && membre.lng) {
                let avatar = membre.avatar ? membre.avatar : 'assets/img/default-avatar.svg';
                
                let customIcon = L.divIcon({
                    className: 'custom-map-marker',
                    html: `<div style="width: 40px; height: 40px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.2); overflow: hidden; background: #f0f2f5;">
                              <img src="${avatar}" style="width: 100%; height: 100%; object-fit: cover;">
                           </div>`,
                    iconSize: [46, 46],
                    iconAnchor: [23, 23]
                });

                let marker = L.marker([membre.lat, membre.lng], {icon: customIcon}).addTo(map);
                
                marker.bindPopup(`
                    <div style="text-align: center; font-family: 'Inter', sans-serif;">
                        <strong style="color:#0f172a; font-size:15px; display:block; margin-bottom:6px;">${membre.first_name} ${membre.last_name || ''}</strong>
                        <div style="margin-bottom: 12px;">${getRoleBadge(membre.user_type)}</div>
                        <button onclick="showUserDetail(${membre.id})" style="background:#4F2EE8; color:white; border:none; padding:8px 12px; border-radius:6px; cursor:pointer; width:100%; font-weight:600; font-size: 12px;">${MAP_LANG.viewProfile}</button>
                    </div>
                `);
                
                marker.on('click', function() {
                    map.setView([membre.lat, membre.lng], 12);
                });

                markers[index] = marker;
            }
        });

        function showUserDetail(userId) {
            const user = membres.find(m => parseInt(m.id) === parseInt(userId));
            if (user) {
                document.getElementById('detail-name').innerText = user.first_name + " " + (user.last_name || "");
                document.getElementById('detail-location').innerHTML = '<img src="assets/img/location.png" style="width: 12px; height: 12px;"> <span>' + user.location_name + '</span>';
                
                document.getElementById('detail-bio').innerText = user.bio || MAP_LANG.noBio;
                document.getElementById('detail-langs').innerText = user.spoken_languages || MAP_LANG.notSpecified;
                document.getElementById('detail-avatar').src = user.avatar ? user.avatar : 'assets/img/default-avatar.svg';
                
                document.getElementById('detail-role').innerHTML = getRoleBadge(user.user_type);

                const skillsDiv = document.getElementById('detail-skills');
                skillsDiv.innerHTML = '';
                if (user.skills_list) {
                    user.skills_list.split(',').forEach(skill => {
                        if(skill.trim() !== '') {
                            const span = document.createElement('span');
                            span.innerText = "#" + skill.trim().toUpperCase();
                            span.style = "background: #f4f0ff; color: #4F2EE8; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 700;";
                            skillsDiv.appendChild(span);
                        }
                    });
                }

                document.getElementById('map-list-panel').style.display = 'none';
                document.getElementById('map-detail-panel').style.display = 'block';
                
                if(user.lat && user.lng) {
                    map.setView([user.lat, user.lng], 13);
                }
            }
        }

        function closeUserDetail() {
            document.getElementById('map-detail-panel').style.display = 'none';
            document.getElementById('map-list-panel').style.display = 'block';
            map.setView([46.603354, 1.888334], 5);
        }

       function applyFilters() {
            const term = document.getElementById('search-input').value.toLowerCase().trim();
            const selectedSkill = document.getElementById('filter-skill').value.toLowerCase().trim();
            const selectedLang = document.getElementById('filter-lang').value.toLowerCase().trim();
            
            const cards = document.querySelectorAll('.member-card');
            let visibleCount = 0;

            membres.forEach((membre, index) => {
                const skills = (membre.skills_list || "").toLowerCase();
                const langs = (membre.spoken_languages || "").toLowerCase();
                const fullName = (membre.first_name + " " + (membre.last_name || "")).toLowerCase();
                const location = (membre.location_name || "").toLowerCase();

                const matchSearch = term === "" || fullName.includes(term) || location.includes(term);
                const matchSkill = selectedSkill === "" || skills.includes(selectedSkill);
                const matchLang = selectedLang === "" || langs.includes(selectedLang);

                const isVisible = matchSearch && matchSkill && matchLang;

                if(cards[index]) {
                    cards[index].style.display = isVisible ? "block" : "none";
                }
                
                if (isVisible) {
                    visibleCount++;
                }

                if (markers[index]) {
                    if (isVisible) {
                        if (!map.hasLayer(markers[index])) {
                            map.addLayer(markers[index]);
                        }
                    } else {
                        if (map.hasLayer(markers[index])) {
                            map.removeLayer(markers[index]);
                        }
                    }
                }
            });

            const countTextElement = document.getElementById('count-text');
            if(countTextElement) {
                countTextElement.innerText = visibleCount;
            }
        }

        document.getElementById('search-input').addEventListener('input', applyFilters);
        document.getElementById('filter-skill').addEventListener('change', applyFilters);
        document.getElementById('filter-lang').addEventListener('change', applyFilters);
        </script>

        <style>
            .map-layout { display: flex; height: calc(100vh - 80px); width: 100%; font-family: 'Inter', sans-serif; }
            .map-container { flex: 1; height: 100% !important; z-index: 1; }
            .map-sidebar { width: 380px; overflow-y: auto; padding: 25px; background: white; border-left: 1px solid #e2e8f0; z-index: 10; box-shadow: -4px 0 15px rgba(0,0,0,0.03); }
            
            .member-card {
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 16px;
                background: white;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .member-card:hover {
                border-color: #4F2EE8;
                box-shadow: 0 4px 12px rgba(79, 46, 232, 0.08);
                transform: translateY(-2px);
            }

            .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
            .custom-map-marker { background: transparent; border: none; }
        </style>
        <?php
    }
}
?>