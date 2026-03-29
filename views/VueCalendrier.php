<?php
// views/VueCalendrier.php

class VueCalendrier extends Vue {

    protected $titre = "Mon Calendrier - SoKnow";

    protected function afficherContenu($donnees) {
        $events   = $donnees['events'] ?? [];
        $upcoming = $donnees['upcoming'] ?? [];
        $user     = $_SESSION['user'] ?? ['first_name' => 'Utilisateur'];

        // Build calendar events JSON: "YYYY-M-D" => ["HH:MM Title", ...]
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
                'type'        => ($ev['event_type'] ?? 'private') === 'public' ? 'presentiel' : 'visio',
                'quand'       => $quand,
            ];
        }
        ?>

        <div class="calendrier-container">

            <!-- ── Page header ── -->
            <div class="page-header">
                <div class="page-title">
                    <h1>Mon Calendrier</h1>
                    <p>Gérez vos rendez-vous et sessions d'entraide</p>
                </div>
                <button class="btn-primary btn-new" id="openModalBtn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5"  y1="12" x2="19" y2="12"/>
                    </svg>
                    Nouvel événement
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
                                <button class="cal-nav-btn" id="prevMonth" aria-label="Mois précédent">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="15 18 9 12 15 6"/>
                                    </svg>
                                </button>
                                <button class="btn-outline btn-today" id="goToday">Aujourd'hui</button>
                                <button class="cal-nav-btn" id="nextMonth" aria-label="Mois suivant">
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
                                    <th>LUN</th><th>MAR</th><th>MER</th>
                                    <th>JEU</th><th>VEN</th><th>SAM</th><th>DIM</th>
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
                            Prochains rendez-vous
                        </h3>

                        <div class="rdv-list">
                            <?php foreach ($rendezVous as $rdv): ?>
                                <?php
                                    $badgeClass = ($rdv['quand'] === 'today') ? 'badge-today' : 'badge-tomorrow';
                                    $badgeLabel = ($rdv['quand'] === 'today') ? "Aujourd'hui" : 'Demain';
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
                                            Avec <?php echo htmlspecialchars($rdv['avec']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if (empty($rendezVous)): ?>
                                <p class="rdv-empty">Aucun rendez-vous à venir.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div><!-- /.grid-layout -->


            <!-- ══════════════════════════════════════════
                 MODAL — NOUVEL ÉVÉNEMENT
                 Matching screenshot: white card, large
                 rounded inputs, uppercase labels,
                 emoji visibility selector, two CTA buttons.
            ══════════════════════════════════════════ -->
            <div id="modal-new-event" class="modal-overlay" style="max-width: 600px !important;" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <div class="modal-box">

                    <!-- ── Header ── -->
                    <div class="modal-header">
                        <h2 class="modal-title" id="modal-title">Nouvel événement</h2>
                        <button class="modal-close" id="closeModalBtn" aria-label="Fermer">
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
                            <label class="mf-label" for="ev-title">Titre</label>
                            <input
                                type="text"
                                id="ev-title"
                                name="title"
                                required
                                class="mf-input"
                                placeholder="Titre de l'événement"
                                autocomplete="off"
                            >
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="mf-group">
                            <label class="mf-label" for="ev-desc">Description</label>
                            <textarea
                                id="ev-desc"
                                name="description"
                                class="mf-input mf-textarea"
                                placeholder="Description optionnelle"
                                rows="4"
                            ></textarea>
                        </div>

                        <!-- DÉBUT / FIN (side by side) -->
                        <div class="mf-row">
                            <div class="mf-group">
                                <label class="mf-label" for="ev-start">Début</label>
                                <input
                                    type="datetime-local"
                                    id="ev-start"
                                    name="start_datetime"
                                    required
                                    class="mf-input"
                                >
                            </div>
                            <div class="mf-group">
                                <label class="mf-label" for="ev-end">Fin</label>
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
                            <label class="mf-label" for="ev-type">Visibilité</label>
                            <div class="mf-select-wrap">
                                <span class="mf-select-icon" id="selectIcon">🔒</span>
                                <select id="ev-type" name="event_type" class="mf-input mf-select">
                                    <option value="private" data-icon="🔒">Privé</option>
                                    <option value="shared"  data-icon="👥">Partagé</option>
                                    <option value="public"  data-icon="🌐">Public</option>
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
                            <button type="button" class="mf-btn-cancel" id="cancelModalBtn">Annuler</button>
                            <button type="submit" class="mf-btn-create">Créer</button>
                        </div>

                    </form>
                </div>
            </div><!-- /#modal-new-event -->

        </div><!-- /.calendrier-container -->

        <link rel="stylesheet" href="assets/css/calendar.css">

        <!-- ── Calendar + Modal JS ── -->
        <script>
        (function () {

            /* ── Calendar ── */
            const MONTHS_FR = [
                'Janvier','Février','Mars','Avril','Mai','Juin',
                'Juillet','Août','Septembre','Octobre','Novembre','Décembre'
            ];

            const EVENTS = <?php echo $calEventsJson; ?>;

            var _now = new Date();
            let current = { year: _now.getFullYear(), month: _now.getMonth() };

            function renderCalendar({ year, month }) {
                document.getElementById('calMonthLabel').textContent =
                    MONTHS_FR[month] + ' ' + year;

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

                        const isToday = !isOther
                            && dayNum === today.getDate()
                            && month  === today.getMonth()
                            && year   === today.getFullYear();

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

            // Sync emoji when visibility changes
            selectEl.addEventListener('change', function () {
                var opt = selectEl.options[selectEl.selectedIndex];
                iconEl.textContent = opt.getAttribute('data-icon') || '🔒';
            });

            // Basic end >= start validation
            document.getElementById('formNewEvent').addEventListener('submit', function (e) {
                var s = document.getElementById('ev-start').value;
                var f = document.getElementById('ev-end').value;
                if (s && f && f < s) {
                    e.preventDefault();
                    alert('La date de fin doit être postérieure à la date de début.');
                    document.getElementById('ev-end').focus();
                }
            });

        })();
        </script>

        <?php
    }
}
?>