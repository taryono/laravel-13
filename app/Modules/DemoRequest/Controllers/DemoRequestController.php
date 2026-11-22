<?php

namespace App\Modules\DemoRequest\Controllers;

use App\DataTables\DemoRequestDataTable;
use App\Http\Controllers\MainController;
use Models\DemoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DemoRequestController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DemoRequest());
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
        DemoRequestDataTable $dataTable
    ) {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        $stats = [
            'total' => DemoRequest::count(),
            'pending' => DemoRequest::where('status', 'pending')->count(),
            'contacted' => DemoRequest::where('status', 'contacted')->count(),
            'scheduled' => DemoRequest::where('status', 'scheduled')->count(),
            'completed' => DemoRequest::where('status', 'completed')->count(),
            'cancelled' => DemoRequest::where('status', 'cancelled')->count(),
        ];

        $demoRequests = DemoRequest::latest()->paginate(15);

        return $dataTable->render(
            'DemoRequest::index',
            compact('stats', 'demoRequests')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $demoRequest = new DemoRequest();
        $result = $this->getForm($demoRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DemoRequest::create',
            compact('demoRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'person_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'package' => ['required', 'string', 'max:100'],
            'demo_datetime' => ['required'],
            'message' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', 'in:pending,contacted,scheduled,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DemoRequest::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DemoRequest berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DemoRequest.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DemoRequest gagal ditambahkan: ' . $e->getMessage()
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $demoRequest = $this->model->findOrFail($id);
        $result = $this->getForm($demoRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DemoRequest::edit',
            compact('demoRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'person_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'package' => ['required', 'string', 'max:100'],
            'demo_datetime' => ['required'],
            'message' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', 'in:pending,contacted,scheduled,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $demoRequest = $this->model->findOrFail($id);
            DB::transaction(function () use ($demoRequest, $validated) {
                $demoRequest->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DemoRequest berhasil diperbarui.',
                $demoRequest
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DemoRequest.',
                [
                    'exception' => $e,
                    'DemoRequest_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DemoRequest gagal diperbarui: ' . $e->getMessage()
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $demoRequest = $this->model->findOrFail($id);

            DB::transaction(function () use ($demoRequest) {
                $demoRequest->delete();
            });

            return $this->successResponse(
                $request,
                'Data DemoRequest berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $demoRequest->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DemoRequest.',
                [
                    'exception' => $e,
                    'DemoRequest_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DemoRequest gagal dihapus.'
            );
        }
    }
}
