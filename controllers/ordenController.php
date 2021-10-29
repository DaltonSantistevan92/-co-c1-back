<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'app/helper.php';
require_once 'core/conexion.php';
require_once 'core/params.php';
require_once 'models/ordenModel.php';
require_once 'models/clienteModel.php';
require_once 'models/mecanicoModel.php';
require_once 'controllers/ordenservicioController.php';

class OrdenController
{

    private $cors;
    private $conexion;
    private $limit_key = 6;


    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function guardar(Request $request){
        $this->cors->corsJson();
        $dataorden = $request->input('orden');
        $ordenServicio = $request->input('ordenservicios'); 
        $response = [];
        
        $helper = new Helper();
        $codigo = $helper->generate_key($this->limit_key);

        if($dataorden){
            $dataorden->usuario_id = intval($dataorden->usuario_id);
            $dataorden->cliente_id = intval($dataorden->cliente_id);
            $dataorden->vehiculo_id = intval($dataorden->vehiculo_id);
            $dataorden->mecanico_id = intval($dataorden->mecanico_id);
            $dataorden->total = floatval($dataorden->total);

            //Guarda la nueva orden
            $nuevaOrden = new Orden();
            $nuevaOrden->usuario_id = $dataorden->usuario_id;
            $nuevaOrden->cliente_id = $dataorden->cliente_id;
            $nuevaOrden->vehiculo_id = $dataorden->vehiculo_id;
            $nuevaOrden->mecanico_id = $dataorden->mecanico_id;
            $nuevaOrden->fecha = date('Y-m-d');
            $nuevaOrden->total = $dataorden->total;
            $nuevaOrden->estado_orden_id = 1; //orden pendiente
            $nuevaOrden->estado = 'A';
            $nuevaOrden->pagado = 'N';
            $nuevaOrden->codigo = $codigo;

            $existeOrden = Orden::where('codigo',$codigo)->get()->first();

            if ($existeOrden) {
                $response = [
                    'status' => false,
                    'mensaje' => 'La orden ya existe',
                    'orden' => null,
                    'ordenservicios' => null,
                ];
            }else{
                if($nuevaOrden->save()){
                    //guarda en la tabla orden-servicio
                    $ordenServicioController = new OrdenServicioController();
                    $extra = $ordenServicioController->guardar($nuevaOrden->id,$ordenServicio);

                    $response = [
                        'status' => true,
                        'mensaje' =>'Guardando los datos',
                        'orden' => $nuevaOrden,
                        'ordenservicio' => $extra
                    ];
                }else{
                    $response = [
                        'status' => false,
                        'mensaje' =>'No se puedo guardar',
                        'orden' => null,
                        'ordenservicio' => null
                    ];
                }
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' =>'La orden ya existe',
                'orden' => null,
                'ordenservicio' => null
            ];
        }
        echo json_encode($response);

    }




    



}



