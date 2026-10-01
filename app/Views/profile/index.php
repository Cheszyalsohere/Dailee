<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php
$avatarUrl = !empty($user['avatar']) ? base_url('uploads/avatars/' . $user['avatar']) : null;
$displayName = $user['nama_lengkap'] ?: ($user['name'] ?? $user['username'] ?? 'Dailee user');
$monthNames = lang('Web.month_names');
$formatDate = static function (string $value) use ($monthNames): string {
    $timestamp = strtotime($value);
    $names = is_array($monthNames) ? $monthNames : [];
    return date('j', $timestamp) . ' ' . ($names[(int) date('n', $timestamp) - 1] ?? date('M', $timestamp));
};
$momentImage = static function (array $moment): ?string {
    $image = $moment['main_image'] ?? $moment['photo_path'] ?? null;
    return $image ? base_url('uploads/moments/' . $image) : null;
};
?>

<div class="mx-auto max-w-2xl pb-28 text-midnight dark:text-white">
    <?php foreach (['message' => 'bg-emerald-500/10 text-emerald-800 dark:text-emerald-200', 'error' => 'bg-rose-500/10 text-rose-800 dark:text-rose-200'] as $flash => $style): ?>
        <?php if ($notice = session()->getFlashdata($flash)): ?>
            <div class="mb-4 rounded-2xl border border-black/5 px-4 py-3 text-sm dark:border-white/10 <?= $style ?>"><?= esc($notice) ?></div>
        <?php endif ?>
    <?php endforeach ?>

    <section class="overflow-hidden rounded-[2rem] border border-persianblue/10 bg-white shadow-2xl dark:border-white/10 dark:bg-[#17171b]">
        <div class="relative h-[25rem] bg-gradient-to-br from-petalfrost via-lavender to-white sm:h-[31rem] dark:from-midnight dark:via-[#26202d] dark:to-black">
            <?php if ($avatarUrl): ?>
                <img src="<?= esc($avatarUrl) ?>" alt="Foto profil <?= esc($displayName) ?>" class="absolute inset-0 h-full w-full object-cover opacity-80">
            <?php else: ?>
                <div class="absolute inset-0 flex items-center justify-center text-8xl font-black text-persianblue/40 dark:text-petalfrost/80"><?= esc(strtoupper(substr($displayName, 0, 1))) ?></div>
            <?php endif ?>
            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/20 to-white/10 dark:from-black dark:via-black/20 dark:to-black/30"></div>
            <div class="absolute left-5 right-5 top-5 flex items-center justify-between">
                <a href="<?= base_url('friends') ?>" class="rounded-full bg-white/75 px-4 py-2 text-sm font-semibold text-midnight shadow-sm backdrop-blur dark:bg-black/40 dark:text-white">＋ <?= esc(lang('Web.friends')) ?></a>
                <a href="<?= base_url('dashboard') ?>" class="rounded-full bg-white/75 px-4 py-2 text-sm font-semibold text-midnight shadow-sm backdrop-blur dark:bg-black/40 dark:text-white">⌂ <?= esc(lang('Web.home')) ?></a>
            </div>
            <div class="absolute bottom-6 left-6 right-6">
                <div class="mb-3 inline-flex rounded-full bg-white/75 px-3 py-1.5 text-sm font-semibold text-midnight shadow-sm backdrop-blur dark:bg-black/60 dark:text-white">🔥 <?= (int) ($user['streak'] ?? 0) ?></div>
                <h1 class="text-3xl font-black tracking-tight sm:text-4xl"><?= esc($displayName) ?></h1>
                <p class="mt-1 text-lg text-midnight/75 dark:text-white/75">@<?= esc($user['username'] ?? '') ?><?= !empty($user['is_private']) ? ' · 🔒' : '' ?></p>
                <?php if (!empty($user['bio'])): ?><p class="mt-2 max-w-lg text-sm text-midnight/85 dark:text-white/85"><?= esc($user['bio']) ?></p><?php endif ?>
            </div>
        </div>

        <div class="space-y-5 p-5 sm:p-7">
            <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-midnight/70 dark:text-white/70">
                <?php if (!empty($user['location'])): ?><span>➤ <?= esc($user['location']) ?></span><?php endif ?>
                <?php if (!empty($user['occupation'])): ?><span>▱ <?= esc($user['occupation']) ?></span><?php endif ?>
                <?php if (!empty($user['education'])): ?><span>▤ <?= esc($user['education']) ?></span><?php endif ?>
                <?php if (!empty($user['zodiac_or_interest'])): ?><span>✦ <?= esc($user['zodiac_or_interest']) ?></span><?php endif ?>
            </div>

            <div class="grid grid-cols-3 rounded-2xl bg-lavender/30 py-4 text-center dark:bg-white/5">
                <div><strong class="block text-xl text-midnight dark:text-white"><?= (int) $totalFriends ?></strong><span class="text-xs text-gray-500 dark:text-white/55"><?= esc(lang('Web.mutual_friends')) ?></span></div>
                <div><strong class="block text-xl text-midnight dark:text-white"><?= (int) $followerCount ?></strong><span class="text-xs text-gray-500 dark:text-white/55"><?= esc(lang('Web.followers')) ?></span></div>
                <div><strong class="block text-xl text-midnight dark:text-white"><?= (int) $followingCount ?></strong><span class="text-xs text-gray-500 dark:text-white/55"><?= esc(lang('Web.following')) ?></span></div>
            </div>

            <?php if ($isOwnProfile): ?>
                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('profile-editor').classList.toggle('hidden')" class="flex-1 rounded-xl bg-persianblue/10 px-4 py-3 text-sm font-bold text-persianblue hover:bg-persianblue/15 dark:bg-white/10 dark:text-white dark:hover:bg-white/15">✎ <?= esc(lang('Web.profile_edit')) ?></button>
                    <button type="button" onclick="shareProfile()" class="rounded-xl bg-persianblue/10 px-4 py-3 text-sm font-bold text-persianblue hover:bg-persianblue/15 dark:bg-white/10 dark:text-white dark:hover:bg-white/15"><?= esc(lang('Web.share')) ?></button>
                </div>
                <div id="profile-editor" class="hidden space-y-4 rounded-2xl border border-black/10 bg-lavender/15 p-4 dark:border-white/10 dark:bg-black/20">
                    <form action="<?= base_url('profile/update-avatar') ?>" method="post" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                        <?= csrf_field() ?>
                        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" required class="min-w-0 flex-1 text-xs text-midnight/70 dark:text-white/70">
                        <button class="rounded-xl bg-persianblue px-4 py-2 text-xs font-bold text-white"><?= esc(lang('Web.change_photo')) ?></button>
                    </form>
                    <form action="<?= base_url('profile/update-details') ?>" method="post" class="grid gap-3 sm:grid-cols-2">
                        <?= csrf_field() ?>
                        <input name="nama_lengkap" maxlength="255" value="<?= esc($user['nama_lengkap'] ?? '') ?>" placeholder="<?= esc(lang('Web.full_name')) ?>" class="rounded-xl bg-white px-3 py-2.5 text-sm text-midnight outline-none dark:bg-white/10 dark:text-white">
                        <input name="location" maxlength="120" value="<?= esc($user['location'] ?? '') ?>" placeholder="<?= esc(lang('Web.city')) ?>" class="rounded-xl bg-white px-3 py-2.5 text-sm text-midnight outline-none dark:bg-white/10 dark:text-white">
                        <input name="occupation" maxlength="120" value="<?= esc($user['occupation'] ?? '') ?>" placeholder="<?= esc(lang('Web.occupation')) ?>" class="rounded-xl bg-white px-3 py-2.5 text-sm text-midnight outline-none dark:bg-white/10 dark:text-white">
                        <input name="education" maxlength="180" value="<?= esc($user['education'] ?? '') ?>" placeholder="<?= esc(lang('Web.education')) ?>" class="rounded-xl bg-white px-3 py-2.5 text-sm text-midnight outline-none dark:bg-white/10 dark:text-white">
                        <input name="zodiac_or_interest" maxlength="120" value="<?= esc($user['zodiac_or_interest'] ?? '') ?>" placeholder="<?= esc(lang('Web.interest')) ?>" class="rounded-xl bg-white px-3 py-2.5 text-sm text-midnight outline-none dark:bg-white/10 dark:text-white">
                        <input name="bio" maxlength="255" value="<?= esc($user['bio'] ?? '') ?>" placeholder="<?= esc(lang('Web.bio')) ?>" class="rounded-xl bg-white px-3 py-2.5 text-sm text-midnight outline-none dark:bg-white/10 dark:text-white sm:col-span-2">
                        <button class="rounded-xl bg-persianblue px-4 py-3 text-sm font-bold text-white sm:col-span-2"><?= esc(lang('Web.save_profile')) ?></button>
                    </form>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 gap-2">
                    <?php if ($isFriend): ?>
                        <span class="rounded-xl bg-emerald-500/10 px-4 py-3 text-center text-sm font-bold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-200">✓ Mutual</span>
                        <a href="<?= base_url('inbox/' . (int) $user['id']) ?>" class="rounded-xl bg-persianblue/10 px-4 py-3 text-center text-sm font-bold text-persianblue hover:bg-persianblue/15 dark:bg-white/10 dark:text-white dark:hover:bg-white/15">✈ <?= esc(lang('Web.send_message')) ?></a>
                    <?php elseif ($friendState === 'sent'): ?>
                        <span class="col-span-2 rounded-xl bg-lavender/30 px-4 py-3 text-center text-sm font-bold dark:bg-white/10"><?= esc(lang('Web.pending_friend_request')) ?></span>
                    <?php elseif ($friendState === 'received'): ?>
                        <a href="<?= base_url('friends') ?>" class="col-span-2 rounded-xl bg-persianblue px-4 py-3 text-center text-sm font-bold text-white"><?= esc(lang('Web.see_friend_request')) ?></a>
                    <?php else: ?>
                        <form action="<?= base_url('friends/add') ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="username" value="<?= esc($user['username'], 'attr') ?>">
                            <button class="w-full rounded-xl bg-persianblue px-4 py-3 text-sm font-bold text-white">＋ <?= esc(lang('Web.add_friend')) ?></button>
                        </form>
                        <span class="rounded-xl bg-lavender/30 px-4 py-3 text-center text-xs font-semibold text-midnight/60 dark:bg-white/10 dark:text-white/60"><?= esc(lang('Web.message_after_mutual')) ?></span>
                    <?php endif ?>
                    <form action="<?= base_url('follow/toggle/' . (int) $user['id']) ?>" method="post" class="<?= $isFriend || $friendState ? 'col-span-2' : '' ?>">
                        <?= csrf_field() ?>
                        <button class="w-full rounded-xl border border-persianblue/20 px-4 py-3 text-sm font-bold text-midnight hover:bg-lavender/30 dark:border-white/15 dark:text-white dark:hover:bg-white/10"><?= $isFollowing ? esc(lang('Web.unfollow')) : esc(lang('Web.follow')) ?></button>
                    </form>
                </div>
            <?php endif ?>

            <section>
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-lg font-extrabold"><?= $isOwnProfile ? "Today's Dailee" : 'Daily logs' ?></h2>
                    <?php if ($isOwnProfile): ?><a href="<?= base_url('capture') ?>" class="text-xs font-bold text-persianblue dark:text-petalfrost"><?= esc(lang('Web.create_log')) ?></a><?php endif ?>
                </div>
                <?php if ($todayMoment): $image = $momentImage($todayMoment); ?>
                    <article class="overflow-hidden rounded-2xl bg-lavender/20 dark:bg-white/5">
                        <?php if ($image): ?><img src="<?= esc($image) ?>" alt="Daily log" class="max-h-[34rem] w-full object-cover"><?php endif ?>
                        <div class="p-4">
                            <p class="text-sm font-bold"><?= esc($todayMoment['caption'] ?: lang('Web.add_caption')) ?></p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-white/55"><?= esc($todayMoment['agenda_title'] ?? '') ?><?= !empty($todayMoment['created_at']) ? ' · ' . esc(date('H:i', strtotime($todayMoment['created_at']))) : '' ?></p>
                        </div>
                    </article>
                <?php else: ?>
                    <div class="rounded-2xl bg-lavender/20 px-5 py-8 text-center text-sm text-gray-500 dark:bg-white/5 dark:text-white/55"><?= esc(lang('Web.no_today_log')) ?></div>
                <?php endif ?>
            </section>

            <section>
                <h2 class="mb-3 text-lg font-extrabold"><?= esc(lang('Web.tab_memories')) ?> <span class="text-sm font-medium text-gray-500 dark:text-white/45"><?= count($moments) ?></span></h2>
                <?php if ($moments): ?>
                    <div class="grid grid-cols-3 gap-2">
                        <?php foreach ($moments as $moment): $image = $momentImage($moment); ?>
                            <div class="relative aspect-[3/4] overflow-hidden rounded-xl bg-lavender/20 dark:bg-white/5">
                                <?php if ($image): ?><img src="<?= esc($image) ?>" alt="Memory" loading="lazy" class="h-full w-full object-cover"><?php endif ?>
                                <?php if (!empty($moment['inset_image'])): ?><img src="<?= base_url('uploads/moments/' . $moment['inset_image']) ?>" alt="Foto kedua" loading="lazy" class="absolute left-1.5 top-1.5 h-12 w-10 rounded-lg border border-white object-cover"><?php endif ?>
                                <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 p-2 text-[10px] font-semibold"><?= !empty($moment['created_at']) ? esc($formatDate($moment['created_at'])) : '' ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <div class="rounded-2xl bg-lavender/20 px-5 py-8 text-center text-sm text-gray-500 dark:bg-white/5 dark:text-white/55"><?= esc(lang('Web.no_memories')) ?></div>
                <?php endif ?>
            </section>
        </div>
    </section>
</div>

<script>
async function shareProfile() {
    const data = { title: <?= json_encode($displayName . ' di Dailee') ?>, url: window.location.href };
    try {
        if (navigator.share) await navigator.share(data);
        else { await navigator.clipboard.writeText(data.url); alert('Link profil disalin.'); }
    } catch (error) { if (error.name !== 'AbortError') alert('Link profil belum bisa dibagikan.'); }
}
</script>

<?= $this->endSection() ?>
