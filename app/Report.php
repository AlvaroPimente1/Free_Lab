<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'mnemonic', 
        'body', 
        'requester', 
        'laboratory_id', 
        'conclusion', 
        'method',
        'soro_lipemico',
        'soro_hemolisado',
        'soro_icterico',
        'soro_outro'
    ];

    // Relação muitos para 1 entre laudo e usuário (Um médico pode gerar vários laudos)
    public function signer() // For the doctor who has signed the report, signalizing that the report is in fact correct
    {
        return $this->belongsTo(User::class, 'signer_id');
    }

    // Relação muitos para 1 entre laudo e paciente (Um Paciente pode ter vários laudos)
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relação muitos para 1 entre laudo e laboratório (Um lab pode ter vários laudos)
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    // Relação muitos para 1 entre laudo e modelo de laudo (Um modelo de laudo pode ter vários laudos)
    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }
}
