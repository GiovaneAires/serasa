<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    protected $fillable = ['cnpj', 'nome_fantasia', 'razao_social'];
    
    public function usuario(){
        return $this->belongsTo('App\Usuario', 'id', 'usuario_id');
    }
    
     public function User() {
        return $this->hasOne(new Usuario, 'id', 'usuario_id');
    }
}
