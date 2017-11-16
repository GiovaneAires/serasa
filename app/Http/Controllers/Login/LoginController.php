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
        $vr = validator($request->all(), $rules->required());
        if ($vr->fails()){
            return response()->json(['mensagem' => 'Campo obrigatório não informado: ' . implode(',', $vr->errors()->keys())], 400);
        }
        
        $v = validator($request->all(), $rules->rules());
        if ($v->fails()){
            return response()->json(['mensagem' => 'Atributo ultrapassou o tamanho máximo: ' . implode(',', $v->errors()->keys())], 422);
        }
        
        $usuario = Usuario::where('nome', '=', $request->nome_usuario)
                ->where('senha', '=', hash('md5', $request->senha))
                ->first();

        if(empty($usuario))
            return response()->json(['mensagem' => 'Usuário não encontrado.'], 404);

        $tokenUsuario = Token::where('usuario_id', '=', $usuario->id)->with('User')->first();

        if(!empty($tokenUsuario->id)){
            $respone = response()->json(['token' => $tokenUsuario->token], 200);
        }else{
            $token = new Token();
            $token->fill(TokenController::gerarToken($usuario->nome));
            $token->usuario_id = $usuario->id;
            $token->save();
            $respone = response()->json(['token' => $token->token], 200);
        }
        
        return $respone;
    }
    
    public function logout(Request $request){
        $token = Token::where('usuario_id', '=', $request->id)->first();
        
        if(!empty($token))
            Token::destroy($token->id);
        
        return response()->json(['mensagem' => 'Parceiro deslogado.'], 200);
    }
}
