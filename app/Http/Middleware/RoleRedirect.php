<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            switch ($user->role) {
                case 'Admin':
                    return redirect()->route('dashboard.super_admin');
                case 'Investor':
                    return redirect()->route('dashboard.investor');
                case 'Startup':
                    return redirect()->route('dashboard.startup');
                case 'Employee':
                    return redirect()->route('dashboard.employee');
                case 'Employer':
                    return redirect()->route('dashboard.employer');
                default:
                    return redirect()->route('dashboard.welcome');
            }
        }

        return $next($request);
    }
}
