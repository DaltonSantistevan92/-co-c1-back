<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/rolModel.php';

use Illuminate\Database\Eloquent\Model;

class Salario extends Model
{

    protected $table = "salarios";
    protected $fillable = ['rol_id','salarios_quincenal','salario_mensual','salario_diario','salario_hora','porcentaje_comision','estado']; 
    public $timestamps = false;

    
    //muchos a uno
    public function rol()
    {
        return $this->belongsTo(Rol::class); 
    }
}
