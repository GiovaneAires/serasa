<?php

namespace App\Http\Controllers\Titulo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Titulo;
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
        
        $jsonTitulos = [
            'id_titulo' => $titulos->id,
            'id_parceiro' => $titulos->parceiro_id,
            'id_cliente' => $titulos->cliente_id,
            'situacao' => $titulos->situacao,
            'descricao' => $titulos->descricao,
            'valor' => $titulos->valor,
            'data_pagamento' => $titulos->data_pagamento,
            'data_emissao' => $titulos->data_emissao
        ];
        
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rules = new StoreRequestTitulos;
        //implementar validação
        
        $titulo = new Titulo;
        $titulo->fill([
                'parceiro_id' => $request->id_parceiro,
                'cliente_id' => $request->id_cliente,
                'situacao' => $request->situacao,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'data_emissao' => $request->data_emissao,
                'data_pagamento' => $request->data_pagamento
            ]);
        $titulo->save();
        
        return response()->json('Título inserido com sucesso.', 200);
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
               
        $titulo = Titulo::where('id', '=', $id);
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
