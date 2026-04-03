<?php
// views/VueRegister2.php

class VueRegister2 extends Vue {
    
    protected $titre = "SoKnow";

    public function __construct() {
        $this->titre = __('reg_step2_title');
    }

    protected function afficherContenu($donnees) {
        ?>
        <link rel="stylesheet" href="style.css">

        <main class="auth-layout">
            <div class="left-panel">
                <div class="left-content">
                    <div class="left-brand">SoKnow</div>
                    <p class="left-quote">
                        "<?= __('login_quote') ?>"
                    </p>
                    <span class="left-cite"><?= __('login_cite') ?></span>
                </div>
            </div>

            <div class="right-panel">
                <div class="form-card">
                    <div class="group">
                        <h1 class="form-card-title"><?= __('reg_step2_heading') ?></h1>
                        <p class="form-card-subtitle"><?= __('reg_step2_subtitle') ?></p>
                    </div>

                    <div class="step-indicator">
                        <div class="step-item"><?= __('reg_step1') ?></div>
                        <div class="step-item active"><?= __('reg_step2') ?></div>
                    </div>

                    <form method="POST" action="index.php?page=register">

                        <p class="role-section-label"><?= __('reg_role_question') ?></p>
                        <div class="role-cards">
                            <label class="role-card selected" id="card-student" onclick="selectRole('student', this)">
                                <input type="radio" name="role" value="student" checked>
                                <div class="role-icon"><img src="assets/img/icon-helper.png" alt="Aider"></div>
                                <div class="role-card-title"><?= __('reg_help_title') ?></div>
                                <div class="role-card-desc"><?= __('reg_help_desc') ?></div>
                            </label>

                            <label class="role-card" id="card-senior" onclick="selectRole('senior', this)">
                                <input type="radio" name="role" value="senior">
                                <div class="role-icon"><img src="assets/img/icon-senior.png" alt="Sénior"></div>
                                <div class="role-card-title"><?= __('reg_need_title') ?></div>
                                <div class="role-card-desc"><?= __('reg_need_desc') ?></div>
                            </label>
                        </div>

                        <div class="form-row">
                           <div class="form-group">
    <label class="form-label"><?= __('reg_languages') ?></label>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd;">
        
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
            <input type="checkbox" name="langs[]" value="Français"> <?= __('reg_lang_fr') ?>
        </label>
        
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
            <input type="checkbox" name="langs[]" value="Anglais"> <?= __('reg_lang_en') ?>
        </label>
        
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
            <input type="checkbox" name="langs[]" value="Albanais"> <?= __('reg_lang_sq') ?>
        </label>
        
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
            <input type="checkbox" name="langs[]" value="Vietnamien"> <?= __('reg_lang_vi') ?>
        </label>
        
    </div>
    <input type="hidden" name="languages" id="final-languages">
</div>
</div>
<div class="form-group" style="margin-top: 25px; margin-bottom: 20px;">
    <label class="form-label" style="font-weight: 600; color: #1a1a1a;"><?= __('reg_location') ?></label>
    <input type="text" name="location" class="form-input" 
           placeholder="<?= __('reg_location_ph') ?>" 
           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 8px;" 
           required>
    <p style="font-size: 11px; color: #888; margin-top: 5px;">📍 <?= __('reg_location_hint') ?></p>
</div>

    <p class="skills-section-label"><?= __('reg_skills') ?></p>
<p style="font-size: 12px; color: #888; margin-bottom: 10px;">
    <?= __('reg_skills_hint') ?>
    <span id="skill-counter" style="font-weight: bold; color: #4F2EE8;">0</span>/5
</p>

<div class="skill-tags" id="skillSuggestions">
    <span class="skill-tag suggestion" onclick="addTag('Smartphone')">+ Smartphone</span>
    <span class="skill-tag suggestion" onclick="addTag('Internet')">+ Internet</span>
    <span class="skill-tag suggestion" onclick="addTag('Ordinateur')">+ Ordinateur</span>
    <span class="skill-tag suggestion" onclick="addTag('Sécurité')">+ Sécurité</span>
    <span class="skill-tag suggestion" onclick="addTag('Emails')">+ Emails</span>
</div>

<div class="custom-skills-box" style="margin-top: 15px; padding: 10px; border: 1px solid #EAEAEA; border-radius: 12px; background: #fff;">
    <div id="selected-tags-container" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
        </div>
    <input type="text" id="custom-skill-input" class="form-input" placeholder="<?= __('reg_skills_ph') ?>" style="border: none; padding: 5px; outline: none; width: 100%;">
</div>

<input type="hidden" name="skills" id="skillsInput">

                        <div class="btn-row">
                            <a href="index.php?page=register" class="btn-outline">
                                <?= __('reg_back') ?>
                            </a>
                            <button type="submit" class="btn-primary"><?= __('reg_finish') ?></button>
                        </div>
                    </form>

                    <p class="form-footer"><?= __('reg_already_member') ?> <a href="index.php?page=login"><?= __('reg_login_link') ?></a></p>
                </div>
            </div>
        </main>
<script src="assets/js/Register.js"></script>
       
        <?php
    }
}
?>