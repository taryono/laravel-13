<?php 
// app/Modules/ProctoringApi/Controllers/ProctoringApiController.php

namespace App\Modules\ProctoringApi\Controllers;

use App\Http\Controllers\Controller;
use Models\CbtSession;
use Models\CbtActivityLog;
use Events\ProctoringEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProctoringApiController extends Controller
{

    public function index(Request $request)
    {
         return view('ProctoringApi::index');
    }

    // Kirim sinyal detak jantung & status sesi
    public function heartbeat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:cbt_sessions,id',
            'status'     => 'nullable|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $session = CbtSession::findOrFail($request->session_id);
        $session->update([
            'last_heartbeat' => now(),
            'status' => $request->status ?? $session->status,
        ]);

        // Siarkan ke dasbor pengawas
        broadcast(new ProctoringEvent('heartbeat', [
            'session_id'     => $session->id,
            'student_name'   => $session->student->name ?? 'Tidak Diketahui',
            'status'         => $session->status,
            'trust_score'    => $session->trust_score ?? 100,
            'last_heartbeat' => $session->last_heartbeat->toDateTimeString(),
        ]))->toOthers();

        return response()->json(['success' => true, 'trust_score' => $session->trust_score ?? 100]);
    }

    // Kirim aktivitas/pelanggaran terdeteksi
    public function logActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id'     => 'required|exists:cbt_sessions,id',
            'activity_type'  => 'required|string|max:30',
            'activity_data'  => 'nullable|array',
            'severity'       => 'nullable|in:info,warning,danger,critical',
            'snapshot_url'   => 'nullable|string',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $log = CbtActivityLog::create([
            'session_id'    => $request->session_id,
            'activity_type' => $request->activity_type,
            'activity_data' => $request->activity_data,
            'severity'      => $request->severity ?? 'warning',
            'snapshot_url'  => $request->snapshot_url,
            'recorded_at'   => now(),
        ]);

        // Perbarui skor kepercayaan
        $session = CbtSession::findOrFail($request->session_id);
        $session->trust_score = $session->calculateTrustScore();
        $session->save();

        // Siarkan ke dasbor
        broadcast(new ProctoringEvent('new_activity', [
            'log_id'         => $log->id,
            'session_id'     => $log->session_id,
            'student_name'   => $session->student->name ?? 'Tidak Diketahui',
            'activity_type'  => $log->activity_type,
            'severity'       => $log->severity,
            'snapshot_url'   => $log->snapshot_url,
            'trust_score'    => $session->trust_score,
            'recorded_at'    => $log->recorded_at->toDateTimeString(),
        ]))->toOthers();

        return response()->json(['success' => true, 'log' => $log, 'trust_score' => $session->trust_score]);
    }

    // Pengawas memberi tanda pada sesi
    public function flagSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id'    => 'required|exists:cbt_sessions,id',
            'flag_reason'   => 'required|string',
            'notes'         => 'nullable|string',
        ]);

        $proctoring = \App\Models\CbtProctoring::create([
            'session_id'      => $request->session_id,
            'proctor_id'      => auth()->id(),
            'start_proctoring'=> now(),
            'status'          => 'flagged',
            'flag_reason'     => $request->flag_reason,
            'notes'           => $request->notes,
        ]);

        broadcast(new ProctoringEvent('session_flagged', [
            'session_id' => $request->session_id,
            'flagged_by' => auth()->user()->name,
            'reason'     => $request->flag_reason,
        ]))->toOthers();

        return response()->json(['success' => true, 'proctoring' => $proctoring]);
    }
} 