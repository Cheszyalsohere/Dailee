<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- FullCalendar CSS & JS CDN -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    
    <!-- Bento Card: Interactive Calendar (Kiri - Lebar) -->
    <div class="lg:col-span-3 bg-white/40 backdrop-blur-md border border-white/30 p-6 rounded-[24px] shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-extrabold text-midnight">Jadwal Minggu Ini 📅</h2>
            <div class="flex gap-2 text-xs font-bold">
                <span class="bg-[#4D3EA3] text-white px-3 py-1 rounded-full">Akademik</span>
                <span class="bg-[#758AD1] text-white px-3 py-1 rounded-full">Tugas</span>
                <span class="bg-[#FFD2F4] text-midnight px-3 py-1 rounded-full">Non-Akademik</span>
            </div>
        </div>
        
        <!-- Wadah Kalender -->
        <div id='calendar' class="bg-white/60 p-4 rounded-xl shadow-inner min-h-[500px]"></div>
    </div>

    <!-- Bento Card: Profile & Quick Stats (Kanan - Sempit) -->
    <div class="flex flex-col gap-6 lg:col-span-1">
        
        <!-- Profile Widget -->
        <div class="bg-glaucous/20 backdrop-blur-md border border-white/30 p-6 rounded-[24px] shadow-sm flex flex-col items-center text-center">
            <div class="w-24 h-24 rounded-full bg-persianblue border-4 border-petalfrost mb-4 shadow-md overflow-hidden">
                <!-- Foto Profil Lo -->
                <img src="https://ui-avatars.com/api/?name=Jace&background=4D3EA3&color=fff" alt="Profile" class="w-full h-full object-cover">
            </div>
            <h3 class="font-bold text-xl text-midnight">Hi, Jace! 👋</h3>
            <p class="text-sm text-midnight/70 font-medium">IIP 2024</p>
            
            <div class="mt-6 w-full flex justify-between bg-white/50 rounded-xl p-3 shadow-inner text-sm">
                <div>
                    <p class="font-bold text-persianblue text-lg">12</p>
                    <p class="text-midnight/60 text-xs">Tugas Done</p>
                </div>
                <div>
                    <p class="font-bold text-persianblue text-lg">8</p>
                    <p class="text-midnight/60 text-xs">Momen BeReal</p>
                </div>
            </div>
        </div>

        <!-- Action Widget -->
        <div class="bg-midnight backdrop-blur-md p-6 rounded-[24px] shadow-sm text-white">
            <h3 class="font-bold mb-3">Quick Action</h3>
            <button class="w-full bg-petalfrost text-midnight font-bold py-2 rounded-xl hover:bg-white transition mb-2 shadow-sm">
                + Tambah Jadwal
            </button>
            <button class="w-full bg-glaucous text-white font-bold py-2 rounded-xl hover:bg-persianblue transition shadow-sm">
                📸 Capture Momen
            </button>
        </div>

    </div>
</div>

<!-- Script Inisialisasi FullCalendar AJAX -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek', // Default view mingguan
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '/dashboard/getSchedules', // Tarik data dari Controller via AJAX[cite: 1]
            height: 550,
            slotMinTime: '06:00:00', // Mulai jam 6 pagi
            slotMaxTime: '22:00:00', // Sampai jam 10 malam
            allDaySlot: false
        });
        calendar.render();
    });
</script>

<?= $this->endSection(); ?>