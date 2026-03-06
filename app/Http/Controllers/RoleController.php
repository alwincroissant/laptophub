<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = Role::query()->withCount('users');

        if ($search !== '') {
            $query->where('role_name', 'like', "%{$search}%");
        }

        $roles = $query->orderBy('role_name')->paginate(12)->withQueryString();

        return view('admin.role.index', [
            'roles' => $roles,
            'search' => $search,
            'protectedRoles' => $this->protectedRoleNames(),
        ]);
    }

    public function create()
    {
        return view('admin.role.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => ['required', 'string', 'max:30', 'unique:roles,role_name'],
        ]);

        Role::create(['role_name' => trim($validated['role_name'])]);

        return redirect()->route('admin.role.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('admin.role.edit', [
            'role' => $role,
            'isProtected' => $this->isProtectedRole($role->role_name),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        if ($this->isProtectedRole($role->role_name)) {
            return redirect()->route('admin.role.index')->with('error', 'This system role cannot be renamed.');
        }

        $validated = $request->validate([
            'role_name' => [
                'required',
                'string',
                'max:30',
                Rule::unique('roles', 'role_name')->ignore($role->role_id, 'role_id'),
            ],
        ]);

        $role->update(['role_name' => trim($validated['role_name'])]);

        return redirect()->route('admin.role.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($this->isProtectedRole($role->role_name)) {
            return redirect()->route('admin.role.index')->with('error', 'This system role cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return redirect()->route('admin.role.index')->with('error', 'Cannot delete a role that is assigned to users.');
        }

        $role->delete();

        return redirect()->route('admin.role.index')->with('success', 'Role deleted successfully.');
    }

    private function protectedRoleNames(): array
    {
        return ['Admin', 'InventoryManager', 'Customer'];
    }

    private function isProtectedRole(string $roleName): bool
    {
        return in_array($roleName, $this->protectedRoleNames(), true);
    }
}
