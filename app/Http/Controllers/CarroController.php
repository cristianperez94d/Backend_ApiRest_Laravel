<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\JwtAuth;
use App\Carro;

class CarroController extends Controller
{
    public function __construct(){

    }
    // consultar carros
    public function index(Request $request){
            
        $carros = Carro::all()->load('usuario');

        return response()->json(array(
            'carros'=>$carros,
            'estado'=>'success'
        ),200);
    }
    // fin consultar carros

    // mostrar carro por id
    public function show($id){
        if (Carro::find($id)) {
            $carro = Carro::find($id)->load('usuario');
            $datos = array(
                'carro'=>$carro,
                'estado'=>'success'
            );
        }
        else{
            $datos = array(
                'mensaje'=>'El carro buscado no existe',
                'estado'=>'error'
            );
        }
        return response()->json($datos, 200);
    }
    // fin mostrar carro por id

    // insertar carro
    public function store(Request $request){
        $token = $request->header('Authorization',null);
        $jwtAuth = new JwtAuth();
        $tokenValido = $jwtAuth->verificarToken($token);
        if($tokenValido){
            //recoger datos por post
            $json = $request->input('json',null);
            $params = json_decode($json);
            $paramsArray = json_decode($json,true);

            // traer usuario autenticado
            $usuario = $jwtAuth->verificarToken($token, true);

            // validar datos     
            if(is_array($paramsArray)){
                $request = $request->merge($paramsArray);            
            }
            $validar = Validator::make($request->all(), [
                'titulo'=>['required'],
                'descripcion'=>['required'],
                'precio'=>['required'],
                'estado'=>['required']
            ]);

            if($validar->fails()){
                // enviar el error como json
                return response()->json($validar->errors(), 400);
            }

            // Guardar carro
            $carro = new Carro();
            
            $carro->usuario_id = $usuario->sub;
            $carro->titulo_car = $params->titulo;
            $carro->descripcion_car = $params->descripcion;
            $carro->precio_car = $params->precio;
            $carro->estado_car = $params->estado;
            
            $carro->save();

            $datos = array(
                'carro'=>$carro,
                'mensaje'=>'registro Almacenado correctamente',
                'estado'=>'success',
                'codigo'=>200
            );
            

        }
        else{
            // Devolver error
            $datos = array(
                'mensaje'=>'Login incorrecto',
                'estado'=>'error',
                'codigo'=>300
            );
        }

        return response()->json($datos, 200);

    }
    // fin insertar carro

    // actualizar carro
    public function update($id, Request $request){

        $token = $request->header('Authorization',null);
        $jwtAuth = new JwtAuth();
        $tokenValido = $jwtAuth->verificarToken($token);
        if($tokenValido){
            // recoger parametros por post
            $json = $request->input('json', null);
            $params = json_decode($json);
            $paramsArray= json_decode($json, true);

            // validar los datos
            if(is_array($paramsArray)){
                $request = $request->merge($paramsArray);            
            }
            $validar = Validator::make($request->all(), [
                'titulo'=>['required'],
                'descripcion'=>['required'],
                'precio'=>['required'],
                'estado'=>['required']
            ]);

            if($validar->fails()){
                // enviar el error como json
                return response()->json($validar->errors(), 400);
            }

            // actualizar el carro
            $datosActualizar = array(
                'titulo_car'=>$params->titulo,
                'descripcion_car'=>$params->descripcion,
                'precio_car'=>$params->precio,
                'estado_car'=>$params->estado
            );
            $carro = Carro::where('id_car', $id)->update($datosActualizar);
            
            $datos = array(
                'carro'=>$params,
                'mensaje'=>'registro actualizado correctamente',
                'estado'=>'success',
                'codigo'=>200
            );

        }
        else{
            $datos = array(
                'mesnaje'=>'usuario NO autenticado',
                'estado'=>'error',
                'codigo'=>300);    
            
        }

        return response()->json($datos ,200);

    }
    // fin actualizar carro

    // eliminar carro
    public function destroy($id, Request $request){
        
        $token = $request->header('Authorization',null);
        $jwtAuth = new JwtAuth();
        $tokenValido = $jwtAuth->verificarToken($token);

        if($tokenValido){
            // comprobar el registro
            $carro = Carro::find($id);

            // borrar registro
            $carro->delete();

            // devolverlo
            $datos = array(
                'carro'=>$carro,
                'estado'=>'success',
                'mensaje'=>'Registro eliminado correctamente',
                'codigo'=>200
            );
            
        }
        else{
            
            $datos = array(
                'mesnaje'=>'usuario NO autenticado',
                'estado'=>'error',
                'codigo'=>300
            );    
            
        }

        return response()->json($datos, 200);

    }
    // eliminar carro

}
