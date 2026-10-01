<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CaptureController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        // Ambil agenda user untuk pilihan dropdown tautan kegiatan
        $schedules = $db->table('schedules')
            ->where('user_id', $userId)
            ->orderBy('start_time', 'ASC')
            ->get()
            ->getResultArray();

        return view('capture/index', [
            'schedules' => $schedules,
        ]);
    }

    public function publish()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        $uploadPath = FCPATH . 'uploads/moments/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Helper fungsi simpan Base64 dari jepretan kamera real-time
        $saveBase64 = function ($base64Data, $path) {
            if (empty($base64Data) || !str_starts_with($base64Data, 'data:image')) {
                return null;
            }
            $parts = explode(',', $base64Data);
            $decoded = base64_decode($parts[1]);
            $fileName = uniqid('cam_') . '.jpg';
            file_put_contents($path . $fileName, $decoded);
            return $fileName;
        };

        // 1. Foto Utama (Kamera / File Galeri)
        $mainCaptured = $this->request->getPost('main_captured');
        $mainFile     = $this->request->getFile('main_image');
        $mainName     = null;

        if (!empty($mainCaptured)) {
            $mainName = $saveBase64($mainCaptured, $uploadPath);
        } elseif ($mainFile && $mainFile->isValid() && !$mainFile->hasMoved()) {
            $mainName = $mainFile->getRandomName();
            $mainFile->move($uploadPath, $mainName);
        }

        // 2. Foto Inset (Kamera / File Galeri)
        $insetCaptured = $this->request->getPost('inset_captured');
        $insetFile     = $this->request->getFile('inset_image');
        $insetName     = null;

        if (!empty($insetCaptured)) {
            $insetName = $saveBase64($insetCaptured, $uploadPath);
        } elseif ($insetFile && $insetFile->isValid() && !$insetFile->hasMoved()) {
            $insetName = $insetFile->getRandomName();
            $insetFile->move($uploadPath, $insetName);
        }

        // 3. Tanggal & Agenda Kegiatan
        $scheduleId     = $this->request->getPost('schedule_id');
        $newAgendaTitle = trim((string) $this->request->getPost('new_agenda_title'));
        $agendaTime     = $this->request->getPost('agenda_time') ?: date('Y-m-d H:i:s');
        $alertTime      = $this->request->getPost('alert_time') ?? '15_min';
        $syncCalendar   = $this->request->getPost('sync_calendar') ? 1 : 0;

        if (!empty($newAgendaTitle)) {
            $db->table('schedules')->insert([
                'user_id'       => $userId,
                'title'         => $newAgendaTitle,
                'category'      => 'Akademik',
                'status'        => 'To Do',
                'start_time'    => $agendaTime,
                'end_time'      => date('Y-m-d H:i:s', strtotime($agendaTime . ' +2 hours')),
                'alert_time'    => $alertTime,
                'sync_calendar' => $syncCalendar,
            ]);
            $scheduleId = $db->insertID();
        }

        // 4. Simpan ke moments
        $db->table('moments')->insert([
            'user_id'     => $userId,
            'main_image'  => $mainName ?? 'default.jpg',
            'inset_image' => $insetName,
            'caption'     => $this->request->getPost('caption'),
            'mood'        => $this->request->getPost('mood') ?? 'Happy',
            'schedule_id' => $scheduleId ?: null,
            'visibility'  => $this->request->getPost('visibility') ?? 'circle',
            'created_at'  => $agendaTime,
        ]);

        if ($syncCalendar && $scheduleId) {
            return redirect()->to('/dashboard')->with('download_ics', $scheduleId)->with('message', 'Daily log berhasil disimpan! ✨');
        }

        return redirect()->to('/dashboard')->with('message', 'Daily log berhasil di-publish! ✨');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        // Ambil data log milik user yang sedang login
        $moment = $db->table('moments')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$moment) {
            return redirect()->to('/dashboard')->with('error', 'Log tidak ditemukan atau bukan milikmu.');
        }

        $schedules = $db->table('schedules')
            ->where('user_id', $userId)
            ->orderBy('start_time', 'ASC')
            ->get()
            ->getResultArray();

        return view('capture/edit', [
            'moment'    => $moment,
            'schedules' => $schedules,
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? 1;

        $moment = $db->table('moments')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$moment) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $uploadPath = FCPATH . 'uploads/moments/';
        $mainName = $moment['main_image'];
        $insetName = $moment['inset_image'];

        // Helper simpan Base64 foto
        $saveBase64 = function ($base64Data, $path) {
            if (empty($base64Data) || !str_starts_with($base64Data, 'data:image')) {
                return null;
            }
            $parts = explode(',', $base64Data);
            $decoded = base64_decode($parts[1]);
            $fileName = uniqid('cam_') . '.jpg';
            file_put_contents($path . $fileName, $decoded);
            return $fileName;
        };

        // 1. Cek pergantian Foto Utama
        $mainCaptured = $this->request->getPost('main_captured');
        $mainFile     = $this->request->getFile('main_image');

        if (!empty($mainCaptured)) {
            if (!empty($moment['main_image']) && file_exists($uploadPath . $moment['main_image'])) {
                @unlink($uploadPath . $moment['main_image']);
            }
            $mainName = $saveBase64($mainCaptured, $uploadPath);
        } elseif ($mainFile && $mainFile->isValid() && !$mainFile->hasMoved()) {
            if (!empty($moment['main_image']) && file_exists($uploadPath . $moment['main_image'])) {
                @unlink($uploadPath . $moment['main_image']);
            }
            $mainName = $mainFile->getRandomName();
            $mainFile->move($uploadPath, $mainName);
        }

        // 2. Cek pergantian Foto Inset
        $insetCaptured = $this->request->getPost('inset_captured');
        $insetFile     = $this->request->getFile('inset_image');

        if (!empty($insetCaptured)) {
            if (!empty($moment['inset_image']) && file_exists($uploadPath . $moment['inset_image'])) {
                @unlink($uploadPath . $moment['inset_image']);
            }
            $insetName = $saveBase64($insetCaptured, $uploadPath);
        } elseif ($insetFile && $insetFile->isValid() && !$insetFile->hasMoved()) {
            if (!empty($moment['inset_image']) && file_exists($uploadPath . $moment['inset_image'])) {
                @unlink($uploadPath . $moment['inset_image']);
            }
            $insetName = $insetFile->getRandomName();
            $insetFile->move($uploadPath, $insetName);
        }

        // 3. Ambil input tanggal yang diedit
        $momentDate = $this->request->getPost('created_at') ?: $moment['created_at'];

        // 4. Update data ke database
        $db->table('moments')->where('id', $id)->update([
            'main_image'  => $mainName,
            'inset_image' => $insetName,
            'caption'     => $this->request->getPost('caption'),
            'mood'        => $this->request->getPost('mood') ?? $moment['mood'],
            'visibility'  => $this->request->getPost('visibility') ?? $moment['visibility'],
            'created_at'  => $momentDate,
        ]);

        return redirect()->to('/dashboard?tab=my_log')->with('message', 'Daily log berhasil diperbarui! ✨');
    }

    public function downloadIcs($scheduleId)
    {
        helper('calendar');
        $db = \Config\Database::connect();
        $agenda = $db->table('schedules')->where('id', $scheduleId)->get()->getRowArray();

        if (!$agenda) {
            return redirect()->to('/dashboard');
        }

        $icsContent = generate_ics(
            $agenda['title'],
            $agenda['start_time'],
            $agenda['end_time'],
            'Pengingat agenda dari dailee.com'
        );

        return $this->response
            ->setHeader('Content-Type', 'text/calendar; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="agenda-' . $scheduleId . '.ics"')
            ->setBody($icsContent);
    }
}