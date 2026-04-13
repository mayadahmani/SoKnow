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
                                    <span class="icon-action" id="trigger-image" title="Joindre une image">🖼️</span>
                                    <span class="icon-action" id="trigger-doc"   title="Joindre un document">📎</span>
                                    <span class="icon-action" id="trigger-hashtag" title="Ajouter un hashtag">#️⃣</span>
                                </div>
                                <button type="submit" class="btn-primary btn-small"><?= __('dash_publish') ?></button>
                            </div>
                        </form>
                    </div>
                    <script>
                    (function () {
                        var imgInput = document.getElementById('post-image-input');
                        var docInput = document.getElementById('post-doc-input');
                        var previews = document.getElementById('post-attach-previews');
                        var textarea = document.querySelector('.input-textarea');

                        document.getElementById('trigger-image').addEventListener('click', function () { imgInput.click(); });
                        document.getElementById('trigger-doc').addEventListener('click',   function () { docInput.click(); });
                        document.getElementById('trigger-hashtag').addEventListener('click', function () {
                            var pos = textarea.selectionStart;
                            textarea.value = textarea.value.slice(0, pos) + ' #' + textarea.value.slice(pos);
                            textarea.selectionStart = textarea.selectionEnd = pos + 2;
                            textarea.focus();
                        });

                        function makePill(id, label, onRemove) {
                            var old = document.getElementById(id);
                            if (old) old.remove();
                            var pill = document.createElement('div');
                            pill.id = id;
                            pill.className = 'attach-pill';
                            pill.innerHTML = label + '<button type="button" class="attach-pill-remove" aria-label="Supprimer">&times;</button>';
                            pill.querySelector('.attach-pill-remove').addEventListener('click', onRemove);
                            previews.appendChild(pill);
                        }

                        imgInput.addEventListener('change', function () {
                            if (this.files[0]) {
                                var f = this.files[0];
                                makePill('pill-image', '🖼️ ' + f.name, function () {
                                    imgInput.value = '';
                                    var p = document.getElementById('pill-image');
                                    if (p) p.remove();
                                });
                            }
                        });

                        docInput.addEventListener('change', function () {
                            if (this.files[0]) {
                                var f = this.files[0];
                                makePill('pill-doc', '📎 ' + f.name, function () {
                                    docInput.value = '';
                                    var p = document.getElementById('pill-doc');
                                    if (p) p.remove();
                                });
                            }
                        });
                    })();
                    </script>

                    <form class="search-filter-bar" action="index.php" method="GET">
                        <input type="hidden" name="page" value="dashboard">
                        <div class="search-box">
                            <span class="search-icon">🔍</span>
                            <input type="text" name="q" value="<?= htmlspecialchars($searchQuery); ?>" placeholder="<?= __('dash_search_placeholder') ?>">
                        </div>
                        <button type="submit" class="btn-outline btn-filter">
                            ♈ <?= __('dash_filters') ?>
                        </button>
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

                            // Extract #hashtags from content
                            preg_match_all('/#(\w+)/u', $post['content'], $tagMatches);
                            $hashtags = $tagMatches[1] ?? [];
                            // Strip hashtags from displayed body
                            $body = trim(preg_replace('/#\w+/u', '', $post['content']));
                            ?>
                            <div class="card post-card" id="post-<?= (int)$post['id']; ?>">
                                <div class="post-header">
                                    <img src="https://i.pravatar.cc/150?u=<?= (int)$post['user_id']; ?>" alt="Avatar" class="avatar">
                                    <div>
                                        <h4 class="post-name">
                                            <?= htmlspecialchars($author); ?>
                                            <?php if ($isOwn): ?>
                                                <span class="badge-self"><?= __('dash_me') ?></span>
                                            <?php endif; ?>
                                        </h4>
                                        <div class="post-time"><?= $timeAgo($post['created_at']); ?></div>
                                    </div>
                                </div>

                                <p class="post-text"><?= nl2br(htmlspecialchars($body)); ?></p>

                                <?php if (!empty($post['image_path'])): ?>
                                    <div class="post-attachment-img-wrap">
                                        <img src="<?= htmlspecialchars($post['image_path']); ?>"
                                             alt="Image jointe" class="post-attachment-img">
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($post['doc_path'])): ?>
                                    <?php
                                    $docExtMap = ['pdf'=>'PDF','doc'=>'Word','docx'=>'Word','xls'=>'Excel',
                                                  'xlsx'=>'Excel','ppt'=>'PowerPoint','pptx'=>'PowerPoint','txt'=>'Texte'];
                                    $docExt    = strtolower(pathinfo($post['doc_path'], PATHINFO_EXTENSION));
                                    $docLabel  = $docExtMap[$docExt] ?? 'Document';
                                    ?>
                                    <a href="<?= htmlspecialchars($post['doc_path']); ?>"
                                       class="post-doc-link" target="_blank" rel="noopener noreferrer">
                                        📄 <?= $docLabel; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($hashtags)): ?>
                                    <div class="hashtag-container">
                                        <?php foreach ($hashtags as $tag): ?>
                                                          <a href="index.php?page=dashboard&q=<?= urlencode('#' . $tag); ?>"
                                                              class="hashtag">#<?= htmlspecialchars(strtoupper($tag)); ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!$isOwn): ?>
                                    <div class="post-footer">
                                        <button class="btn-outline btn-offer-help"
                                                data-post-id="<?= (int)$post['id']; ?>"
                                                data-user-id="<?= (int)$post['user_id']; ?>"
                                                data-author="<?= htmlspecialchars($author, ENT_QUOTES); ?>"
                                                data-body="<?= htmlspecialchars(mb_strimwidth($body, 0, 80, '…'), ENT_QUOTES); ?>"
                                                data-tags="<?= htmlspecialchars(implode(' ', array_map(fn($t) => '#'.strtoupper($t), $hashtags)), ENT_QUOTES); ?>">
                                            <?= __('dash_offer_help') ?>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="col-widgets">
                    
                    <div class="card">
                        <h3 class="card-title">📅 <?= __('dash_upcoming_rdv') ?></h3>
                        <?php
                        $privateEvents = array_filter($upcomingEvents, fn($e) => $e['event_type'] !== 'public');
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
                        <h3 class="card-title">📍 <?= __('dash_nearby_members') ?></h3>
                        <div class="mini-map-preview">
                            <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=600&q=80" alt="Carte preview">
                        </div>
                        <a href="index.php?page=map" class="btn-outline btn-full"><?= __('dash_explore_map') ?></a>
                    </div>

                    <div class="card">
                        <h3 class="card-title">📅 <?= __('dash_upcoming_events') ?></h3>
                        <?php if (empty($upcomingEvents)): ?>
                            <p style="color:var(--text-muted);padding:10px 0;text-align:center;"><?= __('dash_no_events') ?></p>
                        <?php else: ?>
                            <?php
                            $monthNames = explode(',', __('cal_months'));
                            foreach (array_slice($upcomingEvents, 0, 5) as $evt):
                                $evtDate = new DateTime($evt['start_datetime']);
                                $monthIdx = (int)$evtDate->format('n') - 1;
                                $monthLabel = mb_strimwidth($monthNames[$monthIdx] ?? $evtDate->format('M'), 0, 4);
                            ?>
                                <div class="widget-event">
                                    <div class="event-date-box">
                                        <div class="event-month"><?= htmlspecialchars($monthLabel); ?></div>
                                        <div class="event-day"><?= $evtDate->format('d'); ?></div>
                                    </div>
                                    <div>
                                        <h4 class="post-name"><?= htmlspecialchars($evt['title']); ?></h4>
                                        <div class="post-time"><?= htmlspecialchars($evt['description'] ?? $evt['event_type']); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- ── OFFER HELP MODAL ── -->
        <div id="offer-help-overlay" class="modal-overlay" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="modal-box">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-title"></h3>
                    <button class="modal-close" id="modal-close-btn" aria-label="Fermer">&times;</button>
                </div>

                <div class="modal-subject-card">
                    <span class="modal-subject-text"></span>
                    <span class="modal-subject-tags"></span>
                </div>

                <form id="offer-help-form" action="index.php?page=dashboard&action=offerHelp" method="POST">
                    <input type="hidden" name="receiver_id" id="modal-receiver-id" value="">
                    <input type="hidden" name="post_id" id="modal-post-id" value="">
                    <input type="hidden" name="post_body" id="modal-post-body" value="">
                    <input type="hidden" name="post_tags" id="modal-post-tags" value="">
                    <label class="modal-label" for="modal-message"><?= __('dash_modal_intro_label') ?></label>
                    <textarea id="modal-message" name="message" class="input-textarea modal-textarea" placeholder="<?= __('dash_modal_placeholder') ?>" required></textarea>

                    <div class="modal-footer">
                        <button type="button" class="modal-cancel" id="modal-cancel-btn"><?= __('dash_modal_cancel') ?></button>
                        <button type="submit" class="btn-primary btn-small" id="modal-send-btn"><?= __('dash_modal_send') ?></button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        (function () {
            var overlay  = document.getElementById('offer-help-overlay');

            // Move to <body> so position:fixed is relative to the viewport,
            // not trapped inside <main class="container">.
            document.body.appendChild(overlay);

            var title    = overlay.querySelector('.modal-title');
            var subjText = overlay.querySelector('.modal-subject-text');
            var subjTags = overlay.querySelector('.modal-subject-tags');
            var msgArea  = document.getElementById('modal-message');
            var receiverInput = document.getElementById('modal-receiver-id');
            var postIdInput   = document.getElementById('modal-post-id');
            var postBodyInput = document.getElementById('modal-post-body');
            var postTagsInput = document.getElementById('modal-post-tags');

            function openModal(btn) {
                var author = btn.dataset.author || '';
                var body   = btn.dataset.body   || '';
                var tags   = btn.dataset.tags   || '';

                receiverInput.value = btn.dataset.userId || '';
                postIdInput.value   = btn.dataset.postId || '';
                postBodyInput.value = body;
                postTagsInput.value = tags;

                title.textContent    = '<?= __('dash_modal_title_prefix') ?> ' + author;
                subjText.textContent = 'Sujet : ' + body;

                subjTags.innerHTML = '';
                tags.split(' ').forEach(function (tag) {
                    if (!tag) return;
                    var span = document.createElement('span');
                    span.className   = 'hashtag';
                    span.textContent = tag;
                    subjTags.appendChild(span);
                });

                msgArea.value = '';
                overlay.setAttribute('aria-hidden', 'false');
                overlay.classList.add('is-open');
                msgArea.focus();
            }

            function closeModal() {
                overlay.setAttribute('aria-hidden', 'true');
                overlay.classList.remove('is-open');
            }

            document.querySelectorAll('.btn-offer-help').forEach(function (btn) {
                btn.addEventListener('click', function () { openModal(btn); });
            });

            document.getElementById('modal-close-btn').addEventListener('click',  closeModal);
            document.getElementById('modal-cancel-btn').addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeModal();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });

            document.getElementById('offer-help-form').addEventListener('submit', function (e) {
                var msg = msgArea.value.trim();
                if (!msg) {
                    e.preventDefault();
                    msgArea.focus();
                    return;
                }
                // Form will submit naturally via POST
            });
        })();
        </script>
        <?php
    }
}
?>