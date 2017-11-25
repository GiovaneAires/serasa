<?php

namespace App\Http\Controllers\Parceiro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use App\Http\Requests\StoreParceiroRequest;
use App\Parceiro;
use App\Usuario;

class ParceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parceiros = Parceiro::where('id', '=', $request->id)->with('User')->first();
        $jsonParceiro = ["cnpj" => $parceiros->cnpj, "nome_fantasia" => $parceiros->nome_fantasia, "razao_social" => $parceiros->razao_social, "email" => $parceiros->user['email'], "nome_usuario" => $parceiros->user['nome']];
        $response = !empty($parceiros) ? response()->json($jsonParceiro, 200) : response()->json('Parceiro não encontrado', 404);
        return $response;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $rules = new StoreParceiroRequest();
        $vr = validator($request->all(), $rules->required());
        if ($vr->fails()){
            return response()->json(['mensagem' => 'Campo obrigatório não informado: ' . implode(',', $vr->errors()->keys())], 400);
        }
        
        $v = validator($request->all(), $rules->rules());
        if ($v->fails()){
            $mensagem = $v->errors()->keys()[0] == 'email' ? ['mensagem' => 'Email inválido'] : ['mensagem' => 'Atributo ultrapassou o tamanho máximo: ' . implode(',', $v->errors()->keys())];
            return response()->json($mensagem, 422);
        }
        
        $parc = Parceiro::getParceiroCadastrado($request->cnpj, $request->email, $request->nome_usuario);
        if(count($parc)){
            return response()->json(['mensagem' => 'Parceiro já cadastrado.'], 409);
        }
        
        $usuario = new Usuario();
        $usuario->fill(['nome' => $request->nome_usuario, 'senha' => hash('md5', $request->senha), 'email' => $request->email, 'situacao' => 1]);
        $usuario->save();
        
        $parceiro = new Parceiro();
        $parceiro->fill($request->all());
        $parceiro->usuario_id = $usuario->id;
        $parceiro->save();
        
        return response()->json('Cadastro realizado com sucesso', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //USAR VALIDADOR
        if(empty($request->id) || !is_numeric($request->id))
            return response()->json('Id do parceiro inválido, deve ser do tipo inteiro', 400);
        
        if($request->getMethod() == 'DELETE'){
            Usuario::where('id', $request->id)
                ->update(['situacao' => 0]);
            
            return response()->json(['mensagem' => 'Parceiro inativado com sucesso'], 200);
        }else{
            $rules = new StoreParceiroRequest();
//            $vr = validator($request->all(), $rules->required());
//            if ($vr->fails()){
//                return response()->json(['mensagem' => 'Campo obrigatório não informado: ' . implode(',', $vr->errors()->keys())], 400);
//            }

            $v = validator($request->all(), $rules->rules());
            if ($v->fails()){
                $mensagem = $v->errors()->keys()[0] == 'email' ? ['mensagem' => 'Email inválido'] : ['mensagem' => 'Atributo ultrapassou o tamanho máximo: ' . implode(',', $v->errors()->keys())];
                return response()->json($mensagem, 422);
            }

            $usuario = Usuario::find($request->id);
            $usuario->senha = isset($request->senha) ? hash('md5',$request->senha) : $usuario->senha;
            $usuario->email = isset($request->email) ? $request->email : $usuario->email;
            $usuario->save();
            
            $parc = Parceiro::where('usuario_id', '=', $request->id)->with('User')->first();
            $parc->nome_fantasia = isset($request->nome_fantasia) ? $request->nome_fantasia : $parc->nome_fantasia;
            $parc->razao_social = isset($request->razao_social) ? $request->razao_social : $parc->razao_social;
            $parc->save();
            
            return response()->json(['mensagem' => 'Parceiro atualizado com sucesso'], 200);
        }
    }
        
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
