<?php
// views/VueRegister1.php

class VueRegister1 extends Vue {
    
    protected $titre = "Inscription (Étape 1) - SoKnow";

    protected function afficherContenu($donnees) {
        $erreur = $donnees['erreur'] ?? null;
        ?>
        <link rel="stylesheet" href="style.css">

        <main class="auth-layout">
            <div class="left-panel">
                <div class="left-content">
                    <div class="left-brand">SoKnow</div>
                    <p class="left-quote">
                        "Grâce à un étudiant de SoKnow, j'ai enfin pu configurer ma
                        tablette pour voir mes petits-enfants en vidéo. Un réseau
                        vraiment bienveillant."
                    </p>
                    <span class="left-cite">- Marie, 72 ans</span>
                </div>
            </div>

            <div class="right-panel">
                <div class="form-card">
                    <div class="group">
                        <h1 class="form-card-title">Bienvenue sur SoKnow</h1>
                        <p class="form-card-subtitle">Rejoignez notre réseau professionnel d'entraide</p>
                    </div>

                    <div class="step-indicator">
                        <div class="step-item active">Étape 1 : Infos</div>
                        <div class="step-item">Étape 2 : Profil</div>
                    </div>
                    
                    <?php if ($erreur): ?>
                        <div class="alert-error"><?php echo htmlspecialchars($erreur); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?page=register">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="first-name">Prénom</label>
                                <input class="form-input" type="text" id="first-name" name="first_name" placeholder="Ex : Jean" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="last-name">Nom</label>
                                <input class="form-input" type="text" id="last-name" name="last_name" placeholder="Ex : Dupont" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="reg-email">Adresse e-mail</label>
                            <input class="form-input" type="email" id="reg-email" name="email" placeholder="jean@exemple.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="reg-password">Mot de passe (8 car. min.)</label>
                            <input class="form-input" type="password" id="reg-password" name="password" placeholder="········" minlength="8" required>
                        </div>
<div class="form-group">
    <label>Parlez-nous de vous (Bio)</label>
    <textarea name="bio" class="form-input" placeholder="Ex: Passionné d'informatique et de jardinage..." style="height: 100px; resize: none;"></textarea>
</div>
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="terms" required>
                            <span>J'accepte les conditions d'utilisation et la politique de confidentialité.</span>
                        </label>

                        <button type="submit" class="btn-primary">
                            Suivant
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </form>

                    <p class="form-footer">
                        Déjà membre ? <a href="index.php?page=login">Se connecter</a>
                    </p>
                </div>
            </div>
        </main>
        <?php
    }
}
?>