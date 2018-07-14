<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expensa extends Model
{
    protected $fillable = ['id', 'unidad_id', 'año', 'mes', 'estado', 'emision', 'vencimiento', 'importe'];
}
