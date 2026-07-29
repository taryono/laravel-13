<?php

namespace App\DataTables;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DocumentDataTable extends DataTable
{
    /**
     * Build DataTable response.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-flex justify-content-center gap-1">

                        <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            data-toggle="ajax-modal"
                            data-size="lg"
                            data-method="GET"
                            data-title="Detail document '.$row->name.'"
                            data-url="'. route('document.edit', $row->id).'"
                            data-submit=\'<button class="btn btn-primary" type="button">Simpan</button>\'
                            data-cancel=\'<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>\'>
                            <span class="material-symbols-outlined">edit</span>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-danger delete"
                            data-confirm-delete="true"
                            data-url="' . route('document.destroy', $row->id) . '"
                            title="Hapus"
                        >
                            <span class="material-symbols-outlined">delete</span>
                        </button>

                    </div>
                ';
            })
            ->addIndexColumn()
            ->rawColumns(['action']) // ✅ Tetap wajib ada!
            ->setRowId('id');
    }

    /**
     * Get query source of DataTable.
     */
    public function query(
        Document $model
    ): QueryBuilder {

        return $model->newQuery();
    }

    /**
     * Configure DataTable HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()

            /*
             * Example:
             * document -> document-table
             * Sertifikasi -> document-table
             */
            ->setTableId(
                'document-table'
            )

            ->columns(
                $this->getColumns()
            )

            /*
             * Server-side AJAX endpoint.
             */
            ->minifiedAjax(
                route('document.index')
            )

            /*
             * Default sorting:
             * column index 1 = name
             */
            ->orderBy(1)

            ->selectStyleSingle()

            ->parameters([

                /*
                 * Custom Bootstrap 5 layout.
                 */
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

                /*
                 * Let CSS / column configuration
                 * control widths.
                 */
                'autoWidth' => false,

                /*
                 * Responsive DataTable.
                 */
                'responsive' => true,

                /*
                 * Processing indicator.
                 */
                'processing' => true,

                /*
                 * Server-side processing.
                 */
                'serverSide' => true,

                /*
                 * Save DataTable state.
                 */
                'stateSave' => false,

                /*
                 * Language.
                 */
                'language' => [
                    'processing' => '
                        <div class="d-flex align-items-center gap-2">
                            <div
                                class="spinner-border spinner-border-sm text-primary"
                                role="status"
                            ></div>
                            <span>Memuat data...</span>
                        </div>
                    ',

                    'search' => 'Cari:',

                    'lengthMenu' =>
                        'Tampilkan _MENU_ data',

                    'info' =>
                        'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                    'infoEmpty' =>
                        'Tidak ada data',

                    'zeroRecords' =>
                        'Data tidak ditemukan',

                    'emptyTable' =>
                        'Belum ada data',

                    'paginate' => [
                        'first' => 'Awal',
                        'last' => 'Akhir',
                        'next' => '›',
                        'previous' => '‹',
                    ],
                ],
            ])

            /*
             * DataTable buttons.
             */
            ->buttons([

                Button::make('excel')
                    ->text(
                        '<span class="material-symbols-outlined align-middle me-1">
                            download
                        </span> Excel'
                    )
                    ->className(
                        'btn btn-success btn-sm'
                    ),

                Button::make('csv')
                    ->text(
                        '<span class="material-symbols-outlined align-middle me-1">
                            description
                        </span> CSV'
                    )
                    ->className(
                        'btn  btn-sm'
                    ),

                Button::make('pdf')
                    ->text(
                        '<span class="material-symbols-outlined align-middle me-1">
                            picture_as_pdf
                        </span> PDF'
                    )
                    ->className(
                        'btn btn-danger btn-sm'
                    ),

                Button::make('print')
                    ->text(
                        '<span class="material-symbols-outlined align-middle me-1">
                            print
                        </span> Print'
                    )
                    ->className(
                        'btn btn-primary btn-sm'
                    ),

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
    public function getColumns(): array
    {
        return [

            Column::make('DT_RowIndex')
            ->title('No')
                ->width(80)
                ->addClass('text-center'),

            Column::make('name')
                ->title('Nama'),

            Column::computed('action')
                ->title('Aksi')
                ->width(100)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),

        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Document_' . date('YmdHis');
    }
}