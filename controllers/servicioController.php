<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/servicioModel.php';

class ServicioController
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function guardar(Request $request){
        $this->cors->corsJson();
        $servicioRequest = $request->input('servicio');
        $detalle = ucfirst($servicioRequest->detalle);
        $precio = floatval($servicioRequest->precio);
        $response = [];
        
        if($servicioRequest){
            $nuevo = new Servicio();
            $existe = Servicio::where('detalle',$detalle)->get()->first();

            if($existe){
                $response = [
                    'status' => false,
                    'mensaje' => 'El servicio ya existe',
                    'servicio' => null,
                ];
            }else{
                $nuevo->detalle = $detalle;
                $nuevo->precio = $precio;
                $nuevo->estado = 'A';

                if($nuevo->save()){
                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando el servicio',
                        'servicio' => $nuevo,
                    ];

                }else{
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'servicio' => null,
                    ];
                }
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'servicio' => null,
            ]; 
        }
        echo json_encode($response);

    }

    public function datatable()
    {
        $this->cors->corsJson();

        $servicio = Servicio::where('estado','A')->orderBy('detalle')->get();
        $data = [];   $i = 1;
        foreach ($servicio  as $s) {

            $botones = '<div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="editar_servicio(' . $s->id . ')">
                    <i class="fa fa-pencil-square fa-lg"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminar_servicio(' . $s->id . ')">
                <i class="fa fa-trash fa-lg"></i>
                </button>
            </div>';

            $signo = '<spam><b style="color:red">$ </b>'.$s->precio.'</spam>'; 

            $data[] = [
                0 => $i,
                1 => $s->detalle,
                2 => $signo,
                3 => $botones,
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

    public function listar(){
        $this->cors->corsJson();
        $servicio = Servicio::where('estado','A')->get();
        $response = [];

        if($servicio){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'servicio' => $servicio
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'servicio' => null
            ];
        }
        echo json_encode($response);
    }

    public function buscar($params){
        $this->cors->corsJson();
        $response = [];
        $idservicio = intval($params['id']);
        $dataservicio = Servicio::find($idservicio);

        if($dataservicio){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'servicio' => $dataservicio
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'servicio' => null
            ];
        }
        echo json_encode($response);
    }

    public function editar(Request $request){
        $this->cors->corsJson();   
        $serRequest = $request->input('servicio');
        $id = intval($serRequest->id);
        $detalle = ucfirst($serRequest->detalle);
        $precio = floatval($serRequest->precio);

        $response = [];       
        $ser = Servicio::find($id);
        if($serRequest){
            if($ser){
                $ser->detalle = $detalle;
                $ser->precio = $precio;
                $ser->save();
 
                $response = [
                    'status' => true,
                    'mensaje' => 'El servicio se ha actualizado',
                    'servicio' => $ser,
                ];
            }else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se puede actualizar el servicio',
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
        $serRequest = $request->input('servicio');
        $id = intval($serRequest->id);
        $servicio = Servicio::find($id);
        $response = [];

        if($servicio){
            $servicio->estado = 'I';
            $servicio->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado el Servicio', 
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No se ha podido eliminar el Servicio', 
            ];
        }
        echo json_encode($response);
    }

    public function buscarServicio($params){
        $this->cors->corsJson();
        $texto = strtolower($params['texto']);
        $servicio = Servicio::where('detalle', 'like', '%'. $texto . '%')->where('estado', 'A')->get();
        $response = [];

        if ($texto == "") {
            $response = [
                'status' => true,
                'mensaje' => 'Todos los registros',
                'servicios' => $servicio,
            ];
        } else {
            if (count($servicio) > 0) {
                $response = [
                    'status' => true,
                    'mensaje' => 'Coincidencias encontradas',
                    'servicios' => $servicio,
                ];
            } else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No hay registros',
                    'servicios' => null,
                ];
            }
        }
        echo json_encode($response);
    }

    public function getServicioByOrden($id){

        $sql = "SELECT id, 
        (SELECT servicios.detalle FROM servicios WHERE servicios.id = orden_servicio.servicio_id) as servicio, 
        (SELECT servicios.precio FROM servicios WHERE servicios.id = orden_servicio.servicio_id) as precio 
        FROM `orden_servicio` WHERE estado = 'A' AND orden_id = $id";
        $array = $this->conexion->database::select($sql);
        $response = [];

        if(count($array) > 0){
            $response = $array;
        }

        return $response;
    }

    public function contar(){
        $this->cors->corsJson();
        $servicios = Servicio::where('estado','A')->get();
        $response = [];
        if($servicios){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Servicios',
                'Modelo' => 'Servicios',
                'cantidad' => $servicios->count()
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Servicios',
                'Modelo' => 'Servicios',
                'cantidad' => 0
            ];
        }
        echo json_encode($response);
    }

}