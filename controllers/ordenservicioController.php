<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/ordenservicioModel.php';
require_once 'models/ordenModel.php';
require_once 'models/servicioModel.php';

class OrdenServicioController{
    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function guardar($orden_id, $servicio = []){
        $response = [];
        if($servicio > 0){
            foreach($servicio as $ser){
                $nuevo = new OrdenServicio();
                $nuevo->orden_id = $orden_id;
                $nuevo->servicio_id = intval($ser->servicio_id);
                $nuevo->estado = 'A';
                $nuevo->save();
            }
            $response = [
                'status' => true,
                'mensaje' => 'Se ha guardado el servicio de la orden',
                'ordenservicio' => $nuevo
            ];
        }else{
            $response = [
                'status' => true,
                'mensaje' => 'No ahi servicio en la orden',
                'ordenservicio' => null
            ];
        }
        return $response;

    }



}