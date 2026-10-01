<?php

namespace App\Controllers;

use App\Libraries\NotificationService;

class FriendController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        // 1. Ambil Permintaan Pertemanan yang MASUK ke akun ini (status: pending)
        $requests = $db->table('friendships')
            ->select('friendships.id as request_id, users.id as sender_id, users.username, users.nama_lengkap, users.avatar')
            ->join('users', 'users.id = friendships.user_id')
            ->where('friendships.friend_id', $userId)
            ->where('friendships.status', 'pending')
            ->get()
            ->getResultArray();

        // 2. Ambil Daftar Teman yang SUDAH MUTUAL (status: accepted)
        $friends = $db->table('friendships')
            ->select('users.id, users.username, users.nama_lengkap, users.avatar')
            ->join('users', 'users.id = friendships.friend_id')
            ->where('friendships.user_id', $userId)
            ->where('friendships.status', 'accepted')
            ->get()
            ->getResultArray();

        $discover = $db->table('users')
            ->select('id, username, nama_lengkap, name, avatar')
            ->where('id !=', $userId)
            ->orderBy('username', 'ASC')
            ->limit(30)
            ->get()
            ->getResultArray();

        foreach ($discover as &$person) {
            $personId = (int) $person['id'];
            $person['friend_status'] = $db->table('friendships')
                ->where('user_id', $userId)->where('friend_id', $personId)
                ->get()->getRowArray()['status'] ?? null;
            $person['incoming_status'] = $db->table('friendships')
                ->where('user_id', $personId)->where('friend_id', $userId)
                ->get()->getRowArray()['status'] ?? null;
            $person['is_following'] = $db->tableExists('follows') && $db->table('follows')
                ->where('follower_id', $userId)->where('following_id', $personId)
                ->countAllResults() > 0;
        }
        unset($person);

        return view('friends/index', [
            'requests' => $requests,
            'friends'  => $friends,
            'discover' => $discover,
        ]);
    }

    public function add()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;
        $targetUsername = trim((string) $this->request->getPost('username'));

        if (empty($targetUsername)) {
            return redirect()->back()->with('error', 'Masukkan username yang valid!');
        }

        // Cari user target
        $targetUser = $db->table('users')->where('username', $targetUsername)->get()->getRowArray();

        if (!$targetUser) {
            return redirect()->back()->with('error', 'User @' . $targetUsername . ' tidak ditemukan!');
        }

        if ($targetUser['id'] == $userId) {
            return redirect()->back()->with('error', 'Tidak bisa menambahkan akun sendiri!');
        }

        // Cek apakah sudah pernah request atau sudah berteman
        $existing = $db->table('friendships')
            ->where('user_id', $userId)
            ->where('friend_id', $targetUser['id'])
            ->get()
            ->getRowArray();

        if ($existing) {
            if ($existing['status'] === 'accepted') {
                return redirect()->back()->with('error', 'Kamu sudah berteman dengan @' . $targetUsername . '!');
            }
            if ($existing['status'] === 'rejected') {
                $db->table('friendships')->where('id', $existing['id'])->update([
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                return redirect()->back()->with('success', 'Permintaan pertemanan dikirim ulang ke @' . $targetUsername . '.');
            }
            return redirect()->back()->with('error', 'Permintaan pertemanan sudah dikirim sebelumnya!');
        }

        // Cek apakah user target sebenarnya sudah duluan ngirim request ke kita
        $incoming = $db->table('friendships')
            ->where('user_id', $targetUser['id'])
            ->where('friend_id', $userId)
            ->where('status', 'pending')
            ->get()
            ->getRowArray();

        if ($incoming) {
            // Langsung otomatis accept kalau dua-duanya saling add
            return $this->accept($incoming['id']);
        }

        // Kirim permintaan pertemanan dengan status PENDING
        $db->table('friendships')->insert([
            'user_id'   => $userId,
            'friend_id' => $targetUser['id'],
            'status'    => 'pending',
        ]);
        (new NotificationService($db))->create((int) $targetUser['id'], (int) $userId, 'friend_request');

        return redirect()->back()->with('success', 'Permintaan pertemanan terkirim ke @' . $targetUsername . '. Tunggu konfirmasi! ✨');
    }

    public function accept($requestId)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        // Ambil data request
        $request = $db->table('friendships')
            ->where('id', $requestId)
            ->where('friend_id', $userId)
            ->where('status', 'pending')
            ->get()
            ->getRowArray();

        if ($request) {
            // Update request menjadi accepted
            $db->table('friendships')->where('id', $requestId)->update(['status' => 'accepted']);

            // Buat relasi timbal-balik agar mutual
            $mutualExist = $db->table('friendships')
                ->where('user_id', $userId)
                ->where('friend_id', $request['user_id'])
                ->countAllResults();

            if ($mutualExist == 0) {
                $db->table('friendships')->insert([
                    'user_id'   => $userId,
                    'friend_id' => $request['user_id'],
                    'status'    => 'accepted',
                ]);
            } else {
                $db->table('friendships')
                    ->where('user_id', $userId)
                    ->where('friend_id', $request['user_id'])
                    ->update(['status' => 'accepted']);
            }

            (new NotificationService($db))->create((int) $request['user_id'], (int) $userId, 'friend_accepted');
            return redirect()->back()->with('success', 'Permintaan pertemanan diterima! Sekarang kalian mutualan ✨');
        }

        return redirect()->back()->with('error', 'Permintaan pertemanan tidak valid.');
    }

    public function reject($requestId)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        // Hapus request pertemanan dari database
        $db->table('friendships')
            ->where('id', $requestId)
            ->where('friend_id', $userId)
            ->where('status', 'pending')
            ->delete();

        return redirect()->back()->with('success', 'Permintaan pertemanan ditolak.');
    }

    public function toggleFollow($targetId)
    {
        $db = \Config\Database::connect();
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $targetId = (int) $targetId;

        if ($targetId === $userId || !$db->table('users')->where('id', $targetId)->countAllResults()) {
            return redirect()->back()->with('error', 'Akun yang dipilih tidak valid.');
        }
        if (!$db->tableExists('follows')) {
            return redirect()->back()->with('error', 'Fitur follow belum aktif. Jalankan migrasi database terlebih dahulu.');
        }

        $follow = $db->table('follows')->where('follower_id', $userId)
            ->where('following_id', $targetId)->get()->getRowArray();

        if ($follow) {
            $db->table('follows')->where('id', $follow['id'])->delete();
            $message = 'Kamu berhenti mengikuti akun ini.';
        } else {
            $db->table('follows')->insert([
                'follower_id' => $userId,
                'following_id' => $targetId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            (new NotificationService($db))->create($targetId, $userId, 'follow');
            $message = 'Kamu sekarang mengikuti akun ini.';
        }

        return redirect()->back()->with('message', $message);
    }
}
