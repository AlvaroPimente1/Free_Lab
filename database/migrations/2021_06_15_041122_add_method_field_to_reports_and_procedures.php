<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMethodFieldToReportsAndProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->text('method')->nullable();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->text('method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('method')->nullable();
        });

        Schema::table('procedures', function (Blueprint $table) {
            $table->dropColumn('method')->nullable();
        });
    }
}
