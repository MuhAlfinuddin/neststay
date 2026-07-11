<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUserBasedOnRole();
        }
        return view('auth.login');
    }

    /**
     * Handle authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Check if homestay status is suspended
            $user = Auth::user();
            if ($user->role !== 'super_admin' && $user->homestay !== null) {
                if ($user->homestay->status !== 'active') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akun homestay Anda ditangguhkan. Silakan hubungi Super Admin.',
                    ]);
                }
            }

            return $this->redirectUserBasedOnRole();
        }

        return back()->withErrors([
            'email' => 'Password salah',
        ])->onlyInput('email');
    }

    /**
     * Show registration form for new Owners.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUserBasedOnRole();
        }
        return view('auth.register');
    }

    /**
     * Handle Owner and Homestay registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'homestay_name' => ['required', 'string', 'max:255'],
            'homestay_address' => ['required', 'string'],
            'homestay_phone' => ['required', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'plan' => ['required', 'string', 'in:hemat,lengkap'],
        ]);

        $slug = Str::slug($request->homestay_name);

        // Ensure slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (Homestay::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        try {
            DB::transaction(function () use ($request, $slug) {
                // Create Homestay
                $homestay = Homestay::create([
                    'name' => $request->homestay_name,
                    'address' => $request->homestay_address,
                    'phone' => $request->homestay_phone,
                    'slug' => $slug,
                    'status' => 'active',
                    'plan' => $request->plan, // <--- INI KUNCI NYA
                ]);

                // Create Owner User linked to Homestay
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'owner',
                    'homestay_id' => $homestay->id,
                ]);
            });

            // Login after success
            if (Auth::attempt($request->only('email', 'password'))) {
                $request->session()->regenerate();
                return redirect()->route('dashboard')->with('success', 'Registrasi homestay berhasil!');
            }

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal melakukan registrasi: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show forgot password form.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show reset password form.
     */
    public function showResetForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Kata sandi berhasil direset! Silakan login.')
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Redirect user to their appropriate dashboard based on role.
     */
    protected function redirectUserBasedOnRole()
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
}
