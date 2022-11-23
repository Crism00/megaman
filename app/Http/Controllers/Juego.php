<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\juegos;
use Illuminate\Support\Facades\Http;

class Juego extends Controller
{
    public function insertarJuego(Request $request, Response $response){

        $validator = Validator::make($request->all(),[
            'nombre'=>'required | string | unique:juegos',
        ]);
        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],400);
        }
        else{
            $juego = new juegos;
            $juego->nombre = $request->nombre;
            $juego->save();
            return response()->json([
                'estado'=>$request->nombre." exitosamente agregado"
            ],201);
        }
    }

    public function modificarJuego(Request $request, Response $response, int $id){

        if($request->all()==null){
            return response()->json([
                'No hay nada a modificar',
                'valores modificables'=>'nombre'
            ],400);
        }else{


            $validator = Validator::make($request->all(),[
                'nombre'=>'string',
            ]);
            if($validator->fails()){
                return response()->json([
                    $validator->errors()
                ],400);
            }

            $juego = juegos::find($id);
            if($request->nombre != null)$juego->nombre=$request->nombre;
            $juego->save();
            return response()->json([
                'estado'=>'Valor o valores modificados exitosamente'
            ],201);
        }
    }

    public function consultarJuegos(){
        $juegos = juegos::select()->get();
        return $juegos;
    }
    public function consultarJuego(int $id){
        $juego = juegos::select()->where('id','=',$id)->get();
        return $juego;
    }
}
