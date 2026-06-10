<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 sidebar">
            <div class="text-white px-3 mb-4">
                <h5>Dashboard Admin</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">Tampilan Daftar</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/books') ?>">Kelola Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/peminjaman') ?>">Kelola Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('/admin/users') ?>">Kelola Anggota</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/auth/logout') ?>">Keluar</a></li>
            </ul>
        </nav>

        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-1" style="color: var(--text-light);">Edit Anggota</h1>
                    <p class="text-muted mb-0">Ubah username, role, dan status akun anggota.</p>
                </div>
                <a href="<?= base_url('/admin/users') ?>" class="btn btn-secondary">Kembali</a>
            </div>

            <div class="card shadow-sm border-0" style="background: linear-gradient(180deg, #efe4d4 0%, #e3d6c4 100%);">
                <div class="card-body">
                    <form action="<?= base_url('/admin/users/update/' . $user['id']) ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru (opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= !empty($user['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Akun aktif</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
