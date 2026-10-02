<?php

namespace App\Http\Controllers\Ppdb\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Models\Applicant;
use Models\Admission;

class ApplicantDashboardController extends Controller
{
    /**
     * Retrieve the current authenticated applicant or fallback.
     */
    protected function getActiveApplicant()
    {
        $applicant = Auth::guard('applicant')->user();

        if (!$applicant) {
            if (Auth::guard('web')->check()) {
                $applicant = Applicant::where('user_id', Auth::id())->first();
            }
            if (!$applicant) {
                $applicant = Applicant::latest()->first();
            }
        }

        if ($applicant) {
            $applicant->load([
                'admission.timelines',
                'admission.paths',
                'school',
                'domicile',
                'documents',
                'verifications',
                'parents',
                'achievements',
                'invoices',
                'path'
            ]);
        }

        return $applicant;
    }

    /**
     * Display applicant portal dashboard overview.
     */
    public function index(Request $request)
    {
        $applicant = $this->getActiveApplicant();
        $tab = 'overview';
        return view('ppdb.dashboard', compact('applicant', 'tab'));
    }

    /**
     * Display applicant profile.
     */
    public function profile(Request $request)
    {
        $applicant = $this->getActiveApplicant();
        $tab = 'profile';
        return view('ppdb.dashboard', compact('applicant', 'tab'));
    }

    /**
     * Display applicant uploaded documents and verification status.
     */
    public function documents(Request $request)
    {
        $applicant = $this->getActiveApplicant();
        $tab = 'documents';
        return view('ppdb.dashboard', compact('applicant', 'tab'));
    }

    /**
     * Display applicant payment status & invoice.
     */
    public function payments(Request $request)
    {
        $applicant = $this->getActiveApplicant();
        $tab = 'payments';
        return view('ppdb.dashboard', compact('applicant', 'tab'));
    }

    /**
     * Display applicant selection results & announcements.
     */
    public function announcements(Request $request)
    {
        $applicant = $this->getActiveApplicant();
        $tab = 'announcements';
        return view('ppdb.dashboard', compact('applicant', 'tab'));
    }
}
