<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $username = (string) env('ADMIN_USERNAME', 'admin');

        if ($this->db->table('users')->where('username', $username)->countAllResults() > 0) {
            echo "Admin '{$username}' sudah ada, dilewati.\n";

            return;
        }

        $password = (string) env('ADMIN_PASSWORD', '');
        $generated = $password === '';
        if ($generated) {
            $password = bin2hex(random_bytes(6));
        }

        $this->db->table('users')->insert([
            'nama_lengkap' => 'Admin',
            'email'        => (string) env('ADMIN_EMAIL', 'admin@dailee.web.id'),
            'username'     => $username,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'role'         => 'admin',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        echo "Admin '{$username}' dibuat." . ($generated ? " Password sementara: {$password}\n" : "\n");
    }
}
