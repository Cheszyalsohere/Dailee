<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="max-w-xl mx-auto pb-16">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-black text-midnight dark:text-white">Capture Daily Log 📸</h2>
            <p class="text-xs text-gray-500">Tentukan jadwal, abadikan momen, lalu bagikan</p>
        </div>
        <a href="<?= base_url('dashboard'); ?>" class="text-xs font-bold text-gray-500 hover:text-midnight dark:hover:text-white">Batal</a>
    </div>

    <form action="<?= base_url('capture/publish'); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field(); ?>
        
        <!-- ================= BAGIAN 1: WAKTU KEGIATAN & AGENDA (PALING ATAS) ================= -->
        <div class="bg-white/60 dark:bg-darkcard/70 backdrop-blur-xl border border-white/50 dark:border-white/10 rounded-[28px] p-6 shadow-xl space-y-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost flex items-center gap-2">
                <span>🗓️</span> 1. Tanggal & Agenda Kegiatan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Waktu & Tanggal Kegiatan</label>
                    <input type="datetime-local" name="agenda_time" value="<?= date('Y-m-d\TH:i'); ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 outline-none text-xs font-medium text-midnight dark:text-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Pengingat (Alert)</label>
                    <select name="alert_time" class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 outline-none text-xs font-medium text-midnight dark:text-white">
                        <option value="15_min">15 Menit Sebelum</option>
                        <option value="1_hour">1 Jam Sebelum</option>
                        <option value="1_day">1 Hari Sebelum</option>
                    </select>
                </div>
            </div>

            <!-- Tautkan ke Agenda atau Buat Baru -->
            <div class="space-y-3 pt-2 border-t border-black/5 dark:border-white/5">
                <?php if (!empty($schedules)): ?>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Tautkan ke Agenda Terdaftar</label>
                        <select name="schedule_id" class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 outline-none text-xs text-midnight dark:text-white">
                            <option value="">-- Buat Agenda Baru Saja --</option>
                            <?php foreach ($schedules as $s): ?>
                                <option value="<?= $s['id']; ?>"><?= esc($s['title']); ?> (<?= date('d M, H:i', strtotime($s['start_time'])); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Atau Nama Agenda Baru (Opsional)</label>
                    <input type="text" name="new_agenda_title" class="w-full px-4 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 focus:border-persianblue outline-none text-xs text-midnight dark:text-white" placeholder="Misal: Gladi Bersih MC / Diskusi Bab 2">
                </div>

                <label class="flex items-center gap-3 pt-1 cursor-pointer">
                    <input type="checkbox" name="sync_calendar" value="1" checked class="w-4 h-4 rounded text-persianblue focus:ring-persianblue">
                    <span class="text-xs font-semibold text-midnight dark:text-gray-200">
                        Sync .ics ke Kalender HP (Google/Apple Calendar)
                    </span>
                </label>
            </div>
        </div>

        <!-- ================= BAGIAN 2: REAL-TIME CAMERA & DUAL FRAME (TENGAH) ================= -->
        <div class="bg-white/60 dark:bg-darkcard/70 backdrop-blur-xl border border-white/50 dark:border-white/10 rounded-[32px] p-6 shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost flex items-center gap-2">
                    <span>📷</span> 2. Foto Dual-Frame (Utama + Inset)
                </h3>
                <span class="text-[10px] text-gray-400">Bisa kamera langsung / galeri</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Frame 1: Foto Utama -->
                <div class="flex flex-col bg-white/40 dark:bg-white/5 p-3 rounded-2xl border border-black/5 dark:border-white/5 text-center">
                    <span class="text-xs font-bold text-midnight dark:text-white mb-2">1. Foto Utama</span>
                    
                    <div class="relative w-full h-48 bg-black/10 dark:bg-black/30 rounded-xl overflow-hidden flex items-center justify-center mb-2">
                        <video id="video-main" autoplay playsinline class="w-full h-full object-cover hidden"></video>
                        <canvas id="canvas-main" class="hidden"></canvas>
                        <img id="preview-main" class="w-full h-full object-cover hidden">
                        <span id="placeholder-main" class="text-xs text-gray-400">Belum ada foto</span>
                    </div>

                    <!-- Hidden input data URL -->
                    <input type="hidden" name="main_captured" id="input-main-captured">

                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="startCamera('main')" class="bg-persianblue text-white px-3 py-1.5 rounded-lg text-[11px] font-bold shadow hover:bg-midnight transition">
                            Kamera
                        </button>
                        <button type="button" id="snap-btn-main" onclick="snapPhoto('main')" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold shadow hidden">
                            Jepret
                        </button>
                        <label class="bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10 px-3 py-1.5 rounded-lg text-[11px] font-bold cursor-pointer hover:bg-gray-50 transition">
                            Galeri
                            <input type="file" name="main_image" accept="image/*" class="hidden" onchange="previewFile(event, 'main')">
                        </label>
                    </div>
                </div>

                <!-- Frame 2: Foto Inset (Selfie / Detail) -->
                <div class="flex flex-col bg-white/40 dark:bg-white/5 p-3 rounded-2xl border border-black/5 dark:border-white/5 text-center">
                    <span class="text-xs font-bold text-midnight dark:text-white mb-2">2. Inset (Selfie)</span>
                    
                    <div class="relative w-full h-48 bg-black/10 dark:bg-black/30 rounded-xl overflow-hidden flex items-center justify-center mb-2">
                        <video id="video-inset" autoplay playsinline class="w-full h-full object-cover hidden"></video>
                        <canvas id="canvas-inset" class="hidden"></canvas>
                        <img id="preview-inset" class="w-full h-full object-cover hidden">
                        <span id="placeholder-inset" class="text-xs text-gray-400">Belum ada foto</span>
                    </div>

                    <!-- Hidden input data URL -->
                    <input type="hidden" name="inset_captured" id="input-inset-captured">

                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="startCamera('inset')" class="bg-persianblue text-white px-3 py-1.5 rounded-lg text-[11px] font-bold shadow hover:bg-midnight transition">
                            Kamera
                        </button>
                        <button type="button" id="snap-btn-inset" onclick="snapPhoto('inset')" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold shadow hidden">
                            Jepret
                        </button>
                        <label class="bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10 px-3 py-1.5 rounded-lg text-[11px] font-bold cursor-pointer hover:bg-gray-50 transition">
                            Galeri
                            <input type="file" name="inset_image" accept="image/*" class="hidden" onchange="previewFile(event, 'inset')">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= BAGIAN 3: CAPTION, MOOD & VISIBILITY (BAWAH) ================= -->
        <div class="bg-white/60 dark:bg-darkcard/70 backdrop-blur-xl border border-white/50 dark:border-white/10 rounded-[28px] p-6 shadow-xl space-y-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost flex items-center gap-2">
                <span>💬</span> 3. Caption & Mood
            </h3>

            <div>
                <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Catatan Singkat</label>
                <textarea name="caption" rows="2" class="w-full px-4 py-3 rounded-2xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 focus:border-persianblue outline-none text-xs text-midnight dark:text-white" placeholder="Bagikan progress atau cerita singkat mengenai momen ini..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Mood Hari Ini</label>
                    <select name="mood" class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 outline-none text-xs font-medium text-midnight dark:text-white">
                        <option value="⚡ Productive">⚡ Productive</option>
                        <option value="✨ Happy">✨ Happy</option>
                        <option value="😮‍💨 Exhausted">😮‍💨 Exhausted</option>
                        <option value="📚 Deadline Rush">📚 Deadline Rush</option>
                        <option value="☕ Chill">☕ Chill</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Visibilitas</label>
                    <select name="visibility" class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 outline-none text-xs font-medium text-midnight dark:text-white">
                        <option value="circle">👥 Circle (Teman)</option>
                        <option value="me">🔒 My Log Saja</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tombol Eksekusi -->
        <button type="submit" class="w-full bg-persianblue text-white font-black py-4 rounded-2xl shadow-xl hover:bg-midnight hover:scale-[1.01] active:scale-[0.99] transition text-sm">
            Publish & Save Agenda ✨
        </button>

    </form>
</div>

<!-- Logika Kamera Real-Time Webcam/HP (HTML5 MediaDevices) -->
<script>
    let activeStreams = {};

    async function startCamera(type) {
        const video = document.getElementById(`video-${type}`);
        const snapBtn = document.getElementById(`snap-btn-${type}`);
        const preview = document.getElementById(`preview-${type}`);
        const placeholder = document.getElementById(`placeholder-${type}`);

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: type === 'inset' ? 'user' : 'environment',
                    width: { ideal: 720 },
                    height: { ideal: 960 }
                },
                audio: false
            });

            activeStreams[type] = stream;
            video.srcObject = stream;
            video.classList.remove('hidden');
            snapBtn.classList.remove('hidden');
            preview.classList.add('hidden');
            placeholder.classList.add('hidden');
        } catch (err) {
            alert('Tidak dapat mengakses kamera perangkat. Pastikan izin kamera telah diberikan di browser.');
        }
    }

    function snapPhoto(type) {
        const video = document.getElementById(`video-${type}`);
        const canvas = document.getElementById(`canvas-${type}`);
        const preview = document.getElementById(`preview-${type}`);
        const inputHidden = document.getElementById(`input-${type}-captured`);
        const snapBtn = document.getElementById(`snap-btn-${type}`);

        canvas.width = video.videoWidth || 400;
        canvas.height = video.videoHeight || 500;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataURL = canvas.toDataURL('image/jpeg', 0.85);
        preview.src = dataURL;
        preview.classList.remove('hidden');
        video.classList.add('hidden');
        snapBtn.classList.add('hidden');
        inputHidden.value = dataURL;

        // Matikan kamera setelah jepret
        if (activeStreams[type]) {
            activeStreams[type].getTracks().forEach(track => track.stop());
        }
    }

    function previewFile(event, type) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(`preview-${type}`);
            const placeholder = document.getElementById(`placeholder-${type}`);
            const video = document.getElementById(`video-${type}`);
            const snapBtn = document.getElementById(`snap-btn-${type}`);

            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            video.classList.add('hidden');
            snapBtn.classList.add('hidden');

            document.getElementById(`input-${type}-captured`).value = '';
        };
        reader.readAsDataURL(file);
    }
</script>
<?= $this->endSection(); ?>