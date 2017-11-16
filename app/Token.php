<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $timestamps = false;

    protected $fillable = ['token', 'expira_em'];
    
    public function usuario(){
        return $this->belongsTo('App\Usuario', 'id', 'usuario_id');
    }
    
    public function User() {
        return $this->hasOne(new Usuario, 'id', 'usuario_id');
    }
}
