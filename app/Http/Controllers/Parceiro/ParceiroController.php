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
    
    private static $mensagens = [
                        'required' => 'Campo obrigatório não informado.',
                        'max' => 'O tamanho máximo do campo foi ultrapassado.',
                        'email' => 'Email inválido.',
                    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parceiros = Parceiro::where('id', '=', $request->id)->with('User')->first();
        $response = !empty($parceiros) ? response()->json($parceiros, 200) : response()->json('Parceiro não encontrado', 400);
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
        $vr = validator($request->all(), $rules->required(), self::$mensagens);
        if ($vr->fails()){
            return response()->json($vr->getMessageBag(), 400);
        }
        
        $v = validator($request->all(), $rules->rules(), self::$mensagens);
        if ($v->fails()){
            return response()->json($v->getMessageBag(), 422);
        }
        
        $parc = Parceiro::getParceiroCadastrado($request->cnpj, $request->email);
        if(count($parc)){
            return response()->json("Parceiro já cadastrado.", 409);
        }
        
        $usuario = new Usuario();
        $usuario->fill(['nome' => $request->nome_usuario, 'senha' => $request->senha, 'email' => $request->email, 'situacao' => 1]);
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
    public function update(Request $request, $id)
    {
        //USAR VALIDADOR
        if(empty($request->id) || !is_numeric($request->id))
            return response()->json('Id do parceiro inválido, deve ser do tipo inteiro', 400);
        
        if($request->getMethod() == 'DELETE'){
            Usuario::where('id', $request->id)
                ->update(['situacao' => 0]);
            
            return response()->json('Parceiro inativado com sucesso', 200);
        }else{
            $rules = new StoreParceiroRequest();
            $vr = validator($request->all(), $rules->required(), self::$mensagens);
            if ($vr->fails()){
                return response()->json($vr->getMessageBag(), 400);
            }

            $v = validator($request->all(), $rules->rules(), self::$mensagens);
            if ($v->fails()){
                return response()->json($v->getMessageBag(), 422);
            }

            Usuario::where('id', $request->id)
                ->update(['nome' => $request->nome_usuario, 'senha' => $request->senha, 'email' => $request->email, 'situacao' => $request->situacao]);

            Parceiro::where('id', $request->id)
                ->update(['cnpj' => $request->cnpj, 'nome_fantasia' => $request->nome_fantasia, 'razao_social' => $request->razao_social]);
            
            return response()->json('Parceiro atualizado com sucesso', 200);
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
