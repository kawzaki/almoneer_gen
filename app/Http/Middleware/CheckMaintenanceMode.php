<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     * When maintenance mode is active, display 503 maintenance page
     * to guests while allowing authenticated administrators to access both
     * the admin panel and preview the public site.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isMaintenance = (string) \App\Models\Setting::get('site.maintenance_mode', '0') === '1'
            || (string) \App\Models\Setting::get('site_maintenance_mode', '0') === '1';

        if (!$isMaintenance) {
            return $next($request);
        }

        // Always allow static assets, health check, auth routes, and admin panel
        if (
            $request->is('admin*') ||
            $request->is('login*') ||
            $request->is('logout') ||
            $request->is('up') ||
            $request->is('uploads/*') ||
            $request->is('images/*') ||
            $request->is('css/*') ||
            $request->is('js/*') ||
            $request->is('build/*')
        ) {
            return $next($request);
        }

        // Allow authenticated administrators & super admins to preview public site
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin'], true)) {
            return $next($request);
        }

        $title = \App\Models\Setting::get('site.maintenance_title', 'الموقع تحت أعمال الصيانة والتحديث');
        $message = \App\Models\Setting::get('site.maintenance_message', 'نعمل حالياً على تطوير وتحديث الموقع لخدمتكم بشكل أفضل، سنعود قريباً إن شاء الله تعالى.');

        return response()->view('errors.503', [
            'maintenanceTitle'   => $title,
            'maintenanceMessage' => $message,
        ], 503);
    }
}
