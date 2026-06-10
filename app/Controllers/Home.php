<?php

namespace App\Controllers;

use App\Models\BookModel;

class Home extends BaseController
{
    protected $bookModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
    }

    public function index()
    {
        $data = [
            'books' => $this->bookModel->getLatestBooks(6)
        ];

        return view('home/index', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (empty($keyword)) {
            return redirect()->to('/');
        }

        $data = [
            'books' => $this->bookModel->searchBooks($keyword),
            'keyword' => $keyword
        ];

        return view('home/index', $data);
    }
}
