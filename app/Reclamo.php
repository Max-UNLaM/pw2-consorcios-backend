<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $fillable = ['motivo', 'fecha_reclamo', 'fecha_resolucion', 'conforme'];
}
