<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenModel.php';

use Illuminate\Database\Eloquent\Model;

class Progreso extends Model
{
    protected $table = "progresos";
    protected $fillable = ['orden_id', 'detalle','progreso','total','faltante','estado'];
    
    //Muchos
    public function orden(){
        return $this->belongsTo(Orden::class);
    } 
}