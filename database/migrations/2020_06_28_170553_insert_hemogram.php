<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertHemogram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('procedures')->insert([
            'name' => 'Hemograma',
            'mnemonic' => 'HEM',
            'fields' => 'Hemácias;Hematócrito;Hemoglobina;VCM;HCM;CHCM;Leucócitos;Plaquetas;Eosinófilos;Linfócitos;Segmentados;Monócitos;Velocidade de Sedimentação - VHS'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('DELETE FROM procedures WHERE NAME = ?', ['Hemograma']);
    }
}
