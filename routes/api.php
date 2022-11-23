<?php

use App\Http\Controllers\ArmasController;
use App\Http\Controllers\ClasesController;
use App\Http\Controllers\Jefe;
use App\Http\Controllers\Juego;
use App\Http\Controllers\Mapa;
use App\Http\Controllers\PersonajesController;
use App\Http\Controllers\Tipo;
use App\Http\Controllers\Usuario;
use App\Http\Middleware\roles;
use App\Models\jefes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/Tipos')->middleware('rol:1,3')->group(function()
{
    Route::post('/insertar',[Tipo::class,'insertarTipo'])->middleware('auth:sanctum');
    Route::put('/modificar/{id}',[Tipo::class,'modificarTipo'])->middleware('auth:sanctum');
    Route::get('/consultar',[Tipo::class,'consultarTipos'])->middleware('auth:sanctum');
    Route::get('/consultar/{id}',[Tipo::class,'consultarTipo'])->middleware('auth:sanctum');
});
Route::prefix('/Jefes')->middleware('rol:1,3,2')->group(function()
{
    Route::put('/modificar/{id}',[Jefe::class,'modificarJefe'])->middleware('auth:sanctum');
    Route::post('/insertar',[Jefe::class,'insertarJefe'])->middleware('auth:sanctum');
    Route::get('/consultar', [jefe::class, 'consultarJefes'])->middleware('auth:sanctum');
    Route::get('/consultar/{id}', [jefe::class, 'consultarJefe'])->middleware('auth:sanctum');
});
Route::prefix('/Mapas')->middleware('rol:1,3')->group(function()
{
    Route::put('/modificar/{id}',[Mapa::class,'modificarMapa'])->middleware('auth:sanctum');
    Route::post('/insertar',[Mapa::class,'insertarMapa'])->middleware('auth:sanctum');
    Route::get('/consultar',[Mapa::class,'consultarMapas'])->middleware('auth:sanctum');
});
Route::prefix('/Juegos')->middleware('rol:1,3,2')->group(function()
{
    Route::put('/modificar/{id}',[Juego::class,'modificarJuego'])->middleware('auth:sanctum');
    Route::post('/insertar',[Juego::class,'insertarJuego'])->middleware('auth:sanctum');
    Route::get('/consultar',[Juego::class,'consultarJuegos'])->middleware('auth:sanctum');
    Route::get('/consultar/{id}',[Juego::class,'consultarJuego'])->middleware('auth:sanctum');
});
Route::prefix('/Usuarios')->group(function()
{
    Route::post('/insertar',[Usuario::class,'registrarUsuario']);
    Route::post('/login',[Usuario::class,'login']);
    Route::post('/logout',[Usuario::class,'logout'])->middleware('auth:sanctum');
    Route::get('/verify',[Usuario::class,'verify'])->name('verify')->middleware('signed');
    Route::post('/codigo',[Usuario::class,'codigo'])->name('codigo')->middleware('signed');
    Route::post('/hola',[Usuario::class,'hola']);
});

//Rutas Compañero

Route::prefix('v1')->group(function () {
    Route::prefix('clases')->group(function(){
        Route::get('/', [ClasesController::class, 'index']);
        Route::get('/{nombre}', [ClasesController::class, 'encontrarClase']);
        Route::post('/agregar', [ClasesController::class, 'agregarClase']);
        Route::delete('/borrar/{id}', [ClasesController::class, 'borrarPorId']);
        Route::put('/actualizar/{id}', [ClasesController::class, 'actualizarClase']);
    });

    Route::prefix('personajes')->group(function(){
        Route::get('/', [PersonajesController::class, 'index']);
        Route::post('/agregar', [PersonajesController::class, 'agregarPersonaje']);
        Route::delete('/borrar/{id}', [PersonajesController::class, 'borrarPorId']);
        Route::put('/actualizar/{id}', [PersonajesController::class, 'actualizarPersonaje']);
        Route::get('/armas', [PersonajesController::class, 'personajeConArmas']);
        Route::get('/carasteristica', [PersonajesController::class, 'personajeConCaracteristicas']);
        Route::prefix('caracteristicas')->group(function(){
            Route::post('/agregar', [CaracteristicasController::class, 'agregarCaracteristica']);
            Route::put('/actualizar', [CaracteristicasController::class, 'actualizarCaracteristica']);
        });    
    });
    Route::prefix('armas')->group(function(){
        Route::get('/', [ArmasController::class, 'index']);
        Route::post('/agregar', [ArmasController::class, 'agregarArma']);
        Route::delete('/borrar/{id}', [ArmasController::class, 'borrarPorId']);
        Route::put('/actualizar', [ArmasController::class, 'actualizarArma']);
        Route::get('/personajes', [ArmasController::class, 'armaConPersonajes']);
    });
    Route::prefix('equipos')->group(function(){
        Route::get('/', [EquiposController::class, 'index']);
        Route::post('/agregar', [EquiposController::class, 'agregarEquipo']);
        Route::delete('/eliminar', [EquiposController::class, 'eliminarEquipo']);
    }); 
});
    

