<?php

namespace App\Http\Controllers\Autenticacao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Token;

class TokenController extends Controller
{
    public function validarToken($token){
        $tokenUsuario = Token::where('token', '=', $token)->with('User')->first();
        $autorizacao = array();
        $autorizacao['autorizado'] = $tokenUsuario->expira_em > date("Y-m-d H:i:s") ? false : true;
        $autorizacao['id'] = $tokenUsuario->usuario_id;
        return $autorizacao;
    }
    
    public static function gerarToken(){
        $token = array();
        $token['token'] = hash($usuario->nome, $dataExpiracao); 
        $token['expira_em'] = mktime (0, 0, 0, date("Y"),  date("m"),  date("d")+7);
        return $token;
    }
}
