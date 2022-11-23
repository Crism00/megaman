<?php

namespace App\Http\Controllers;

use App\Jobs\ProccesMail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class Usuario extends Controller
{
    public function registrarUsuario(Request $request, Response $response){

        $validator = Validator::make($request->all(),[
            'email'=>'required | string | unique:users',
            'name'=>'required | string | max:25',
            'password'=>'required | string',
            'rol'=>'required | int',
            'telefono'=>'required | string | max:13 | unique:users',
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ],400);
        }
        
        $usuario = new User;
        $usuario->name = $request->name;
        $usuario->password = Hash::make($request->password);
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;
        $usuario->telefono = $request->telefono;
        $usuario->apellidos = $request->apellidos;
        $usuario->activo='no';
        $usuario->save();
        $url = URL::temporarySignedRoute(
            'verify',
            now()->addMinutes(20),
            ['id'=>$usuario->id]
        );

        ProccesMail::dispatch($usuario,$url)
        ->onQueue("mail")
        ->delay(now()->addMinutes(2));
        //Mail::to($request->email)->send(new SendMail($usuario,$url));
        return response()->json([
            'url'=>$url,
            'telefono' => $request->telefono,
            'estado'=>'Usuario agregado correctamente, revisa tu Email para continuar con la activacion de la cuenta!!!'
        ],201);

    }

    public function login(Request $request, Response $response){
        
        $user = User::whereEmail($request->email)->first();
        if(!is_null($user) && Hash::check($request->password, $user->password)){
            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                "estado"=>"Bienvenido al sistema",
                "acces_token"=>$token,
                "token_type"=>"Bearer"
            ],201);
        }
        else{
            return response()->json([
                "estado"=>"Contraseña incorrecta o el usuario no existe, intente de nuevo"
            ],401);
        }
    }

    public function logout(Request $request, Response $response){
        $request->user()->tokens()->delete();
        $request->user()->save();
        return [
            'message'=>'Sesion cerrada'
        ];
    }

    public function verify(Request $request){
        if(!$request->hasValidSignature()){abort(400);}

        $nRandom = rand(1000,9999);
        $user = User::find($request->id);
        $user->codigo = $nRandom;
        $user->save();

        $response = Http::post('https://rest.nexmo.com/sms/json', [
            "from" => "Cristian",
            "api_key" => "11bb862d",
            "api_secret" => "uNm7rSQIILYO99Ks",
            "to" => 52 . $user->telefono,
            "text" => "Tu codigo de verificacion es: " . $nRandom
        ]);

        $url = URL::temporarySignedRoute(
            'codigo',
            now()->addMinutes(20),
            ['id'=>$user->id]
        );
        return view('email.codigo',['url'=>$url]);
    }

    public function codigo(Request $request, Response $response){
        $user = User::find($request->id);
        $random = $user->codigo;
        if($random == $request->codigo){
            $user->activo="si";
            $user->save();
            return response()->json([
                "User"=>$user->id,
                "Estado"=>"Cuenta activada correctamente"
            ]);
        }
        else{
            return response()->json([
                "Estado"=>"La cuenta no pudo ser activada"
            ]);
        }
    }

    public function hola(Request $request){
       ProccesMail::dispatch($request)
       ->onQueue("mail")
       ->delay(now()->addMinutes(1)); 
    }

}
