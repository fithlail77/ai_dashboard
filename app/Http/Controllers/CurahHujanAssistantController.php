<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\CurahHujanLlmService;
use App\Services\CurahHujanPbiService;
use Throwable;

class CurahHujanAssistantController extends Controller
{
    public function chat(
        Request $request,
        CurahHujanPbiService $curahHujanPbiService,
        CurahHujanLlmService $curahHujanLlmService,

    ): JsonResponse {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
        ]);

        $question = $validated['prompt'];

        // Ambil riwayat percakapan sebelumnya dari Session Laravel (Multi-Turn Memory)
        $history = $request->session()->get('pbi_chat_history', []);

        try {
            // 1. Minta 9Router membuatkan kueri DAX (dengan mempertimbangkan riwayat sebelumnya)
            $daxStep = $curahHujanLlmService->questionToDax($question, $history);
            $daxQuery = $daxStep['dax'];
            $selfHealed = false;
            $originalFailedDax = null;

            // 2. Jalankan kueri DAX ke Power BI REST API dengan Auto-Retry Self-Healing
            try {
                $rows = $curahHujanPbiService->executeDaxQuery($daxQuery);
            } catch (Throwable $e) {
                $isPbiEngineError = str_contains($e->getMessage(), 'DatasetExecuteQueriesError')
                    || str_contains($e->getMessage(), 'HTTP 400');

                // Jika error sintaks dari Power BI engine, lakukan Self-Healing otomatis (Maksimal 1x retry)
                if ($isPbiEngineError) {
                    $originalFailedDax = $daxQuery;
                    $fixedStep = $curahHujanLlmService->fixDaxQuery($question, $daxQuery, $e->getMessage());
                    $daxQuery = $fixedStep['dax'];

                    // Eksekusi ulang dengan kueri yang sudah diperbaiki
                    $rows = $curahHujanPbiService->executeDaxQuery($daxQuery);
                    $selfHealed = true;
                } else {
                    throw $e;
                }
            }

            if (empty($rows)) {
                return response()->json([
                    'status'      => 'success',
                    'reply'       => 'Data tidak ditemukan untuk pertanyaan tersebut pada dataset Power BI.',
                    'dax'         => $daxQuery,
                    'model'       => $daxStep['model'],
                    'self_healed' => $selfHealed,
                ]);
            }

            // 3. Minta 9Router merangkum hasil data JSON menjadi kalimat analitis / grafik
            $summaryStep = $curahHujanLlmService->summarizeResult($question, $rows, $history);

            // 4. Simpan giliran percakapan ke session history (maksimal 8 pesan / 4 roundtrips)
            $history[] = ['role' => 'user', 'content' => $question];
            $history[] = [
                'role'    => 'assistant',
                'content' => $summaryStep['answer'],
                'dax'     => $daxQuery,
            ];

            if (count($history) > 8) {
                $history = array_slice($history, -8);
            }
            $request->session()->put('pbi_chat_history', $history);

            return response()->json([
                'status'       => 'success',
                'reply'        => $summaryStep['answer'],
                'dax'          => $daxQuery,
                'model'        => $summaryStep['model'],
                'row_count'    => count($rows),
                'self_healed'  => $selfHealed,
                'failed_dax'   => $originalFailedDax,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bersihkan riwayat percakapan di Session Laravel
     */
    public function reset(Request $request): JsonResponse
    {
        $request->session()->forget('pbi_chat_history');

        return response()->json([
            'status'  => 'success',
            'message' => 'Riwayat sesi percakapan telah dibersihkan.',
        ]);
    }
}
