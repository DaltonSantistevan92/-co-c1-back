<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/menuModel.php';

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{

    protected $table = "permisos";

    //Muchos a uno --- uno a muchos(Inverso)
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

}
 
