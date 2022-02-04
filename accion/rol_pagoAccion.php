<?php

require_once 'app/error.php';

class Rol_PagoAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if($ruta == '/rol_pago/cal' && $params){
                    Route::get('rol_pago/calc/:clave', 'rol_pagoController@create_detail_pay', $params);
                }else
                if($ruta == '/rol_pago/create' && $params){
                    Route::get('rol_pago/create/:inicio/:fin/:user_id', 'rol_pagoController@createRolPago', $params);
                }else    
                if ($ruta == '/rol_pago/listar') {
                    Route::get('/rol_pago/listar', 'rol_pagoController@listar');
                }
                else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
            break;

            case 'post':
                if ($ruta == '/rol_pago/guardar') {
                    Route::get('/rol_pago/guardar', 'rol_pagoController@guardar');
                }else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
            break;
        }
    }
}
