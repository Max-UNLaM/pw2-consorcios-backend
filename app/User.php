<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getUserIdsByConsorcioId($consorcioId){
        $usuarios = DB::table('unidads')
            ->where('consorcio_id', $consorcioId)
            ->get(['usuario_id'])
            ->unique();

        foreach ($usuarios as $usuario){
            $respuesta[] = $usuario->usuario_id;
        }

        return $respuesta;

    }

    public function isOperator(){
        if($this->rol_id == 2){ //2 es el id del rol operator
            return 1;
        } else {
            return 0;
        }
    }
}
