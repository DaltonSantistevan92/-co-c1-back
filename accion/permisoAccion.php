<?php

require_once 'app/error.php';

class PermisoAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/permiso') {
                    echo "Ruta permiso";
                } else
                if($ruta == '/permiso/menu'){
                    Route::get('/permiso/menu', 'permisoController@menu');
                }
                if ($ruta == '/permiso/rol' && $params) {
                    Route::get('/permiso/rol/:id', 'permisoController@newPermiso', $params);

                }
                break;
        }
    }
}
 