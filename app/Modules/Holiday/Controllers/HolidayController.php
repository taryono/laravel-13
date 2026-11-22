<?php

namespace App\Modules\Holiday\Controllers;

use App\DataTables\HolidayDataTable;
use App\Http\Controllers\MainController;
use Models\Holiday;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HolidayController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Holiday(), 'holiday');
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
        HolidayDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /holiday?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Holiday::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $holiday = new Holiday();
        $result = $this->getForm($holiday);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Holiday::create',
            compact('holiday', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $rules = [
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'name' => 'required|string|max:255',
            'type' => 'required|in:national,special',
            'is_recurring' => 'nullable|boolean',
            'is_range' => 'nullable|boolean',
        ];

        $validated = $request->validate($rules, [
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        try {
            $isRange = $request->has('is_range');
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = $isRange && !empty($validated['end_date'])
                ? Carbon::parse($validated['end_date'])
                : $startDate;

            $isRecurring = $request->has('is_recurring') ? 1 : 0;
            $insertedCount = 0;
            $skippedCount = 0;

            // PERBAIKAN 1: Bungkus SELURUH loop dalam SATU transaksi database
            DB::transaction(function () use ($validated, $startDate, $endDate, $isRecurring, &$insertedCount, &$skippedCount) {

                $period = CarbonPeriod::create($startDate, $endDate);

                foreach ($period as $date) {
                    $dateString = $date->toDateString();

                    // Cek apakah kombinasi tanggal + jenis sudah ada
                    $exists = Holiday::where('date', $dateString)
                        ->where('type', $validated['type'])
                        ->exists();

                    if (!$exists) {
                        Holiday::create([
                            'date' => $dateString,
                            'name' => $validated['name'],
                            'type' => $validated['type'],
                            'is_recurring' => $isRecurring,
                        ]);
                        $insertedCount++;
                    } else {
                        $skippedCount++;
                    }
                }
            });

            // 3. Response
            $message = "Berhasil menambahkan {$insertedCount} hari libur.";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} tanggal dilewati karena sudah ada di sistem).";
            }

            // Ini akan redirect ke route('holiday.index') karena $this->controller = 'holiday'
            return $this->successResponse($request, $message);

        } catch (Throwable $e) {
            Log::error(
                'Gagal menambahkan Holiday.',
                [
                    'exception' => $e->getMessage(),
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Holiday gagal ditambahkan. Silakan coba lagi.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $holiday = $this->model->findOrFail($id);
        $result = $this->getForm($holiday);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Holiday::edit',
            compact('holiday', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $holiday = $this->model->findOrFail($id);
        $validated = $request->validate([
            // UBAH: 'date' menjadi 'start_date' sesuai nama input di form
            'date' => [
                'required',
                'date',
                Rule::unique('holidays')->where(
                    fn($query) =>
                        $query->where('date', $request->date)->where('type', $request->type)
                )->ignore($holiday->id) // Abaikan record yang sedang diedit
            ],
            'name' => 'required|string|max:255',
            'type' => 'required|in:national,special',
            'is_recurring' => 'nullable|boolean',
        ], [
            'date.unique' => 'Kombinasi tanggal dan jenis libur ini sudah ada di sistem.',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring') ? 1 : 0;

        try {

            DB::transaction(function () use ($holiday, $validated, $request) {
                $holiday->update([
                    'date' => $validated['date'],
                    'name' => $validated['name'],
                    'type' => $validated['type'],
                    'is_recurring' => $request->has('is_recurring') ? 1 : 0,
                ]);
            });

            return $this->successResponse(
                $request,
                'Data Holiday berhasil diperbarui.',
                $holiday
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Holiday.',
                [
                    'exception' => $e,
                    'Holiday_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Holiday gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $holiday = $this->model->findOrFail($id);

            DB::transaction(function () use ($holiday) {
                $holiday->delete();
            });

            return $this->successResponse(
                $request,
                'Data Holiday berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $holiday->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Holiday.',
                [
                    'exception' => $e,
                    'Holiday_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Holiday gagal dihapus.'
            );
        }
    }
}
