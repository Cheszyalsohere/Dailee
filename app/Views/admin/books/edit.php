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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="mb-4" style="color: var(--text-light);">Edit Buku</h1>

                    <div class="card">
                        <div class="card-body">
                            <form action="<?= base_url('/admin/books/update/' . $book['id']) ?>" method="post">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label for="nama_buku" class="form-label">Nama Buku <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_buku" name="nama_buku" value="<?= esc($book['nama_buku']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="penulis" name="penulis" value="<?= esc($book['penulis']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= esc($book['penerbit']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="<?= $book['tahun_terbit'] ?>" min="1900" max="<?= date('Y') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="stok" name="stok" value="<?= $book['stok'] ?>" min="0" required>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="<?= base_url('/admin/books') ?>" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
