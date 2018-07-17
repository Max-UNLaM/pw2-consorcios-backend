<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expensa extends Model
{
    protected $fillable = ['unidad_id', 'año', 'mes', 'estado', 'emision', 'vencimiento', 'importe'];

    public static function userGetAllUsersExpensas(string $userId)
    {
        return DB::table('expensas')
            ->join('unidads', 'expensas.unidad_id', '=', 'expensas.id')
            ->join('users', 'users.id', '=', 'unidads.usuario_id')
            ->select('expensas.*', 'unidads.nombre as unidad_nombre')
            ->where('users.id', '=', $userId);
    }

    public static function crearExpensaConImporte(Expensa $expensaNueva)
    {
        $gasto = new Gasto();
        $expensaNueva['importe'] = ($gasto->importeGastosMensualConsorcio($expensaNueva['año'], $expensaNueva['mes'], $expensaNueva['consorcio_id']) * 1.2) * Unidad::calcularCoeficiente($expensaNueva['unidad_id']);
        return Expensa::create($expensaNueva);
    }

    public static function listByUnidad($unidadId)
    {
        return DB::table('expensas')
            ->where('unidad_id', $unidadId)
            ->get();
    }
}
