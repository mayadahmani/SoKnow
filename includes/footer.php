<footer style="background: #fff; padding: 60px 0 20px 0; margin-top: 100px; border-top: 1px solid #eee;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 50px; margin-bottom: 40px;">
            
            <div>
                <h3 style="color: #4F2EE8; margin-top: 0;">SoKnow</h3>
                <p style="color: #666; max-width: 300px; line-height: 1.6;">
                    <?= $lang['footer_desc'] ?? 'Le premier réseau social d\'entraide technologique intergénérationnelle.' ?>
                </p>
                <p style="color: #999; font-size: 12px; margin-top: 15px;">
                    En partenariat avec l'IUT de Bobigny (USPN), UT (Albanie) et VNU (Vietnam).
                </p>
            </div>
            
            <div>
                <h4 style="color: #333; margin-top: 0;"><?= $lang['footer_links'] ?? 'Liens rapides' ?></h4>
                <ul style="list-style: none; padding: 0; line-height: 2.5;">
                    <li><a href="index.php?page=login" style="text-decoration: none; color: #666; transition: 0.2s;" onmouseover="this.style.color='#4F2EE8'" onmouseout="this.style.color='#666'"><?= $lang['nav_login'] ?? 'Se connecter' ?></a></li>
                    <li><a href="index.php?page=register" style="text-decoration: none; color: #666; transition: 0.2s;" onmouseover="this.style.color='#4F2EE8'" onmouseout="this.style.color='#666'"><?= $lang['nav_register'] ?? 'S\'inscrire' ?></a></li>
                    <li><a href="index.php?page=agenda" style="text-decoration: none; color: #666; transition: 0.2s;" onmouseover="this.style.color='#4F2EE8'" onmouseout="this.style.color='#666'"><?= $lang['footer_events'] ?? 'Événements à venir' ?></a></li>
                </ul>
            </div>
            
            <div>
                <h4 style="color: #333; margin-top: 0;"><?= $lang['footer_contact'] ?? 'Nous contacter' ?></h4>
                <ul style="list-style: none; padding: 0; line-height: 2.5; color: #666;">
                    <li>📧 hello@soknow.com</li>
                    <li>📍 Projet International MMI</li>
                    <li>👩‍💻 Équipe Technique (Lead: Maya)</li>
                </ul>
            </div>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; border-top: 1px solid #eee; padding-top: 25px; font-size: 13px; color: #999; gap: 20px;">
            
            <div>
                © 2026 SoKnow. <?= $lang['footer_rights'] ?? 'Tous droits réservés.' ?>
            </div>
            
            <div style="display: flex; gap: 25px;">
                <a href="index.php?page=mentions-legales" style="color: #999; text-decoration: none; transition: 0.2s;" onmouseover="this.style.color='#4F2EE8'" onmouseout="this.style.color='#999'"><?= $lang['footer_legal'] ?? 'Mentions légales' ?></a>
                
                <a href="index.php?page=cgu" style="color: #999; text-decoration: none; transition: 0.2s;" onmouseover="this.style.color='#4F2EE8'" onmouseout="this.style.color='#999'"><?= $lang['footer_terms'] ?? 'Conditions d\'utilisation' ?></a>
                
                <a href="index.php?page=confidentialite" style="color: #999; text-decoration: none; transition: 0.2s;" onmouseover="this.style.color='#4F2EE8'" onmouseout="this.style.color='#999'"><?= $lang['footer_privacy'] ?? 'Politique de confidentialité' ?></a>
            </div>
            
        </div>
    </div>
</footer>
