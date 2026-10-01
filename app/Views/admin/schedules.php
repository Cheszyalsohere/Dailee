<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container my-4">
    <!-- Header Modul -->
    <div class="bento-card mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--text-midnight);">📅 Manajemen Jadwal & Agenda</h3>
            <p class="text-muted mb-0 small">Atur dan pantau agenda Akademik, Tugas, serta Non-Akademikmu di sini[cite: 1].</p>
        </div>
        <button class="btn btn-bento" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            + Tambah Jadwal Baru
        </button>
    </div>

    <!-- Alert Notifikasi -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success py-2 mb-4" style="border-radius: 12px; font-size: 0.9rem;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- List Tabel Bento Jadwal -->
    <div class="bento-card">
        <h5 class="fw-bold mb-3" style="color: var(--text-midnight);">Daftar Agenda Aktif</h5>
        <hr style="border-color: rgba(69, 12, 63, 0.1);">

        <?php if(empty($schedules)): ?>
            <div class="text-center py-5 text-muted">
                <p>Belum ada jadwal tersimpan. Yuk, buat agenda pertamamu!</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr style="color: var(--text-midnight); font-size: 0.9rem;">
                            <th>Judul Kegiatan</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($schedules as $row): ?>
                            <tr>
                                <td class="fw-semibold">
                                    <?= $row['title'] ?>
                                    <?php if(!empty($row['description'])): ?>
                                        <br><small class="text-muted fw-normal"><?= $row['description'] ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge px-3 py-2 
                                        <?= $row['category'] == 'Akademik' ? 'bg-primary' : ($row['category'] == 'Tugas' ? 'bg-warning text-dark' : 'bg-success') ?>">
                                        <?= $row['category'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td><?= $row['schedule_date'] ?></td>
                                <td>
                                    <a href="<?= base_url('admin/schedules/delete/' . $row['id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bento-card border-0" style="background: #ffffff;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: var(--text-midnight);">✨ Tambah Agenda Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/schedules/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color: var(--text-midnight);">Judul Kegiatan</label>
                        <input type="text" class="form-control" name="title" required placeholder="Contoh: Kuliah Metode Riset..." style="border-radius: 10px;">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small" style="color: var(--text-midnight);">Kategori</label>
                            <select class="form-select" name="category" required style="border-radius: 10px;">
                                <option value="Akademik">Akademik</option>
                                <option value="Tugas">Tugas</option>
                                <option value="Non-Akademik">Non-Akademik</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small" style="color: var(--text-midnight);">Status</label>
                            <select class="form-select" name="status" required style="border-radius: 10px;">
                                <option value="To Do">To Do</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Done">Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color: var(--text-midnight);">Tanggal Pelaksanaan</label>
                        <input type="date" class="form-control" name="schedule_date" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color: var(--text-midnight);">Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Catatan tambahan..." style="border-radius: 10px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-bento btn-sm px-4">Simpan Agenda</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>