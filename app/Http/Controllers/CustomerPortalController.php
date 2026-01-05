<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerPortalController extends Controller
{
    /**
     * Show customer dashboard
     */
    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();
        
        return Inertia::render('Portal/Dashboard', [
            'user' => $customer,
            'orders_count' => $customer->orders()->count(),
            'recent_orders' => $customer->orders()->latest()->take(3)->get(),
        ]);
    }

    /**
     * Show detailed profile
     */
    public function profile()
    {
        return Inertia::render('Portal/Profile', [
            'user' => Auth::guard('customer')->user(),
        ]);
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $customer->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show customer orders
     */
    public function orders()
    {
        return Inertia::render('Portal/Orders', [
            'orders' => Auth::guard('customer')->user()->orders()->latest()->get(),
        ]);
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user('customer')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    // Placeholders for other features
    public function subscription() {}
    public function invoices() {}
    public function support() {}
}
