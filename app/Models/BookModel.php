<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['nama_buku', 'penulis', 'penerbit', 'tahun_terbit', 'stok'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    public function getLatestBooks($limit = 6)
    {
        return $this->orderBy('id', 'DESC')->limit($limit)->findAll();
    }

    public function getAllBooks($limit = null, $offset = 0)
    {
        if ($limit) {
            return $this->limit($limit, $offset)->findAll();
        }
        return $this->findAll();
    }

    public function searchBooks($keyword)
    {
        return $this->like('nama_buku', $keyword)
            ->orLike('penulis', $keyword)
            ->orLike('penerbit', $keyword)
            ->findAll();
    }

    public function countBooks()
    {
        return $this->countAllResults();
    }

    public function updateStok($bookId, $quantity)
    {
        $book = $this->find($bookId);
        if ($book) {
            $newStok = $book['stok'] + $quantity;
            return $this->update($bookId, ['stok' => $newStok]);
        }
        return false;
    }
}
