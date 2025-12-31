<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Address;
use App\Models\Order;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function index()
    {
        $user = Auth::user();
        
        $addresses = Address::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $orders = Order::where('user_id', $user->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return Inertia::render('Profile', [
            'user' => $user,
            'addresses' => $addresses,
            'orders' => $orders,
        ]);
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Store new address
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'is_default' => ['boolean'],
        ]);
        
        $user = Auth::user();
        
        // If setting as default, unset other defaults
        if ($request->is_default) {
            Address::where('user_id', $user->id)
                ->update(['is_default' => false]);
        }
        
        Address::create([
            'user_id' => $user->id,
            'label' => $request->label,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'is_default' => $request->is_default ?? false,
        ]);
        
        return back()->with('success', 'Address added successfully');
    }

    /**
     * Update address
     */
    public function updateAddress(Request $request, Address $address)
    {
        // Verify ownership
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'is_default' => ['boolean'],
        ]);
        
        // If setting as default, unset other defaults
        if ($request->is_default) {
            Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }
        
        $address->update($request->all());
        
        return back()->with('success', 'Address updated successfully');
    }

    /**
     * Delete address
     */
    public function deleteAddress(Address $address)
    {
        // Verify ownership
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $address->delete();
        
        return back()->with('success', 'Address deleted successfully');
    }
}