<?php

namespace App\DataTables;

use Models\Asset;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AssetDataTable extends DataTable
{
    /**
     * Kolom yang ditampilkan di tabel (selain DT_RowIndex & action).
     * Digenerate langsung sebagai array literal oleh make:crud-yajra dari
     * opsi --columns.
     */
    protected array $columns = [
        // Kepemilikan
        'organization.name',
        'school.name',
        'is_active',

        // Identitas
        'asset_category.name',
        'supplier.name',
        'location.name',
        'code',
        'name',
        'brand',
        'model',
        'specification',
        'serial_number',

        // Keuangan & Penyusutan
        'purchase_date',
        'purchase_price',
        'salvage_value',
        'useful_life_months',
        'depreciation_method',
        'accumulated_depreciation',
        'book_value',
        'asset_chart_of_account_id',
        'accumulated_depreciation_chart_of_account_id',
        'depreciation_expense_chart_of_account_id',

        // Status & Penggunaan
        'status',
        'condition',
        'assigned_to',
        'warranty_end',

        // Penghapusan
        'disposed_at',
        'disposal_value',
        'disposal_reason',

        // Lampiran & Catatan
        'photo_url',
        'notes',
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
            ->editColumn('status', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status']) // ✅ Tetap wajib ada!
            ->setRowId('id');
    }

    /**
     * Render tombol aksi (edit & delete) untuk satu baris.
     *
     * - $row->name di-escape dengan e() untuk mencegah XSS/attribute
     *   breakout jika nilainya mengandung karakter " atau <script>.
     * - Payload data-datatable-context dibangun via json_encode() lalu
     *   di-escape dengan htmlspecialchars() supaya valid ditaruh di
     *   dalam attribute HTML (bukan concat string manual yang rawan
     *   attribute-nya terpotong).
     * - Class tombol delete WAJIB "delete-button" (bukan "delete") supaya
     *   cocok dengan selector di public/assets/js/button.js:
     *   .on('click', '.delete-button', this.handleDeleteButton)
     */
    protected function renderActionButtons($row): string
    {
        $title = e('Detail Asset ' . $row->name);

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'academic-calendar-event-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        $editUrl = e(route('asset.edit', $row));
        $deleteUrl = e(route('asset.destroy', $row));

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
                    data-submit='<button class="btn btn-primary modal-submit" type="button" data-is_execute_drawer="true" data-datatable-id="academic-calendar-event-table">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
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
    public function query(Asset $model): QueryBuilder
    {
        return $model->newQuery()->With(['organization', 'school', 'assetCategory', 'supplier', 'location', 'assignedTo']);
    }

    /**
     * Configure DataTable HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()

            /*
             * Example:
             * Asesi -> asesi-table
             * Asesor -> asesor-table
             */
            ->setTableId('asset-table')

            ->columns($this->getColumns())

            /*
             * Server-side AJAX endpoint.
             */
            ->minifiedAjax(route('asset.index'))

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
                    ->className('btn btn-success btn-sm'),

                Button::make('csv')
                    ->text(
                        '<span class="material-symbols-outlined align-middle me-1">
                            description
                        </span> CSV'
                    )
                    ->className('btn btn-primary btn-sm'),

                Button::make('pdf')
                    ->text(
                        '<span class="material-symbols-outlined align-middle me-1">
                            picture_as_pdf
                        </span> PDF'
                    )
                    ->className('btn btn-danger btn-sm'),

                Button::make('print')
                    ->text(
                        '<span class="material-symbols-outlined align-middle me-1">
                            print
                        </span> Print'
                    )
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
     *
     * Dibangun langsung dari property $columns (array literal yang sudah
     * digenerate di atas oleh make:crud-yajra) -- tidak lagi pakai
     * explode() dari string literal seperti versi lama, yang rapuh
     * terhadap karakter khusus pada nama kolom.
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
        return 'Asset_' . date('YmdHis');
    }
}
