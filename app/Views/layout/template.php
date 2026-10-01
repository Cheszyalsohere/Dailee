<!DOCTYPE html>
<html lang="<?= session()->get('lang') ?? 'id'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc(lang('Web.page_title')) ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg?v=1') ?>">
    <meta name="theme-color" content="#4D3EA3">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        lavender: '#E1DAFB',
                        petalfrost: '#FFD2F4',
                        glaucous: '#758AD1',
                        persianblue: '#4D3EA3',
                        midnight: '#450C3F',
                        darkbg: '#121212',
                        darkcard: '#1E1E2E'
                    }
                }
            }
        }
    </script>
    <script>
        const daileeTheme = localStorage.getItem('dailee-theme') || 'light';
        document.documentElement.classList.toggle('dark', daileeTheme === 'dark');
    </script>
    <style>
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 20s linear infinite;
        }
        .notification-count {
            display: inline-flex; min-width: 1.25rem; height: 1.25rem; align-items: center; justify-content: center;
            border-radius: 9999px; background: #e34f73; padding: 0 .35rem; color: white; font-size: 9px; font-weight: 900; line-height: 1;
        }
        .notification-count.hidden { display: none; }
        #notification-count-main { position: absolute; top: -4px; right: -5px; }
        .landing-ambient { position: absolute; inset: -18%; overflow: hidden; pointer-events: none; z-index: 0; }
        .landing-ambient::before, .landing-ambient::after { content: ""; position: absolute; width: min(50vw, 38rem); aspect-ratio: 1; border-radius: 9999px; filter: blur(78px); opacity: .58; will-change: transform; }
        .landing-ambient::before { left: 7%; top: 12%; background: radial-gradient(circle, rgba(117,138,209,.55), rgba(117,138,209,0) 70%); animation: dailee-float-a 19s ease-in-out infinite alternate; }
        .landing-ambient::after { right: 4%; bottom: 3%; background: radial-gradient(circle, rgba(255,210,244,.72), rgba(225,218,251,0) 72%); animation: dailee-float-b 24s ease-in-out infinite alternate; }
        .landing-hero-glass { isolation: isolate; border: 1px solid rgba(255,255,255,.64); background: linear-gradient(125deg,rgba(255,255,255,.24),rgba(225,218,251,.16),rgba(255,210,244,.18)); backdrop-filter: blur(26px) saturate(135%); box-shadow: 0 28px 90px rgba(77,62,163,.08); }
        .dark .landing-hero-glass { border-color: rgba(255,255,255,.12); background: linear-gradient(125deg,rgba(30,30,46,.32),rgba(77,62,163,.17),rgba(69,12,63,.23)); box-shadow: 0 28px 90px rgba(0,0,0,.2); }
        @keyframes dailee-float-a { from { transform: translate3d(-5%,0,0) scale(.92); } to { transform: translate3d(28%,12%,0) scale(1.16); } }
        @keyframes dailee-float-b { from { transform: translate3d(5%,2%,0) scale(1.04); } to { transform: translate3d(-24%,-16%,0) scale(.9); } }
        @media (prefers-reduced-motion: reduce) { .landing-ambient::before, .landing-ambient::after { animation: none; } }
    </style>
</head>
<body class="bg-gradient-to-br from-lavender via-white to-petalfrost dark:from-darkbg dark:via-darkcard dark:to-midnight text-midnight dark:text-gray-100 min-h-screen font-sans transition-colors duration-300">

    <?php 
        // Cek status login
        $isLoggedIn = session()->get('isLoggedIn') ?? session()->get('logged_in') ?? false; 
        $activity = ['unread' => 0, 'unread_messages' => 0, 'friend_requests' => 0, 'due_tasks' => 0, 'items' => []];
        if ($isLoggedIn) {
            $activity = (new \App\Libraries\NotificationService())->summary((int) (session()->get('id') ?? session()->get('user_id')));
        }
        $badge = static function (int $count, string $id): string {
            $hidden = $count > 0 ? '' : ' hidden';
            $label = $count > 99 ? '99+' : (string) $count;
            return '<span id="' . esc($id) . '" class="notification-count' . $hidden . '" aria-label="' . esc((string) $count) . '">' . esc($label) . '</span>';
        };
    ?>

    <div class="flex min-h-screen relative">

        <!-- SIDEBAR KIRI (Hanya muncul jika sudah Login) -->
        <?php if ($isLoggedIn): ?>
            <aside class="w-64 bg-white/70 dark:bg-darkcard/70 backdrop-blur-md border-r border-white/40 dark:border-white/10 p-5 hidden md:block flex-shrink-0 sticky top-0 h-screen z-20 overflow-y-auto">
                <nav class="space-y-2 font-bold text-xs">
                    <a href="<?= base_url('friends'); ?>" class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl hover:bg-white/60 dark:hover:bg-white/10 transition"><span><?= esc(lang('Web.friends')) ?></span><?= $badge((int) $activity['friend_requests'], 'friends-count') ?></a>
                    <a href="<?= base_url('inbox'); ?>" class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl hover:bg-white/60 dark:hover:bg-white/10 transition"><span><?= esc(lang('Web.inbox')) ?></span><?= $badge((int) $activity['unread_messages'], 'inbox-count') ?></a>
                    <a href="<?= base_url('profile'); ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/60 dark:hover:bg-white/10 transition"><?= esc(lang('Web.profile')) ?></a>
                    <a href="<?= base_url('dashboard'); ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/60 dark:hover:bg-white/10 transition"><?= esc(lang('Web.home')) ?></a>
                    <a href="<?= base_url('chat'); ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/60 dark:hover:bg-white/10 transition"><?= esc(lang('Web.ai')) ?></a>
                    <a href="<?= base_url('capture'); ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/60 dark:hover:bg-white/10 transition"><?= esc(lang('Web.capture')) ?></a>
                    <a href="<?= base_url('memories'); ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/60 dark:hover:bg-white/10 transition"><?= esc(lang('Web.moments')) ?></a>
                    <a href="<?= base_url('todo'); ?>" class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl hover:bg-white/60 dark:hover:bg-white/10 transition"><span><?= esc(lang('Web.tasks')) ?></span><?= $badge((int) $activity['due_tasks'], 'tasks-count') ?></a>
                </nav>
            </aside>
        <?php endif; ?>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- NAVBAR / TOPBAR -->
            <header class="h-16 bg-white/40 dark:bg-darkcard/40 backdrop-blur-md border-b border-white/40 dark:border-white/10 px-6 flex items-center justify-between sticky top-0 z-30">
                <!-- Logo Brand -->
                <a href="<?= base_url(); ?>" class="text-xl font-black tracking-tighter uppercase text-persianblue dark:text-petalfrost hover:opacity-80 transition">
    DAILEE<span class="text-midnight dark:text-white">.COM</span>
</a>

                <!-- Nav Menu Kanan -->
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 rounded-full border border-persianblue/15 bg-white/60 p-1 dark:border-white/10 dark:bg-white/5" aria-label="Language">
                        <a href="<?= base_url('lang/id'); ?>" class="rounded-full px-2 py-1 text-[10px] font-extrabold <?= (session()->get('lang') ?? 'id') === 'id' ? 'bg-persianblue text-white' : 'text-midnight/60 dark:text-white/60' ?>">ID</a>
                        <a href="<?= base_url('lang/en'); ?>" class="rounded-full px-2 py-1 text-[10px] font-extrabold <?= (session()->get('lang') ?? 'id') === 'en' ? 'bg-persianblue text-white' : 'text-midnight/60 dark:text-white/60' ?>">EN</a>
                    </div>
                    <button id="theme-toggle" type="button" class="h-8 w-8 rounded-full bg-white/70 text-sm font-bold text-midnight shadow-sm dark:bg-white/10 dark:text-white" aria-label="<?= esc(lang('Web.theme')) ?>">◐</button>
                    <?php if ($isLoggedIn): ?>
                        <div class="relative" id="notification-root">
                            <button id="notification-toggle" type="button" class="relative flex h-9 w-9 items-center justify-center rounded-full bg-white/70 text-lg text-midnight shadow-sm transition hover:bg-white dark:bg-white/10 dark:text-white dark:hover:bg-white/15" aria-label="<?= esc(lang('Web.notifications')) ?>" aria-expanded="false" aria-controls="notification-menu">
                                <span aria-hidden="true">&#128276;</span><?= $badge((int) $activity['unread'], 'notification-count-main') ?>
                            </button>
                            <section id="notification-menu" class="absolute right-0 top-12 z-50 hidden w-[min(24rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-persianblue/15 bg-white/95 text-midnight shadow-2xl backdrop-blur-2xl dark:border-white/10 dark:bg-darkcard/95 dark:text-white" aria-label="<?= esc(lang('Web.notifications')) ?>">
                                <div class="flex items-center justify-between border-b border-black/5 px-4 py-3 dark:border-white/10">
                                    <h2 class="text-sm font-black"><?= esc(lang('Web.notifications')) ?></h2>
                                    <button id="notification-read-all" type="button" class="text-[10px] font-bold text-persianblue hover:underline dark:text-petalfrost"><?= esc(lang('Web.mark_all_read')) ?></button>
                                </div>
                                <div id="notification-list" class="max-h-[min(70vh,28rem)] overflow-y-auto p-2">
                                    <?php if (empty($activity['items'])): ?>
                                        <p class="px-3 py-7 text-center text-xs opacity-60"><?= esc(lang('Web.notifications_empty')) ?></p>
                                    <?php else: ?>
                                        <?php foreach ($activity['items'] as $notification): ?>
                                            <?php $titleKey = 'Web.notification_' . $notification['type']; ?>
                                            <a href="<?= esc($notification['href']) ?>" data-notification-link data-notification-id="<?= (int) $notification['id'] ?>" class="flex gap-3 rounded-xl px-3 py-3 transition hover:bg-lavender/60 dark:hover:bg-white/10 <?= empty($notification['is_read']) ? 'bg-persianblue/5 dark:bg-white/5' : '' ?>">
                                                <span class="mt-1 h-2.5 w-2.5 flex-none rounded-full <?= empty($notification['is_read']) ? 'bg-persianblue dark:bg-petalfrost' : 'bg-transparent' ?>" data-unread-dot></span>
                                                <span class="min-w-0 flex-1">
                                                    <span class="block text-xs"><strong><?= esc($notification['actor_name']) ?></strong> <?= esc(lang($titleKey)) ?></span>
                                                    <?php if (!empty($notification['body'])): ?><span class="mt-1 block truncate text-[11px] opacity-70"><?= esc($notification['body']) ?></span><?php endif; ?>
                                                    <time class="mt-1 block text-[10px] opacity-50"><?= esc($notification['created_at'] ? date('d M H:i', strtotime($notification['created_at'])) : '') ?></time>
                                                </span>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </section>
                        </div>
                        <span class="text-xs font-bold opacity-75 hidden sm:inline-block"><?= esc(lang('Web.hello')) ?>, <?= esc(session()->get('nama_lengkap') ?? session()->get('username') ?? 'User'); ?>!</span>
                        <a href="<?= base_url('logout'); ?>" class="px-4 py-2 rounded-full bg-red-500/10 text-red-500 font-bold text-xs hover:bg-red-500 hover:text-white transition"><?= esc(lang('App.logout')) ?></a>
                    <?php else: ?>
                        <a href="<?= base_url('login'); ?>" class="px-6 py-2.5 rounded-full bg-persianblue text-white font-black text-xs uppercase tracking-wider hover:bg-midnight transition shadow-lg shadow-persianblue/20">
                            Masuk
                        </a>
                    <?php endif; ?>
                </div>
            </header>

            <!-- KONTEN UTAMA (View yang di-render) -->
            <main class="flex-1 p-4 pb-24 md:p-6 md:pb-6">
                <?= $this->renderSection('content'); ?>
            </main>

            <!-- FOOTER SIMPLE -->
            <footer class="py-6 border-t border-black/5 dark:border-white/5 text-center text-xs opacity-50">
                &copy; <?= date('Y'); ?> dailee.com. All rights reserved.
            </footer>

        </div>

        <!-- SIDEBAR KANAN (Hanya muncul jika sudah Login) -->
        <?php if ($isLoggedIn): ?>
            <aside class="w-72 bg-white/70 dark:bg-darkcard/70 backdrop-blur-md border-l border-white/40 dark:border-white/10 p-5 hidden lg:block flex-shrink-0 sticky top-0 h-screen z-20 overflow-y-auto">
                <div class="bg-white/80 dark:bg-darkcard/80 p-4 rounded-2xl border border-black/5 dark:border-white/5 text-xs">
                    <h4 class="font-extrabold text-persianblue dark:text-petalfrost uppercase mb-2">⚡ Info Quick Log</h4>
                    <p class="opacity-70"><?= esc(lang('Web.quick_info')) ?></p>
                </div>
            </aside>
        <?php endif; ?>

        <?php if ($isLoggedIn): ?>
            <nav class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-5 border-t border-black/10 bg-white/90 px-2 pb-[max(.5rem,env(safe-area-inset-bottom))] pt-2 text-center text-[10px] font-bold text-midnight shadow-2xl backdrop-blur-xl dark:border-white/10 dark:bg-[#151519]/95 dark:text-white md:hidden">
                <a href="<?= base_url('dashboard') ?>" class="flex flex-col items-center gap-1 py-1"><span class="text-lg">⌂</span><?= esc(lang('Web.home')) ?></a>
                <a href="<?= base_url('inbox') ?>" class="flex flex-col items-center gap-1 py-1"><span class="text-lg">✈</span><?= esc(lang('Web.inbox')) ?></a>
                <a href="<?= base_url('capture') ?>" class="flex flex-col items-center gap-1 py-1"><span class="flex h-11 w-11 -translate-y-4 items-center justify-center rounded-full bg-persianblue text-2xl text-white shadow-lg">＋</span><?= esc(lang('Web.capture')) ?></a>
                <a href="<?= base_url('memories') ?>" class="flex flex-col items-center gap-1 py-1"><span class="text-lg">▦</span><?= esc(lang('Web.moments')) ?></a>
                <a href="<?= base_url('profile') ?>" class="flex flex-col items-center gap-1 py-1"><span class="text-lg">◉</span><?= esc(lang('Web.profile')) ?></a>
            </nav>
        <?php endif; ?>

    </div>

<script>
    (function () {
        const root = document.getElementById('notification-root');
        if (!root) return;
        const toggle = document.getElementById('notification-toggle');
        const menu = document.getElementById('notification-menu');
        const list = document.getElementById('notification-list');
        const feedUrl = <?= json_encode(site_url('notifications/feed')) ?>;
        const readUrl = <?= json_encode(site_url('notifications/read')) ?>;
        const readAllUrl = <?= json_encode(site_url('notifications/read-all')) ?>;
        const emptyLabel = <?= json_encode(lang('Web.notifications_empty')) ?>;

        const updateBadge = (id, count) => {
            const badge = document.getElementById(id);
            if (!badge) return;
            badge.textContent = count > 99 ? '99+' : String(count);
            badge.setAttribute('aria-label', String(count));
            badge.classList.toggle('hidden', count < 1);
        };
        const setCount = (data) => {
            updateBadge('notification-count-main', data.unread || 0);
            updateBadge('friends-count', data.friend_requests || 0);
            updateBadge('inbox-count', data.unread_messages || 0);
            updateBadge('tasks-count', data.due_tasks || 0);
        };
        const renderItems = (items) => {
            list.replaceChildren();
            if (!items.length) {
                const empty = document.createElement('p');
                empty.className = 'px-3 py-7 text-center text-xs opacity-60';
                empty.textContent = emptyLabel;
                list.append(empty);
                return;
            }
            for (const item of items) {
                const link = document.createElement('a');
                link.href = item.href;
                link.dataset.notificationLink = '';
                link.dataset.notificationId = String(item.id);
                link.className = 'flex gap-3 rounded-xl px-3 py-3 transition hover:bg-lavender/60 dark:hover:bg-white/10';
                if (!item.is_read) link.classList.add('bg-persianblue/5', 'dark:bg-white/5');
                const dot = document.createElement('span');
                dot.dataset.unreadDot = '';
                dot.className = `mt-1 h-2.5 w-2.5 flex-none rounded-full ${item.is_read ? 'bg-transparent' : 'bg-persianblue dark:bg-petalfrost'}`;
                const content = document.createElement('span');
                content.className = 'min-w-0 flex-1';
                const title = document.createElement('span');
                title.className = 'block text-xs';
                const actor = document.createElement('strong');
                actor.textContent = item.actor_name || 'Dailee';
                title.append(actor, document.createTextNode(` ${item.title || ''}`));
                content.append(title);
                if (item.body) {
                    const body = document.createElement('span');
                    body.className = 'mt-1 block truncate text-[11px] opacity-70';
                    body.textContent = item.body;
                    content.append(body);
                }
                const time = document.createElement('time');
                time.className = 'mt-1 block text-[10px] opacity-50';
                time.textContent = item.time_label || '';
                content.append(time);
                link.append(dot, content);
                list.append(link);
            }
        };
        const refresh = async () => {
            try {
                const response = await fetch(feedUrl, { headers: { Accept: 'application/json' } });
                const data = await response.json();
                if (!response.ok || data.status !== 'success') return;
                setCount(data);
                renderItems(data.items || []);
            } catch (error) {
                // Retain the last visible items during a temporary connection problem.
            }
        };

        toggle.addEventListener('click', () => {
            const opening = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !opening);
            toggle.setAttribute('aria-expanded', String(opening));
            if (opening) refresh();
        });
        document.addEventListener('click', async (event) => {
            const link = event.target.closest('[data-notification-link]');
            if (!link || !root.contains(link)) return;
            event.preventDefault();
            try {
                await fetch(`${readUrl}/${encodeURIComponent(link.dataset.notificationId)}`, { method: 'POST', headers: { Accept: 'application/json' } });
            } finally {
                window.location.assign(link.href);
            }
        });
        document.getElementById('notification-read-all')?.addEventListener('click', async () => {
            await fetch(readAllUrl, { method: 'POST', headers: { Accept: 'application/json' } });
            await refresh();
        });
        document.addEventListener('click', (event) => {
            if (!root.contains(event.target)) {
                menu.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                menu.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
        window.setInterval(() => {
            if (document.visibilityState === 'visible') refresh();
        }, 30000);
    })();

    (function () {
        const button = document.getElementById('theme-toggle');
        if (!button) return;
        const paint = () => {
            const dark = document.documentElement.classList.contains('dark');
            button.textContent = dark ? '☀' : '◐';
            button.title = dark ? <?= json_encode(lang('Web.light')) ?> : <?= json_encode(lang('Web.dark')) ?>;
        };
        paint();
        button.addEventListener('click', () => {
            const dark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('dailee-theme', dark ? 'dark' : 'light');
            paint();
        });
    })();
</script>
</body>
</html>
