<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/ordenModel.php';


use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{

    protected $table = "estado_orden";

    
}
