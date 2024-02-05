<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class InsertUserAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $password = env('ADMIN_PASSWORD', 'admin');

        DB::table('users')->insert([
            'name' => 'Administrador',
            'cpf' => '000.000.000-00',
            'password' => Hash::make($password),
            'role' => 'root'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // $cpf = env('ADMIN_EMAIL', 'admin@admin.com');

        // DB::delete('DELETE FROM users WHERE email = ?', [$email]);
    }
}
