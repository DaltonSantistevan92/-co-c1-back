<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenModel.php';
require_once 'models/servicioModel.php';


use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model{

    protected $table = "orden_servicio";
    protected $fillable = ['orden_id','servicio_id','estado'];
    public $timestamps = false;

    //Muchos a uno --- uno a muchos(Inverso)
    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }



}