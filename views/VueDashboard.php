<?php
// views/VueDashboard.php

class VueDashboard extends Vue {
    
    protected $titre = "SoKnow";

    protected function afficherContenu($donnees) {
        $posts = $donnees['posts'] ?? [];
        $upcomingEvents = $donnees['upcoming_events'] ?? [];
        $user = $_SESSION['user'] ?? ['first_name' => 'Utilisateur'];
        $searchQuery = $donnees['search_query'] ?? '';
        ?>

        <div class="dashboard-container">
            <div class="grid-layout">
                
                <div class="col-feed">
                    
                    <div class="card">
                        <h3 class="card-title"><?= __('dash_create_request') ?></h3>
                        <form action="index.php?page=dashboard&action=createPost" method="POST" enctype="multipart/form-data">
                            <textarea name="content" class="input-textarea" placeholder="<?= __('dash_describe_problem') ?>" required maxlength="2000"></textarea>
                            <div id="post-attach-previews"></div>
                            <div class="post-actions-bar">
                                <div class="post-icons">
                                    <input type="file" id="post-image-input" name="post_image"
                                           accept="image/jpeg,image/png,image/gif,image/webp"
                                           style="display:none">
                                    <input type="file" id="post-doc-input" name="post_doc"
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.ppt,.pptx"
                                           style="display:none">
                                   <span class="icon-action" id="trigger-image" title="<?= __('dash_attach_image') ?>" style="cursor: pointer;">
    <img src="assets/img//image.png" alt="Image" style="width: 24px; height: 24px;">
</span>

<span class="icon-action" id="trigger-doc" title="<?= __('dash_attach_doc') ?>" style="cursor: pointer;">
    <img src="assets/img/document.png" alt="Document" style="width: 24px; height: 24px;">
</span>

<span class="icon-action" id="trigger-hashtag" title="<?= __('dash_add_hashtag') ?>" style="cursor: pointer;">
    <img src="assets/img/hashtag.png" alt="Hashtag" style="width: 24px; height: 24px;">
</span>
                                </div>
                                <button type="submit" class="btn-primary btn-small"><?= __('dash_publish') ?></button>
                            </div>
                        </form>
                    </div>

                    <form class="search-filter-bar" action="index.php" method="GET" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px; flex-wrap: wrap; width: 100%;">
    <input type="hidden" name="page" value="dashboard">
    
    <div class="search-box" style="display: flex; align-items: center; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 5px 15px; flex-grow: 1; min-width: 200px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        
        <button type="submit" style="background: none; border: none; padding: 0; margin-right: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="<?= __('dash_search_btn') ?>">
            <img src="assets/img/search.png" alt="<?= __('dash_search_btn') ?>" style="width: 20px; height: 20px;">
        </button>
        
        <input type="text" id="live-search-input" name="q" value="<?= htmlspecialchars($searchQuery); ?>" placeholder="<?= __('dash_search_placeholder') ?>" style="border: none; outline: none; background: transparent; width: 100%; height: 35px; font-size: 15px; font-family: inherit; color: #333;">
    </div>
    
    <div style="display: flex; gap: 10px;">
        
        <div style="position: relative; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <select name="author" onchange="this.form.submit()" style="appearance: none; -webkit-appearance: none; background: transparent; border: none; outline: none; height: 47px; padding: 0 35px 0 15px; font-family: inherit; font-size: 14px; color: #4F2EE8; font-weight: 500; cursor: pointer; min-width: 160px;">
                <option value="all" <?= ($donnees['author'] ?? '') === 'all' ? 'selected' : '' ?>><?= __('dash_filter_all_members') ?></option>
                <option value="mine" <?= ($donnees['author'] ?? '') === 'mine' ? 'selected' : '' ?>><?= __('dash_filter_my_posts') ?></option>
            </select>
            <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 10px; color: #4F2EE8;">▼</span>
        </div>

        <div style="position: relative; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <select name="sort" onchange="this.form.submit()" style="appearance: none; -webkit-appearance: none; background: transparent; border: none; outline: none; height: 47px; padding: 0 35px 0 15px; font-family: inherit; font-size: 14px; color: #4F2EE8; font-weight: 500; cursor: pointer; min-width: 150px;">
                <option value="desc" <?= ($donnees['sort'] ?? '') === 'desc' ? 'selected' : '' ?>><?= __('dash_sort_newest') ?></option>
                <option value="asc" <?= ($donnees['sort'] ?? '') === 'asc' ? 'selected' : '' ?>><?= __('dash_sort_oldest') ?></option>
            </select>
            <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 10px; color: #4F2EE8;">▼</span>
        </div>

    </div>
</form>
                    <?php
                    $timeAgo = function($datetime) {
                        $diff = (new DateTime())->diff(new DateTime($datetime));
                        if ($diff->days >= 1)   return __('dash_published_ago') . ' ' . $diff->days . ' ' . __('dash_ago_days');
                        if ($diff->h >= 1)      return __('dash_published_ago') . ' ' . $diff->h . ' ' . __('dash_ago_hours');
                        if ($diff->i >= 1)      return __('dash_published_ago') . ' ' . $diff->i . ' ' . __('dash_ago_min');
                        return __('dash_published_ago') . ' ' . __('dash_few_seconds');
                    };

                    $currentUserId = (int)($_SESSION['user_id'] ?? 0);
                    ?>

                    <?php if (empty($posts)): ?>
                        <div class="card post-empty-state">
                            <p class="post-text" style="text-align:center;color:var(--text-muted);padding:20px 0;"><?= __('dash_no_posts') ?></p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <?php
                            $author = trim(($post['first_name'] ?? '') . ' ' . ($post['last_name'] ?? ''));
                            if ($author === '') {
                                $author = __('msg_user_prefix') . ' #' . (int)$post['user_id'];
                            }
                            $isOwn = ((int)$post['user_id'] === $currentUserId);

                            // Extraction des hashtags
                            preg_match_all('/#(\w+)/u', $post['content'], $tagMatches);
                            $hashtags = $tagMatches[1] ?? [];
                            $body = trim(preg_replace('/#\w+/u', '', $post['content']));
                            ?>
                            
        <div class="card post-card" id="post-<?= (int)$post['id']; ?>">
          <div class="post-header" style="display: flex; align-items: flex-start; justify-content: space-between; width: 100%; margin-bottom: 15px;">
    
    <div style="display: flex; gap: 12px; align-items: center;">
        <?php $avatarUrl = !empty($post['avatar']) ? htmlspecialchars($post['avatar']) : 'assets/img/default-avatar.svg'; ?>
        <img src="<?= $avatarUrl ?>" alt="Avatar" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; background: #f0f2f5; flex-shrink: 0;">
        
        <div style="display: flex; flex-direction: column; gap: 2px;">
            <h4 style="margin: 0; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                <?= htmlspecialchars($author); ?>
                <?php if ($isOwn): ?> 
                    <span style="background: #e7f3ff; color: #1877f2; padding: 2px 8px; border-radius: 6px; font-size: 11px;"><?= __('dash_you') ?></span> 
                <?php endif; ?>
            </h4>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <?php if (($post['user_type'] ?? '') === 'student'): ?>
                    <span style="color: #059669; background: #e7f5ed; padding: 2px 10px; border-radius: 12px; font-weight: 700; font-size: 10px; text-transform: uppercase;">🤝 <?= __('dash_badge_sharing') ?></span>
                <?php elseif (($post['user_type'] ?? '') === 'senior'): ?>
                    <span style="color: #d97706; background: #fff7ed; padding: 2px 10px; border-radius: 12px; font-weight: 700; font-size: 10px; text-transform: uppercase;">❓ <?= __('dash_badge_seeking') ?></span>
                <?php endif; ?>
                <span style="color: #65676b; font-size: 11px; white-space: nowrap;"><?= $timeAgo($post['created_at']) ?></span>
            </div>
        </div>
    </div>

    <?php if ($isOwn): ?>
    <div style="position: relative; margin-left: 10px;">
        <button class="btn-options-trigger" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #65676b; line-height: 1; padding: 0 5px;">⋮</button>
        
        <div class="post-options-menu" style="display: none; position: absolute; right: 0; top: 30px; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); z-index: 9999; min-width: 150px; overflow: hidden;">
            <button class="btn-edit-post" data-post-id="<?= (int)$post['id'] ?>"
               style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; text-decoration: none; color: #1c1e21; font-size: 14px; border-bottom: 1px solid #f0f2f5; background: none; border-left: none; border-right: none; border-top: none; width: 100%; cursor: pointer;"
               onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'">
                <span>✏️</span> <?= __('dash_edit') ?? 'Modifier' ?>
            </button>
            <a href="index.php?page=dashboard&action=deletePost&id=<?= (int)$post['id'] ?>" 
               style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; text-decoration: none; color: #dc3545; font-size: 14px; font-weight: 600;"
               onclick="return confirm('<?= __('dash_confirm_delete') ?? 'Voulez-vous vraiment supprimer ce post ?' ?>');">
                <span>🗑️</span> <?= __('dash_delete') ?? 'Supprimer' ?>
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

                                <div class="post-body-wrap" data-post-id="<?= (int)$post['id'] ?>" data-raw-content="<?= htmlspecialchars($post['content'], ENT_QUOTES) ?>">
                                <p class="post-text"><?= nl2br(htmlspecialchars($body)); ?></p>
                                </div>

                                <?php if (!empty($post['image_path']) && strlen(trim($post['image_path'])) > 5): ?>
                                    <div class="post-attachment-img-wrap" style="margin-top: 15px; border-radius: 8px; overflow: hidden; border: 1px solid #eee;">
                                        <img src="<?= htmlspecialchars($post['image_path']); ?>" 
                                             alt="Image jointe" 
                                             style="max-width: 100%; display: block; height: auto;">
                                    </div>
                                <?php endif; ?>

                            <?php if (!empty($post['doc_path']) && strlen(trim($post['doc_path'])) > 5): ?>
    <div style="margin-top: 10px; background: #f8f9fa; padding: 10px; border-radius: 6px; display: inline-block; border: 1px solid #e2e8f0;">
        <a href="<?= htmlspecialchars($post['doc_path']); ?>" 
           target="_blank" 
           style="text-decoration: none; color: #4F2EE8; display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px;">
           <span style="font-size: 20px;">📄</span> 
           
           <?= __('dash_doc_attached') ?>
           
           <span style="font-size: 11px; color: #64748b; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;">
               <?= strtoupper(pathinfo($post['doc_path'], PATHINFO_EXTENSION)) ?>
           </span>
        </a>
    </div>
<?php endif; ?>

                                <?php if (!empty($hashtags)): ?>
    <div class="hashtag-container" style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; margin-bottom: 15px;">
        <?php foreach ($hashtags as $tag): ?>
            <a href="index.php?page=dashboard&q=<?= urlencode('#' . $tag); ?>" 
               style="background-color: #f4f0ff; color: #4F2EE8; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 800; text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px; transition: background 0.2s;"
               onmouseover="this.style.backgroundColor='#eaddff'" 
               onmouseout="this.style.backgroundColor='#f4f0ff'">
               #<?= htmlspecialchars(strtoupper($tag)); ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

                             <?php if (!$isOwn): ?>
    <?php
    if (($post['user_type'] ?? '') === 'student') {
        // Le post propose une connaissance
        $btnMessageText = __('dash_ask_question');
        $btnRdvText = __('dash_learn_with');
    } 
    elseif (($post['user_type'] ?? '') === 'senior') {
        // Le post est une demande d'aide
        $btnMessageText = __('dash_offer_solution');
        $btnRdvText = __('dash_help_via_rdv');
    }
    ?>
    
    <div class="post-footer" style="display: flex; gap: 10px; margin-top: 15px; border-top: 1px solid #f0f0f0; padding-top: 15px;">
        <a href="index.php?page=messages&user=<?= $post['user_id'] ?>" style="flex: 1; text-align: center; background-color: #f3f4f6; color: #374151; padding: 10px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 13px; transition: 0.3s;">
            <?= $btnMessageText ?>
        </a>
        <a href="index.php?page=agenda&schedule_with=<?= $post['user_id'] ?>" style="flex: 1; text-align: center; background-color: #4F2EE8; color: white; padding: 10px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 13px; transition: 0.3s;">
            <?= $btnRdvText ?>
        </a>
    </div>
<?php endif; ?>
                            </div> <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="col-widgets">
                    
                    <div class="card">
                        <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
    <img src="assets/img/calendar.png" alt="Calendrier" style="width: 20px; height: 20px;">
    <?= __('dash_upcoming_rdv') ?>
</h3>
                        <?php
                        $privateEvents = array_filter($upcomingEvents, function($e) { 
                            return $e['event_type'] !== 'public'; 
                        });
                        $privateEvents = array_slice($privateEvents, 0, 3);
                        ?>
                        <?php if (empty($privateEvents)): ?>
                            <p style="color:var(--text-muted);padding:10px 0;text-align:center;"><?= __('dash_no_rdv') ?></p>
                        <?php else: ?>
                            <?php foreach ($privateEvents as $rdv): ?>
                                <?php
                                $rdvDate = new DateTime($rdv['start_datetime']);
                                $now = new DateTime();
                                $tomorrow = (new DateTime())->modify('+1 day');
                                $isToday = $rdvDate->format('Y-m-d') === $now->format('Y-m-d');
                                $isTomorrow = $rdvDate->format('Y-m-d') === $tomorrow->format('Y-m-d');
                                ?>
                                <div class="widget-rdv">
                                    <div class="rdv-info">
                                        <img src="https://i.pravatar.cc/150?u=<?= (int)$rdv['organizer_id']; ?>" alt="Avatar" class="avatar avatar-small">
                                        <div>
                                            <h4 class="post-name"><?= htmlspecialchars($rdv['title']); ?></h4>
                                            <div class="rdv-time"><?= $rdvDate->format('H:i'); ?> - <?= htmlspecialchars($rdv['first_name'] . ' ' . $rdv['last_name']); ?></div>
                                        </div>
                                    </div>
                                    <?php if ($isToday): ?>
                                        <span class="badge-today"><?= __('dash_today') ?></span>
                                    <?php elseif ($isTomorrow): ?>
                                        <span class="badge-today"><?= __('cal_tomorrow') ?></span>
                                    <?php else: ?>
                                        <span class="badge-today"><?= $rdvDate->format('d/m'); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="card">
                       <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
    <img src="assets/img/location.png" alt="Lieu" style="width: 20px; height: 20px;">
    <?= __('dash_nearby_members') ?>
</h3>
                        <div class="mini-map-preview">
                            <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=600&q=80" alt="Carte preview">
                        </div>
                        <a href="index.php?page=map" class="btn-outline btn-full"><?= __('dash_explore_map') ?></a>
                    </div>

                  <div class="card">
    <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
        <img src="assets/img/calendar.png" alt="Événements" style="width: 20px; height: 20px;">
        <?= __('dash_upcoming_events') ?>
    </h3>
    
    <?php
    // 1. ON FILTRE POUR NE GARDER QUE LES ÉVÉNEMENTS PUBLICS (La plateforme)
    $publicEvents = array_filter($upcomingEvents, function($e) { 
        return ($e['event_type'] ?? '') === 'public'; 
    });
    ?>

    <?php if (empty($publicEvents)): ?>
        <p style="color:var(--text-muted);padding:10px 0;text-align:center;"><?= __('dash_no_events') ?></p>
    <?php else: ?>
        <?php
        $monthNames = explode(',', __('cal_months'));
        // On affiche les 5 prochains événements publics maximum
        foreach (array_slice($publicEvents, 0, 5) as $evt):
            $evtDate = new DateTime($evt['start_datetime']);
            $monthIdx = (int)$evtDate->format('n') - 1;
            $monthLabel = mb_strimwidth($monthNames[$monthIdx] ?? $evtDate->format('M'), 0, 4);
        ?>
            <a href="index.php?page=events&id=<?= $evt['id'] ?? '' ?>" style="text-decoration: none; color: inherit; display: block; border-radius: 8px; transition: background 0.2s; padding: 5px;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                <div class="widget-event" style="margin: 0;">
                    <div class="event-date-box">
                        <div class="event-month"><?= htmlspecialchars($monthLabel); ?></div>
                        <div class="event-day"><?= $evtDate->format('d'); ?></div>
                    </div>
                    <div>
                        <h4 class="post-name" style="color: #4F2EE8;"><?= htmlspecialchars($evt['title']); ?></h4>
                        <div class="post-time"><?= mb_strimwidth(htmlspecialchars($evt['description'] ?? 'Événement SoKnow'), 0, 40, '...'); ?></div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

                </div>
            </div>
        </div>

     <script>
var DASH_LANG = {
    save: <?= json_encode(__('dash_save')) ?>,
    cancel: <?= json_encode(__('dash_cancel')) ?>,
    confirmDelete: <?= json_encode(__('dash_confirm_delete')) ?>
};
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. GESTION DU MENU DES 3 POINTS (Modifier / Supprimer)
    const optionButtons = document.querySelectorAll('.btn-options-trigger');
    
    optionButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const menu = this.nextElementSibling;
            const isVisible = menu.style.display === 'block';
            
            document.querySelectorAll('.post-options-menu').forEach(m => m.style.display = 'none');
            
            if (!isVisible) {
                menu.style.display = 'block';
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.post-options-menu') && !e.target.closest('.btn-options-trigger')) {
            document.querySelectorAll('.post-options-menu').forEach(m => m.style.display = 'none');
        }
    });

    // 1b. INLINE EDIT POST
    document.querySelectorAll('.btn-edit-post').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close the menu
            document.querySelectorAll('.post-options-menu').forEach(function(m) { m.style.display = 'none'; });
            
            var postId = this.getAttribute('data-post-id');
            var wrap = document.querySelector('.post-body-wrap[data-post-id="' + postId + '"]');
            if (!wrap || wrap.querySelector('.edit-form-inline')) return;
            
            var rawContent = wrap.getAttribute('data-raw-content');
            var originalHTML = wrap.innerHTML;
            
            wrap.innerHTML = '<form class="edit-form-inline" method="POST" action="index.php?page=dashboard&action=editPost&id=' + postId + '">' +
                '<textarea name="content" style="width:100%;min-height:80px;border:1.5px solid #4F2EE8;border-radius:10px;padding:12px;font-family:inherit;font-size:14px;resize:vertical;outline:none;">' + rawContent.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</textarea>' +
                '<div style="display:flex;gap:8px;margin-top:10px;justify-content:flex-end;">' +
                '<button type="button" class="btn-cancel-edit" style="padding:8px 18px;border:1px solid #ddd;border-radius:8px;background:#fff;cursor:pointer;font-weight:600;font-size:13px;">' + DASH_LANG.cancel + '</button>' +
                '<button type="submit" style="padding:8px 18px;border:none;border-radius:8px;background:#4F2EE8;color:#fff;cursor:pointer;font-weight:600;font-size:13px;">' + DASH_LANG.save + '</button>' +
                '</div></form>';
            
            var ta = wrap.querySelector('textarea');
            ta.focus();
            ta.setSelectionRange(ta.value.length, ta.value.length);
            
            wrap.querySelector('.btn-cancel-edit').addEventListener('click', function() {
                wrap.innerHTML = originalHTML;
            });
        });
    });

    // 2. RECHERCHE EN DIRECT (Live Search)
    var searchInput = document.getElementById('live-search-input');
    var typingTimer; 
    var doneTypingInterval = 500; 

    if(searchInput) {
        if (searchInput.value.length > 0) {
            searchInput.focus();
            var val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }

        searchInput.addEventListener('input', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                searchInput.form.submit();
            }, doneTypingInterval);
        });
    }

    // 3. GESTION DES PIÈCES JOINTES ET HASHTAGS
    var imgInput = document.getElementById('post-image-input');
    var docInput = document.getElementById('post-doc-input');
    var previews = document.getElementById('post-attach-previews'); // Conteneur des previews
    var textarea = document.querySelector('.input-textarea');

    // Clics sur les icônes
    if(document.getElementById('trigger-image')) document.getElementById('trigger-image').addEventListener('click', function () { imgInput.click(); });
    if(document.getElementById('trigger-doc')) document.getElementById('trigger-doc').addEventListener('click', function () { docInput.click(); });
    if(document.getElementById('trigger-hashtag')) {
        document.getElementById('trigger-hashtag').addEventListener('click', function () {
            var pos = textarea.selectionStart;
            textarea.value = textarea.value.slice(0, pos) + ' #' + textarea.value.slice(pos);
            textarea.focus();
        });
    }

    // Fonction pour créer la petite bulle d'affichage du fichier
    function makePill(id, label, onRemove) {
        var old = document.getElementById(id);
        if (old) old.remove();
        var pill = document.createElement('div');
        pill.id = id;
        pill.className = 'attach-pill'; // Utilise ton CSS existant
        pill.style.display = "inline-flex";
        pill.style.alignItems = "center";
        pill.style.gap = "8px";
        pill.style.background = "#f0f2f5";
        pill.style.padding = "5px 10px";
        pill.style.borderRadius = "20px";
        pill.style.fontSize = "13px";
        pill.style.marginTop = "10px";
        
        pill.innerHTML = label + '<button type="button" class="attach-pill-remove" aria-label="Supprimer" style="background:none; border:none; cursor:pointer; color:red; font-weight:bold;">&times;</button>';
        pill.querySelector('.attach-pill-remove').addEventListener('click', onRemove);
        previews.appendChild(pill);
    }

    // Quand on choisit une image
    if(imgInput) {
        imgInput.addEventListener('change', function () {
            if (this.files[0]) {
                makePill('pill-image', '🖼️ ' + this.files[0].name, function () {
                    imgInput.value = '';
                    var p = document.getElementById('pill-image');
                    if (p) p.remove();
                });
            }
        });
    }

    // Quand on choisit un document
    if(docInput) {
        docInput.addEventListener('change', function () {
            if (this.files[0]) {
                makePill('pill-doc', '📎 ' + this.files[0].name, function () {
                    docInput.value = '';
                    var p = document.getElementById('pill-doc');
                    if (p) p.remove();
                });
            }
        });
    }
});
</script>
        <?php
    }
}
?>