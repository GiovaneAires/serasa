<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Autenticacao\TokenController;

class Autenticacao
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $request->header('Authorization');
        
        $autorizado = true;
        $request->merge(array("id" => "3"));
        
        if($autorizado)
            $response = $next($request);
        else
            $response = response()->json('ERRO', 401);
        
        return $response;
    }
}
