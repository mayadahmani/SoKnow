<?php
// views/VueDashboard.php

class VueDashboard extends Vue {
    
    protected $titre = "Tableau de Bord - SoKnow";

    protected function afficherContenu($donnees) {
        $posts = $donnees['posts'] ?? [];
        $rdv = $donnees['rendez_vous'] ?? [];
        $user = $_SESSION['user'] ?? ['first_name' => 'Utilisateur'];
        ?>

        <div class="dashboard-container">
            <div class="grid-layout">
                
                <div class="col-feed">
                    
                    <div class="card">
                        <h3 class="card-title">Créer une demande</h3>
                        <form action="index.php?action=createPost" method="POST">
                            <textarea name="content" class="input-textarea" placeholder="Décrivez votre problème technique ici..."></textarea>
                            <div class="post-actions-bar">
                                <div class="post-icons">
                                    <span class="icon-action">🖼️</span>
                                    <span class="icon-action">📎</span>
                                    <span class="icon-action">#️⃣</span>
                                </div>
                                <button type="submit" class="btn-primary btn-small">Publier</button>
                            </div>
                        </form>
                    </div>

                    <div class="search-filter-bar">
                        <div class="search-box">
                            <span class="search-icon">🔍</span>
                            <input type="text" placeholder="Chercher un sujet, une personne à aider...">
                        </div>
                        <button class="btn-outline btn-filter">
                            ♈ Filtres
                        </button>
                    </div>

                    <?php if (empty($posts)): ?>
                        <div class="card">
                            <div class="post-header">
                                <img src="https://i.pravatar.cc/150?img=5" alt="Avatar" class="avatar">
                                <div>
                                    <h4 class="post-name">Eleanor & John</h4>
                                    <div class="post-time">Publié il y a 10 min</div>
                                </div>
                            </div>
                            <p class="post-text">
                                Je n'arrive pas à connecter ma nouvelle imprimante sans fil au Wi-Fi. Un étudiant peut-il me guider par appel vidéo ?
                            </p>
                            <div class="hashtag-container">
                                <span class="hashtag">#HARDWARE</span>
                                <span class="hashtag">#WIFISETUP</span>
                            </div>
                            <div class="post-footer">
                                <button class="btn-outline">Proposer son aide</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="card">
                                <div class="post-header">
                                    <img src="<?php echo $post['avatar']; ?>" class="avatar">
                                    <div>
                                        <h4 class="post-name"><?php echo htmlspecialchars($post['author']); ?></h4>
                                        <div class="post-time"><?php echo $post['created_at']; ?></div>
                                    </div>
                                </div>
                                <p class="post-text"><?php echo htmlspecialchars($post['content']); ?></p>
                                <div class="post-footer">
                                    <button class="btn-outline">Proposer son aide</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="col-widgets">
                    
                    <div class="card">
                        <h3 class="card-title">📅 Rendez-vous à venir</h3>
                        <div class="widget-rdv">
                            <div class="rdv-info">
                                <img src="https://i.pravatar.cc/150?img=5" alt="Avatar" class="avatar avatar-small">
                                <div>
                                    <h4 class="post-name">Eleanor & John</h4>
                                    <div class="rdv-time">14:00 - Visio</div>
                                </div>
                            </div>
                            <span class="badge-today">Aujourd'hui</span>
                        </div>
                    </div>

                    <div class="card">
                        <h3 class="card-title">📍 Membres à proximité</h3>
                        <div class="mini-map-preview">
                            <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=600&q=80" alt="Carte preview">
                        </div>
                        <a href="index.php?page=map" class="btn-outline btn-full">Explorer la carte</a>
                    </div>

                    <div class="card">
                        <h3 class="card-title">📅 Événements à venir</h3>
                        <div class="widget-event">
                            <div class="event-date-box">
                                <div class="event-month">Oct</div>
                                <div class="event-day">24</div>
                            </div>
                            <div>
                                <h4 class="post-name">Atelier Cybersécurité</h4>
                                <div class="post-time">Événement en ligne</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }
}
?>