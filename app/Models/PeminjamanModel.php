<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['user_id', 'book_id', 'tanggal_pinjam', 'tanggal_kembali', 'tanggal_dikembalikan', 'status', 'denda'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function getPeminjamanUser($userId)
    {
        return $this->select('peminjaman.*, books.nama_buku, books.penulis, users.username')
            ->join('books', 'books.id = peminjaman.book_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->where('peminjaman.user_id', $userId)
            ->orderBy('peminjaman.id', 'DESC')
            ->findAll();
    }

    public function getAllPeminjaman()
    {
        return $this->select('peminjaman.*, books.nama_buku, books.penulis, users.username')
            ->join('books', 'books.id = peminjaman.book_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->orderBy('peminjaman.id', 'DESC')
            ->findAll();
    }

    public function getPeminjamanByStatus($status)
    {
        return $this->select('peminjaman.*, books.nama_buku, users.username')
            ->join('books', 'books.id = peminjaman.book_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->where('peminjaman.status', $status)
            ->orderBy('peminjaman.id', 'DESC')
            ->findAll();
    }

    public function countPeminjaman()
    {
        return $this->countAllResults();
    }

    public function countStatusPeminjaman($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    public function calculateDenda($peminjamanId)
    {
        $peminjaman = $this->find($peminjamanId);

        if (!$peminjaman || $peminjaman['status'] !== 'dipinjam') {
            return 0;
        }

        $today = date('Y-m-d');
        $tanggalKembali = $peminjaman['tanggal_kembali'];

        if ($today <= $tanggalKembali) {
            return 0;
        }

        $daysLate = max(1, (int) ceil((strtotime($today) - strtotime($tanggalKembali)) / 86400));
        $weeksLate = (int) ceil($daysLate / 7);

        return $weeksLate * 2000;
    }

    public function returnBook($peminjamanId)
    {
        $peminjaman = $this->find($peminjamanId);

        if (!$peminjaman || $peminjaman['status'] !== 'dipinjam') {
            return false;
        }

        $denda = $this->calculateDenda($peminjamanId);

        return $this->update($peminjamanId, [
            'tanggal_dikembalikan' => date('Y-m-d'),
            'status' => 'dikembalikan',
            'denda' => $denda
        ]);
    }

    public function checkActiveLoan($userId, $bookId)
    {
        return $this->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('status', 'dipinjam')
            ->first();
    }
}
