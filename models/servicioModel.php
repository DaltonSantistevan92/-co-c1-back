<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenservicioModel.php';



use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = "servicios";
    protected $fillable = ['detalle','precio','estado'];
    public $timestamps = false;


    public function ordenservicio()
    {
        return $this->hasMany(OrdenServicio::class);
    }



}
