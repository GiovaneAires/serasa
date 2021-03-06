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
        $token = new TokenController();
        $autorizacao = $token->validarToken($request->header('Authorization'));
        
        if($autorizacao['autorizado']){
            $request->merge(array("id" => $autorizacao['id']));
            $response = $next($request);
        }else{
            $response = response()->json(['mensagem' => 'Usuário não logado no sistema para realizar esta operação.'], 401);
        }
        
        return $response;
    }
}
