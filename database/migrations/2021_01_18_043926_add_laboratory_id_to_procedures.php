<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLaboratoryIdToProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('laboratories')->insert([
            'name' => 'Lab Teste',
            'mnemonic' => 'LABT'
        ]);

        Schema::table('procedures', function (Blueprint $table) {
            $table->unsignedBigInteger('laboratory_id')->default('1');

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
        Schema::table('procedures', function (Blueprint $table) {
            Schema::table('procedures', function (Blueprint $table) {
                $table->dropColumn('laboratory_id');
            });
        });
    }
}
