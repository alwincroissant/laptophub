<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validateWithBag('login', [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password_hash)) {
            return back()
                ->withErrors(['email' => 'Invalid credentials.'], 'login')
                ->withInput($request->only('email', 'remember'));
        }

        if (! $user->is_active) {
            return back()
                ->withErrors(['email' => 'Your account is deactivated. Please contact support.'], 'login')
                ->withInput($request->only('email', 'remember'));
        }

        if (! $user->email_verified_at) {
            return back()
                ->withErrors(['email' => 'Please verify your email address before logging in.'], 'login')
                ->withInput($request->only('email', 'remember'));
        }

        Auth::login($user, (bool) $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()
            ->intended($this->redirectPathByRole($user))
            ->with('success', 'Welcome back! You are now logged in.');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('register', [
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'terms' => ['accepted'],
        ]);

        $customerRoleId = Role::where('role_name', 'Customer')->value('role_id')
            ?? Role::query()->min('role_id');

        if (! $customerRoleId) {
            return back()
                ->withErrors(['email' => 'No role found. Please seed roles first.'], 'register')
                ->withInput($request->only('full_name', 'email', 'contact_number', 'terms'));
        }

        $profileImageUrl = null;
        if ($request->hasFile('profile_image')) {
            $profileImageUrl = \Illuminate\Support\Facades\Storage::url($request->file('profile_image')->store('profile-images', 'public'));
        }

        $user = User::create([
            'role_id' => $customerRoleId,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'contact_number' => $data['contact_number'] ?? null,
            'profile_image_url' => $profileImageUrl,
            'is_active' => true,
        ]);

        // Send email verification
        $user->sendEmailVerificationNotification();

        // Redirect to the verification notice page
        return redirect()->route('verification.notice')->with('success', 'Account created! Please check your email to verify your account.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    private function redirectPathByRole(User $user): string
    {
        $roleName = Role::where('role_id', $user->role_id)->value('role_name');

        return match ($roleName) {
            'Admin', 'InventoryManager' => route('admin.dashboard'),
            'Customer' => route('index'),
            default => route('index'),
        };
    }
}
