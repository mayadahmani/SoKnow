<?php
// views/VueCgu.php

class VueCGU extends Vue {
    
    protected $titre = "SoKnow";

    protected function afficherContenu($donnees) {
        $this->titre = __('cgu_page_title');
        ?>
        <div class="container" style="max-width: 800px; margin: 60px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); font-family: 'Inter', sans-serif;">
            
            <h1 style="color: #4F2EE8; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 30px; font-size: 28px;">
                <?= __('cgu_heading') ?>
            </h1>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('cgu_preamble_title') ?></h3>
                <p style="color: #666; line-height: 1.6;"><?= __('cgu_preamble_text') ?></p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('cgu_art1_title') ?></h3>
                <p style="color: #666; line-height: 1.6;"><?= __('cgu_art1_text') ?></p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('cgu_art2_title') ?></h3>
                <p style="color: #666; line-height: 1.6;"><?= __('cgu_art2_intro') ?></p>
                <ul style="color: #666; line-height: 1.6; margin-top: 10px;">
                    <li><?= __('cgu_art2_age') ?></li>
                    <li><?= __('cgu_art2_minor') ?></li>
                </ul>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('cgu_art3_title') ?></h3>
                <p style="color: #666; line-height: 1.6;"><?= __('cgu_art3_text') ?></p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('cgu_art4_title') ?></h3>
                <p style="color: #666; line-height: 1.6;"><?= __('cgu_art4_text') ?></p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('cgu_art5_title') ?></h3>
                <p style="color: #666; line-height: 1.6;"><?= __('cgu_art5_text') ?></p>
            </div>

            <div style="margin-bottom: 0;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;"><?= __('cgu_art6_title') ?></h3>
                <p style="color: #666; line-height: 1.6; background: #f8f8ff; padding: 15px; border-left: 4px solid #4F2EE8;"><?= __('cgu_art6_text') ?></p>
            </div>

        </div>
        <?php
    }
}
?>