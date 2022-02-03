<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/entradasModel.php';
require_once 'models/usuarioModel.php';
require_once 'app/helper.php';


class EntradasController
{

    private $limiteKey = 0;
    private $cors;

    public function __construct()
    {
        $this->limiteKey = 6;
        $this->cors = new Cors();
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();
        $entradaData = $request->input('entrada');
        if ($entradaData) {
            $entradaData->qr = trim($entradaData->qr);

            $user = Usuario::where('code_qr', $entradaData->qr)->get()->first();
            if ($user) {
                //Buscar el usuario
                $helper = new Helper();
                $entrada = new Entradas();

                $entrada->usuario_id = $user->id;
                $entrada->clave = $helper->generate_key($this->limiteKey);
                $entrada->hora = date('H:i:s');
                $entrada->fecha = date('Y-m-d');
                $entrada->save();

                $response = [
                    'status' => true,
                    'mensaje' => 'Entrada registrada de ' . $user->usuario,
                ];
            } else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo registrar la entrada',
                ];
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'no hay datos para procesar',
            ];
        }
        echo json_encode($response);
    }

    public function lastEntry($params)
    {
        $usuario_id = intval($params['usuario_id']);

        $last = Entradas::where('usuario_id', $usuario_id)->orderBy('id', 'desc')->first();

        if ($last) {
            $response = [
                'status' => true,
                'mensaje' => 'Existe un dato',
                'data' => $last,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existe datos',
                'data' => false,
            ];
        }

        echo json_encode($response);
    }

    public function buscarEntrada($usuario_id)
    {
        $last = Entradas::where('usuario_id', $usuario_id)->orderBy('id', 'desc')->first();

        if ($last) {
            return $last;   
        } else {
            return false;
        }
 
    }
}
