<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterarCamposNullPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Altera a coluna para aceitar valores nulos
            $table->string('name')->nullable(false)->change();
            $table->date('birthday')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('profession')->nullable()->change();
            $table->integer('height')->nullable()->change();
            $table->integer('weight')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('cpf')->nullable()->change();
            $table->string('cep')->nullable()->change();
            $table->string('neighborhood')->nullable()->change();
            $table->string('state', 2)->nullable()->change();
            $table->string('extras', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Se você precisar reverter a alteração, você pode definir a coluna de volta para NOT NULL
            $table->string('profession')->nullable(false)->change();
            // Repita este processo para outras colunas que você deseja reverter para NOT NULL
        });
    }
}