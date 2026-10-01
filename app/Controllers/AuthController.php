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

        // Batasi percobaan login: maksimal 5 per menit untuk kombinasi IP + username.
        $throttleKey = 'login_' . md5($this->request->getIPAddress() . '|' . strtolower($username));
        if (! service('throttler')->check($throttleKey, 5, MINUTE)) {
            return redirect()->to('/login')->with('error', 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.');
        }

        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->regenerate(true);
            $session->set([
                'id'         => $user['id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
                'user_id'    => $user['id'],
                'logged_in'  => true,
            ]);

            return $user['role'] === 'admin'
                ? redirect()->to('/admin/dashboard')
                : redirect()->to('/dashboard');
        }

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

        $rules = [
            'username'     => 'required|min_length[3]|max_length[30]|alpha_dash|is_unique[users.username]',
            'nama_lengkap' => 'required|max_length[100]',
            'email'        => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'password'     => 'required|min_length[8]|max_length[72]',
        ];
        $messages = [
            'username' => [
                'min_length' => 'Username minimal 3 karakter.',
                'max_length' => 'Username maksimal 30 karakter.',
                'alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
                'is_unique'  => 'Username sudah dipakai! Coba username lain.',
            ],
            'nama_lengkap' => ['required' => 'Nama lengkap wajib diisi.'],
            'email' => [
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar!',
            ],
            'password' => ['min_length' => 'Password minimal 8 karakter.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', (string) current($this->validator->getErrors()));
        }

        $userModel->save([
            'username'     => trim((string) $this->request->getPost('username')),
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'email'        => trim((string) $this->request->getPost('email')),
            'password'     => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
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
