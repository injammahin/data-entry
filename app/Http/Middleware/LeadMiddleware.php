<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LeadMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'lead') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}