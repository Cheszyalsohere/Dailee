<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

class NotificationService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    public function create(int $recipientId, int $actorId, string $type, ?int $entityId = null, ?string $body = null): void
    {
        if ($recipientId < 1 || $actorId < 1 || $recipientId === $actorId || !$this->db->tableExists('notifications')) {
            return;
        }

        $this->db->table('notifications')->insert([
            'recipient_id' => $recipientId,
            'actor_id'     => $actorId,
            'type'         => $type,
            'entity_id'    => $entityId,
            'body'         => $body === null ? null : mb_substr($body, 0, 255),
            'is_read'      => 0,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    public function summary(int $userId, int $limit = 8): array
    {
        if ($userId < 1 || !$this->db->tableExists('notifications')) {
            return ['unread' => 0, 'unread_messages' => 0, 'friend_requests' => 0, 'due_tasks' => 0, 'items' => []];
        }

        $items = $this->db->table('notifications n')
            ->select('n.id, n.type, n.entity_id, n.body, n.is_read, n.created_at, u.username, u.nama_lengkap, u.name')
            ->join('users u', 'u.id = n.actor_id', 'left')
            ->where('n.recipient_id', $userId)
            ->orderBy('n.created_at', 'DESC')
            ->orderBy('n.id', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();

        $unreadMessages = $this->db->table('notifications')
            ->where('recipient_id', $userId)->where('type', 'message')->where('is_read', 0)
            ->countAllResults();
        $unread = $this->db->table('notifications')
            ->where('recipient_id', $userId)->where('is_read', 0)
            ->countAllResults();
        $friendRequests = $this->db->table('friendships')
            ->where('friend_id', $userId)->where('status', 'pending')
            ->countAllResults();
        $dueTasks = $this->db->table('schedules')
            ->where('user_id', $userId)->where('status !=', 'Done')
            ->where('start_time >=', date('Y-m-d H:i:s'))
            ->where('start_time <=', date('Y-m-d H:i:s', strtotime('+24 hours')))
            ->countAllResults();

        foreach ($items as &$item) {
            $item['actor_name'] = $item['nama_lengkap'] ?: ($item['name'] ?: ('@' . $item['username']));
            $item['href'] = match ($item['type']) {
                'friend_request', 'friend_accepted' => site_url('friends'),
                'message' => site_url('inbox'),
                'follow' => site_url('profile/' . rawurlencode((string) $item['username'])),
                default => site_url('dashboard') . '#moment-' . (int) ($item['entity_id'] ?? 0),
            };
            $item['is_read'] = (int) $item['is_read'];
        }
        unset($item);

        return [
            'unread' => $unread,
            'unread_messages' => $unreadMessages,
            'friend_requests' => $friendRequests,
            'due_tasks' => $dueTasks,
            'items' => $items,
        ];
    }
}
