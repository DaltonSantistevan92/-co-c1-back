<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/personaModel.php';
require_once 'models/clienteVehiculoModel.php';


use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $table = "clientes";
    protected $fillable = ['persona_id', 'estado'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function cliente_vehiculo()
    {
        return $this->hasMany(Cliente_Vehiculo::class);
    }
    
}
