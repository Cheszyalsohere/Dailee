<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        $tab = $this->request->getGet('tab') ?? 'my_friends';

        // Teman langsung yang sudah menerima permintaan pertemanan.
        $friendRows = $db->table('friendships')
            ->select('friend_id')
            ->where('user_id', $userId)
            ->where('status', 'accepted')
            ->get()
            ->getResultArray();

        $friendIds = array_map('intval', array_column($friendRows, 'friend_id'));

        // Teman dari teman, tidak termasuk diri sendiri atau teman langsung.
       $fofIds = [];

if (!empty($friendIds)) {
    $fofRows = $db->table('friendships')
        ->select('friend_id')
        ->whereIn('user_id', $friendIds)
        ->where('status', 'accepted')
        ->get()
        ->getResultArray();

    $fofIds = array_values(array_diff(
                array_unique(array_map('intval', array_column($fofRows, 'friend_id'))),
        array_merge([$userId], $friendIds)
    ));
    }

        // Ambil postingan dan data pembuatnya.
        $builder = $db->table('moments')
            ->select('moments.*, users.username, users.nama_lengkap, users.avatar, schedules.title as agenda_title, schedules.start_time as agenda_time')
            ->join('users', 'users.id = moments.user_id', 'left')
            ->join('schedules', 'schedules.id = moments.schedule_id', 'left')
            ->orderBy('moments.created_at', 'DESC');

if ($tab === 'my_log') {
    $builder->where('moments.user_id', $userId);
} elseif ($tab === 'friends_of_friends') {
    if (empty($fofIds)) {
        $builder->where('moments.id', 0);
    } else {
        $builder->whereIn('moments.user_id', $fofIds)
                ->where('moments.visibility', 'circle');
    }
} else {
            // Default: postingan sendiri dan teman langsung.
    $builder->whereIn('moments.user_id', array_merge([$userId], $friendIds))
            ->groupStart()
                ->where('moments.visibility', 'circle')
                ->orWhere('moments.user_id', $userId)
            ->groupEnd();
}

        $logs = $builder->get()->getResultArray();

        foreach ($logs as &$log) {
            $log['total_comments'] = $db->table('post_comments')
                ->where('post_id', $log['id'])->countAllResults();
            $log['recent_comments'] = $db->table('post_comments')
                ->select('post_comments.id, post_comments.comment, post_comments.created_at, users.username, users.nama_lengkap')
                ->join('users', 'users.id = post_comments.user_id', 'left')
                ->where('post_comments.post_id', $log['id'])
                ->orderBy('post_comments.created_at', 'DESC')
                ->orderBy('post_comments.id', 'DESC')
                ->limit(3)
                ->get()->getResultArray();
        }
        unset($log);

        // 3. Jadwal terdekat untuk notifikasi lonceng
        $upcomingDeadlines = $db->table('schedules')
            ->where('user_id', $userId)
            ->where('status !=', 'Done')
            ->where('start_time >=', date('Y-m-d H:i:s'))
            ->orderBy('start_time', 'ASC')
            ->limit(3)
            ->get()
            ->getResultArray();

        // 4. Muat Helper Tips Produktivitas & Manajemen Informasi
        helper('tips');
        $allTips = get_productivity_tips();
        $initialTip = !empty($allTips) ? $allTips[array_rand($allTips)] : null;

        return view('dashboard/index', [
            'logs'              => $logs,
            'currentTab'        => $tab,
            'upcomingDeadlines' => $upcomingDeadlines,
            'allTips'           => $allTips,
            'initialTip'        => $initialTip,
        ]);
    }

    public function askSparksAi()
    {
        $factTitle = $this->request->getPost('fact_title');
        $factDesc  = $this->request->getPost('fact_desc');
        $question  = trim((string) $this->request->getPost('question'));

        if (empty($question)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Pertanyaan tidak boleh kosong!'
            ]);
        }

        // Basis respons cerdas seputar produktivitas, manajemen file, & IIP
        $answer = "Tentu! Terkait **{$factTitle}**:\n\n";

        $qLower = strtolower($question);
        if (str_contains($qLower, 'contoh') || str_contains($qLower, 'penerapan') || str_contains($qLower, 'gimana')) {
            $answer .= "Contoh penerapannya di perkuliahan: mulai terapkan pada satu mata kuliah dulu minggu ini. Pasang target tugas di agenda dailee, lalu amati apakah fokus belajarmu meningkat setelah menerapkan metode ini.";
        } elseif (str_contains($qLower, 'manfaat') || str_contains($qLower, 'kenapa') || str_contains($qLower, 'efek')) {
            $answer .= "Manfaat utamanya adalah mereduksi *cognitive overload* (kelelahan otak akibat informasi berantakan). Pengorganisasian yang terstruktur membuat waktu luangmu untuk istirahat jadi lebih berkualitas tanpa rasa cemas tertinggal tugas.";
        } elseif (str_contains($qLower, 'tips') || str_contains($qLower, 'trik') || str_contains($qLower, 'cara')) {
            $answer .= "Tips tambahannya: pasang alarm pengingat di fitur Calendar dailee 15 menit sebelum mulai, dan letakkan ponsel di luar jangkauan pandangan saat sesi fokus berlangsung.";
        } else {
            $answer .= "Pendekatan ini berfokus pada efisiensi pengelolaan energi mentalmu. Kamu bisa mengombinasikannya langsung dengan fitur sinkronisasi kalender di dailee agar ritme belajarmu tetap konsisten.";
        }

        return $this->response->setJSON([
            'status' => 'success',
            'answer' => nl2br(esc($answer))
        ]);
    }
}
