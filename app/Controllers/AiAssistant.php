<?php

namespace App\Controllers;

use App\Libraries\GeminiClient;

class AiAssistant extends BaseController
{
    public function chat()
    {
        $message = trim((string) $this->request->getPost('pesan'));
        if ($message === '' || mb_strlen($message) > 4000) {
            return $this->response->setJSON(['reply' => 'Pertanyaan harus berisi 1 sampai 4.000 karakter.'])->setStatusCode(422);
        }

        $result = (new GeminiClient())->generate(
            $message,
            'Kamu adalah Dailee AI, asisten produktivitas, agenda kuliah, dan pengelolaan informasi mahasiswa. Jawab ramah dan to the point.'
        );

        if (!$result['ok']) {
            return $this->response->setJSON(['reply' => $result['message']])->setStatusCode(502);
        }

        return $this->response->setJSON(['reply' => nl2br(esc($result['text']))]);
    }
}
