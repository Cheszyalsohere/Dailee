<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - LibSpace' : 'LibSpace - Perpustakaan Digital' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2f221c;
            --secondary: #47362d;
            --surface: #382c24;
            --surface-soft: #4a3a30;
            --accent: #d8c2a0;
            --accent-secondary: #b99974;
            --text-light: #fffaf5;
            --text-bright: #fff2de;
            --text-secondary: #eadbc6;
            --border: #6b5645;
            --shadow: rgba(24, 17, 14, 0.45);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            background: linear-gradient(180deg, #221a15 0%, #2f221c 45%, #1c1511 100%);
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(90deg, #2f221c 0%, #47362d 50%, #2f221c 100%);
            box-shadow: 0 4px 20px rgba(15, 10, 8, 0.35);
            border-bottom: 3px solid var(--border);
            backdrop-filter: blur(12px);
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 900;
            font-size: 1.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: linear-gradient(90deg, #efe1c7 0%, #d8c2a0 45%, #b99974 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
        }

        .nav-link {
            transition: all 0.3s ease;
            color: var(--text-secondary) !important;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-family: 'Inter', sans-serif;
        }

        .nav-link:hover {
            color: var(--accent) !important;
            text-shadow: 0 0 15px rgba(0, 212, 255, 0.8);
        }

        .card {
            background: linear-gradient(135deg, rgba(71, 54, 45, 0.85) 0%, rgba(47, 34, 28, 0.78) 100%);
            border: 1px solid rgba(216, 194, 160, 0.18);
            box-shadow: 0 8px 32px rgba(15, 10, 8, 0.35);
            transition: all 0.3s ease;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 48px rgba(15, 10, 8, 0.45);
            border-color: rgba(216, 194, 160, 0.35);
            background: linear-gradient(135deg, rgba(77, 60, 50, 0.95) 0%, rgba(46, 36, 29, 0.88) 100%);
        }

        .btn-primary {
            background: linear-gradient(90deg, #efe1c7 0%, #d8c2a0 100%);
            border: none;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #2f221c;
            box-shadow: 0 4px 15px rgba(216, 194, 160, 0.18);
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #fff5ea 0%, #e3d4ba 100%);
            box-shadow: 0 6px 25px rgba(216, 194, 160, 0.28);
            transform: translateY(-2px);
            color: #2f221c;
        }

        .btn-dark {
            background: rgba(26, 26, 46, 0.9);
            border: 2px solid var(--accent);
            color: var(--accent);
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }

        .btn-dark:hover {
            background: var(--accent);
            color: #0f0f1e;
        }

        .btn-outline-light {
            border: 2px solid var(--accent);
            color: var(--accent);
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }

        .btn-outline-light:hover {
            background: var(--accent);
            color: #0f0f1e;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--accent), #00ffff);
            color: #0f0f1e;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            width: 235px;
            background: linear-gradient(180deg, #372c25 0%, #241a14 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            overflow-x: hidden;
            border-right: 3px solid var(--border);
            box-shadow: 0 0 30px rgba(15, 10, 8, 0.35);
            box-sizing: border-box;
        }

        .sidebar h5 {
            color: var(--accent);
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar .nav {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            min-height: 48px;
            color: var(--text-secondary);
            padding: 0.75rem 0.9rem 0.75rem 1rem;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            line-height: 1.2;
            text-align: left;
            white-space: normal;
            overflow-wrap: anywhere;
            box-sizing: border-box;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(216, 194, 160, 0.12);
            border-left-color: var(--accent);
            color: var(--text-bright);
            padding-left: 1rem;
            box-shadow: inset 0 0 20px rgba(216, 194, 160, 0.08);
            text-shadow: 0 0 10px rgba(216, 194, 160, 0.15);
        }

        .main-content {
            margin-left: 235px;
            padding: 30px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }

            .main-content {
                margin-left: 0;
            }
        }

        .alert-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 300px;
            z-index: 1000;
        }

        .alert-success {
            background: linear-gradient(90deg, rgba(216, 194, 160, 0.15) 0%, rgba(185, 153, 116, 0.18) 100%);
            border: 2px solid rgba(216, 194, 160, 0.45);
            color: var(--text-bright);
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
        }

        .alert-danger {
            background: linear-gradient(90deg, rgba(118, 98, 81, 0.22) 0%, rgba(61, 47, 38, 0.24) 100%);
            border: 2px solid rgba(185, 153, 116, 0.45);
            color: #efe1c7;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
        }

        .hero {
            background: linear-gradient(135deg, #221a15 0%, #3a2d25 45%, #221a15 100%);
            color: var(--text-light);
            padding: 120px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-bottom: 2px solid var(--border);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(216, 194, 160, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(185, 153, 116, 0.06) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3.5rem;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: linear-gradient(90deg, var(--accent), #00ffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(0, 212, 255, 0.3);
            margin-bottom: 20px;
        }

        .hero p {
            font-family: 'Inter', sans-serif;
            font-size: 1.2rem;
            letter-spacing: 1px;
            font-weight: 500;
            opacity: 0.95;
            color: var(--text-secondary);
        }

        .footer {
            background: linear-gradient(180deg, #221a15 0%, #37302a 100%);
            color: var(--text-secondary);
            padding: 40px 30px;
            text-align: center;
            margin-top: 50px;
            border-top: 3px solid var(--border);
            font-family: 'Inter', sans-serif;
        }

        .footer p {
            margin-bottom: 10px;
            font-weight: 600;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(71, 54, 45, 0.68) 0%, rgba(47, 34, 28, 0.56) 100%);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            border: 2px solid rgba(0, 212, 255, 0.25);
            box-shadow: 0 8px 32px rgba(0, 212, 255, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: linear-gradient(135deg, rgba(77, 60, 50, 0.86) 0%, rgba(46, 36, 29, 0.74) 100%);
            border-color: var(--accent);
            transform: translateY(-5px);
            box-shadow: 0 12px 48px rgba(15, 10, 8, 0.35);
        }

        .stat-number {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(90deg, var(--accent), #00ffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
        }

        .table {
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
        }

        .table thead {
            border-bottom: 2px solid var(--accent);
        }

        .table th {
            color: var(--text-bright);
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
        }

        .table td {
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
            font-weight: 500;
            color: var(--text-secondary);
        }

        .table tr:hover {
            background: rgba(216, 194, 160, 0.08);
        }

        .card-body {
            background: linear-gradient(135deg, rgba(58, 42, 34, 0.88) 0%, rgba(37, 28, 23, 0.92) 100%);
            color: var(--text-light);
        }

        .card-body p {
            color: var(--text-light);
        }

        .form-control, .form-select {
            background: rgba(26, 26, 46, 0.7);
            border: 1px solid rgba(0, 212, 255, 0.3);
            color: var(--text-light);
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(47, 34, 28, 0.95);
            border-color: var(--accent);
            box-shadow: 0 0 15px rgba(216, 194, 160, 0.18);
            color: var(--text-light);
        }

        .form-label {
            color: var(--text-bright);
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Poppins', sans-serif;
            color: var(--text-light);
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        p {
            font-family: 'Inter', sans-serif;
            color: var(--text-secondary);
            font-weight: 500;
        }

        a {
            color: var(--accent);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
        }

        a:hover {
            color: #00ffff;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        ::placeholder {
            color: rgba(224, 224, 224, 0.4);
            font-family: 'Inter', sans-serif;
        }

        .input-group {
            gap: 0;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .btn {
            border-left: none;
        }

        .display-4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 900;
            background: linear-gradient(90deg, var(--accent), #00ffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lead {
            font-family: 'Inter', sans-serif;
            color: var(--text-secondary);
            font-weight: 600;
        }

        strong, .font-weight-bold {
            font-family: 'Poppins', sans-serif;
            color: var(--text-bright);
            font-weight: 700;
        }

        small {
            font-family: 'Inter', sans-serif;
            color: var(--text-secondary);
        }

        .text-danger {
            color: #ff66b3 !important;
            font-weight: 700;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .text-white {
            color: var(--text-light) !important;
        }

        .btn-secondary {
            background: rgba(160, 160, 160, 0.3);
            border: 2px solid rgba(176, 224, 255, 0.4);
            color: var(--text-secondary);
            font-weight: 700;
        }

        .btn-secondary:hover {
            background: rgba(176, 224, 255, 0.2);
            color: var(--text-bright);
        }

        .btn-warning {
            background: linear-gradient(90deg, rgba(255, 193, 7, 0.3) 0%, rgba(255, 152, 0, 0.3) 100%);
            border: 1px solid rgba(255, 193, 7, 0.6);
            color: #ffc107;
            font-weight: 700;
        }

        .btn-warning:hover {
            background: linear-gradient(90deg, rgba(255, 193, 7, 0.5) 0%, rgba(255, 152, 0, 0.5) 100%);
            color: #ffeb3b;
        }

        .btn-danger {
            background: linear-gradient(90deg, rgba(255, 0, 110, 0.3) 0%, rgba(192, 57, 43, 0.3) 100%);
            border: 1px solid rgba(255, 0, 110, 0.6);
            color: #ff66b3;
            font-weight: 700;
        }

        .btn-danger:hover {
            background: linear-gradient(90deg, rgba(255, 0, 110, 0.5) 0%, rgba(192, 57, 43, 0.5) 100%);
            color: #ff99cc;
        }

        .btn-success {
            background: linear-gradient(90deg, rgba(76, 175, 80, 0.3) 0%, rgba(56, 142, 60, 0.3) 100%);
            border: 1px solid rgba(76, 175, 80, 0.6);
            color: #66bb6a;
            font-weight: 700;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, rgba(76, 175, 80, 0.5) 0%, rgba(56, 142, 60, 0.5) 100%);
            color: #81c784;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }

        .badge.bg-info {
            background: rgba(0, 188, 212, 0.4) !important;
            color: #00ffff !important;
            border: 1px solid rgba(0, 212, 255, 0.6);
        }

        .badge.bg-warning {
            background: rgba(255, 193, 7, 0.3) !important;
            color: #ffc107 !important;
            border: 1px solid rgba(255, 193, 7, 0.6);
        }

        .badge.bg-success {
            background: rgba(76, 175, 80, 0.3) !important;
            color: #66bb6a !important;
            border: 1px solid rgba(76, 175, 80, 0.6);
        }

        .badge.bg-danger {
            background: rgba(255, 0, 110, 0.3) !important;
            color: #ff66b3 !important;
            border: 1px solid rgba(255, 0, 110, 0.6);
        }

        .badge.bg-secondary {
            background: rgba(160, 160, 160, 0.3) !important;
            color: var(--text-secondary) !important;
            border: 1px solid rgba(160, 160, 160, 0.5);
        }

        .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: #0f0f1e;
        }

        .page-link {
            background: rgba(26, 26, 46, 0.5);
            border-color: rgba(0, 212, 255, 0.3);
            color: var(--text-secondary);
            font-weight: 600;
        }

        .page-link:hover {
            background: rgba(0, 212, 255, 0.2);
            border-color: var(--accent);
            color: var(--accent);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>">📚 LibSpace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item">
                            <span class="nav-link">👤 <?= session()->get('username') ?></span>
                        </li>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">Dashboard Admin</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('/user/dashboard') ?>">Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/auth/logout') ?>">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/auth/login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/auth/register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show alert-toast" role="alert">
            ✓ <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show alert-toast" role="alert">
            ✕ <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 LibSpace - Perpustakaan Digital. All rights reserved.</p>
            <p style="font-size: 0.9rem; margin-top: 10px;">Dibuat dengan ❤️ menggunakan CodeIgniter 4 & Bootstrap 5</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert-toast').forEach(alert => {
            setTimeout(() => {
                new bootstrap.Alert(alert).close();
            }, 5000);
        });
    </script>
</body>
</html>
