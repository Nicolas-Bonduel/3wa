<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware extends Authenticate
{
    public function handle($request, Closure $next, ...$guards): Response
    {
        if (! auth('user')->user()) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
