<?php

class Helper
{

    public static function save_file($file, $path)
    {
        $response = [];
        $imagen = $file;
        $target_path = $path;
        $target_path = $target_path . basename($imagen['name']);

        $enlace_actual = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $enlace_actual = str_replace('index.php', '', $enlace_actual);

        $response = [];

        if (move_uploaded_file($imagen['tmp_name'], $target_path)) {
            $response = [
                'status' => true,
                'mensaje' => 'Fichero subido',
                'imagen' => $imagen['name'],
                'direccion' => $enlace_actual . '/' . $target_path,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se pudo guardar el fichero',
                'imagen' => null,
                'direccion' => null,
            ];
        }
        return ($response);
    }

    public function generate_key($limit){
        $key = '';

        $aux = sha1(md5(time()));
        $key = substr($aux, 0, $limit);

        return $key;
    }

    public static function mes($pos){
        $pos = intval($pos) -1;

        $array = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return $array[$pos];
    }


    public static function invertir_array($array){

        $copia = [];

        for($i = 0; $i < count($array); $i++){
            $copia[] = $array[count($array) - $i  - 1];
        }

        return $copia;
    }

    public static function MESES(){
        $m = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ];
        return $m;
    }

    public static function hace_tiempo($fecha,$hora){
        $start_date = new DateTime($fecha." ".$hora);
        $since_start = $start_date->diff(new DateTime(date("Y-m-d")." ".date("H:i:s")));
        
        if($since_start->y==0){
            if($since_start->m==0){
                if($since_start->d==0){
                   if($since_start->h==0){
                       if($since_start->i==0){
                          if($since_start->s==0){
                             return $since_start->s.' segundos';
                          }else{
                              if($since_start->s==1){
                                return $since_start->s.' segundo'; 
                              }else{
                                return $since_start->s.' segundos'; 
                              }
                          }
                       }else{
                          if($since_start->i==1){
                              return $since_start->i.' minuto'; 
                          }else{
                            return $since_start->i.' minutos';
                          }
                       }
                   }else{
                      if($since_start->h==1){
                        return $since_start->h.' hora';
                      }else{
                        return $since_start->h.' horas';
                      }
                   }
                }else{
                    if($since_start->d==1){
                        return $since_start->d.' día';
                    }else{
                        return $since_start->d.' días';
                    }
                }
            }else{
                if($since_start->m==1){
                   return $since_start->m.' mes';
                }else{
                    return $since_start->m.' meses';
                }
            }
        }else{
            if($since_start->y==1){
                return $since_start->y.' año';
            }else{
                return $since_start->y.' años';
            }
        }
    }

    public static function move_to($origen,$destino){
        copy($origen,$destino);
        unlink($origen);
    }

    public static function getDia($id){
        $array = ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'];
        return $array[$id];
    }
   
    public static function getDay(){
        return date('d');
    }

    public static function getSemana($id){
        $dia_base = Helper::getDia($id);
        $dia_hoy = Helper::getDia(date('w'));
        $fecha_actual = date("Y-m-d");
        $auxDate = '';

        if($dia_hoy === $dia_base){
            return date('d');
        }else{

            switch($dia_hoy){
                case 'Domingo':
                //sumo 1 día
                $auxDate = date("Y-m-d",strtotime($fecha_actual."+ 1 days")); 
                break;

                case 'Martes':
                    //sumo 6 día
                $auxDate = date("Y-m-d",strtotime($fecha_actual."+ 6 days")); 
                break;
                
                case 'Miercoles':
                        //sumo 5 día
                $auxDate = date("Y-m-d",strtotime($fecha_actual."+ 5 days"));
                break;
                
                case 'Jueves':
                        //sumo 4 día
                $auxDate = date("Y-m-d",strtotime($fecha_actual."+ 4 days"));
                break;
                
                case 'Viernes':
                        //sumo 3 día
                $auxDate = date("Y-m-d",strtotime($fecha_actual."+ 3days"));
                break;

                case 'Sabado':
                        //sumo 2 día
                $auxDate = date("Y-m-d",strtotime($fecha_actual."+ 2 days"));
                break;
            }

            $auxDate = explode('-',$auxDate);
            return intval($auxDate[2]);
        }
    }
}
