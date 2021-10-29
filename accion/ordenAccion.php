<?php

require_once 'app/error.php';

class OrdenAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/orden/listar' && $params) {
                    Route::get('/orden/listar/:id', 'ordenController@buscar', $params);
                } else
                if ($ruta == '/orden/listar') {
                    Route::get('/orden/listar', 'ordenController@listar');
                }/*  else
                if ($ruta == '/orden/listarhoy') {
                    Route::get('/orden/listarhoy', 'ordenController@listarhoy');
                } else
                if ($ruta == '/orden/visualizar' && $params) {
                    Route::get('/orden/visualizar/:opcion/:estado', 'ordenController@visualizar', $params);
                } else
                if ($ruta == '/orden/dashboard_mecanico' && $params) {
                    Route::get('/orden/dashboard_mecanico/:id_persona', 'ordenController@dashboard_mecanico', $params);
                } else
                if ($ruta == '/orden/estado' && $params) {
                    Route::get('/orden/estado/:id_persona/:estado_id', 'ordenController@estado', $params);
                } else
                if ($ruta == '/orden/actualizar_orden' && $params) {
                    Route::get('/orden/actualizar_orden/:id_orden/:estado_id/:estado_mecanico', 'ordenController@actualizar_orden', $params);
                }else
                if($ruta == '/orden/cliente'){
                    Route::get('/orden/cliente', 'ordenController@orden_cliente');
                }else
                if($ruta == '/orden/count_estado'){
                    Route::get('/orden/count_estado', 'ordenController@count_estado');
                }else
                if($ruta == '/orden/grafica_estados'){
                    Route::get('/orden/count_estado', 'ordenController@grafica_estados');
                }else
                if($ruta == '/orden/recientes'){
                    Route::get('/orden/recientes', 'ordenController@ordenes_recientes');
                } */
                 else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/orden/guardar') {
                    Route::post('/orden/guardar', 'ordenController@guardar');
                }/*  else
                if ($ruta == '/orden/repuesto') {
                    Route::post('/orden/repuesto', 'ordenController@repuesto');
                } */ else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            /* case 'put':
                if ($ruta == '/orden/updateObservacion' && $params) {
                    Route::put('/orden/updateObservacion/:id', 'ordenController@updateObservacion', $params);
                } else
                if ($ruta == '/orden/example') {
                    Route::put('/orden/example', 'ordenController@example');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break; */


        }
    }
}
