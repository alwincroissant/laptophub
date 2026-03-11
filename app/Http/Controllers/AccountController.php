<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function profile()
    {
        return view('customer.account.profile');
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $wantsDetailsUpdate = $request->hasAny(['full_name', 'email', 'contact_number']);

        $rules = [
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];

        if ($wantsDetailsUpdate) {
            $rules['full_name'] = ['required', 'string', 'max:100'];
            $rules['email'] = [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ];
            $rules['contact_number'] = ['nullable', 'string', 'max:20'];
        }

        $data = $request->validate($rules);

        if (! $wantsDetailsUpdate && ! $request->hasFile('profile_image')) {
            return redirect()->route('customer.account.profile')->with('error', 'Please choose an image to upload.');
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image_url && Str::startsWith($user->profile_image_url, '/storage/')) {
                $existingPath = Str::after($user->profile_image_url, '/storage/');

                if ($existingPath !== '') {
                    Storage::disk('public')->delete($existingPath);
                }
            }

            $storedPath = $request->file('profile_image')->store('profile-images', 'public');
            $data['profile_image_url'] = Storage::url($storedPath);
        }

        unset($data['profile_image']);

        $user->fill($data);
        $user->save();

        return redirect()->route('customer.account.profile')->with('success', 'Profile updated successfully.');
    }

    public function password()
    {
        return redirect()->route('customer.account.profile', ['tab' => 'security']);
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
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password_hash = Hash::make($data['new_password']);
        $user->save();

        return redirect()->route('customer.account.profile', ['tab' => 'security'])
            ->with('success', 'Password changed successfully.');
    }

    public function addresses()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $addresses = UserAddress::where('user_id', $user->user_id)
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        return view('customer.account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $data = $this->validateAddress($request);
        $setAsDefault = $request->boolean('is_default');

        DB::transaction(function () use ($user, $data, $setAsDefault) {
            $markDefault = $setAsDefault || !UserAddress::where('user_id', $user->user_id)->exists();

            if ($markDefault) {
                UserAddress::where('user_id', $user->user_id)->update(['is_default' => false]);
            }

            UserAddress::create([
                'user_id' => $user->user_id,
                'label' => $data['label'] ?? null,
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'region' => $data['region'],
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
                'street_address' => $data['street_address'],
                'is_default' => $markDefault,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('customer.account.addresses')->with('success', 'Address added.');
    }

    public function updateAddress(Request $request, UserAddress $address)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $this->ensureOwnership($user->user_id, $address);

        $data = $this->validateAddress($request);
        $setAsDefault = $request->boolean('is_default');

        DB::transaction(function () use ($user, $address, $data, $setAsDefault) {
            if ($setAsDefault) {
                UserAddress::where('user_id', $user->user_id)->update(['is_default' => false]);
            }

            $address->fill([
                'label' => $data['label'] ?? null,
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'region' => $data['region'],
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
                'street_address' => $data['street_address'],
            ]);

            if ($setAsDefault) {
                $address->is_default = true;
            }

            $address->save();
        });

        return redirect()->route('customer.account.addresses')->with('success', 'Address updated.');
    }

    public function setDefaultAddress(UserAddress $address)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $this->ensureOwnership($user->user_id, $address);

        DB::transaction(function () use ($user, $address) {
            UserAddress::where('user_id', $user->user_id)->update(['is_default' => false]);
            $address->is_default = true;
            $address->save();
        });

        return redirect()->route('customer.account.addresses')->with('success', 'Default address updated.');
    }

    public function destroyAddress(UserAddress $address)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $this->ensureOwnership($user->user_id, $address);

        $wasDefault = (bool) $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $nextAddress = UserAddress::where('user_id', $user->user_id)
                ->orderByDesc('updated_at')
                ->first();

            if ($nextAddress) {
                $nextAddress->is_default = true;
                $nextAddress->save();
            }
        }

        return redirect()->route('customer.account.addresses')->with('success', 'Address deleted.');
    }

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'region' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'street_address' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable'],
        ]);
    }

    private function ensureOwnership(int $userId, UserAddress $address): void
    {
        if ($address->user_id !== $userId) {
            abort(403, 'Unauthorized');
        }
    }
}
