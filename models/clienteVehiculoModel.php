<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/clienteModel.php';
require_once 'models/vehiculoModel.php';


use Illuminate\Database\Eloquent\Model;

class Cliente_Vehiculo extends Model
{

    protected $table = "clientes_vehiculos";
    protected $fillable = ['cliente_id','vehiculo_id','estado'];
    public $timestamps = false;


    //Muchos a uno --- uno a muchos(Inverso)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    //Muchos a uno --- uno a muchos(Inverso)
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}
