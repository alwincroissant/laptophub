@extends('layouts.admin')

@section('title', 'LaptopHub - User Management')
@section('active_nav', 'user')
@section('page_title', 'Users')
@section('page_subtitle', 'Manage user accounts, roles, and access status')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css"/>
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User List</h5>
            <div id="table-buttons"></div>
        </div>
        <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table mb-0 table-hover align-middle w-100', 'id' => 'usersTable']) !!}
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            // Create custom dropdown filters
            var statusFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Statuses</option><option value="Active">Active</option><option value="Inactive">Inactive</option></select>');
            var roleFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Roles</option>' + 
                @foreach($roles as $role)
                    '<option value="{{ $role->role_name }}">{{ $role->role_name }}</option>' +
                @endforeach
                '</select>');

            // Append them after DataTables has rendered
            setTimeout(function() {
                $('.dataTables_filter').addClass('d-flex justify-content-end align-items-center mb-3');
                $('.dataTables_filter label').addClass('mb-0 me-2');
                $('.dataTables_filter').append(roleFilter).append(statusFilter);

                var table = window.LaravelDataTables["usersTable"];
                table.buttons().container().appendTo('#table-buttons');

                // Bind events for precise column filtering via AJAX
                statusFilter.on('change', function() {
                    table.column(6).search($(this).val()).draw();
                });

                roleFilter.on('change', function() {
                    table.column(4).search($(this).val()).draw();
                });
            }, 300);
        });
    </script>
@endpush
