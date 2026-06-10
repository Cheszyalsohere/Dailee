<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
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
                    <a class="nav-link" href="<?= base_url('/admin/peminjaman') ?>">
                    Kelola Peminjaman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/admin/site/images') ?>">
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

        <div class="col-md-9 col-lg-10 main-content">
            <h1 class="mb-4" style="color: var(--text-light);">Kelola Gambar Home</h1>

            <div class="card">
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('/admin/site/images/upload') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Unggah Gambar Home (banner)</label>
                            <input type="file" name="home_image" accept="image/*" class="form-control" required>
                        </div>
                        <button class="btn btn-primary" type="submit">Unggah</button>
                    </form>

                    <hr>

                    <h5>Preview Gambar Saat Ini</h5>
                    <?php
                        $uploads = FCPATH . 'uploads/';
                        $banner = null;

                        if (is_dir($uploads)) {
                            $files = scandir($uploads);
                            foreach ($files as $f) {
                                if (stripos($f, 'home_banner.') === 0) {
                                    $banner = base_url('uploads/' . $f);
                                    break;
                                }
                            }
                        }
                    ?>

                    <?php if ($banner): ?>
                        <img src="<?= $banner ?>" alt="Home Banner" class="img-fluid mt-3" style="max-height:300px;">
                        <p class="small text-muted mt-2">Gambar yang terupload akan muncul di halaman utama.</p>
                    <?php else: ?>
                        <p class="text-muted">Belum ada gambar. Silakan unggah foto dari Site Images.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
