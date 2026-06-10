<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 sidebar">
            <div class="text-white px-3 mb-4">
                <h5>Dashboard Admin</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">
                    Tampilan Daftar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/books') ?>">
                    Kelola Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/admin/peminjaman') ?>">
                    Kelola Peminjaman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/site/images') ?>">
                    Kelola Gambar Home
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
            <h1 class="mb-4" style="color: var(--text-light);">Kelola Peminjaman</h1>

            <!-- Peminjaman Table -->
            <div class="card">
                <div class="card-body" style="background: linear-gradient(135deg, #efe4d4 0%, #e0d1bf 100%);">
                    <?php if (empty($peminjaman)): ?>
                        <p class="text-muted text-center py-5">Belum ada data peminjaman</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background: linear-gradient(135deg, #c9b08e 0%, #b99774 50%, #a6835e 100%); color: #111111;">
                                    <tr>
                                        <th style="color: #111111;">No</th>
                                        <th style="color: #111111;">User</th>
                                        <th style="color: #111111;">Buku</th>
                                        <th style="color: #111111;">Tgl Pinjam</th>
                                        <th style="color: #111111;">Tgl Kembali</th>
                                        <th style="color: #111111;">Tgl Dikembalikan</th>
                                        <th style="color: #111111;">Status</th>
                                        <th style="color: #111111;">Denda</th>
                                        <th style="color: #111111;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($peminjaman as $item): ?>
                                        <tr style="background: linear-gradient(135deg, #efe4d4 0%, #e0d1bf 100%); color: #111111;">
                                            <td style="color: #111111;"><?= $no++ ?></td>
                                            <td style="color: #111111;"><?= esc($item['username']) ?></td>
                                            <td style="color: #111111;"><?= esc($item['nama_buku']) ?></td>
                                            <td style="color: #111111;"><?= date('d/m/Y', strtotime($item['tanggal_pinjam'])) ?></td>
                                            <td style="color: #111111;"><?= date('d/m/Y', strtotime($item['tanggal_kembali'])) ?></td>
                                            <td style="color: #111111;">
                                                <?php if (!empty($item['tanggal_dikembalikan'])): ?>
                                                    <?= date('d/m/Y', strtotime($item['tanggal_dikembalikan'])) ?>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Belum</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="color: #111111;">
                                                <?php if ($item['status'] === 'dipinjam'): ?>
                                                    <span class="badge" style="background: #cfd9e3; color: #334155; border: 1px solid #b1c0d4;">Dipinjam</span>
                                                <?php else: ?>
                                                    <span class="badge" style="background: #cfd9e3; color: #334155; border: 1px solid #b1c0d4;">Dikembalikan</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="color: #111111;">
                                                <?php if ($item['denda'] > 0): ?>
                                                    <span class="badge" style="background: #cfd9e3; color: #334155; border: 1px solid #b1c0d4;">Rp<?= number_format($item['denda']) ?></span>
                                                <?php else: ?>
                                                    <span class="badge" style="background: #cfd9e3; color: #334155; border: 1px solid #b1c0d4;">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="color: #111111;">
                                                <?php if ($item['status'] === 'dipinjam'): ?>
                                                    <a href="<?= base_url('/admin/approve-return/' . $item['id']) ?>" class="btn btn-sm" style="background: #cfd9e3; color: #1e3a8a; border: 1px solid #b1c0d4;" onclick="return confirm('Konfirmasi pengembalian buku ini?')">
                                                        Approve
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge" style="background: #cfd9e3; color: #1e3a8a; border: 1px solid #b1c0d4;">Selesai</span>
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
