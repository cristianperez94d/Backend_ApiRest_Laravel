<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_carros', function (Blueprint $table) {
            $table->id('id_car');
            $table->unsignedBigInteger('usuario_id'); //llave foranea
            $table->string('titulo_car',255);
            $table->text('descripcion_car');
            $table->string('precio_car',30);
            $table->string('estado_car',30);
            $table->timestamps();
            $table->foreign('usuario_id')
                ->references('id_usu')->on('t_usuarios')
                ->onDelete('cascade')
                ->onUpdate('cascade'); //Definiendo la relacion
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_carros');
    }
}
