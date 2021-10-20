<?php 

require_once 'models/permisoModel.php';
require_once 'models/menuModel.php';
require_once 'app/cors.php';

class PermisoController
{

    private $cors;

    public function __construct()
    {
        $this->cors = new Cors();
    }

    public function index()
    {
        echo "Metodo index de permisoController<br>";
    }

    public function newPermiso($params) 
    {
        $this->cors->corsJson();
        $id_rol = intval($params['id']);

        $accesos = Permiso::where('rol_id', $id_rol)->get();
        $response = [];

        if (count($accesos) > 0) {
            $menus_padres = [];
            $menus_hijos = [];
            $menusPadresOrdenadosAccesos = [];
            $menuFinal = [];

            $bdMenusPadres = Menu::where('id_seccion', 0)
                ->where('estado', 'A')
                ->orderBy('posicion')->get();

            //Separar menus padres de hijos que tienen acceso
            foreach ($accesos as $item) {
                $aux = [
                    'id' => $item->menu->id,
                    'nombre' => $item->menu->menu,
                    'icono' => $item->menu->icono,
                    'url' => $item->menu->url,
                    'id_seccion' => $item->menu->id_seccion,
                    // 'menus_hijos' => $menus_hijos
                ];

                if ($item->menu->id_seccion == 0) {
                    $menus_padres[] = $aux;
                } else {
                    $menus_hijos[] = $aux;
                }
            }

            //Ordenar los menus padres solo con acceso
            foreach ($bdMenusPadres as $ordenados) {
                foreach ($menus_padres as $desorden) {
                    if ($ordenados->id === $desorden['id']) {
                        $menusPadresOrdenadosAccesos[] = (object) $desorden;
                    }

                }
            }

            foreach ($menusPadresOrdenadosAccesos as $padre) {
                $menus_hijos_ordenados = Menu::where('estado', 'A')
                    ->where('id_seccion', $padre->id)
                    ->orderBy('posicion')->get();

                $hijos_ordenados = [];

                $auxFinal['id'] = $padre->id;
                $auxFinal['nombre'] = $padre->nombre;
                $auxFinal['icono'] = $padre->icono;
                $auxFinal['url'] = $padre->url;

                if (count($menus_hijos_ordenados) > 0) {
                    foreach ($menus_hijos_ordenados as $ordenado) {
                        foreach ($menus_hijos as $desorden) {
                            if ($desorden['id'] === $ordenado->id) {
                                $hijos_ordenados[] = (object) $desorden;
                            }
                        }
                    }

                    $auxFinal['menus_hijos'] = $hijos_ordenados;
                } else {
                    $auxFinal['menus_hijos'] = [];
                }

                $menuFinal[] = $auxFinal;
            }

            // die();
            $response = [
                'status' => true,
                'mensaje' => 'Hay información',
                'menus' => $menuFinal,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay menus para el rol',
                'menus' => [],
            ];
        }

        echo json_encode($response['menus']);
    }

    public function menu()
    {

        $this->cors->corsJson();
        $response = [];

        $menus = $this->menusPadres();

        if ($menus) {
            $response = [
                'status' => true,
                'menu_padre' => $menus,
            ];
        } else {
            $response = [
                'status' => true,
                'menu_padre' => [],
            ];
        }

        echo json_encode($response);
    }

    private function menusPadres()
    {
        $menus = Menu::where('estado', 'A')->where('id_seccion', '0')->get();

        if ($menus) {
            return $menus;
        } else {
            return false;
        }

    }

}
