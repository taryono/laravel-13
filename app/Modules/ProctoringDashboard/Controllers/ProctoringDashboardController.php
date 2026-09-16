<?php // app/Http/Controllers/ProctoringDashboardController.php
namespace App\Modules\ProctoringApi\Controllers;

use App\Http\Controllers\Controller;
use Models\CbtSession;
use Models\ExamPackage;
use Illuminate\Http\Request;

class ProctoringDashboardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewProctoring', auth()->user()); // Atur izin sesuai kebutuhan

        $sessions = CbtSession::with(['student', 'examPackage'])
            ->where('status', '!=', 'completed')
            ->where('is_active', 1)
            ->orderByDesc('start_time')
            ->get()
            ->append(['log_count']); // Atau tambahkan select raw

        // Tambahkan jumlah log untuk setiap sesi
        $sessions->transform(function ($s) {
            $s->log_count = $s->activityLogs()->count();
            return $s;
        });

        $exams = ExamPackage::where('is_active', 1)->orderBy('name')->get();
        return view('ProctoringDashboard::index', compact('sessions', 'exams'));
    }
}