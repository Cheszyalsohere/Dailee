<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (strtoupper($this->request->getMethod()) === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                if (isset($user['is_active']) && (int) $user['is_active'] === 0) {
                    return redirect()->back()->with('error', 'Akun Anda sedang nonaktif. Hubungi admin.');
                }

                session()->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'isLoggedIn' => true
                ]);

                if ($user['role'] === 'admin') {
                    return redirect()->to('/admin/dashboard')->with('success', 'Login berhasil!');
                } else {
                    return redirect()->to('/user/dashboard')->with('success', 'Login berhasil!');
                }
            } else {
                return redirect()->back()->with('error', 'Username atau password salah!');
            }
        }

        return view('auth/login');
    }

    public function register()
    {
        if (strtoupper($this->request->getMethod()) === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $password_confirm = $this->request->getPost('password_confirm');

            if ($password !== $password_confirm) {
                return redirect()->back()->with('error', 'Password tidak cocok!');
            }

            $existingUser = $this->userModel->getUserByUsername($username);
            if ($existingUser) {
                return redirect()->back()->with('error', 'Username sudah terdaftar!');
            }

            $data = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => 'user',
                'is_active' => 1,
            ];

            if ($this->userModel->insert($data)) {
                return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
            } else {
                return redirect()->back()->with('error', 'Registrasi gagal!');
            }
        }

        return view('auth/register');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda berhasil logout!');
    }
}
