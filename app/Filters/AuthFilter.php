<?php
// app/Filters/AuthFilter.php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // kalau ada argumen role, ex: ['filter' => 'auth:admin']
        if ($arguments && !empty($arguments[0])) {
            $requiredRole = $arguments[0];
            if (session()->get('role') !== $requiredRole) {
                return redirect()->to('/dashboard')
                    ->with('error', 'Kamu tidak punya akses ke halaman itu.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu apa-apa di sini
    }
}