<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $fillable = ['usuario_id', 'unidad_id', 'motivo', 'fecha_reclamo', 'fecha_resolucion', 'conforme'];
}
