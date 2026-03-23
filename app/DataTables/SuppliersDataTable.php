<?php

namespace App\DataTables;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SuppliersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Supplier $supplier) {
                return view('admin.supplier.datatables_actions', compact('supplier'))->render();
            })
            ->addColumn('status_badge', function (Supplier $supplier) {
                return view('admin.supplier.datatables_status', compact('supplier'))->render();
            })
            ->editColumn('contact_name', function (Supplier $supplier) {
                return $supplier->contact_name ?: '—';
            })
            ->editColumn('contact_email', function (Supplier $supplier) {
                return $supplier->contact_email ?: '—';
            })
            ->editColumn('contact_phone', function (Supplier $supplier) {
                return $supplier->contact_phone ?: '—';
            })
            ->editColumn('products_count', function (Supplier $supplier) {
                return number_format((int) $supplier->products_count);
            })
            ->rawColumns(['action', 'status_badge'])
            ->setRowId('supplier_id');
    }

    public function query(Supplier $model): QueryBuilder
    {
        $status = request('status', 'all');
        $query = $model->newQuery()->withCount('products');

        if ($status === 'trashed') {
            $query->onlyTrashed();
        } elseif ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('suppliersTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            ->selectStyleSingle()
            ->parameters([
                'dom' => "<'d-none'B><'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" .
                         "<'row'<'col-sm-12'tr>>" .
                         "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search suppliers...'
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
            Column::make('supplier_id')->title('ID'),
            Column::make('name')->title('Company Name'),
            Column::make('contact_name')->title('Contact'),
            Column::make('contact_email')->title('Email'),
            Column::make('contact_phone')->title('Phone'),
            Column::make('products_count')->title('Products')->searchable(false),
            Column::computed('status_badge')->title('Status')->addClass('text-center'),
            Column::computed('action')->title('Actions')
                  ->exportable(false)
                  ->printable(false)
                  ->width(140)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Suppliers_' . date('YmdHis');
    }
}
