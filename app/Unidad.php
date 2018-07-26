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

    public static function getUnidadsIdByConsorcioId($consorcioId){
        $unidades = DB::table('unidads')
            ->where('consorcio_id', $consorcioId)
            ->get()
            ->unique();

        foreach ($unidades as $unidad){
            $respuesta[] = $unidad->id;
        }

        return $respuesta;
    }

    public static function list(){
        return DB::table('unidads')
            ->join('consorcios', 'unidads.consorcio_id', '=', 'consorcios.id')
            ->join('users', 'users.id', '=', 'unidads.usuario_id')
            ->addSelect([
                'unidads.id as id',
                'unidads.nombre as nombre',
                'users.id as usuario_id',
                'users.name as usuario_nombre',
                'consorcios.id as consorcio_id',
                'consorcios.nombre as consorcio_nombre',
            ]);
    }

    public static function unidadById($unidadId){
        return Unidad::list()->where('unidads.id', $unidadId);
    }

    public static function unidadsByUser($usuarioId){
        return Unidad::list()->where('usuario_id', $usuarioId);
    }
}
