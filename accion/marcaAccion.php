<?php

require_once 'app/error.php';

class MarcaAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/marca/listar' && $params) {
                    Route::get('/marca/listar/:id', 'marcaController@buscar',$params);
                } else
                if ($ruta == '/marca/listar') {
                    Route::get('/marca/listar', 'marcaController@listar');
                }else
                if ($ruta == '/marca/datatable') {
                    Route::get('/marca/datatable', 'marcaController@datatable');
                }else
                if ($ruta == '/marca/buscarmarca' && $params) {
                    Route::get('/marca/buscarmarca/:texto', 'marcaController@buscarMarcas',$params);
                }
                /* else
                if( $ruta == '/marca/todos'){
                    Route::get('/marca/todos', 'marcaController@todos');
                } */
                else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }

            break;

            case 'post':
                if ($ruta == '/marca/guardar') {
                    Route::post('/marca/guardar', 'marcaController@guardar');
                }else 
                if ($ruta == '/marca/editar') {
                    Route::post('/marca/editar', 'marcaController@editar');
                }else 
                if ($ruta == '/marca/eliminar') {
                    Route::post('/marca/eliminar', 'marcaController@eliminar');
                } 
                else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }

            break;
   
        }
    }
}
