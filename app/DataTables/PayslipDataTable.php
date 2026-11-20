<?php

namespace App\DataTables;

use Models\Payslip;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayslipDataTable extends DataTable
{
    /**
     * Kolom yang ditampilkan di tabel (selain DT_RowIndex & action).
     */
    protected array $columns = [
        'payslip_number',
        'employee.name',
        'payroll_period.period',
        'gross_salary',
        'total_deduction',
        'net_salary',
        'status',
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
            ->editColumn('gross_salary', function ($row) {
                return 'Rp ' . number_format((float) ($row->gross_salary ?? 0), 0, ',', '.');
            })
            ->editColumn('total_deduction', function ($row) {
                return 'Rp ' . number_format((float) ($row->total_deduction ?? 0), 0, ',', '.');
            })
            ->editColumn('net_salary', function ($row) {
                return '<span class="fw-bold text-success">Rp ' . number_format((float) ($row->net_salary ?? 0), 0, ',', '.') . '</span>';
            })
            ->editColumn('status', function ($row) {
                return $row->status_label;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'net_salary', 'status'])
            ->setRowId('id');
    }

    /**
     * Render tombol aksi (show, edit & delete) untuk satu baris.
     */
    protected function renderActionButtons($row): string
    {
        $num = e($row->payslip_number ?? ('#' . $row->id));
        $title = e('Edit Payslip ' . $num);
        $showTitle = e('Detail Payslip ' . $num);

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'payslip-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        $showUrl = e(route('payslip.show', $row->id));
        $editUrl = e(route('payslip.edit', $row->id));
        $deleteUrl = e(route('payslip.destroy', $row->id));

        return <<<HTML
            <div class="d-flex justify-content-center gap-1">

                <button
                    type="button"
                    class="btn btn-sm btn-info text-white"
                    data-toggle="ajax-modal"
                    data-size="lg"
                    data-method="GET"
                    data-title="{$showTitle}"
                    data-url="{$showUrl}"
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>'
                    title="Lihat Detail">
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
                    data-submit='<button class="btn btn-primary modal-submit" type="submit" form="formPayslip" data-is_execute_drawer="true" data-datatable-id="payslip-table">Simpan</button>'
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
                    title="Hapus"
                >
                    <span class="material-symbols-outlined">delete</span>
                </button>

            </div>
        HTML;
    }

    /**
     * Get query source of DataTable.
     */
    public function query(Payslip $model): QueryBuilder
    {
        return $model->newQuery()->with(['employee', 'payrollPeriod', 'payrollDetail']);
    }

    /**
     * Configure DataTable HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('payslip-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('payslip.index'))
            ->orderBy(1)
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
                    'className' => 'btn btn-secondary',
                    'action' => 'function (e, dt, node, config) { dt.ajax.reload(null, false); }',
                ])->className('btn btn-secondary'),
            ]);
    }

    /**
     * Get DataTable columns.
     */
    protected function getColumns(): array
    {
        return collect($this->columns)
            ->map(function (string $kolom) {
                $titles = [
                    'payslip_number'        => 'No. Slip',
                    'employee.name'         => 'Karyawan',
                    'payroll_period.period' => 'Periode',
                    'gross_salary'          => 'Gaji Kotor',
                    'total_deduction'       => 'Potongan',
                    'net_salary'            => 'Gaji Bersih',
                    'status'                => 'Status',
                ];

                return Column::make($kolom)
                    ->title($titles[$kolom] ?? ucwords(str_replace(['_', '.'], [' ', ' '], $kolom)));
            })
            ->prepend(
                Column::make('DT_RowIndex')
                    ->title('No')
                    ->orderable(false)
                    ->searchable(false)
                    ->width(50)
                    ->addClass('text-center')
            )
            ->push(
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(150)
                    ->addClass('text-center')
                    ->title('Aksi')
            )
            ->all();
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Payslip_' . date('YmdHis');
    }
}
