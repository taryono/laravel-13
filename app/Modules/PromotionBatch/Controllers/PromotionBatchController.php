<?php
namespace App\Modules\PromotionBatch\Controllers;
use App\DataTables\PromotionBatchDataTable;
use App\Http\Controllers\MainController;
use App\Http\Requests\PromotionBatchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\AcademicYear;
use Models\PromotionBatch;
use Throwable;

class PromotionBatchController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PromotionBatch());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        PromotionBatchDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /promotion-approval?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PromotionBatch::index'
        );
    }

    public function create(): View
    {
        return view('PromotionBatch::create', $this->formOptions());
    }

    public function store(PromotionBatchRequest $request)
    {
        $data = $request->validated();

        try {
            $model = DB::transaction(function () use ($data) {
                return PromotionBatch::create($data + [
                    'status' => PromotionBatch::STATUS_DRAFT, // batch baru selalu draft
                    'created_by' => Auth::id(),
                ]);
            });

            return $this->successResponse($request, 'Batch kenaikan kelas berhasil ditambahkan.', $model);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan PromotionBatch.', [
                'exception' => $e,
                'data' => $data,
                'user_id' => $request->user()?->id,
            ]);

            return $this->errorResponse($request, 'Batch kenaikan kelas gagal ditambahkan.');
        }
    }

    public function edit(PromotionBatch $promotion_batch): View
    {
        $this->ensureDraft($promotion_batch);

        return view('PromotionBatch::edit', ['promotion_batch' => $promotion_batch] + $this->formOptions());
    }

    public function update(PromotionBatchRequest $request, PromotionBatch $promotion_batch)
    {
        $this->ensureDraft($promotion_batch);

        $data = $request->validated();

        try {
            DB::transaction(fn() => $promotion_batch->update($data));

            return $this->successResponse($request, 'Batch kenaikan kelas berhasil diperbarui.', $promotion_batch);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui PromotionBatch.', [
                'exception' => $e,
                'promotion_batch_id' => $promotion_batch->id,
                'data' => $data,
                'user_id' => $request->user()?->id,
            ]);

            return $this->errorResponse($request, 'Batch kenaikan kelas gagal diperbarui.');
        }
    }

    public function destroy(Request $request, PromotionBatch $promotion_batch)
    {
        $this->ensureDraft($promotion_batch);

        try {
            DB::transaction(fn() => $promotion_batch->delete());

            return $this->successResponse($request, 'Batch kenaikan kelas berhasil dihapus.', null);
        } catch (Throwable $e) {
            Log::error('Gagal menghapus PromotionBatch.', [
                'exception' => $e,
                'promotion_batch_id' => $promotion_batch->id,
                'user_id' => $request->user()?->id,
            ]);

            return $this->errorResponse($request, 'Batch kenaikan kelas gagal dihapus.');
        }
    }

    /* ---------- helpers ---------- */

    private function formOptions(): array
    {
        return [
            // sesuaikan kolom 'name' dengan kolom tampilan di tabel academic_years
            'academicYears' => AcademicYear::orderByDesc('id')->pluck('name', 'id')->toArray(),
        ];
    }

    /** Hanya batch berstatus draft yang boleh diubah / dihapus. */
    private function ensureDraft(PromotionBatch $batch): void
    {
        abort_unless($batch->isDraft(), 403, 'Hanya batch berstatus draft yang dapat diubah atau dihapus.');
    }
}