<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $fillable = ['nombre', 'direccion', 'localidad', 'provincia', 'consorcio_id'];
}
