<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class IndoBertApiService
{
    /**
     * Mengirim teks ke FastAPI dan mengembalikan
     * hasil analisis dalam bentuk array.
     */
    public function analyzeText(string $text): ?array
    {
        $text = trim($text);

        if ($text === '') {
            Log::warning(
                'Analisis NLP dibatalkan karena teks kosong.'
            );

            return null;
        }

        $baseUrl = rtrim(
            (string) config('services.indobert.url'),
            '/'
        );

        $timeout = (int) config(
            'services.indobert.timeout',
            30
        );

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->connectTimeout(5)
                ->timeout($timeout)
                ->post(
                    $baseUrl . '/predict',
                    [
                        'text' => $text,
                    ]
                );

            if (!$response->successful()) {
                Log::error(
                    'FastAPI IndoBERT mengembalikan respons gagal.',
                    [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]
                );

                return null;
            }

            $result = $response->json();

            if (
                !is_array($result)
                || !isset(
                    $result['kategori'],
                    $result['confidence'],
                    $result['probabilitas_perundungan'],
                    $result['probabilitas']
                )
            ) {
                Log::error(
                    'Struktur respons FastAPI tidak sesuai.',
                    [
                        'response' => $result,
                    ]
                );

                return null;
            }

            return $result;
        } catch (Throwable $exception) {
            Log::error(
                'Gagal terhubung ke FastAPI IndoBERT.',
                [
                    'message' => $exception->getMessage(),
                    'url' => $baseUrl . '/predict',
                ]
            );

            return null;
        }
    }
}
