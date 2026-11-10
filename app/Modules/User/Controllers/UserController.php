<?php

namespace App\Modules\User\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Controllers\MainController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Rules\StrongPassword;
use Illuminate\Support\Arr;

class UserController extends MainController
{

    public function __construct()
    {
        parent::__construct(new User());
        // Otomatis hubungkan semua method ke UserPolicy
        // index, show, create, update, delete akan otomatis cek ke policy 
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        UserDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /user?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'User::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = new User();
        $organizations = \Models\Organization::pluck('name', 'id')->toArray();
        $roles = \Models\Role::pluck('name', 'id')->toArray();
        return view(
            'User::create',
            compact('user', 'organizations', 'roles')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed', new StrongPassword()],
            'password_confirmation' => 'required',
            'same:password',
            'min:8',
        ]);
        try {
            $model = DB::transaction(function () use ($validated) {
                return User::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data User berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan User.',
                [
                    'exception' => $e,
                    'data' => Arr::except($validated, ['password']), // jangan log password meski sudah hashed
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data User gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = $this->model->findOrFail($id);
        $organizations = \Models\Organization::pluck('name', 'id')->toArray();
        $roles = \Models\Role::pluck('name', 'id')->toArray();
        return view('User::edit', compact('user', 'organizations', 'roles'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'nullable', // boleh dikosongkan -> berarti password tidak diganti
                'confirmed',
                new StrongPassword(),
            ],
        ]);

        // Kalau field password dikosongkan, jangan ikut-ikutan di-update ke
        // null / string kosong -- buang saja dari payload.
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // FIX: pindahkan findOrFail() ke luar try, atau tangkap ModelNotFoundException
        // secara terpisah, supaya $user selalu terdefinisi sebelum dipakai di catch.
        $user = $this->model->findOrFail($id);
        $this->authorize('update', $user);
        try {
            DB::transaction(function () use ($user, $validated) {
                $user->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data User berhasil diperbarui.',
                $user
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui User.',
                [
                    'exception' => $e,
                    'user_id_target' => $user->getKey(),
                    'data' => Arr::except($validated, ['password']),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data User gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {

            $user = $this->model->find($id);
            if (!$user) {
                return $this->errorResponse(
                    $request,
                    'Data User tidak ditemukan.'
                );
            }
            DB::transaction(function () use ($user) {
                $user->delete();
            });

            return $this->successResponse(
                $request,
                'Data User berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus User.',
                [
                    'exception' => $e,
                    'User_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data User gagal dihapus.'
            );
        }
    }
}