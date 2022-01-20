<?php

require_once 'app/error.php';

class ComprobantesAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/comprobantes/listar' && $params) {
                    Route::get('/comprobantes/listar/:id', 'comprobantesController@buscar', $params);
                }  else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/comprobantes/guardar') {
                    Route::post('/comprobantes/guardar', 'comprobantesController@guardar');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

           
        }
    }
}
