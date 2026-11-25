<?php

namespace App\DataTables;

use Models\DemoRequest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DemoRequestDataTable extends DataTable
{
    /**
     * Kolom yang ditampilkan di tabel (selain DT_RowIndex & action).
     * Digenerate langsung sebagai array literal oleh make:crud-yajra dari
     * opsi --columns.
     */
    protected array $columns = ['school_name', 'person_name', 'position', 'email', 'phone', 'package', 'demo_datetime', 'message', 'status', 'notes'];

    /**
     * Build DataTable response.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('school_name', function ($row) {
                $initials = e(strtoupper(substr($row->school_name, 0, 2)));
                $school = e($row->school_name);
                $pic = e($row->person_name);
                $pos = e($row->position);
                return <<<HTML
                    <div class="d-flex align-items-center">
                        <div class="school-avatar me-2" style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#2f5ce0,#7b42f6);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                            {$initials}
                        </div>
                        <div>
                            <div class="fw-bold text-dark text-truncate" style="max-width:200px;">{$school}</div>
                            <small class="text-muted"><i class="fa-solid fa-user me-1"></i>{$pic} <span class="badge bg-light text-secondary border">{$pos}</span></small>
                        </div>
                    </div>
                HTML;
            })
            ->editColumn('package', function ($row) {
                $pkg = strtolower($row->package);
                $class = match ($pkg) {
                    'starter' => 'background:rgba(13,202,240,0.12);color:#0dcaf0;border:1px solid rgba(13,202,240,0.3);',
                    'professional' => 'background:rgba(47,92,224,0.12);color:#3b82f6;border:1px solid rgba(47,92,224,0.3);',
                    'enterprise' => 'background:rgba(168,85,247,0.12);color:#a855f7;border:1px solid rgba(168,85,247,0.3);',
                    default => 'background:rgba(108,117,125,0.12);color:#6c757d;border:1px solid rgba(108,117,125,0.3);',
                };
                $name = e(ucfirst($row->package));
                return "<span class='badge rounded-pill px-2 py-1' style='{$class}'>{$name}</span>";
            })
            ->editColumn('demo_datetime', function ($row) {
                if (!$row->demo_datetime)
                    return '-';
                $dt = \Carbon\Carbon::parse($row->demo_datetime);
                $date = $dt->translatedFormat('d M Y');
                $time = $dt->format('H:i') . ' WIB';
                return "<div><span class='fw-semibold'>{$date}</span><br><small class='text-muted'><i class='fa-regular fa-clock me-1'></i>{$time}</small></div>";
            })
            ->editColumn('phone', function ($row) {
                $phone = e($row->phone);
                $email = e($row->email);
                $clean = preg_replace('/[^0-9]/', '', $row->phone);
                $wa = str_starts_with($clean, '0') ? '62' . substr($clean, 1) : $clean;
                return <<<HTML
                    <div>
                        <a href="https://wa.me/{$wa}" target="_blank" class="text-success text-decoration-none fw-semibold small d-block">
                            <i class="fa-brands fa-whatsapp me-1"></i>{$phone}
                        </a>
                        <small class="text-muted d-block text-truncate" style="max-width:160px;">
                            <i class="fa-regular fa-envelope me-1"></i>{$email}
                        </small>
                    </div>
                HTML;
            })
            ->editColumn('status', function ($row) {
                $status = strtolower($row->status ?? 'pending');
                [$badgeClass, $label, $icon] = match ($status) {
                    'scheduled' => ['badge bg-info-subtle text-info border border-info-subtle', 'Terjadwal', 'fa-calendar-check'],
                    'completed' => ['badge bg-success-subtle text-success border border-success-subtle', 'Selesai', 'fa-circle-check'],
                    'contacted' => ['badge bg-primary-subtle text-primary border border-primary-subtle', 'Dihubungi', 'fa-phone-volume'],
                    'cancelled' => ['badge bg-secondary-subtle text-secondary border border-secondary-subtle', 'Dibatalkan', 'fa-circle-xmark'],
                    default => ['badge bg-warning-subtle text-warning border border-warning-subtle', 'Menunggu', 'fa-hourglass-half'],
                };
                return "<span class='{$badgeClass} px-2 py-1 rounded-pill small'><i class='fa-solid {$icon} me-1'></i>{$label}</span>";
            })
            ->addColumn('action', function ($row) {
                return $this->renderActionButtons($row);
            })
            ->addIndexColumn()
            ->rawColumns(['school_name', 'package', 'demo_datetime', 'phone', 'status', 'action'])
            ->setRowId('id');
    }

    /**
     * Render tombol aksi (edit & delete) untuk satu baris.
     */
    protected function renderActionButtons($row): string
    {
        $title = e('Edit Data Demo ' . $row->school_name);

        $context = htmlspecialchars(
            json_encode([
                'executeDraw' => true,
                'tableId' => 'demo-request-table',
                'reloadDelay' => 500,
            ]),
            ENT_QUOTES,
            'UTF-8'
        );

        $editUrl = e(route('demo_request.e.destroy', $row->id));
        $deleteUrl = e(route('demo_request.destroy', $row));

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
                    data-submit='<button class="btn btn-primary" type="submit" form="formDemoRequest">Simpan Perubahan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'
                    title="Ubah Data">
                    <span class="material-symbols-outlined fs-6">edit</span>
                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-danger delete-button"
                    data-confirm-delete="true"
                    data-is_execute_drawer="true"
                    data-url="{$deleteUrl}"
                    title="Hapus Data">
                    <span class="material-symbols-outlined fs-6">delete</span>
                </button>
            </div>
        HTML;
    }

    /**
     * Get query source of DataTable.
     */
    public function query(DemoRequest $model): QueryBuilder
    {
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
             * Asesi -> asesi-table
             * Asesor -> asesor-table
             */
            ->setTableId('demo-request-table')

            ->columns($this->getColumns())

            /*
             * Server-side AJAX endpoint.
             */
            ->minifiedAjax(route('demo_request.index'))

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
                'responsive' => false,

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
        return 'DemoRequest_' . date('YmdHis');
    }
}
