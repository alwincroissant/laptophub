<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Order $order) {
                return view('admin.order.datatables_actions', compact('order'))->render();
            })
            ->addColumn('status_badge', function (Order $order) {
                return view('admin.order.datatables_status', compact('order'))->render();
            })
            ->editColumn('subtotal', function (Order $order) {
                return '₱' . number_format((float) $order->subtotal, 2);
            })
            ->editColumn('placed_at', function (Order $order) {
                return optional($order->placed_at)->format('M d, Y h:i A') ?? '—';
            })
            ->filterColumn('customer_name', function($query, $keyword) {
                $query->whereRaw("TRIM(CONCAT(users.first_name, ' ', users.last_name)) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('customer_email', function($query, $keyword) {
                $query->where('users.email', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action', 'status_badge'])
            ->setRowId('order_id');
    }

    public function query(Order $model): QueryBuilder
    {
        $statusId = (int) request('status_id', 0);
        
        $query = $model->newQuery()
            ->leftJoin('users', 'orders.user_id', '=', 'users.user_id')
            ->leftJoin('order_statuses', 'orders.status_id', '=', 'order_statuses.status_id')
            ->leftJoin('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.payment_method_id')
            ->leftJoin('order_items', 'orders.order_id', '=', 'order_items.order_id')
            ->select([
                'orders.order_id',
                'orders.status_id',
                'orders.placed_at',
                'orders.updated_at',
                DB::raw("TRIM(CONCAT(users.first_name, ' ', users.last_name)) as customer_name"),
                'users.email as customer_email',
                'order_statuses.status_name',
                'payment_methods.method_name as payment_method',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as item_count'),
                DB::raw('COALESCE(SUM(order_items.quantity * order_items.unit_price), 0) as subtotal'),
            ])
            ->groupBy([
                'orders.order_id',
                'orders.status_id',
                'orders.placed_at',
                'orders.updated_at',
                'users.first_name',
                'users.last_name',
                'users.email',
                'order_statuses.status_name',
                'payment_methods.method_name',
            ]);

        if ($statusId > 0) {
            $query->where('orders.status_id', $statusId);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('ordersTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search orders...'
                ]
            ])
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('print')
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('order_id')->title('Order #'),
            Column::make('customer_name')->title('Customer')->name('customer_name'),
            Column::make('customer_email')->title('Email')->name('customer_email'),
            Column::make('item_count')->title('Items')->searchable(false),
            Column::make('subtotal')->title('Subtotal')->searchable(false)->addClass('text-end'),
            Column::computed('status_badge')->title('Status')->addClass('text-center'),
            Column::make('placed_at')->title('Placed On'),
            Column::make('payment_method')->title('Payment')->name('payment_methods.method_name'),
            Column::computed('action')->title('Actions')
                  ->exportable(false)
                  ->printable(false)
                  ->width(80)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }
}
