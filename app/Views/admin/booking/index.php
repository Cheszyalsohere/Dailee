<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Admin -->
        <nav class="col-md-3 col-lg-2 sidebar">
            <div class="text-white px-3 mb-4">
                <h5>Dashboard Admin</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">Tampilan Daftar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/books') ?>">Kelola Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/peminjaman') ?>">Kelola Peminjaman</a>
                </li>
                <!-- MENU KELOLA BOOKING (Baru Ditambahin) -->
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/admin/booking') ?>">Kelola Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/users') ?>">Kelola Anggota</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/site/images') ?>">Kelola Gambar Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/auth/logout') ?>">Keluar</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <h1 class="mb-4" style="color: var(--text-light);">Kelola Booking Ruangan</h1>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body" style="background: linear-gradient(135deg, #efe4d4 0%, #e0d1bf 100%);">
                    <?php if (empty($bookings)): ?>
                        <p class="text-muted text-center py-5">Belum ada pengajuan booking ruangan.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background: linear-gradient(135deg, #c9b08e 0%, #b99774 50%, #a6835e 100%); color: #111111;">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama User</th>
                                        <th>Ruangan</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($bookings as $b): ?>
                                        <tr style="background: linear-gradient(135deg, #efe4d4 0%, #e0d1bf 100%); color: #111111;">
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($b['username']) ?></td>
                                            <td><?= esc($b['nama_ruangan']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($b['tanggal'])) ?></td>
                                            <td><?= date('H:i', strtotime($b['jam_mulai'])) ?> - <?= date('H:i', strtotime($b['jam_selesai'])) ?></td>
                                            <td>
                                                <?php if ($b['status'] === 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php elseif ($b['status'] === 'approved'): ?>
                                                    <span class="badge bg-success">Disetujui</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Ditolak</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($b['status'] === 'pending'): ?>
                                                    <a href="<?= base_url('/admin/booking/update/' . $b['id_booking'] . '/approved') ?>" class="btn btn-sm btn-success">Setujui</a>
                                                    <a href="<?= base_url('/admin/booking/update/' . $b['id_booking'] . '/rejected') ?>" class="btn btn-sm btn-danger">Tolak</a>
                                                <?php else: ?>
                                                    <span class="text-muted">- Selesai -</span>
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