<?php

require_once 'app/cors.php';
require_once 'core/conexion.php';
require_once 'app/request.php';
require_once 'models/comprobantesModel.php';

class ComprobantesController 
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    
    }

    public function guardar(Request $request){
        $this->cors->corsJson();
        $response = [];
        $comprobanteRequest = $request->input('comprobantes');

        if($comprobanteRequest){
            $orden_id = intval($comprobanteRequest->orden_id);
            $total = floatval($comprobanteRequest->total);
            $subtotal = floatval($comprobanteRequest->subtotal);
            $iva = floatval($comprobanteRequest->iva);

            $nuevoComprobante = new Comprobantes();
            $nuevoComprobante->orden_id = $orden_id;
            $nuevoComprobante->total = $total;
            $nuevoComprobante->subtotal = $subtotal;
            $nuevoComprobante->iva = $iva;
            $nuevoComprobante->fecha = date('Y-m-d');
            $nuevoComprobante->estado = 'A';

            if($nuevoComprobante->save()){
                $response = [
                    'status' => true,
                    'mensaje' => 'Se generó el comprobante',
                    'comprobantes' => $nuevoComprobante
                ];
                
                
                //actualizar la orden a pagado
                $updateOrden = Orden::find($orden_id);
                $updateOrden->pagado = 'S';
                $updateOrden->save();
                
            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo generar el comprobante',
                    'comprobantes' => $nuevoComprobante
                ]; 
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'no hay datos',
                'comprobantes' => null
            ];
        }
        echo json_encode($response);
    }


}