<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth
{
    public $clave_secreta;

    public function __construct(){
        $this->clave_secreta = 'ApiRest_laravel7';
    }

    public function iniciarSesion($email, $password, $getToken = null ){
        $usuario = User::where('email_usu','=',$email)->first();

        if(is_object($usuario)){
            // validar la contraseña
            if (password_verify($password,$usuario->password_usu) ) {
                
                // Generar token y retornarlo con los datos del usuario
                // iat: tiempo que se genero el token
                // exp: tiempo de expiracion del token
                $token = array(
                    'sub'=>$usuario->id_usu,
                    'email'=>$usuario->email_usu,
                    'nombre'=>$usuario->nombre_usu,
                    'iat'=>time(), 
                    'exp'=>time()+(7*24*60*60)
                );

                $jwt = JWT::encode($token, $this->clave_secreta, 'HS256');

                $jwtDecode = JWT::decode($jwt,$this->clave_secreta,array('HS256'));
                if(is_null($getToken)){
                    return $jwt;
                } else {
                    return $jwtDecode;
                }
            }
            else{
                // Devolver error
                return array('estado' => 'error', 'mensaje' => 'Contraseña  incorrecta');    
            }

        } else {
            // Devolver error
            return array('estado' => 'error', 'mensaje' => 'Login a fallado');
        }
        
    }

    public function verificarToken($jwtToken, $getIdentidad = false){
        $auth = false;
        try{
            $decode = JWT::decode($jwtToken,$this->clave_secreta,array('HS256'));
        }
        catch(\UnexpectedValueException $e){
            $auth = false;
        }
        catch(\DomainException $e){
            $auth = false;
        }

        if( isset($decode) && is_object($decode) && isset($decode->sub) ){
            $auth = true;
        }
        else{
            $auth = false;
        }

        if ($getIdentidad) {
            return $decode;
        }

        return $auth;
    }

}
