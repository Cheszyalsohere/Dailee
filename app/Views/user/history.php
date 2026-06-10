<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 sidebar" style="background: linear-gradient(180deg, #372c25 0%, #241a14 100%); border-right: 3px solid #6b5645;">
            <div class="text-white px-3 mb-4">
                <h5>Dashboard User</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/user/dashboard') ?>">
                    Tampilan Daftar Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/user/history') ?>">
                    Riwayat Peminjaman
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
            <h1 class="mb-4" style="color: #ffffff;">📖 Riwayat Peminjaman</h1>

            <!-- Peminjaman Table -->
            <div class="card border-0 shadow-sm" style="background: linear-gradient(180deg, #d7c3a7 0%, #c7b08f 100%);">
                <div class="card-body">
                    <?php if (empty($peminjaman)): ?>
                        <p class="text-muted text-center py-5">Anda belum pernah meminjam buku</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 history-table" style="border-collapse: separate; border-spacing: 0;">
                                <thead style="background: linear-gradient(135deg, #6d5747 0%, #4c3d30 100%); color: #ffffff;">
                                    <tr>
                                        <th>No</th>
                                        <th>Buku</th>
                                        <th>Penulis</th>
                                        <th>Tgl Pinjam</th>
                                        <th>Tgl Kembali</th>
                                        <th>Tgl Dikembalikan</th>
                                        <th>Status</th>
                                        <th>Denda</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($peminjaman as $item): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($item['nama_buku']) ?></td>
                                            <td><?= esc($item['penulis']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($item['tanggal_pinjam'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($item['tanggal_kembali'])) ?></td>
                                            <td>
                                                <?php if ($item['tanggal_dikembalikan']): ?>
                                                    <?= date('d/m/Y', strtotime($item['tanggal_dikembalikan'])) ?>
                                                <?php else: ?>
                                                    <span class="badge history-badge pending">Belum</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($item['status'] === 'dipinjam'): ?>
                                                    <span class="badge history-badge borrow">Dipinjam</span>
                                                <?php else: ?>
                                                    <span class="badge history-badge returned">Dikembalikan</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($item['denda'] > 0): ?>
                                                    <span class="badge history-badge penalty">Rp<?= number_format($item['denda']) ?></span>
                                                <?php else: ?>
                                                    <span class="badge history-badge none">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($item['status'] === 'dipinjam'): ?>
                                                    <a href="<?= base_url('/user/return/' . $item['id']) ?>" class="btn btn-sm history-action-btn" onclick="return confirm('Kembalikan buku ini?')">
                                                        Kembalikan
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge history-status-done">Selesai</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <style>
                .history-table thead th {
                    border-bottom: 1px solid #5c4b3c;
                    font-weight: 700;
                    letter-spacing: 0.2px;
                    color: #ffffff;
                    background: linear-gradient(135deg, #6d5747 0%, #4c3d30 100%);
                }

                .history-table tbody td {
                    background-color: #d8c6af;
                    color: #111111;
                    border-color: #c6b79c;
                }

                .history-table tbody tr:nth-child(even) td {
                    background-color: #cfbda3;
                }

                .history-table tbody tr:hover td {
                    background-color: #c6af91;
                    color: #111111;
                }

                .history-badge {
                    color: #111111;
                    background-color: #eadcc9;
                    border: 1px solid #c9b89d;
                    font-weight: 600;
                }

                .history-badge.pending {
                    background-color: #dfc89d;
                    color: #3d311b;
                }

                .history-badge.borrow {
                    background-color: #cbbd9a;
                    color: #2f261d;
                }

                .history-badge.returned {
                    background-color: #bfd4c1;
                    color: #223226;
                }

                .history-badge.penalty {
                    background-color: #d9b4a0;
                    color: #4a2c21;
                }

                .history-badge.none {
                    background-color: #d9d0c3;
                    color: #3f382f;
                }

                .history-action-btn {
                    background-color: #7f9db8;
                    border: 1px solid #7f9db8;
                    color: #ffffff;
                    font-weight: 600;
                }

                .history-action-btn:hover,
                .history-action-btn:focus {
                    background-color: #6f8fab;
                    border-color: #6f8fab;
                    color: #ffffff;
                }

                .history-status-done {
                    background-color: #7f9db8;
                    color: #ffffff;
                    border: 1px solid #7f9db8;
                    font-weight: 600;
                }
            </style>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
