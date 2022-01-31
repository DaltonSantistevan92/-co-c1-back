<?php

require_once 'app/error.php';

class ServicioAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {

            case 'get':
                if ($ruta == '/servicio/listar' && $params) {
                    Route::get('/servicio/listar/:id', 'servicioController@buscar', $params);
                }else 
                if ($ruta == '/servicio/listar') {
                    Route::get('/servicio/listar', 'servicioController@listar');
                }else
                if ($ruta == '/servicio/datatable') {
                    Route::get('/servicio/datatable', 'servicioController@datatable');
                }else
                if ($ruta == '/servicio/buscarServicio' & $params) {
                    Route::get('/servicio/buscarServicio/:texto', 'servicioController@buscarServicio', $params);
                }else
                if ($ruta == '/servicio/contar') {
                    Route::get('/servicio/contar', 'servicioController@contar');
                }else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                    }      
                break;

            case 'post':
                if ($ruta == '/servicio/guardar') {
                    Route::post('/servicio/guardar', 'servicioController@guardar');
                }else 
                if ($ruta == '/servicio/editar') {
                    Route::post('/servicio/editar', 'servicioController@editar');
                }else 
                if ($ruta == '/servicio/eliminar') {
                    Route::post('/servicio/eliminar', 'servicioController@eliminar');
                }
                break;
        }
    }
}
