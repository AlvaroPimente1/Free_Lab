<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoroColumnsToProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->boolean('soro_lipemico')->default(false);
            $table->boolean('soro_hemolisado')->default(false);
            $table->boolean('soro_icterico')->default(false);
            $table->string('soro_outro', 50)->nullable()->default(null);
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
            $table->dropColumn('soro_lipemico');
            $table->dropColumn('soro_hemolisado');
            $table->dropColumn('soro_icterico');
            $table->dropColumn('soro_outro');
        });
    }
}
