<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    //
    public function registrar(Request $request){
        // Recibir los datos por Post
        $json = $request->input("json",null);
        $params = json_decode($json);
        $email = ( !is_null($json) && isset($params->email) ) ? $params->email : null;
        $nombre = ( !is_null($json) && isset($params->nombre) ) ? $params->nombre : null;
        $pass = ( !is_null($json) && isset($params->pass) ) ? $params->pass : null;
        $rol = 'ROL_USUARIO';
        
        if ( !is_null($email) && !is_null($nombre) && !is_null($pass) ) {
            // crear usuario
            $usuario = new User();
            $usuario->email_usu = $email;
            $usuario->nombre_usu = $nombre;
            $usuario->rol_usu = $rol;
            
            $pass_cifrada = password_hash($pass, PASSWORD_DEFAULT);
            $usuario->password_usu = $pass_cifrada;
            // comprobar usuario duplicado
            $existe_usuario = User::where('email_usu','=',$email)->count();
            if ( $existe_usuario === 0 ) {
                // Guardar usuario
                $usuario->save();
                $datos = array(
                    'estado'=>'success',
                    'codigo'=>200,
                    'mensaje'=>'usuario registrado correctamente'
                );

            }
            else{
                // no guardar ya existe usuario
                $datos = array(
                    'estado'=>'error',
                    'codigo'=>400,
                    'mensaje'=>'El usuario ya existe'
                );
            }

        }
        else {
            $datos = array(
                'estado'=>'error',
                'codigo'=>400,
                'mensaje'=>'usuario no encontrado'
            );
        }

        return response()->json($datos, 200);
        
    }
    public function login(Request $request){
        $jwtAuth = new JwtAuth();

        // Recibir datos por post
        $json = $request->input('json',null);
        $params = json_decode($json);

        $email = ( !is_null($json) && isset($params->email) ) ? $params->email : null ;
        $pass = ( !is_null($json) && isset($params->pass) ) ? $params->pass : null ;
        $getToken = ( !is_null($json) && isset($params->getToken) ) ? $params->getToken  : null ;

        if( !is_null($email) && !is_null($pass) && ($getToken == null || $getToken == 'false') ){
            $iniciarSesion = $jwtAuth->iniciarSesion($email, $pass);
        }
        elseif($getToken != null){
            $iniciarSesion = $jwtAuth->iniciarSesion($email, $pass, $getToken);
        }
        else{
            $iniciarSesion =  array(
                'estado'=>'error',
                'mensaje'=>'Envia tus datos por post'
            );
        }
        return response()->json($iniciarSesion, 200);
    }
}
