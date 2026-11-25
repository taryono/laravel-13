<?php

namespace App\DataTables;

use Models\Shift;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ShiftDataTable extends DataTable
{
    /**
     * Kolom yang ditampilkan di tabel
     */
    protected array $columns = [
        'code',
        'name',
        'start_time',
        'end_time',
        'grace_period_start',
        'grace_period_end',
        'overtime_min_threshold',
        'break_start',
        'break_end',
        'break_duration',
        'crosses_midnight',
        'is_active'
    ];

    /**
     * Build DataTable response.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return $this->renderActionButtons($row);
            })
            ->addIndexColumn()
            ->editColumn('start_time', function ($row) {
                return $row->start_time ? date('H:i', strtotime($row->start_time)) : '-';
            })
            ->editColumn('end_time', function ($row) {
                return $row->end_time ? date('H:i', strtotime($row->end_time)) : '-';
            })
            ->editColumn('grace_period_start', function ($row) {
                return $row->grace_period_start ? $row->grace_period_start . ' menit' : '-';
            })
            ->editColumn('grace_period_end', function ($row) {
                return $row->grace_period_end ? $row->grace_period_end . ' menit' : '-';
            })
            ->editColumn('overtime_rate', function ($row) {
                return $row->overtime_rate ? number_format($row->overtime_rate, 1) . 'x' : '1.5x';
            })
            ->editColumn('night_shift', function ($row) {
                if ($row->night_shift) {
                    $nightStart = $row->night_shift_start ? date('H:i', strtotime($row->night_shift_start)) : '22:00';
                    return '<span class="badge bg-warning text-dark">Ya (Mulai ' . $nightStart . ')</span>';
                }
                return '<span class="badge bg-secondary">Tidak</span>';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Nonaktif</span>';
            })
            ->rawColumns(['action', 'night_shift', 'is_active'])
            ->setRowId('id');
    }

    /**
     * Render tombol aksi
     */
    protected function renderActionButtons($row): string
    {
        $title = e('Edit Shift ' . $row->name);

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'shift-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        $editUrl = e(route('shift.edit', $row->id));
        $deleteUrl = e(route('shift.destroy', $row->id));

        // Detail URL untuk melihat employee yang menggunakan shift ini
        $detailUrl = e(route('shift.show', $row->id));

        return <<<HTML
            <div class="d-flex justify-content-center gap-1">
                <button
                    type="button"
                    class="btn btn-sm btn-info"
                    data-toggle="ajax-modal"
                    data-size="lg"
                    data-method="GET"
                    data-title="Detail Shift"
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
                    data-submit='<button class="btn btn-primary modal-submit" type="button" data-is_execute_drawer="true" data-datatable-id="shift-table">Simpan</button>'
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

    /**
     * Get query source of DataTable.
     */
    public function query(Shift $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Configure DataTable HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('shift-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('shift.index'))
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'dom' =>
                    '<"row mb-3"' .
                    '<"col-md-4 d-flex align-items-center"B>' .
                    '<"col-md-4"l>' .
                    '<"col-md-4"f>' .
                    '>' .
                    'rt' .
                    '<"row mt-3"' .
                    '<"col-md-6"i>' .
                    '<"col-md-6 d-flex justify-content-md-end"p>' .
                    '>',
                'autoWidth' => false,
                'responsive' => false,
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
                    'className' => 'btn btn-secondary',
                    'action' => 'function (e, dt, node, config) {
                        dt.ajax.reload(null, false);
                    }',
                ])->className('btn btn-secondary'),
            ]);
    }

    /**
     * Get DataTable columns.
     */
    protected function getColumns(): array
    {
        return collect($this->columns)
            ->map(fn(string $kolom) => Column::make($kolom)
                ->title(ucwords(str_replace(['_', "."], [' ', ' '], $kolom))))
            // 1. PREPEND: Masukkan kolom No di posisi PALING AWAL (indeks 0)
            ->prepend(
                Column::make('DT_RowIndex')
                    ->title('No')
                    ->orderable(false)
                    ->searchable(false)
                    ->width(80)
                    ->addClass('text-center')
            )
            // 2. PUSH: Masukkan kolom Aksi di posisi PALING AKHIR
            ->push(
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(120)
                    ->addClass('text-center')
                    ->title('Aksi')
            )
            ->all(); // Konversi collection kembali menjadi array di akhir
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Shift_' . date('YmdHis');
    }
}