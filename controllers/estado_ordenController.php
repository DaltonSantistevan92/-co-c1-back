<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/estado_ordenModel.php';

class Estado_OrdenController
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function listar(){
        $this->cors->corsJson();
        $response = [];

        $estados = Estado_Orden::where('estado', 'A')->get();

        if($estados){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'estado' => $estados
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'estado' => $estados
            ];

        }
        echo json_encode($response);
    }

    public function realizadas(){
        $this->cors->corsJson();

        $estado = Estado_Orden::where('estado','A')->get();  //3
                
        $labels = [];  $data = []; $dataPorcentaje = []; $response = [];
        
        foreach($estado as $e){
            $orden = $e->orden;//orden terminada
            $labels[] = $e->detalle;
            $data[] = count($orden);
            
        }
        


        $suma = 0;

        for ($i=0; $i < count($data); $i++) { 
            $suma += $data[$i];      
        }

        for ($i=0; $i < count($data) ; $i++) { 
            $aux = ((100 * $data[$i] ) / $suma);
            $dataPorcentaje[] = round($aux,2);

        }


        $response = [
            'status' => true,
            'mensaje' =>'Existen datos',
            'datos' => [
                'labels' => $labels,
                'data' => $data,
                'porcentaje'=> $dataPorcentaje
            ]
        ];
        
        echo json_encode($response); die();

    

    }

}