<?php

namespace App\Controllers;

class MemoriesController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $tab = $this->request->getGet('tab') ?? 'memories';
        if (!in_array($tab, ['memories', 'calendar', 'recaps'], true)) {
            $tab = 'memories';
        }

        $monthInput = (string) ($this->request->getGet('month') ?? date('Y-m'));
        $month = preg_match('/^\d{4}-\d{2}$/', $monthInput) && checkdate((int) substr($monthInput, 5, 2), 1, (int) substr($monthInput, 0, 4))
            ? $monthInput
            : date('Y-m');
        $monthStart = $month . '-01';
        $nextMonth = date('Y-m-01', strtotime($monthStart . ' +1 month'));
        $daysInMonth = (int) date('t', strtotime($monthStart));
        $firstWeekday = (int) date('w', strtotime($monthStart));

        $requestedDate = (string) ($this->request->getGet('date') ?? '');
        $isValidDate = preg_match('/^\d{4}-\d{2}-\d{2}$/', $requestedDate)
            && substr($requestedDate, 0, 7) === $month
            && checkdate((int) substr($requestedDate, 5, 2), (int) substr($requestedDate, 8, 2), (int) substr($requestedDate, 0, 4));
        $selectedDate = $isValidDate ? $requestedDate : (date('Y-m') === $month ? date('Y-m-d') : $monthStart);

        $monthMoments = $db->table('moments')
            ->select('moments.*, schedules.title as agenda_title')
            ->join('schedules', 'schedules.id = moments.schedule_id', 'left')
            ->where('moments.user_id', $userId)
            ->where('moments.created_at >=', $monthStart . ' 00:00:00')
            ->where('moments.created_at <', $nextMonth . ' 00:00:00')
            ->orderBy('moments.created_at', 'DESC')
            ->get()->getResultArray();

        $monthAgendas = $db->table('schedules')
            ->where('user_id', $userId)
            ->where('start_time >=', $monthStart . ' 00:00:00')
            ->where('start_time <', $nextMonth . ' 00:00:00')
            ->orderBy('start_time', 'ASC')
            ->get()->getResultArray();

        $momentsByDay = [];
        $activeDays = [];
        foreach ($monthMoments as $moment) {
            if (empty($moment['created_at'])) {
                continue;
            }
            $day = substr($moment['created_at'], 0, 10);
            $momentsByDay[$day][] = $moment;
            $activeDays[$day] = true;
        }

        $agendasByDay = [];
        foreach ($monthAgendas as $agenda) {
            if (!empty($agenda['start_time'])) {
                $agendasByDay[substr($agenda['start_time'], 0, 10)][] = $agenda;
            }
        }

        $selectedMoments = $momentsByDay[$selectedDate] ?? [];
        $selectedAgendas = $agendasByDay[$selectedDate] ?? [];
        $doneAgendas = count(array_filter($monthAgendas, static fn ($agenda) => strtolower((string) $agenda['status']) === 'done'));
        $monthNames = lang('Web.month_names');
        $weekdays = lang('Web.weekdays');
        $monthName = is_array($monthNames) ? $monthNames[(int) date('n', strtotime($monthStart)) - 1] : date('F', strtotime($monthStart));

        // 1. Data Foto Kronologis untuk Tab Memories
        $memories = $db->table('moments')
            ->select('moments.*, schedules.title as agenda_title')
            ->join('schedules', 'schedules.id = moments.schedule_id', 'left')
            ->where('moments.user_id', $userId)
            ->orderBy('moments.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $previousMonth = date('Y-m', strtotime($monthStart . ' -1 month'));
        $nextMonthLabel = date('Y-m', strtotime($monthStart . ' +1 month'));

        return view('memories/index', [
            'currentTab'      => $tab,
            'memories'        => $memories,
            'monthMoments' => $monthMoments,
            'monthAgendas' => $monthAgendas,
            'momentsByDay' => $momentsByDay,
            'agendasByDay' => $agendasByDay,
            'selectedMoments' => $selectedMoments,
            'selectedAgendas' => $selectedAgendas,
            'selectedDate' => $selectedDate,
            'month' => $month,
            'monthName' => $monthName,
            'year' => (int) substr($month, 0, 4),
            'daysInMonth' => $daysInMonth,
            'firstWeekday' => $firstWeekday,
            'weekdays' => is_array($weekdays) ? $weekdays : [],
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonthLabel,
            'activeDays' => count($activeDays),
            'doneAgendas' => $doneAgendas,
            'totalAgendas' => count($monthAgendas),
        ]);
    }

    public function uploadToAgenda()
    {
        $db = \Config\Database::connect();
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $scheduleId = (int) $this->request->getPost('schedule_id');
        $agenda = $db->table('schedules')->where('id', $scheduleId)->where('user_id', $userId)->get()->getRowArray();
        if (!$agenda) {
            return redirect()->to('/memories?tab=calendar')->with('error', 'Agenda tidak ditemukan.');
        }

        $mainFile = $this->request->getFile('main_image');
        $uploadPath = FCPATH . 'uploads/moments/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if ($mainFile && $mainFile->isValid() && !$mainFile->hasMoved()
            && in_array(strtolower($mainFile->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $mainName = $mainFile->getRandomName();
            $mainFile->move($uploadPath, $mainName);

            $db->table('moments')->insert([
                'user_id'     => $userId,
                'main_image'  => $mainName,
                'caption'     => $this->request->getPost('caption') ?? 'Dokumentasi agenda',
                'mood'        => 'Productive',
                'schedule_id' => $scheduleId,
                'visibility'  => 'me',
                'created_at'  => date('Y-m-d H:i:s')
            ]);
            return redirect()->to('/memories?tab=calendar&month=' . date('Y-m', strtotime($agenda['start_time'])) . '&date=' . date('Y-m-d', strtotime($agenda['start_time'])))->with('message', 'Foto berhasil ditambahkan ke arsip tanggal ini.');
        }

        return redirect()->to('/memories?tab=calendar')->with('error', 'Pilih foto JPG, PNG, atau WebP yang valid.');
    }
}
