<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'app/helper.php';
require_once 'core/conexion.php';
require_once 'core/params.php';
require_once 'models/ordenModel.php';
require_once 'models/clienteModel.php';
require_once 'models/usuarioModel.php';
require_once 'models/progresoModel.php';
require_once 'models/servicioModel.php';
require_once 'controllers/ordenservicioController.php';
require_once 'controllers/servicioController.php';

class OrdenController
{

    private $cors;
    private $conexion;
    private $limit_key = 6;
    private $servicioCtrl;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
        $this->servicioCtrl = new ServicioController();
    }

    public function buscar($params)
    {
        $this->cors->corsJson();
        $id = intval($params['id']);
        $response = [];

        $dataOrden = Orden::find($id);

        if ($dataOrden == null) {
            $response = [
                'status' => false,
                'mensaje' => 'No existen Datos',
                'orden' => null,
            ];
        } else {
            //cargar los servicios
            $servicios = $this->servicioCtrl->getServicioByOrden($id);

            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'orden' => $dataOrden,
                'usuario_id' => $dataOrden->usuario->persona->id,
                'cliente_id' => $dataOrden->cliente->persona->id,
                'vehiculo_id' => $dataOrden->vehiculo->marca->id,
                'mecanico_id' => $dataOrden->mecanico->persona->id,
                'estado_orden_id' => $dataOrden->estado_orden->id,
                'servicio' => $servicios,
            ];
        }
        echo json_encode($response);
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();
        $dataorden = $request->input('orden');
        $ordenServicio = $request->input('ordenservicios');
        $response = [];

        $helper = new Helper();
        $codigo = $helper->generate_key($this->limit_key);

        if ($dataorden) {
            $dataorden->usuario_id = intval($dataorden->usuario_id);
            $dataorden->cliente_id = intval($dataorden->cliente_id);
            $dataorden->vehiculo_id = intval($dataorden->vehiculo_id);
            $dataorden->mecanico_id = intval($dataorden->mecanico_id);
            $dataorden->total = floatval($dataorden->total);

            //Guarda la nueva orden
            $nuevaOrden = new Orden();
            $nuevaOrden->usuario_id = $dataorden->usuario_id;
            $nuevaOrden->cliente_id = $dataorden->cliente_id;
            $nuevaOrden->vehiculo_id = $dataorden->vehiculo_id;
            $nuevaOrden->mecanico_id = $dataorden->mecanico_id;
            $nuevaOrden->fecha = date('Y-m-d');
            $nuevaOrden->total = $dataorden->total;
            $nuevaOrden->estado_orden_id = 1; //orden pendiente
            $nuevaOrden->estado = 'A';
            $nuevaOrden->pagado = 'N';
            $nuevaOrden->codigo = $codigo;

            $existeOrden = Orden::where('codigo', $codigo)->get()->first();

            if ($existeOrden) {
                $response = [
                    'status' => false,
                    'mensaje' => 'La orden ya existe',
                    'orden' => null,
                    'ordenservicios' => null,
                ];
            } else {
                if ($nuevaOrden->save()) {
                    //guarda en la tabla orden-servicio
                    $ordenServicioController = new OrdenServicioController();
                    $extra = $ordenServicioController->guardar($nuevaOrden->id, $ordenServicio);

                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando los datos',
                        'orden' => $nuevaOrden,
                        'ordenservicio' => $extra,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se puedo guardar',
                        'orden' => null,
                        'ordenservicio' => null,
                    ];
                }
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'La orden ya existe',
                'orden' => null,
                'ordenservicio' => null,
            ];
        }
        echo json_encode($response);

    }

    public function visualizar($params)
    {
        $this->cors->corsJson();
        $opcion = $params['opcion'];
        $estado = intval($params['estado']);

        $response = $this->ordenesByEstado($estado, $opcion);
        echo json_encode($response);
    }

    private function ordenesByEstado($estado_orden_id, $opcion)
    {
        $hoy = date('Y-m-d');
        $pend = 1;
        $existe = '';
        $datos = [];
        $response = [];
        $servicioController = new ServicioController;

        if ($opcion == '1') { //hoy
            $pendientes = Orden::where('estado_orden_id', $estado_orden_id)
                ->where('fecha', $hoy)->orderBy('id', 'DESC')->get();

            $existe = (count($pendientes) > 0) ? '1' : '0';
        } else
        if ($opcion == '2') { //ayer
            $ayer = date("Y-m-d", strtotime($hoy . "- 1 days"));

            $pendientes = Orden::where('estado_orden_id', $estado_orden_id)
                ->where('fecha', $ayer)->orderBy('id', 'DESC')->get();

            $existe = (count($pendientes) > 0) ? '1' : '0';
        } else
        if ($opcion == '3') { //ultimo 7 dias
            $last7days = date("Y-m-d", strtotime($hoy . "- 7 days"));

            $pendientes = Orden::where('estado_orden_id', $estado_orden_id)
                ->where('fecha', '>=', $last7days)
                ->where('fecha', '<=', $hoy)->orderBy('id', 'DESC')->get();

            $existe = (count($pendientes) > 0) ? '1' : '0';
        } else {
            $existe = '1'; //pendiente
        }

        if ($existe == '1') { //pendiente
            foreach ($pendientes as $pen) {
                $servicios = $servicioController->getServicioByOrden($pen->id);

                $aux = [
                    'orden' => $pen,
                    'cliente_id' => $pen->cliente->persona->id,
                    'vehiculo_id' => $pen->vehiculo->marca->id,
                    'usuario_id' => $pen->usuario->persona->id,
                    'mecanico_id ' => $pen->mecanico->persona->id,
                    'estado_orden_id' => $pen->estado_orden->id,
                    'servicios' => $servicios,

                ];
                $datos[] = $aux;
            }
            $response = [
                'status' => true,
                'mensaje' => 'Existen ordenes',
                'ordenes' => $datos,
            ];
        } else
        if ($existe == '0') {
            $response = [
                'status' => false,
                'mensaje' => 'No existen datos para la consulta realizadas',
                'ordenes' => null,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'El parametro ingresado no es válido',
                'ordenes' => null,
            ];
        }
        return $response;
    }

    public function actualizarOrden($params)
    {
        $this->cors->corsJson();
        $id_orden = intval($params['id_orden']);
        $id_estado = intval($params['estado_id']);
        $estado_mecanico = ucfirst($params['estado_mecanico']);
        $mensajes = '';
        $response = [];

        $orden = Orden::find($id_orden);

        if ($orden) {
            $mecanico = Mecanico::find($orden->mecanico_id);

            $orden->estado_orden_id = $id_estado;
            $orden->save();

            if ($estado_mecanico == 'D' || $estado_mecanico == 'O') {
                $mecanico->status = $estado_mecanico;
                $mecanico->save();
            }

            switch ($id_estado) {
                case 1:
                    $mensajes = 'La orden esta pendiente';
                    break;
                case 2:
                    $mensajes = 'La orden esta en proceso';
                    break;
                case 3:
                    $mensajes = 'La orden se encuentra terminada';
                    break;
                    /* case 4:
            $mensajes = 'La orden ha sido cancelada'; break; */
            }

            $response = [
                'status' => true,
                'mensaje' => $mensajes,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se puede actualizar la orden',
            ];
        }
        echo json_encode($response);

    }

    public function estado($params)
    {
        $this->cors->corsJson();
        $id_persona = intval($params['id_persona']);
        $id_estado = intval($params['estado_id']);

        $response = [];

        $usuario = Usuario::where('estado', 'A')->where('persona_id', $id_persona)->get()->first();

        if ($usuario) {
            $usu_id = $usuario->id;

            $pendientes = Orden::where('estado', 'A')
                ->where('estado_orden_id', $id_estado)
                ->where('pagado', 'N')
                ->where('usuario_id', $usu_id)->orderBy('id', 'DESC')->get();

            $servicioController = new ServicioController;
            foreach ($pendientes as $pen) {

                $serv = $servicioController->getServicioByOrden($pen->id);
                $ultimoProgreso = Progreso::where('orden_id', $pen->id)->orderBy('id', 'desc')->get()->first();

                $aux = [
                    'orden' => $pen,
                    'cliente_id' => $pen->cliente->persona->id,
                    'vehiculo_id' => $pen->vehiculo->marca->id,
                    'usuario_id' => $pen->usuario->persona->id,
                    'estado_orden_id' => $pen->estado_orden->id,
                    'servicios' => $serv,
                    'ultimo_progreso' => $ultimoProgreso,
                ];
                $response[] = $aux;
            }

        }
        echo json_encode($response);

    }

    public function estadoPagada($params)
    {
        $this->cors->corsJson();
        $id_persona = intval($params['id_persona']);
        $id_estado = intval($params['estado_id']);

        $response = [];

        $usuario = Usuario::where('estado', 'A')->where('persona_id', $id_persona)->get()->first();

        if ($usuario) {
            $usu_id = $usuario->id;

            $pendientes = Orden::where('estado', 'A')
                ->where('estado_orden_id', $id_estado)
                ->where('pagado', 'S')
                ->where('usuario_id', $usu_id)->orderBy('id', 'DESC')->get();

            $servicioController = new ServicioController;
            foreach ($pendientes as $pen) {

                $serv = $servicioController->getServicioByOrden($pen->id);
                $ultimoProgreso = Progreso::where('orden_id', $pen->id)->orderBy('id', 'desc')->get()->first();

                $aux = [
                    'orden' => $pen,
                    'cliente_id' => $pen->cliente->persona->id,
                    'vehiculo_id' => $pen->vehiculo->marca->id,
                    'usuario_id' => $pen->usuario->persona->id,
                    'estado_orden_id' => $pen->estado_orden->id,
                    'servicios' => $serv,
                    'ultimo_progreso' => $ultimoProgreso,
                ];
                $response[] = $aux;
            }

        }
        echo json_encode($response);

    }

    private function _ordenes($mes, $estado)
    {
        $ordenes = Orden::where('estado', 'A')
            ->where('estado_orden_id', $estado)
            ->whereMonth('created_at', $mes)->get();

        return $ordenes;
    }

    public function cantidades_estados()
    {
        $this->cors->corsJson();
        $pendiente = 1;
        $proceso = 2;
        $terminado = 3;

        $mes = date('m');
        $nombreMes = Helper::mes($mes);

        $ordenesPendientes = $this->_ordenes($mes, $pendiente);
        $ordenesProceso = $this->_ordenes($mes, $proceso);
        $ordenesTerminado = $this->_ordenes($mes, $terminado);

        $cantPendientes = ($ordenesPendientes->count()) ? $ordenesPendientes->count() : 0;
        $cantProcesos = ($ordenesProceso->count()) ? $ordenesProceso->count() : 0;
        $cantTerminados = ($ordenesTerminado->count()) ? $ordenesTerminado->count() : 0;

        $response = [
            'status' => true,
            'cantidad' => [
                'pendientes' => $cantPendientes,
                'procesos' => $cantProcesos,
                'terminados' => $cantTerminados,
            ],
            'mes' => $nombreMes,
        ];
        echo json_encode($response);
    }

    private function _OrdenesPagadas($mes, $estado, $pagado)
    {

        $ordenesPagadas = Orden::where('estado', 'A')
            ->where('estado_orden_id', $estado)
            ->where('pagado', $pagado)
            ->whereMonth('created_at', $mes)->get();

        return $ordenesPagadas;
    }

    public function contarOrdenesPagadas()
    {
        $this->cors->corsJson();
        $atendido = 3;
        $pagado = 'S';

        $mes = date('m');
        $nombreMes = Helper::mes($mes);

        $ordenesAtendidas = $this->_OrdenesPagadas($mes, $atendido, $pagado);

        $cantOrdenesAtendidasPagadas = ($ordenesAtendidas->count()) ? $ordenesAtendidas->count() : 0;

        $response = [
            'status' => true,
            'cantidad' => [
                'pagadas' => $cantOrdenesAtendidasPagadas,
            ],
            'mes' => $nombreMes,
        ];
        echo json_encode($response);
    }

    public function ordenesRealizados($params)
    {
        $this->cors->corsJson();

        $inicio = $params['inicio'];
        $fin = $params['fin'];
        $estado_orden_id = intval($params['estado_orden_id']);
        $top = intval($params['top']);

        $ord = [];
        $precio_general = 0;
        $total_general = 0;

        //todos
        if ($estado_orden_id == -1) {
            $ordenes = Orden::where('fecha', '>=', $inicio)
                ->where('fecha', '<=', $fin)
                ->orderBy('codigo')
                ->take($top)->get();

            foreach ($ordenes as $orde) {
                $ordenservicio = $orde->orden_servicio;

                foreach ($ordenservicio as $ser) {
                    $servicio = $ser->servicio;
                    $nombreServicio = $servicio->detalle;

                    $aux = [
                        'nombre_servicio' => $nombreServicio,
                        'precio' => $servicio->precio,
                        'total' => $orde->total,
                    ];
                    $ord[] = (object) $aux;
                    $precio_general += $servicio->precio;
                    $total_general += $orde->total;
                }
            }

        } else {
            $ordenes = Orden::where('estado_orden_id', $estado_orden_id)
                ->where('fecha', '>=', $inicio)
                ->where('fecha', '<=', $fin)
                ->orderBy('codigo')
                ->take($top)->get();

            foreach ($ordenes as $orde) {

                $ordenservicio = $orde->orden_servicio;

                foreach ($ordenservicio as $ser) {
                    $servicio = $ser->servicio;
                    $nombreServicio = $servicio->detalle;


                    $aux = [
                        'nombre_servicio' => $nombreServicio,
                        'precio' => $servicio->precio,
                        
                    ];
                    $ord[] = (object) $aux;
                    $precio_general += $servicio->precio;
                    $total_general += $orde->total;
                }
            }
        }

        $response = [
            'status' => true,
            'mensaje' => 'Datos procesados',
            'data' => $ord,
            'total_general' => $total_general,
            'precio_general' => $precio_general,
        ];

        echo json_encode($response);

    }

    public function graficaOrden()
    {
        $this->cors->corsJson();
        $year = date('Y');

        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
        ];
        $data = [];

        //Obtener total de las ordenes
        for ($i = 0; $i < count($meses); $i++) {
            $sqlOrden = "SELECT SUM(total ) as suma FROM `orden` WHERE MONTH(fecha) = ($i + 1) AND  YEAR(fecha) = $year AND estado = 'A'";

            $ordenesMes = $this->conexion->database::select($sqlOrden);

            $data[] = ($ordenesMes[0]->suma) ? round($ordenesMes[0]->suma, 2) : 0;

            $response = [
                'orden' => [
                    'labels' => $meses,
                    'data' => $data,
                    'anio' => $year,
                ],
            ];
        }
        echo json_encode($response);
    }

    public function servicioFrecuente($params){
        $this->cors->corsJson();
        $inicio = $params['inicio'];
        $fin = $params['fin'];
        $top = intval($params['top']);
        $ordenesTerminadas = 3;

        $orden = Orden::where('fecha', '>=', $inicio)->where('fecha', '<=', $fin)->where('estado', 'A')
                    ->where('estado_orden_id',$ordenesTerminadas)->where('pagado','S')->take($top)->get();
        
        $data = [];  $dataServicio_id = []; $total_general = 0;

        foreach($orden as $_ord){
            $c = 0;
            $_ordSer = $_ord->orden_servicio;
            $total = $_ord->total;
            $cliente_id = $_ord->cliente_id;
            foreach($_ordSer as $item){
                $_orServi_id = $item->id;
                $_orden_id = $item->orden_id;
                $_servi_id = $item->servicio->id; 
                
                $servicioOrden = OrdenServicio::where('orden_id',$_orden_id)->get();
                foreach($servicioOrden as $o){
                   $o->servicio->detalle;
                }
                $c++;   
            }
      
            $aux = [
                'orden_servicio_id' => $_orServi_id,
                'cliente_id' => $cliente_id,
                'orden_id' => $_orden_id,
                'servicio_id' => $_servi_id,
                'orden_servicio' => $servicioOrden,
                'cantidad' => $c,
                'total' => $total
            ];
            $data[] = (object)$aux;
            $dataServicio_id[] = $_servi_id;
            $total_general += $_ord->total;
        }

        $noRepetidos = array_values(array_unique($dataServicio_id));
        $newArray = [];  $contador = 0;  

        //algoritmo para contar y eliminar los elementos repetidos de un array
        for ($i = 0; $i < count($noRepetidos); $i++) {
            foreach ($data as $item) {
                if ($item->servicio_id === $noRepetidos[$i]) {
                    $_cliente_id = $item->cliente_id;
                    $_orden_id = $item->orden_id;
                    $contador += $item->cantidad;                
                }
            }
            $aux = [
                'orden_id' => $_orden_id,
                'cliente_id' => $_cliente_id,
                'servicio_id' => $noRepetidos[$i],
                'cantidad' => $contador,
            ];

            $contador = 0;
            $newArray[] = (object) $aux;
            $aux = [];
        }

        $arrayOrdenado = $this->ordenarArray($newArray);
        $arrayOrdenado = Helper::invertir_array($arrayOrdenado);

        $arraySec = [];

        //recortar segun su top
        if (count($arrayOrdenado) < $top) {
            $arraySec = $arrayOrdenado;
        } else if (count($arrayOrdenado) == $top) {
            $arraySec = $arrayOrdenado;
        } else if (count($arrayOrdenado) > $top) {
            for ($i = 0; $i < $top; $i++) {
                $arraySec[] = $arrayOrdenado[$i];
            }
        }
        //arma la data mas frecuentes para un grafico estadistico
        $labelsClientes = [];
        foreach($arraySec as $asec){
            $ordenes = Orden::find($asec->orden_id);
            $labelsClientes[] = $ordenes->cliente->persona->nombres.' '.$ordenes->cliente->persona->apellidos;
            $cantidad[] = $asec->cantidad;
        }
        
        //armar la data en una lista
        foreach($data as $d){
            $orden = Orden::find($d->orden_id);
            $codigo = $orden->codigo;

            $cliente = Cliente::find($d->cliente_id);
            $nombreClientes = $cliente->persona->nombres. ' '. $cliente->persona->apellidos;

            $total = $d->total;
            $aux = [
                'codigo' => $codigo,
                'cliente-id' => $d->cliente_id,
                'nombre' => $nombreClientes,
                'servicio_id' => $d->servicio_id,
                'servicio' => $d->orden_servicio, 
                'total' => $total,
            ];
            $final[] = (object)$aux;
        }

        $response = [
            'lista_servicio' => $final,
            'data' =>[
                'masFrecuentes' => [
                    'labels'=> $labelsClientes,
                    'cantidad' => $cantidad
                ],
            ],
            'total_general' => $total_general,     
        ];
    
        echo json_encode($response); die();    
    }

    function ordenarArray($array){
        for ($i=1; $i < count($array); $i++) { 
            for ($j=0; $j < count($array) - $i; $j++) { 
                if($array[$j] > $array[$j + 1]){
                    $chelas = $array[$j + 1];
                    $array[$j + 1] = $array[$j];
                    $array[$j] = $chelas;
                }
            }
            
        }
        return $array;
    }

    public function mantenimientoRegresionLineal($params){
        $this->cors->corsJson();
        $inicio = $params['inicio'];
        $fin = $params['fin'];

        $ordenesTerminadas = 3;

        $orden = Orden::where('fecha', '>=', $inicio)->where('fecha', '<=', $fin)->where('estado', 'A')
                    ->where('estado_orden_id',$ordenesTerminadas)->where('pagado','S')->get();

        $data = [];  $dataServicio_id = [];

        foreach($orden as $_ord){
            $c = 0;
            $_ordSer = $_ord->orden_servicio;
            $total = $_ord->total;
            $cliente_id = $_ord->cliente_id;
            foreach($_ordSer as $item){
                $_orServi_id = $item->id;
                $_orden_id = $item->orden_id;
                $_servi_id = $item->servicio->id;  
                $c++;   
            }

            $aux = [
                'cliente_id' => $cliente_id,
                'orden_servicio_id' => $_orServi_id,
                'orden_id' => $_orden_id,
                'servicio_id' => $_servi_id,
                'cantidad' => $c,
                'total' => $total
            ];
            $data[] = (object)$aux;
            $dataServicio_id[] = $_servi_id;
        }

        

        echo json_encode($data);
        echo json_encode($dataServicio_id);
        

    }

    

}
