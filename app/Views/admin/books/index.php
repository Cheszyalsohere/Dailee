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
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('/admin/books') ?>">Kelola Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/peminjaman') ?>">Kelola Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/booking') ?>">Kelola Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/users') ?>">Kelola Anggota</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/site/images') ?>">Kelola Gambar Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/auth/logout') ?>">Keluar</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
                <h1 class="mb-0" style="color: var(--text-light);">Kelola Buku</h1>
                <a href="<?= base_url('/admin/books/create') ?>" class="btn" style="background-color: #6d5747; color: white; font-weight: bold;">+ Tambah Buku</a>
            </div>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm" style="background: linear-gradient(180deg, #efe4d4 0%, #e4d7c5 100%);">
                <div class="card-body">
                    <?php if (empty($books)): ?>
                        <p class="text-muted text-center py-5">Belum ada buku tersedia.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background: linear-gradient(135deg, #6d5747 0%, #4c3d30 100%); color: #ffffff;">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Buku</th>
                                        <th>Penulis</th>
                                        <th>Penerbit</th>
                                        <th>Tahun</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($books as $b): ?>
                                        <tr style="background: linear-gradient(135deg, #efe4d4 0%, #e3d6c4 100%); color: #111111;">
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($b['nama_buku']) ?></td>
                                            <td><?= esc($b['penulis']) ?></td>
                                            <td><?= esc($b['penerbit']) ?></td>
                                            <td><?= $b['tahun_terbit'] ?></td>
                                            <td><span class="badge" style="background-color: #bfd4c1; color: #223226;"><?= $b['stok'] ?></span></td>
                                            <td>
                                                <a href="<?= base_url('/admin/books/edit/' . $b['id']) ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                                <a href="<?= base_url('/admin/books/delete/' . $b['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
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