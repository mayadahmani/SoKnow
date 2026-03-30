<?php
// views/VueLogin.php

class VueLogin extends Vue {
    
    protected $titre = "Se connecter - SoKnow";

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
                        <h1 class="form-card-title">Se connecter</h1>
                        <p class="form-card-subtitle">Heureux de vous revoir parmi nous.</p>
                    </div>
                    
                    <?php if ($erreur): ?>
                        <div class="alert-error"><?php echo htmlspecialchars($erreur); ?></div>
                    <?php endif; ?>
                    
                   <form method="POST" action="index.php?page=login">

                        <div class="form-group">
                            <label class="form-label" for="login-email">
                                Adresse e-mail professionnelle ou personnelle
                            </label>
                            <input class="form-input" type="email" id="login-email" name="email" placeholder="votre@email.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="login-password">
                                Mot de passe (8 caractères min.)
                            </label>
                            <input class="form-input" type="password" id="login-password" name="password" placeholder="••••••••" minlength="8" required>
                        </div>

                        <button type="submit" class="btn-primary">
                            Se connecter
                        </button>

                    </form>

                    <p class="form-footer">
                        Nouveau sur SoKnow ?
                        <a href="index.php?page=register">S'inscrire</a>
                    </p>
                </div>
            </div>
        </main>
        <?php
    }
}
?>