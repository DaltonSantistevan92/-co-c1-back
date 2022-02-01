<?php

require_once 'app/error.php';

class Rol_PagoAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
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
