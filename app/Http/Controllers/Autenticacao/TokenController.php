<?php

namespace App\Http\Controllers\Autenticacao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Token;
use Carbon\Carbon;

class TokenController extends Controller
{
    public function validarToken($token){
        $tokenUsuario = Token::where('token', '=', $token)->with('User')->first();
        $autorizacao = array();
        if(empty($tokenUsuario)){
            $autorizacao['autorizado'] = false;
        }else{
            $autorizacao['autorizado'] = $tokenUsuario->expira_em > Carbon::now() ? true : false;
            $autorizacao['id'] = $tokenUsuario->usuario_id;
        }
        return $autorizacao;
    }
    
    public static function gerarToken($nome){
        $dataExpiracao = Carbon::now()->addDay(7);
        $token = array();
        $token['token'] = hash("md5", $nome.$dataExpiracao); 
        $token['expira_em'] = $dataExpiracao;
        return $token;
    }
}
