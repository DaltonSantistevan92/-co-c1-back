<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenModel.php';



use Illuminate\Database\Eloquent\Model;

class Comprobantes extends Model
{

    protected $table = "comprobantes";
    protected $fillable = ['orden_id','total','subtotal','iva','fecha','estado'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    
}
