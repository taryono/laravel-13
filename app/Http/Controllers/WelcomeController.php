<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Models\TrialRegistration;
use App\Services\SliderService;
use Models\EducationLevel;
use Models\Module;
use Models\Role;
use Models\User;
use Models\School;
use Models\Position;
use Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(SliderService $sliderService)
    {
        $sliders = $sliderService->getActive();
        return view('welcome', compact('sliders'));
    }
    public function trialSignup(Request $request): View
    {
        $token = $request->session()->get('trial_token');
        $registration = $token
            ? TrialRegistration::where('token', $token)->where('status', 'in_progress')->first()
            : null;

        if (!$registration) {
            $registration = TrialRegistration::create([
                'token' => Str::uuid(),
                'current_step' => 1,
            ]);
            $request->session()->put('trial_token', $registration->token);
        }

        $modules = Module::where('is_active', true)->get(['id', 'name']);
        $student_ranges = \Models\StudentRange::where('is_active', true)->get(['id', 'name']);
        $roles = Role::where('is_active', true)->where('show_to_trial', 1)->get(['id', 'name']);
        $packages = Package::where('is_active', true)->get(['id', 'name']);
        return view('trial_signup', compact('registration', 'modules', 'roles', 'student_ranges', 'packages'));
    }

    public function saveStep(Request $request, int $step): JsonResponse
    {
        $registration = $this->currentRegistration($request);

        $data = match ($step) {
            1 => $request->validate([
                'nama_sekolah' => 'required|string|max:255',
                'npsn' => 'nullable|string|max:20',
                'education_level_id' => 'required|array|min:1',
                'education_level_id.*' => 'exists:education_levels,code',
                'total_student' => 'required|string',
                'address' => 'nullable|string|max:255',
                'nama_user' => 'required|string|max:255',
                'position_id' => 'required|in:kepala_sekolah,wakil_kepala_sekolah,guru,tata_usaha,admin_it,lainnya',
            ]),
            2 => $request->validate([
                'modul' => 'required|array|min:1',
                'modul.*' => 'exists:modules,code',
            ]),
            3 => $request->validate([
                'role' => 'required|array|min:1',
                'role.*' => 'exists:roles,code',
            ]),
            4 => $request->validate([
                'notif_whatsapp' => 'boolean',
                'notif_email' => 'boolean',
                'notif_sms' => 'boolean',
            ]),
            default => abort(422, 'Step tidak valid'),
        };

        match ($step) {
            1 => tap($registration)->fill(collect($data)->except('education_level_id')->toArray())
                ->modules() && $registration->education_level_ids()->sync(
                    EducationLevel::whereIn('code', $data['education_level_id'])->pluck('id')
                ),
            2 => $registration->modules()->sync(
                Module::whereIn('code', $data['modul'])->pluck('id')
            ),
            3 => $registration->roles()->sync(
                Role::whereIn('code', $data['role'])->pluck('id')
            ),
            default => $registration->fill($data),
        };

        $registration->current_step = max($registration->current_step, $step + 1);
        $registration->save();

        return response()->json(['success' => true, 'next_step' => $step + 1]);
    }

    public function completeSignup(Request $request): JsonResponse
    {
        $registration = $this->currentRegistration($request);

        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = DB::transaction(function () use ($registration, $data) {
            $school = School::create([
                'trial_registration_id' => $registration->id,
                'name' => $registration->nama_sekolah,
                'npsn' => $registration->npsn,
                'education_level_id' => $registration->education_level_id,
                'total_student' => $registration->total_student,
                'alamat' => $registration->address,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            $school->education_levels()->sync($registration->education_levels()->pluck('education_levels.id'));
            $school->modules()->sync(
                $registration->modules()->pluck('modules.id')->mapWithKeys(
                    fn($id) => [$id => ['activated_at' => now()]]
                )
            );

            $user = \App\Models\User::create([
                'school_id' => $school->id,
                'role_id' => $registration->role_id,
                'name' => $registration->nama_user,
                'position_id' => $registration->position_id,
                'email' => $data['email'],
                'no_hp' => $data['no_hp'],
                'password' => Hash::make($data['password']),
                'is_trial' => true,
            ]);

            foreach (['whatsapp' => 'notif_whatsapp', 'email' => 'notif_email', 'sms' => 'notif_sms'] as $channel => $field) {
                $user->notificationPreferences()->create([
                    'channel' => $channel,
                    'is_enabled' => $registration->{$field},
                ]);
            }

            $registration->update([
                'email' => $data['email'],
                'no_hp' => $data['no_hp'],
                'school_id' => $school->id,
                'user_id' => $user->id,
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            $user->roles()->sync($registration->roles()->pluck('roles.id'));
            return $user;
        });

        Auth::login($result);
        session()->forget('trial_token');

        return response()->json(['success' => true, 'redirect' => route('dashboard')]);
    }

    private function currentRegistration(Request $request): TrialRegistration
    {
        return TrialRegistration::where('token', $request->session()->get('trial_token'))
            ->where('status', 'in_progress')
            ->firstOrFail();
    }

    public function requestDemo()
    {
        $roles = Role::all();
        $positions = Position::where('is_active', true)->pluck('id', 'name')->toArray();
        $packages = Package::where('is_active', true)->pluck('id', 'name')->toArray();
        return view('request-demo', compact('roles', 'positions', 'packages'));
    }
}
