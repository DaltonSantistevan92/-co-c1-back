<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/permisoModel.php';

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $table = "menus";

    //uno a muchos
    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }
}
