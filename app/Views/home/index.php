<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
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

        <div class="d-flex justify-content-center mb-4">
            <form action="<?= base_url('/search') ?>" method="get" class="w-100" style="max-width:560px;">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari buku di LibSpace..." value="<?= esc($keyword ?? '') ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>

        <?php if ($banner): ?>
            <div class="mb-4 text-center">
                <img src="<?= $banner ?>" alt="Home Banner" class="img-fluid rounded" style="max-height:360px; width:100%; object-fit:cover;">
            </div>
        <?php else: ?>
            <div class="mb-4 text-center text-white">
                <div class="p-5" style="background: rgba(255,255,255,0.1); border: 1px dashed rgba(255,255,255,0.6);">
                    <h2>Gambar Home belum diunggah.</h2>
                    <p>Admin dapat mengunggah gambar dari halaman ini atau halaman Site Images.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
            <div class="card mb-4" style="border: 2px solid rgba(0, 212, 255, 0.5); background: linear-gradient(135deg, rgba(90, 24, 154, 0.2) 0%, rgba(123, 44, 191, 0.1) 100%);">
                <div class="card-body">
                    <h5 class="card-title" style="color: #00d4ff;">Unggah Gambar Home</h5>
                    <form action="<?= base_url('/admin/site/images/upload') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <input type="file" name="home_image" accept="image/*" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-light">Unggah Gambar</button>
                    </form>
                    <p class="small text-white mt-3">Foto ini akan tampil langsung di halaman home setelah berhasil diunggah.</p>
                </div>
            </div>
        <?php endif; ?>

        <h1 class="display-4 fw-bold mb-4" style="font-size: 3rem; letter-spacing: 2px;">Hi, Welcome to LibSpace</h1>
        <p class="lead mb-4" style="font-size: 1.3rem; letter-spacing: 0.5px;">Siap Melayani Anda</p>
        <?php if (session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
            <div class="d-flex justify-content-center mt-4">
                <a href="<?= base_url('/admin/site/images') ?>" class="btn btn-outline-light btn-lg">Edit Gambar Home</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Buku Terbaru Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold" style="color: var(--primary);">Buku Tersedia</h2>
        
        <?php if (empty($books)): ?>
            <div class="alert alert-info">
                <p class="mb-0">Belum ada buku yang tersedia. Silakan kembali lagi nanti.</p>
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
                                <?php if (session()->get('isLoggedIn') && session()->get('role') === 'user' && $book['stok'] > 0): ?>
                                    <a href="<?= base_url('/user/borrow/' . $book['id']) ?>" class="btn btn-sm btn-primary w-100">
                                        Pinjam Buku
                                    </a>
                                <?php elseif (!session()->get('isLoggedIn')): ?>
                                    <a href="<?= base_url('/auth/login') ?>" class="btn btn-sm btn-primary w-100">
                                        Login untuk Pinjam
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
