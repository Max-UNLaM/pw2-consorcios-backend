<?php

namespace App\Http\Controllers;

use App\Rol;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->get('id');
        if ($id) return User::find($id);
        $size = $request->get('size') ? $request->get('size') : 5;
        $status = $request->get('status');
        if ($status && $status != 'ACTIVO' && $status != 'INACTIVO' && $status != 'APROBACION_PENDIENTE') return response("Los posibles valores del parametro status son ACTIVO, INACTIVO y APROBACION_PENDIENTE", 400);
        if ($status) return User::filterByStatus($status)->paginate($size);
        return User::list()->paginate($size);
    }

    public function update(Request $request)
    {
        $id = $request->get('id');
        if (!$id) return response("El campo id es obligatorio", 400);
        $rolId = $request->get('rol_id');
        $status = $request->get('estado');
        if (!$rolId && !$status) return response("Debe enviar al menos uno de estos campos: rol_id, estado");

        if ($rolId && Rol::exists($rolId) == 0) {
            $roles = Rol::all();
            $rolesString = "";

            foreach ($roles as $rol) {
                $str = $rol->id . ' = ' . $rol->nombre;
                $rolesString .= $str . ' ';
            }

            return response("El rol_id ingresado es invalido. Los valores posibles son: " . $rolesString, 400);
        }

        if ($status && $status != 'ACTIVO' && $status != 'INACTIVO' && $status != 'APROBACION_PENDIENTE') return response("El estado ingresado no es valido, los posibles valores son: ACTIVO, INACTIVO y APROBACION_PENDIENTE", 400);

        $user = User::find($id);
        $user->rol_id = $rolId;
        $user->estado = $status;
        if ($request->get('name')) $user->name = $request->get('name');
        if ($request->get('email')) $user->email = $request->get('email');
        if ($request->get('password')) $user->password = bcrypt($request->get('password'));
        if ($request->get('administra_consorcio')) $user->administra_consorcio = $request->get('administra_consorcio');
        if ($request->get('dni')) $user->dni = $request->get('dni');
        $user->update();
        return $user;
    }

    public function userCreate(Request $request)
    {
        $nombre = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');
        $dni = $request->get('dni');

        if (!$nombre) return response("El campo name es obligatorio", 400);
        if (!$email) return response("El campo email es obligatorio", 400);
        if (!$password) return response("El campo password es obligatorio", 400);
        if (!$dni) return response("El campo dni es obligatorio", 400);

        $user = User::create([
            'name' => $nombre,
            'email' => $email,
            'password' => bcrypt($password),
            'dni' => $dni,
            'estado' => "APROBACION_PENDIENTE",
            'rol_id' => 3
        ]);
        return $user;
    }

    public function read(Request $request)
    {
        $id = $request->get('id');
        $user = User::find(Auth::user()->getAuthIdentifier());
        if ($user->rol_id == 3 && $id != $user->id) {
            return response('Forbidden', 403);
        }
        if ($id) {
            return User::find($id);
        } else {
            return $user;
        }
    }
}
