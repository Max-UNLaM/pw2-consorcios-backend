<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $fillable = ['mes', 'anio', 'consorcio_id', 'valor'];
}
