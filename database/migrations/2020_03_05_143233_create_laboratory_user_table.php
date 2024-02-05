<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoryUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratory_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('laboratory_id');
            $table->unsignedBigInteger('user_id');
            $table->string('permission')->default('default');
            $table->timestamps();

            $table->unique(['laboratory_id', 'user_id']);

            // If a laboratory is deleted, it cascades to delete all other entries between users
            $table->foreign('laboratory_id')
                ->references('id')
                ->on('laboratories')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('laboratory_user');
    }
}
