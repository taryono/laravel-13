<?php

namespace App\DataTables;

use App\Enums\AttendanceStatus;
use Models\ExtracurricularAttendance;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExtracurricularAttendanceDataTable extends DataTable
{
    /**
     * Definisi kolom: 'data_path' => ['title' => '...', 'name' => 'relasi.kolom']
     */
    protected array $columns = [
        'student_extracurricular.extracurricular.name' => [
            'title' => 'Ekstrakurikuler',
            'name' => 'studentExtracurricular.extracurricular.name',
        ],
        'student_extracurricular.student.name' => [
            'title' => 'Siswa',
            'name' => 'studentExtracurricular.student.name',
        ],
        'meeting_date' => [
            'title' => 'Tanggal Pertemuan',
            'name' => 'meeting_date',
        ],
        'status' => [
            'title' => 'Status',
            'name' => 'status',
        ],
        'notes' => [
            'title' => 'Catatan',
            'name' => 'notes',
        ],
        'recorder.name' => [
            'title' => 'Dicatat Oleh',
            'name' => 'recorder.name',
        ],
        'is_active' => [
            'title' => 'Aktif',
            'name' => 'is_active',
        ],
    ];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('meeting_date', fn($row) => $row->meeting_date?->format('d-m-Y') ?? '-')
            ->editColumn('status', function ($row) {
                $status = $row->status instanceof AttendanceStatus
                    ? $row->status
                    : AttendanceStatus::tryFrom((string) $row->status);

                return match ($status) {
                    AttendanceStatus::PRESENT => '<span class="badge bg-success">Hadir</span>',
                    AttendanceStatus::SICK => '<span class="badge bg-warning text-dark">Sakit</span>',
                    AttendanceStatus::PERMIT => '<span class="badge bg-info text-dark">Izin</span>',
                    AttendanceStatus::ABSENT => '<span class="badge bg-danger">Alpa</span>',
                    AttendanceStatus::LATE => '<span class="badge bg-secondary">Terlambat</span>',
                    default => '<span class="badge bg-light text-dark">-</span>',
                };
            })
            ->editColumn('notes', fn($row) => e($row->notes ?? '-'))
            ->editColumn('recorder.name', fn($row) => e($row->recorder?->name ?? '-'))
            ->editColumn('is_active', fn($row) => $row->is_active
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-secondary">Nonaktif</span>')
            ->addColumn('action', fn($row) => $this->renderActionButtons($row))
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'is_active'])
            ->setRowId('id');
    }

    protected function renderActionButtons($row): string
    {
        $title = e(
            'Kehadiran ' .
            ($row->studentExtracurricular?->student?->name ?? '-') . ' - ' .
            ($row->studentExtracurricular?->extracurricular?->name ?? '-')
        );

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'extracurricular-attendance-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        $editUrl = e(route('extracurricular_attendance.edit', $row));
        $deleteUrl = e(route('extracurricular_attendance.destroy', $row));

        return <<<HTML
            <div class="d-flex justify-content-center gap-1">
                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    data-toggle="ajax-modal"
                    data-size="lg"
                    data-method="GET"
                    data-title="{$title}"
                    data-url="{$editUrl}"
                    data-datatable-context='{$context}'
                    data-submit='<button class="btn btn-primary modal-submit" type="button" data-is_execute_drawer="true" data-datatable-id="extracurricular-attendance-table">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                    <span class="material-symbols-outlined">edit</span>
                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-danger delete-button"
                    data-confirm-delete="true"
                    data-is_execute_drawer="true"
                    data-url="{$deleteUrl}"
                    title="Hapus">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
        HTML;
    }

    public function query(ExtracurricularAttendance $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('extracurricular_attendances.*')
            ->with([
                'studentExtracurricular:id,student_id,extracurricular_id',
                'studentExtracurricular.student:id,name',
                'studentExtracurricular.extracurricular:id,name',
                'recorder:id,name',
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('extracurricular-attendance-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('extracurricular_attendance.index'))
            // Indeks 3 = kolom meeting_date (0: No, 1: Ekskul, 2: Siswa, 3: Tanggal)
            ->orderBy(3, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'dom' =>
                    '<"row mb-3"' .
                    '<"col-md-6 d-flex align-items-center"B>' .
                    '<"col-md-6"f>' .
                    '>' .
                    'rt' .
                    '<"row mt-3"' .
                    '<"col-md-6"i>' .
                    '<"col-md-6 d-flex justify-content-md-end"p>' .
                    '>',
                'autoWidth' => false,
                'responsive' => true,
                'processing' => true,
                'serverSide' => true,
                'stateSave' => false,
                'language' => [
                    'processing' => '
                        <div class="d-flex align-items-center gap-2">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <span>Memuat data...</span>
                        </div>
                    ',
                    'search' => 'Cari:',
                    'lengthMenu' => 'Tampilkan _MENU_ data',
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    'infoEmpty' => 'Tidak ada data',
                    'zeroRecords' => 'Data tidak ditemukan',
                    'emptyTable' => 'Belum ada data',
                    'paginate' => [
                        'first' => 'Awal',
                        'last' => 'Akhir',
                        'next' => '›',
                        'previous' => '‹',
                    ],
                ],
            ])
            ->buttons([
                Button::make('excel')
                    ->text('<span class="material-symbols-outlined align-middle me-1">download</span> Excel')
                    ->className('btn btn-success btn-sm'),
                Button::make('csv')
                    ->text('<span class="material-symbols-outlined align-middle me-1">description</span> CSV')
                    ->className('btn btn-primary btn-sm'),
                Button::make('pdf')
                    ->text('<span class="material-symbols-outlined align-middle me-1">picture_as_pdf</span> PDF')
                    ->className('btn btn-danger btn-sm'),
                Button::make('print')
                    ->text('<span class="material-symbols-outlined align-middle me-1">print</span> Print')
                    ->className('btn btn-primary btn-sm'),
                Button::make([
                    'text' => '<span class="material-symbols-outlined align-middle">refresh</span>',
                    'action' => 'function (e, dt, node, config) { dt.ajax.reload(null, false); }',
                ])->className('btn btn-secondary btn-sm'),
            ]);
    }

    protected function getColumns(): array
    {
        return collect($this->columns)
            ->map(fn(array $config, string $dataKey) => Column::make($dataKey)
                ->name($config['name'])
                ->title($config['title']))
            ->prepend(
                Column::make('DT_RowIndex')
                    ->title('No')
                    ->orderable(false)
                    ->searchable(false)
                    ->width(60)
                    ->addClass('text-center')
            )
            ->push(
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(120)
                    ->addClass('text-center')
                    ->title('Aksi')
            )
            ->values()
            ->all();
    }

    protected function filename(): string
    {
        return 'ExtracurricularAttendance_' . date('YmdHis');
    }
}