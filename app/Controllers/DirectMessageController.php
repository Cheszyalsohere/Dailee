<?php

namespace App\Controllers;

use App\Libraries\NotificationService;

class DirectMessageController extends BaseController
{
    public function index(?int $friendId = null)
    {
        $db = \Config\Database::connect();
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        if ($db->tableExists('notifications')) {
            $db->table('notifications')
                ->where('recipient_id', $userId)
                ->where('type', 'message')
                ->where('is_read', 0)
                ->update(['is_read' => 1]);
        }
        $friends = $db->table('friendships')
            ->select('users.id, users.username, users.nama_lengkap, users.name, users.avatar')
            ->join('users', 'users.id = friendships.friend_id')
            ->where('friendships.user_id', $userId)
            ->where('friendships.status', 'accepted')
            ->orderBy('users.username', 'ASC')
            ->get()->getResultArray();

        foreach ($friends as &$friend) {
            $last = $db->table('chats')
                ->groupStart()
                    ->groupStart()->where('sender_id', $userId)->where('receiver_id', $friend['id'])->groupEnd()
                    ->orGroupStart()->where('sender_id', $friend['id'])->where('receiver_id', $userId)->groupEnd()
                ->groupEnd()
                ->orderBy('created_at', 'DESC')->limit(1)->get()->getRowArray();
            $friend['last_message'] = $last['message'] ?? null;
            $friend['last_time'] = $last['created_at'] ?? null;
        }
        unset($friend);

        $activeFriend = null;
        $messages = [];
        if ($friendId !== null) {
            foreach ($friends as $friend) {
                if ((int) $friend['id'] === $friendId) {
                    $activeFriend = $friend;
                    break;
                }
            }
            if (!$activeFriend) {
                return redirect()->to('/inbox')->with('error', 'Direct message hanya tersedia untuk teman mutual.');
            }

            $messages = $db->table('chats')
                ->groupStart()
                    ->groupStart()->where('sender_id', $userId)->where('receiver_id', $friendId)->groupEnd()
                    ->orGroupStart()->where('sender_id', $friendId)->where('receiver_id', $userId)->groupEnd()
                ->groupEnd()
                ->orderBy('created_at', 'ASC')->orderBy('id', 'ASC')
                ->get()->getResultArray();
        }

        return view('inbox/index', [
            'friends' => $friends,
            'activeFriend' => $activeFriend,
            'messages' => $messages,
            'currentUserId' => $userId,
        ]);
    }

    public function send(int $friendId)
    {
        $db = \Config\Database::connect();
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $isFriend = $db->table('friendships')
            ->where('user_id', $userId)->where('friend_id', $friendId)
            ->where('status', 'accepted')->countAllResults() > 0;

        if (!$isFriend) {
            return redirect()->to('/inbox')->with('error', 'Kamu perlu menjadi teman mutual untuk mengirim pesan.');
        }

        $message = trim((string) $this->request->getPost('message'));
        if ($message === '' || mb_strlen($message) > 2000) {
            return redirect()->to('/inbox/' . $friendId)->with('error', 'Pesan harus berisi 1 sampai 2.000 karakter.');
        }

        $db->table('chats')->insert([
            'sender_id' => $userId,
            'receiver_id' => $friendId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        (new NotificationService($db))->create($friendId, $userId, 'message');

        return redirect()->to('/inbox/' . $friendId);
    }
}
