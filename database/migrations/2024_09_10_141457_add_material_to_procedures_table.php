<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialToProceduresTable extends Migration
{

    public function up()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->string('material'); 
        });
    }

    public function down()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->dropColumn('material'); // Remove o campo 'material' caso haja rollback
        });
    }

}
