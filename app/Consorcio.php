<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consorcio extends Model
{
    protected $fillable = ['nombre', 'direccion', 'cuit', 'codigo_postal', 'localidad', 'provincia', 'telefono', 'email', 'cantidad_de_pisos', 'departamentos_por_piso'];

    public static function obtenerPropietarios($consorcio_id)
    {
        $unidadesDelConsorcio = Unidad::obtenerPropietariosPorIdConsorcio($consorcio_id);

        return $unidadesDelConsorcio;


        $idDePropietarios = $unidadesDelConsorcio->get(['usuario_id']);//->unique();

        return $idDePropietarios;

        $propietarios = array();

        foreach ($idDePropietarios as $idPropietario) {
            $propietarios . push(User::find($idPropietario));
        }

        return $propietarios;
    }

    public static function getAllConsorciosOfUser($userId)
    {
        return DB::table('consorcios')
            ->join('unidads', 'unidads.consorcio_id', '=', 'consorcios.id')
            ->join('users', 'users.id', '=', 'unidads.usuario_id')
            ->addSelect(['consorcios.nombre as nombre', 'consorcios.direccion as direccion', 'consorcios.localidad as localidad'])
            ->groupBy('consorcios.id')
            ->where('usuario_id', $userId);
    }
}
