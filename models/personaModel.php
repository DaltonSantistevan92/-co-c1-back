<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/usuarioModel.php';
require_once 'models/clienteModel.php';
require_once 'models/mecanicoModel.php';


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
    //uno a muchos
    public function cliente()
    {
        return $this->hasMany(Cliente::class);
    }
    //uno a muchos
    public function mecanico()
    {
        return $this->hasMany(Mecanico::class);
    }


}
  