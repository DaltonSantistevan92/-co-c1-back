<?php

require_once 'app/error.php';

class ProgresoAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {

            case 'get':
                if ($ruta == '/progreso/listar' && $params) {
                    Route::get('/progreso/listar/:id', 'progresoController@listar', $params);
                }/* else
                if($ruta == '/actividad/last_porcentaje'){
                    Route::get('/actividad/last_porcentaje/:id_orden', 'actividadController@ultimo_porcentaje', $params);
                }
                else
                if($ruta == '/actividad/reciente' && $params){
                    Route::get('/actividad/reciente/:cantidad', 'actividadController@reciente', $params);
                } 
                else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                break;
                */

            case 'post':
                if ($ruta == '/progreso/guardar') {
                    Route::post('/progreso/guardar', 'progresoController@guardar');
                }else
                if ($ruta == '/progreso/guardarmodal'){
                    Route::post('/progreso/guardarmodal', 'progresoController@guardarmodal');
                }
                break; 

            
        }
    }
}
