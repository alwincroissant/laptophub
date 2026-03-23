<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Product $product) {
                return view('admin.product.datatables_actions', compact('product'))->render();
            })
            ->addColumn('status_badge', function (Product $product) {
                return view('admin.product.datatables_status', compact('product'))->render();
            })
            ->addColumn('image', function (Product $product) {
                return view('admin.product.datatables_image', compact('product'))->render();
            })
            ->editColumn('price', function (Product $product) {
                return '₱' . number_format((float) $product->price, 2);
            })
            ->editColumn('stock_qty', function (Product $product) {
                return number_format($product->stock_qty);
            })
            ->editColumn('low_stock_threshold', function (Product $product) {
                return number_format($product->low_stock_threshold);
            })
            ->addColumn('category_name', function (Product $product) {
                return $product->category->name ?? '—';
            })
            ->addColumn('brand_name', function (Product $product) {
                return $product->brand->name ?? '—';
            })
            ->editColumn('updated_at', function (Product $product) {
                return optional($product->updated_at)->format('M d, Y h:i A') ?? '—';
            })
            ->filterColumn('category_name', function($query, $keyword) {
                $query->whereHas('category', function($q) use($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('brand_name', function($query, $keyword) {
                $query->whereHas('brand', function($q) use($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action', 'status_badge', 'image'])
            ->setRowId('product_id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $model->newQuery()->withTrashed()->with(['category', 'brand']);

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('productsTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc') // By default order by ID desc
            ->selectStyleSingle()
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search products...'
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
            Column::make('product_id')->title('ID'),
            Column::computed('image')->title('Image')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('name')->title('Product'),
            Column::make('category_name')->title('Category')->name('category_name'),
            Column::make('brand_name')->title('Brand')->name('brand_name'),
            Column::make('price')->title('Price')->addClass('text-end'),
            Column::make('stock_qty')->title('Stock')->addClass('text-end'),
            Column::make('low_stock_threshold')->title('Threshold')->addClass('text-end'),
            Column::computed('status_badge')->title('Status')
                  ->exportable(false)
                  ->printable(false),
            Column::make('updated_at')->title('Last Updated'),
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
        return 'Products_' . date('YmdHis');
    }
}
