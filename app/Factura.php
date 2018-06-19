<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
	protected $fillable = ['nombre', 'direccion', 'localidad', 'provincia'];
}
