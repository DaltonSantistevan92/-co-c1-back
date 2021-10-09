<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/usuarioModel.php';

use Illuminate\Database\Eloquent\Model;


class Persona extends Model
{

    protected $table = "personas";
    protected $fillable = ['cedula', 'nombres', 'apellidos', 'telefono', 'correo', 'direccion', 'estado'];

    //uno a muchos
    public function usuario()
    {
        return $this->hasMany(Usuario::class);
    }

}
  