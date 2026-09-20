<?php

namespace App\Http\Middleware;

use App\Enums\WebAppStatus;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Only registered on the already-`auth`-protected route group in
 * routes/web.php, not globally — a guest hitting anything unauthenticated is
 * redirected to /login regardless of maintenance status, so restricting the
 * check to authenticated requests means an admin can never be locked out by
 * being redirected away from login. No route-exemption list to maintain.
 */
class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::current();

        if ($setting->web_app_status === WebAppStatus::UnderMaintenance
            && ! $request->user()?->can('view', Setting::class)) {
            abort(503);
        }

        return $next($request);
    }
}
