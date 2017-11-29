<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    protected $fillable =  ['parceiro_id', 'parceiros', 'cliente_id', 'clientes', 'valor', 'descricao', 'data_emissao', 'data_pagamento'];
}
