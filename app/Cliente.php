<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    protected $fillable = ['nome_cliente', 'cpf'];
    
    public function cliente(){
        return $this->belongsTo('App\Cliente', 'id', 'cliente_id');
    }
    
    public function User() {
        return $this->hasOne(new Usuario, 'id', 'cliente_id');
    }
    
    public static function getClienteCadastrado($cpf, $nome_cliente) {
        
        return DB::table('clientes')
                    ->where('cpf', '=', $cpf)
                    ->orWhere('nome_cliente', '=', $nome_cliente)
                    ->first();
    }
    
    public static function getTituloCpf($cpf){
        return DB::table('clientes')
                ->join('titulos', 'clientes.id', '=', 'titulos.cliente_id')
                ->select('titulos.*')
                ->where('cpf', '=', $cpf)
                ->get();
    }
}
