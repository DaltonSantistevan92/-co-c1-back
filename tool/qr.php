<?php
require_once 'libs/phpqrcode/qrlib.php';

class Qr {
    private $nombre;

    public function __construct($nombre)
    {
        $this->nombre=$nombre;
    }

    public function generar($codigo, $path){

        if($path == '/'){
            QRcode::png($codigo,$this->nombre,QR_ECLEVEL_L,10,2);
        }else{
            QRcode::png($codigo,$path.'/'.$this->nombre,QR_ECLEVEL_L,10,2);
        }
    }
}