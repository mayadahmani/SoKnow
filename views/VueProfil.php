<?php
// views/VueProfil.php

class VueProfil extends Vue {

    protected $titre = "SoKnow";

    public function __construct() {
        $this->titre = __('profile_title');
    }

    protected function afficherContenu($donnees) {
        // Extraction des données ou valeurs par défaut
        $user = $donnees['user'] ?? [];
        $user_skills = $donnees['user_skills'] ?? [];
        $user_badges = $donnees['user_badges'] ?? [];
        $all_skills = $donnees['all_skills'] ?? [];
        $languages = $donnees['languages'] ?? [];
        $impact = $donnees['impact'] ?? ['helped' => 0, 'rating' => 0];
        
        $avatarUrl = !empty($user['avatar_url']) ? $user['avatar_url'] : 'assets/img/default-avatar.png';
        $bannerUrl = !empty($user['banner_url']) ? $user['banner_url'] : '';
        $csrfToken = $_SESSION['csrf_token'] ?? '';
        ?>

        <link rel="stylesheet" href="assets/css/viewProfile.css" />

        <div class="page-wrapper">

            <div class="profile-card">
                <div class="profile-banner" id="bannerEl" style="<?= $bannerUrl ? "background-image:url('$bannerUrl'); background-size:cover;" : "" ?>">
                    <button type="button" class="btn-modifier" data-modal="modal-modifier">
                        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.333 2a1.885 1.885 0 0 1 2.667 2.667L5.417 13.25 2 14l.75-3.417L11.333 2Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?= __('profile_modify') ?>
                    </button>
                </div>

                <div class="profile-info-row">
                    <div style="display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;">
                        <div class="avatar-wrapper">
                            <img id="mainAvatarImg" src="<?= htmlspecialchars($avatarUrl) ?>" alt="Avatar de <?= htmlspecialchars($user['first_name'] ?? 'moi') ?>" />
                        </div>
                        <div class="profile-meta">
                            <div class="profile-name"><?= htmlspecialchars($user['first_name'] ?? '') ?> <?= htmlspecialchars($user['last_name'] ?? '') ?> 👋</div>
                            <div class="profile-tagline">
                                <span>
                                    <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 13.5V12a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3v1.5M8 9A3.5 3.5 0 1 0 8 2a3.5 3.5 0 0 0 0 7Z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <?= ($user['user_type'] ?? '') === 'student' ? __('profile_student') : __('profile_senior') ?>
                                </span>
                                <span>
                                    <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 1.5A4.5 4.5 0 0 1 12.5 6c0 3-4.5 8.5-4.5 8.5S3.5 9 3.5 6A4.5 4.5 0 0 1 8 1.5Z" stroke="#f97316" stroke-width="1.3"/>
                                        <circle cx="8" cy="6" r="1.5" stroke="#f97316" stroke-width="1.3"/>
                                    </svg>
                                    <?= htmlspecialchars($user['location_name'] ?? __('profile_not_located')) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-edit-profile" data-modal="modal-edit">
                        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.333 2a1.885 1.885 0 0 1 2.667 2.667L5.417 13.25 2 14l.75-3.417L11.333 2Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?= __('profile_edit') ?>
                    </button>
                </div>
            </div>

            <div class="two-col">
                <div style="display:flex;flex-direction:column;gap:20px;">
                    <div class="card">
                        <div class="card-title"><?= __('profile_about') ?></div>
                        <p class="about-text" id="profileBio">
                            <?= nl2br(htmlspecialchars($user['bio'] ?? __('profile_no_bio'))) ?>
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-title"><?= __('profile_skills') ?></div>
                        <div class="skills-tags" id="profileSkillsDisplay">
                            <?php foreach ($user_skills as $skill): ?>
                                <span class="skill-tag">#<?= htmlspecialchars(strtoupper($skill['name_fr'])) ?></span>
                            <?php endforeach; ?>
                            <?php if(empty($user_skills)): ?> <span style="color:#94a3b8; font-size:13px;"><?= __('profile_no_skills') ?></span> <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:20px;">
                    <div class="card">
                        <div class="card-title"><?= __('profile_impact') ?></div>
                        <div class="impact-grid">
                            <div class="impact-stat">
                                <span class="impact-number"><?= (int)$impact['helped'] ?></span>
                                <span class="impact-label"><?= __('profile_helped') ?></span>
                            </div>
                            <div class="impact-stat">
                                <span class="impact-number"><?= number_format($impact['rating'], 1) ?></span>
                                <span class="impact-label"><?= __('profile_rating') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title"><?= __('profile_badges') ?></div>
                        <?php foreach ($user_badges as $badge): ?>
                            <div class="badge-item gold">
                                <div class="badge-icon"><?= $badge['icon'] ?? '🏅' ?></div>
                                <div>
                                    <span class="badge-text-name"><?= htmlspecialchars($badge['name']) ?></span>
                                    <span class="badge-text-desc"><?= htmlspecialchars($badge['description']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if(empty($user_badges)): ?> <p style="color:#94a3b8; font-size:13px;"><?= __('profile_no_badges') ?></p> <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-modifier" class="modal-overlay" role="dialog" aria-modal="true">
            <div class="modal">
                <div class="modal-header">
                    <span class="modal-title"><?= __('profile_edit_photos') ?></span>
                    <button type="button" class="modal-close" data-close>&#x2715;</button>
                </div>
                <form action="index.php?page=update_media" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <div class="modal-body">
                        <div class="avatar-preview-row">
                            <img class="avatar-preview-img" id="avatarPreviewThumb" src="<?= htmlspecialchars($avatarUrl) ?>" />
                            <div class="avatar-preview-info">
                                <strong><?= htmlspecialchars($user['first_name'] ?? '') ?></strong>
                                <p><?= __('profile_photo_visible') ?></p>
                            </div>
                        </div>
                        <div class="field">
                            <label class="form-label"><?= __('profile_avatar_label') ?></label>
                            <div class="upload-zone" id="avatarZone">
                                <input type="file" name="avatar" accept="image/*" data-zone="avatarZone" data-mirror="mainAvatarImg" data-mirror-type="img" />
                                <div class="upload-zone-text"><?= __('profile_avatar_change') ?></div>
                            </div>
                        </div>
                        <div class="field" style="margin-top:20px;">
                            <label class="form-label"><?= __('profile_banner_label') ?></label>
                            <div class="upload-zone" id="bannerZone">
                                <input type="file" name="banner" accept="image/*" data-zone="bannerZone" data-mirror="bannerEl" data-mirror-type="bg" />
                                <div class="upload-zone-text"><?= __('profile_banner_change') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-close><?= __('profile_cancel') ?></button>
                        <button type="submit" class="btn-primary"><?= __('profile_save') ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div id="modal-edit" class="modal-overlay" role="dialog" aria-modal="true">
            <div class="modal modal-wide">
                <div class="modal-header">
                    <span class="modal-title"><?= __('profile_edit') ?></span>
                    <button type="button" class="modal-close" data-close>&#x2715;</button>
                </div>
                <form action="index.php?page=update_profile" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <div class="modal-body">
                        <div class="form-section-title"><?= __('profile_identity') ?></div>
                        <div class="form-grid">
                            <div class="field">
                                <label class="form-label"><?= __('profile_first_name') ?></label>
                                <input class="form-input" type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label class="form-label"><?= __('profile_last_name') ?></label>
                                <input class="form-input" type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                            </div>
                            <div class="field span-2">
                                <label class="form-label"><?= __('profile_bio') ?></label>
                                <textarea class="form-textarea" name="bio" maxlength="1000"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="form-section-title"><?= __('profile_location_langs') ?></div>
                        <div class="form-grid">
                            <div class="field span-2">
                                <label class="form-label"><?= __('profile_city') ?></label>
                                <input class="form-input" type="text" name="location_name" value="<?= htmlspecialchars($user['location_name'] ?? '') ?>" required>
                            </div>
                            <div class="field">
                                <label class="form-label"><?= __('profile_interface_lang') ?></label>
                                <select class="form-select" name="preferred_lang">
                                    <?php foreach ($languages as $l): ?>
                                        <option value="<?= $l['id'] ?>" <?= ($user['preferred_lang'] ?? 0) == $l['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($l['label']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field">
                                <label class="form-label"><?= __('profile_spoken_langs') ?></label>
                                <input class="form-input" type="text" name="spoken_languages" value="<?= htmlspecialchars($user['spoken_languages'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-section-title"><?= __('profile_skills_section') ?></div>
                        <div class="skills-check-grid" id="skillsCheckGrid">
                            <?php 
                            $user_skill_ids = array_column($user_skills, 'id');
                            foreach ($all_skills as $skill): 
                                $checked = in_array($skill['id'], $user_skill_ids) ? 'checked' : '';
                            ?>
                                <div class="skill-check-item">
                                    <input type="checkbox" id="sk_<?= $skill['id'] ?>" name="skills[]" value="<?= $skill['id'] ?>" <?= $checked ?>>
                                    <label class="skill-check-label" for="sk_<?= $skill['id'] ?>">#<?= htmlspecialchars(strtoupper($skill['name_fr'])) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-close><?= __('profile_cancel') ?></button>
                        <button type="submit" class="btn-primary"><?= __('profile_save_changes') ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="toast" id="toast"><span id="toastMsg"></span></div>

        <script>
            // Logique JS simplifiée pour les modals et previews
            (function() {
                const openModal = (id) => {
                    document.getElementById(id).classList.add('is-open');
                    document.body.style.overflow = 'hidden';
                };
                const closeModal = (overlay) => {
                    overlay.classList.remove('is-open');
                    document.body.style.overflow = '';
                };

                document.querySelectorAll('[data-modal]').forEach(btn => {
                    btn.onclick = () => openModal(btn.dataset.modal);
                });

                document.querySelectorAll('[data-close], .modal-overlay').forEach(el => {
                    el.onclick = (e) => { if(e.target === el || el.hasAttribute('data-close')) closeModal(el.closest('.modal-overlay')); };
                });

                // Preview images
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    input.onchange = () => {
                        const file = input.files[0];
                        if(file) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const mirror = document.getElementById(input.dataset.mirror);
                                if(input.dataset.mirrorType === 'img') mirror.src = e.target.result;
                                else mirror.style.backgroundImage = `url(${e.target.result})`;
                            };
                            reader.readAsDataURL(file);
                        }
                    };
                });
            })();
        </script>

        <?php
    }
}