<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/usuarioModel.php';

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{

    protected $table = "salidas";
    protected $fillable = ['usuario_id','clave','hora','fecha']; 
    public $timestamps = false;

    
    //muchos a uno
    public function usuario()
    {
        return $this->belongsTo(Usuario::class); 
    }
}
