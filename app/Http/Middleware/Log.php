<?php

namespace App\Http\Middleware;

use App\Providers\LogServiceProvider;
use Closure;

class Log
{
    public function handle($request, Closure $next)
    {
        $logger = new LogServiceProvider();
        $logger->log("path:" . $request->url() . " request: " . json_encode($request->all()));
        return $next($request);
    }
}
