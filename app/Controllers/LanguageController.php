<?php

namespace App\Controllers;

class LanguageController extends BaseController
{
    public function switch($locale = 'id')
    {
        $session = session();

        // Validasi bahasa yang diizinkan (id atau en)
        if (in_array($locale, ['id', 'en'])) {
            $session->set('lang', $locale);
        }

        // Kembali ke halaman sebelumnya
        return redirect()->back();
    }
}