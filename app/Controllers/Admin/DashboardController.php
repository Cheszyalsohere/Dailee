<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        // View sederhana buat ngetest admin berhasil masuk
        echo "<h1 style='color:#4D3EA3; font-family:sans-serif; text-align:center; margin-top:20%'>
                ✨ Welcome to Admin Panel, " . session()->get('username') . "! ✨
              </h1>";
        echo "<div style='text-align:center;'><a href='/logout'>Logout</a></div>";
    }
}