<?php

namespace App\DataTables;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReviewsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('title', function (Review $review) {
                return view('admin.review.datatables_review', compact('review'))->render();
            })
            ->editColumn('product.name', function (Review $review) {
                return view('admin.review.datatables_product', compact('review'))->render();
            })
            ->editColumn('user.full_name', function (Review $review) {
                return view('admin.review.datatables_customer', compact('review'))->render();
            })
            ->editColumn('orderItem.order_id', function (Review $review) {
                return view('admin.review.datatables_order', compact('review'))->render();
            })
            ->editColumn('rating', function (Review $review) {
                return view('admin.review.datatables_rating', compact('review'))->render();
            })
            ->editColumn('is_visible', function (Review $review) {
                return view('admin.review.datatables_status', compact('review'))->render();
            })
            ->addColumn('action', function (Review $review) {
                return view('admin.review.datatables_actions', compact('review'))->render();
            })
            ->editColumn('created_at', function(Review $review) {
                return optional($review->created_at)->format('M d, Y h:i A') ?? 'N/A';
            })
            ->filterColumn('title', function($query, $keyword) {
                $query->where(function($q) use($keyword) {
                    $q->where('reviews.title', 'like', "%{$keyword}%")
                      ->orWhere('reviews.body', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('rating', function($query, $keyword) {
                $val = trim($keyword);
                if (str_contains($val, '★')) {
                    $stars = substr_count($val, '★');
                    if ($stars > 0 && $stars <= 5) {
                        $query->where('reviews.rating', $stars);
                    }
                } else {
                    $query->where('reviews.rating', 'like', "%{$val}%");
                }
            })
            ->filterColumn('is_visible', function($query, $keyword) {
                $val = trim(strtolower($keyword));
                if ($val === 'visible') {
                    $query->where('reviews.is_visible', true);
                } elseif ($val === 'hidden') {
                    $query->where('reviews.is_visible', false);
                }
            })
            ->filterColumn('user.full_name', function($query, $keyword) {
                $query->whereHas('user', function($q) use($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                      ->orWhere('last_name', 'like', "%{$keyword}%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$keyword}%"]);
                });
            })
            ->rawColumns(['title', 'product.name', 'user.full_name', 'orderItem.order_id', 'rating', 'is_visible', 'action'])
            ->setRowId('review_id');
    }

    public function query(Review $model): QueryBuilder
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $model->newQuery()
            ->with([
                'user:user_id,first_name,last_name,email',
                'product:product_id,name',
                'orderItem:order_item_id,order_id',
                'orderItem.order:order_id,placed_at',
            ]);

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('reviewsTable')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(6, 'desc') // created_at sits at index 6
                    ->parameters([
                        'language' => [
                            'search' => '',
                            'searchPlaceholder' => 'Search reviews...'
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
            Column::make('title')->title('Review')->width(280),
            Column::make('product.name')->title('Product'),
            Column::make('user.full_name')->title('Customer'),
            Column::make('orderItem.order_id')->title('Order')->searchable(false),
            Column::make('rating')->title('Rating')->width(100),
            Column::make('is_visible')->title('Status')->searchable(true)->orderable(false),
            Column::make('created_at')->title('Created')->searchable(false),
            Column::computed('action')->title('Actions')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }
}
