<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTokenPut;
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
            return response()->json("Usuário nã encontrado.", 404);
        
        $tokenUsuario = Token::where('usuario_id', '=', $usuario->id)->with('User')->first();
        
//        if(!empty($tokenUsuario->usuario_id))
//            Token->delete
        
        
//        $token = Token->cadastro;
        return response()->json(['token' => $token], 200);
    }
    
    public function logout(){
        
    }
}
