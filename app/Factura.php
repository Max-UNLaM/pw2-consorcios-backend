<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
	protected $fillable = ['gasto_id', 'direccion', 'localidad', 'provincia'];
}
