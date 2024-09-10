<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = [
        'name', 
        'mnemonic', 
        'fields', 
        'textmodel', 
        'conclusion', 
        'method', 
        'soro_lipemico', 
        'soro_hemolisado', 
        'soro_icterico', 
        'soro_outro',
        'material',
        'status'
    ];

    // Relação 1 para muitos entre modelos de laudos e laudo (Um modelo de laudo pode ter vários laudos)
    public function report()
    {
        return $this->hasMany(Report::class, 'procedure_id');
    }

    // Relação muitos para 1 entre modelos de laudos e laboratório (Um lab pode ter vários modelos de laudos)
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class, 'laboratory_id');
    }
}
