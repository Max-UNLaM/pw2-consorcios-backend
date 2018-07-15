<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gasto extends Model
{
    protected $fillable = ['nombre', 'valor', 'fecha', 'proveedor_id', 'consorcio_id'];

    protected function gastosMensual(string $anio, string $mes)
    {
        return DB::table('gastos')
            ->where('fecha', '>=', "${anio}-${mes}-00")
            ->where('fecha', '<=', "${anio}-${mes}-31");
    }

    public function importeGastosMensual(string $anio, string $mes)
    {
        return $this->gastosMensual($anio, $mes)
            ->sum('valor');
    }
}
