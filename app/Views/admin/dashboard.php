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
                    <a class="nav-link active" href="<?= base_url('/admin/dashboard') ?>">
                    Tampilan Daftar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/books') ?>">
                    Kelola Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/peminjaman') ?>">
                    Kelola Peminjaman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/users') ?>">
                    Kelola Anggota
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
            <h1 class="mb-4" style="color: var(--text-light);">Tampilan Daftar</h1>

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #efe4d4 0%, #e3d6c4 100%);">
                        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <h5 class="mb-1" style="color: #111111;">Manajemen Anggota</h5>
                                <p class="mb-0 text-dark">Lihat, tambah, edit, dan nonaktifkan anggota yang menggunakan aplikasi.</p>
                            </div>
                            <a href="<?= base_url('/admin/users') ?>" class="btn btn-primary">Kelola Anggota</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?= $totalBooks ?></div>
                        <div class="stat-label">Total Buku</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?= $totalUsers ?></div>
                        <div class="stat-label">Total User</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?= $totalPeminjaman ?></div>
                        <div class="stat-label">Total Peminjaman</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?= $aktivPeminjaman ?></div>
                        <div class="stat-label">Sedang Dipinjam</div>
                    </div>
                </div>
            </div>

            <!-- Recent Books -->
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #c9b08e 0%, #b99774 50%, #a6835e 100%); border-bottom: 1px solid #8b704f; color: #111111;">
                    <h5 class="mb-0" style="color: #111111;">Daftar Buku Terbaru</h5>
                </div>
                <div class="card-body" style="background: linear-gradient(135deg, #efe4d4 0%, #e6d8c5 100%);">
                    <?php if (empty($books)): ?>
                        <p class="text-muted">Belum ada buku</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead style="background: linear-gradient(135deg, #c9b08e 0%, #b99774 50%, #a6835e 100%); color: #111111;">
                                    <tr>
                                        <th style="color: #111111;">No</th>
                                        <th style="color: #111111;">Nama Buku</th>
                                        <th style="color: #111111;">Penulis</th>
                                        <th style="color: #111111;">Penerbit</th>
                                        <th style="color: #111111;">Tahun</th>
                                        <th style="color: #111111;">Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($books as $book): ?>
                                        <tr style="background: linear-gradient(135deg, #efe4d4 0%, #e0d1bf 100%); color: #111111;">
                                            <td style="color: #111111;"><?= $no++ ?></td>
                                            <td style="color: #111111;"><?= esc($book['nama_buku']) ?></td>
                                            <td style="color: #111111;"><?= esc($book['penulis']) ?></td>
                                            <td style="color: #111111;"><?= esc($book['penerbit']) ?></td>
                                            <td style="color: #111111;"><?= $book['tahun_terbit'] ?></td>
                                            <td>
                                                <span class="badge" style="background: #cfd9e3; color: #334155; border: 1px solid #b1c0d4;"><?= $book['stok'] ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer" style="background: linear-gradient(90deg, #372c25 0%, #4a3a30 100%); border-top: 1px solid #6b5645;">
                    <a href="<?= base_url('/admin/books') ?>" class="btn btn-sm btn-primary">Lihat Semua Buku</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
