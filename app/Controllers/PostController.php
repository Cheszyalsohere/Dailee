<?php

namespace App\Controllers;

use App\Libraries\NotificationService;
use App\Controllers\BaseController;

class PostController extends BaseController
{
    // 1. HANDLER REACT / LIKE
    public function react()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Login dulu dong!'])->setStatusCode(401);
        }

        $userId = $session->get('user_id');
        $postId = (int) $this->request->getPost('post_id');
        $type   = $this->request->getPost('type') ?? 'like'; // default 'like'

        if ($postId < 1 || !in_array($type, ['like', 'love', 'laugh', 'wow', 'sad'], true)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Momen atau reaksi tidak valid.'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();
        $moment = $db->table('moments')->select('user_id')->where('id', $postId)->get()->getRowArray();
        if (!$moment) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Momen tidak ditemukan.'])->setStatusCode(404);
        }
        $builder = $db->table('post_reactions');

        // Cek apakah user udah pernah react di post ini
        $existing = $builder->where(['post_id' => $postId, 'user_id' => $userId])->get()->getRow();

        if ($existing) {
            if ($existing->type === $type) {
                // Kalau tipe reaksinya sama, dianggap UNLIKE (Hapus)
                $builder->where('id', $existing->id)->delete();
                $action = 'unliked';
            } else {
                // Kalau beda tipe (misal dari 'like' ganti ke 'love'), UPDATE tipe reaksinya
                $builder->where('id', $existing->id)->update(['type' => $type]);
                $action = 'updated';
            }
        } else {
            // Kalau belum pernah, INSERT baru
            $builder->insert([
                'post_id'    => $postId,
                'user_id'    => $userId,
                'type'       => $type,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $action = 'liked';
        }

        // Hitung total reaksi terbaru di post ini
        $total = $db->table('post_reactions')->where('post_id', $postId)->countAllResults();
        if ($action !== 'unliked') {
            (new NotificationService($db))->create((int) $moment['user_id'], (int) $userId, 'reaction', $postId);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'action' => $action,
            'total'  => $total
        ]);
    }

    // 2. HANDLER KOMEN
    public function comment()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Login dulu bos!'])->setStatusCode(401);
        }

        $userId  = $session->get('user_id');
        $postId  = (int) $this->request->getPost('post_id');
        $comment = trim((string) $this->request->getPost('comment'));

        if ($postId < 1 || $comment === '' || mb_strlen($comment) > 1000) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Komentar harus berisi 1 sampai 1.000 karakter.'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();
        $moment = $db->table('moments')->select('user_id')->where('id', $postId)->get()->getRowArray();
        if (!$moment) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Momen tidak ditemukan.'])->setStatusCode(404);
        }
        $db->table('post_comments')->insert([
            'post_id'    => $postId,
            'user_id'    => $userId,
            'comment'    => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $commentId = (int) $db->insertID();
        (new NotificationService($db))->create((int) $moment['user_id'], (int) $userId, 'comment', $postId, $comment);
        $author = $db->table('users')->select('username, nama_lengkap')->where('id', $userId)->get()->getRowArray();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Komentar berhasil dikirim!',
            'comment' => [
                'id' => $commentId,
                'username' => $author['username'] ?? 'user',
                'name' => $author['nama_lengkap'] ?: ($author['username'] ?? 'user'),
                'text' => $comment,
                'created_at' => date('d M H:i'),
            ],
        ]);
    }

    // 3. HANDLER SHARE
    public function share()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Login dulu!'])->setStatusCode(401);
        }

        $userId = $session->get('user_id');
        $postId = (int) $this->request->getPost('post_id');

        $db = \Config\Database::connect();
        $moment = $db->table('moments')->select('user_id')->where('id', $postId)->get()->getRowArray();
        if (!$moment) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Momen tidak ditemukan.'])->setStatusCode(404);
        }
        $db->table('post_shares')->insert([
            'post_id'    => $postId,
            'user_id'    => $userId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        (new NotificationService($db))->create((int) $moment['user_id'], (int) $userId, 'share', $postId);

        $totalShares = $db->table('post_shares')->where('post_id', $postId)->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'shares' => $totalShares
        ]);
    }
}
