<?php

namespace App\Controllers;

use App\Libraries\NotificationService;

class NotificationsController extends BaseController
{
    public function feed()
    {
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $summary = (new NotificationService())->summary($userId);

        foreach ($summary['items'] as &$item) {
            $item['title'] = lang('Web.notification_' . $item['type']);
            $item['time_label'] = $item['created_at'] ? date('d M H:i', strtotime($item['created_at'])) : '';
        }
        unset($item);

        return $this->response->setJSON(['status' => 'success'] + $summary);
    }

    public function markRead(int $id)
    {
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $db = \Config\Database::connect();
        $db->table('notifications')->where('id', $id)->where('recipient_id', $userId)->update(['is_read' => 1]);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function markAllRead()
    {
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $db = \Config\Database::connect();
        $db->table('notifications')->where('recipient_id', $userId)->where('is_read', 0)->update(['is_read' => 1]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
