<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Parceiro extends Model
{
    protected $fillable = ['cnpj', 'nome_fantasia', 'razao_social'];
    
    public function usuario(){
        return $this->belongsTo('App\Usuario', 'id', 'usuario_id');
    }
    
    public function User() {
        return $this->hasOne(new Usuario, 'id', 'usuario_id');
    }
    
    public static function getParceiroCadastrado($cnpj, $email, $nome) {
        return DB::table('usuarios')
                    ->join('parceiros', 'usuarios.id', '=', 'parceiros.usuario_id')
                    ->where('cnpj', '=', $cnpj)
                    ->orWhere('email', '=', $email)
                    ->orWhere('nome', '=', $nome)
                    ->first();
    }
    
    public static function getTituloCpf($cpf, $situacao = null){
        
        if(!is_null($situacao)){
            return DB::table('clientes')
                    ->join('titulos', 'clientes.id', '=', 'titulos.cliente_id')
                    ->select('titulos.*')
                    ->where('cpf', '=', $cpf)
                    ->where('situacao', '=', $situacao)
                    ->get();
        }else{
            return DB::table('clientes')
                    ->join('titulos', 'clientes.id', '=', 'titulos.cliente_id')
                    ->select('titulos.*')
                    ->where('cpf', '=', $cpf)
                    ->get();
        }
    }
}
