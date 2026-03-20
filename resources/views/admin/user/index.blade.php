@extends('layouts.admin')

@section('title', 'LaptopHub - User Management')
@section('active_nav', 'user')
@section('page_title', 'Users')
@section('page_subtitle', 'Manage user accounts, roles, and access status')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.user.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add User</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif



    <div class="table-card">
        <div class="card-header">
            <h5>User List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($users->count()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 table-hover align-middle" id="usersTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>#{{ $user->user_id }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->role_name ?? 'N/A' }}</td>
                        <td>{{ $user->contact_number ?: '-' }}</td>
                        <td>
                            @if ($user->is_active)
                                <span class="status-badge badge-active">Active</span>
                            @else
                                <span class="status-badge badge-archived">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if ($user->is_active)
                                    <form method="POST" action="{{ route('admin.user.deactivate', $user) }}" onsubmit="return confirm('Deactivate this user account?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Deactivate" aria-label="Deactivate">
                                            <i class="bi bi-person-dash"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.user.activate', $user) }}" onsubmit="return confirm('Activate this user account?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Activate" aria-label="Activate">
                                            <i class="bi bi-person-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#usersTable').DataTable({
            "pageLength": 10,
            "order": [],
            "language": {
                "search": "",
                "searchPlaceholder": "Search users..."
            }
        });

        // Create custom dropdown filters
        var statusFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Statuses</option><option value="Active">Active</option><option value="Inactive">Inactive</option></select>');
        var roleFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Roles</option>' + 
            @foreach($roles as $role)
                '<option value="{{ $role->role_name }}">{{ $role->role_name }}</option>' +
            @endforeach
            '</select>');

        // Append them next to the search input
        $('.dataTables_filter').addClass('d-flex justify-content-end align-items-center mb-3');
        $('.dataTables_filter label').addClass('mb-0 me-2');
        $('.dataTables_filter').append(roleFilter).append(statusFilter);

        // Bind events for exact-match column filtering
        statusFilter.on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            // Column 5 is Status
            table.column(5).search(val ? '^' + val + '$' : '', true, false).draw();
        });

        roleFilter.on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            // Column 3 is Role
            table.column(3).search(val ? '^' + val + '$' : '', true, false).draw();
        });
    });
</script>
@endpush
