<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php
$photoUrl = static function (array $moment): ?string {
    $file = $moment['main_image'] ?? $moment['photo_path'] ?? null;
    return $file ? base_url('uploads/moments/' . $file) : null;
};
$dayUrl = static fn (string $date, string $tab = 'calendar'): string => base_url('memories?tab=' . $tab . '&month=' . rawurlencode($month) . '&date=' . rawurlencode($date));
$weekdays = $weekdays ?: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
$isEnglish = (session()->get('lang') ?? 'id') === 'en';
$monthNames = lang('Web.month_names');
$formatDate = static function (string $value, bool $withTime = false) use ($monthNames): string {
    $timestamp = strtotime($value);
    $names = is_array($monthNames) ? $monthNames : [];
    $monthLabel = $names[(int) date('n', $timestamp) - 1] ?? date('M', $timestamp);
    return date('j', $timestamp) . ' ' . $monthLabel . ' ' . date('Y', $timestamp) . ($withTime ? ', ' . date('H:i', $timestamp) : '');
};
$selectedLabel = $formatDate($selectedDate);
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div class="mx-auto max-w-4xl pb-28 text-midnight dark:text-white">
    <?php if ($message = session()->getFlashdata('message')): ?><div class="mb-4 rounded-2xl bg-emerald-500/10 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200"><?= esc($message) ?></div><?php endif ?>
    <?php if ($error = session()->getFlashdata('error')): ?><div class="mb-4 rounded-2xl bg-rose-500/10 px-4 py-3 text-sm text-rose-800 dark:text-rose-200"><?= esc($error) ?></div><?php endif ?>

    <div class="mb-5 flex items-center justify-between">
        <div><p class="text-xs font-bold uppercase tracking-[.2em] text-persianblue dark:text-petalfrost">DAILEE</p><h1 class="text-2xl font-black tracking-tight"><?= esc(lang('Web.memories_title')) ?></h1></div>
        <a href="<?= base_url('capture') ?>" class="rounded-full bg-persianblue px-4 py-2 text-xs font-bold text-white shadow-lg"><?= esc(lang('Web.create_log')) ?></a>
    </div>

    <nav class="mb-6 flex rounded-2xl border border-black/5 bg-white/70 p-1 shadow-sm backdrop-blur dark:border-white/10 dark:bg-darkcard/70">
        <?php foreach (['memories' => 'tab_memories', 'calendar' => 'tab_calendar', 'recaps' => 'tab_recaps'] as $tabKey => $labelKey): ?>
            <a href="<?= base_url('memories?tab=' . $tabKey . '&month=' . rawurlencode($month)) ?>" class="flex-1 rounded-xl px-2 py-2.5 text-center text-xs font-bold transition <?= $currentTab === $tabKey ? 'bg-persianblue text-white shadow' : 'text-gray-500 hover:text-midnight dark:hover:text-white' ?>"><?= esc(lang('Web.' . $labelKey)) ?></a>
        <?php endforeach ?>
    </nav>

    <?php if ($currentTab === 'memories'): ?>
        <?php if (empty($memories)): ?>
            <div class="rounded-3xl border border-black/5 bg-white/60 p-10 text-center dark:border-white/10 dark:bg-darkcard/60">
                <span class="mb-2 block text-4xl">▧</span><p class="text-sm text-gray-500"><?= esc(lang('Web.empty_memories')) ?></p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                <?php foreach ($memories as $item): $image = $photoUrl($item); ?>
                    <button type="button" class="memory-card group relative aspect-square overflow-hidden rounded-2xl bg-lavender/30 text-left shadow-sm dark:bg-darkcard" data-image="<?= esc($image ?? '', 'attr') ?>" data-caption="<?= esc($item['caption'] ?? '', 'attr') ?>" data-agenda="<?= esc($item['agenda_title'] ?? '', 'attr') ?>" data-date="<?= esc($formatDate($item['created_at']), 'attr') ?>">
                        <?php if ($image): ?><img src="<?= esc($image) ?>" alt="" loading="lazy" class="h-full w-full object-cover transition group-hover:scale-105"><?php endif ?>
                        <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 to-transparent p-3 pt-8 text-xs font-bold text-white"><?= esc($formatDate($item['created_at'])) ?></span>
                    </button>
                <?php endforeach ?>
            </div>
        <?php endif ?>

    <?php elseif ($currentTab === 'calendar'): ?>
        <section class="rounded-3xl border border-black/5 bg-white/70 p-4 shadow-lg dark:border-white/10 dark:bg-darkcard/70 sm:p-6">
            <div class="mb-5 flex items-center justify-between gap-2">
                <a href="<?= base_url('memories?tab=calendar&month=' . $previousMonth) ?>" title="<?= esc(lang('Web.previous_month')) ?>" class="rounded-xl bg-lavender/50 px-3 py-2 text-sm font-bold hover:bg-lavender dark:bg-white/5 dark:hover:bg-white/10">‹</a>
                <div class="text-center"><h2 class="text-lg font-black sm:text-xl"><?= esc($monthName) ?> <?= (int) $year ?></h2><p class="text-xs text-gray-500"><?= esc(lang('Web.calendar_hint')) ?></p></div>
                <a href="<?= base_url('memories?tab=calendar&month=' . $nextMonth) ?>" title="<?= esc(lang('Web.next_month')) ?>" class="rounded-xl bg-lavender/50 px-3 py-2 text-sm font-bold hover:bg-lavender dark:bg-white/5 dark:hover:bg-white/10">›</a>
            </div>

            <div class="grid grid-cols-7 gap-1.5 sm:gap-2">
                <?php foreach ($weekdays as $weekday): ?><div class="pb-1 text-center text-[10px] font-bold uppercase tracking-wide text-gray-400 sm:text-xs"><?= esc($weekday) ?></div><?php endforeach ?>
                <?php for ($blank = 0; $blank < $firstWeekday; $blank++): ?><div aria-hidden="true" class="aspect-square"></div><?php endfor ?>
                <?php for ($day = 1; $day <= $daysInMonth; $day++):
                    $cellDate = sprintf('%s-%02d', $month, $day);
                    $dayMoments = $momentsByDay[$cellDate] ?? [];
                    $dayAgendas = $agendasByDay[$cellDate] ?? [];
                    $cover = !empty($dayMoments) ? $photoUrl($dayMoments[0]) : null;
                    $selected = $selectedDate === $cellDate;
                    $today = date('Y-m-d') === $cellDate;
                ?>
                    <a href="<?= esc($dayUrl($cellDate), 'attr') ?>" aria-label="<?= esc($cellDate) ?>, <?= count($dayMoments) ?> memories" class="relative aspect-square overflow-hidden rounded-xl border <?= $selected ? 'border-persianblue ring-2 ring-persianblue dark:border-petalfrost dark:ring-petalfrost' : 'border-black/5 dark:border-white/10' ?> <?= $cover ? 'bg-black' : 'bg-lavender/25 dark:bg-white/5' ?>">
                        <?php if ($cover): ?><img src="<?= esc($cover) ?>" alt="" loading="lazy" class="absolute inset-0 h-full w-full object-cover opacity-90"><?php endif ?>
                        <?php if ($cover): ?><span class="absolute inset-0 bg-gradient-to-b from-black/35 via-transparent to-black/35"></span><?php endif ?>
                        <span class="absolute left-1.5 top-1.5 flex h-6 min-w-6 items-center justify-center rounded-full px-1 text-xs font-extrabold <?= $cover ? 'text-white drop-shadow' : ($today ? 'text-persianblue dark:text-petalfrost' : 'text-midnight dark:text-white') ?>"><?= $day ?></span>
                        <?php if (count($dayMoments) > 1): ?><span class="absolute right-1 top-1 rounded-full bg-white px-1.5 py-0.5 text-[9px] font-black text-midnight shadow"><?= count($dayMoments) ?></span><?php endif ?>
                        <?php if ($dayAgendas): ?><span class="absolute bottom-1 right-1 h-2 w-2 rounded-full bg-glaucous ring-2 ring-white dark:ring-black" title="<?= esc(lang('Web.agenda')) ?>"></span><?php endif ?>
                        <?php if ($today): ?><span class="absolute inset-x-0 bottom-1 text-center text-[8px] font-black uppercase text-white drop-shadow"><?= $isEnglish ? 'Today' : 'Hari ini' ?></span><?php endif ?>
                    </a>
                <?php endfor ?>
            </div>
        </section>

        <section class="mt-5 rounded-3xl border border-black/5 bg-white/70 p-4 shadow-sm dark:border-white/10 dark:bg-darkcard/70 sm:p-6">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div><p class="text-[10px] font-bold uppercase tracking-widest text-persianblue dark:text-petalfrost"><?= esc(lang('Web.selected_date')) ?></p><h3 class="text-lg font-black"><?= esc($selectedLabel) ?></h3></div>
                <?php if ($selectedDate !== date('Y-m-d')): ?><a href="<?= base_url('memories?tab=calendar&month=' . $month . '&date=' . date('Y-m-d')) ?>" class="text-xs font-bold text-persianblue dark:text-petalfrost"><?= $isEnglish ? 'Today' : 'Hari ini' ?></a><?php endif ?>
            </div>
            <?php if ($selectedAgendas): ?>
                <div class="mb-4 space-y-2">
                    <?php foreach ($selectedAgendas as $agenda):
                        $hasAgendaLog = false;
                        foreach ($selectedMoments as $selectedMoment) {
                            if ((int) ($selectedMoment['schedule_id'] ?? 0) === (int) $agenda['id']) { $hasAgendaLog = true; break; }
                        }
                    ?>
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-lavender/25 px-3 py-2.5 dark:bg-white/5">
                            <div class="min-w-0"><p class="truncate text-sm font-bold"><?= esc($agenda['title']) ?></p><p class="text-xs text-gray-500"><?= esc(date('H:i', strtotime($agenda['start_time']))) ?><?= !empty($agenda['location']) ? ' · ' . esc($agenda['location']) : '' ?></p></div>
                            <span class="shrink-0 rounded-full bg-persianblue/10 px-2.5 py-1 text-[10px] font-bold text-persianblue dark:text-petalfrost"><?= esc($agenda['status'] ?? '') ?></span>
                        </div>
                        <?php if (!$hasAgendaLog): ?>
                            <form action="<?= base_url('memories/upload-to-agenda') ?>" method="post" enctype="multipart/form-data" class="mt-2 flex flex-wrap items-center gap-2 rounded-xl bg-lavender/15 p-3 dark:bg-white/5">
                                <?= csrf_field() ?><input type="hidden" name="schedule_id" value="<?= (int) $agenda['id'] ?>">
                                <input type="file" name="main_image" accept="image/jpeg,image/png,image/webp" required class="min-w-0 flex-1 text-[10px] text-gray-500">
                                <button class="rounded-lg bg-persianblue px-3 py-2 text-[10px] font-bold text-white"><?= esc(lang('Web.upload_photo')) ?></button>
                            </form>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <?php if ($selectedMoments): ?>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <?php foreach ($selectedMoments as $item): $image = $photoUrl($item); ?>
                        <button type="button" class="memory-card group relative aspect-[4/5] overflow-hidden rounded-2xl bg-lavender/20 text-left dark:bg-black/20" data-image="<?= esc($image ?? '', 'attr') ?>" data-caption="<?= esc($item['caption'] ?? '', 'attr') ?>" data-agenda="<?= esc($item['agenda_title'] ?? '', 'attr') ?>" data-date="<?= esc($formatDate($item['created_at'], true), 'attr') ?>">
                            <?php if ($image): ?><img src="<?= esc($image) ?>" alt="" loading="lazy" class="h-full w-full object-cover transition group-hover:scale-105"><?php endif ?>
                            <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-3 pt-8 text-xs text-white"><strong class="block truncate"><?= esc($item['caption'] ?: (lang('Web.tab_memories'))) ?></strong><span class="text-white/75"><?= esc($formatDate($item['created_at'], true)) ?><?= !empty($item['agenda_title']) ? ' · ' . esc($item['agenda_title']) : '' ?></span></span>
                        </button>
                    <?php endforeach ?>
                </div>
            <?php elseif (!$selectedAgendas): ?>
                <div class="rounded-2xl bg-lavender/20 px-5 py-8 text-center text-sm text-gray-500 dark:bg-white/5"><?= esc(lang('Web.no_day_archive')) ?></div>
            <?php endif ?>

            <?php if ($selectedAgendas): ?>
                <p class="mt-4 text-xs font-bold text-gray-500"><?= esc(lang('Web.agenda')) ?> · <?= esc(date('j M Y', strtotime($selectedDate))) ?></p>
                <?php foreach ($selectedAgendas as $agenda): ?>
                    <a href="<?= base_url('calendar/sync-ics/' . (int) $agenda['id']) ?>" class="mr-2 mt-2 inline-block text-xs font-bold text-persianblue underline dark:text-petalfrost">Sync <?= esc($agenda['title']) ?> (.ics)</a>
                <?php endforeach ?>
            <?php endif ?>
        </section>

    <?php else: ?>
        <div class="mb-4 flex items-center justify-between rounded-2xl border border-black/5 bg-white/70 p-3 dark:border-white/10 dark:bg-darkcard/70">
            <a href="<?= base_url('memories?tab=recaps&month=' . $previousMonth) ?>" class="rounded-xl px-3 py-2 text-sm font-bold hover:bg-lavender/40 dark:hover:bg-white/10" title="<?= esc(lang('Web.previous_month')) ?>">‹</a>
            <strong class="text-sm"><?= esc($monthName) ?> <?= (int) $year ?></strong>
            <a href="<?= base_url('memories?tab=recaps&month=' . $nextMonth) ?>" class="rounded-xl px-3 py-2 text-sm font-bold hover:bg-lavender/40 dark:hover:bg-white/10" title="<?= esc(lang('Web.next_month')) ?>">›</a>
        </div>

        <section id="monthly-recap" class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-petalfrost via-lavender to-white p-5 shadow-2xl dark:from-midnight dark:via-persianblue dark:to-[#1e1e2e] sm:p-8">
            <div class="mx-auto max-w-2xl">
                <p class="text-xs font-extrabold uppercase tracking-[.2em] text-persianblue dark:text-petalfrost">DAILEE · <?= esc($monthName) ?> <?= (int) $year ?></p>
                <h2 class="mt-2 text-3xl font-black text-midnight dark:text-white"><?= esc(lang('Web.monthly_recap')) ?></h2>
                <p class="mt-1 text-sm text-midnight/65 dark:text-white/70"><?= esc(lang('Web.monthly_desc')) ?></p>
                <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <?php foreach ([['total_logs', count($monthMoments)], ['active_days', $activeDays], ['total_agendas', $totalAgendas], ['done_agendas', $doneAgendas]] as [$label, $value]): ?>
                        <div class="rounded-2xl border border-white/70 bg-white/60 p-3 text-midnight shadow-sm dark:border-white/10 dark:bg-white/10 dark:text-white"><p class="text-[10px] font-bold opacity-65"><?= esc(lang('Web.' . $label)) ?></p><p class="mt-1 text-2xl font-black"><?= (int) $value ?></p></div>
                    <?php endforeach ?>
                </div>
                <h3 class="mb-3 mt-7 text-sm font-extrabold text-midnight dark:text-white"><?= esc(lang('Web.tab_memories')) ?></h3>
                <?php if ($monthMoments): ?>
                    <div class="grid grid-cols-3 gap-2 sm:grid-cols-4">
                        <?php foreach (array_slice($monthMoments, 0, 8) as $item): $image = $photoUrl($item); ?>
                            <a href="<?= esc($dayUrl(substr($item['created_at'], 0, 10), 'calendar'), 'attr') ?>" class="aspect-square overflow-hidden rounded-xl bg-white/40 dark:bg-black/20">
                                <?php if ($image): ?><img src="<?= esc($image) ?>" alt="" loading="lazy" class="h-full w-full object-cover"><?php endif ?>
                            </a>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <p class="rounded-2xl bg-white/50 p-5 text-center text-sm text-midnight/60 dark:bg-white/10 dark:text-white/60"><?= esc(lang('Web.no_month_logs')) ?></p>
                <?php endif ?>
            </div>
        </section>
        <div class="mt-4 text-center"><button id="download-recap" type="button" class="rounded-full bg-persianblue px-6 py-3 text-sm font-extrabold text-white shadow-lg hover:bg-midnight"><?= esc(lang('Web.download_recap')) ?></button></div>
    <?php endif ?>
</div>

<div id="detail-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm">
    <div class="relative w-full max-w-sm rounded-3xl bg-white p-5 text-midnight shadow-2xl dark:bg-darkcard dark:text-white">
        <button type="button" id="close-detail" class="absolute right-4 top-3 text-2xl text-gray-500">&times;</button>
        <img id="modal-img" src="" alt="" class="mb-3 aspect-[4/5] w-full rounded-2xl bg-lavender/20 object-cover dark:bg-black/20">
        <p id="modal-date" class="text-xs text-gray-500"></p><h4 id="modal-agenda" class="mt-1 text-sm font-bold text-persianblue dark:text-petalfrost"></h4><p id="modal-caption" class="mt-1 text-sm"></p>
    </div>
</div>

<script>
document.querySelectorAll('.memory-card').forEach((card) => card.addEventListener('click', () => {
    document.getElementById('modal-img').src = card.dataset.image || '';
    document.getElementById('modal-caption').textContent = card.dataset.caption || '';
    document.getElementById('modal-agenda').textContent = card.dataset.agenda || '';
    document.getElementById('modal-date').textContent = card.dataset.date || '';
    const modal = document.getElementById('detail-modal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
}));
document.getElementById('close-detail')?.addEventListener('click', () => {
    const modal = document.getElementById('detail-modal'); modal.classList.add('hidden'); modal.classList.remove('flex');
});
document.getElementById('detail-modal')?.addEventListener('click', (event) => {
    if (event.target.id === 'detail-modal') { event.currentTarget.classList.add('hidden'); event.currentTarget.classList.remove('flex'); }
});
document.getElementById('download-recap')?.addEventListener('click', async (event) => {
    const button = event.currentTarget; const original = button.textContent;
    button.disabled = true; button.textContent = <?= json_encode($isEnglish ? 'Preparing…' : 'Menyiapkan…') ?>;
    try {
        const canvas = await html2canvas(document.getElementById('monthly-recap'), { scale: 2, useCORS: true });
        const link = document.createElement('a'); link.download = <?= json_encode('Dailee-' . $month . '.png') ?>; link.href = canvas.toDataURL('image/png'); link.click();
    } catch (error) { alert(<?= json_encode($isEnglish ? 'Could not create the recap image.' : 'Gambar rekap belum bisa dibuat.') ?>); }
    button.disabled = false; button.textContent = original;
});
</script>

<?= $this->endSection() ?>
