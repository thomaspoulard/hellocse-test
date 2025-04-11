<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuthSanctum
{
    /**
     * Allow both public and admin requests
     *
     * @param  \Closure(\Illuminate\Http\Request)
     * @return (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken()) {
            $user = Auth::guard('sanctum')->user();
            if (isset($user)) {
                Auth::setUser($user);
            }
        }

        return $next($request);
    }
}
