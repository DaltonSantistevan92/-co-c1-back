<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/marcaModel.php';

class MarcaController
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function buscar($params){
        $this->cors->corsJson();
        $response = [];
        $idMar = intval($params['id']);
        $dataMar = Marca::find($idMar);

        if($dataMar){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'marca' => $dataMar
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'marca' => null
            ];
        }
        echo json_encode($response);
    }

    public function listar() 
    {
        $this->cors->corsJson();
        $response = [];

        $datamarcas = Marca::where('estado', 'A')->orderBy('nombre')->get();

        if($datamarcas){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'marca' => $datamarcas
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'marca' => $datamarcas
            ];

        }
        echo json_encode($response);
    }

    public function guardar(Request $request){
        $this->cors->corsJson();
        $marcaRequest = $request->input('marca');

        $nombre = ucfirst($marcaRequest->nombre);
        $response = [];
        
        if($marcaRequest){
            $nuevo = new Marca();
            $existe = Marca::where('nombre',$nombre)->get()->first();

            if($existe){
                $response = [
                    'status' => false,
                    'mensaje' => 'La marca ya existe',
                    'marca' => null,
                ];
            }else{
                $nuevo->nombre = $nombre;
                $nuevo->estado = 'A';

                if($nuevo->save()){
                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando los datos',
                        'marca' => $nuevo,
                    ];

                }else{
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'marca' => null,
                    ];
                }
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'marca' => null,
            ]; 
        }
        echo json_encode($response);

    }

    public function datatable()
    {
        $this->cors->corsJson();

        $marcas = Marca::where('estado', 'A')->orderBy('nombre')->get();
        $data = [];   $i = 1;
        foreach ($marcas  as $m) {

            $botones = '<div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="editar_marca(' . $m->id . ')">
                    <i class="fa fa-pencil-square fa-lg"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminar_marca(' . $m->id . ')">
                <i class="fa fa-trash fa-lg"></i>
                </button>
            </div>';

            $data[] = [
                0 => $i,
                1 => $m->nombre,
                2 => $botones,
            ];
            $i++;
        }
        $result = [
            'sEcho' => 1,
            'iTotalRecords' => count($data),
            'iTotalDisplayRecords' => count($data),
            'aaData' => $data,
        ];
        echo json_encode($result);

    }

    public function editar(Request $request){
        $this->cors->corsJson();   
        $marRequest = $request->input('marca');
        $id = intval($marRequest->id);
        $nombre = ucfirst($marRequest->nombre);
        $response = [];       
        $mar = Marca::find($id);
        if($marRequest){
            if($mar){
                $mar->nombre = $nombre;
                $mar->save();
 
                $response = [
                    'status' => true,
                    'mensaje' => 'La Marca se ha actualizado',
                    'data' => $mar,
                ];
            }else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se puede actualizar la Marca',
                ];
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos...!!'
            ];
        }
        echo json_encode($response);
        

    }

    public function eliminar(Request $request){
        $this->cors->corsJson();   
        $marRequest = $request->input('marca');
        $id = intval($marRequest->id);

        $mar = Marca::find($id);

        $response = [];

        if($mar){
            $mar->estado = 'I';
            $mar->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado la Marca', 
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No se ha podido eliminar la Marca', 
            ];
        }
        echo json_encode($response);
    }

    public function buscarMarcas($params){
        $this->cors->corsJson();
        $texto = ucfirst($params['texto']);
        $response = [];

        $sql = "SELECT m.id,m.nombre FROM marcas m WHERE m.estado = 'A' and (m.nombre LIKE '%$texto%')";
        $array = $this->conexion->database::select($sql);

        if ($array) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'marca' => $array,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen coincidencias',
                'marca' => null,
            ];
        }
        echo json_encode($response);
    }

}