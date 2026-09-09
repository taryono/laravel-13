<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Kolom yang ditampilkan di tabel (selain DT_RowIndex & action).
     * Sebelumnya berasal dari string hasil artisan command yang di-explode
     * ulang ("id', 'name") — sekarang jadi array biasa agar tidak rapuh
     * terhadap karakter khusus (koma, apostrof) pada nama kolom.
     */
    protected array $columns = ['name', 'email'];

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
            ->rawColumns(['action']) // ✅ Tetap wajib ada!
            ->setRowId('id');
    }

    /**
     * Render tombol aksi (edit & delete) untuk satu baris.
     *
     * Catatan perbaikan:
     * - $row->name di-escape dengan e() untuk mencegah XSS/attribute
     *   breakout jika nama user mengandung karakter " atau <script>.
     * - Payload JSON pada data-datatable-context sebelumnya rusak
     *   (ada karakter " nyasar sebelum { sehingga attribute HTML
     *   terpotong di kutip pertama). Sekarang dibangun via
     *   json_encode() lalu di-escape dengan htmlspecialchars() agar
     *   valid ditaruh di dalam attribute HTML.
     */
    protected function renderActionButtons($row): string
    {
        $title = e('Detail User ' . $row->name);

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId'     => 'user-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        $editUrl   = e(route('user.edit', $row));
        $deleteUrl = e(route('user.destroy', $row));

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
                    data-submit='<button class="btn btn-primary modal-submit" type="button" data-is_execute_drawer="true" data-datatable-id="user-table">Simpan</button>'
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
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->whereHas('roles', function ($query) {
            $query->where('name', '!=', 'super-admin');
        });
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
            ->setTableId('user-table')

            ->columns($this->getColumns())

            /*
             * Server-side AJAX endpoint.
             */
            ->minifiedAjax(route('user.index'))

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
     * Sebelumnya kolom didapat dari string literal hasil artisan command
     * ("id', 'name") lalu di-explode ulang menggunakan pemisah "', '".
     * Pendekatan ini rapuh: jika ada nama kolom yang mengandung apostrof,
     * koma, atau formatnya berubah, hasilnya bisa salah tanpa error yang
     * jelas. Sekarang kolom didefinisikan langsung sebagai array di
     * properti $columns.
     */
    protected function getColumns(): array
    {
        return collect($this->columns)
            ->map(fn (string $kolom) => Column::make($kolom)
                ->title(ucwords(str_replace(['_',"."], [' ',' '], $kolom))))
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
        return 'User_' . date('YmdHis');
    }
}