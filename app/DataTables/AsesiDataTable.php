<?php

namespace App\DataTables;

use App\Models\Asesi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AsesiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Asesi> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return (new EloquentDataTable($query))
        ->addColumn('action', function ($row) {
            return '
                <div class="d-flex justify-content-center gap-1">

                    <button
                        data-url="' . route('asesi.edit', $row->id) . '"
                        class="btn btn-sm btn-info edit"
                        title="Edit"
                    >
                        <span class="material-symbols-outlined">
                            edit
                        </span>
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-danger delete"
                        data-confirm-delete="true"
                        data-url="' . route('asesi.destroy', $row->id) . '"
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
     * @return QueryBuilder<Asesi>
     */
    public function query(Asesi $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('asesi-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
            Button::make('csv'),
            Button::make('pdf'),
            Button::make('print'),
            Button::make([
                    'text' => '<span class="material-symbols-outlined align-middle">refresh</span>',
                    'className' => 'btn btn-secondary',
                    'action' => 'function (e, dt, node, config) {
                        dt.ajax.reload(null, false);
                    }',
                ])->className('btn btn-secondary')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::computed('action') 
                  ->width(100)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Asesi_' . date('YmdHis');
    }
}
