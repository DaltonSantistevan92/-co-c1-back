<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/personaModel.php';

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{

    protected $table = "roles";
}

