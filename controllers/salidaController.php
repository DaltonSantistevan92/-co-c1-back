<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/salidaModel.php';
require_once 'models/usuarioModel.php';
require_once 'controllers/entradasController.php';
require_once 'app/helper.php';

class SalidaController
{

    private $cors;
    private $entradaCtrl;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->entradaCtrl = new EntradasController();
    }

    public function guardarSalida(Request $request)
    {
        $this->cors->corsJson();
        $dataSalida = $request->input('salida');
        $dataSalida->qr;
        $response = [];

        if ($dataSalida) {
            $usuario = Usuario::where('code_qr', $dataSalida->qr)->get()->first();

            $entradaUsuario = $this->entradaCtrl->buscarEntrada($usuario->id);

            if ($entradaUsuario) {
                $existeSalida = Salida::where('clave', $entradaUsuario->clave)->get()->first();

                if ($existeSalida) {
                    $response = [
                        'status' => false,
                        'mensaje' => 'ya tiene registrado una salida',
                    ];
                } else {
                    $nuevoSalida = new Salida();
                    $nuevoSalida->usuario_id = intval($usuario->id);
                    $nuevoSalida->clave = $entradaUsuario->clave;
                    $nuevoSalida->hora = date('H:i:s');
                    $nuevoSalida->fecha = date('Y-m-d');
                    $nuevoSalida->save();

                    $response = [
                        'status' => true,
                        'message' => 'Salida registrada de ' . $usuario->persona->nombres,
                    ];

                }
            } else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No existe la clave',
                ];
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'data' => null,
            ];
        }
        echo json_encode($response);
    }

}
