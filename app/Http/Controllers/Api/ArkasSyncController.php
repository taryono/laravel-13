<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SyncArkasDataJob;
use Models\BosPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArkasSyncController extends Controller
{
    public function sync(Request $request, BosPeriod $bosPeriod): JsonResponse
    {
        $syncMode = $request->input('mode', 'API_PUSH'); // 'API_PUSH' atau 'EXPORT_ONLY'

        SyncArkasDataJob::dispatch(
            bosPeriodId: $bosPeriod->id,
            userId: $request->user()->id,
            syncMode: $syncMode
        );

        return response()->json([
            'message' => 'Proses sinkronisasi ARKAS sedang berjalan di latar belakang.',
            'status' => 'QUEUED',
        ], 202);
    }
}