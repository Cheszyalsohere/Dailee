<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (untuk user, lebih sederhana) -->
        <nav class="col-md-3 col-lg-2 sidebar" style="background: linear-gradient(180deg, #372c25 0%, #241a14 100%); border-right: 3px solid #6b5645;">
            <div class="text-white px-3 mb-4">
                <h5>Dashboard User</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/user/dashboard') ?>">
                    Tampilan Daftar Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/user/history') ?>">
                    Riwayat Peminjaman
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
            <h1 class="mb-4" style="color: #ffffff;">Dashboard User</h1>

            <!-- Search Bar -->
            <div class="mb-4">
                <form action="<?= base_url('/user/search') ?>" method="get" class="input-group">
                    <input type="text" class="form-control" placeholder="Cari buku..." name="q" required>
                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-primary h-100">
                        <div class="card-body">
                            <h6 class="text-primary">📘 Total Peminjaman</h6>
                            <h3 class="mb-0"><?= $totalPeminjaman ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-warning h-100">
                        <div class="card-body">
                            <h6 class="text-warning">📖 Sedang Dipinjam</h6>
                            <h3 class="mb-0"><?= $activeLoans ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-danger h-100">
                        <div class="card-body">
                            <h6 class="text-danger">⏰ Terlambat</h6>
                            <h3 class="mb-0"><?= $lateLoans ?? 0 ?></h3>
                            <small class="text-muted">Lihat riwayat untuk melihat denda.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <a class="btn btn-outline-primary" href="<?= base_url('/user/history') ?>">Lihat Riwayat Peminjaman</a>
            </div>

            <!-- Books Grid -->
            <h3 class="mb-4" style="color: #ffffff;">📚 Daftar Buku</h3>
            
            <?php if (empty($books)): ?>
                <div class="alert alert-info">
                    <p class="mb-0">Belum ada buku yang tersedia.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($books as $book): ?>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($book['nama_buku']) ?></h5>
                                    <p class="card-text">
                                        <strong>Penulis:</strong> <?= esc($book['penulis']) ?><br>
                                        <strong>Penerbit:</strong> <?= esc($book['penerbit']) ?><br>
                                        <strong>Tahun:</strong> <?= $book['tahun_terbit'] ?><br>
                                        <strong>Stok:</strong> 
                                        <?php if ($book['stok'] > 0): ?>
                                            <span class="badge bg-success"><?= $book['stok'] ?> tersedia</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Tidak tersedia</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <?php if ($book['stok'] > 0): ?>
                                        <a href="<?= base_url('/user/borrow/' . $book['id']) ?>" class="btn btn-sm btn-primary w-100">
                                            Pinjam Buku
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary w-100" disabled>
                                            Tidak Tersedia
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
