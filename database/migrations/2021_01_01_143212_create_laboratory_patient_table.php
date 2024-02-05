<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoryPatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratory_patient', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('laboratory_id');
            $table->unsignedBigInteger('patient_id');
            $table->timestamps();

            $table->unique(['laboratory_id', 'patient_id']);

            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->foreign('laboratory_id')
                ->references('id')
                ->on('laboratories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laboratory_patient');
    }
}
