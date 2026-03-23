<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InventoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Product $item) {
                return view('admin.inventory.datatables_actions', compact('item'))->render();
            })
            ->addColumn('status_badge', function (Product $item) {
                return view('admin.inventory.datatables_status', compact('item'))->render();
            })
            ->editColumn('image_url', function (Product $item) {
                return view('admin.inventory.datatables_image', compact('item'))->render();
            })
            ->editColumn('price', function (Product $item) {
                return 'P' . number_format((float) $item->price, 2);
            })
            ->editColumn('stock_qty', function (Product $item) {
                return number_format((int) $item->stock_qty);
            })
            ->editColumn('low_stock_threshold', function (Product $item) {
                return number_format((int) $item->low_stock_threshold);
            })
            ->editColumn('updated_at', function (Product $item) {
                return optional($item->updated_at)->format('M d, Y h:i A') ?? '—';
            })
            ->filterColumn('category_name', function($query, $keyword) {
                $query->where('categories.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('brand_name', function($query, $keyword) {
                $query->where('brands.name', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action', 'status_badge', 'image_url'])
            ->setRowId('product_id');
    }

    public function query(Product $model): QueryBuilder
    {
        $status = request('status', 'all');
        
        $query = $model->newQuery()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.category_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.brand_id')
            ->select([
                'products.product_id',
                'products.name',
                'products.image_url',
                'products.stock_qty',
                'products.low_stock_threshold',
                'products.price',
                'products.is_archived',
                'products.updated_at',
                'categories.name as category_name',
                'brands.name as brand_name',
            ]);

        if ($status === 'low-stock') {
            $query->where('products.is_archived', false)
                ->whereColumn('products.stock_qty', '<=', 'products.low_stock_threshold')
                ->where('products.stock_qty', '>', 0);
        } elseif ($status === 'out-of-stock') {
            $query->where('products.is_archived', false)
                ->where('products.stock_qty', 0);
        } elseif ($status === 'archived') {
            $query->where('products.is_archived', true);
        } elseif ($status === 'active') {
            $query->where('products.is_archived', false);
        }

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('inventoryTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'dom' => "<'d-none'B><'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" .
                         "<'row'<'col-sm-12'tr>>" .
                         "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search inventory...'
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

    public function getColumns(): array
    {
        return [
            Column::make('product_id')->title('ID'),
            Column::computed('image_url')->title('Image')->exportable(false)->printable(false)->searchable(false),
            Column::make('name')->title('Item'),
            Column::make('category_name')->title('Category')->name('categories.name'),
            Column::make('brand_name')->title('Brand')->name('brands.name'),
            Column::make('price')->title('Price')->addClass('text-end')->searchable(false),
            Column::make('stock_qty')->title('Stock')->addClass('text-end')->searchable(false),
            Column::make('low_stock_threshold')->title('Threshold')->addClass('text-end')->searchable(false),
            Column::computed('status_badge')->title('Status')->addClass('text-center'),
            Column::make('updated_at')->title('Updated On')->searchable(false),
            Column::computed('action')->title('Actions')
                  ->exportable(false)
                  ->printable(false)
                  ->width(80)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Inventory_' . date('YmdHis');
    }
}
