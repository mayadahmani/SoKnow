<?php
// views/VueCgu.php

class VueCGU extends Vue {
    
    protected $titre = "Conditions Générales d'Utilisation - SoKnow";

    protected function afficherContenu($donnees) {
        ?>
        <div class="container" style="max-width: 800px; margin: 60px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); font-family: 'Inter', sans-serif;">
            
            <h1 style="color: #4F2EE8; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 30px; font-size: 28px;">
                Conditions Générales d'Utilisation (CGU) - SoKnow
            </h1>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">Préambule</h3>
                <p style="color: #666; line-height: 1.6;">Bienvenue sur SoKnow. Les présentes Conditions Générales d'Utilisation ont pour but de définir les règles d'accès et d'utilisation de notre plateforme d'entraide informatique intergénérationnelle. En créant un compte, vous acceptez ces règles sans réserve.</p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">Article 1 : Objet du service</h3>
                <p style="color: #666; line-height: 1.6;">SoKnow est une plateforme de mise en relation dont l'objectif exclusif est l'échange de compétences informatiques et technologiques. Elle permet à des utilisateurs (notamment des jeunes et des seniors) de se connecter via une messagerie, une carte interactive et un outil de visioconférence pour s'entraider.</p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">Article 2 : Accès, Inscription et Limite d'Âge</h3>
                <p style="color: #666; line-height: 1.6;">L'accès aux services de SoKnow nécessite la création d'un compte utilisateur.</p>
                <ul style="color: #666; line-height: 1.6; margin-top: 10px;">
                    <li><strong>Limite d'âge :</strong> L'utilisation de la plateforme est strictement réservée aux personnes âgées d'au moins 15 ans.</li>
                    <li><strong>Utilisateurs mineurs :</strong> L'inscription des mineurs (entre 15 et 18 ans) implique obligatoirement l'autorisation préalable de leurs représentants légaux.</li>
                </ul>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">Article 3 : Gratuité et Non-monétisation</h3>
                <p style="color: #666; line-height: 1.6;">Le service SoKnow est 100% gratuit. Il est strictement interdit d'utiliser la plateforme pour proposer des services rémunérés ou réaliser des transactions commerciales.</p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">Article 4 : Charte de Bienveillance</h3>
                <p style="color: #666; line-height: 1.6;">SoKnow repose sur l'empathie et le respect mutuel. Les utilisateurs s'engagent à faire preuve de patience et de pédagogie, et à ne publier aucun contenu injurieux ou hors-sujet.</p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">Article 5 : Protection des Données (RGPD)</h3>
                <p style="color: #666; line-height: 1.6;">Les données collectées servent uniquement au bon fonctionnement de la mise en relation. SoKnow s'engage à ne jamais revendre vos données à des tiers.</p>
            </div>

            <div style="margin-bottom: 0;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">Article 6 : Limitation de Responsabilité</h3>
                <p style="color: #666; line-height: 1.6; background: #f8f8ff; padding: 15px; border-left: 4px solid #4F2EE8;">SoKnow intervient uniquement comme un intermédiaire technique. L'équipe ne saurait être tenue responsable des dommages matériels (pannes, perte de données) suite aux conseils donnés par un utilisateur.</p>
            </div>

        </div>
        <?php
    }
}
?>