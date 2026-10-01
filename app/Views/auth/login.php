<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="flex items-center justify-center min-h-[70vh] px-4 py-8">
    <div class="bg-glaucous/20 dark:bg-darkcard/60 backdrop-blur-lg border border-white/30 dark:border-white/10 p-8 sm:p-10 rounded-[24px] shadow-xl w-full max-w-md text-center">
        <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-lavender dark:bg-white/10">
            <img src="<?= base_url('favicon.svg'); ?>" alt="" class="h-9 w-9">
        </div>
        <h2 class="text-3xl font-extrabold text-midnight dark:text-white mb-2">Masuk ke dailee.com</h2>
        <p class="text-midnight/70 dark:text-white/70 mb-6 text-sm">Catat jadwal dan abadikan momenmu hari ini.</p>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="bg-emerald-500/90 text-white p-3.5 rounded-xl mb-6 text-sm font-semibold shadow-md">
                <?= esc(session()->getFlashdata('success')); ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-red-500/90 text-white p-3.5 rounded-xl mb-6 text-sm font-semibold shadow-md">
                <?= esc(session()->getFlashdata('error')); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login'); ?>" method="post" class="space-y-5">
            <?= csrf_field(); ?>
            <div class="text-left">
                <label for="username" class="block text-midnight dark:text-white font-bold mb-1 text-sm">Username</label>
                <input id="username" type="text" name="username" autocomplete="username" class="w-full px-4 py-3 rounded-xl border-none focus:ring-2 focus:ring-persianblue bg-white/80 dark:bg-white/10 dark:text-white outline-none transition" placeholder="Masukkan username..." required>
            </div>
            <div class="text-left">
                <label for="password" class="block text-midnight dark:text-white font-bold mb-1 text-sm">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password" class="w-full px-4 py-3 rounded-xl border-none focus:ring-2 focus:ring-persianblue bg-white/80 dark:bg-white/10 dark:text-white outline-none transition" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full bg-persianblue text-white font-bold py-3 rounded-xl hover:bg-midnight transition-colors shadow-md mt-2">Masuk ke dailee.com</button>
        </form>

        <div class="my-5 flex items-center gap-3 text-xs font-semibold text-midnight/50 dark:text-white/50">
            <span class="h-px flex-1 bg-midnight/10 dark:bg-white/15"></span><span>ATAU</span><span class="h-px flex-1 bg-midnight/10 dark:bg-white/15"></span>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <form action="<?= site_url('login/demo/google'); ?>" method="post">
                <?= csrf_field(); ?>
                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl border border-midnight/10 dark:border-white/15 bg-white/70 dark:bg-white/5 px-3 py-3 text-sm font-bold text-midnight dark:text-white hover:border-persianblue hover:shadow-md transition">
                <svg aria-hidden="true" viewBox="0 0 48 48" class="h-5 w-5"><path fill="#4285F4" d="M43.6 24.5c0-1.4-.1-2.8-.4-4.1H24v7.8h11a9.4 9.4 0 0 1-4.1 6.2v5h6.6c3.9-3.6 6.1-8.8 6.1-14.9Z"/><path fill="#34A853" d="M24 44c5.5 0 10.1-1.8 13.5-4.8l-6.6-5c-1.8 1.2-4 1.9-6.9 1.9-5.3 0-9.8-3.6-11.4-8.4h-6.8v5.1A20 20 0 0 0 24 44Z"/><path fill="#FBBC05" d="M12.6 27.7a12 12 0 0 1 0-7.4v-5.1H5.8a20 20 0 0 0 0 17.6l6.8-5.1Z"/><path fill="#EA4335" d="M24 12c3 0 5.7 1 7.8 3.1l5.8-5.8C34.1 6 29.5 4 24 4A20 20 0 0 0 5.8 15.2l6.8 5.1C14.2 15.6 18.7 12 24 12Z"/></svg>
                    Google (Demo)
                </button>
            </form>
            <form action="<?= site_url('login/demo/apple'); ?>" method="post">
                <?= csrf_field(); ?>
                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl border border-midnight/10 dark:border-white/15 bg-white/70 dark:bg-white/5 px-3 py-3 text-sm font-bold text-midnight dark:text-white hover:border-persianblue hover:shadow-md transition">
                <svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5 fill-current"><path d="M16.37 12.2c.02 2.13 1.87 2.84 1.89 2.85-.02.05-.3 1.02-.98 2.02-.59.86-1.2 1.71-2.16 1.73-.94.02-1.24-.56-2.31-.56-1.08 0-1.42.54-2.3.58-.92.03-1.63-.93-2.22-1.78-1.2-1.74-2.12-4.92-.88-7.07a3.43 3.43 0 0 1 2.88-1.75c.9-.02 1.75.61 2.3.61.54 0 1.55-.76 2.62-.65.45.02 1.72.18 2.54 1.38-.07.04-1.52.89-1.5 2.64ZM14.65 7.97a3.25 3.25 0 0 0 .74-2.34 3.3 3.3 0 0 0-2.12 1.08 3.06 3.06 0 0 0-.76 2.27 2.74 2.74 0 0 0 2.14-1.01Z"/></svg>
                    Apple (Demo)
                </button>
            </form>
        </div>
        <p class="mt-3 text-[11px] leading-relaxed text-midnight/55 dark:text-white/50">Mode demo lokal: tombol ini masuk ke akun uji, bukan akun Google atau Apple sungguhan.</p>

        <div class="mt-6 border-t border-white/30 dark:border-white/10 pt-4">
            <p class="text-sm text-midnight dark:text-white">Belum punya akun? <a href="<?= base_url('register'); ?>" class="font-bold text-persianblue hover:underline">Daftar di sini</a></p>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
