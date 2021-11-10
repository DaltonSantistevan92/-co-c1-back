<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/usuarioModel.php';
require_once 'models/clienteModel.php';
require_once 'models/estadoModel.php';
require_once 'models/progresoModel.php';


use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{

    protected $table = "orden";
    protected $fillable = ['usuario_id','cliente_id','vehiculo_id','mecanico_id','fecha','total','estado_orden_id','estado','pagado','codigo'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

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

     //Muchos a uno --- uno a muchos(Inverso)
    public function mecanico()
    {
        return $this->belongsTo(Mecanico::class);
    }

     //Muchos a uno --- uno a muchos(Inverso)
    public function estado_orden()
    {
        return $this->belongsTo(Estado::class);
    }

     //uno a muchos
     public function orden_servicio()
     {
         return $this->hasMany(OrdenServicio::class,'orden_id','id');
     }

     //uno a muchos
    public function progreso()
    {
        return $this->hasMany(Progreso::class);
    }
}