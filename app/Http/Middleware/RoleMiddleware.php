<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if the user's role is in the permitted roles list
        if (in_array($user->role, $roles)) {
            // Also check if owner/staff has active homestay status
            if ($user->role !== 'super_admin' && $user->homestay !== null) {
                if ($user->homestay->status !== 'active') {
                    auth()->logout();
                    return redirect()->route('login')->withErrors([
                        'email' => 'Akun homestay Anda ditangguhkan. Silakan hubungi Super Admin.',
                    ]);
                }
            }
            return $next($request);
        }

        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
    }
}
