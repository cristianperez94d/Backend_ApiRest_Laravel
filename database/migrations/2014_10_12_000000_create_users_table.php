<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_usuarios', function (Blueprint $table) {
            $table->id('id_usu');
            $table->string('nombre_usu',255);
            $table->string('rol_usu',20);
            $table->string('email_usu')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password_usu',255);
            $table->rememberToken();
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
        Schema::dropIfExists('t_usuarios');
    }
}
