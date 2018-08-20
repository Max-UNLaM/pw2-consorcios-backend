<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 31/07/18
 * Time: 23:18
 */

namespace App\Model;


class Errores
{
    private $valor;
    private $mensaje;
    private $tipo;

    public function __construct($valor, $mensaje, $tipo)
    {
        $this->mensaje = $mensaje;
        $this->valor = $valor;
        $this->tipo = $tipo;
    }

    public function validar() {

    }



}