<?php
require_once 'app/app.php';
require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/mecanicoModel.php';



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

}