<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/usuarioModel.php';



use Illuminate\Database\Eloquent\Model;

class Detalles_Pagos extends Model
{

    protected $table = "detalles_pagos";
    protected $fillable = ['usuario_id','cant_hora','precio_hora','total'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

   
    
}
