<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialToReportsTable extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('material'); 
        });
    }
    
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('material'); // Remove o campo 'material' caso haja rollback
        });
    }
    
}
