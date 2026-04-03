<?php
// views/VueCalendrier.php
class VueCalendrier extends Vue {
    protected $titre = "SoKnow - Mes Rendez-vous";

    protected function afficherContenu($donnees) {
        $events   = $donnees['events'] ?? [];
        $upcoming = $donnees['upcoming'] ?? [];
        $user     = $_SESSION['user'] ?? ['first_name' => 'Utilisateur'];

        // Build calendar events JSON: "YYYY-M-D" => [{full event data}, ...]
        $calEvents = [];
        $visibilityIcons = ['private' => '🔒', 'shared' => '👥', 'public' => '🌐'];
        foreach ($events as $ev) {
            $ts  = strtotime($ev['start_datetime']);
            $key = date('Y', $ts) . '-' . (int)date('m', $ts) . '-' . (int)date('d', $ts);
            $calEvents[$key][] = [
                'label'       => date('H:i', $ts) . ' ' . mb_strimwidth($ev['title'], 0, 18, '…'),
                'title'       => $ev['title'],
                'description' => $ev['description'] ?? '',
                'start'       => date('d/m/Y H:i', $ts),
                'end'         => date('d/m/Y H:i', strtotime($ev['end_datetime'])),
                'type'        => $ev['event_type'] ?? 'private',
                'type_icon'   => $visibilityIcons[$ev['event_type'] ?? 'private'] ?? '🔒',
                'organizer'   => trim(($ev['first_name'] ?? '') . ' ' . ($ev['last_name'] ?? '')),
            ];
        }
        $calEventsJson = json_encode($calEvents, JSON_UNESCAPED_UNICODE);

        // Build upcoming appointments for sidebar
        $today    = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $rendezVous = [];
        foreach ($upcoming as $ev) {
            $evDate = date('Y-m-d', strtotime($ev['start_datetime']));
            if ($evDate === $today) {
                $quand = 'today';
            } elseif ($evDate === $tomorrow) {
                $quand = 'tomorrow';
            } else {
                $quand = $evDate;
            }
            $rendezVous[] = [
                'titre'       => $ev['title'],
                'heure_debut' => date('H:i', strtotime($ev['start_datetime'])),
                'heure_fin'   => date('H:i', strtotime($ev['end_datetime'])),
                'avec'        => $ev['first_name'] . ' ' . $ev['last_name'],
                'type'        => ($ev['event_type'] ?? 'private') === 'public' ? 'presentiel' : 'visio',
                'quand'       => $quand,
            ];
        }

        // Calendar day/month names from lang
        $calDays = explode(',', __('cal_days'));
        ?>
        <div class="calendrier-container">

            <!-- ── Page header ── -->
            <div class="page-header">
                <div class="page-title">
                    <h1><?= __('cal_title') ?></h1>
                    <p><?= __('cal_subtitle') ?></p>
                </div>
                <button class="btn-primary btn-new" id="openModalBtn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5"  y1="12" x2="19" y2="12"/>
                    </svg>
                    <?= __('cal_new_event') ?>
                </button>
            </div>

            <!-- ── Two-column grid ── -->
            <div class="grid-layout">

                <!-- Calendar -->
                <div class="col-calendar">
                    <div class="card calendar-card">
                        <div class="cal-header">
                            <span class="cal-month" id="calMonthLabel"></span>
                            <div class="cal-nav">
                                <button class="cal-nav-btn" id="prevMonth" aria-label="<?= __('cal_prev_month') ?>">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="15 18 9 12 15 6"/>
                                    </svg>
                                </button>
                                <button class="btn-outline btn-today" id="goToday"><?= __('cal_today') ?></button>
                                <button class="cal-nav-btn" id="nextMonth" aria-label="<?= __('cal_next_month') ?>">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="9 18 15 12 9 6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <table class="cal-grid" id="calGrid">
                            <thead>
                                <tr>
                                    <?php foreach ($calDays as $d): ?><th><?= htmlspecialchars($d) ?></th><?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody id="calBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-widgets">
                    <div class="card">
                        <h3 class="card-title">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8"  y1="2" x2="8"  y2="6"/>
                                <line x1="3"  y1="10" x2="21" y2="10"/>
                            </svg>
                            <?= __('cal_upcoming') ?>
                        </h3>
                        <div class="rdv-list">
                            <?php foreach ($rendezVous as $rdv): ?>
                                <?php
                                if ($rdv['quand'] === 'today') {
                                    $badgeClass = 'badge-today';
                                    $badgeLabel = __('cal_today');
                                } elseif ($rdv['quand'] === 'tomorrow') {
                                    $badgeClass = 'badge-tomorrow';
                                    $badgeLabel = __('cal_tomorrow');
                                } else {
                                    $badgeClass = 'badge-tomorrow';
                                    $badgeLabel = date('d/m', strtotime($rdv['quand']));
                                }
                                $isVisio = ($rdv['type'] === 'visio');
                                ?>
                                <div class="rdv-item">
                                    <div class="rdv-top">
                                        <span class="<?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span>
                                        <span class="rdv-icon">
                                            <?php if ($isVisio): ?>
                                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polygon points="23 7 16 12 23 17 23 7"/>
                                                    <rect x="1" y="5" width="15" height="14" rx="2"/>
                                                </svg>
                                            <?php else: ?>
                                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="9" cy="7" r="4"/>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                                </svg>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="rdv-name"><?php echo htmlspecialchars($rdv['titre']); ?></div>
                                    <div class="rdv-meta">
                                        <div class="rdv-meta-row">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                            <?php echo htmlspecialchars($rdv['heure_debut']); ?> – <?php echo htmlspecialchars($rdv['heure_fin']); ?>
                                        </div>
                                        <div class="rdv-meta-row">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                            </svg>
                                            <?= __('cal_with') ?> <?php echo htmlspecialchars($rdv['avec']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($rendezVous)): ?>
                                <p class="rdv-empty"><?= __('cal_no_upcoming') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div><!-- /.grid-layout -->

            <!-- ══════════════════════════════════════════
                 MODAL — NOUVEL ÉVÉNEMENT
            ══════════════════════════════════════════ -->
            <div id="modal-new-event" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <div class="modal-box">
                    <!-- ── Header ── -->
                    <div class="modal-header">
                        <h2 class="modal-title" id="modal-title"><?= __('modal_new_event') ?></h2>
                        <button class="modal-close" id="closeModalBtn" aria-label="<?= __('modal_close') ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <line x1="18" y1="6"  x2="6"  y2="18"/>
                                <line x1="6"  y1="6"  x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                    <!-- ── Form ── -->
                    <form method="POST" action="index.php?page=calendar&action=create" id="formNewEvent" novalidate>
                        <!-- TITRE -->
                        <div class="mf-group">
                            <label class="mf-label" for="ev-title"><?= __('modal_title') ?></label>
                            <input
                                type="text"
                                id="ev-title"
                                name="title"
                                required
                                class="mf-input"
                                placeholder="<?= __('modal_title_placeholder') ?>"
                                autocomplete="off"
                            >
                        </div>
                        <!-- DESCRIPTION -->
                        <div class="mf-group">
                            <label class="mf-label" for="ev-desc"><?= __('modal_desc') ?></label>
                            <textarea
                                id="ev-desc"
                                name="description"
                                class="mf-input mf-textarea"
                                placeholder="<?= __('modal_desc_placeholder') ?>"
                                rows="4"
                            ></textarea>
                        </div>
                        <!-- DÉBUT / FIN (side by side) -->
                        <div class="mf-row">
                            <div class="mf-group">
                                <label class="mf-label" for="ev-start"><?= __('modal_start') ?></label>
                                <input
                                    type="datetime-local"
                                    id="ev-start"
                                    name="start_datetime"
                                    required
                                    class="mf-input"
                                >
                            </div>
                            <div class="mf-group">
                                <label class="mf-label" for="ev-end"><?= __('modal_end') ?></label>
                                <input
                                    type="datetime-local"
                                    id="ev-end"
                                    name="end_datetime"
                                    required
                                    class="mf-input"
                                >
                            </div>
                        </div>
                        <!-- VISIBILITÉ -->
                        <div class="mf-group">
                            <label class="mf-label" for="ev-type"><?= __('modal_visibility') ?></label>
                            <div class="mf-select-wrap">
                                <span class="mf-select-icon" id="selectIcon">🔒</span>
                                <select id="ev-type" name="event_type" class="mf-input mf-select">
                                    <option value="private" data-icon="🔒"><?= __('modal_private') ?></option>
                                    <option value="shared"  data-icon="👥"><?= __('modal_shared') ?></option>
                                    <option value="public"  data-icon="🌐"><?= __('modal_public') ?></option>
                                </select>
                                <span class="mf-chevron">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!-- ── Action buttons ── -->
                        <div class="mf-actions">
                            <button type="button" class="mf-btn-cancel" id="cancelModalBtn"><?= __('modal_cancel') ?></button>
                            <button type="submit" class="mf-btn-create"><?= __('modal_create') ?></button>
                        </div>
                    </form>
                </div>
            </div><!-- /#modal-new-event -->

            <!-- ══ EVENT DETAIL POPUP ══ -->
            <div id="event-detail-overlay" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="evdet-title">
                <div class="modal-box evdet-box">
                    <div class="modal-header">
                        <h2 class="modal-title" id="evdet-title"></h2>
                        <button class="modal-close" id="evdetCloseBtn" aria-label="<?= __('modal_close') ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <line x1="18" y1="6"  x2="6"  y2="18"/>
                                <line x1="6"  y1="6"  x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                    <div class="evdet-body">
                        <div class="evdet-row" id="evdet-desc-row">
                            <span class="evdet-icon">📝</span>
                            <div>
                                <div class="evdet-label"><?= __('modal_desc') ?></div>
                                <div class="evdet-value" id="evdet-desc"></div>
                            </div>
                        </div>
                        <div class="evdet-row">
                            <span class="evdet-icon">🕐</span>
                            <div>
                                <div class="evdet-label"><?= __('cal_event_start') ?></div>
                                <div class="evdet-value" id="evdet-start"></div>
                            </div>
                        </div>
                        <div class="evdet-row">
                            <span class="evdet-icon">🕑</span>
                            <div>
                                <div class="evdet-label"><?= __('cal_event_end') ?></div>
                                <div class="evdet-value" id="evdet-end"></div>
                            </div>
                        </div>
                        <div class="evdet-row">
                            <span class="evdet-icon" id="evdet-type-icon">🔒</span>
                            <div>
                                <div class="evdet-label"><?= __('modal_visibility') ?></div>
                                <div class="evdet-value" id="evdet-type"></div>
                            </div>
                        </div>
                        <div class="evdet-row">
                            <span class="evdet-icon">👤</span>
                            <div>
                                <div class="evdet-label"><?= __('cal_event_organizer') ?></div>
                                <div class="evdet-value" id="evdet-organizer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.calendrier-container -->

        <!-- ── Calendar + Modal JS ── -->
        <script>
        (function () {
            /* ── Data from PHP ── */
            const MONTHS_FR = <?php echo json_encode(explode(',', __('cal_months')), JSON_UNESCAPED_UNICODE); ?>;
            const EVENTS    = <?php echo $calEventsJson; ?>;

            /* ── Today reference (numeric key for fast comparison) ── */
            const _now    = new Date();
            const todayY  = _now.getFullYear();
            const todayM  = _now.getMonth();
            const todayD  = _now.getDate();
            const todayNumeric = todayY * 10000 + (todayM + 1) * 100 + todayD;

            let current = { year: todayY, month: todayM };

            /* ── Render calendar ── */
            function renderCalendar({ year, month }) {
                document.getElementById('calMonthLabel').textContent =
                    MONTHS_FR[month] + ' ' + year;

                const firstDay  = new Date(year, month, 1);
                let startDow    = firstDay.getDay();
                startDow        = (startDow === 0) ? 6 : startDow - 1; // Monday-first

                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInPrev  = new Date(year, month, 0).getDate();

                const body = document.getElementById('calBody');
                body.innerHTML = '';

                let dayCount  = 1;
                let nextCount = 1;
                const totalCells = Math.ceil((startDow + daysInMonth) / 7) * 7;

                for (let i = 0; i < totalCells; i += 7) {
                    const tr = document.createElement('tr');

                    for (let j = 0; j < 7; j++) {
                        const td  = document.createElement('td');
                        const idx = i + j;

                        let dayNum, isOther = false, mo = month, yr = year;

                        if (idx < startDow) {
                            dayNum  = daysInPrev - startDow + idx + 1;
                            isOther = true;
                            mo = month - 1;
                            if (mo < 0) { mo = 11; yr = year - 1; }
                        } else if (dayCount > daysInMonth) {
                            dayNum  = nextCount++;
                            isOther = true;
                            mo = month + 1;
                            if (mo > 11) { mo = 0; yr = year + 1; }
                        } else {
                            dayNum = dayCount++;
                        }

                        /* ── Numeric key for this cell ── */
                        const cellNumeric = yr * 10000 + (mo + 1) * 100 + dayNum;

                        /* ── CSS classes ── */
                        if (isOther) {
                            td.classList.add('day-other');
                        } else {
                            if (cellNumeric < todayNumeric) {
                                td.classList.add('is-past');
                            } else if (cellNumeric === todayNumeric) {
                                td.classList.add('day-today');
                            }
                        }

                        /* ── Day number ── */
                        const span = document.createElement('span');
                        span.className   = 'day-num';
                        span.textContent = dayNum;
                        td.appendChild(span);

                        /* ── Click to pre-fill modal (future days only) ── */
                        if (!isOther && cellNumeric >= todayNumeric) {
                            td.style.cursor = 'pointer';
                            td.addEventListener('click', (function(y, m, d) {
                                return function () {
                                    const mStr = String(m + 1).padStart(2, '0');
                                    const dStr = String(d).padStart(2, '0');
                                    document.getElementById('ev-start').value = `${y}-${mStr}-${dStr}T09:00`;
                                    document.getElementById('ev-end').value   = `${y}-${mStr}-${dStr}T10:00`;
                                    openModal();
                                };
                            })(yr, mo, dayNum));
                        }

                        /* ── Event pills ── */
                        const key = yr + '-' + (mo + 1) + '-' + dayNum;
                        if (!isOther && EVENTS[key]) {
                            EVENTS[key].forEach(function (ev) {
                                const pill = document.createElement('span');
                                pill.className   = 'event-pill';
                                pill.textContent = ev.label;
                                /* Past events get a muted pill style via CSS class */
                                if (cellNumeric < todayNumeric) {
                                    pill.classList.add('event-pill--past');
                                }
                                pill.dataset.evTitle     = ev.title;
                                pill.dataset.evDesc      = ev.description;
                                pill.dataset.evStart     = ev.start;
                                pill.dataset.evEnd       = ev.end;
                                pill.dataset.evType      = ev.type;
                                pill.dataset.evTypeIcon  = ev.type_icon;
                                pill.dataset.evOrganizer = ev.organizer;
                                pill.addEventListener('click', function (e) {
                                    e.stopPropagation();
                                    showEventDetail(this.dataset);
                                });
                                td.appendChild(pill);
                            });
                        }

                        tr.appendChild(td);
                    }
                    body.appendChild(tr);
                }
            }

            /* ── Navigation ── */
            document.getElementById('prevMonth').addEventListener('click', function () {
                current.month--;
                if (current.month < 0) { current.month = 11; current.year--; }
                renderCalendar(current);
            });
            document.getElementById('nextMonth').addEventListener('click', function () {
                current.month++;
                if (current.month > 11) { current.month = 0; current.year++; }
                renderCalendar(current);
            });
            document.getElementById('goToday').addEventListener('click', function () {
                current = { year: todayY, month: todayM };
                renderCalendar(current);
            });

            renderCalendar(current);

            /* ── Event detail popup ── */
            var TYPE_LABELS = {
                'private': <?= json_encode(__('modal_private')) ?>,
                'shared':  <?= json_encode(__('modal_shared')) ?>,
                'public':  <?= json_encode(__('modal_public')) ?>
            };

            var detailOverlay = document.getElementById('event-detail-overlay');

            function showEventDetail(d) {
                document.getElementById('evdet-title').textContent = d.evTitle;

                var descRow = document.getElementById('evdet-desc-row');
                var descEl  = document.getElementById('evdet-desc');
                if (d.evDesc) {
                    descEl.textContent    = d.evDesc;
                    descRow.style.display = '';
                } else {
                    descRow.style.display = 'none';
                }

                document.getElementById('evdet-start').textContent     = d.evStart;
                document.getElementById('evdet-end').textContent       = d.evEnd;
                document.getElementById('evdet-type').textContent      = TYPE_LABELS[d.evType] || d.evType;
                document.getElementById('evdet-type-icon').textContent = d.evTypeIcon;
                document.getElementById('evdet-organizer').textContent = d.evOrganizer;

                detailOverlay.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }

            function closeDetail() {
                detailOverlay.classList.remove('is-open');
                document.body.style.overflow = '';
            }

            document.getElementById('evdetCloseBtn').addEventListener('click', closeDetail);
            detailOverlay.addEventListener('click', function (e) {
                if (e.target === detailOverlay) closeDetail();
            });

            /* ── New-event modal ── */
            var modal     = document.getElementById('modal-new-event');
            var openBtn   = document.getElementById('openModalBtn');
            var closeBtn  = document.getElementById('closeModalBtn');
            var cancelBtn = document.getElementById('cancelModalBtn');
            var selectEl  = document.getElementById('ev-type');
            var iconEl    = document.getElementById('selectIcon');

            function openModal() {
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
                setTimeout(function () {
                    document.getElementById('ev-title').focus();
                }, 80);
            }

            function closeModal() {
                modal.classList.remove('is-open');
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click',   openModal);
            closeBtn.addEventListener('click',  closeModal);
            cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            /* ── Shared Escape key handler (both modals) ── */
            document.addEventListener('keydown', function (e) {
                if (e.key !== 'Escape') return;
                if (detailOverlay.classList.contains('is-open')) closeDetail();
                if (modal.classList.contains('is-open'))         closeModal();
            });

            /* ── Sync emoji when visibility changes ── */
            selectEl.addEventListener('change', function () {
                var opt = selectEl.options[selectEl.selectedIndex];
                iconEl.textContent = opt.getAttribute('data-icon') || '🔒';
            });

            /* ── End >= start validation ── */
            document.getElementById('formNewEvent').addEventListener('submit', function (e) {
                var s = document.getElementById('ev-start').value;
                var f = document.getElementById('ev-end').value;
                if (s && f && f < s) {
                    e.preventDefault();
                    alert(<?php echo json_encode(__('modal_end_before_start')); ?>);
                    document.getElementById('ev-end').focus();
                }
            });

        })();
        </script>
        <?php
    }
}
?>