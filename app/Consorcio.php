<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consorcio extends Model
{
    protected $fillable = ['nombre', 'direccion', 'localidad', 'provincia', 'telefono'];

}
