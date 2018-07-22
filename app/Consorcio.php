<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consorcio extends Model
{
    protected $fillable = ['nombre', 'direccion', 'cuit', 'codigo_postal', 'localidad', 'provincia', 'telefono', 'email', 'cantidad_de_pisos', 'departamentos_por_piso'];

}
