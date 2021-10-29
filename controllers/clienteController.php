<?php

require_once 'app/cors.php';
require_once 'core/conexion.php';
require_once 'app/request.php';
require_once 'models/personaModel.php';
require_once 'models/clienteModel.php';

class ClienteController 
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    
    }

    public function buscar($params){
        $this->cors->corsJson();
        $id = intval($params['id']);
        $response = [];

        $dataCliente = Cliente::find($id);
        $dataCliente->persona;

        if($dataCliente){
        $response = [
            'status' => true,
            'mensaje' => 'Existen Datos',
            'cliente' => $dataCliente
        ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'cliente' => null
            ];

        }
        echo json_encode($response);

    }

    public function listar()
    {
        $this->cors->corsJson();
        $clientes = Cliente::where('estado','A')->get();
        $response = [];
        foreach ($clientes as $item) {
            $aux = [
                'cliente' => $item,
                'persona' => $item->persona->id,
            ];
            $response[] = $aux;
        }

        echo json_encode($response);
    }
  
    public function datatable()
    {
        $clientes = Cliente::where('estado', 'A')
            ->get();

        $data = [];
        $i = 1;
        foreach ($clientes as $c) {

            $botones = '<div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="editar_cliente(' . $c->id . ')">
                    <i class="fa fa-pencil-square fa-lg"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminar_cliente(' . $c->id . ')">
                <i class="fa fa-trash fa-lg"></i>
                </button>
            </div>';

            $data[] = [
                0 => $i,
                1 => $c->persona->cedula,
                2 => $c->persona->nombres,
                3 => $c->persona->apellidos,
                4 => $c->persona->telefono,
                5 => $c->persona->correo,
                6 => $c->persona->direccion,
                7 => $botones,
            ];
            $i++;
        }

        $result = [
            'sEcho' => 1,
            'iTotalRecords' => count($data),
            'iTotalDisplayRecords' => count($data),
            'aaData' => $data,
        ];

        echo json_encode($result);
    }

    public function editar(Request $request){
        $this->cors->corsJson();   
        $cliRequest = $request->input('cliente');
        $id = intval($cliRequest->id);
        $persona_id = intval($cliRequest->persona_id);
        $response = [];       
        $cli = Cliente::find($id);
        if($cliRequest){
            if($cli){
                $cli->persona_id = $persona_id;
                $persona = Persona::find($cli->persona_id);
                $persona->nombres = ucfirst($cliRequest->nombres);
                $persona->apellidos = ucfirst($cliRequest->apellidos);
                $persona->telefono = ucfirst($cliRequest->telefono);
                $persona->correo = ucfirst($cliRequest->correo);
                $persona->direccion = ucfirst($cliRequest->direccion);
                $persona->save();
                $cli->save();  

                $response = [
                    'status' => true,
                    'mensaje' => 'El Cliente se ha actualizado',
                    'data' => $cli,
                ];
            }else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se puede actualizar el cliente',
                ];
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos...!!'
            ];
        }
        echo json_encode($response);
        

    }

    public function eliminar(Request $request){
        $this->cors->corsJson();
        $clienteRequest = $request->input('cliente');
        $id = intval($clienteRequest->id);

        $cliente = Cliente::find($id);
        $response = [];

        if($cliente){
            $cliente->estado = 'I';
            $cliente->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado el cliente', 
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No se ha podido eliminar el cliente', 
            ];
        }
        echo json_encode($response);
    }

    public function buscarCliente($params)
    {
        $this->cors->corsJson();
        $texto = ucfirst($params['texto']);
        $response = [];

        $sql = "SELECT c.id, p.cedula, p.nombres,p.apellidos,p.correo,p.telefono FROM personas p
        INNER JOIN clientes c ON c.persona_id = p.id
        WHERE p.estado = 'A' and (p.cedula LIKE '$texto%' OR p.nombres LIKE '%$texto%' OR p.apellidos LIKE '%$texto%')";

        $array = $this->conexion->database::select($sql);

        if ($array) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'clientes' => $array,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen coincidencias',
                'clientes' => null,
            ];
        }
        echo json_encode($response);
    }

}