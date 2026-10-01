<?php

namespace App\Controllers;

class TodoController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        // Ambil task pending (belum selesai) diurutkan dari deadline terdekat
        $pendingTasks = $db->table('schedules')
            ->where('user_id', $userId)
            ->where('status !=', 'Done')
            ->orderBy('start_time', 'ASC')
            ->get()
            ->getResultArray();

        // Ambil task yang sudah selesai (Done)
        $completedTasks = $db->table('schedules')
            ->where('user_id', $userId)
            ->where('status', 'Done')
            ->orderBy('start_time', 'DESC')
            ->get()
            ->getResultArray();

        return view('todo/index', [
            'pendingTasks'   => $pendingTasks,
            'completedTasks' => $completedTasks,
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        $title     = trim((string) $this->request->getPost('title'));
        $deadline  = $this->request->getPost('deadline');
        $category  = $this->request->getPost('category') ?: 'Akademik';
        $alertTime = $this->request->getPost('alert_time') ?: '1_hour';

        if (empty($title) || empty($deadline)) {
            return redirect()->back()->with('error', 'Judul tugas dan deadline wajib diisi!');
        }

        $db->table('schedules')->insert([
            'user_id'       => $userId,
            'title'         => $title,
            'category'      => $category,
            'status'        => 'To Do',
            'start_time'    => $deadline,
            'end_time'      => date('Y-m-d H:i:s', strtotime($deadline . ' +1 hour')),
            'alert_time'    => $alertTime,
            'sync_calendar' => 1,
        ]);

        return redirect()->to('/todo')->with('success', 'Task baru berhasil ditambahkan! 🚀');
    }

    public function toggle($id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        $task = $db->table('schedules')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$task) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Task tidak ditemukan.']);
        }

        $newStatus = ($task['status'] === 'Done') ? 'To Do' : 'Done';

        $db->table('schedules')->where('id', $id)->update([
            'status' => $newStatus
        ]);

        return $this->response->setJSON([
            'status'     => 'success',
            'new_status' => $newStatus
        ]);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        $db->table('schedules')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();

        return redirect()->to('/todo')->with('success', 'Task berhasil dihapus.');
    }
}