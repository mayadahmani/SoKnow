<?php
// views/VueCalendrier.php

class VueCalendrier extends Vue {

    protected $titre = "SoKnow - Mes Rendez-vous";

    protected function afficherContenu($donnees) {
        $events   = $donnees['events'] ?? [];
        $upcoming = $donnees['upcoming'] ?? [];
        
        $calEvents = [];
        foreach ($events as $ev) {
            $ts  = strtotime($ev['start_datetime']);
            $key = date('Y-m-d', $ts);
            $calEvents[$key][] = date('H:i', $ts) . ' ' . mb_strimwidth($ev['title'], 0, 18, '…');
        }
        $calEventsJson = json_encode($calEvents, JSON_UNESCAPED_UNICODE);
        $calDays = explode(',', __('cal_days') ?: 'LUN,MAR,MER,JEU,VEN,SAM,DIM');
        ?>

        <style>
            .calendrier-container { padding: 20px; font-family: 'Inter', sans-serif; }
            .cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
            .cal-month { font-size: 20px; font-weight: 800; color: #4F2EE8; }
            .cal-grid { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; table-layout: fixed; }
            .cal-grid th { padding: 15px; background: #f8fafc; color: #64748b; font-size: 12px; border: 1px solid #eef2f6; }
            .cal-grid td { height: 110px; vertical-align: top; padding: 10px; border: 1px solid #eef2f6; position: relative; }
            
            /* --- STYLES POUR LE GRISAGE --- */
            .is-past { 
                background-color: #F3F4F6 !important; /* Force le gris */
                color: #9CA3AF !important; 
                cursor: not-allowed !important; 
            }
            .is-past .day-num { color: #9CA3AF !important; opacity: 0.5; }
            .is-past .event-pill { background: #E5E7EB !important; color: #6B7280 !important; border: none; opacity: 0.7; }

            .day-today { background-color: #F5F3FF !important; border: 2px solid #4F2EE8 !important; }
            .day-num { font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px; }
            .event-pill { display: block; font-size: 10px; padding: 4px 6px; border-radius: 4px; background: #EEF2FF; color: #4338CA; margin-bottom: 3px; cursor: pointer; border: 1px solid #E0E7FF; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            
            .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; }
            .modal-overlay.is-open { display: flex; }
            .modal-box { background: white; padding: 25px; border-radius: 12px; width: 90%; max-width: 450px; }
            .btn-outline { padding: 8px 15px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 6px; }
        </style>

        <div class="calendrier-container">
            <div class="cal-header">
                <span class="cal-month" id="calMonthLabel"></span>
                <div style="display: flex; gap: 8px;">
                    <button class="btn-outline" id="btnPrev">‹</button>
                    <button class="btn-outline" id="btnToday">Aujourd'hui</button>
                    <button class="btn-outline" id="btnNext">›</button>
                </div>
            </div>
            <table class="cal-grid">
                <thead><tr><?php foreach ($calDays as $d): ?><th><?= $d ?></th><?php endforeach; ?></tr></thead>
                <tbody id="calBody"></tbody>
            </table>
        </div>

        <div id="modal-new-event" class="modal-overlay">
            <div class="modal-box">
                <h3>Nouveau Rendez-vous</h3>
                <form method="POST" action="index.php?page=calendar&action=create">
                    <input type="text" name="title" placeholder="Objet" required style="width:100%; margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    <input type="datetime-local" name="start_datetime" id="ev-start" required style="width:100%; margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    <div style="text-align:right; margin-top: 15px;">
                        <button type="button" class="btn-outline" onclick="closeModal()">Annuler</button>
                        <button type="submit" class="btn-primary" style="padding: 8px 15px; background: #4F2EE8; color: white; border: none; border-radius: 6px; cursor: pointer;">Créer</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        (function() {
            const MONTHS = <?php echo json_encode(explode(',', __('cal_months')), JSON_UNESCAPED_UNICODE); ?>;
            const EVENTS = <?php echo $calEventsJson; ?>;
            
            const now = new Date();
            // Identifiant numérique pour aujourd'hui (ex: 20260401)
            const todayKey = now.getFullYear() * 10000 + (now.getMonth() + 1) * 100 + now.getDate();

            let viewY = now.getFullYear();
            let viewM = now.getMonth();

            function draw() {
                document.getElementById('calMonthLabel').textContent = MONTHS[viewM] + ' ' + viewY;
                const body = document.getElementById('calBody');
                body.innerHTML = '';

                const firstDay = new Date(viewY, viewM, 1);
                let startOffset = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
                const daysInM = new Date(viewY, viewM + 1, 0).getDate();

                let day = 1;
                for (let i = 0; i < 6; i++) {
                    let tr = document.createElement('tr');
                    let rowHasContent = false;
                    for (let j = 0; j < 7; j++) {
                        let td = document.createElement('td');
                        if ((i === 0 && j < startOffset) || day > daysInM) {
                            td.style.backgroundColor = "#F9FAFB";
                        } else {
                            rowHasContent = true;
                            const currentDay = day;
                            const cellKey = viewY * 10000 + (viewM + 1) * 100 + currentDay;
                            const dateString = `${viewY}-${String(viewM + 1).padStart(2, '0')}-${String(currentDay).padStart(2, '0')}`;

                            td.innerHTML = `<span class="day-num">${currentDay}</span>`;

                            if (cellKey < todayKey) {
                                // --- ON FORCE LE GRIS ICI ---
                                td.classList.add('is-past');
                                td.style.backgroundColor = "#F3F4F6"; // Double sécurité
                                td.onclick = null; 
                            } else {
                                if (cellKey === todayKey) td.classList.add('day-today');
                                td.style.cursor = 'pointer';
                                td.onclick = () => {
                                    const mStr = String(viewM + 1).padStart(2, '0');
                                    const dStr = String(currentDay).padStart(2, '0');
                                    document.getElementById('ev-start').value = `${viewY}-${mStr}-${dStr}T09:00`;
                                    document.getElementById('modal-new-event').classList.add('is-open');
                                };
                            }

                            if (EVENTS[dateString]) {
                                EVENTS[dateString].forEach(msg => {
                                    let p = document.createElement('span');
                                    p.className = 'event-pill';
                                    p.textContent = msg;
                                    p.onclick = (e) => { e.stopPropagation(); alert(msg); };
                                    td.appendChild(p);
                                });
                            }
                            day++;
                        }
                        tr.appendChild(td);
                    }
                    if (rowHasContent) body.appendChild(tr);
                }
            }

            window.closeModal = () => document.getElementById('modal-new-event').classList.remove('is-open');
            document.getElementById('btnPrev').onclick = () => { viewM--; if(viewM<0){viewM=11;viewY--;} draw(); };
            document.getElementById('btnNext').onclick = () => { viewM++; if(viewM>11){viewM=0;viewY++;} draw(); };
            document.getElementById('btnToday').onclick = () => { viewY=now.getFullYear(); viewM=now.getMonth(); draw(); };

            draw();
        })();
        </script>
        <?php
    }
}