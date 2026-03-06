@extends('layouts.admin')

@section('title', 'LaptopHub - Admin Dashboard')
@section('active_nav', 'dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', now()->format('l, d F Y').'  Live operational overview')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.product.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
@endsection

@section('admin_content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card red">
                <i class="bi bi-bag icon"></i>
                <div class="label">Total Orders</div>
                <div class="value">{{ number_format($totalOrders) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card blue">
                <i class="bi bi-currency-dollar icon"></i>
                <div class="label">Total Revenue</div>
                <div class="value">P{{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card gold">
                <i class="bi bi-people icon"></i>
                <div class="label">Active Users</div>
                <div class="value">{{ number_format($activeUsers) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card green">
                <i class="bi bi-box-seam icon"></i>
                <div class="label">Active Products</div>
                <div class="value">{{ number_format($activeProducts) }}</div>
                <div class="change" style="color:var(--accent)"><i class="bi bi-exclamation-circle me-1"></i>{{ number_format($lowStockCount) }} low stock</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
            <div class="table-card">
                <div class="card-header">
                    <h5>Recent Orders</h5>
                    <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th class="text-end">Items</th>
                            <th class="text-end">Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentOrders as $order)
                            @php
                                $statusName = strtolower((string) ($order->status->status_name ?? 'pending'));
                                $statusClass = match ($statusName) {
                                    'pending' => 'badge-pending',
                                    'processing' => 'badge-processing',
                                    'shipped' => 'badge-shipped',
                                    'delivered' => 'badge-delivered',
                                    'cancelled' => 'badge-cancelled',
                                    default => 'badge-archived',
                                };
                                $itemCount = (int) $order->items->sum('quantity');
                                $orderTotal = (float) $order->items->sum(function ($item) {
                                    return (float) $item->unit_price * (int) $item->quantity;
                                });
                            @endphp
                            <tr>
                                <td><strong>#{{ $order->order_id }}</strong></td>
                                <td>{{ $order->user->full_name ?? 'Unknown' }}</td>
                                <td class="text-end">{{ number_format($itemCount) }}</td>
                                <td class="text-end">P{{ number_format($orderTotal, 2) }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $order->paymentMethod->method_name ?? 'N/A' }}</span></td>
                                <td><span class="status-badge {{ $statusClass }}">{{ $order->status->status_name ?? 'Unknown' }}</span></td>
                                <td><a href="{{ route('admin.order.show', $order->order_id) }}" style="font-size:.75rem;color:var(--accent2)">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">No recent orders found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="section-title">Quick Actions</div>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <a href="{{ route('admin.product.create') }}" class="quick-btn">
                        <i class="bi bi-laptop"></i>
                        Add Product
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.user.create') }}" class="quick-btn">
                        <i class="bi bi-person-plus"></i>
                        Add User
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.supplier.create') }}" class="quick-btn">
                        <i class="bi bi-truck"></i>
                        Add Supplier
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.category.create') }}" class="quick-btn">
                        <i class="bi bi-tags"></i>
                        New Category
                    </a>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header">
                    <h5>Order Status Breakdown</h5>
                </div>
                <div class="p-3">
                    @php
                        $totalForBreakdown = max(1, (int) $statusBreakdown->sum('total'));
                    @endphp
                    @forelse($statusBreakdown as $status)
                        @php
                            $count = (int) $status->total;
                            $percent = $totalForBreakdown > 0 ? max(2, (int) round(($count / $totalForBreakdown) * 100)) : 2;
                            $statusName = strtolower((string) $status->status_name);
                            $barColor = match ($statusName) {
                                'delivered' => '#2f9c5a',
                                'shipped' => 'var(--accent2)',
                                'processing' => '#c89a2f',
                                'pending' => '#c89a2f',
                                'cancelled' => 'var(--accent)',
                                default => '#7a7670',
                            };
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ $status->status_name }}</small>
                            <small><strong>{{ number_format($count) }}</strong></small>
                        </div>
                        <div class="progress mb-3" style="height:5px;border-radius:2px">
                            <div class="progress-bar" style="width:{{ $percent }}%;background:{{ $barColor }}"></div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No order status data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="table-card h-100">
                <div class="card-header">
                    <h5><i class="bi bi-exclamation-triangle me-1" style="color:var(--accent)"></i>Low Stock Alert</h5>
                    <a href="{{ route('admin.inventory.index', ['status' => 'low-stock']) }}" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem">Manage</a>
                </div>
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-end">Qty</th>
                        <th>Level</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($lowStockProducts as $product)
                        @php
                            $threshold = max(1, (int) $product->low_stock_threshold);
                            $level = max(2, min(100, (int) round(((int) $product->stock_qty / $threshold) * 100)));
                            $stockClass = (int) $product->stock_qty <= 0 ? 'stock-none' : 'stock-low';
                        @endphp
                        <tr>
                            <td>
                                <div style="font-size:.8rem;font-weight:500">{{ $product->name }}</div>
                                <div style="font-size:.7rem;color:var(--muted)">{{ $product->category_name ?: 'Uncategorized' }}</div>
                            </td>
                            <td class="text-end"><strong>{{ number_format((int) $product->stock_qty) }}</strong></td>
                            <td>
                                <div class="stock-bar-wrap"><div class="stock-bar {{ $stockClass }}" style="width:{{ $level }}%"></div></div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">No low stock products.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="table-card h-100">
                <div class="card-header">
                    <h5>Recent Activity</h5>
                </div>
                <div>
                    @forelse($recentActivities as $activity)
                        @php
                            $statusName = strtolower((string) ($activity->status->status_name ?? 'updated'));
                            $dotColor = match ($statusName) {
                                'delivered' => '#2f9c5a',
                                'shipped' => 'var(--accent2)',
                                'processing' => '#c89a2f',
                                'pending' => '#c89a2f',
                                'cancelled' => 'var(--accent)',
                                default => '#7a7670',
                            };
                        @endphp
                        <div class="activity-item">
                            <div class="activity-dot" style="background:{{ $dotColor }}"></div>
                            <div class="flex-grow-1">
                                <div class="text">
                                    Order <strong>#{{ $activity->order_id }}</strong>
                                    marked as <em>{{ $activity->status->status_name ?? 'Updated' }}</em>
                                    by {{ $activity->changedBy->full_name ?? 'System' }}
                                </div>
                                <div class="time">{{ optional($activity->changed_at)->diffForHumans() ?? 'N/A' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-muted">No recent activity logs.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="table-card h-100">
                <div class="card-header">
                    <h5>Top Reviewed Products</h5>
                </div>
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Avg</th>
                        <th>Reviews</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($topReviewedProducts as $item)
                        <tr>
                            <td>
                                <div style="font-size:.8rem;font-weight:500">{{ $item->product_name }}</div>
                            </td>
                            <td><span class="stars">*</span> <strong>{{ number_format((float) $item->avg_rating, 1) }}</strong></td>
                            <td>{{ number_format((int) $item->total_reviews) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">No review data yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="table-card">
                <div class="card-header">
                    <h5>Recent Users</h5>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem">Manage Users</a>
                </div>
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentUsers as $user)
                        <tr>
                            <td>{{ $user->full_name }}</td>
                            <td style="font-size:.75rem;color:var(--muted)">{{ $user->email }}</td>
                            <td><span class="badge bg-light text-dark border" style="font-size:.65rem">{{ $user->role->role_name ?? 'N/A' }}</span></td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge" style="background:#d1e7dd;color:#0a3622;font-size:.65rem">Active</span>
                                @else
                                    <span class="badge" style="background:#f8d7da;color:#842029;font-size:.65rem">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">No users found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="table-card">
                <div class="card-header">
                    <h5>Active Suppliers</h5>
                    <a href="{{ route('admin.supplier.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem">All Suppliers</a>
                </div>
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Contact</th>
                        <th class="text-end">Products</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($activeSuppliers as $supplier)
                        <tr>
                            <td><strong style="font-size:.83rem">{{ $supplier->name }}</strong></td>
                            <td style="font-size:.75rem;color:var(--muted)">{{ $supplier->contact_name ?: '-' }}</td>
                            <td class="text-end">{{ number_format((int) $supplier->products_count) }}</td>
                            <td><span class="badge" style="background:#d1e7dd;color:#0a3622;font-size:.65rem">Active</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">No active suppliers found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
