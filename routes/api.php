<?php
use Illuminate\Http\Request;
use App\Unidad;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/unidad', 'UnidadController@index');
Route::get('/test', 'TestController@index');

Route::get('/admin/estadistica', 'EstadisticaController@index');

# Route::post('/register', 'Auth\RegisterController@register');

Route::group(['middleware' => ['api', 'cors']], function () {
    Route::get('/expensa',  'ExpensaController@index');
    Route::post('/expensa', 'ExpensaController@store');
    Route::get('/reclamo',  'ReclamoController@index');
    Route::post('/reclamo', 'ReclamoController@store');

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
    //Consorcio
    //Admin
    Route::get('/consorcio', 'ConsorcioController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/consorcio', 'ConsorcioController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/consorcio', 'ConsorcioController@delete')->middleware('auth:api', 'scope:operator,admin');

    //Unidad
    //Admin
    Route::get('/admin/unidad', 'UnidadController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/unidad', 'UnidadController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/unidad', 'UnidadController@delete')->middleware('auth:api', 'scope:operator,admin');
    //User
    Route::get('/user/unidad', 'UnidadController@user')->middleware('auth:api', 'scope:user:operator,admin');

    //Expensas
    //Admin
    Route::get('/admin/expensa', 'ExpensaController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/admin/expensa', 'ExpensaController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/expensa', 'ExpensaController@update')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/expensa', 'ExpensaController@delete')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/generar-expensas', 'ExpensaController@generarExpensas')->middleware('auth:api', 'scope:operator,admin');
    //User
    Route::get('/user/expensa', 'ExpensaController@user')->middleware('auth:api', 'scope:user,operator,admin');

    //Factura
    //Admin
    Route::get('/admin/factura', 'FacturaController@index')->middleware('auth:api', 'scope:operator,admin');
    //User
    Route::get('/user/factura', 'FacturaController@user')->middleware('auth:api', 'scope:user,operator,admin');

    //Pago
    //Admin
    Route::get('/admin/pago', 'PagoController@index')->middleware('auth:api', 'scope:operator,admin');
    //User
    Route::get('/user/pago', 'PagoController@user')->middleware('auth:api', 'scope:user,operator,admin');

    //Reclamo
    //Admin
    Route::get('/admin/reclamo', 'ReclamoController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::put('/admin/reclamo', 'ReclamoController@update')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/admin/reclamo', 'ReclamoController@delete')->middleware('auth:api', 'scope:operator,admin');
    //User
    Route::get('/user/reclamo', 'ReclamoController@user')->middleware('auth:api', 'scope:user,operator,admin');
    Route::post('/user/reclamo', 'ReclamoController@store')->middleware('auth:api', 'scope:user,operator,admin');



    //Gasto
    Route::get('/gasto/mensual', 'GastoController@gastosMensual');
    Route::get('/gasto', 'GastoController@index')->middleware('auth:api', 'scope:operator,admin');
    Route::post('/gasto', 'GastoController@store')->middleware('auth:api', 'scope:operator,admin');
    Route::delete('/gasto', 'GastoController@delete')->middleware('auth:api', 'scope:operator,admin');

    Route::post('auth/admin/token/create', 'Auth\AdminController@addRoles')->middleware(['auth:api', 'scope:admin']);
    Route::post('auth/register', 'Auth\ApiRegisterController@register');
    Route::post('oauth/login', 'Auth\PassportController@login');
    Route::post('oauth/register', 'Auth\PassportController@register');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('get-details', 'Auth\PassportController@getDetails');
    });
});