<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    // Allows for filling on all fields of the patients
    protected $fillable = ['name', 'birthday', 'email', 'phone', 'profession', 'birthday', 'height', 'weight', 'address', 'cpf', 'cep', 'neighborhood', 'state'];

    //Relação 1 para muitos entre paciente e laudo (Um paciente pode ter vários laudos)
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    //Relação muitos para muitos entre paciente e laboratório
    public function laboratories()
    {
        return $this->belongsToMany(Laboratory::class,'laboratory_patient');
    }
}
