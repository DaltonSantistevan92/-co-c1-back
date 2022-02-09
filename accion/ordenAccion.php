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
                } else
                if ($ruta == '/orden/visualizar' && $params) {
                    Route::get('/orden/visualizar/:opcion/:estado', 'ordenController@visualizar', $params); 
                } else
                if ($ruta == '/orden/actualizarOrden' && $params) {
                    Route::get('/orden/actualizarOrden/:id_orden/:estado_id/:estado_mecanico', 'ordenController@actualizarOrden', $params);
                } else
                if ($ruta == '/orden/estado' && $params) {
                    Route::get('/orden/estado/:id_persona/:estado_id', 'ordenController@estado', $params);
                }else
                if ($ruta == '/orden/estadoPagada' && $params) {
                    Route::get('/orden/estadoPagada/:id_persona/:estado_id', 'ordenController@estadoPagada', $params);
                }else
                if($ruta == '/orden/cantidades_estados'){
                    Route::get('/orden/cantidades_estados', 'ordenController@cantidades_estados');
                }else
                if($ruta == '/orden/cantidad_pagadas'){
                    Route::get('/orden/cantidad_pagadas', 'ordenController@contarOrdenesPagadas');
                }else
                if ($ruta == '/orden/estadoOrden' && $params) {//falta x terminar
                    Route::get('/orden/estadoOrden/:inicio/:fin/:estado_orden_id/:top', 'ordenController@ordenesRealizados', $params);
                }else
                if($ruta == '/orden/graficaOrden'){
                    Route::get('/orden/graficaOrden', 'ordenController@graficaOrden');
                }else
                if($ruta == '/orden/servicioFrecuente' && $params){
                    Route::get('/orden/servicioFrecuente/:inicio/:fin/:top', 'ordenController@servicioFrecuente', $params);
                }else
                if($ruta == '/orden/mantenimientoRegresionLineal' && $params){
                    Route::get('/orden/mantenimientoRegresionLineal/:inicio/:fin', 'ordenController@mantenimientoRegresionLineal', $params);
                }else
                if( $ruta == '/orden/proyeccion' && $params){
                    Route::get('/orden/proyeccion/:inicio/:fin/:temporalidad', 'ordenController@proyeccion', $params);
                } 
                else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/orden/guardar') {
                    Route::post('/orden/guardar', 'ordenController@guardar');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

        }
    }
}
