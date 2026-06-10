<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4" style="color: var(--primary);">Login</h3>
                    
                    <form action="<?= base_url('/auth/login') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0">
                        Belum punya akun? <a href="<?= base_url('/auth/register') ?>">Daftar di sini</a>
                    </p>

                    <p class="text-center mt-3" style="font-size: 0.9rem; color: #7f8c8d;">
                        <strong>Demo Admin:</strong><br>
                        Username: admin<br>
                        Password: admin123
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
