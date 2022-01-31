<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once './models/entradaModel.php';
require_once './models/usuarioModel.php';

class EntradaController {

    private $limiteKey = 0;
    private $cors;

    public function __construct(){ 
        $this->limiteKey = 6;
        $this->cors = new Cors();
    }

    public function create(Request $request){

        $this->cors->corsJson();
        $entradaData = $request->input('entrada');
        $entradaData->qr = trim($entradaData->qr);

        //Buscar el usuario
        $user = Usuario::where('code_qr', $entradaData->qr)->first();
        $helper = new Helper();
        $entrada = new Entrada();

        $entrada->usuario_id = $user->id;
        $entrada->clave = $helper->generate_key($this->limiteKey);
        $entrada->hora = date('H:i:s');
        $entrada->fecha = date('Y-m-d');
        $entrada->save();

        $response = [
            'status' =>  true,
            'message' => 'Entrada registrada de '.$user->usuario
        ];

        echo json_encode($response);
    }

    public function lastEntry($params){
        $usuario_id = intval($params['usuario_id']);

        $last = Entrada::where('usuario_id', $usuario_id)->orderBy('id', 'desc')->first();

        if($last){
            $response = [
                'status' => true,
                'message' => 'Existe un dato',
                'data' => $last
            ];
        }else{
            $response = [
                'status' => false,
                'message' => 'No existe datos',
                'data' => false
            ];
        }

        echo json_encode($response);
    }
}
