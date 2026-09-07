<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    // Handle Phone Number Login
    public function loginWithPhone(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
        ]);

        $phone = $request->input('phone_number');

        // Find or create user by phone number
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

        return redirect()->route('repair.dashboard')->with('success', 'Logged in successfully!');
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