<?php
/**
 * Created by PhpStorm.
 * User: maximiliano
 * Date: 26/06/18
 * Time: 19:57
 */

namespace App\Http\Controllers\Auth;

use App\Rol;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{

    public $successStatus = 200;


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

            $user = Auth::user();

            if($user->estado == 'ACTIVO'){
                $rol = Rol::find($user->rol_id);
                $success['token'] = $user->createToken('ConsorcioLoco', [$rol->scope])->accessToken;

                return response()->json([
                    'success' => $success,
                    'user' => json_encode($user)
                ],
                    $this->successStatus
                );
            } else {
                return response("El usuario ingresado no se encuentra activo. Pongase en contacto con el administrador del consorcio.", 401);
            }

        } else {

            return response()->json(['error' => 'unauthorized'], 401);

        }

    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'name' => 'required',

            'email' => 'required|email',

            'password' => 'required',

            'c_password' => 'required|same:password',

        ]);


        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 401);

        }


        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] = $user->createToken('ConsorcioLoco', ['user'])->accessToken;

        $success['name'] = $user->name;


        return response()->json(['success' => $success], $this->successStatus);

    }


    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */

    public function getDetails()

    {

        $user = Auth::user();

        return response()->json(['success' => $user], $this->successStatus);

    }

    public function addRoles() {

    }

}
