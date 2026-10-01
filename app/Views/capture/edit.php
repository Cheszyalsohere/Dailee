<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="max-w-xl mx-auto pb-20">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-black text-midnight dark:text-white">Edit Daily Log ✏️</h2>
            <p class="text-xs text-gray-500">Perbarui tanggal, foto, atau catatan momen kamu</p>
        </div>
        <a href="<?= base_url('dashboard?tab=my_log'); ?>" class="text-xs font-bold text-gray-500 hover:text-midnight dark:hover:text-white">Batal</a>
    </div>

    <form action="<?= base_url('moments/update/' . $moment['id']); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        
        <!-- Bagian Edit Tanggal & Waktu (Paling Atas) -->
        <div class="bg-white/70 dark:bg-darkcard/70 backdrop-blur-xl border border-white/50 dark:border-white/10 rounded-[28px] p-5 shadow-xl">
            <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost mb-3 flex items-center gap-2">
                <span>🗓️</span> Tanggal & Waktu Kegiatan
            </h3>

            <div>
                <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Waktu Momen</label>
                <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i', strtotime($moment['created_at'])); ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 outline-none text-xs font-semibold text-midnight dark:text-white" required>
            </div>
        </div>

        <!-- Frame Foto Dual-Frame -->
        <div class="bg-white/70 dark:bg-darkcard/70 backdrop-blur-xl border border-white/50 dark:border-white/10 rounded-[32px] p-6 shadow-xl">
            <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost mb-4 flex items-center gap-2">
                <span>📷</span> Ganti Foto (Opsional)
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Frame Foto Utama -->
                <div class="flex flex-col bg-white/40 dark:bg-white/5 p-3 rounded-2xl border border-black/5 dark:border-white/5 text-center">
                    <span class="text-xs font-bold text-midnight dark:text-white mb-2">Foto Utama</span>
                    
                    <div class="relative w-full h-48 bg-black/10 rounded-xl overflow-hidden flex items-center justify-center mb-2">
                        <video id="video-main" autoplay playsinline class="w-full h-full object-cover hidden"></video>
                        <canvas id="canvas-main" class="hidden"></canvas>
                        <img id="preview-main" src="<?= base_url('uploads/moments/' . $moment['main_image']); ?>" class="w-full h-full object-cover">
                    </div>

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

                <!-- Frame Inset -->
                <div class="flex flex-col bg-white/40 dark:bg-white/5 p-3 rounded-2xl border border-black/5 dark:border-white/5 text-center">
                    <span class="text-xs font-bold text-midnight dark:text-white mb-2">Inset (Selfie)</span>
                    
                    <div class="relative w-full h-48 bg-black/10 rounded-xl overflow-hidden flex items-center justify-center mb-2">
                        <video id="video-inset" autoplay playsinline class="w-full h-full object-cover hidden"></video>
                        <canvas id="canvas-inset" class="hidden"></canvas>
                        <?php if (!empty($moment['inset_image'])): ?>
                            <img id="preview-inset" src="<?= base_url('uploads/moments/' . $moment['inset_image']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <img id="preview-inset" class="w-full h-full object-cover hidden">
                            <span id="placeholder-inset" class="text-xs text-gray-400">Tidak ada inset</span>
                        <?php endif; ?>
                    </div>

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

        <!-- Edit Caption & Mood -->
        <div class="bg-white/70 dark:bg-darkcard/70 backdrop-blur-xl border border-white/50 dark:border-white/10 rounded-[28px] p-6 shadow-xl space-y-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost flex items-center gap-2">
                <span>💬</span> Catatan & Suasana Hati
            </h3>

            <div>
                <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Catatan Momen (Caption)</label>
                <textarea name="caption" rows="2" class="w-full px-4 py-3 rounded-2xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 focus:border-persianblue outline-none text-xs text-midnight dark:text-white" placeholder="Bagikan progress atau cerita singkat..."><?= esc($moment['caption']); ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Mood</label>
                    <select name="mood" class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 outline-none text-xs font-medium text-midnight dark:text-white">
                        <option value="⚡ Productive" <?= $moment['mood'] === '⚡ Productive' ? 'selected' : ''; ?>>⚡ Productive</option>
                        <option value="✨ Happy" <?= $moment['mood'] === '✨ Happy' ? 'selected' : ''; ?>>✨ Happy</option>
                        <option value="😮‍💨 Exhausted" <?= $moment['mood'] === '😮‍💨 Exhausted' ? 'selected' : ''; ?>>😮‍💨 Exhausted</option>
                        <option value="📚 Deadline Rush" <?= $moment['mood'] === '📚 Deadline Rush' ? 'selected' : ''; ?>>📚 Deadline Rush</option>
                        <option value="☕ Chill" <?= $moment['mood'] === '☕ Chill' ? 'selected' : ''; ?>>☕ Chill</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-midnight dark:text-white mb-1">Visibilitas</label>
                    <select name="visibility" class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/10 outline-none text-xs font-medium text-midnight dark:text-white">
                        <option value="circle" <?= $moment['visibility'] === 'circle' ? 'selected' : ''; ?>>👥 Circle (Teman)</option>
                        <option value="me" <?= $moment['visibility'] === 'me' ? 'selected' : ''; ?>>🔒 My Log Saja</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-persianblue text-white font-black py-4 rounded-2xl shadow-xl hover:bg-midnight transition text-sm">
            Simpan Perubahan ✨
        </button>

    </form>
</div>

<script>
    let activeStreams = {};

    async function startCamera(type) {
        const video = document.getElementById(`video-${type}`);
        const snapBtn = document.getElementById(`snap-btn-${type}`);
        const preview = document.getElementById(`preview-${type}`);
        const placeholder = document.getElementById(`placeholder-${type}`);

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: type === 'inset' ? 'user' : 'environment' },
                audio: false
            });

            activeStreams[type] = stream;
            video.srcObject = stream;
            video.classList.remove('hidden');
            snapBtn.classList.remove('hidden');
            preview.classList.add('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        } catch (err) {
            alert('Tidak dapat mengakses kamera.');
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
            const video = document.getElementById(`video-${type}`);
            const snapBtn = document.getElementById(`snap-btn-${type}`);
            const placeholder = document.getElementById(`placeholder-${type}`);

            preview.src = e.target.result;
            preview.classList.remove('hidden');
            video.classList.add('hidden');
            snapBtn.classList.add('hidden');
            if (placeholder) placeholder.classList.add('hidden');

            document.getElementById(`input-${type}-captured`).value = '';
        };
        reader.readAsDataURL(file);
    }
</script>
<?= $this->endSection(); ?>