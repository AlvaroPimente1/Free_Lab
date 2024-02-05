<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertProceduresToProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::delete('DELETE FROM procedures WHERE NAME = ?', ['Hemograma']);
        DB::delete('DELETE FROM procedures WHERE NAME = ?', ['Bioquímico']);
        // Adicionar qualquer procedimento que deve ser adicionado abaixo, e também no down
        DB::table('procedures')->insert([
            'name' => 'Hemograma',
            'mnemonic' => 'HEM',
            'method' => 'Automático',
            'fields' => 'Hemácias!-!10000!@#;Hematócrito!-!10000!@#Hemoglobina!-!10000!@#VCM!-!10000!@#HCM!-!10000!@#CHCM!-!10000!@#Leucócitos!-!10000!@#Plaquetas!-!10000!@#Eosinófilos!-!10000!@#Linfócitos!-!10000!@#Segmentados!-!10000!@#Monócitos!-!10000!@#Velocidade de Sedimentação - VHS!-!10000'
        ]);

        DB::table('procedures')->insert([
            'name' => 'Bioquímico',
            'mnemonic' => 'BQM',
            'method' => 'Automático',
            'fields' => 'Glicose!-!70–105 mg/dL!@#Colesterol!-!≥ 40 mg/dL!@#Triglicerídeos!-!< 250 mg/dL'
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
        DB::delete('DELETE FROM procedures WHERE NAME = ?', ['Bioquímico']);
    }
}
