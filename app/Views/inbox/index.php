<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-5xl pb-28 text-midnight dark:text-white">
    <div class="mb-5 flex items-center justify-between">
        <div><p class="text-xs font-bold uppercase tracking-[.2em] text-persianblue">Dailee</p><h1 class="text-2xl font-black"><?= esc(lang('Web.inbox')) ?></h1></div>
        <a href="<?= base_url('friends') ?>" class="rounded-full bg-persianblue px-4 py-2 text-xs font-bold text-white"><?= esc(lang('Web.find_friends')) ?></a>
    </div>

    <?php foreach (['message' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-200', 'error' => 'bg-rose-500/10 text-rose-700 dark:text-rose-200'] as $flash => $style): ?>
        <?php if ($notice = session()->getFlashdata($flash)): ?><div class="mb-4 rounded-2xl px-4 py-3 text-sm <?= $style ?>"><?= esc($notice) ?></div><?php endif ?>
    <?php endforeach ?>

    <div class="grid min-h-[65vh] overflow-hidden rounded-3xl border border-black/5 bg-white/80 shadow-xl dark:border-white/10 dark:bg-darkcard/80 md:grid-cols-[17rem_1fr]">
        <aside class="border-b border-black/5 dark:border-white/10 md:border-b-0 md:border-r">
            <div class="border-b border-black/5 p-4 text-sm font-extrabold dark:border-white/10"><?= esc(lang('Web.inbox_title')) ?> <span class="text-xs font-medium text-gray-400">· <?= esc(lang('Web.mutual_friends_only')) ?></span></div>
            <?php if (empty($friends)): ?>
                <div class="p-5 text-center text-sm text-gray-500"><?= esc(lang('Web.no_friends')) ?></div>
            <?php else: ?>
                <div class="max-h-60 space-y-1 overflow-y-auto p-2 md:max-h-[60vh]">
                    <?php foreach ($friends as $friend):
                        $selected = $activeFriend && (int) $activeFriend['id'] === (int) $friend['id'];
                        $friendName = $friend['nama_lengkap'] ?: ($friend['name'] ?? $friend['username']);
                    ?>
                        <a href="<?= base_url('inbox/' . (int) $friend['id']) ?>" class="flex items-center gap-3 rounded-2xl p-3 <?= $selected ? 'bg-persianblue/10' : 'hover:bg-black/5 dark:hover:bg-white/5' ?>">
                            <?php if (!empty($friend['avatar'])): ?>
                                <img src="<?= base_url('uploads/avatars/' . $friend['avatar']) ?>" alt="" class="h-11 w-11 rounded-full object-cover">
                            <?php else: ?>
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-persianblue font-black text-white"><?= esc(strtoupper(substr($friendName, 0, 1))) ?></span>
                            <?php endif ?>
                            <span class="min-w-0 flex-1">
                                <strong class="block truncate text-sm"><?= esc($friendName) ?></strong>
                                <span class="block truncate text-xs text-gray-500"><?= esc($friend['last_message'] ?? '@' . $friend['username']) ?></span>
                            </span>
                            <?php if (!empty($friend['last_time'])): ?><span class="text-[10px] text-gray-400"><?= esc(date('H:i', strtotime($friend['last_time']))) ?></span><?php endif ?>
                        </a>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </aside>

        <section class="flex min-h-[52vh] flex-col">
            <?php if ($activeFriend):
                $activeName = $activeFriend['nama_lengkap'] ?: ($activeFriend['name'] ?? $activeFriend['username']);
            ?>
                <header class="flex items-center gap-3 border-b border-black/5 p-4 dark:border-white/10">
                    <?php if (!empty($activeFriend['avatar'])): ?><img src="<?= base_url('uploads/avatars/' . $activeFriend['avatar']) ?>" alt="" class="h-10 w-10 rounded-full object-cover"><?php else: ?><span class="flex h-10 w-10 items-center justify-center rounded-full bg-persianblue font-bold text-white"><?= esc(strtoupper(substr($activeName, 0, 1))) ?></span><?php endif ?>
                    <a href="<?= base_url('profile/' . rawurlencode($activeFriend['username'])) ?>" class="font-extrabold hover:text-persianblue"><?= esc($activeName) ?><span class="ml-2 text-xs font-normal text-gray-500">@<?= esc($activeFriend['username']) ?></span></a>
                </header>
                <div id="message-list" class="flex-1 space-y-3 overflow-y-auto p-4 sm:p-6">
                    <?php if (empty($messages)): ?><p class="pt-10 text-center text-sm text-gray-500"><?= esc(lang('Web.say_hello')) ?></p><?php endif ?>
                    <?php foreach ($messages as $message): $mine = (int) $message['sender_id'] === (int) $currentUserId; ?>
                        <div class="flex <?= $mine ? 'justify-end' : 'justify-start' ?>">
                            <div class="max-w-[82%] rounded-2xl px-4 py-2.5 <?= $mine ? 'rounded-br-md bg-persianblue text-white' : 'rounded-bl-md bg-black/5 dark:bg-white/10' ?>">
                                <p class="whitespace-pre-wrap break-words text-sm"><?= esc($message['message']) ?></p>
                                <time class="mt-1 block text-right text-[10px] opacity-60"><?= esc(date('H:i', strtotime($message['created_at']))) ?></time>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <form action="<?= base_url('inbox/send/' . (int) $activeFriend['id']) ?>" method="post" class="flex gap-2 border-t border-black/5 p-3 dark:border-white/10 sm:p-4">
                    <?= csrf_field() ?>
                    <input name="message" maxlength="2000" required autocomplete="off" placeholder="<?= esc(lang('Web.write_message')) ?>" class="min-w-0 flex-1 rounded-full bg-black/5 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-persianblue dark:bg-white/10">
                    <button class="rounded-full bg-persianblue px-5 py-3 text-sm font-bold text-white hover:bg-midnight"><?= esc(lang('Web.send')) ?></button>
                </form>
                <script>const messageList = document.getElementById('message-list'); if (messageList) messageList.scrollTop = messageList.scrollHeight;</script>
            <?php else: ?>
                <div class="flex flex-1 flex-col items-center justify-center p-8 text-center">
                    <span class="mb-3 text-5xl">✈️</span><h2 class="text-lg font-extrabold"><?= esc(lang('Web.choose_friend')) ?></h2>
                    <p class="mt-1 max-w-sm text-sm text-gray-500"><?= esc(lang('Web.mutual_dm_only')) ?></p>
                </div>
            <?php endif ?>
        </section>
    </div>
</div>

<?= $this->endSection() ?>
