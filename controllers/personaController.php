<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/personaModel.php';

class PersonaController
{

    private $cors;

    public function __construct()
    {
        $this->cors = new Cors();
    }

    public function guardarPersona(Request $request)
    {
        $data = $request->input('persona');
        $response = [];

        $this->cors->corsJson();

        if (!isset($data) || $data == null) {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'persona' => null,
            ];
        } else {
            $response = $this->procesandoDatos($data);
        }

        return $response;
    }
    
    //URL
    public function guardar(Request $request)
    {
        $data = $request->input('persona');
        $response = [];

        $this->cors->corsJson();

        if (!isset($data) || $data == null) {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'persona' => null,
            ];
        } else {
            $response = $this->procesandoDatos($data);
        }

        echo json_encode($response);
    }

    private function procesandoDatos($data)
    {
        $buscar = Persona::where('cedula', $data->cedula)->get()->first();
        $response = [];

        if ($buscar == null) {
            $persona = new Persona;

            //seteando campos o rellenando el modelo
            $persona->cedula = $data->cedula;
            $persona->nombres = $data->nombres;
            $persona->apellidos = $data->apellidos;
            $persona->telefono = $data->telefono;
            $persona->correo = $data->correo;
            $persona->direccion = $data->direccion;
            $persona->estado = 'A';

            if ($persona->save()) {
                $response = [
                    'status' => true,
                    'mensaje' => 'Se ha guardado la persona',
                    'persona' => $persona,
                ];
            } else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo guardar :C',
                    'persona' => null,
                ];
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'La persona ya se encuentra registrada',
                'persona' => $buscar,
            ];
        }

        return $response;
    }
}
