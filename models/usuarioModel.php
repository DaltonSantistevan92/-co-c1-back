<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/personaModel.php';
require_once 'models/rolModel.php';

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{

    protected $table = "usuarios";
    protected $hidden = ['conf_clave'];
    protected $fillable = ['persona_id', 'rol_id', 'usuario', 'img', 'clave', 'conf_clave', 'estado'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    //Muchos a uno --- uno a muchos(Inverso)
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
} 
 