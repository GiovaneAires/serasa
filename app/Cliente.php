<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
                    ->join('cliente', 'cliente.id', '=', 'clientes.cliente_id')
                    ->where('cpf', '=', $cpf)
                    ->orWhere('nome_cliente', '=', $nome_cliente)
                    ->first();
    }
}
