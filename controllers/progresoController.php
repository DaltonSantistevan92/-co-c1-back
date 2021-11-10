<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/progresoModel.php';
require_once 'models/ordenModel.php';

class ProgresoController
{
    private $cors;
    private $db;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->db = new Conexion();
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();
        $dataProgreso = $request->input('progreso');
        $response = [];

        if ($dataProgreso) {
            $dataProgreso->orden_id = intval($dataProgreso->orden_id);
            $dataProgreso->detalle = $dataProgreso->detalle;
            $dataProgreso->progreso = intval($dataProgreso->progreso);
            $dataProgreso->total = intval($dataProgreso->total);
            $dataProgreso->faltante = ucfirst($dataProgreso->faltante);

            $nuevo = new Progreso();
            $nuevo->orden_id = $dataProgreso->orden_id;
            $nuevo->detalle = ucfirst($dataProgreso->detalle);
            $nuevo->progreso = $dataProgreso->progreso;
            $nuevo->total = $dataProgreso->total;
            $nuevo->faltante = $dataProgreso->faltante;
            $nuevo->estado = 'A';
            $nuevo->save();

            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
            ];
        }
        echo json_encode($response);
    }

    public function guardarmodal(Request $request){
        $this->cors->corsJson();
        $response = [];
        $dataProgreso = $request->input('progreso');

        if($dataProgreso){
            $dataProgreso->orden_id = intval($dataProgreso->orden_id);
            $dataProgreso->detalle = ucfirst($dataProgreso->detalle);
            $dataProgreso->progreso = intval($dataProgreso->progreso);

            $last = Progreso::where('orden_id', $dataProgreso->orden_id)->orderBy('id', 'DESC')->get()->first();
            $progressTemp = $last->total + $dataProgreso->progreso;

            if($last){
                if($progressTemp <= 100){
                    if($last->total > 100){
                        $response = [
                            'status' => false,
                            'mensaje' => 'EL progreso no puede pasarse del 100%'
                        ];
                    }else
                    if($last->total < 100){    //menor a 100%
                        $nuevo = new Progreso();
                        $nuevo->orden_id = $dataProgreso->orden_id;
                        $nuevo->detalle = $dataProgreso->detalle;
        
                        $aux_progreso = $last->total + $dataProgreso->progreso;
                        
                        $nuevo->progreso = $dataProgreso->progreso;
                        $nuevo->total  = $aux_progreso;
                        $nuevo->faltante = (100 - $aux_progreso); 
                        $nuevo->estado = 'A';
                        $nuevo->save();
    
                        $response = [
                            'status' => true,
                            'mensaje' => 'Se ha guardado el progreso correctamente'
                        ];
                    }if($last->total == 100){
                        $response = [
                            'status' => false,
                            'mensaje' => 'El progreso de la orden están al 100%'
                        ]; 
                    }
                }else{
                    $response = [
                        'status' => false,
                        'mensaje' => 'El progreso de la orden no puede exceder el 100%'
                    ]; 
                }
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No ha enviado datos'
            ];
        }
        echo json_encode($response);
    }

    public function listar($params)
    {
        $this->cors->corsJson();
        $orden_id = intval($params['id']);

        $progreso = Progreso::where('orden_id', $orden_id)->get();
        $response = [];

        if($progreso){
            $response  = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'progreso' => $progreso
            ];
        }else{
            $response  = [
                'status' => false,
                'mensaje' => 'No existen datos',
                'progreso' => []
            ];
        }
        echo json_encode($response);
    }
}
