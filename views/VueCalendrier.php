<?php
// views/VueCalendrier.php

class VueCalendrier extends Vue {

    protected $titre = "SoKnow - Mes Rendez-vous";

    protected function afficherContenu($donnees) {
        $events   = $donnees['events'] ?? [];
        $upcoming = $donnees['upcoming'] ?? [];
        $user     = $_SESSION['user'] ?? ['first_name' => 'Utilisateur'];
        $allUsers = $donnees['all_users'] ?? []; // Pour la liste d'invitation

        // Build calendar events JSON
        $calEvents = [];
        foreach ($events as $ev) {
            $ts  = strtotime($ev['start_datetime']);
            $key = date('Y', $ts) . '-' . (int)date('m', $ts) . '-' . (int)date('d', $ts);
            $calEvents[$key][] = date('H:i', $ts) . ' ' . mb_strimwidth($ev['title'], 0, 18, '…');
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
                'avec'        => htmlspecialchars($ev['first_name'] . ' ' . $ev['last_name']),
                'type'        => ($ev['event_type'] ?? 'private') === 'shared' ? 'visio' : 'presentiel',
                'quand'       => $quand,
            ];
        }

        $calDays = explode(',', __('cal_days'));
        ?>

        <div class="calendrier-container">

            <div class="page-header">
                <div class="page-title">
                    <h1>Mon Planning de RDV</h1>
                    <p>Gérez vos sessions d'entraide et rendez-vous personnels.</p>
                </div>
                <button class="btn-primary btn-new" id="openModalBtn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5"  y1="12" x2="19" y2="12"/>
                    </svg>
                    Nouveau RDV
                </button>
            </div>

            <div class="grid-layout">

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
                            Prochains RDV
                        </h3>

                        <div class="rdv-list">
                            <?php foreach ($rendezVous as $rdv): ?>
                                <?php
                                    $badgeClass = ($rdv['quand'] === 'today') ? 'badge-today' : 'badge-tomorrow';
                                    $badgeLabel = ($rdv['quand'] === 'today') ? __('cal_today') : __('cal_tomorrow');
                                    $isVisio    = ($rdv['type'] === 'visio');
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
                                <p class="rdv-empty">Aucun rendez-vous prévu.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div><div id="modal-new-event" class="modal-overlay" style="max-width: 600px !important;" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <div class="modal-box">

                    <div class="modal-header">
                        <h2 class="modal-title" id="modal-title">Planifier un RDV</h2>
                        <button class="modal-close" id="closeModalBtn" aria-label="<?= __('modal_close') ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <line x1="18" y1="6"  x2="6"  y2="18"/>
                                <line x1="6"  y1="6"  x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="index.php?page=calendar&action=create" id="formNewEvent" novalidate>

                        <div class="mf-group">
                            <label class="mf-label" for="ev-title">Objet du RDV</label>
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

                        <div class="mf-group">
                            <label class="mf-label" for="ev-type">Type de RDV</label>
                            <div class="mf-select-wrap">
                                <span class="mf-select-icon" id="selectIcon">🔒</span>
                                <select id="ev-type" name="event_type" class="mf-input mf-select" onchange="document.getElementById('user_select_div_cal').style.display = this.value === 'shared' ? 'block' : 'none';">
                                    <option value="private" data-icon="🔒">🔒 Personnel (Pense-bête)</option>
                                    <option value="shared"  data-icon="👥">👥 Partagé (RDV avec un membre)</option>
                                </select>
                                <span class="mf-chevron">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        
                        <div id="user_select_div_cal" class="mf-group" style="display:none; margin-top: 16px;">
                            <label class="mf-label" style="display:block; margin-bottom: 6px;">Membre(s) à inviter</label>
                            <select name="invited_users[]" multiple class="mf-input mf-textarea" style="height: 100px; padding: 8px;">
                                <?php foreach ($allUsers as $u): ?>
                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                        <option value="<?= htmlspecialchars($u['id']) ?>">
                                            <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?> (<?= htmlspecialchars($u['user_type']) ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <div style="font-size: 11px; color:#6B7280; margin-top:4px;">Maintenez Ctrl (Win) ou Cmd (Mac) pour sélectionner.</div>
                        </div>

                        <div class="mf-actions" style="margin-top: 24px;">
                            <button type="button" class="mf-btn-cancel" id="cancelModalBtn"><?= __('modal_cancel') ?></button>
                            <button type="submit" class="mf-btn-create">Planifier</button>
                        </div>

                    </form>
                </div>
            </div></div><link rel="stylesheet" href="assets/css/calendar.css">

        <script>
        (function () {
            /* ── Calendar ── */
            const MONTHS_FR = <?php echo json_encode(explode(',', __('cal_months')), JSON_UNESCAPED_UNICODE); ?>;
            const EVENTS = <?php echo $calEventsJson; ?>;
            var _now = new Date();
            let current = { year: _now.getFullYear(), month: _now.getMonth() };

            function renderCalendar({ year, month }) {
                document.getElementById('calMonthLabel').textContent = MONTHS_FR[month] + ' ' + year;
                const today    = new Date();
                const firstDay = new Date(year, month, 1);
                let startDow = firstDay.getDay();
                startDow = (startDow === 0) ? 6 : startDow - 1;

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

                        const isToday = !isOther && dayNum === today.getDate() && month  === today.getMonth() && year   === today.getFullYear();
                        if (isOther) td.classList.add('day-other');
                        if (isToday) td.classList.add('day-today');

                        const span = document.createElement('span');
                        span.className   = 'day-num';
                        span.textContent = dayNum;
                        td.appendChild(span);

                        const key = yr + '-' + (mo + 1) + '-' + dayNum;
                        if (!isOther && EVENTS[key]) {
                            EVENTS[key].forEach(function (label) {
                                const pill = document.createElement('span');
                                pill.className   = 'event-pill';
                                pill.textContent = label;
                                td.appendChild(pill);
                            });
                        }
                        tr.appendChild(td);
                    }
                    body.appendChild(tr);
                }
            }

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
                var t = new Date();
                current = { year: t.getFullYear(), month: t.getMonth() };
                renderCalendar(current);
            });

            renderCalendar(current);

            /* ── Modal ── */
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

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
            });

            selectEl.addEventListener('change', function () {
                var opt = selectEl.options[selectEl.selectedIndex];
                iconEl.textContent = opt.getAttribute('data-icon') || '🔒';
            });

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