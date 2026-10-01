<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="max-w-xl mx-auto pb-24">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-midnight dark:text-white flex items-center gap-2">
                <span>Task & Deadlines</span> 🎯
            </h1>
            <p class="text-xs text-midnight/60 dark:text-gray-400">Kelola to-do list & set reminder pengingatmu</p>
        </div>
        <a href="<?= base_url('dashboard'); ?>" class="text-xs font-bold text-gray-500 hover:text-midnight dark:hover:text-white">
            Kembali
        </a>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-emerald-500 text-white text-xs font-bold p-3.5 rounded-2xl mb-4 shadow">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500 text-white text-xs font-bold p-3.5 rounded-2xl mb-4 shadow">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Form Tambah To-Do Baru (Card Dropdown / Expandable) -->
    <div class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-2xl border border-white/60 dark:border-white/10 rounded-[28px] p-5 shadow-xl mb-6">
        <h3 class="text-xs font-black uppercase tracking-wider text-persianblue dark:text-petalfrost mb-3 flex items-center gap-1.5">
            <span>➕</span> Tambah Tugas / Deadline Baru
        </h3>

        <form action="<?= base_url('todo/create'); ?>" method="POST" class="space-y-3">
            <?= csrf_field(); ?>
            <!-- Judul Tugas -->
            <div>
                <input type="text" name="title" placeholder="Contoh: Revisi Bab 2 Semiotika..." class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-white/5 border border-transparent focus:border-persianblue outline-none text-xs font-semibold text-midnight dark:text-white" required>
            </div>

            <!-- Grid Tanggal & Reminder Setting -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Tanggal & Waktu Deadline -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-1">Tenggat Waktu (Deadline)</label>
                    <input type="datetime-local" name="deadline" value="<?= date('Y-m-d\TH:i', strtotime('+1 day')); ?>" class="w-full px-3 py-2 rounded-xl bg-gray-100 dark:bg-white/5 border border-transparent focus:border-persianblue outline-none text-xs font-semibold text-midnight dark:text-white" required>
                </div>

                <!-- Setting Reminder -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-1">Ingatkan Saya (Reminder)</label>
                    <select name="alert_time" class="w-full px-3 py-2 rounded-xl bg-gray-100 dark:bg-white/5 border border-transparent focus:border-persianblue outline-none text-xs font-semibold text-midnight dark:text-white">
                        <option value="15_min">🔔 15 Menit Sebelum</option>
                        <option value="1_hour" selected>⏰ 1 Jam Sebelum</option>
                        <option value="1_day">📅 1 Hari Sebelum</option>
                        <option value="3_days">⏳ 3 Hari Sebelum</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <!-- Kategori -->
                <select name="category" class="px-3 py-2 rounded-xl bg-gray-100 dark:bg-white/5 border border-transparent outline-none text-xs font-bold text-persianblue dark:text-petalfrost">
                    <option value="Akademik">📚 Kuliah / Tugas</option>
                    <option value="Organisasi">🏛️ Organisasi / HIMA</option>
                    <option value="Personal">☕ Pribadi / Self-Care</option>
                </select>

                <button type="submit" class="bg-persianblue hover:bg-midnight text-white text-xs font-black px-5 py-2.5 rounded-xl shadow-lg transition">
                    Simpan Task ✨
                </button>
            </div>
        </form>
    </div>

    <!-- DAFTAR TUGAS AKTIF (TO DO) -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
            <h3 class="text-xs font-black uppercase tracking-wider text-midnight dark:text-white flex items-center gap-1.5">
                <span>📌</span> Tugas yang Sedang Berjalan
            </h3>
            <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full bg-persianblue/10 text-persianblue dark:text-petalfrost">
                <?= count($pendingTasks); ?> Pending
            </span>
        </div>

        <?php if (empty($pendingTasks)): ?>
            <div class="bg-white/40 dark:bg-white/5 rounded-2xl p-6 text-center border border-dashed border-gray-300 dark:border-white/10">
                <span class="text-2xl block mb-1">🎉</span>
                <p class="text-xs font-bold text-gray-500">Semua deadline beres! Nggak ada tugas pending.</p>
            </div>
        <?php else: ?>
            <div class="space-y-2.5">
                <?php foreach ($pendingTasks as $t): ?>
                    <div id="task-card-<?= $t['id']; ?>" class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-xl border border-white/60 dark:border-white/10 rounded-2xl p-3.5 shadow-md flex items-center justify-between transition hover:shadow-lg">
                        
                        <!-- Checklist & Detail -->
                        <div class="flex items-center gap-3 flex-1 min-w-0 pr-3">
                            <input type="checkbox" onchange="toggleTask(<?= $t['id']; ?>)" class="w-5 h-5 rounded-lg text-persianblue focus:ring-0 cursor-pointer accent-persianblue">
                            
                            <div class="min-w-0">
                                <p id="task-title-<?= $t['id']; ?>" class="text-xs font-black text-midnight dark:text-white truncate">
                                    <?= esc($t['title']); ?>
                                </p>
                                <div class="flex items-center gap-2 mt-0.5 text-[10px] text-gray-400">
                                    <span class="font-bold text-persianblue dark:text-petalfrost"><?= esc($t['category']); ?></span>
                                    <span>•</span>
                                    <span>⏰ <?= date('d M, H:i', strtotime($t['start_time'])); ?></span>
                                    <span>•</span>
                                    <span class="bg-amber-500/10 text-amber-600 dark:text-amber-400 px-1.5 py-0.2 rounded">
                                        🔔 <?= str_replace('_', ' ', $t['alert_time']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Delete -->
                        <form action="<?= base_url('todo/delete/' . $t['id']); ?>" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                            <?= csrf_field(); ?>
                            <button type="submit" class="w-7 h-7 rounded-xl hover:bg-red-500 hover:text-white text-gray-400 text-xs flex items-center justify-center transition" title="Hapus">
                                🗑️
                            </button>
                        </form>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- DAFTAR TUGAS SELESAI (COMPLETED) -->
    <?php if (!empty($completedTasks)): ?>
        <div>
            <div class="flex items-center justify-between mb-3 px-1">
                <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 flex items-center gap-1.5">
                    <span>✓</span> Selesai Dikerjakan (<?= count($completedTasks); ?>)
                </h3>
            </div>

            <div class="space-y-2 opacity-75">
                <?php foreach ($completedTasks as $t): ?>
                    <div id="task-card-<?= $t['id']; ?>" class="bg-white/40 dark:bg-white/5 border border-black/5 dark:border-white/5 rounded-2xl p-3 flex items-center justify-between">
                        <div class="flex items-center gap-3 flex-1 min-w-0 pr-3">
                            <input type="checkbox" checked onchange="toggleTask(<?= $t['id']; ?>)" class="w-5 h-5 rounded-lg text-persianblue focus:ring-0 cursor-pointer accent-persianblue">
                            <p class="text-xs font-semibold text-gray-400 line-through truncate">
                                <?= esc($t['title']); ?>
                            </p>
                        </div>
                        <form action="<?= base_url('todo/delete/' . $t['id']); ?>" method="POST">
                            <?= csrf_field(); ?>
                            <button type="submit" class="text-gray-400 hover:text-red-500 text-xs">✕</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- Script Checklist Interaktif -->
<script>
    async function toggleTask(taskId) {
        try {
            const res = await fetch(`<?= base_url('todo/toggle'); ?>/${taskId}`, {
                method: 'POST'
            });
            const data = await res.json();
            if (data.status === 'success') {
                // Refresh halaman agar task langsung berpindah grup antara Pending vs Done
                window.location.reload();
            }
        } catch (err) {
            alert('Gagal memperbarui status tugas.');
        }
    }
</script>
<?= $this->endSection(); ?>