<?php
// views/VueConfidentialite.php

class VueConfidentialite extends Vue {
    
    protected $titre = "SoKnow";

    protected function afficherContenu($donnees) {
        $this->titre = __('privacy_page_title');
        ?>
        <div class="container" style="max-width: 800px; margin: 60px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); font-family: 'Inter', sans-serif;">
            
            <h1 style="color: #4F2EE8; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 30px; font-size: 28px;">
                <?= __('privacy_heading') ?>
            </h1>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('privacy_preamble_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    <?= __('privacy_preamble_text') ?>
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;"><?= __('privacy_s1_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;"><?= __('privacy_s1_intro') ?></p>
                <ul style="color: #666; line-height: 1.8; font-size: 15px; margin-top: 10px;">
                    <li><?= __('privacy_s1_id') ?></li>
                    <li><?= __('privacy_s1_profile') ?></li>
                    <li><?= __('privacy_s1_usage') ?></li>
                </ul>
                <p style="color: #666; font-size: 14px; background: #f4f4f9; padding: 10px; border-radius: 6px; margin-top: 15px;">
                    <em><strong><?= __('privacy_s1_note') ?></strong></em>
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;"><?= __('privacy_s2_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;"><?= __('privacy_s2_intro') ?></p>
                <ul style="color: #666; line-height: 1.8; font-size: 15px; margin-top: 10px;">
                    <li><?= __('privacy_s2_item1') ?></li>
                    <li><?= __('privacy_s2_item2') ?></li>
                    <li><?= __('privacy_s2_item3') ?></li>
                </ul>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('privacy_s3_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    <?= __('privacy_s3_text') ?>
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('privacy_s4_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    <?= __('privacy_s4_text') ?>
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;"><?= __('privacy_s5_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;"><?= __('privacy_s5_intro') ?></p>
                <ul style="color: #666; line-height: 1.8; font-size: 15px; margin-top: 10px;">
                    <li><?= __('privacy_s5_access') ?></li>
                    <li><?= __('privacy_s5_delete') ?></li>
                </ul>
            </div>

            <div style="margin-bottom: 0;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('privacy_s6_title') ?></h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    <?= __('privacy_s6_text') ?>
                </p>
            </div>

        </div>
        <?php
    }
}
?>