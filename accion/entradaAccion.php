<?php

require_once 'app/error.php';

class EntradaAccion{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/entrada/lastEntry' && $params) {
                    Route::get('/entrada/lastEntry/:usuario_id', 'entradaController@lastEntry', $params);
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/entrada/guardar') {
                    //var_dump($_POST); die();
                    Route::post('/entrada/guardar', 'entradaController@create');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;    
        }
    }
}