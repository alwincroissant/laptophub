<?php

namespace App\DataTables;

use App\Models\RestockTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RestocksDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('type_badge', function (RestockTransaction $restock) {
                return view('admin.restock.datatables_type', compact('restock'))->render();
            })
            ->editColumn('restocked_at', function (RestockTransaction $restock) {
                return $restock->restocked_at->format('M d, Y') . '<br><span class="text-muted" style="font-size:.75rem">' . $restock->restocked_at->format('h:i A') . '</span>';
            })
            ->editColumn('product.name', function (RestockTransaction $restock) {
                $productName = optional($restock->product)->name ?? 'Unknown';
                if ($restock->notes) {
                    $productName .= '<br><div class="text-muted" style="font-size:.75rem; max-width: 15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="' . htmlspecialchars((string) $restock->notes) . '">' . htmlspecialchars((string) $restock->notes) . '</div>';
                }
                return $productName;
            })
            ->editColumn('supplier.name', function (RestockTransaction $restock) {
                return optional($restock->supplier)->name ?? 'Unknown';
            })
            ->addColumn('restocked_by', function (RestockTransaction $restock) {
                return view('admin.restock.datatables_manager', compact('restock'))->render();
            })
            ->editColumn('quantity_added', function (RestockTransaction $restock) {
                if ($restock->quantity_added < 0) {
                    return '<span class="badge bg-danger" style="font-size:.8rem;">' . join('', array_map('htmlspecialchars', [(string)$restock->quantity_added])) . '</span>';
                } else {
                    return '<span class="badge bg-success" style="font-size:.8rem;">+' . join('', array_map('htmlspecialchars', [(string)$restock->quantity_added])) . '</span>';
                }
            })
            ->editColumn('unit_cost', function (RestockTransaction $restock) {
                return '₱' . number_format((float) $restock->unit_cost, 2);
            })
            ->addColumn('total_cost', function (RestockTransaction $restock) {
                $val = (float) $restock->unit_cost * (int) $restock->quantity_added;
                if ($restock->quantity_added < 0) {
                    return '<span class="text-danger fw-bold">(₱' . number_format(abs($val), 2) . ')</span>';
                } else {
                    return '<span class="text-success fw-bold">₱' . number_format($val, 2) . '</span>';
                }
            })
            ->filterColumn('product.name', function($query, $keyword) {
                $query->whereHas('product', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('supplier.name', function($query, $keyword) {
                $query->whereHas('supplier', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['type_badge', 'restocked_at', 'product.name', 'restocked_by', 'quantity_added', 'total_cost'])
            ->setRowId('transaction_id');
    }

    public function query(RestockTransaction $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['product', 'supplier', 'manager']);

        if (request()->filled('start_date')) {
            $query->whereDate('restocked_at', '>=', request('start_date'));
        }
        if (request()->filled('end_date')) {
            $query->whereDate('restocked_at', '<=', request('end_date'));
        }
        if (request()->filled('product_id')) {
            $query->where('restock_transactions.product_id', request('product_id'));
        }
        if (request()->filled('supplier_id')) {
            $query->where('restock_transactions.supplier_id', request('supplier_id'));
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('restocksTable')
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
                    'searchPlaceholder' => 'Search restocks...'
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
            Column::make('restocked_at')->title('Date')->width(100),
            Column::computed('type_badge')->title('Type')->addClass('text-center'),
            Column::make('product.name')->title('Product')->name('product.name'),
            Column::make('supplier.name')->title('Supplier')->name('supplier.name'),
            Column::computed('restocked_by')->title('Restocked By'),
            Column::make('quantity_added')->title('Qty')->addClass('text-end')->searchable(false),
            Column::make('unit_cost')->title('Unit Cost')->addClass('text-end text-muted')->searchable(false),
            Column::computed('total_cost')->title('Total Cost')->addClass('text-end')->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Restocks_' . date('YmdHis');
    }
}
