<?php

namespace App\Controllers;

use App\Libraries\GeminiClient;

class ChatController extends BaseController
{
    public function index()
    {
        return view('chat/index', [
            'initialPrompt' => $this->request->getGet('prompt'),
        ]);
    }

    public function sendMessage()
    {
        $message = trim((string) $this->request->getPost('message'));
        if ($message === '') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tulis pertanyaan terlebih dahulu.'])->setStatusCode(422);
        }
        if (mb_strlen($message) > 4000) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesan maksimal 4.000 karakter.'])->setStatusCode(422);
        }

        $systemPrompt = 'Kamu adalah Dailee AI, asisten produktivitas, manajemen informasi pribadi, dan kehidupan akademis mahasiswa. Jawab dengan ramah, santai, suportif, jelas, terstruktur, dan aplikatif. Jangan mengarang informasi yang tidak diketahui.';
        $result = (new GeminiClient())->generate($message, $systemPrompt);

        if (!$result['ok']) {
            return $this->response->setJSON(['status' => 'error', 'message' => $result['message']])->setStatusCode(502);
        }

        return $this->response->setJSON(['status' => 'success', 'reply' => $result['text']]);
    }
}
