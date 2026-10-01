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
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/peminjaman') ?>">Kelola Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/booking') ?>">Kelola Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/users') ?>">Kelola Anggota</a></li>
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('/admin/site/images') ?>">Kelola Gambar Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/auth/logout') ?>">Keluar</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <h1 class="mb-4" style="color: var(--text-light);">Kelola Gambar Home</h1>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm" style="background: linear-gradient(180deg, #efe4d4 0%, #e4d7c5 100%);">
                <div class="card-body">
                    <p class="text-dark mb-4">Unggah gambar banner atau latar belakang baru untuk halaman utama (Home).</p>
                    
                    <form action="<?= base_url('/admin/site/images/upload') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="home_image" class="form-label font-weight-bold" style="color: #372c25;">Pilih Gambar (Format: JPG/PNG)</label>
                            <input type="file" name="home_image" id="home_image" class="form-control" required style="background-color: #eadcc9; border: 1px solid #c9b89d;">
                        </div>
                        <button type="submit" class="btn" style="background-color: #6d5747; color: white; font-weight: bold;">Unggah Gambar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>