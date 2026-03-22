<?php

namespace App\DataTables;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BrandsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Brand $brand) {
                return view('admin.brand.datatables_actions', compact('brand'))->render();
            })
            ->addColumn('status_badge', function (Brand $brand) {
                return view('admin.brand.datatables_status', compact('brand'))->render();
            })
            ->editColumn('description', function (Brand $brand) {
                return $brand->description ?: '—';
            })
            ->editColumn('created_at', function (Brand $brand) {
                return optional($brand->created_at)->format('M d, Y h:i A') ?? '—';
            })
            ->rawColumns(['action', 'status_badge'])
            ->setRowId('brand_id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Brand $model): QueryBuilder
    {
        $status = request('status', 'all');
        $query = $model->newQuery();

        if ($status === 'trashed') {
            $query->onlyTrashed();
        } elseif ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('brandsTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search brands...'
                ]
            ])
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
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
            Column::make('brand_id')->title('ID'),
            Column::make('name')->title('Name'),
            Column::make('description')->title('Description')->orderable(false),
            Column::computed('status_badge')->title('Status')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
            Column::make('created_at')->title('Added On')->searchable(false),
            Column::computed('action')->title('Actions')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Brands_' . date('YmdHis');
    }
}
