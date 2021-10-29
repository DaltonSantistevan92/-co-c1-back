<?php

require_once 'app/error.php';

class VehiculoAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/vehiculo/listar' && $params) {
                    Route::get('/vehiculo/listar/:id', 'vehiculoController@buscar', $params);
                } else
                if ($ruta == '/vehiculo/listar') {
                    Route::get('/vehiculo/listar', 'vehiculoController@listar');
                }else
                if ($ruta == '/vehiculo/datatable') {
                    Route::get('/vehiculo/datatable', 'vehiculoController@dataTable');
                } else
                if ($ruta == '/vehiculo/disponible' && $params) {
                    Route::get('/vehiculo/disponible/:disponible', 'vehiculoController@disponible', $params);
                } else
                if ($ruta == '/vehiculo/buscar' && $params) {
                    Route::get('/vehiculo/buscar/:texto', 'vehiculoController@buscarVehiculo', $params);
                }else
                if ($ruta == '/vehiculo/cliente') {
                    Route::get('/vehiculo/cliente', 'vehiculoController@clienteVehiculo');
                }else
                if ($ruta == '/vehiculo/buscarClienteVehiculo' && $params) {
                    Route::get('/vehiculo/buscarClienteVehiculo/:id_cliente', 'vehiculoController@buscarClienteVehiculo', $params);
                }else
                if ($ruta == '/vehiculo/buscarxplaca' && $params) {
                    Route::get('/vehiculo/buscarxplaca/:id', 'vehiculoController@buscarxplaca', $params);
                }else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/vehiculo/guardar') {
                    Route::post('/vehiculo/guardar', 'vehiculoController@guardar');
                }else
                if ($ruta == '/vehiculo/editar') {
                    Route::post('/vehiculo/editar', 'vehiculoController@editar');
                }else 
                if ($ruta == '/vehiculo/eliminar') {
                    Route::post('/vehiculo/eliminar', 'vehiculoController@eliminar');
                } else
                if ($ruta == '/vehiculo/guardarClienteVehiculo') {
                    Route::post('/vehiculo/guardarClienteVehiculo', 'vehiculoController@guardarClienteVehiculo');
                } else
                if ($ruta == '/vehiculo/eliminarClienteVehiculo') {
                    Route::post('/vehiculo/eliminarClienteVehiculo', 'vehiculoController@eliminarClienteVehiculo');
                }
                 else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

        }
    }
}
