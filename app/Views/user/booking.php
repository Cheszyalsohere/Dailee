<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 sidebar" style="background: linear-gradient(180deg, #372c25 0%, #241a14 100%); border-right: 3px solid #6b5645;">
            <div class="text-white px-3 mb-4">
                <h5>Dashboard User</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/user/dashboard') ?>">
                    Tampilan Daftar Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/user/history') ?>">
                    Riwayat Peminjaman
                    </a>
                </li>
                <!-- INI MENU BOOKING NYA -->
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/user/booking') ?>">
                    Booking Ruangan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/auth/logout') ?>">
                    Keluar
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <h1 class="mb-4" style="color: #ffffff;">🏫 Booking Ruangan</h1>

            <!-- Menampilkan Pesan Sukses/Error -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success" style="background-color: #bfd4c1; border: none; color: #223226; font-weight: bold;">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" style="background-color: #d9b4a0; border: none; color: #4a2c21; font-weight: bold;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Form Pengajuan Booking -->
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(180deg, #d7c3a7 0%, #c7b08f 100%);">
                <div class="card-body">
                    <h5 style="color: #372c25; font-weight: bold; margin-bottom: 20px;">📝 Form Pengajuan Ruangan</h5>
                    <form action="<?= base_url('/user/booking/proses') ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: #372c25; font-weight: 600;">Pilih Ruangan</label>
                                <select name="ruangan_id" class="form-select" required style="background-color: #eadcc9; border: 1px solid #c9b89d;">
                                    <option value="">-- Pilih Ruangan --</option>
                                    <?php foreach($ruangan as $r): ?>
                                        <option value="<?= $r['id_ruangan'] ?>"><?= esc($r['nama_ruangan']) ?> (Kapasitas: <?= esc($r['kapasitas']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: #372c25; font-weight: 600;">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required style="background-color: #eadcc9; border: 1px solid #c9b89d;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: #372c25; font-weight: 600;">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" required style="background-color: #eadcc9; border: 1px solid #c9b89d;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: #372c25; font-weight: 600;">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control" required style="background-color: #eadcc9; border: 1px solid #c9b89d;">
                            </div>
                        </div>
                        <button type="submit" class="btn mt-2" style="background-color: #6d5747; color: #ffffff; font-weight: bold; padding: 8px 20px; border-radius: 6px;">Ajukan Booking</button>
                    </form>
                </div>
            </div>

            <!-- Tabel Riwayat Booking -->
            <div class="card border-0 shadow-sm" style="background: linear-gradient(180deg, #d7c3a7 0%, #c7b08f 100%);">
                <div class="card-body">
                    <h5 style="color: #372c25; font-weight: bold; margin-bottom: 20px;">🕒 Riwayat Booking Saya</h5>
                    <?php if (empty($riwayat_booking)): ?>
                        <p class="text-muted text-center py-4">Belum ada riwayat booking ruangan.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 history-table" style="border-collapse: separate; border-spacing: 0;">
                                <thead style="background: linear-gradient(135deg, #6d5747 0%, #4c3d30 100%); color: #ffffff;">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($riwayat_booking as $item): ?>
                                        <tr>
                                            <td style="background-color: #d8c6af; border-bottom: 1px solid #c6b79c; color: #111;"><?= $no++ ?></td>
                                            <td style="background-color: #d8c6af; border-bottom: 1px solid #c6b79c; color: #111;"><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                                            <td style="background-color: #d8c6af; border-bottom: 1px solid #c6b79c; color: #111;"><?= date('H:i', strtotime($item['jam_mulai'])) ?> - <?= date('H:i', strtotime($item['jam_selesai'])) ?></td>
                                            <td style="background-color: #d8c6af; border-bottom: 1px solid #c6b79c; color: #111;">
                                                <?php if ($item['status'] === 'pending'): ?>
                                                    <span class="badge" style="background-color: #dfc89d; color: #3d311b; border: 1px solid #c9b89d;">Pending</span>
                                                <?php elseif ($item['status'] === 'approved'): ?>
                                                    <span class="badge" style="background-color: #bfd4c1; color: #223226; border: 1px solid #c9b89d;">Disetujui</span>
                                                <?php else: ?>
                                                    <span class="badge" style="background-color: #d9b4a0; color: #4a2c21; border: 1px solid #c9b89d;">Ditolak</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>