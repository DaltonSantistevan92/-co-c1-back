<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/rolModel.php';



class RolController
{

    private $cors;
  

    public function __construct()
    {
        $this->cors = new Cors();
       
    }

    public function listar()
    {
        $this->cors->corsJson();

        $roles = Rol::where('estado', 'A')->orderBy('cargo')->get();
        $response = [];

        if ($roles) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen cargos',
                'cargo' => $roles,
            ];
        } else {
            $response = [
                'status' => false,
                'cargo' => null,
                'mensaje' => 'No existen cargos',
            ];
        }
        echo json_encode($response);
    }

    /* public function getRolId($cargo){
        $rol = Rol::where('cargo',$cargo)->get()->first();

        if(isset($rol->id)){
            return $rol->id;
        }else{
            return false;
        }
    } */
}
