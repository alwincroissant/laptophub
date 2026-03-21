<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = (string) $request->input('status', 'all');
        $roleId = (int) $request->input('role_id', 0);

        $roles = Role::orderBy('role_name')->get(['role_id', 'role_name']);

        $query = User::query()->with('role');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($roleId > 0) {
            $query->where('role_id', $roleId);
        }

        $users = $query->orderBy('full_name')->get();

        return view('admin.user.index', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'status' => $status,
            'roleId' => $roleId,
        ]);
    }

    public function create()
    {
        $roles = Role::orderBy('role_name')->get(['role_id', 'role_name']);

        return view('admin.user.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,role_id'],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address_label' => ['nullable', 'string', 'max:50'],
            'recipient_name' => ['nullable', 'string', 'max:100'],
            'address_phone' => ['nullable', 'string', 'max:20'],
            'region' => ['nullable', 'string', 'max:100', 'required_with:street_address'],
            'city' => ['nullable', 'string', 'max:100', 'required_with:street_address'],
            'postal_code' => ['nullable', 'string', 'max:10', 'required_with:street_address'],
            'street_address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'role_id' => (int) $validated['role_id'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'] ?? null,
                'password_hash' => Hash::make($validated['password']),
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->upsertDefaultAddress($user, $validated);
        });

        return redirect()->route('admin.user.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $user->load('defaultAddress');
        $roles = Role::orderBy('role_name')->get(['role_id', 'role_name']);

        return view('admin.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'defaultAddress' => $user->defaultAddress,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,role_id'],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address_label' => ['nullable', 'string', 'max:50'],
            'recipient_name' => ['nullable', 'string', 'max:100'],
            'address_phone' => ['nullable', 'string', 'max:20'],
            'region' => ['nullable', 'string', 'max:100', 'required_with:street_address'],
            'city' => ['nullable', 'string', 'max:100', 'required_with:street_address'],
            'postal_code' => ['nullable', 'string', 'max:10', 'required_with:street_address'],
            'street_address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'role_id' => (int) $validated['role_id'],
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $payload['password_hash'] = Hash::make($validated['password']);
        }

        DB::transaction(function () use ($user, $payload, $validated) {
            $user->update($payload);
            $this->upsertDefaultAddress($user, $validated);
        });

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = auth()->user();

        if ($authUser && $authUser->user_id === $user->user_id) {
            return redirect()->route('admin.user.index')->with('error', 'You cannot delete your own account.');
        }

        $roleName = Role::where('role_id', $user->role_id)->value('role_name');
        if ($roleName === 'Admin') {
            $adminCount = User::whereHas('role', function ($query) {
                $query->where('role_name', 'Admin');
            })->count();

            if ($adminCount <= 1) {
                return redirect()->route('admin.user.index')->with('error', 'Cannot delete the last admin account.');
            }
        }

        try {
            $user->delete();
        } catch (\Throwable $e) {
            return redirect()->route('admin.user.index')->with('error', 'Cannot delete this user because it is referenced by other records.');
        }

        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully.');
    }

    public function deactivate(User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = auth()->user();

        if ($authUser && $authUser->user_id === $user->user_id) {
            return redirect()->route('admin.user.index')->with('error', 'You cannot deactivate your own account.');
        }

        $roleName = Role::where('role_id', $user->role_id)->value('role_name');
        if ($roleName === 'Admin' && $user->is_active) {
            $activeAdminCount = User::where('is_active', true)
                ->whereHas('role', function ($query) {
                    $query->where('role_name', 'Admin');
                })
                ->count();

            if ($activeAdminCount <= 1) {
                return redirect()->route('admin.user.index')->with('error', 'Cannot deactivate the last active admin account.');
            }
        }

        if (!$user->is_active) {
            return redirect()->route('admin.user.index')->with('success', 'User is already inactive.');
        }

        $user->is_active = false;
        $user->save();

        return redirect()->route('admin.user.index')->with('success', 'User deactivated successfully.');
    }

    public function activate(User $user)
    {
        if ($user->is_active) {
            return redirect()->route('admin.user.index')->with('success', 'User is already active.');
        }

        $user->is_active = true;
        $user->save();

        return redirect()->route('admin.user.index')->with('success', 'User activated successfully.');
    }

    private function upsertDefaultAddress(User $user, array $validated): void
    {
        $streetAddress = trim((string) ($validated['street_address'] ?? ''));

        if ($streetAddress === '') {
            return;
        }

        $phone = trim((string) ($validated['address_phone'] ?? $user->contact_number ?? ''));
        if ($phone === '') {
            $phone = 'N/A';
        }

        $payload = [
            'label' => $validated['address_label'] ?? null,
            'recipient_name' => $validated['recipient_name'] ?? $user->full_name,
            'phone' => $phone,
            'region' => $validated['region'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'street_address' => $streetAddress,
            'is_default' => true,
        ];

        $defaultAddress = $user->defaultAddress()->first();

        if ($defaultAddress) {
            $defaultAddress->fill($payload);
            $defaultAddress->save();
            return;
        }

        UserAddress::where('user_id', $user->user_id)->update(['is_default' => false]);

        UserAddress::create(array_merge($payload, [
            'user_id' => $user->user_id,
        ]));
    }
}
