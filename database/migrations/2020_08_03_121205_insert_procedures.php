<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adicionar qualquer procedimento que deve ser adicionado abaixo, e também no down
        DB::table('procedures')->insert([
            'name' => 'Bioquímico',
            'mnemonic' => 'BQM',
            'fields' => 'Glicose; Colesterol; Colesterol Fração - HDL; Colesterol Fração - LDL; Colesterol Fração - VLDL; Triglicerídeos; TGO/AST; TGP/ALT; Gama GT; Fosfatase Alcalina; Uréia; Creatinina; Ácido Úrico; PCR - Proteína C Reativa; ASO - Antiestreptolisina O; Bilirrubina Total; Bilirrubina Direta'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('DELETE FROM procedures WHERE NAME = ?', ['Bioquímico']);
    }
}
