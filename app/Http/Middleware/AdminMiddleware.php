<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Enforces strict authentication, role authorization, and security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بالوصول. يرجى تسجيل الدخول أولاً.',
                ], 401);
            }

            return redirect()->guest(route('login'))
                ->with('status', 'يرجى تسجيل الدخول للوصول إلى لوحة التحكم.');
        }

        // 2. Strict Role Authorization Check (admin or super_admin only)
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'super_admin'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بالوصول إلى لوحة التحكم.',
                ], 403);
            }

            return redirect()->route('login')
                ->withErrors(['login' => 'غير مصرح لهذا الحساب بالوصول إلى لوحة التحكم.']);
        }

        // 3. Process the request
        $response = $next($request);

        // 4. Inject strict security headers on all admin responses
        // Prevent browser caching of sensitive admin management screens
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
