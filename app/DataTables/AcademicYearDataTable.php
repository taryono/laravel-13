<?php

namespace App\DataTables;

use Models\AcademicYear;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AcademicYearDataTable extends DataTable
{
    /**
     * Kolom yang ditampilkan di tabel (selain DT_RowIndex & action).
     * Digenerate langsung sebagai array literal oleh make:crud-yajra dari
     * opsi --columns.
     */
    protected array $columns = ['school_name', 'name', 'code', 'start_date', 'end_date', 'start_date', 'end_date', 'is_registration_open', 'registration_start_date', 'registration_end_date', 'is_active', 'is_current'];

    /**
     * Build DataTable response.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return $this->renderActionButtons($row);
            })->addColumn('is_registration_open', function ($row) {
                return $this->renderIsRegistrationOpenButtons($row);
            })->addColumn('is_active', function ($row) {
                return $this->renderIsActiveButtons($row);
            })->addColumn('is_current', function ($row) {
                return $this->renderIsCurrentButtons($row);
            })
            ->addColumn('school_name', function ($row) {
                return $row->school_name ?? $row->school?->name ?? '-';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'is_registration_open', 'is_active', 'is_current']) // ✅ Tetap wajib ada!
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
        $title = e('Detail AcademicYear ' . $row->name);

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'academic-year-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );
        if ($row->id) {
            $editUrl = e(route('academic_year.edit', $row));
            $deleteUrl = e(route('academic_year.destroy', $row));
        } else {
            $editUrl = "#";
            $deleteUrl = "#";
        }

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
                    data-submit='<button class="btn btn-primary modal-submit" type="button" data-is_execute_drawer="true" data-datatable-id="academic-year-table">Simpan</button>'
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
    public function query(AcademicYear $model): QueryBuilder
    {
        return $model->newQuery()
            ->select([
                'academic_years.*',
                'schools.name as school_name' // Asumsikan relasi school ke tabel schools
            ])
            ->leftJoin('schools', 'academic_years.school_id', '=', 'schools.id')
            ->with(['school', 'organization']);
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
            ->setTableId('academic-year-table')

            ->columns($this->getColumns())

            /*
             * Server-side AJAX endpoint.
             */
            ->minifiedAjax(route('academic_year.index'))

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
        return 'AcademicYear_' . date('YmdHis');
    }

    protected function renderIsRegistrationOpenButtons($row): string
    {
        $status = $row->is_registration_open ? 'primary' : 'danger';
        $text = $row->is_registration_open ? 'Buka' : 'Tutup';
        $newStatus = $row->is_registration_open ? 0 : 1;

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'academic-year-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        if ($row->id) {
            $url = e(route('academic_year.updateIsRegistrationOpen', ['academic_year' => $row, 'is_registration_open' => $newStatus]));
        } else {
            $url = "#";
        }

        return <<<HTML
            <span class="badge bg-{$status} cursor-pointer update-registration-open" data-title="{$text} Pendaftaran" data-url="{$url}" data-datatable-context='{$context}'>
                {$text}
            </span> 
        HTML;
    }

    protected function renderIsActiveButtons($row): string
    {
        $status = $row->is_active ? 'success' : 'danger';
        $text = $row->is_active ? 'Aktif' : 'Tidak Aktif';
        $newStatus = $row->is_active ? 0 : 1;

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'academic-year-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        if ($row->id) {
            $url = e(route('academic_year.updateIsActive', ['academic_year' => $row, 'is_active' => $newStatus]));
        } else {
            $url = "#";
        }

        return <<<HTML
            <span class="badge bg-{$status} cursor-pointer update-isactive" data-title="{$text} Tahun Akademik" data-url="{$url}" data-datatable-context='{$context}'>
                {$text}
            </span> 
        HTML;
    }

    protected function renderIsCurrentButtons($row): string
    {
        $status = $row->is_current ? 'success' : 'secondary';
        $text = $row->is_current ? 'Aktif' : 'Non-Aktif';
        $newStatus = $row->is_current ? 0 : 1;

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'academic-year-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        if ($row->id) {
            $url = e(route('academic_year.updateIsCurrent', ['academic_year' => $row, 'is_current' => $newStatus]));
        } else {
            $url = "#";
        }

        return <<<HTML
            <span class="badge bg-{$status} cursor-pointer update-iscurrent" data-title="{$text} Tahun Akademik Saat Ini" data-url="{$url}" data-datatable-context='{$context}'>
                {$text}
            </span> 
        HTML;
    }
}
