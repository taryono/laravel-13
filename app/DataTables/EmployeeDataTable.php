<?php

namespace App\DataTables;

use Models\Employee;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeDataTable extends DataTable
{
    protected array $columns = [
        'nik',
        'full_name',
        'department.name',
        'position.name',
        'employment_status',
        'join_date',
        'is_active'
    ];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return $this->renderActionButtons($row);
            })
            ->addIndexColumn()
            ->editColumn('full_name', function ($row) {
                return '<strong>' . e($row->full_name) . '</strong>' .
                    ($row->nick_name ? ' (' . e($row->nick_name) . ')' : '');
            })
            ->editColumn('department_id', function ($row) {
                return $row->department ? e($row->department->name) : '-';
            })
            ->editColumn('position_id', function ($row) {
                return $row->position ? e($row->position->name) : '-';
            })
            ->editColumn('employment_status', function ($row) {
                $badges = [
                    'tetap' => 'primary',
                    'kontrak' => 'warning',
                    'probation' => 'info',
                    'freelance' => 'secondary',
                ];
                $label = $row->employment_status_label;
                $badge = $badges[$row->employment_status] ?? 'secondary';
                return '<span class="badge bg-' . $badge . '">' . $label . '</span>';
            })
            ->editColumn('join_date', function ($row) {
                return $row->join_date ? $row->join_date->format('d/m/Y') : '-';
            })
            ->editColumn('is_active', function ($row) {
                if ($row->is_active && (!$row->resign_date || $row->resign_date > now())) {
                    return '<span class="badge bg-success">Aktif</span>';
                }
                return '<span class="badge bg-danger">Nonaktif</span>';
            })
            ->addColumn('years_of_service', function ($row) {
                return $row->years_of_service ? number_format($row->years_of_service, 1) . ' thn' : '-';
            })
            ->rawColumns(['action', 'full_name', 'employment_status', 'is_active'])
            ->setRowId('id');
    }

    protected function renderActionButtons($row): string
    {
        $title = e('Edit Karyawan ' . $row->full_name);

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'employee-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        $editUrl = e(route('employee.edit', $row->id));
        $deleteUrl = e(route('employee.destroy', $row->id));
        $detailUrl = e(route('employee.show', $row->id));

        return <<<HTML
            <div class="d-flex justify-content-center gap-1">
                <button
                    type="button"
                    class="btn btn-sm btn-info"
                    data-toggle="ajax-modal"
                    data-size="lg"
                    data-method="GET"
                    data-title="Detail Karyawan"
                    data-url="{$detailUrl}"
                    title="Detail">
                    <span class="material-symbols-outlined">visibility</span>
                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    data-toggle="ajax-modal"
                    data-size="lg"
                    data-method="GET"
                    data-title="{$title}"
                    data-url="{$editUrl}"
                    data-datatable-context='{$context}'
                    data-submit='<button class="btn btn-primary modal-submit" type="button" data-is_execute_drawer="true" data-datatable-id="employee-table">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'
                    title="Edit">
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

    public function query(Employee $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['department', 'position']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employee-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('employee.index'))
            ->orderBy(2)
            ->selectStyleSingle()
            ->parameters([
                // 'dom' =>
                //     '<"row mb-3"' .
                //         '<"col-md-4 d-flex align-items-center"B>' .
                //         '<"col-md-4"l>' .
                //         '<"col-md-4"f>' .
                //     '>' .
                //     'rt' .
                //     '<"row mt-3"' .
                //         '<"col-md-6"i>' .
                //         '<"col-md-6 d-flex justify-content-md-end"p>' .
                //     '>',
                'dom' =>
                    '<"row mb-3"' .
                    '<"col-md-5 d-flex align-items-center"B>' .
                    '<"col-md-2"l>' .
                    '<"col-md-5"f>' .
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
                    'lengthMenu' => '_MENU_',
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
                    'className' => 'btn btn-secondary',
                    'action' => 'function (e, dt, node, config) {
                        dt.ajax.reload(null, false);
                    }',
                ])->className('btn btn-secondary'),
            ]);
    }

    protected function getColumns(): array
    {
        $columns = [
            Column::make('employee_id')->title('NIK')->width(120),
            Column::make('full_name')->title('Nama Lengkap')->width(200),
            Column::make('department_id')->title('Departemen')->width(150),
            Column::make('position_id')->title('Jabatan')->width(150),
            Column::make('employment_status')->title('Status')->width(120),
            Column::make('join_date')->title('Tgl Bergabung')->width(120),
            Column::make('years_of_service')->title('Masa Kerja')->width(100),
            Column::make('is_active')->title('Status Aktif')->width(100),
        ];

        $columns[] = Column::make('DT_RowIndex')
            ->title('No')
            ->orderable(false)
            ->searchable(false)
            ->width(50)
            ->addClass('text-center');

        $columns[] = Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(150)
            ->addClass('text-center')
            ->title('Aksi');

        return $columns;
    }

    protected function filename(): string
    {
        return 'Employees_' . date('YmdHis');
    }
}