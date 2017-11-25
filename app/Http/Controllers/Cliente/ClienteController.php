<?php

namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestCliente;

class ClienteController extends Controller
{
    
    public function index()
    {
        $cliente = \App\Cliente::where('id', '=', $request->id)->with('User')->first();
        $jsonCliente = ["cpf" => $clientes->cpf, "nome_cliente" => $clientes->user['nome_cliente']];
        $response = !empty($clientes) ? response()->json($jsonCliente, 200) : response()->json('Cliente não encontrado', 404);
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
        $rules = new StoreRequestCliente();
        $vr = validator($request->all(), $rules->required());
        if($vr->fails())
        {
            return response()->json(['mensagem'=>'Campo obrigatório não informado: '.implode('', $vr->errors()->keys())], 400);
        }
        
        $v = validator($request->all(), $rules->rules());
        if ($v->fails()){
            $mensagem = $v->errors()->keys()[0] == 'cpf' ? ['mensagem' => 'CPF inválido'] : ['mensagem' => 'Atributo ultrapassou o tamanho máximo: ' . implode(',', $v->errors()->keys())];
            return response()->json($mensagem, 422);
        }
        
        $cli = \App\Cliente::getClienteCadastrado($request->nome_cliente, $request->cpf);
        if(count($cli)){
            return response()->json(['mensagem' => 'Parceiro já cadastrado.'], 409);
        }
        
        $cliente = new Cliente();
        $cliente->fill($request->all());
        $parceiro->cliente_id = $cliente->id;
        $cliente->save();
        
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
         if(empty($request->id) || !is_numeric($request->id))
            return response()->json('Id do cliente inválido, deve ser do tipo inteiro', 400);
        
        if($request->getMethod() == 'DELETE'){
            \App\Cliente::where('id', $request->id)
                ->update(['situacao' => 0]);
            
           return response()->json(['mensagem' => 'Cliente inativado com sucesso'], 200);
           
        }else{
            $rules = new StoreRequestCliente();
//            $vr = validator($request->all(), $rules->required());
//            if ($vr->fails()){
//                return response()->json(['mensagem' => 'Campo obrigatório não informado: ' . implode(',', $vr->errors()->keys())], 400);
//            }

            $v = validator($request->all(), $rules->rules());
            if ($v->fails()){
                $mensagem = $v->errors()->keys()[0] == 'cpf' ? ['mensagem' => 'CPF inválido'] : ['mensagem' => 'Atributo ultrapassou o tamanho máximo: ' . implode(',', $v->errors()->keys())];
                return response()->json($mensagem, 422);
            }

            /*$cliente = Usuario::find($request->id);
            $cliente->cpf = isset($request->cpf) ? hash('md5',$request->senha) : $usuario->senha;
            $cliente->email = isset($request->email) ? $request->email : $usuario->email;
            $cliente->save();*/
            
            $cli = \App\Cliente::where('cliente_id', '=', $request->id)->with('User')->first();
            $cli->nome_cliente = isset($request->nome_cliente) ? $request->nome_cliente : $cli->nome_cliente;
            $cli->cpf = isset($request->cpf) ? $request->cpf : $cli->cpf;
            $cli->save();
            
            return response()->json(['mensagem' => 'Cliente atualizado com sucesso'], 200);
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
