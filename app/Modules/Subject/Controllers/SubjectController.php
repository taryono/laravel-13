<?php

namespace App\Modules\Subject\Controllers;

use App\DataTables\SubjectDataTable;
use App\Http\Controllers\MainController;
use Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\Services\DataTable;

class SubjectController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Subject());
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
        SubjectDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /subject?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Subject::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $subject = new Subject();
        $parents = \Models\Subject::pluck('name', 'id')->prepend('Pilih Parent', '')->toArray();
        $schools = \Models\School::pluck('name', 'id')->prepend('Pilih Sekolah', '')->toArray();
        $organizations = \Models\Organization::pluck('name', 'id')->prepend('Pilih Yayasan', '')->toArray();
        $categories = \Models\SubjectCategory::pluck('name', 'id')->prepend('Pilih Kategori', '')->toArray();
        return view(
            'Subject::create',
            compact('subject', 'organizations', 'schools', 'categories', 'parents')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'abbreviation' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Subject::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Subject berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Subject.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subject gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $subject = $this->model->findOrFail($id);
        $parents = \Models\Subject::pluck('name', 'id')->prepend('Pilih Parent', '')->toArray();
        $schools = \Models\School::pluck('name', 'id')->prepend('Pilih Sekolah', '')->toArray();
        $organizations = \Models\Organization::pluck('name', 'id')->prepend('Pilih Yayasan', '')->toArray();
        $categories = \Models\SubjectCategory::pluck('name', 'id')->prepend('Pilih Kategori', '')->toArray();
        return view(
            'Subject::edit',
            compact('subject', 'organizations', 'schools', 'categories', 'parents')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'abbreviation' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $subject = $this->model->findOrFail($id);
            DB::transaction(function () use ($subject, $validated) {
                $subject->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Subject berhasil diperbarui.',
                $subject
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Subject.',
                [
                    'exception' => $e,
                    'Subject_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subject gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $subject = $this->model->findOrFail($id);

            DB::transaction(function () use ($subject) {
                $subject->delete();
            });

            return $this->successResponse(
                $request,
                'Data Subject berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $subject->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Subject.',
                [
                    'exception' => $e,
                    'Subject_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subject gagal dihapus.'
            );
        }
    }

    // DataSource untuk DataTables — Format Pohon
    public function treeData(Request $request)
    {
        $orgId = Auth::user()->organization_id ?? 1; // Sesuaikan auth kamu

        // Ambil semua data lalu susun hierarki
        $subjects = Subject::with(['category'])
            ->where('organization_id', $orgId)
            ->orderByRaw('COALESCE(parent_id, id)')
            ->orderBy('name')
            ->get();

        // Susun pohon rekursif
        $tree = $this->toTree($subjects);

        return DataTable::collection($tree)
            ->addColumn('name', function ($row) {
                $prefix = $this->getIndent($row->level);
                $icon = $row->children->isNotEmpty()
                    ? '<i class="fa fa-folder-open text-warning me-1"></i>'
                    : '<i class="fa fa-file-text-o text-muted me-1"></i>';
                return $prefix . $icon . e($row->name);
            })
            ->addColumn('code', fn($row) => e($row->code))
            ->addColumn('category_name', fn($row) => $row->category?->name ?? '-')
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Nonaktif</span>';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <a href="' . route('subjects.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Hapus</button>
                ';
            })
            ->rawColumns(['name', 'status', 'actions'])
            ->make(true);
    }

    // Helper Ubah Datar ke Pohon
    private function toTree($items, $parentId = null, $level = 0)
    {
        $branch = collect();
        foreach ($items->where('parent_id', $parentId) as $item) {
            $item->level = $level;
            $item->children = $this->toTree($items, $item->id, $level + 1);
            $branch->push($item);
        }
        return $branch;
    }

    private function getIndent($level)
    {
        return str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    }
}
