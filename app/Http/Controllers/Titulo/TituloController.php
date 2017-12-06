<?php

namespace App\Http\Controllers\Titulo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Titulo;
use App\Parceiro;
use App\Http\Requests\StoreRequestTitulos;

class TituloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titulos = Titulo::all();
        
        foreach ($titulos as $titulo){
            $jsonTitulos[] = [
                'id_titulo' => $titulo->id,
                'id_parceiro' => $titulo->parceiro_id,
                'id_cliente' => $titulo->cliente_id,
                'situacao' => $titulo->situacao,
                'descricao' => $titulo->descricao,
                'valor' => $titulo->valor,
                'data_pagamento' => $titulo->data_pagamento,
                'data_emissao' => $titulo->data_emissao
            ];
        }
        
        $response = !empty($titulos) ? response()->json($jsonTitulos, 200) : response()->json('Nenhum título encontrado', 404);
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
        $rules = new StoreRequestTitulos;
        //implementar validação
        
        $parceiro = Parceiro::where('id', '=', $request->id)->with('User')->first();
        if(count($parceiro) == 0)
            return response()->json(['mensagem' => 'Parceiro inválido.'], 409);
        
        $titulo = new Titulo;
        $titulo->fill([
                'parceiro_id' => $parceiro->id,
                'cliente_id' => $request->id_cliente,
                'situacao' => $request->situacao,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'data_emissao' => $request->data_emissao,
                'data_pagamento' => isset($request->data_pagamento) ? $request->data_pagamento : null
            ]);
        $titulo->save();
        
        return response()->json('Título inserido com sucesso.', 200);
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
        $rules = new StoreRequestTitulos;
        //implementar validação
               
        $titulo = Titulo::find($id);
        $titulo->valor = isset($request->valor) ? $request->valor : $titulo->valor;
        $titulo->descricao = isset($request->descricao) ? $request->descricao : $titulo->descricao;
        $titulo->situacao  = isset($request->situacao) ? $request->situacao : $titulo->situacao;
        $titulo->data_emissao = isset($request->data_emissao) ? $request->data_emissao : $titulo->data_emissao;
        $titulo->data_pagamento = isset($request->data_pagamento) ? $request->data_pagamento : $titulo->data_pagamento;
        $titulo->save();

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
        
        Titulo::destroy($id);
        return response()->json('Título excluído com sucesso', 200);
    }
}
