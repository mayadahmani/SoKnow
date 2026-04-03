<?php
// views/VueAccueil.php

class VueAccueil extends Vue {
    protected $titre = "SoKnow";

    public function __construct() {
        $this->titre = __('home_title');
    }

    protected function afficherContenu($donnees) {
        ?>
        <div class="home-wrapper">
            <section class="hero-section">
                <div class="container hero-container">
                    <div class="hero-text">
                        <div class="hero-badge">
                            <span>✓</span> <?= __('hero_badge') ?>
                        </div>
                        <h1><?= __('hero_title') ?></h1>
                        <p><?= __('hero_desc') ?></p>
                    </div>
                    <div class="hero-video">
                        <div class="play-btn">▶</div>
                        <p><?= __('hero_video') ?></p>
                    </div>
                </div>
            </section>

            <section class="features-section">
                <div class="container text-center">
                    <h2><?= __('feat_title') ?></h2>
                    <p class="features-subtitle"><?= __('feat_desc') ?></p>

                    <div class="features-grid">
                        <div class="feature-card">
                            <img src="assets/img/question.png" alt="Questions">
                            <h3><?= __('feat_1_title') ?></h3>
                            <p><?= __('feat_1_desc') ?></p>
                        </div>
                        <div class="feature-card">
                            <img src="assets/img/pro.png" alt="Profil">
                            <h3><?= __('feat_2_title') ?></h3>
                            <p><?= __('feat_2_desc') ?></p>
                        </div>
                        <div class="feature-card">
                            <img src="assets/img/local.png" alt="Mentorat">
                            <h3><?= __('feat_3_title') ?></h3>
                            <p><?= __('feat_3_desc') ?></p>
                        </div>
                    </div>

                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=register" class="btn-primary btn-large"><?= __('hero_btn_join') ?></a>
                    <?php else: ?>
                        <a href="index.php?page=dashboard" class="btn-primary btn-large"><?= __('nav_dashboard') ?> →</a>
                    <?php endif; ?>
                </div>
            </section>

            <section class="events-section">
                <div class="container">
                    <div class="events-header">
                        <div class="events-title-area">
                            <h2><?= __('event_title') ?></h2>
                            <p><?= __('event_desc') ?></p>
                        </div>
                        <a href="index.php?page=agenda" class="btn-outline"><?= __('event_btn') ?></a>
                    </div>

                    <div class="events-grid">
                        <div class="event-card">
                            <div class="event-tag"><img src="assets/img/Icon.png" alt="Icon"> 12 Avril - En ligne</div>
                            <h4>Atelier : Maîtriser son Smartphone</h4>
                            <p>Apprenez les bases pour naviguer, télécharger des applications et sécuriser votre appareil.</p>
                            <div class="event-meta"><img src="assets/img/Icon.png" alt="Icon"> 12 participants inscrits</div>
                        </div>

                        <div class="event-card">
                            <div class="event-tag"><img src="assets/img/Icon.png" alt="Icon"> 15 Avril - En ligne</div>
                            <h4>Rencontre intergénérationnelle</h4>
                            <p>Un moment d'échange convivial autour d'un café pour répondre à toutes vos questions tech.</p>
                            <div class="event-meta"><img src="assets/img/Icon.png" alt="Icon"> 8 places restantes</div>
                        </div>

                        <div class="event-card">
                            <div class="event-tag"><img src="assets/img/Icon.png" alt="Icon"> 18 Avril - En ligne</div>
                            <h4>Webinaire : Sécurité sur Internet</h4>
                            <p>Les bonnes pratiques pour repérer les arnaques et naviguer sur internet en toute sécurité.</p>
                            <div class="event-meta"><img src="assets/img/Icon.png" alt="Icon"> 25 participants inscrits</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php
    }
}
?>