<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\UserModel;
use App\Models\PeminjamanModel;

class Admin extends BaseController
{
    protected $bookModel;
    protected $userModel;
    protected $peminjamanModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->userModel = new UserModel();
        $this->peminjamanModel = new PeminjamanModel();
        $this->ensureUserStatusColumn();
        $this->checkAdmin();
    }

    private function ensureUserStatusColumn()
    {
        $db = db_connect();
        $fields = $db->getFieldData('users');

        foreach ($fields as $field) {
            if ($field->name === 'is_active') {
                return;
            }
        }

        $db->query('ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1');
    }

    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Anda tidak memiliki akses!');
        }
    }

    public function dashboard()
    {
        $data = [
            'totalBooks' => $this->bookModel->countBooks(),
            'totalUsers' => $this->userModel->countUsers() - 1, // exclude admin
            'totalPeminjaman' => $this->peminjamanModel->countPeminjaman(),
            'aktivPeminjaman' => $this->peminjamanModel->countStatusPeminjaman('dipinjam'),
            'books' => $this->bookModel->findAll(10),
        ];

        return view('admin/dashboard', $data);
    }

    // CRUD Buku
    public function booksIndex($page = 1)
    {
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $totalBooks = $this->bookModel->countBooks();
        $totalPages = ceil($totalBooks / $perPage);

        $data = [
            'books' => $this->bookModel->getAllBooks($perPage, $offset),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalBooks' => $totalBooks
        ];

        return view('admin/books/index', $data);
    }

    public function bookCreate()
    {
        return view('admin/books/create');
    }

    public function bookStore()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back();
        }

        $data = [
            'nama_buku' => $this->request->getPost('nama_buku'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'stok' => $this->request->getPost('stok')
        ];

        if ($this->bookModel->insert($data)) {
            return redirect()->to('/admin/books')->with('success', 'Buku berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan buku!');
        }
    }

    public function bookEdit($id)
    {
        $data = [
            'book' => $this->bookModel->find($id)
        ];

        if (!$data['book']) {
            return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan!');
        }

        return view('admin/books/edit', $data);
    }

    public function bookUpdate($id)
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back();
        }

        $data = [
            'nama_buku' => $this->request->getPost('nama_buku'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'stok' => $this->request->getPost('stok')
        ];

        if ($this->bookModel->update($id, $data)) {
            return redirect()->to('/admin/books')->with('success', 'Buku berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui buku!');
        }
    }

    public function bookDelete($id)
    {
        if ($this->bookModel->delete($id)) {
            return redirect()->to('/admin/books')->with('success', 'Buku berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus buku!');
        }
    }

    // Anggota / User management
    public function usersIndex()
    {
        $users = $this->userModel
            ->where('role', 'user')
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('admin/users/index', ['users' => $users]);
    }

    public function userCreate()
    {
        return view('admin/users/create');
    }

    public function userStore()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back();
        }

        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        if ($username === '' || $password === '') {
            return redirect()->back()->with('error', 'Username dan password wajib diisi.');
        }

        if ($this->userModel->getUserByUsername($username)) {
            return redirect()->back()->with('error', 'Username sudah digunakan.');
        }

        $data = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => 'user',
            'is_active' => 1,
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/admin/users')->with('success', 'Anggota berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan anggota.');
    }

    public function userEdit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Anggota tidak ditemukan.');
        }

        return view('admin/users/edit', ['user' => $user]);
    }

    public function userUpdate($id)
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back();
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Anggota tidak ditemukan.');
        }

        $username = trim($this->request->getPost('username'));

        if ($username === '') {
            return redirect()->back()->with('error', 'Username wajib diisi.');
        }

        $existing = $this->userModel->where('username', $username)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Username sudah digunakan.');
        }

        $data = [
            'username' => $username,
            'role' => $this->request->getPost('role') ?? $user['role'],
            'is_active' => (int) ($this->request->getPost('is_active') ?? 1),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/admin/users')->with('success', 'Data anggota berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui data anggota.');
    }

    public function toggleUserStatus($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Anggota tidak ditemukan.');
        }

        $newStatus = (isset($user['is_active']) && (int)$user['is_active'] === 1) ? 0 : 1;

        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'Anggota diaktifkan kembali.' : 'Anggota berhasil dinonaktifkan.';
            return redirect()->to('/admin/users')->with('success', $message);
        }

        return redirect()->back()->with('error', 'Gagal mengubah status anggota.');
    }

    // Peminjaman
    public function peminjaman()
    {
        $data = [
            'peminjaman' => $this->peminjamanModel->getAllPeminjaman()
        ];

        return view('admin/peminjaman/index', $data);
    }

    public function approveReturn($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman || $peminjaman['status'] !== 'dipinjam') {
            return redirect()->back()->with('error', 'Peminjaman ini sudah selesai atau tidak ditemukan!');
        }

        $denda = $this->peminjamanModel->calculateDenda($id);

        if ($this->peminjamanModel->update($id, [
            'tanggal_dikembalikan' => date('Y-m-d'),
            'status' => 'dikembalikan',
            'denda' => $denda,
        ])) {
            $this->bookModel->updateStok($peminjaman['book_id'], 1);

            $message = 'Pengembalian buku berhasil diproses!';
            if ($denda > 0) {
                $message .= ' Denda: Rp' . number_format($denda);
            }

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'Gagal memproses pengembalian!');
    }

    // Site images management (admin)
    public function siteImages()
    {
        return view('admin/site/images');
    }

    public function uploadSiteImage()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->back();
        }

        $file = $this->request->getFile('home_image');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Gagal mengunggah file.');
        }

        $uploadsDir = FCPATH . 'uploads/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        $extension = strtolower($file->getExtension());
        $filename = 'home_banner.' . $extension;

        // Remove any existing home_banner.* files first so the newest upload is always used
        foreach (glob($uploadsDir . 'home_banner.*') as $oldFile) {
            if (is_file($oldFile)) {
                @unlink($oldFile);
            }
        }

        if ($file->move($uploadsDir, $filename, true)) {
            return redirect()->to('/admin/site/images')->with('success', 'Gambar home berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan file.');
    }
}
