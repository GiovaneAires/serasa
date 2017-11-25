<?php

namespace App\Http\Controllers\TituloTipo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TituloTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titulos_tipo = \App\TituloTipo::where('id', '=', $request->id)->with('User')->first();
        $jsontitulos_tipo = ["valor" => $titulos_tipo->valor,"valor" => $titulos_tipo->data_pagamento, "identificador" => $titulos_tipo->identificador, "data_emissao" => $titulos_tipo->data_emissao, "situacao" => $titulos_tipo->situacao, 'situacao'];
        $response = !empty($titulos_tipo) ? response()->json($jsontitulos_tipo, 200) : response()->json('Tipo de títulos não encontrado', 404);
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
        $rules = new \App\Http\Requests\StoreTituloTipoRequest();
        $vr = validator($request->all(), $rules->required());
        if ($vr->fails()){
            return response()->json(['mensagem' => 'Campo obrigatório não informado: ' . implode(',', $vr->errors()->keys())], 400);
        }
        
        /*$v = validator($request->all(), $rules->rules());
        if ($v->fails()){
            $mensagem = $v->errors()->keys()[0] == 'email' ? ['mensagem' => 'Email inválido'] : ['mensagem' => 'Atributo ultrapassou o tamanho máximo: ' . implode(',', $v->errors()->keys())];
            return response()->json($mensagem, 422);
        }*/
       
        $parc = \App\TituloTipo::getTituloTipoCadastrado($request->valor, $request->data_pagamento, $request->identificador, $request->data_emissao, $request->situacao);
        if(count($titTip)){
            return response()->json(['mensagem' => 'Tipos de títulos já cadastrado.'], 409);
        }
        
        /*$usuario = new Usuario();
        $usuario->fill(['nome' => $request->nome_usuario, 'senha' => hash('md5', $request->senha), 'email' => $request->email, 'situacao' => 1]);
        $usuario->save();*/
        
        $titTip = new Parceiro();
        $titTip->fill($request->all());
        $titTip->titulos_tipo_id = $usuario->id;
        $titTip->save();
        
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
    public function update(Request $request, $id)
    {
         //USAR VALIDADOR
        if(empty($request->id) || !is_numeric($request->id))
            return response()->json('Id do tipo de titulos inválido, deve ser do tipo inteiro', 400);
        
        if($request->getMethod() == 'DELETE'){
            \App\TituloTipo::where('id', $request->id)
                ->update(['situacao' => 0]);
            
            return response()->json(['mensagem' => 'Tipo de título inativado com sucesso'], 200);
        }else{
            $rules = new \App\Http\Requests\StoreTituloTipoRequest();
//            $vr = validator($request->all(), $rules->required());
//            if ($vr->fails()){
//                return response()->json(['mensagem' => 'Campo obrigatório não informado: ' . implode(',', $vr->errors()->keys())], 400);
//            }

            /*$v = validator($request->all(), $rules->rules());
            if ($v->fails()){
                $mensagem = $v->errors()->keys()[0] == 'email' ? ['mensagem' => 'Email inválido'] : ['mensagem' => 'Atributo ultrapassou o tamanho máximo: ' . implode(',', $v->errors()->keys())];
                return response()->json($mensagem, 422);
            }*/

            /*$tituloTipo = Usuario::find($request->id);
            $tituloTipo->senha = isset($request->senha) ? hash('md5',$request->senha) : $usuario->senha;
            $usuario->email = isset($request->email) ? $request->email : $usuario->email;
            $usuario->save();*/
            
            $titTip = Parceiro::where('titulos_tipo_id', '=', $request->id)->with('User')->first();
            $titTip->valor = isset($request->valor) ? $request->valor : $titTip->valor;
            $titTip->data_pagamento = isset($request->data_pagamento) ? $request->data_pagamento : $titTip->data_pagamento;
            $titTip->identificador = isset($request->identificador) ? $request->identificador : $titTip->identificador;
            $titTip->data_emissao = isset($request->data_emissao) ? $request->data_emissao : $titTip->data_emissao;
            $titTip->situacao = isset($request->situacao) ? $request->situacao : $titTip->situacao;
            $titTip->save();
            
            return response()->json(['mensagem' => 'Tipos de títulos atualizado com sucesso'], 200);
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
