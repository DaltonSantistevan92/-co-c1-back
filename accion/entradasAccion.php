<?php

require_once 'app/error.php';

class EntradasAccion{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/entradas/lastEntry' && $params) {
                    Route::get('/entradas/lastEntry/:usuario_id', 'entradasController@lastEntry', $params);
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/entradas/guardar') {
                    Route::post('/entradas/guardar', 'entradasController@guardar');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;    
        }
    }
}