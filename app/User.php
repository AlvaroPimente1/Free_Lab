<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Access;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cpf', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relação muitos para muitos entre paciente e laboratório (Usuários que tem acesso ao lab)
    public function labs()
    {
        return $this->belongsToMany(Laboratory::class, 'laboratory_user');
    }

    //Relação muitos para muitos entre paciente e laboratório (Usuários administrador de um lab)
    public function administrator()
    {
        return $this->belongsToMany(Laboratory::class, 'administrator_laboratory');
    }

    //Verifica se o usuário é administrador do lab
    public function hasAdminLab($lab)
    {
        return $this->administrator->contains($lab);
    }

    //Relação 1 para muitos entre usuário e acessos (Um usuário pode ter vários acessos)
    public function accesses()
    {
        return $this->hasMany(Access::class);
    }

    public function registerAccess()
    {
        // Cadastra na tabela accesses um novo registro com as informações do usuário logado + data e hora
        return $this->accesses()->create([
            'user_id'   => $this->id,
        ])->save();
    }
}
