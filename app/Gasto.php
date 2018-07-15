<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $fillable = ['id', 'nombre', 'valor', 'fecha', 'proveedor_id', 'consorcio_id'];
}
