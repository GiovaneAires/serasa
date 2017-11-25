<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
           /* $table->string('valor', int)->unique();
                $table->string('data_pagamento', date);
                $table->integer('identificador', int)->unique();
                $table->string('data_emissao', date);
                $table->integer('situacao', int);
                */
class TituloTipo extends Model
{
    protected $fillable = ['valor', 'data_pagamento', 'identificador', 'data_emissao', 'situacao'];
    
    public function usuario(){
        return $this->belongsTo('App\Usuario', 'id', 'tituloTipo_id');
    }
    
    public function User() {
        return $this->hasOne(new Usuario, 'id', 'tituliTipo_id');
    }
    
    public static function getTituloTipoCadastrado($data_pagamento, $identificador, $data_emissao, $situacao) {
        return DB::table('titulos_tipo')
                    ->join('titulos_tipo', 'titulos.id', '=', 'titulos.titulosTipo_id')
                    ->where('valor', '=', $valor)
                    ->orWhere('data_pagamento', '=', $data_pagamento)
                    ->orWhere('identificador', '=', $identificador)
                    ->orWhere('data_emissao', '=', $data_emissao)
                    ->orWhere('situacao', '=', $situacao)
                    ->first();
    }
}

