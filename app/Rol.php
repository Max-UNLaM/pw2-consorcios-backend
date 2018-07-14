<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $fillable = [
        'nombre', 'scope'
    ];

    public function customCreate(array $rol)
    {
        if ($this->verifyUniqueScope($rol)) {
            $this::create([
                'nombre' => $rol['nombre'],
                'scope' => $rol['scope']
            ]);
            return true;
        } else {
            return false;
        }
    }


    /**
     * Get first Rol of specified name
     *
     * @param  string  $name
     * @return \App\Rol
     */
    public function getFirstByName(string $name) {
        $rolCollection = $this::where('nombre', $name)->get();
        return $rolCollection->first();
    }

    protected function verifyUniqueScope(array $rol)
    {
        if (array_key_exists($rol, 'scope')) {
            return $this::where('scope', $rol['scope'])
                ->get();
        } else {
            return null;
        }
    }

}


