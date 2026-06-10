<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\PeminjamanModel;

class User extends BaseController
{
    protected $bookModel;
    protected $peminjamanModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->peminjamanModel = new PeminjamanModel();
        $this->checkUser();
    }

    private function checkUser()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu!');
        }
    }

    public function dashboard()
    {
        $userId = session()->get('user_id');
        $history = $this->peminjamanModel->getPeminjamanUser($userId);

        $activeLoans = array_filter($history, fn($item) => $item['status'] === 'dipinjam');
        $lateLoans = array_filter($history, fn($item) => $item['status'] === 'dipinjam' && $item['tanggal_kembali'] < date('Y-m-d'));

        $data = [
            'books' => $this->bookModel->getAllBooks(12),
            'totalPeminjaman' => count($history),
            'activeLoans' => count($activeLoans),
            'lateLoans' => count($lateLoans),
        ];

        return view('user/dashboard', $data);
    }

    public function searchBooks()
    {
        $keyword = $this->request->getGet('q');
        
        if (empty($keyword)) {
            return redirect()->to('/user/dashboard');
        }

        $data = [
            'books' => $this->bookModel->searchBooks($keyword),
            'keyword' => $keyword
        ];

        return view('user/search', $data);
    }

    public function borrowBook($bookId)
    {
        $userId = session()->get('user_id');
        $book = $this->bookModel->find($bookId);

        if (!$book) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan!');
        }

        if ($book['stok'] <= 0) {
            return redirect()->back()->with('error', 'Stok buku tidak tersedia!');
        }

        // Check if already borrowing this book
        $activeLoan = $this->peminjamanModel->checkActiveLoan($userId, $bookId);
        if ($activeLoan) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini!');
        }

        $tanggalPinjam = date('Y-m-d');
        $tanggalKembali = date('Y-m-d', strtotime('+7 days'));

        $data = [
            'user_id' => $userId,
            'book_id' => $bookId,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'dipinjam',
            'denda' => 0
        ];

        if ($this->peminjamanModel->insert($data)) {
            $this->bookModel->updateStok($bookId, -1);
            return redirect()->to('/user/history')->with('success', 'Buku berhasil dipinjam! Batas pengembalian: ' . $tanggalKembali);
        } else {
            return redirect()->back()->with('error', 'Gagal meminjam buku!');
        }
    }

    public function history()
    {
        $userId = session()->get('user_id');

        $data = [
            'peminjaman' => $this->peminjamanModel->getPeminjamanUser($userId)
        ];

        return view('user/history', $data);
    }

    public function returnBookRequest($peminjamanId)
    {
        $peminjaman = $this->peminjamanModel->find($peminjamanId);

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Peminjaman tidak ditemukan!');
        }

        if ($peminjaman['user_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses!');
        }

        if ($peminjaman['status'] !== 'dipinjam') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan!');
        }

        $today = date('Y-m-d');
        $denda = $this->peminjamanModel->calculateDenda($peminjamanId);

        $this->peminjamanModel->update($peminjamanId, [
            'tanggal_dikembalikan' => $today,
            'status' => 'dikembalikan',
            'denda' => $denda
        ]);

        // Update stok buku
        $this->bookModel->updateStok($peminjaman['book_id'], 1);

        $message = 'Buku berhasil dikembalikan!';
        if ($denda > 0) {
            $message .= ' Denda: Rp' . number_format($denda);
        }

        return redirect()->to('/user/history')->with('success', $message);
    }
}
