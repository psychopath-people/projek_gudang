<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->bearerToken()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. No token provided.'
            ], 401);
        }

        return $next($request);
    }
}
