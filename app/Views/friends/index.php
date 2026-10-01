<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="max-w-md mx-auto pb-24">

    <!-- Header & Back -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-black text-midnight dark:text-white"><?= esc(lang('Web.friends_title')) ?> 👥</h1>
        <a href="<?= base_url('profile'); ?>" class="text-xs font-bold text-gray-500 hover:text-midnight dark:hover:text-white"><?= esc(lang('Web.back')) ?></a>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-emerald-500 text-white text-xs font-bold p-3 rounded-2xl mb-4 shadow">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500 text-white text-xs font-bold p-3 rounded-2xl mb-4 shadow">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Form Add Friend by Username -->
    <div class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-2xl rounded-3xl p-5 border border-white/60 dark:border-white/10 shadow-xl mb-6">
        <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost mb-3"><?= esc(lang('Web.find_add_friends')) ?></h3>
        <form action="<?= base_url('friends/add'); ?>" method="POST" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-400">@</span>
                <input type="text" name="username" class="w-full pl-8 pr-4 py-2.5 rounded-xl bg-gray-100 dark:bg-white/5 border border-transparent focus:border-persianblue outline-none text-xs font-bold text-midnight dark:text-white" placeholder="<?= esc(lang('Web.friend_username_placeholder')) ?>" required>
            </div>
            <button type="submit" class="bg-persianblue text-white text-xs font-black px-5 py-2.5 rounded-xl shadow hover:bg-midnight transition">
                <?= esc(lang('Web.add')) ?>
            </button>
        </form>
    </div>

    <!-- SEKSI PERMINTAAN PERTEMANAN (FRIEND REQUESTS) -->
    <?php if (!empty($requests)): ?>
        <div class="bg-white/90 dark:bg-darkcard/90 backdrop-blur-2xl rounded-3xl p-5 border border-persianblue/30 shadow-xl mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost flex items-center gap-1.5">
                    <span>📩</span> <?= esc(lang('Web.friend_requests')) ?>
                </h3>
                <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-red-500 text-white animate-pulse">
                    <?= count($requests); ?> <?= esc(lang('Web.new')) ?>
                </span>
            </div>

            <div class="space-y-2.5">
                <?php foreach ($requests as $req): ?>
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-white/60 dark:bg-white/5 border border-black/5 dark:border-white/5">
                        <div class="flex items-center gap-3">
                            <?php 
                                $avatar = (!empty($req['avatar']) && file_exists(FCPATH . 'uploads/avatars/' . $req['avatar']))
                                    ? base_url('uploads/avatars/' . $req['avatar']) 
                                    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200';
                            ?>
                            <img src="<?= $avatar; ?>" class="w-10 h-10 rounded-full object-cover shadow-sm">
                            <div class="text-left">
                                <p class="text-xs font-black text-midnight dark:text-white leading-tight">
                                    <?= esc($req['nama_lengkap'] ?: $req['username']); ?>
                                </p>
                                <span class="text-[10px] text-gray-400">@<?= esc($req['username']); ?></span>
                            </div>
                        </div>

                        <!-- Tombol Accept & Tolak (X) -->
                        <div class="flex items-center gap-1.5">
                            <!-- Tombol Accept -->
                            <form action="<?= base_url('friends/accept/' . $req['request_id']); ?>" method="POST">
                                <button type="submit" class="bg-persianblue hover:bg-midnight text-white text-[11px] font-bold px-3 py-1.5 rounded-xl shadow transition flex items-center gap-1">
                                    <span>✓</span> Accept
                                </button>
                            </form>

                            <!-- Tombol Tolak (X) -->
                            <form action="<?= base_url('friends/reject/' . $req['request_id']); ?>" method="POST">
                                <button type="submit" class="w-7 h-7 bg-gray-200 dark:bg-white/10 hover:bg-red-500 hover:text-white text-gray-600 dark:text-gray-300 rounded-xl flex items-center justify-center text-xs font-bold transition" title="Tolak">
                                    ✕
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Temukan akun, kirim permintaan mutual, atau ikuti secara terpisah -->
    <div class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-2xl rounded-3xl p-5 border border-white/60 dark:border-white/10 shadow-xl mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-midnight dark:text-white"><?= esc(lang('Web.find_people')) ?></h3>
            <span class="text-[10px] text-gray-500"><?= esc(lang('Web.follow_note')) ?></span>
        </div>
        <?php if (empty($discover)): ?>
            <p class="py-4 text-center text-xs text-gray-500"><?= esc(lang('Web.no_other_accounts')) ?></p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($discover as $person): ?>
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-white/60 dark:bg-white/5 p-3">
                        <a href="<?= base_url('profile/' . rawurlencode($person['username'])) ?>" class="flex min-w-0 items-center gap-3">
                            <?php $personAvatar = !empty($person['avatar']) ? base_url('uploads/avatars/' . $person['avatar']) : null; ?>
                            <?php if ($personAvatar): ?>
                                <img src="<?= esc($personAvatar) ?>" alt="" class="h-10 w-10 rounded-full object-cover">
                            <?php else: ?>
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-persianblue text-sm font-black text-white"><?= esc(strtoupper(substr($person['username'], 0, 1))) ?></span>
                            <?php endif ?>
                            <span class="min-w-0 text-left">
                                <strong class="block truncate text-xs text-midnight dark:text-white"><?= esc($person['nama_lengkap'] ?: ($person['name'] ?: $person['username'])) ?></strong>
                                <span class="text-[10px] text-gray-500">@<?= esc($person['username']) ?></span>
                            </span>
                        </a>
                        <div class="flex flex-wrap items-center gap-2">
                            <?php if ($person['friend_status'] === 'accepted'): ?>
                                <span class="rounded-full bg-emerald-500/10 px-3 py-1.5 text-[10px] font-bold text-emerald-600"><?= esc(lang('Web.mutual')) ?></span>
                            <?php elseif ($person['friend_status'] === 'pending'): ?>
                                <span class="rounded-full bg-gray-200 px-3 py-1.5 text-[10px] font-bold text-gray-600"><?= esc(lang('Web.request_sent_short')) ?></span>
                            <?php elseif ($person['incoming_status'] === 'pending'): ?>
                                <a href="<?= base_url('friends') ?>" class="rounded-full bg-persianblue px-3 py-1.5 text-[10px] font-bold text-white"><?= esc(lang('Web.accept_request')) ?></a>
                            <?php else: ?>
                                <form action="<?= base_url('friends/add') ?>" method="post">
                                    <?= csrf_field() ?><input type="hidden" name="username" value="<?= esc($person['username'], 'attr') ?>">
                                    <button class="rounded-full bg-persianblue px-3 py-1.5 text-[10px] font-bold text-white">＋ Teman</button>
                                </form>
                            <?php endif ?>
                            <form action="<?= base_url('follow/toggle/' . (int) $person['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <button class="rounded-full border border-gray-300 px-3 py-1.5 text-[10px] font-bold text-midnight dark:border-white/20 dark:text-white"><?= $person['is_following'] ? esc(lang('Web.unfollow')) : esc(lang('Web.follow')) ?></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>

    <!-- DAFTAR TEMAN MUTUAL -->
    <div class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-2xl rounded-3xl p-5 border border-white/60 dark:border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-midnight dark:text-white"><?= esc(lang('Web.your_friends')) ?></h3>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-persianblue/10 text-persianblue dark:text-petalfrost"><?= count($friends); ?> <?= esc(lang('Web.mutual')) ?></span>
        </div>

        <?php if (empty($friends)): ?>
            <div class="text-center py-6">
                <span class="text-3xl block mb-2">🤝</span>
                <p class="text-xs text-gray-500 font-medium"><?= esc(lang('Web.no_mutual_friends')) ?></p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($friends as $f): ?>
                    <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white/50 dark:bg-white/5 border border-black/5 dark:border-white/5">
                        <div class="flex items-center gap-3">
                            <?php 
                                $avatar = (!empty($f['avatar']) && file_exists(FCPATH . 'uploads/avatars/' . $f['avatar']))
                                    ? base_url('uploads/avatars/' . $f['avatar']) 
                                    : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200';
                            ?>
                            <img src="<?= $avatar; ?>" class="w-10 h-10 rounded-full object-cover shadow-sm">
                            <div class="text-left">
                                <p class="text-xs font-black text-midnight dark:text-white leading-none"><?= esc($f['nama_lengkap'] ?: $f['username']); ?></p>
                                <span class="text-[10px] text-gray-400">@<?= esc($f['username']); ?></span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full">Mutual</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection(); ?>
