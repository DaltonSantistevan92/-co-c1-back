<?php

use Illuminate\Support\Facades\Date;

require_once 'app/cors.php';
require_once 'core/conexion.php';
require_once 'app/request.php';
require_once 'models/rol_pagoModel.php';


class Rol_PagoController
{

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

    public function guardar(Request $request)
    {
    }

    public function create_detail_pay($params)
    {

        $key = $params['clave'];

        //Search entry and sale
        $entry = Entradas::where('clave', $key)->first();
        $sale = Salida::where('clave', $key)->first();

        //Make calc hour for sale minus entry
        $timestapEntry = new DateTime($entry->fecha . ' ' . $entry->hora);  //Menor
        $timestapSale = new DateTime($sale->fecha . ' ' . $sale->hora);     //Mayor

        $diff = $timestapSale->diff($timestapEntry);
        $hour =  $diff->format('%h');                   //Including hour eats
        $hour = abs($hour);                             //Get value absolute
        $hour = $hour - 1;                              // Minus - one

        //Identify type role user and extrac money month
        $user = Usuario::find($entry->usuario_id);
        $salario = Salario::where('rol_id', $user->rol_id)->first();

        $detail = null;
        //Verify hour most 8 and mechanic
        if ($user->rol_id == $this->roleMechanic) {
            // var_dump($hour);    die();

            if ($hour > $this->hourLaborable) {

                $diff = ($hour - $this->hourLaborable);

                //Normal
                $newCal = round(floatval($this->hourLaborable) * floatval($salario->salario_hora), 2);
                $detailNormal = $this->makeDetail($user->id, $this->hourLaborable, $salario->salario_hora, $newCal);

                //Extra plus percent fee
                $newSaleHour = round(((floatval($salario->salario_hora) * $salario->porcentaje_comision) / 100), 2) + $salario->salario_hora;
                $total = round(floatval($diff) * floatval($newSaleHour), 2);
                $detailExtra = $this->makeDetail($user->id, $diff, $newSaleHour, $total, 'E');
            } else {
                $total = round(floatval($hour) * floatval($salario->salario_hora), 2);
                $detail = $this->makeDetail($user->id, $hour, $salario->salario_hora, $total);
            }
        } else {  //Other role
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

    private function makeDetail($user_id, $hour, $sale_hour, $total, $type = 'N')
    {
        $detailPay = new Detalles_Pagos();

        $detailPay->usuario_id = $user_id;
        $detailPay->cant_hora = $hour;
        $detailPay->precio_hora = $sale_hour;
        $detailPay->total = $total;
        $detailPay->type = $type;
        $detailPay->save();

        return $detailPay;
    }

    public function createRolPago($params)
    {

        $inicio = $params['inicio'];
        $fin = $params['fin'];
        $user_id = intval($params['user_id']);

        //Consultar a los detalles pagos
        $detalleHorasNormal = Detalles_Pagos::where('usuario_id', $user_id)->where('type', 'N')
            ->whereDate('created_at', '>=', $inicio)->whereDate('created_at', '<=', $fin)->get();
        $detalleHorasExtras = Detalles_Pagos::where('usuario_id', $user_id)->where('type', 'E')
            ->whereDate('created_at', '>=', $inicio)->whereDate('created_at', '<=', $fin)->get();
        $detalleHorasTotales = Detalles_Pagos::where('usuario_id', $user_id)
            ->whereDate('created_at', '>=', $inicio)->whereDate('created_at', '<=', $fin)->get();

        $response = [];
        $hoursExtras = 0;
        $horas_normal = 0;
        $totalExtras = 0;
        $totalNormal = 0;

        $user = Usuario::find($user_id);
        $cargo = $user->rol->cargo;
        $porcentaje_iess = 5;

        if ($detalleHorasTotales->count() > 0) {
            foreach ($detalleHorasTotales as $extra) {

                if ($extra->type == 'E') {
                    $hoursExtras += $extra->cant_hora;
                    $totalExtras += $extra->total;
                }

                if ($extra->type == 'N') {
                    $horas_normal += $extra->cant_hora;
                    $totalNormal += $extra->total;
                }
            }

            $total = $totalExtras + $totalNormal;
            $aporteIess = round(floatval(($total * $porcentaje_iess) / 100), 2);

            $sueldo = $total - $aporteIess;

            $response = [
                'usuario_id' => $user_id,
                'cargo' => $cargo,
                'persona' => $user->persona,
                'horas_normales' => $horas_normal,
                'horas_extras' => $hoursExtras,
                'total_normal' => $totalNormal,
                'total_extra' => $totalExtras,
                'porcentaje_iess' => $porcentaje_iess,
                'aporte_iess' => $aporteIess,
                'total_ingresos' => $total,
                'total_descuentos' => 0,
                'sueldo_recibir' => $sueldo
            ];

            //Guardar el rol_pago
            $rolPago = new Rol_Pago();
            $rolPago->usuario_id = $response['usuario_id'];
            $rolPago->horas_normales = $response['horas_normales'];
            $rolPago->horas_extras = $response['horas_extras'];
            $rolPago->total_normal = $response['total_normal'];
            $rolPago->total_extra = $response['total_extra'];
            $rolPago->porcentaje_iess = $response['porcentaje_iess'];
            $rolPago->aporte_iess = $response['aporte_iess'];
            $rolPago->total_ingresos = $response['total_ingresos'];
            $rolPago->total_descuentos = $response['total_descuentos'];
            $rolPago->sueldo_recibir = $response['sueldo_recibir'];

            $rolPago->save();
        } else {
            $response = [
                'usuario_id' => $user_id,
                'cargo' => $cargo,
                'persona' => $user->persona,
                'horas_normales' => $horas_normal,
                'horas_extras' => $hoursExtras,
                'total_normal' => $totalNormal,
                'total_extra' => $totalExtras,
                'porcentaje_iess' => $porcentaje_iess,
                'aporte_iess' => 0,
                'total_ingresos' => 0,
                'total_descuentos' => 0,
                'sueldo_recibir' => 0
            ];
        }

        echo json_encode($response);
    }
}
