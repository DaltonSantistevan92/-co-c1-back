<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/personaModel.php';
require_once 'models/rolModel.php';
require_once 'models/entradaModel.php';
require_once 'models/salidaModel.php';
require_once 'models/rol_pagoModel.php';



use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{

    protected $table = "usuarios";
    protected $hidden = ['conf_clave','clave'];
    protected $fillable = ['persona_id','rol_id','usuario','img','clave','code_qr','conf_clave','estado'];

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

    public function entrada(){
        return $this->hasMany(Entrada::class);
    }

    public function salida(){
        return $this->hasMany(Salida::class);
    }

    public function rol_pago(){
        return $this->hasMany(Rol_Pago::class);
    }
} 
 