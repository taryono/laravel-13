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
        ->addColumn('action', function($row) {
            return '
                <a href="'.route('asesor.edit', $row->id).'" class="btn btn-sm btn-info">
                    <span class="material-symbols">edit</span>
                </a>
                <button class="btn btn-sm btn-danger" 
                        data-confirm-delete="true" 
                        data-url="'.route('asesor.destroy', $row->id).'">
                    <span class="material-symbols">delete</span>
                </button>
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
                        'dom' => 'frtip',
                    ])
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
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
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
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
