<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    //Relação muitos para muitos entre usuário e laboratório (Administradores do lab)
    public function administrator()
    {
        return $this->belongsToMany(User::class, 'administrator_laboratory');
    }

    //Relação muitos para muitos entre usuário e laboratório (Qualquer usuário de um lab)
    public function employees()
    {
        return $this->belongsToMany(User::class, 'laboratory_user');
    }

    //Relação 1 para muitos entre laboratório e laudo (Um lab pode ter vários laudos)
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    //Relação muitos para muitos entre laboratório e paciente
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'laboratory_patient');
    }

    //Relação 1 para muitos entre laboratório e laudo (Um lab pode ter vários modelos de laudos)
    public function procedures()
    {
        return $this->hasMany(Procedure::class);
    }
}
