<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user()->load('role:role_id,role_name');

        return view('admin.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'contact_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user->fill($data);
        $user->save();

        return redirect()->route('admin.account.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($data['current_password'], $user->password_hash)) {
            return redirect()->route('admin.account.profile')
                ->with('error', 'Current password is incorrect.');
        }

        $user->password_hash = Hash::make($data['new_password']);
        $user->save();

        return redirect()->route('admin.account.profile')
            ->with('success', 'Password changed successfully.');
    }
}
