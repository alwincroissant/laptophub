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
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'current_password' => ['required_with:new_password', 'nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image_url && \Illuminate\Support\Str::startsWith($user->profile_image_url, '/storage/')) {
                $existingPath = \Illuminate\Support\Str::after($user->profile_image_url, '/storage/');
                if ($existingPath !== '') {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($existingPath);
                }
            }

            $storedPath = $request->file('profile_image')->store('profile-images', 'public');
            $data['profile_image_url'] = \Illuminate\Support\Facades\Storage::url($storedPath);
        }

        unset($data['profile_image']);

        // Only update password if provided
        if (!empty($data['new_password'])) {
            if (!\Illuminate\Support\Facades\Hash::check($data['current_password'] ?? '', $user->password_hash)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect. Profile update failed.'])->withInput();
            }
            $data['password_hash'] = \Illuminate\Support\Facades\Hash::make($data['new_password']);
        }
        unset($data['new_password'], $data['current_password']);

        $user->fill($data);
        $user->save();

        return redirect()->route('admin.account.profile')
            ->with('success', 'Profile saved successfully.');
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
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $user->password_hash = Hash::make($data['new_password']);
        $user->save();

        return redirect()->route('admin.account.profile')
            ->with('success', 'Password changed successfully.');
    }
}
