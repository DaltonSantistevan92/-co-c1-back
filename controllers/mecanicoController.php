<?php
require_once 'app/app.php';
require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/mecanicoModel.php';
require_once 'models/ordenModel.php';
require_once 'models/progresoModel.php';
require_once 'models/estado_ordenModel.php';





class MecanicoController
{
    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function listar()
    {
        $this->cors->corsJson();
        $mecanicos = Mecanico::where('estado', 'A')->get();
        $response = [];

        foreach($mecanicos as $item)
        {
            $aux = [
                'mecanico' => $item,
                'persona_id' => $item->persona->id,
                'foto' => $item->persona->usuario
            ];
            $response[] = $aux;
        }

        if(count($mecanicos)>0){
            $response = [
                'status' => true,
                'mensaje' => 'El mecanico exite',
                'mecanico' => $mecanicos
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'El mecanico  no exite',
                'mecanico' => null,
            ];
        }
        echo json_encode($response);
    }

    public function buscarMecanico($params){
        $this->cors->corsJson();
        $texto = $params['texto'];
        $sql = "SELECT m.id,p.cedula,p.nombres,p.apellidos,p.telefono,p.correo,m.status,
        (SELECT usuarios.img FROM usuarios WHERE usuarios.persona_id = p.id) as img
         FROM personas p INNER JOIN mecanicos m ON m.persona_id = p.id WHERE p.estado = 'A'
         and (p.cedula LIKE '$texto%' OR p.nombres LIKE '%$texto%' OR p.apellidos LIKE '%$texto%')";

        $array = $this->conexion->database::select($sql);

        if ($array) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'mecanicos' => $array,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen coincidencias',
                'mecanicos' => null,
            ];
        }
        echo json_encode($response);
    }

    public function reporteOrden($params){
        $this->cors->corsJson();
        $inicio = $params['inicio'];
        $fin = $params['fin'];
        $mecanico_id = intval($params['mecanico_id']);

        if($mecanico_id == -1){
            $ordenes = Orden::where('fecha','>=',$inicio)->where('fecha','<=',$fin)->orderBy('estado_orden_id','Desc')->get();
        }else{
            $ordenes = Orden::where('fecha','>=',$inicio)->where('fecha','<=',$fin)->where('mecanico_id',$mecanico_id)->orderBy('estado_orden_id','Desc')->get();    
        }
        $data = [];
        foreach($ordenes as $ord){
            $progreso = Progreso::where('orden_id',$ord->id)->orderBy('id','Desc')->take(1)->get()->first();

            if($progreso != null){
                $aux = [
                    'id' => $ord->id,
                    'codigo' => $ord->codigo,
                    'progreso' => $progreso,
                    'estado' => $ord->estado_orden->detalle,
                    'estado_id' => $ord->estado_orden_id,
                    'mecanico' => $ord->mecanico->persona,
                    'mecanico_id' => $ord->mecanico->id,
                ];
            }else{
                $aux = [
                    'id' => $ord->id,
                    'codigo' => $ord->codigo,
                    'progreso' => ['detalle' => 'Ninguna', 'total' => 0],
                    'estado' => $ord->estado_orden->detalle,
                    'estado_id' => $ord->estado_orden_id,
                    'mecanico' => $ord->mecanico->persona,
                    'mecanico_id' => $ord->mecanico->id,
                ];
            }
            $data[] = (object)$aux;
        }
        //echo json_encode($data); die();
        $estadoOrden = Estado_Orden::where('estado','A')->orderBy('detalle','Desc')->get();
        $mecanicos = Mecanico::where('estado','A')->get();

        $labels = [];  $dataOrden = [];  $labelsMecanico = [];  $dataMecanico = [];
        //CANTIDAD DE ORDENES X ESTADOS
        foreach($estadoOrden as $eo){
            $labels[] = $eo->detalle;
            $c = 0;
            foreach($data as $item){
                if($eo->id == $item->estado_id)  $c++;
            }
            $dataOrden[] = $c;  $c = 0;
        }
        //CANTIDAD DE ORDENES X MECANICO
        $m = 0;
        foreach($mecanicos as $me){
            foreach($data as $d){
                if($d->mecanico_id == $me->id)  $m++;
            }
            $nombreMecanico = $me->persona->nombres.' '. $me->persona->apellidos;
            $labelsMecanico[]= $nombreMecanico;
            $dataMecanico[] = $m;   $m = 0;
        }

        $response = [
            'lista' => $data,
            'orden' => [
                'labels' => $labels,
                'data' => $dataOrden
            ],
            'mecanico' => [
                'labels' => $labelsMecanico,
                'data' => $dataMecanico
            ]
        ];
        echo json_encode($response);
    }

}