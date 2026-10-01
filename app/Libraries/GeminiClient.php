<?php

namespace App\Libraries;

use Throwable;

class GeminiClient
{
    public function generate(string $prompt, string $systemInstruction): array
    {
        $apiKey = trim((string) env('GEMINI_API_KEY', ''));
        if ($apiKey === '') {
            return ['ok' => false, 'message' => 'GEMINI_API_KEY belum diisi di file .env.'];
        }

        $model = trim((string) env('GEMINI_MODEL', 'gemini-3.8-flash'));
        if (!preg_match('/^[A-Za-z0-9._-]+$/', $model)) {
            $model = 'gemini-3.8-flash';
        }

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent';
        $payload = [
            'systemInstruction' => ['parts' => [['text' => $systemInstruction]]],
            'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
            'generationConfig' => ['temperature' => 0.7, 'maxOutputTokens' => 900],
        ];

        try {
            $client = \Config\Services::curlrequest();
            for ($attempt = 0; ; $attempt++) {
                $response = $client->post($endpoint, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'x-goog-api-key' => $apiKey,
                    ],
                    'json' => $payload,
                    // Gemini can take longer on a cold start; allow generation to finish.
                    // Keep this below PHP's configured request execution limit.
                    'timeout' => 45,
                    'connect_timeout' => 10,
                    'http_errors' => false,
                    'verify' => true,
                ]);

                // 503 is a temporary Gemini service/overload response; retry once.
                if ($response->getStatusCode() !== 503 || $attempt >= 1) {
                    break;
                }

                usleep(800000);
            }

            $status = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);
            $answer = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($status >= 200 && $status < 300 && is_string($answer) && $answer !== '') {
                return ['ok' => true, 'text' => $answer];
            }

            $message = match ($status) {
                400, 401, 403 => 'Kunci atau akses Gemini ditolak. Periksa GEMINI_API_KEY dan izin Gemini API di Google AI Studio.',
                429 => 'Kuota Gemini sedang penuh. Coba lagi sebentar.',
                503 => 'Layanan Gemini sedang sibuk atau sementara tidak tersedia. Coba kirim ulang beberapa saat lagi.',
                0 => 'Gemini tidak memberi respons. Periksa koneksi server.',
                default => 'Gemini sedang mengalami kendala (HTTP ' . $status . '). Coba lagi nanti.',
            };

            if (isset($body['error']['message'])) {
                log_message('warning', 'Gemini API returned HTTP {status}: {message}', [
                    'status' => $status,
                    'message' => mb_substr((string) $body['error']['message'], 0, 300),
                ]);
            }

            return ['ok' => false, 'message' => $message];
        } catch (Throwable $exception) {
            log_message('error', 'Gemini request failed: {message}', ['message' => $exception->getMessage()]);

            if (str_contains(strtolower($exception->getMessage()), 'timed out')) {
                return ['ok' => false, 'message' => 'Gemini terlalu lama merespons. Coba kirim lagi; jika terus terjadi, periksa koneksi internet atau firewall.'];
            }

            if (str_contains(strtolower($exception->getMessage()), 'ssl') || str_contains(strtolower($exception->getMessage()), 'certificate')) {
                return ['ok' => false, 'message' => 'Koneksi aman ke Gemini gagal. Periksa sertifikat CA di konfigurasi PHP/XAMPP.'];
            }

            return ['ok' => false, 'message' => 'Tidak dapat terhubung ke Gemini. Periksa koneksi dan ekstensi OpenSSL/cURL PHP.'];
        }
    }
}
