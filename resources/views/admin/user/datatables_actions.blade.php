<div class="d-flex gap-2 justify-content-center">
    <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
        <i class="bi bi-pencil-square"></i>
    </a>
    @if ($user->is_active)
        <form method="POST" action="{{ route('admin.user.deactivate', $user) }}" onsubmit="return confirm('Deactivate this user account?')" class="m-0">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-warning" title="Deactivate" aria-label="Deactivate">
                <i class="bi bi-person-dash"></i>
            </button>
        </form>
    @else
        <form method="POST" action="{{ route('admin.user.activate', $user) }}" onsubmit="return confirm('Activate this user account?')" class="m-0">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-success" title="Activate" aria-label="Activate">
                <i class="bi bi-person-check"></i>
            </button>
        </form>
    @endif
</div>
