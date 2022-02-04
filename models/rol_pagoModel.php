<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/usuarioModel.php';

use Illuminate\Database\Eloquent\Model;

class Rol_Pago extends Model
{

    protected $table = "rol_pagos";
    protected $fillable = ['usuario_id','horas_normales','horas_extras', 'total_normal', 'total_extra','porcentaje_iess','aporte_iess','total_ingresos','total_descuentos','sueldo_recibir']; 
    public $timestamps = false;

    
    //muchos a uno
    public function usuario()
    {
        return $this->belongsTo(Usuario::class); 
    }
}
