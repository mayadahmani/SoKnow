<?php
// views/VueMentionsLegales.php

class VueMentionsLegales extends Vue {
    
    protected $titre = "SoKnow";

    protected function afficherContenu($donnees) {
        ?>
        <div class="container" style="max-width: 800px; margin: 60px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); font-family: 'Inter', sans-serif;">
            
            <h1 style="color: #4F2EE8; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 40px; font-size: 28px;">
                <?= __('legal_title') ?> - SoKnow
            </h1>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;"><?= __('legal_editor_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    <?= __('legal_editor_text') ?>
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;"><?= __('legal_host_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    <?= __('legal_host_text') ?>
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;"><?= __('legal_prop_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    <?= __('legal_prop_text') ?>
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;"><?= __('legal_warn_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px; background: #fff5f5; padding: 15px; border-left: 4px solid #ff4d4f; border-radius: 4px;">
                    <?= __('legal_warn_text') ?>
                </p>
            </div>

        </div>
        <?php
    }
}
?>