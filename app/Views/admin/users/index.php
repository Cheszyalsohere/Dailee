<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 sidebar" style="background: linear-gradient(180deg, #372c25 0%, #241a14 100%); border-right: 3px solid #6b5645;">
            <div class="text-white px-3 mb-4">
                <h5>Dashboard Admin</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">Tampilan Daftar</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/books') ?>">Kelola Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/peminjaman') ?>">Kelola Peminjaman</a></li>
                <!-- Menu Kelola Booking (Baru Ditambahin) -->
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/booking') ?>">Kelola Booking</a></li>
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('/admin/users') ?>">Kelola Anggota</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/site/images') ?>">Kelola Gambar Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/auth/logout') ?>">Keluar</a></li>
            </ul>
        </nav>

        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
                <div>
                    <h1 class="mb-1" style="color: var(--text-light);">Kelola Anggota</h1>
                    <p class="text-muted mb-0">Lihat daftar pengguna, tambah anggota baru, ubah data, dan nonaktifkan akun.</p>
                </div>
                <a href="<?= base_url('/admin/users/create') ?>" class="btn" style="background-color: #6d5747; color: white; font-weight: bold;">+ Tambah Anggota</a>
            </div>

            <?= view('partials/alerts') ?>

            <div class="card border-0 shadow-sm" style="background: linear-gradient(180deg, #efe4d4 0%, #e4d7c5 100%);">
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <p class="text-muted mb-0">Belum ada anggota yang terdaftar.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background: linear-gradient(135deg, #6d5747 0%, #4c3d30 100%); color: #ffffff;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr style="background: linear-gradient(135deg, #efe4d4 0%, #e3d6c4 100%); color: #111111;">
                                            <td><?= esc($user['id']) ?></td>
                                            <td><?= esc($user['username']) ?></td>
                                            <td><?= esc($user['role']) ?></td>
                                            <td>
                                                <?php if (!empty($user['is_active'])): ?>
                                                    <span class="badge" style="background-color: #bfd4c1; color: #223226;">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge" style="background-color: #d9b4a0; color: #4a2c21;">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-warning me-1" href="<?= base_url('/admin/users/edit/' . $user['id']) ?>">Edit</a>
                                                <a class="btn btn-sm" style="background-color: #7f9db8; color: white; font-weight: 600;" href="<?= base_url('/admin/users/toggle/' . $user['id']) ?>" onclick="return confirm('Ubah status akun ini?')">
                                                    <?= !empty($user['is_active']) ? 'Nonaktifkan' : 'Aktifkan' ?>
                                                </a>
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