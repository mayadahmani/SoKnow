<?php
// views/VueAccueil.php

class VueAccueil extends Vue {
    protected $titre = "SoKnow - L'entraide technologique intergénérationnelle";

    protected function afficherContenu($donnees) {
        ?>
        <div class="home-wrapper">
            <section class="hero-section">
                <div class="container hero-container">
                    <div class="hero-text">
                        <div class="hero-badge">
                            <span>✓</span> N°1 de l'entraide intergénérationnelle
                        </div>
                        <h1>L'entraide technologique professionnelle et humaine</h1>
                        <p>Rejoignez la première communauté intergénérationnelle qui connecte les natifs du numérique aux seniors pour un accompagnement technologique bienveillant et structuré.</p>
                    </div>
                    <div class="hero-video">
                        <div class="play-btn">▶</div>
                        <p>Vidéo de présentation (À venir)</p>
                    </div>
                </div>
            </section>

            <section class="features-section">
                <div class="container text-center">
                    <h2>L'alliance parfaite entre réseau pro et<br>forum d'entraide</h2>
                    <p class="features-subtitle">SoKnow réinvente le support technique en valorisant les compétences des jeunes<br>tout en offrant un accompagnement de qualité aux seniors.</p>

                    <div class="features-grid">
                        <div class="feature-card">
                            <img src="assets/img/question.png" alt="Questions">
                            <h3>Questions & Réponses expertes</h3>
                            <p>Un système de forum structuré pour poser vos questions et obtenir des réponses claires, validées par la communauté.</p>
                        </div>
                        <div class="feature-card">
                            <img src="assets/img/pro.png" alt="Profil">
                            <h3>Profil Professionnel</h3>
                            <p>Valorisez votre aide. Chaque intervention réussie enrichit votre profil avec des badges de compétences reconnus.</p>
                        </div>
                        <div class="feature-card">
                            <img src="assets/img/local.png" alt="Mentorat">
                            <h3>Mentorat Local</h3>
                            <p>Trouvez de l'aide près de chez vous ou organisez des sessions vidéo sécurisées avec nos mentors certifiés.</p>
                        </div>
                    </div>

                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=register" class="btn-primary btn-large">Rejoindre la communauté maintenant →</a>
                    <?php else: ?>
                        <a href="index.php?page=dashboard" class="btn-primary btn-large">Accéder à mon tableau de bord →</a>
                    <?php endif; ?>
                </div>
            </section>

            <section class="events-section">
                <div class="container">
                    <div class="events-header">
                        <div class="events-title-area">
                            <h2>Événements à venir</h2>
                            <p>Découvrez nos prochains ateliers et rencontres en ligne ou près de chez vous.</p>
                        </div>
                        <a href="index.php?page=agenda" class="btn-outline">Voir plus →</a>
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