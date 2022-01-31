<?php

require_once 'app/error.php';

class SalidaAccion{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                /* if ($ruta == '/salida/lastEntry' && $params) {
                    Route::get('/salida/lastEntry/:usuario_id', 'salidaController@lastEntry', $params);
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                } */
                break;

            case 'post':
                if ($ruta == '/salida/guardar') {
                    Route::post('/salida/guardar', 'salidaController@guardarSalida');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;    
        }
    }
}