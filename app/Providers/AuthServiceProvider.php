<?php

namespace App\Providers;

use App\Laboratory;
use App\Patient;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        Schema::defaultStringLength(191); //From MariaDB

        //Funções de autorizações
        $this->registerPolicies($gate);

        //Autorização para usuário root
        $gate->define('support', function(User $user){
            return $user->role === 'root';
        });

        //Autorização para usuário root e administrador de um lab
        $gate->define('administrator', function(User $user, Laboratory $laboratory){
            /* foreach ($user->administrator as $lab) {
                if (($lab->id == $laboratory->id) && ($laboratory->enabled > 0)) {
                    return true;
                }
            } */
            if ($user->role == 'root') {
                return true;
            }
            return ($user->hasAdminLab($laboratory)&&($laboratory->enabled > 0));
        });

        //Autorização para usuário root e administrador de um lab
        $gate->define('edit-lab', function(User $user, Laboratory $laboratory){
            if ($user->role == 'root') {
                return true;
            }
            foreach ($user->administrator as $lab) {
                if ($lab->id == $laboratory->id) {
                    if(($laboratory->enabled > 0)){
                        return true;
                    }
                }
            }
        });

        //Autorização para usuário administrador de um lab
        $gate->define('administrators-lab', function(User $user, Laboratory $laboratory, User $usr){
            foreach ($laboratory->administrator as $adm) {
                if($adm->id == $usr->id){
                    if(($laboratory->enabled > 0)){
                        return true;
                    }
                }
            }
        });

        //Autorização para qualquer usuário de um lab
        $gate->define('laboratory-users', function(User $user, Laboratory $laboratory, User $usr){
            foreach ($usr->labs as $lab) {
                if (($lab->id === $laboratory->id) ?? false) {
                    if(($laboratory->enabled > 0)){
                        return true;
                    }
                }
            }
        });

        //Autorização para pacientes de um lab
        $gate->define('patient_lab', function(User $user ,$patient , $laboratory){
            foreach ($patient->laboratories as $lab) {
                if ($lab->id == $laboratory->id) {
                    if(($laboratory->enabled > 0)){
                        return true;
                    }
                }
            }
        });

        //Autorização para laudos de um lab
        $gate->define('report_lab', function(User $user, $laboratory, $report){
                if ($report->laboratory->id == $laboratory->id) {
                    if(($laboratory->enabled > 0)){
                        return true;
                    }
                }
        });
    }
}
