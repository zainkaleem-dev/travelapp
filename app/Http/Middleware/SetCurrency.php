<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCurrency
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $default = (string) config('currencies.default', 'USD');
        $currency = $request->session()->get('currency', $default);

        if (!is_string($currency) || !preg_match('/^[A-Z]{3}$/', $currency)) {
            $currency = $default;
        }

        $request->session()->put('currency', $currency);

        return $next($request);
    }
}