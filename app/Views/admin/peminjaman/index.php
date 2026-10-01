<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Admin -->
        <nav class="col-md-3 col-lg-2 sidebar" style="background: linear-gradient(180deg, #372c25 0%, #241a14 100%); border-right: 3px solid #6b5645;">
            <div class="text-white px-3 mb-4">
                <h5>Dashboard Admin</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">Tampilan Daftar</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/books') ?>">Kelola Buku</a></li>
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('/admin/peminjaman') ?>">Kelola Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/booking') ?>">Kelola Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/users') ?>">Kelola Anggota</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/site/images') ?>">Kelola Gambar Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/auth/logout') ?>">Keluar</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <h1 class="mb-4" style="color: var(--text-light);">Kelola Peminjaman Buku</h1>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm" style="background: linear-gradient(180deg, #efe4d4 0%, #e4d7c5 100%);">
                <div class="card-body">
                    <?php if (empty($peminjaman)): ?>
                        <p class="text-muted text-center py-5">Belum ada data peminjaman.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background: linear-gradient(135deg, #6d5747 0%, #4c3d30 100%); color: #ffffff;">
                                    <tr>
                                        <th>No</th>
                                        <th>Peminjam</th>
                                        <th>Buku</th>
                                        <th>Tgl Pinjam</th>
                                        <th>Tgl Harus Kembali</th>
                                        <th>Status</th>
                                        <th>Denda</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($peminjaman as $p): ?>
                                        <tr style="background: linear-gradient(135deg, #efe4d4 0%, #e3d6c4 100%); color: #111111;">
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($p['username'] ?? 'User') ?></td>
                                            <td><?= esc($p['nama_buku']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($p['tanggal_kembali'])) ?></td>
                                            <td>
                                                <?php if ($p['status'] === 'dipinjam'): ?>
                                                    <span class="badge" style="background-color: #dfc89d; color: #3d311b;">Dipinjam</span>
                                                <?php else: ?>
                                                    <span class="badge" style="background-color: #bfd4c1; color: #223226;">Dikembalikan</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= $p['denda'] > 0 ? 'Rp' . number_format($p['denda']) : '-' ?>
                                            </td>
                                            <td>
                                                <?php if ($p['status'] === 'dipinjam'): ?>
                                                    <a href="<?= base_url('/admin/approve-return/' . $p['id']) ?>" class="btn btn-sm" style="background-color: #6d5747; color: white; font-weight: bold;" onclick="return confirm('Proses pengembalian buku ini?')">Proses Kembali</a>
                                                <?php else: ?>
                                                    <span class="text-muted">Selesai</span>
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