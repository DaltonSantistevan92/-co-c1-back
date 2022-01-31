<?php

require_once 'app/error.php';

class Estado_OrdenAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/estado_orden/listar') {
                    Route::get('/estado_orden/listar', 'estado_ordenController@listar');
                }else
                if ($ruta == '/estado_orden/realizadas') {//falta x terminar
                    Route::get('/estado_orden/realizadas', 'estado_ordenController@realizadas');
                }else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
            break;

            case 'post':
               

            break;
   
        }
    }
}
