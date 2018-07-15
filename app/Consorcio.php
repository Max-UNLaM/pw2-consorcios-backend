<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consorcio extends Model
{
    protected $fillable = ['nombre', 'direccion', 'localidad', 'provincia', 'telefono'];

}
