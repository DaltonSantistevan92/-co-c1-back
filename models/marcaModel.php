<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/vehiculoModel.php';

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{

    protected $table = "marcas";
    protected $fillable = ['nombre','estado']; 
    public $timestamps = false;

    
    //uno a muchos
    public function vehiculo()
    {
        return $this->hasMany(Vehiculo::class); 
    }
}
