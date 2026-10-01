<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="relative w-full overflow-hidden">

    <!-- HERO SECTION -->
    <section class="landing-hero-glass relative mx-auto flex min-h-[85vh] w-full max-w-7xl flex-col items-center justify-center overflow-hidden rounded-[2rem] px-4 py-20 text-center">
        
        <div class="landing-ambient" aria-hidden="true"></div>
        <div class="relative z-10 flex w-full flex-col items-center">

        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/60 dark:bg-white/10 border border-white/80 dark:border-white/10 backdrop-blur-md shadow-sm mb-6 animate-bounce">
            <span class="w-2 h-2 rounded-full bg-persianblue animate-ping"></span>
            <span class="text-xs font-bold tracking-widest uppercase opacity-80">Welcome to Dailee v2.4</span>
        </div>

        <!-- Main Headline (Typography Bold khas Lando Norris style) -->
        <h1 class="text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-black uppercase tracking-tighter leading-none mb-6 text-midnight dark:text-white drop-shadow-sm">
            REDEFINING <span class="text-transparent bg-clip-text bg-gradient-to-r from-persianblue via-purple-500 to-pink-500 hover:scale-105 transition duration-500 inline-block">MEMORIES</span> & LOGS.
        </h1>

        <!-- Subheadline -->
        <p class="max-w-2xl text-sm md:text-lg font-medium opacity-75 mb-10 leading-relaxed">
            Abadikan momen harian, catat skripsi, agenda, dan kolaborasi dalam satu tempat yang estetis, cepat, dan modern.
        </p>

        <!-- Call to Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-4 z-10">
            <a href="<?= base_url('login'); ?>" class="w-full sm:w-auto px-8 py-4 rounded-full bg-persianblue hover:bg-midnight text-white font-black text-sm uppercase tracking-wider transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl hover:shadow-persianblue/30 active:scale-95 text-center">
                Mulai Sekarang ➔
            </a>
            <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white/50 dark:bg-white/10 hover:bg-white dark:hover:bg-white/20 border border-black/5 dark:border-white/10 font-bold text-sm uppercase tracking-wider backdrop-blur-md transition-all duration-300 transform hover:-translate-y-1 text-center">
                Jelajahi Fitur
            </a>
        </div>

        </div>

    </section>


    <!-- STATS / HIGHLIGHT TICKER (MARQUEE RUNNING TEXT) -->
    <div class="w-full py-4 bg-midnight/5 dark:bg-white/5 border-y border-black/5 dark:border-white/10 overflow-hidden whitespace-nowrap mb-20">
        <div class="inline-flex gap-12 animate-marquee font-black text-xs uppercase tracking-widest opacity-60">
            <span>✦ DAILY LOGS</span>
            <span>✦ DUAL-FRAME CAPTURE</span>
            <span>✦ ACADEMIC FOCUS</span>
            <span>✦ SMART CHAT</span>
            <span>✦ MEMORIES CALENDAR</span>
            <span>✦ DAILY LOGS</span>
            <span>✦ DUAL-FRAME CAPTURE</span>
            <span>✦ ACADEMIC FOCUS</span>
            <span>✦ SMART CHAT</span>
            <span>✦ MEMORIES CALENDAR</span>
        </div>
    </div>


    <!-- FEATURES SECTION (GRID CARD INTERAKSI HOVER) -->
    <section id="features" class="max-w-6xl mx-auto px-4 py-12">
        
        <div class="text-center mb-16">
            <h2 class="text-2xl md:text-4xl font-black uppercase tracking-tight mb-3">
                BUILT FOR YOUR DAILY <span class="underline decoration-persianblue decoration-4">RHYTHM</span>
            </h2>
            <p class="text-xs md:text-sm font-semibold opacity-60">Segala yang lu butuhkan dalam satu sistem yang kencang.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Feature Card 1 -->
            <div class="group relative p-8 rounded-3xl bg-white/50 dark:bg-darkcard/50 border border-white/80 dark:border-white/10 backdrop-blur-xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-persianblue/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                <div class="w-12 h-12 rounded-2xl bg-persianblue/10 text-persianblue dark:text-petalfrost flex items-center justify-center text-2xl mb-6 group-hover:rotate-12 transition-transform duration-300">
                    📷
                </div>
                <h3 class="text-xl font-extrabold mb-2 uppercase tracking-wide">Dual-Frame Capture</h3>
                <p class="text-xs opacity-70 leading-relaxed">
                    Tangkap foto dari dua sudut sekaligus atau gabungkan momen favorit lu dalam satu layout frame.
                </p>
            </div>

            <!-- Feature Card 2 -->
            <div class="group relative p-8 rounded-3xl bg-white/50 dark:bg-darkcard/50 border border-white/80 dark:border-white/10 backdrop-blur-xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-pink-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                <div class="w-12 h-12 rounded-2xl bg-pink-500/10 text-pink-500 flex items-center justify-center text-2xl mb-6 group-hover:rotate-12 transition-transform duration-300">
                    📅
                </div>
                <h3 class="text-xl font-extrabold mb-2 uppercase tracking-wide">Memories & Calendar</h3>
                <p class="text-xs opacity-70 leading-relaxed">
                    Lacak setiap progres harian, jurnal pribadi, dan agenda penting secara rapi dan visual.
                </p>
            </div>

            <!-- Feature Card 3 -->
            <div class="group relative p-8 rounded-3xl bg-white/50 dark:bg-darkcard/50 border border-white/80 dark:border-white/10 backdrop-blur-xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-2xl mb-6 group-hover:rotate-12 transition-transform duration-300">
                    🎯
                </div>
                <h3 class="text-xl font-extrabold mb-2 uppercase tracking-wide">Academic Focus</h3>
                <p class="text-xs opacity-70 leading-relaxed">
                    Mode khusus buat pemantauan skripsi, tugas akhir, dan target harian biar tetep terarah.
                </p>
            </div>

        </div>
    </section>


    <!-- FOOTER BANNER / CTA -->
    <section class="max-w-6xl mx-auto px-4 my-20">
        <div class="relative p-10 md:p-16 rounded-3xl bg-gradient-to-r from-persianblue to-midnight text-white overflow-hidden text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl">
            <div class="relative z-10 max-w-xl">
                <h3 class="text-3xl md:text-5xl font-black uppercase tracking-tight mb-4">Siap Abadikan Momen Lu?</h3>
                <p class="text-xs md:text-sm opacity-80 leading-relaxed">Bergabung sekarang dan rasakan pengalaman daily logging yang cepat dan seamless.</p>
            </div>
            <a href="<?= base_url('login'); ?>" class="relative z-10 px-8 py-4 rounded-full bg-white text-midnight font-black text-xs uppercase tracking-wider hover:bg-petalfrost transition-all duration-300 transform hover:scale-105 shadow-lg">
                Masuk / Daftar
            </a>
        </div>
    </section>

</div>

<?= $this->endSection(); ?>
