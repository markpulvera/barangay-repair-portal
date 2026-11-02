<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller
{
    // Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google Callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(str()->random(16)),
                    'role' => 'user', // Default role for new users
                ]
            );

            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            Auth::login($user);

            // Redirect based on user role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('repair.dashboard');

        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Google Authentication Failed.']);
        }
    }

    // Step 1: Request OTP
    public function sendPhoneOtp(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^09[0-9]{9}$/'],
        ]);

        $phone = $request->input('phone_number');
        $otp = rand(100000, 999999);

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));

        // Log for record-keeping
        Log::info("OTP for {$phone}: {$otp}");

        // Flash code directly into the success message for smooth presentation demo
        return redirect()->route('login.otp.verify', ['phone' => $phone])
            ->with('success', 'Verification code generated successfully! [DEMO CODE: ' . $otp . ']');
    }

    // Step 2: Verify OTP and Login
    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $phone = $request->input('phone_number');
        $cachedOtp = Cache::get('otp_' . $phone);

        if (!$cachedOtp || (string)$cachedOtp !== (string)$request->input('otp')) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        // Clear the OTP from cache so it can't be reused
        Cache::forget('otp_' . $phone);

        $user = User::firstOrCreate(
            ['phone_number' => $phone],
            [
                'name' => 'Resident (' . substr($phone, -4) . ')',
                'email' => $phone . '@barangay.local',
                'password' => bcrypt(str()->random(16)),
                'role' => 'user',
            ]
        );

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('repair.dashboard')->with('success', 'Logged in successfully via OTP!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}