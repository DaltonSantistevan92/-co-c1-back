<?php

require_once 'app/error.php';

class MecanicoAccion 
{

    public function index($metodo_http, $ruta, $params = null)
    {
        switch ($metodo_http) {

            case 'get':
                if ($ruta == '/mecanico/listar' && $params) {
                    Route::get('/mecanico/listar/:id', 'mecanicoController@buscar', $params);
                }else 
                if ($ruta == '/mecanico/listar') {
                    Route::get('/mecanico/listar', 'mecanicoController@listar'); 
                }else
                if($ruta == '/mecanico/buscarMecanico' && $params){
                    Route::get('/mecanico/buscarMecanico/:texto', 'mecanicoController@buscarMecanico', $params);
                }else
                if($ruta == '/mecanico/reporteorden' && $params){
                    Route::get('/mecanico/reporteorden/:inicio/:fin/:mecanico_id', 'mecanicoController@reporteOrden', $params); 
                }
                break;


         
        }
    }
}
