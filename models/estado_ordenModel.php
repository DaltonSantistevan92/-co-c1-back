<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenModel.php';



use Illuminate\Database\Eloquent\Model;

class Estado_Orden extends Model
{

    protected $table = "estado_orden";


    public function orden()
    {
        return $this->hasMany(Orden::class,'estado_orden_id');
    }

    
}
