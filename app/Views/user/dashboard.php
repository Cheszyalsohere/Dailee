<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container my-4">
    <div class="bento-card mb-4">
        <h3 class="fw-bold mb-1" style="color: var(--text-midnight);">Hi, <?= session()->get('username') ?>! ✨</h3>
        <p class="text-muted mb-0 small">Pantau jadwal dan tangkap momen BeReal harian lo hari ini.</p>
    </div>

    <div class="bento-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0" style="color: var(--text-midnight);">📅 Agenda Hari Ini</h5>
        </div>
        <hr style="border-color: rgba(69, 12, 63, 0.1);">
        
        <?php if(empty($schedules)): ?>
            <div class="text-center py-5 text-muted">Lo belum punya jadwal aktif.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr style="color: var(--text-midnight); font-size: 0.9rem;">
                            <th>Kegiatan</th>
                            <th>Kategori</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($schedules as $row): ?>
                            <tr>
                                <td class="fw-semibold"><?= $row['title'] ?></td>
                                <td><span class="badge bg-secondary"><?= $row['category'] ?></span></td>
                                <td><span class="badge bg-light text-dark border"><?= $row['status'] ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>