<?php
// views/VueRegister1.php

class VueRegister1 extends Vue {
    
    protected $titre = "SoKnow";

    public function __construct() {
        $this->titre = __('reg_step1_title');
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
                        <h1 class="form-card-title"><?= __('reg_heading') ?></h1>
                        <p class="form-card-subtitle"><?= __('reg_subtitle') ?></p>
                    </div>

                    <div class="step-indicator">
                        <div class="step-item active"><?= __('reg_step1') ?></div>
                        <div class="step-item"><?= __('reg_step2') ?></div>
                    </div>
                    
                    <?php if ($erreur): ?>
                        <div class="alert-error"><?php echo htmlspecialchars($erreur); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?page=register">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="first-name"><?= __('reg_first_name') ?></label>
                                <input class="form-input" type="text" id="first-name" name="first_name" placeholder="<?= __('reg_first_name_ph') ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="last-name"><?= __('reg_last_name') ?></label>
                                <input class="form-input" type="text" id="last-name" name="last_name" placeholder="<?= __('reg_last_name_ph') ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="reg-email"><?= __('reg_email') ?></label>
                            <input class="form-input" type="email" id="reg-email" name="email" placeholder="<?= __('reg_email_ph') ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="reg-password"><?= __('reg_password') ?></label>
                            <input class="form-input" type="password" id="reg-password" name="password" placeholder="········" minlength="8" required>
                        </div>
<div class="form-group">
    <label><?= __('reg_bio_label') ?></label>
    <textarea name="bio" class="form-input" placeholder="<?= __('reg_bio_ph') ?>" style="height: 100px; resize: none;"></textarea>
</div>
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="terms" required>
                            <span><?= __('reg_terms') ?></span>
                        </label>

                        <button type="submit" class="btn-primary">
                            <?= __('reg_next') ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </form>

                    <p class="form-footer">
                        <?= __('reg_already_member') ?> <a href="index.php?page=login"><?= __('reg_login_link') ?></a>
                    </p>
                </div>
            </div>
        </main>
        <?php
    }
}
?>