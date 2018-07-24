<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Unidad extends Model
{
    protected $fillable = ['nombre', 'consorcio_id', 'usuario_id'];


    public static function calcularCoeficiente(int $id)
    {
        $total = DB::table('unidads')
            ->where('consorcio_id', Unidad::obtenerSucursal($id)->id)
            ->count();

        return ($total != 0) ? (1 / $total) : 1;
    }

    public static function getIdAllUnidadOfUser(int $userId) {
        return DB::table('unidads')
            ->where('usuario_id', $userId)
            ->get(['id']);
    }

    public static function obtenerIdUnidadesPorIdConsorcio(int $consorcio_id) {
        return DB::table('unidads')
            ->where('consorcio_id', $consorcio_id)
            ->get(['id']);
    }

    public static function obtenerUnidadesPorIdConsorcio(int $consorcio_id) {
        return DB::table('unidads')
            ->where('consorcio_id', $consorcio_id)
            ->get();
    }

    public static function obtenerPropietariosPorIdConsorcio(int $consorcio_id) {
        $idUsuarios = DB::table('unidads')
            ->where('consorcio_id', $consorcio_id)
            ->get(['usuario_id'])
            ->unique();

        foreach ($idUsuarios as $idUsuario){
            $usuarios[] = DB::table('users')
                ->where('id', $idUsuario->usuario_id)
                ->get()[0];
        }

        return $usuarios;
    }

    public static function getAllUnidadOfUser(int $userId) {
        return DB::table('unidads')
            ->where('usuario_id', $userId)
            ->get();
    }

    public static function getAllUnidadIdOfUser(int $userId) {
        $unidads = Unidad::getAllUnidadOfUser($userId);

        foreach ($unidads as $unidad){
            $response[] = $unidad->id;
        }

        return $response;
    }

    public static function obtenerUnidadesPorUsuarioYConsorcio($usuarioId, $consorcioId) {
        $unidades = DB::table('unidads')
            ->where('usuario_id', $usuarioId)
            ->where('consorcio_id', $consorcioId)
            ->get();

        return $unidades;
    }


    public static function obtenerIdDeUnidadesPorUsuarioYConsorcio($usuarioId, $consorcioId) {
        $idDeUnidades = DB::table('unidads')
            ->where('usuario_id', $usuarioId)
            ->where('consorcio_id', $consorcioId)
            ->get(['id'])
            ->unique();

        foreach ($idDeUnidades as $id){
            $respuesta[] = $id->id;
        }

        return $respuesta;
    }


    public static function obtenerSucursal(int $id) {
        return DB::table('unidads')
            ->where('id', $id)
            ->get()[0];
    }
}
