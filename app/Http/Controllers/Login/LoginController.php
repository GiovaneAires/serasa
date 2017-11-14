<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTokenPut;
use App\Http\Controllers\Autenticacao\TokenController;
use App\Usuario;
use App\Token;

class LoginController extends Controller
{
    public function login(Request $request){
        $rules = new StoreTokenPut();
        $vr = validator($request->all(), $rules->required(), 'Campo obrigatório não informado.');
        if ($vr->fails()){
            return response()->json($vr->getMessageBag(), 400);
        }
        
        $v = validator($request->all(), $rules->rules(), 'O tamanho máximo do campo foi ultrapassado.');
        if ($v->fails()){
            return response()->json($v->getMessageBag(), 422);
        }
        
        $usuario = Usuario::where('nome', $request->nome_usuario)
                            ->where('senha', $request->senha);

        if(empty($usuario))
            return response()->json("Usuário não encontrado.", 404);

        $tokenUsuario = Token::where('usuario_id', '=', $usuario->id)->with('User')->first();

        if(!empty($tokenUsuario->id))
            Token::destroy($tokenUsuario->id);

        $token = new Token();
        $token->fill(TokenController::gerarToken());
        $token->usuario_id = $usuario->id;
        $token->save();

        return response()->json(['token' => $token->token], 200);
    }
    
    public function logout(Request $request){
        $token = Token::where('token', $request->header('Authorization'));
        
        if(!empty($token))
            Token::destroy($token->id);
        
        return response()->json(['mensagem' => 'Parceiro deslogado.'], 200);
    }
}
