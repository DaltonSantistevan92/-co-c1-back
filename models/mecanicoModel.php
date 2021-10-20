<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/personaModel.php';

use Illuminate\Database\Eloquent\Model;

class Mecanico extends Model
{
    protected $table = "mecanicos";
    protected $fillable = ['persona_id','estado'];
    public $timestamps = false;

    //Muchos a uno --- uno a muchos(Inverso)
    public function persona(){
        return $this->belongsTo(Persona::class);
    }

}
