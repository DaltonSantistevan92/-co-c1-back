<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'core/params.php';
require_once 'models/vehiculoModel.php';
require_once 'models/clienteVehiculoModel.php';
//require_once 'models/clienteModel.php';
//require_once 'models/ordenModel.php';

class VehiculoController
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function buscar($params)
    {
        $this->cors->corsJson();
        $id = intval($params['id']);
        $response = [];

        $dataVehiculo = Vehiculo::find($id);
        $dataVehiculo->marca;

        if ($dataVehiculo) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'vehiculo' => $dataVehiculo,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen Datos',
                'vehiculo' => null,
            ];
        }
        echo json_encode($response);
    }

    public function listar()
    {
        $this->cors->corsJson();
        $vehiculos = Vehiculo::where('estado', 'A')->get();
        $response = [];

        foreach ($vehiculos as $item) {
            $aux = [
                'vehiculo' => $item,
                'marca_id' => $item->marca->id,
            ];
            $response[] = $aux;
        }

        echo json_encode($vehiculos);
    }

    public function disponible($params)
    {
        $this->cors->corsJson();
        $nuevo_disponible = strtoupper($params['disponible']);
        $response = [];

        if ($nuevo_disponible == 'S' || $nuevo_disponible == 'N') {
            $vehiculos = Vehiculo::where('disponible', $nuevo_disponible)->get();
            
            foreach($vehiculos as $ve){
                $ve->marca;
            }

            $response = [
                'status' => true,
                'mensaje' => 'Vehiculos encontrados',
                'vehiculos' => $vehiculos,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existe',
                'vehiculos' => null,
            ];
        }

        echo json_encode($response);
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();

        $objVehiculo = $request->input('vehiculo');
        $response = [];

        if ($objVehiculo) {
            $objVehiculo->marca_id = intval($objVehiculo->marca_id);
            $objVehiculo->placa = strtoupper($objVehiculo->placa);
            $objVehiculo->modelo = ucfirst($objVehiculo->modelo);
            $objVehiculo->kilometraje = $objVehiculo->kilometraje;

            //Empiezas
            $nuevo = new vehiculo();
            $nuevo->marca_id = $objVehiculo->marca_id;
            $nuevo->placa = $objVehiculo->placa;
            $nuevo->modelo = $objVehiculo->modelo;
            $nuevo->kilometraje = $objVehiculo->kilometraje;
            $nuevo->disponible = 'S';
            $nuevo->estado = 'A';

            $existe = Vehiculo::where('placa', $objVehiculo->placa)->get()->first(); //validar que la placa no se repita

            if ($existe) {
                $response = [
                    'status' => false,
                    'mensaje' => 'La placa ya exite',
                    'vehiculo' => null,
                ];
            } else {
                if ($nuevo->save()) {
                    $response = [
                        'status' => true,
                        'mensaje' => 'El vehiculo se ha guardado',
                        'vehiculo' => $nuevo,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'vehiculo' => null,
                    ];
                }
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No ha enviado datos',
                'vehiculo' => null,
            ];
        }

        echo json_encode($response);
    }

    public function dataTable()
    {
        $vehiculos = Vehiculo::where('estado', 'A')->get();
        $data = [];     $i = 1;
        foreach ($vehiculos as $v) {

            $botones = '<div class="btn-group">
                            <button class="btn btn-sm btn-warning" onclick="editar_vehiculo(' . $v->id . ')">
                                <i class="fa fa-pencil-square fa-lg"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminar_vehiculo(' . $v->id . ')">
                                <i class="fa fa-trash fa-lg"></i>
                            </button>
                        </div>';
            $data[] = [
                0 => $i,
                1 => $v->placa,
                2 => $v->marca->nombre,
                3 => $v->modelo,
                4 => $v->kilometraje,
                5 => $botones,
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
        $vehiculoRequest = $request->input('vehiculo');
        $id = intval($vehiculoRequest->id);
        $marca_id = intval($vehiculoRequest->marca_id);
        $placa = strtoupper($vehiculoRequest->placa);       
        $modelo = ucfirst($vehiculoRequest->modelo);
        $kilometraje = $vehiculoRequest->kilometraje;       
        $response = [];

        $vehiculo = Vehiculo::find($id);
    
        if($vehiculoRequest){
            if($vehiculo){
                $vehiculo->marca_id = $marca_id;
                $vehiculo->placa = $placa;
                $vehiculo->modelo = $modelo;
                $vehiculo->kilometraje = $kilometraje;
                $vehiculo->save();
  
                $response = [
                    'status' => true,
                    'mensaje' => 'El Vehículo se ha actualizado',
                    'data' => $vehiculo,
                ];
            }else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se puede actualizar el vehículo',
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
        $vehiculoRequest = $request->input('vehiculo');
        $id = intval($vehiculoRequest->id);

        $vehiculo = Vehiculo::find($id);
        $response = [];

        if($vehiculo){
            $vehiculo->estado = 'I';
            $vehiculo->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado el vehículo', 
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No se ha podido eliminar el vehiculo', 
            ];
        }
        echo json_encode($response);
    }

    public function guardarClienteVehiculo(Request $request)
    {
        $this->cors->corsJson();
        $datos = $request->input('datos');
        $response = [];

        if ($datos) {
            $datos->cliente_id = intval($datos->cliente_id);
            $datos->vehiculo_id = intval($datos->vehiculo_id);

            $clienteVehiculo = new Cliente_Vehiculo;
            $clienteVehiculo->cliente_id = $datos->cliente_id;
            $clienteVehiculo->vehiculo_id = $datos->vehiculo_id;
            $clienteVehiculo->estado = 'A';

            //validar que no se repita vehiculo_id
            $existe = Cliente_Vehiculo::where('vehiculo_id', $datos->vehiculo_id)->get()->first();

            if ($existe) {
                $response = [
                    'status' => false,
                    'mensaje' => 'El cliente ya tiene asignado ese vehiculo',
                    'vehiculo' => null,
                ];
            } else {
                if ($clienteVehiculo->save()) {
                    //actualizar vehiculo
                    $vehiculo = Vehiculo::find($datos->vehiculo_id);
                    $vehiculo->disponible = 'N';
                    $vehiculo->save();

                    $response = [
                        'status' => true,
                        'mensaje' => 'Se ha asignado el vehículo',
                        'vehiculo' => $clienteVehiculo,
                    ];

                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se ha guardado los datos ',
                        'vehiculo' => null,
                    ];
                }

            }

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No ha enviado datos',
                'vehiculo' => null,
            ];
        }

        echo json_encode($response);

    }

    public function buscarVehiculo($params)
    {
        $this->cors->corsJson();
        $texto = ucfirst($params['texto']);
        $response = [];

        $sql = "SELECT m.id,m.nombre,v.marca_id,v.placa,v.modelo,v.kilometraje FROM vehiculos v
        INNER JOIN marcas m ON m.id = v.marca_id
        WHERE v.disponible = 'S' and (v.placa LIKE '$texto%' OR m.nombre LIKE '%$texto%' OR v.modelo LIKE '%$texto%' OR v.kilometraje LIKE '%$texto%')";

        $array = $this->conexion->database::select($sql);

        if ($array) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'vehiculos' => $array,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen coincidencias',
                'vehiculos' => null,
            ];
        }
        echo json_encode($response);
    }

    public function clienteVehiculo(){
        $this->cors->corsJson();
        $clivehi = Cliente_Vehiculo::where('estado','A')->get();
        $response = [];
        
        foreach($clivehi as $item){
            $item->id;
            $item->cliente->persona;
            $item->vehiculo->marca;
        }

        if($clivehi){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'datos' => $clivehi
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No existen Datos',
                'datos' => null
            ];
        }
        echo json_encode($response);

    }

    public function eliminarClienteVehiculo(Request $request){
        $this->cors->corsJson();
        $datos = $request->input('datos');
        $id = intval($datos->id);
        $datos->cliente_id = intval($datos->cliente_id);
        $datos->vehiculo_id = intval($datos->vehiculo_id);
        $response = [];

        $datos = Cliente_Vehiculo::find($id);

        if($datos){

            $datos->cliente_id = $datos->cliente_id;
            $datos->vehiculo_id = $datos->vehiculo_id;
             
            //actualizar vehiculo
            $vehiculo = Vehiculo::find($datos->vehiculo_id);
            $vehiculo->disponible = 'S';
            $vehiculo->save();
            $datos->delete(); 

            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado el cliente con su vehículo',
                'vehiculo' => $datos,
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No se ha eliminado el cliente con su vehículo',
                'vehiculo' => null
            ];
        }
        echo json_encode($response);

    }
}
