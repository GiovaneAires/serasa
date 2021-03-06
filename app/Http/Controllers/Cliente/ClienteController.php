<?php

namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestCliente;
use App\Cliente;

class ClienteController extends Controller
{
    
    public function index($id = null)
    {
        if(isset($id)){
            $clientes = Cliente::where('id', '=', $id)->with('User')->first();
            $jsonCliente[] = ["nome_cliente" => $clientes->nome_cliente, "cpf" => $clientes->cpf, "id_cliente" => $clientes->id];
        }else{
            $clientes = Cliente::all(); // where('id', '=', $request->id)->with('User')->first();
            foreach ($clientes as $cliente)
                $jsonCliente[] = ["nome_cliente" => $cliente->nome_cliente, "cpf" => $cliente->cpf, "id_cliente" => $cliente->id];
        }
        
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
        
        $cli = Cliente::getClienteCadastrado($request->nome_cliente, $request->cpf);
        if(count($cli))
            return response()->json(['mensagem' => 'Cliente já cadastrado.'], 409);
        
        $cliente = new Cliente();
        $cliente->fill($request->all());
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
         if(empty($id) || !is_numeric($id))
            return response()->json('Id do cliente inválido, deve ser do tipo inteiro', 400);
       
        $rules = new StoreRequestCliente();

        $v = validator($request->all(), $rules->rules());
        if ($v->fails()){
            $mensagem = $v->errors()->keys()[0] == 'cpf' ? ['mensagem' => 'CPF inválido'] : ['mensagem' => 'Atributo ultrapassou o tamanho máximo: ' . implode(',', $v->errors()->keys())];
            return response()->json($mensagem, 422);
        }

        // $cli = Cliente::getClienteCadastrado($request->nome_cliente, $request->cpf, $id);
        $cli = Cliente::where('id', '=', $id)->with('User')->first();
        if(count($cli) == 0){
            return response()->json(['mensagem' => 'Cliente não encontrado.'], 404);
        }
        
        $cli = Cliente::where('id', '=', $id)->with('User')->first();
        $cli->nome_cliente = isset($request->nome_cliente) ? $request->nome_cliente : $cli->nome_cliente;
        $cli->cpf = isset($request->cpf) ? $request->cpf : $cli->cpf;
        $cli->save();

        return response()->json(['mensagem' => 'Cliente atualizado com sucesso'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //implementar validação
        
        Cliente::destroy($id);
        return response()->json('Cliente excluído com sucesso', 200);
    }
    
    public function consultaCpf(Request $request){
        
        $titulos = Cliente::getTituloCpf($request->cpf);
        
        if(count($titulos) == 0)
            return response()->json(['mensagem' => 'Nenhum título encontrado.'], 404);

        foreach ($titulos as $titulo){
            $jsonTitulos[] = ["id_titulo" => $titulo->id,
                "descricao" => $titulo->descricao,
                "situacao" => $titulo->situacao,
                "valor" => $titulo->valor,
            ];
        }
        
        return response()->json($jsonTitulos, 200);
    }
}
