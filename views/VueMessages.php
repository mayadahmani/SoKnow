<?php
// views/VueMessages.php

class VueMessages extends Vue {

    protected $titre = "Messages - SoKnow";

    protected function afficherContenu($donnees) {
        $this->titre = __('msg_page_title');
        $conversations  = $donnees['conversations']  ?? [];
        $messages       = $donnees['messages']       ?? [];
        $activeContact  = $donnees['active_contact'] ?? null;
        $searchQuery    = $donnees['search_query']   ?? '';

        ?>

        <div class="messages-page">

            <!-- ══ LEFT: Conversation list ══ -->
            <aside class="conv-sidebar">

                <form class="conv-search-wrap" method="GET" action="index.php">
                    <input type="hidden" name="page" value="messages">
                    <svg class="conv-search-icon" width="15" height="15" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="q" class="conv-search"
                           value="<?php echo htmlspecialchars($searchQuery); ?>"
                           placeholder="<?php echo htmlspecialchars(__('msg_search_placeholder')); ?>">
                </form>

                <ul class="conv-list">
                    <?php if (empty($conversations)): ?>
                        <li class="conv-empty">
                            <?php echo htmlspecialchars($searchQuery !== '' ? __('msg_no_contacts_found') : __('msg_no_conversations')); ?>
                        </li>
                    <?php else: ?>
                        <?php foreach ($conversations as $conv): ?>
                            <li class="conv-item <?php echo !empty($conv['active']) ? 'is-active' : ''; ?>"
                                data-conv-id="<?php echo (int)$conv['id']; ?>">
                                <div class="conv-avatar-wrap">
                                    <img src="<?php echo htmlspecialchars($conv['avatar']); ?>"
                                         alt="<?php echo htmlspecialchars($conv['name']); ?>"
                                         class="conv-avatar">
                                    <?php if (!empty($conv['online'])): ?>
                                        <span class="online-dot"></span>
                                    <?php endif; ?>
                                </div>
                                <div class="conv-info">
                                    <div class="conv-name-row">
                                        <span class="conv-name"><?php echo htmlspecialchars($conv['name']); ?></span>
                                        <span class="conv-time"><?php echo htmlspecialchars($conv['time']); ?></span>
                                    </div>
                                    <div class="conv-preview"><?php echo htmlspecialchars($conv['preview']); ?></div>
                                </div>
                                <?php if (!empty($conv['unread_count'])): ?>
                                    <span class="conv-unread"><?php echo (int)$conv['unread_count']; ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

            </aside>

            <!-- ══ CENTER: Chat area ══ -->
            <div class="chat-area">

                <!-- Chat header -->
                <div class="chat-header">
                    <div class="chat-header-left">
                        <div class="chat-avatar-wrap">
                            <?php if ($activeContact): ?>
                                <img src="<?php echo htmlspecialchars($activeContact['avatar']); ?>"
                                     alt="<?php echo htmlspecialchars($activeContact['name']); ?>"
                                     class="chat-avatar">
                            <?php else: ?>
                                <div class="chat-avatar chat-avatar-placeholder">?</div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="chat-contact-name"><?php echo htmlspecialchars($activeContact['name'] ?? __('nav_messages')); ?></div>
                            <?php if (!empty($activeContact['online'])): ?>
                                <div class="chat-status"><?php echo htmlspecialchars(__('msg_status_online')); ?></div>
                            <?php elseif (!$activeContact): ?>
                                <div class="chat-status chat-status-muted"><?php echo htmlspecialchars(__('msg_choose_conversation')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="chat-header-actions">
                        <!-- Phone -->
                        <button class="chat-action-btn" title="<?php echo htmlspecialchars(__('msg_audio_call')); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </button>
                        <!-- Video -->
                        <button class="chat-action-btn" title="<?php echo htmlspecialchars(__('msg_video_call')); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="23 7 16 12 23 17 23 7"/>
                                <rect x="1" y="5" width="15" height="14" rx="2"/>
                            </svg>
                        </button>
                        <!-- Info -->
                        <button class="chat-action-btn" title="<?php echo htmlspecialchars(__('msg_info')); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Messages feed -->
                <div class="chat-feed" id="chatFeed">
                    <?php if (empty($activeContact)): ?>
                        <div class="chat-empty"><?php echo htmlspecialchars(__('msg_select_conversation')); ?></div>
                    <?php elseif (empty($messages)): ?>
                        <div class="chat-empty"><?php echo htmlspecialchars(__('msg_no_messages_yet')); ?></div>
                    <?php else: ?>
                    <?php foreach ($messages as $msg): ?>

                        <?php
                        // Parse post preview from initial help proposal messages
                        $postPreview = null;
                        $displayText = $msg['text'];
                        if (!empty($msg['is_initial']) && preg_match('/^\[post_preview:(\{.*?\})\]\s*/s', $msg['text'], $pvMatch)) {
                            $decoded = json_decode($pvMatch[1], true);
                            if ($decoded && isset($decoded['post_id'])) {
                                $postPreview = $decoded;
                            }
                            $displayText = trim(substr($msg['text'], strlen($pvMatch[0])));
                        }
                        ?>

                        <?php if ($msg['type'] === 'sent'): ?>
                            <div class="msg-row msg-sent">
                                <?php if ($postPreview): ?>
                                    <a href="index.php?page=dashboard#post-<?php echo (int)$postPreview['post_id']; ?>" class="msg-post-card">
                                        <div class="msg-post-card-header">
                                            <span class="msg-post-card-icon">📋</span>
                                            <span class="msg-post-card-label"><?php echo htmlspecialchars(__('dash_re_post')); ?></span>
                                            <span class="msg-post-card-arrow">→</span>
                                        </div>
                                        <div class="msg-post-card-body"><?php echo htmlspecialchars($postPreview['body'] ?? ''); ?></div>
                                        <?php if (!empty($postPreview['tags'])): ?>
                                            <div class="msg-post-card-tags"><?php echo htmlspecialchars($postPreview['tags']); ?></div>
                                        <?php endif; ?>
                                    </a>
                                <?php endif; ?>
                                <div class="bubble bubble-sent">
                                    <?php echo nl2br(htmlspecialchars($displayText)); ?>
                                </div>
                                <div class="msg-meta msg-meta-sent">
                                    <?php echo htmlspecialchars($msg['time']); ?>
                                    <?php if (!empty($msg['read'])): ?>
                                        <span class="read-tick">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php elseif ($msg['type'] === 'received'): ?>
                            <div class="msg-row msg-received">
                                <?php if ($postPreview): ?>
                                    <a href="index.php?page=dashboard#post-<?php echo (int)$postPreview['post_id']; ?>" class="msg-post-card">
                                        <div class="msg-post-card-header">
                                            <span class="msg-post-card-icon">📋</span>
                                            <span class="msg-post-card-label"><?php echo htmlspecialchars(__('dash_re_post')); ?></span>
                                            <span class="msg-post-card-arrow">→</span>
                                        </div>
                                        <div class="msg-post-card-body"><?php echo htmlspecialchars($postPreview['body'] ?? ''); ?></div>
                                        <?php if (!empty($postPreview['tags'])): ?>
                                            <div class="msg-post-card-tags"><?php echo htmlspecialchars($postPreview['tags']); ?></div>
                                        <?php endif; ?>
                                    </a>
                                <?php endif; ?>
                                <div class="bubble bubble-received">
                                    <?php echo nl2br(htmlspecialchars($displayText)); ?>
                                </div>
                                <div class="msg-meta msg-meta-received">
                                    <?php echo htmlspecialchars($msg['time']); ?>
                                </div>
                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Message input -->
                <?php if ($activeContact): ?>
                <form class="chat-input-bar" method="POST" action="index.php?page=messages&action=send">
                    <input type="hidden" name="conv_id" value="<?php echo (int)$activeContact['id']; ?>">
                    <button type="button" class="input-attach-btn" title="<?php echo htmlspecialchars(__('msg_attachment')); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                        </svg>
                    </button>
                    <input type="text" name="message" class="chat-input"
                              placeholder="<?php echo htmlspecialchars(__('msg_write_message')); ?>" autocomplete="off" required>
                          <button type="submit" class="chat-send-btn" title="<?php echo htmlspecialchars(__('msg_send')); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </form>
                <?php endif; ?>

            </div><!-- /.chat-area -->

        </div><!-- /.messages-page -->

        <script>
        (function () {
            // Auto-scroll chat to bottom on load
            var feed = document.getElementById('chatFeed');
            if (feed) feed.scrollTop = feed.scrollHeight;

            // Conversation switching (client-side highlight; server handles data)
            document.querySelectorAll('.conv-item').forEach(function (item) {
                item.addEventListener('click', function () {
                    document.querySelectorAll('.conv-item').forEach(function (i) {
                        i.classList.remove('is-active');
                    });
                    item.classList.add('is-active');
                    var id = item.getAttribute('data-conv-id');
                    window.location.href = 'index.php?page=messages&conv=' + id;
                });
            });

            // Send on Enter (not Shift+Enter)
            var input = document.querySelector('.chat-input');
            if (input) {
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        if (input.value.trim()) {
                            input.closest('form').submit();
                        }
                    }
                });
            }
        })();
        </script>

        <?php
    }
}
?>