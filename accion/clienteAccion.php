<?php

require_once 'app/error.php';

class ClienteAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/cliente/listar' && $params) {
                    Route::get('/cliente/listar/:id', 'clienteController@buscar', $params);
                }else
                if ($ruta == '/cliente/listar') {
                    Route::get('/cliente/listar', 'clienteController@listar');
                } else
                if ($ruta == '/cliente/datatable') {
                    Route::get('/cliente/datatable', 'clienteController@datatable');
                } else
                if ($ruta == '/cliente/buscar' && $params) {
                    Route::get('/cliente/buscar/:texto', 'clienteController@buscarCliente', $params);
                } else
                if ($ruta == '/cliente/contar') {
                    Route::get('/cliente/contar', 'clienteController@contar');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/cliente/editar') {
                    Route::post('/cliente/editar', 'clienteController@editar');
                }else
                if ($ruta == '/cliente/eliminar') {
                    Route::post('/cliente/eliminar', 'clienteController@eliminar');
                } else
                if ($ruta == '/cliente/updateKilometraje') {
                    Route::post('/cliente/updateKilometraje', 'clienteController@updateKilometraje');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

           
        }
    }
}
