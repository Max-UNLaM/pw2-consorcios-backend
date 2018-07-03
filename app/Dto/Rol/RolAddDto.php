<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 02/07/18
 * Time: 23:01
 */

namespace App\Dto\Rol;



class RolAddDto
{
    public $usuarioId;
    public $rol = "";

    /**
     * @return mixed
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * @param int $usuarioId
     */
    public function setUsuarioId(int $usuarioId): void
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * @return string
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * @param string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }


}