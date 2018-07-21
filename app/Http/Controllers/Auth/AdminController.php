<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 26/06/18
 * Time: 21:25
 */

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;


class AdminController extends Controller
{

    public function addRoles(Request $request)
    {
        return response([
          "k" => "ok"
            ]
        );
     #   $recibido         = $request->all();
     #   $user             = User::find($recibido['user-id']);
     #   $success['token'] = $user->createToken('ConsorcioLoco', $recibido['scopes'])->accessToken;
    }
}