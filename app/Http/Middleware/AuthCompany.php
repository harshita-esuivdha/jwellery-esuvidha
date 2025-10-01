<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCompany
{
  public function handle(Request $request, Closure $next)
    {
        if (!session()->has('company_id') && !session()->has('superadmin_id')) {
            return redirect()->route('company.login');
        }

        return $next($request);
    }

}
