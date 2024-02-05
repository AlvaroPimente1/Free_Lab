<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('profession');
            $table->date('birthday');
            $table->integer('height'); // In Centimeters
            $table->integer('weight'); // In KGs, ignore decimals
            $table->timestamps();
            $table->string('address');
            $table->string('extras')->nullable();

            // BR part

            $table->string('cpf', 14)->unique();
            $table->string('cep', 8);
            $table->string('neighborhood');
            $table->string('state', 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
