<?php

namespace App\Controllers;

use App\Models\ScheduleModel;

class ScheduleController extends BaseController
{
    public function store()
    {
        $scheduleModel = new ScheduleModel();
        
        // Ambil data dari form modal
        $data = [
            'user_id'    => session()->get('id') ?? 1, // Fallback ke 1 kalau belum ada sistem login full
            'title'      => $this->request->getPost('title'),
            'category'   => $this->request->getPost('category'),
            'status'     => 'To Do', // Default status awal
            'start_time' => $this->request->getPost('start_time'),
            'end_time'   => $this->request->getPost('end_time'),
        ];

        $scheduleModel->save($data);

        // Redirect balik ke dashboard dengan pesan sukses
        return redirect()->to('/dashboard')->with('message', 'Jadwal berhasil ditambahkan! ✨');
    }

    public function markDone($id)
    {
        $scheduleModel = new ScheduleModel();
        
        // Update status jadwal jadi 'Done'
        $scheduleModel->update($id, ['status' => 'Done']);

        return redirect()->to('/dashboard')->with('message', 'Mantap! Satu tugas selesai. 🔥');
    }

    public function delete($id)
    {
        $scheduleModel = new ScheduleModel();
        $scheduleModel->delete($id);

        return redirect()->to('/dashboard')->with('message', 'Jadwal dihapus.');
    }
}