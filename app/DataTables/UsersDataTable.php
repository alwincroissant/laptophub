<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (User $user) {
                return view('admin.user.datatables_actions', compact('user'))->render();
            })
            ->addColumn('status_badge', function (User $user) {
                return view('admin.user.datatables_status', compact('user'))->render();
            })
            ->addColumn('role_name', function (User $user) {
                return $user->role->role_name ?? 'N/A';
            })
            ->editColumn('contact_number', function(User $user) {
                return $user->contact_number ?: '-';
            })
            ->filterColumn('role_name', function($query, $keyword) {
                $query->whereHas('role', function($q) use($keyword) {
                    $q->where('role_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('status_badge', function($query, $keyword) {
                $val = trim(strtolower($keyword));
                if ($val === 'active') {
                    $query->where('is_active', true);
                } elseif ($val === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->rawColumns(['action', 'status_badge'])
            ->setRowId('user_id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()->with('role');

        $status = request('status', 'all');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $roleId = (int) request('role_id', 0);
        if ($roleId > 0) {
            $query->where('role_id', $roleId);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('usersTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0) // Order by user_id by default
            ->selectStyleSingle()
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
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
            Column::make('user_id')->title('ID'),
            Column::make('first_name')->title('First Name'),
            Column::make('last_name')->title('Last Name'),
            Column::make('email')->title('Email'),
            Column::make('role_name')->title('Role')->name('role_name'),
            Column::make('contact_number')->title('Contact'),
            Column::make('status_badge')->title('Status')
                  ->searchable(true)
                  ->orderable(false)
                  ->exportable(false)
                  ->printable(false),
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
        return 'Users_' . date('YmdHis');
    }
}
