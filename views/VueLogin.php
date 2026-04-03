<?php
// views/VueLogin.php

class VueLogin extends Vue {
    
    protected $titre = "SoKnow";

    public function __construct() {
        $this->titre = __('login_title');
    }

    protected function afficherContenu($donnees) {
        $erreur = $donnees['erreur'] ?? null;
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
                        <h1 class="form-card-title"><?= __('login_heading') ?></h1>
                        <p class="form-card-subtitle"><?= __('login_subtitle') ?></p>
                    </div>
                    
                    <?php if ($erreur): ?>
                        <div class="alert-error"><?php echo htmlspecialchars($erreur); ?></div>
                    <?php endif; ?>
                    
                   <form method="POST" action="index.php?page=login">

                        <div class="form-group">
                            <label class="form-label" for="login-email">
                                <?= __('login_email_label') ?>
                            </label>
                            <input class="form-input" type="email" id="login-email" name="email" placeholder="votre@email.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="login-password">
                                <?= __('login_password_label') ?>
                            </label>
                            <input class="form-input" type="password" id="login-password" name="password" placeholder="••••••••" minlength="8" required>
                        </div>

                        <button type="submit" class="btn-primary">
                            <?= __('login_submit') ?>
                        </button>

                    </form>

                    <p class="form-footer">
                        <?= __('login_new_user') ?>
                        <a href="index.php?page=register"><?= __('login_signup_link') ?></a>
                    </p>
                </div>
            </div>
        </main>
        <?php
    }
}
?>