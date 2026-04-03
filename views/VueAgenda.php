<?php
// views/VueAgenda.php

class VueAgenda extends Vue {

    protected $titre = "SoKnow";

    public function __construct() {
        $this->titre = __('agenda_title');
    }

    protected function afficherContenu($donnees) {
        $events = $donnees['events'] ?? [];
        $pendingInvites = $donnees['pending_invites'] ?? [];
        $allUsers = $donnees['all_users'] ?? [];

        // Build a lookup map: day => [events]
        $eventsByDay = [];
        foreach ($events as $event) {
            $day = date('Y-m-d', strtotime($event['start_datetime']));
            $eventsByDay[$day][] = $event;
        }

        // Calendar navigation
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
        $year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');

        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth     = date('t', $firstDayOfMonth);
        $startWeekday    = (int)date('N', $firstDayOfMonth); // 1=Mon, 7=Sun
        $monthName       = date('F Y', $firstDayOfMonth);

        $prevMonth = $month - 1; $prevYear = $year;
        if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
        $nextMonth = $month + 1; $nextYear = $year;
        if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

        $today = date('Y-m-d');
        $nowForInput = date('Y-m-d\TH:i'); // Pour l'attribut min
        ?>

        <div class="dashboard-container">
            <div class="grid-layout">
                <div class="col-feed">
                    <div class="card">
                        <div class="agenda-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <div>
                                <h1 class="card-title" style="font-size: 24px;"><?= __('agenda_heading') ?></h1>
                                <p class="post-time"><?= __('agenda_subtitle') ?></p>
                            </div>
                            <button class="btn-primary agenda-new-btn" onclick="document.getElementById('modal-new-event').style.display='flex'">
                                <?= __('agenda_new_event') ?>
                            </button>
                        </div>
                        
                        <div class="agenda-nav" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <a href="?page=agenda&month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="btn-outline">&#8249;</a>
                            <span class="agenda-month-label" style="font-weight: 700; font-size: 16px;"><?= $monthName ?></span>
                            <div style="display: flex; gap: 10px;">
                                <a href="?page=agenda" class="btn-outline"><?= __('agenda_today') ?></a>
                                <a href="?page=agenda&month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="btn-outline">&#8250;</a>
                            </div>
                        </div>

                        <div class="agenda-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
                            <!-- Day headers -->
                            <?php $calDays = explode(',', __('cal_days')); foreach ($calDays as $d): ?>
                                <div class="agenda-day-header" style="text-align: center; font-size: 12px; font-weight: 700; color: #6B7280; padding-bottom: 10px;"><?= $d ?></div>
                            <?php endforeach; ?>

                            <!-- Empty cells before day 1 -->
                            <?php for ($i = 1; $i < $startWeekday; $i++): ?>
                                <div class="agenda-day-cell agenda-day-empty" style="min-height: 100px; border: 1px solid #E5E7EB; border-radius: 8px; background: #F9FAFB;"></div>
                            <?php endfor; ?>

                            <?php for ($day = 1; $day <= $daysInMonth; $day++):
                                $dateStr  = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                $isToday  = ($dateStr === $today);
                                $isPast   = ($dateStr < $today);
                                $dayEvents = $eventsByDay[$dateStr] ?? [];
                            ?>
                                <div class="agenda-day-cell" 
                                     style="min-height: 100px; border: 1px solid <?= $isToday ? '#4A2BBD' : '#E5E7EB' ?>; border-radius: 8px; padding: 8px; 
                                     <?= $isToday ? 'background: #F3F0FF;' : '' ?> 
                                     <?= $isPast ? 'background: #f5f5f5; opacity:0.7; cursor:not-allowed;' : 'cursor:pointer;' ?>"
                                     <?php if (!$isPast): ?> onclick="document.getElementById('modal-new-event').style.display='flex'" <?php endif; ?>>
                                    
                                    <span style="font-size: 14px; font-weight: <?= $isToday ? '800' : '500' ?>; color: <?= $isPast ? '#ccc' : ($isToday ? '#4A2BBD' : '#374151') ?>;"><?= $day ?></span>
                                    
                                    <div style="margin-top: 8px; display: flex; flex-direction: column; gap: 4px;">
                                    <?php foreach ($dayEvents as $ev): ?>
                                        <div class="agenda-event-pill" style="font-size: 11px; padding: 4px 6px; border-radius: 4px; background: <?= $ev['event_type']=='shared' ? '#FEF3C7' : '#E0E7FF' ?>; color: <?= $ev['event_type']=='shared' ? '#92400E' : '#3730A3' ?>;" title="<?= htmlspecialchars($ev['title']) ?>">
                                            <?= htmlspecialchars(mb_strimwidth($ev['title'], 0, 18, '…')) ?>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="col-widgets">
                    <!-- NEW Pending Invites Widget -->
                    <?php if (!empty($pendingInvites)): ?>
                    <div class="card" style="margin-bottom: 24px; border-left: 4px solid #F59E0B;">
                        <h3 class="card-title" style="color: #D97706;">⏳ <?= __('agenda_pending') ?></h3>
                        <?php foreach ($pendingInvites as $inv): ?>
                            <div class="widget-rdv" style="flex-direction: column; align-items: flex-start; gap: 8px; border-bottom: 1px solid #F3F4F6; padding-bottom: 12px; margin-bottom: 12px;">
                                <div class="rdv-info" style="width: 100%;">
                                    <div style="flex: 1;">
                                        <h4 class="post-name"><?= htmlspecialchars($inv['title']) ?></h4>
                                        <div class="rdv-time"><?= date('d M Y, H:i', strtotime($inv['start_datetime'])) ?></div>
                                        <div style="font-size: 11px; margin-top: 4px; color: #6B7280;"><?= __('agenda_from') ?> <?= htmlspecialchars($inv['first_name'] . ' ' . $inv['last_name']) ?></div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 8px; width: 100%;">
                                    <a href="index.php?page=agenda&action=accept&event_id=<?= $inv['id'] ?>" class="btn-primary" style="flex:1; padding: 6px; font-size:12px; text-align:center;"><?= __('agenda_accept') ?></a>
                                    <a href="index.php?page=agenda&action=decline&event_id=<?= $inv['id'] ?>" class="btn-outline" style="flex:1; padding: 6px; font-size:12px; text-align:center;"><?= __('agenda_decline') ?></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="card">
                        <h3 class="card-title">📅 <?= __('agenda_upcoming') ?></h3>
                        <?php if (empty($events)): ?>
                            <p class="post-time"><?= __('agenda_no_upcoming') ?></p>
                        <?php else: ?>
                            <?php foreach (array_slice($events, 0, 5) as $ev): ?>
                                <div class="widget-rdv">
                                    <div class="rdv-info">
                                        <div class="event-date-box" style="min-width: 50px;">
                                            <div class="event-month"><?= date('M', strtotime($ev['start_datetime'])) ?></div>
                                            <div class="event-day" style="font-size: 16px;"><?= date('d', strtotime($ev['start_datetime'])) ?></div>
                                        </div>
                                        <div>
                                            <h4 class="post-name"><?= htmlspecialchars($ev['title']) ?></h4>
                                            <div class="rdv-time"><?= date('H:i', strtotime($ev['start_datetime'])) ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Event Modal -->
        <div id="modal-new-event" class="agenda-modal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
            <div class="card agenda-modal-box" style="width: 100%; max-width: 600px; padding: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 class="card-title"><?= __('agenda_subject') ?></h2>
                    <button style="background: none; border: none; font-size: 24px; cursor: pointer;" onclick="document.getElementById('modal-new-event').style.display='none'">&times;</button>
                </div>
                <form method="POST" action="index.php?page=agenda&action=create">
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; font-weight: 700; color: #4B5563; text-transform: uppercase;"><?= __('agenda_subject') ?></label>
                        <input type="text" name="title" required class="input-textarea" style="min-height: 40px; margin-top: 6px;" placeholder="<?= __('agenda_subject_ph') ?>">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; font-weight: 700; color: #4B5563; text-transform: uppercase;"><?= __('agenda_desc') ?></label>
                        <textarea name="description" class="input-textarea" style="margin-top: 6px;" rows="3" placeholder="<?= __('agenda_desc_ph') ?>"></textarea>
                    </div>
                    <div class="agenda-modal-dates" style="display: flex; gap: 16px; margin-bottom: 16px;">
                        <div style="flex: 1;">
                            <label style="font-size: 12px; font-weight: 700; color: #4B5563; text-transform: uppercase;"><?= __('agenda_start') ?></label>
                            <input type="datetime-local" name="start_datetime" required class="input-textarea" style="min-height: 40px; margin-top: 6px;">
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 12px; font-weight: 700; color: #4B5563; text-transform: uppercase;"><?= __('agenda_end') ?></label>
                            <input type="datetime-local" name="end_datetime" required class="input-textarea" style="min-height: 40px; margin-top: 6px;">
                        </div>
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label style="font-size: 12px; font-weight: 700; color: #4B5563; text-transform: uppercase;"><?= __('agenda_visibility') ?></label>
                        <select name="event_type" id="event_type" class="input-textarea" style="min-height: 40px; margin-top: 6px;" onchange="document.getElementById('user_select_div').style.display = this.value === 'shared' ? 'block' : 'none';">
                            <option value="private">🔒 <?= __('agenda_private') ?></option>
                            <option value="shared">👥 <?= __('agenda_shared') ?></option>
                            <option value="public">🌍 <?= __('agenda_public') ?></option>
                        </select>
                    </div>
                    <div id="user_select_div" style="display:none; margin-bottom: 24px;">
                        <label style="font-size: 12px; font-weight: 700; color: #4B5563; text-transform: uppercase;"><?= __('agenda_invite') ?></label>
                        <select name="invited_users[]" multiple class="input-textarea" style="margin-top: 6px; height: 100px;">
                            <?php foreach ($allUsers as $u): ?>
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <option value="<?= htmlspecialchars($u['id']) ?>"><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?> (<?= htmlspecialchars($u['user_type']) ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <div style="font-size: 11px; color:#6B7280; margin-top:4px;"><?= __('agenda_select_hint') ?></div>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="button" class="btn-outline" onclick="document.getElementById('modal-new-event').style.display='none'"><?= __('agenda_cancel') ?></button>
                        <button type="submit" class="btn-primary"><?= __('agenda_schedule') ?></button>
                    </div>
                </form>
            </div>
        </div>

        <?php
    }
}
