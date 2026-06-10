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
                    <a class="nav-link active" href="<?= base_url('/admin/books') ?>">
                    Kelola Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/peminjaman') ?>">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 style="color: var(--text-light);">Kelola Buku</h1>
                <a href="<?= base_url('/admin/books/create') ?>" class="btn btn-primary">+ Tambah Buku</a>
            </div>

            <!-- Books Table -->
            <div class="card">
                <div class="card-body" style="background: linear-gradient(135deg, #efe4d4 0%, #e0d1bf 100%);">
                    <?php if (empty($books)): ?>
                        <p class="text-muted text-center py-5">Belum ada buku yang terdaftar</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background: linear-gradient(135deg, #c9b08e 0%, #b99774 50%, #a6835e 100%); color: #111111;">
                                    <tr>
                                        <th style="color: #111111;">No</th>
                                        <th style="color: #111111;">Nama Buku</th>
                                        <th style="color: #111111;">Penulis</th>
                                        <th style="color: #111111;">Penerbit</th>
                                        <th style="color: #111111;">Tahun</th>
                                        <th style="color: #111111;">Stok</th>
                                        <th style="color: #111111;">Aksi</th>
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
                                            <td style="color: #111111;">
                                                <a href="<?= base_url('/admin/books/edit/' . $book['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="<?= base_url('/admin/books/delete/' . $book['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus buku ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= base_url('/admin/books?page=' . ($currentPage - 1)) ?>">Previous</a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= base_url('/admin/books?page=' . $i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= base_url('/admin/books?page=' . ($currentPage + 1)) ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
