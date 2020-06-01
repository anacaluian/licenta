<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsClient
{
    public function handle($request, Closure $next)
    {
        if(Auth::user()->role === 3) {
            return $next($request);
        }        else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
}
