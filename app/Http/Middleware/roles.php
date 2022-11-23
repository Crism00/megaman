<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$rol)
    {
        foreach($rol as $roles) {if($request->user()->rol==$roles)return $next($request);}


        abort(401, "EL usuario no tiene acceso a esta ruta");
        
    }
}
