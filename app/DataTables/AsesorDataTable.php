<?php

namespace App\DataTables;

use App\Models\Asesor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AsesorDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Asesor> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return (new EloquentDataTable($query))
        ->addColumn('action', function ($row) {
            return '
                <div class="d-flex justify-content-center gap-1">

                    <a
                        href="' . route('asesor.edit', $row->id) . '"
                        class="btn btn-sm btn-info"
                        title="Edit"
                    >
                        <span class="material-symbols-outlined">
                            edit
                        </span>
                    </a>

                    <button
                        type="button"
                        class="btn btn-sm btn-danger"
                        data-confirm-delete="true"
                        data-url="' . route('asesor.destroy', $row->id) . '"
                        title="Hapus"
                    >
                        <span class="material-symbols-outlined">
                            delete
                        </span>
                    </button>

                </div>
            ';
        })
        ->rawColumns(['action']) // ✅ WAJIB: izinkan tampilan HTML
        ->setRowId('id');
}

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Asesor>
     */
    public function query(Asesor $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('asesor-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('asesor.index'))
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'dom' => '<"row mb-3"' .
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
            ])
            ->buttons([
                Button::make('excel')
                    ->text('<span class="material-symbols-outlined align-middle me-1">download</span> Excel')
                     ->className('btn btn-success'),

                Button::make('csv')
                    ->text('<span class="material-symbols-outlined align-middle me-1">description</span> CSV')
                    ->className('btn btn-info'),

                Button::make('pdf')
                    ->text('<span class="material-symbols-outlined align-middle me-1">picture_as_pdf</span> PDF')
                    ->className('btn btn-danger'),

                Button::make('print')
                    ->text('<span class="material-symbols-outlined align-middle me-1">print</span> Print')
                    ->className('btn btn-primary'),

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
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')
                ->title('ID')
                ->width(80)
                ->addClass('text-center'),

            Column::make('name')
                ->title('Nama Asesor'),

            Column::computed('action')
                ->title('Aksi')
                ->width(100)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Asesor_' . date('YmdHis');
    }
}
