<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 31/07/18
 * Time: 23:09
 */

namespace App\Utils;


class Errors
{
    private $listaDeErrores = [];

    public function pushError(string $mensaje)
    {
        array_push($this->listaDeErrores, $mensaje);
    }

    public function validarErrores($array) {

    }

    public function getErrorLoco()
    {
        if (array_count_values($this->listaDeErrores) > 1) {
            return $this->listaDeErrores;
        } else {
            return $this->listaDeErrores[0];
        }
    }
}