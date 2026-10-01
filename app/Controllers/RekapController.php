<?php

namespace App\Controllers;

class RekapController extends BaseController
{
    public function index()
    {
        $month = (string) ($this->request->getGet('month') ?? date('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month) || !checkdate((int) substr($month, 5, 2), 1, (int) substr($month, 0, 4))) {
            $month = date('Y-m');
        }

        return redirect()->to('/memories?tab=recaps&month=' . $month);
    }
}
