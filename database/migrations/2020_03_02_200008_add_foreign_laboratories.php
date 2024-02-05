<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignLaboratories extends Migration
{
    /**
     * Adds laboratory foreign keys and assigns them.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->unsignedBigInteger('laboratory_id')->nullable(); // Remember to delete nullable later on

        //     $table->foreign('laboratory_id')
        //         ->references('id')
        //         ->on('laboratories')
        //         ->onDelete('set null'); // Remember to set to cascade later on
        // });

        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('laboratory_id')->nullable();
            $table->unsignedBigInteger('signer_id')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();

            // Do not delete reports when the one responsible for them is deleted, instead maintain the report for future use.
            $table->foreign('laboratory_id')
                ->references('id')
                ->on('laboratories')
                ->onDelete('set null');

            $table->foreign('signer_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('laboratory_id');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('laboratory_id');
            $table->dropColumn('responsible_id');
        });
    }
}
