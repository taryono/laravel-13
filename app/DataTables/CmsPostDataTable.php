<?php

namespace App\DataTables;

use App\Enums\PostStatus;
use Models\CmsPost;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CmsPostDataTable extends DataTable
{
    protected array $columns = [
        'featured_image' => ['title' => 'Cover', 'name' => 'featured_image', 'orderable' => false, 'searchable' => false],
        'title' => ['title' => 'Judul', 'name' => 'title'],
        'category.name' => ['title' => 'Kategori', 'name' => 'category.name'],
        'author.name' => ['title' => 'Penulis', 'name' => 'author.name'],
        'status' => ['title' => 'Status', 'name' => 'status'],
        'published_at' => ['title' => 'Tgl Terbit', 'name' => 'published_at'],
        'view_count' => ['title' => 'Views', 'name' => 'view_count'],
    ];

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('featured_image', function ($row) {
                if (!$row->featured_image_url) {
                    return '<div class="bg-light border rounded d-inline-flex align-items-center justify-content-center text-muted" style="width:48px;height:36px;"><span class="material-symbols-outlined fs-6">image</span></div>';
                }
                return '<img src="' . e($row->featured_image_url) . '" class="rounded object-fit-cover border" style="width:48px;height:36px;" alt="Cover">';
            })
            ->editColumn('title', function ($row) {
                $featuredBadge = $row->is_featured
                    ? ' <span class="badge bg-warning text-dark ms-1">Featured</span>'
                    : '';
                return '<div class="fw-semibold text-dark">' . e($row->title) . $featuredBadge . '</div>'
                    . '<small class="text-muted">/' . e($row->slug) . '</small>';
            })
            ->editColumn('category.name', fn($row) => e($row->category?->name ?? 'Tanpa Kategori'))
            ->editColumn('author.name', fn($row) => e($row->author?->name ?? '-'))
            ->editColumn('status', function ($row) {
                $status = $row->status instanceof PostStatus ? $row->status : PostStatus::tryFrom((string) $row->status);
                return $status
                    ? '<span class="badge ' . $status->badgeClass() . '">' . $status->label() . '</span>'
                    : '-';
            })
            ->editColumn('published_at', fn($row) => $row->published_at?->format('d-m-Y H:i') ?? '-')
            ->editColumn('view_count', fn($row) => number_format((int) $row->view_count, 0, ',', '.'))
            ->addColumn('action', fn($row) => $this->renderActionButtons($row))
            ->addIndexColumn()
            ->rawColumns(['featured_image', 'title', 'status', 'action'])
            ->setRowId('id');
    }

    protected function renderActionButtons($row): string
    {
        $title = e('Edit Artikel: ' . $row->title);
        $editUrl = e(route('cms_post.edit', $row));
        $delUrl = e(route('cms_post.destroy', $row));

        $context = htmlspecialchars(json_encode([
            'executeDraw' => true,
            'tableId' => 'cms-post-table',
            'reloadDelay' => 500,
        ]), ENT_QUOTES, 'UTF-8');

        return <<<HTML
            <div class="d-flex justify-content-center gap-1">
                <button type="button" class="btn btn-sm btn-primary"
                    data-toggle="ajax-modal" data-size="xl" data-method="GET"
                    data-title="{$title}" data-url="{$editUrl}" data-datatable-context='{$context}'
                    data-submit='<button class="btn btn-primary modal-submit" type="button" data-is_execute_drawer="true" data-datatable-id="cms-post-table">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                    <span class="material-symbols-outlined">edit</span>
                </button>
                <button type="button" class="btn btn-sm btn-danger delete-button"
                    data-confirm-delete="true" data-is_execute_drawer="true"
                    data-url="{$delUrl}" title="Hapus">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
        HTML;
    }

    public function query(CmsPost $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('cms_posts.*')
            ->with([
                'author:id,name',
                'category:id,name',
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('cms-post-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('cms_post.index'))
            ->orderBy(6, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'dom' => '<"row mb-3"<"col-md-6 d-flex align-items-center"B><"col-md-6"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6 d-flex justify-content-md-end"p>>',
                'autoWidth' => false,
                'responsive' => true,
                'processing' => true,
                'serverSide' => true,
            ])
            ->buttons([
                Button::make('excel')->className('btn btn-success btn-sm'),
                Button::make('pdf')->className('btn btn-danger btn-sm'),
                Button::make('print')->className('btn btn-primary btn-sm'),
                Button::make([
                    'text' => '<span class="material-symbols-outlined align-middle">refresh</span>',
                    'action' => 'function (e, dt) { dt.ajax.reload(null, false); }',
                ])->className('btn btn-secondary btn-sm'),
            ]);
    }

    protected function getColumns(): array
    {
        return collect($this->columns)
            ->map(function (array $cfg, string $dataKey) {
                $col = Column::make($dataKey)->name($cfg['name'])->title($cfg['title']);
                if (isset($cfg['orderable'])) {
                    $col->orderable($cfg['orderable']);
                }
                if (isset($cfg['searchable'])) {
                    $col->searchable($cfg['searchable']);
                }
                return $col;
            })
            ->prepend(Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false)->width(60)->addClass('text-center'))
            ->push(Column::computed('action')->exportable(false)->printable(false)->width(110)->addClass('text-center')->title('Aksi'))
            ->values()
            ->all();
    }

    protected function filename(): string
    {
        return 'CmsPosts_' . date('YmdHis');
    }
}