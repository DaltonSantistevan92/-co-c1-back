<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/marcaModel.php';
require_once 'models/clienteVehiculoModel.php';


use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{

    protected $table = "vehiculos";
    protected $fillable = ['marca_id', 'placa', 'modelo', 'kilometraje', 'disponible', 'estado'];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function cliente_vehiculo()
    {
        return $this->hasMany(Cliente_Vehiculo::class);
    }
}
