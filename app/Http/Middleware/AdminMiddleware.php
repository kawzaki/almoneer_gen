<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'انتهت الجلسة، يرجى تسجيل الدخول مجدداً.'], 401);
            }

            return redirect()->guest(route('login'))
                ->with('status', 'انتهت جلستك، يرجى تسجيل الدخول للمتابعة إلى الصفحة المطلوبة.');
        }

        return $next($request);
    }
}
