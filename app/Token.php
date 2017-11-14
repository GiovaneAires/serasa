<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['token', 'expira_em'];
    
    public function User() {
        return $this->hasOne(new Usuario, 'id', 'usuario_id');
    }
}
