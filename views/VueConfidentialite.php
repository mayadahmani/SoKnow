<?php
// views/VueConfidentialite.php

class VueConfidentialite extends Vue {
    
    protected $titre = "Politique de confidentialité - SoKnow";

    protected function afficherContenu($donnees) {
        ?>
        <div class="container" style="max-width: 800px; margin: 60px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); font-family: 'Inter', sans-serif;">
            
            <h1 style="color: #4F2EE8; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 30px; font-size: 28px;">
                Politique de Confidentialité (RGPD) - SoKnow
            </h1>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">Préambule</h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    La protection de votre vie privée est une priorité absolue pour SoKnow. Cette politique explique quelles données nous collectons, pourquoi nous les utilisons, et comment nous les protégeons conformément au Règlement Général sur la Protection des Données (RGPD).
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;">1. Les données que nous collectons</h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">Pour assurer la mise en relation et le bon fonctionnement de la plateforme, nous collectons uniquement les données strictement nécessaires lors de votre inscription et de votre utilisation :</p>
                <ul style="color: #666; line-height: 1.8; font-size: 15px; margin-top: 10px;">
                    <li><strong>Données d'identification :</strong> Prénom/Pseudo, adresse e-mail, âge (pour vérifier la limite des 15 ans).</li>
                    <li><strong>Données de profil :</strong> Localisation géographique (ville ou code postal pour la carte interactive), langues parlées, et compétences (hashtags).</li>
                    <li><strong>Données d'usage :</strong> Historique des rendez-vous dans l'agenda et logs de connexion.</li>
                </ul>
                <p style="color: #666; font-size: 14px; background: #f4f4f9; padding: 10px; border-radius: 6px; margin-top: 15px;">
                    <em><strong>Note :</strong> Le contenu de la messagerie interne et des appels vidéo reste strictement privé et chiffré.</em>
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;">2. Utilisation de vos données</h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">Vos données ne servent qu'à une seule chose : faire fonctionner SoKnow. Elles sont utilisées pour :</p>
                <ul style="color: #666; line-height: 1.8; font-size: 15px; margin-top: 10px;">
                    <li>Créer et gérer votre compte.</li>
                    <li>Vous afficher sur la carte interactive pour trouver de l'aide à proximité.</li>
                    <li>Sécuriser la plateforme et modérer les comportements abusifs.</li>
                </ul>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">3. Stockage et Sécurité</h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    Vos données sont stockées de manière sécurisée sur les serveurs LAMP de l'IUT de Bobigny. Les mots de passe de tous les utilisateurs sont chiffrés (hachés) dans notre base de données MariaDB. En cas de faille, aucun mot de passe en clair ne pourra être lu.
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">4. Partage et revente des données</h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    SoKnow s'engage fermement à <strong>ne jamais vendre, louer ou céder</strong> vos données personnelles à des tiers (entreprises, annonceurs, réseaux sociaux). Votre confiance est notre socle.
                </p>
            </div>

            <div style="margin-bottom: 35px;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 15px;">5. Vos droits (Droit à l'oubli)</h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">Conformément à la loi informatique et libertés et au RGPD, vous êtes maître de vos données. Vous disposez à tout moment d'un droit :</p>
                <ul style="color: #666; line-height: 1.8; font-size: 15px; margin-top: 10px;">
                    <li><strong>D'accès et de rectification :</strong> Vous pouvez modifier vos informations depuis les paramètres de votre profil.</li>
                    <li><strong>De suppression ("Droit à l'oubli") :</strong> Vous pouvez supprimer définitivement votre compte et l'intégralité de vos données en un seul clic depuis votre espace personnel.</li>
                </ul>
            </div>

            <div style="margin-bottom: 0;">
                <h3 style="color: #131313; font-size: 18px; margin-bottom: 10px;">6. Utilisation des Cookies</h3>
                <p style="color: #666; line-height: 1.8; font-size: 15px;">
                    SoKnow utilise uniquement des "cookies techniques" strictement nécessaires (par exemple, pour vous maintenir connecté à votre session ou mémoriser votre langue). Nous n'utilisons aucun cookie de ciblage publicitaire.
                </p>
            </div>

        </div>
        <?php
    }
}
?>