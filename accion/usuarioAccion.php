<?php

require_once 'app/error.php';

class UsuarioAccion
{

    public function __construct()
    {
        //echo "Soy la clase accionUsuario<br>";
    }

    //Configurar rutas y controllers
    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/usuario/listar' && $params) {
                    Route::get('/usuario/listar/:id', 'usuarioController@buscar',$params);
                } else
                if ($ruta == '/usuario/contar') {
                    Route::get('/usuario/contar', 'usuarioController@contar');
                } else
                if ($ruta == '/usuario/datatable') {
                    Route::get('/usuario/datatable', 'usuarioController@dataTable');
                }else
                if($ruta == '/usuario/list'){
                    Route::get('/usuario/list', 'usuarioController@listUsers');
                }
                else {
                    ErrorClass::e(404, "La ruta no existe");
                }
                break;

            case 'post':
                if ($ruta == '/usuario/login') {
                    Route::post('/usuario/login', 'usuarioController@login2');
                } else
                if ($ruta == '/usuario/save') {
                    Route::post('/usuario/save', 'usuarioController@guardar');
                }else
                if ($ruta == '/usuario/fichero') {
                    Route::post('/usuario/fichero', 'usuarioController@subirFichero', true);
                }else 
                if($ruta == '/usuario/editar'){
                    Route::post('/usuario/editar', 'usuarioController@editar');
                }else 
                if($ruta == '/usuario/eliminar'){
                    Route::post('/usuario/eliminar', 'usuarioController@eliminar');
                }else {
                    ErrorClass::e(404, "La ruta no existe");
                }
                break;

        }

    }

}