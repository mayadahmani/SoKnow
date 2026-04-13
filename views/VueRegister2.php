<?php
// views/VueRegister2.php

class VueRegister2 extends Vue {
    
    protected $titre = "Inscription (Étape 2) - SoKnow";

    protected function afficherContenu($donnees) {
        ?>
        <link rel="stylesheet" href="style.css">

        <main class="auth-layout">
            <div class="left-panel">
                <div class="left-content">
                    <div class="left-brand">SoKnow</div>
                    <p class="left-quote">
                        "Grâce à un étudiant de SoKnow, j'ai enfin pu configurer ma tablette pour voir mes petits-enfants en vidéo."
                    </p>
                    <span class="left-cite">- Marie, 72 ans</span>
                </div>
            </div>

            <div class="right-panel">
                <div class="form-card">
                    <div class="group">
                        <h1 class="form-card-title">Presque terminé...</h1>
                        <p class="form-card-subtitle">Personnalisez votre profil pour de meilleurs échanges.</p>
                    </div>

                    <div class="step-indicator">
                        <div class="step-item">Étape 1 : Infos</div>
                        <div class="step-item active">Étape 2 : Profil</div>
                    </div>

                    <form method="POST" action="index.php?page=register">

                        <p class="role-section-label">Comment souhaitez-vous utiliser SoKnow ?</p>
                        <div class="role-cards">
                            <label class="role-card selected" id="card-student" onclick="selectRole('student', this)">
                                <input type="radio" name="role" value="student" checked>
                                <div class="role-icon"><img src="assets/img/icon-helper.png" alt="Aider"></div>
                                <div class="role-card-title">Je veux aider</div>
                                <div class="role-card-desc">Partagez vos compétences tech</div>
                            </label>

                            <label class="role-card" id="card-senior" onclick="selectRole('senior', this)">
                                <input type="radio" name="role" value="senior">
                                <div class="role-icon"><img src="assets/img/icon-senior.png" alt="Sénior"></div>
                                <div class="role-card-title">Besoin d'aide</div>
                                <div class="role-card-desc">Trouvez un mentor patient</div>
                            </label>
                        </div>

                        <div class="form-row">
                           <div class="form-group">
    <label class="form-label">Langues parlées (plusieurs choix possibles)</label>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd;">
        
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
            <input type="checkbox" name="langs[]" value="Français"> Français
        </label>
        
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
            <input type="checkbox" name="langs[]" value="Anglais"> Anglais
        </label>
        
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
            <input type="checkbox" name="langs[]" value="Albanais"> Albanais
        </label>
        
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px;">
            <input type="checkbox" name="langs[]" value="Vietnamien"> Vietnamien
        </label>
        
    </div>
    <input type="hidden" name="languages" id="final-languages">
</div>
</div>
<div class="form-group" style="margin-top: 25px; margin-bottom: 20px;">
    <label class="form-label" style="font-weight: 600; color: #1a1a1a;">Où habitez-vous ? (Ville, Pays)</label>
    <input type="text" name="location" class="form-input" 
           placeholder="Ex: Bobigny, France" 
           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 8px;" 
           required>
    <p style="font-size: 11px; color: #888; margin-top: 5px;">📍 Indispensable pour apparaître sur la carte.</p>
</div>

    <p class="skills-section-label">Vos compétences (5 maximum)</p>
<p style="font-size: 12px; color: #888; margin-bottom: 10px;">
    Cliquez sur nos suggestions ou tapez les vôtres (Appuyez sur Entrée). 
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
    <input type="text" id="custom-skill-input" class="form-input" placeholder="Tapez une autre compétence..." style="border: none; padding: 5px; outline: none; width: 100%;">
</div>

<input type="hidden" name="skills" id="skillsInput">

                        <div class="btn-row">
                            <a href="index.php?page=register" class="btn-outline">
                                Retour
                            </a>
                            <button type="submit" class="btn-primary">Terminer l'inscription</button>
                        </div>
                    </form>

                    <p class="form-footer">Déjà membre ? <a href="index.php?page=login">Se connecter</a></p>
                </div>
            </div>
        </main>
<script src="assets/js/Register.js"></script>
       
        <?php
    }
}
?>