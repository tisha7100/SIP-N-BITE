<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryPartner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /*
    |----------------------------------------
    | SHOW LOGIN
    |----------------------------------------
    */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /*
    |----------------------------------------
    | SHOW REGISTER
    |----------------------------------------
    */
    public function showRegister()
    {
        return view('auth.register');
    }

    /*
    |----------------------------------------
    | REGISTER
    |----------------------------------------
    */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
            'registration_time' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    /*
    |----------------------------------------
    | LOGIN (ALL ROLES SINGLE PAGE)
    |----------------------------------------
    */
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'role' => 'required',
    ]);

    if ($request->role === 'delivery_partner') {
        $partner = DeliveryPartner::where('email', $request->email)->first();

        if (!$partner) {
            return back()->with('error', 'Invalid credentials')->withInput();
        }

        $validPassword = false;

        if (is_string($partner->password) && str_starts_with($partner->password, '$2y$')) {
            try {
                $validPassword = Hash::check($request->password, $partner->password);
            } catch (\Exception $e) {
                $validPassword = false;
            }
        } else {
            $validPassword = (trim($request->password) === trim($partner->password));
        }

        if (!$validPassword) {
            return back()->with('error', 'Invalid password')->withInput();
        }

        Auth::guard('delivery_partner')->login($partner, true);

        return redirect()->route('delivery-partner.dashboard');
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->with('error', 'Invalid credentials')->withInput();
    }

    if ($user->role !== $request->role) {
        return back()->with('error', 'Wrong login type selected')->withInput();
    }

    /*
    |------------------------------------
    | SAFE PASSWORD CHECK (NO CRASH)
    |------------------------------------
    */

    $validPassword = false;

    // If password looks like bcrypt
    if (is_string($user->password) && str_starts_with($user->password, '$2y$')) {
        try {
            $validPassword = Hash::check($request->password, $user->password);
        } catch (\Exception $e) {
            $validPassword = false;
        }
    } else {
        // plain password fallback
        $validPassword = ($request->password === $user->password);
    }

    if (!$validPassword) {
        return back()->with('error', 'Invalid password')->withInput();
    }

    if ($user->status === 'blocked') {
        return back()->with('error', 'Your account is blocked')->withInput();
    }

    Auth::login($user);

    $user->update([
        'login_time' => now(),
    ]);

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'delivery_partner') {
        return redirect()->route('delivery-partner.dashboard');
    }

    return redirect()->route('home');
}
    /*
    |----------------------------------------
    | LOGOUT
    |----------------------------------------
    */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    /*
    |----------------------------------------
    | GOOGLE LOGIN
    |----------------------------------------
    */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /*
    |----------------------------------------
    | GOOGLE CALLBACK
    |----------------------------------------
    */
    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()),
                    'role' => 'customer',
                    'status' => 'active',
                    'registration_time' => now(),
                    'login_time' => now(),
                ]);

            } else {

                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'login_time' => now(),
                ]);
            }

            Auth::login($user);

            return redirect()->route('home');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
