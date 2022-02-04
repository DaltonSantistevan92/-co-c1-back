<?php

use Illuminate\Support\Facades\Date;

require_once 'app/cors.php';
require_once 'core/conexion.php';
require_once 'app/request.php';
require_once 'models/rol_pagoModel.php';


class Rol_PagoController {

    private $cors;
    private $conexion;
    private $roleMechanic;
    private $hourLaborable = 8;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
        $this->roleMechanic = 2;
    }

    public function guardar(Request $request) { }

    public function create_detail_pay($params){

        $key = $params['clave'];

        //Search entry and sale
        $entry = Entradas::where('clave', $key)->first();
        $sale = Salida::where('clave', $key)->first();

        //Make calc hour for sale minus entry
        $timestapEntry = new DateTime($entry->fecha.' '.$entry->hora);  //Menor
        $timestapSale = new DateTime($sale->fecha.' '.$sale->hora);     //Mayor

        $diff = $timestapSale->diff($timestapEntry);
        $hour =  $diff->format('%h');                   //Including hour eats
        $hour = abs($hour);                             //Get value absolute
        $hour = $hour - 1;                              // Minus - one

        //Identify type role user and extrac money month
        $user = Usuario::find($entry->usuario_id);
        $salario = Salario::where('rol_id', $user->rol_id)->first();
        
        $detail = null;
        //Verify hour most 8 and mechanic
        if($user->rol_id == $this->roleMechanic){   
            // var_dump($hour);    die();

            if($hour > $this->hourLaborable){  

                $diff = ($hour - $this->hourLaborable);

                //Normal
                $newCal = round(floatval($this->hourLaborable) * floatval($salario->salario_hora), 2);
                $detailNormal = $this->makeDetail($user->id, $this->hourLaborable, $salario->salario_hora, $newCal);
                
                //Extra plus percent fee
                $newSaleHour = round(((floatval($salario->salario_hora) * $salario->porcentaje_comision)/100),2) + $salario->salario_hora;
                $total = round(floatval($diff) * floatval($newSaleHour), 2);
                $detailExtra = $this->makeDetail($user->id, $diff, $newSaleHour, $total, 'E');

            }else{
                $total = round(floatval($hour) * floatval($salario->salario_hora), 2);
                $detail = $this->makeDetail($user->id, $hour, $salario->salario_hora, $total);
            }

        }else{  //Other role
            //Make to data for detail_pay
            $total = round(floatval($hour) * floatval($salario->salario_hora), 2);
            $detail = $this->makeDetail($user->id, $hour, $salario->salario_hora, $total);
        }
        
        $response = [
            'status' => true,
            'mensaje' => 'Detail hour generated !!'
        ];

        echo json_encode($response);
    }
    
    private function makeDetail($user_id, $hour, $sale_hour, $total, $type = 'N'){
        $detailPay = new Detalles_Pagos();

        $detailPay->usuario_id = $user_id;
        $detailPay->cant_hora = $hour;
        $detailPay->precio_hora = $sale_hour;
        $detailPay->total = $total;
        $detailPay->type = $type;
        $detailPay->save();

        return $detailPay;
    }
}