<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Send OTP to UK phone number
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^\+44[0-9]{10}$/'],
        ]);

        $phone = $request->phone;
        
        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP in cache for 5 minutes
        Cache::put("otp_{$phone}", $otp, now()->addMinutes(5));
        
        // TODO: Send SMS using Twilio, Vonage, or AWS SNS
        // For development, log the OTP
        //Log::info("OTP for {$phone}: {$otp}");
        
        // For testing, also store in session
        session(['dev_otp' => $otp]);
        
        return back()->with('success', 'OTP sent successfully');
    }

    /**
     * Verify OTP and login/register user
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^\+44[0-9]{10}$/'],
            'otp' => ['required', 'digits:6'],
        ]);

        $phone = $request->phone;
        $otp = $request->otp;
        
        // Get stored OTP from cache
        $storedOTP = Cache::get("otp_{$phone}");
        
        if (!$storedOTP || $storedOTP !== $otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }
        
        // Clear OTP from cache
        Cache::forget("otp_{$phone}");
        
        // Find or create user
        $user = User::where('phone', $phone)->first();
        
        if (!$user) {
            // New user - needs profile completion
            session(['pending_phone' => $phone]);
            return Inertia::render('Auth/Login', [
                'needsProfile' => true,
            ]);
        }
        
        // Login existing user
        Auth::login($user);
        
        return redirect()->intended('/');
    }

    /**
     * Complete profile for new user
     */
    public function completeProfile(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^\+44[0-9]{10}$/'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users'],
        ]);

        $phone = $request->phone;
        
        // Verify this phone was just verified
        if (!session('pending_phone') || session('pending_phone') !== $phone) {
            return back()->withErrors(['phone' => 'Invalid session']);
        }
        
        // Create new user
        $user = User::create([
            'phone' => $phone,
            'name' => $request->name,
            'email' => $request->email,
            'phone_verified_at' => now(),
        ]);
        
        // Clear pending session
        session()->forget('pending_phone');
        
        // Login user
        Auth::login($user);
        
        return redirect()->intended('/');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}