<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitulosTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titulos_tipos', function (Blueprint $table) {
            $table->increments('id');
                $table->string('valor', int)->unique();
                $table->string('data_pagamento', date);
                $table->integer('identificador', int)->unique();
                $table->string('data_emissao', date);
                $table->integer('situacao', int);
                $table->intger('id_titulo_tipo', int)->unique();
                $table->intger('id_cliente', int)->unique();
                $table->intger('id_parceiro', int)->unique();
                $table->foreign('titulos_tipo_id')->references('id')->on('titulos_tipo');
                $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('titulos_tipos');
    }
}
