<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // ប្រសិនបើមិនទាន់ Login ឬ Role របស់អ្នកប្រើមិនស្ថិតក្នុងបញ្ជីដែលអនុញ្ញាត
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'អ្នកមិនមានសិទ្ធិចូលប្រើប្រាស់ផ្នែកនេះទេ!');
        }

        return $next($request);
    }
}
