<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function loginForm()
    {
        // Kalau sudah login, langsung lempar ke dashboard masing-masing
        if (session()->get('isLoggedIn')) {
            return session()->get('role') === 'admin'
                ? redirect()->to('/admin/dashboard')
                : redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $userModel = new UserModel();
        
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');
        
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // Cek password hash atau plain text (untuk testing)
            $isPasswordValid = password_verify($password, $user['password']) || ($password === $user['password']);

            if ($isPasswordValid) {
                $session->set([
                    'id'         => $user['id'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true,
                    'user_id'   => $user['id'],
                    'logged_in' => true,
                ]);

                // Redirect sesuai role
                if ($user['role'] === 'admin') {
                    return redirect()->to('/admin/dashboard');
                }

                return redirect()->to('/dashboard');
            }
        }
        
        // Simpan flashdata dan kembali ke halaman login
        return redirect()->to('/login')->with('error', 'Username atau Password salah.');
    }

    public function registerForm()
    {
        // Kalau sudah login, lempar langsung
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    public function register()
    {
        $userModel = new UserModel();

        $username    = trim((string) $this->request->getPost('username'));
        $namaLengkap = trim((string) $this->request->getPost('nama_lengkap'));
        $email       = trim((string) $this->request->getPost('email'));
        $password    = (string) $this->request->getPost('password');

        // Validasi duplikasi username
        if ($userModel->where('username', $username)->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah dipakai! Coba username lain.');
        }

        // Validasi duplikasi email
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar!');
        }

        // Simpan user baru dengan password hash aman
        $userModel->save([
            'username'     => $username,
            'nama_lengkap' => $namaLengkap,
            'email'        => $email,
            'password'     => password_hash($password, PASSWORD_BCRYPT),
            'role'         => 'user',
        ]);

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat! Silakan login ✨');
    }

    /** Local-only simulated Google/Apple sign-in for demos; never enabled in production. */
    public function demoLogin(string $provider)
    {
        $enabled = strtolower(trim((string) env('DEMO_LOGIN_ENABLED', 'false')));
        if (ENVIRONMENT !== 'development' || !in_array($enabled, ['true', '1', 'yes'], true)) {
            return $this->response->setStatusCode(404)->setBody('Not Found');
        }

        $provider = strtolower($provider);
        if (!in_array($provider, ['google', 'apple'], true)) {
            return redirect()->to('/login')->with('error', 'Penyedia login demo tidak dikenal.');
        }

        $userModel = new UserModel();
        $username = 'demo_' . $provider;
        $user = $userModel->where('username', $username)->first();

        if (!$user) {
            // Match a known non-admin role so this also works with schemas that
            // use "mahasiswa" instead of the "user" role label.
            $regularUser = $userModel->where('role !=', 'admin')->first();
            $demoRole = $regularUser['role'] ?? 'user';
            $userModel->save([
                'username'     => $username,
                'nama_lengkap' => 'Dailee ' . ucfirst($provider) . ' Demo',
                'email'        => $username . '@dailee.local',
                'password'     => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
                'role'         => $demoRole,
            ]);
            $user = $userModel->where('username', $username)->first();
        }

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Akun demo belum bisa dibuat. Periksa tabel users.');
        }

        session()->regenerate(true);
        session()->set([
            'id'         => $user['id'],
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
            'logged_in'  => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
