<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'app/error.php';
require_once 'app/helper.php';
require_once 'models/usuarioModel.php';
require_once 'models/personaModel.php';
require_once 'models/mecanicoModel.php';
require_once 'controllers/personaController.php';
require_once 'controllers/rolController.php';
require_once 'tool/qr.php';
require_once 'libs/phpqrcode/qrlib.php';


class UsuarioController
{
    private $cors;
    private $personaController;
    private $rolCtr;


    public function __construct()
    {
        $this->cors = new Cors();
        $this->db = new Conexion();
        $this->personaController = new PersonaController();
        $this->rolCtr = new Rol();

    } 

    public function login2(Request $request)
    {
        $data = $request->input('login');

        $entrada = $data->entrada;
        $clave = $data->clave;
        $encriptar = hash('sha256', $clave);

        $this->cors->corsJson();
        $response = [];

        if ((!isset($entrada) || $entrada == "") || (!isset($clave) || $clave == "")) {
            $response = [
                'estatus' => false,
                'mensaje' => 'Falta datos',
            ];
        } else {
            $usuario = Usuario::where('usuario',$entrada)->get()->first();
            $persona = Persona::where('correo',$entrada)->get()->first();

            if($usuario){//x usuario
                if($this->checkValidator($encriptar,$usuario->clave)){
                    $usuario->persona;
                    $rol = $usuario->rol;

                    $per = Persona::find($usuario->persona_id);

                    $usuario['persona'] = $per;
                    $nombre = $per->nombres .' '.$per->apellidos; 
                    
                    $response = [
                        'status' => true,
                        'mensaje' => 'Acceso al sistema',
                        'rol' => $rol,
                        'persona' => $nombre,
                        'usuario' => $usuario,
                    ];
                }else{
                    $response =[
                        'status' => false,
                        'mensaje' => 'credenciales incorrecta'
                    ];
                }
            }else if($persona){//x correo
                $_user = Usuario::where('persona_id',$persona->id)->get()->first();
                $_user['persona']=$persona;

                if($this->checkValidator($encriptar,$_user->clave)){
                    $rol = $_user->rol;
                    $per = Persona::find($_user->persona_id);
                    $nombre = $per->nombres .' '.$per->apellidos; 

                    $response = [
                        'status' => true,
                        'mensaje' => 'Acceso al sistema',
                        'rol' => $rol,
                        'persona' => $nombre,
                        'usuario' => $_user,
                    ];
                }else{
                    $response =[
                        'status' => false,
                        'mensaje' => 'credenciales incorrecta'
                    ];
                }
            }else{
                $response =[
                    'status' => false,
                    'mensaje' => 'credenciales incorrecta'
                ];

            }        
        }
        echo json_encode($response);
    }

    public function buscar($params)
    {
        $this->cors->corsJson();
        $id = intval($params['id']);
        $usuario = Usuario::find($id);
        $response = [];

        if ($usuario) {
            $response = [
                'status' => true,
                'usuario' => $usuario,
                'persona' => $usuario->persona,
                'rol' => $usuario->rol,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se encuentra el usuario',
                'usuario' => null,
                'persona' => null,
                'rol' => null
            ];
            
        }
        echo json_encode($response);
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();
        $user = $request->input('usuario');
        $response = [];

        if (!isset($user) || $user == null) {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'usuario' => null,
            ];
        } else {
            $resPersona = $this->personaController->guardarPersona($request);

            $id_pers = $resPersona['persona']->id;

        
            $qrCodeArray = $this->generarDatos($id_pers); 
            $path = $this->generarQr($qrCodeArray[0],$qrCodeArray[1],$id_pers);

            $clave = $user->clave;
            $encriptar = hash('sha256', $clave);
            $user->rol_id = intval($user->rol_id);

            $usuario = new Usuario;
            $usuario->persona_id = $id_pers;
            $usuario->rol_id = $user->rol_id;
            $usuario->usuario = $user->usuario;
            $usuario->img = $user->img;
            $usuario->clave = $encriptar;
            $usuario->code_qr = $qrCodeArray[1];
            $usuario->conf_clave = $encriptar;
            $usuario->estado = 'A';

            //buscar en usuarios el id_persona si existe y validar
            $exis_user = Usuario::where('persona_id', $id_pers)->get()->first();

            if ($exis_user) {
                $response = [
                    'status' => false,
                    'mensaje' => 'El usuario ya se encuentra registrado',
                    'usuario' => null,
                ];
            } else {
                if ($usuario->save()) {
                    //verificar si ahi un rol mecanico
                    if($usuario->rol_id == 2){
                        //Crear un mecanico y guardar
                        $mecanico = new Mecanico;
                        $mecanico->persona_id = $id_pers;
                        $mecanico->status= 'D';
                        $mecanico->estado= 'A';
                        $mecanico->save();


                    }else if($usuario->rol_id == 4){ //verifica si ahi rol cliente
                        //Crear un cliente y guardar
                        $cliente = new Cliente;
                        $cliente->persona_id = $id_pers;
                        $cliente->estado = 'A';
                        $cliente->save();
                    }
                    $response = [
                        'status' => true,
                        'mensaje' => 'Se ha guardado el usuario',
                        'usuario' => $usuario,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar el usuario',
                        'usuario' => null,
                    ];
                }
            }

        }

        echo json_encode($response);
    }

    private function generarDatos($persona){

        $pers = Persona::find($persona);        
        $cedula =  $pers->cedula;
        $nombrePersonales = $pers->nombres . '-' . $pers->apellidos;
        
        $nombre = $cedula.'-'.$nombrePersonales.'.png';
        $auxCodigo = str_replace(' ','',$nombre);
        $codigo = md5(sha1($auxCodigo));
        
        $array = [$nombre,$codigo]; 
        return $array;
        //$qr =  QRcode::png($cedula,$nombrePersonales,QR_ECLEVEL_L,10,2);
    }
  
    private function generarQr($nombre,$codigo,$persona){
        $persona = Persona::find($persona);
        $nombreCompleto = $persona->nombres . ' ' .$persona->apellidos;
        $privilegio = 777;
        $path = 'resources/qrUsuario/'.$nombreCompleto;

        if(!file_exists($path)){ //sino existe la ruta crear un directorio 
            mkdir($path,$privilegio,true);   
        }

        if(file_exists($path.'/'.$nombre)){
            unlink($path.'/'.$nombre);
        }

        $qr = new Qr($nombre);
        $qr->generar($codigo, $path);
        return $path;
    }




    public function subirFichero($file)
    {
        $this->cors->corsJson();
        $img = $file['fichero'];
        $path = 'resources/usuarios/';

        $response = Helper::save_file($img, $path);
        echo json_encode($response);
    }

   /*  public function login(Request $request)
    {
        $data = $request->input('login');

        $entrada = $data->entrada;
        $clave = $data->clave;
        $encriptar = hash('sha256', $clave);

        $this->cors->corsJson();
        $response = [];

        if ((!isset($entrada) || $entrada == "") || (!isset($clave) || $clave == "")) {
            $response = [
                'estatus' => false,
                'mensaje' => 'Falta datos',
            ];
        } else {
            $usuario = Usuario::where('usuario', $entrada)->get()->first();
            $persona = Persona::where('correo', $entrada)->get()->first();

            if ($usuario || $persona) {
                $us = $usuario;
                
                if ($persona) {
                    $us = $persona->usuario[0];
                }
                
                //echo json_encode($us); die();

                //Segun con la verificacion de credenciales
                if ($encriptar == $us->clave) {
                    //$personas = Persona::find($us->persona_id);
                    $personas = Persona::find($us->persona->id);


                   // echo json_encode($personas); die();

                    $per = $personas->nombres . " " . $personas->apellidos;
                    $rol = $us->rol->cargo;

                    $per = $us->persona->nombres . " " . $us->persona->apellidos;
                    $rol = $us->rol->cargo;

                    $us->persona;

                    $response = [
                        'status' => true,
                        'mensaje' => 'Acceso al sistema',
                        'rol' => $rol,
                        'persona' => $per,
                        'usuario' => $us,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'La contraseña es incorrecta',
                    ];
                }
            } else {
                $response = [
                    'estatus' => false,
                    'mensaje' => 'El correo o usuario no existe',
                ];
            }
        }

        echo json_encode($response);
    } */

    

    private function checkValidator($credencia1, $credencial2){
        if($credencia1 == $credencial2){
            return true;
        }else{
            return false;
        }

    }

    public function dataTable()
    {
        $this->cors->corsJson();

        //$usuarios = Usuario::where('estado', 'A')->orderBy('usuario')->get();
        $usuarios = Usuario::where('rol_id','<>',4)->orderBy('usuario')->get();


        $data = [];    $i = 1;

        foreach ($usuarios as $u) {
            $url = BASE . 'resources/usuarios/' . $u->img;
            //$estado = $u->estado == 'A' '<span class="badge bg-success">Activado</span>'?
            $icono = $u->estado == 'I' ? '<i class="fa fa-check-circle fa-lg"></i>' : '<i class="fa fa-trash fa-lg"></i>';
            $clase = $u->estado == 'I' ? 'btn-success' : 'btn-danger';
            $other = $u->estado == 'A' ? 0 : 1;

            $botones = '<div class="btn-group">
                            <button class="btn btn-warning" onclick="editar_usuario(' . $u->id . ')">
                                <i class="fa fa-pencil-square fa-lg"></i>
                            </button>
                            <button class="btn ' . $clase . '" onclick="eliminar(' . $u->id . ',' . $other . ')">
                                ' . $icono . '
                            </button>
                        </div>';

            $data[] = [
                0 => $i,
                1 => '<div class="box-img-usuario"><img src=' . "$url" . '></div>',
                2 => $u->persona->nombres,
                3 => $u->persona->apellidos,
                4 => $u->usuario,
                5 => $u->rol->cargo,
                6 => $botones,
            ];
            $i++;
        }

        $result = [
            'sEcho' => 1,
            'iTotalRecords' => count($data),
            'iTotalDisplayRecords' => count($data),
            'aaData' => $data,
        ];
        echo json_encode($result);
    }

    public function contar(){
        $this->cors->corsJson();
        $usuarios = Usuario::where('estado','A')->get();
        $response = [];
        if($usuarios){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Usuario',
                'Modelo' => 'Usuario',
                'cantidad' => $usuarios->count()
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Usuario',
                'Modelo' => 'Usuario',
                'cantidad' => 0
            ];
        }
        echo json_encode($response);
    }

    //post
    public function editar(Request $request){
        
        $this->cors->corsJson();   
        $usuRequest = $request->input('usuario');

        $id = intval($usuRequest->id);
        $persona_id = intval($usuRequest->persona_id);
        $rol_id = intval($usuRequest->rol_id);
        $usuario = ucfirst($usuRequest->usuario);

        $response = [];       
        $usu = Usuario::find($id);
        if($usuRequest){
            if($usu){
                $usu->persona_id = $persona_id;
                $usu->rol_id = $rol_id;
                $usu->usuario = $usuario;

                $persona = Persona::find($usu->persona_id);
                $persona->nombres = ucfirst($usuRequest->nombres);
                $persona->apellidos = ucfirst($usuRequest->apellidos);
                $persona->save();
                $usu->save();  

                $response = [
                    'status' => true,
                    'mensaje' => 'El Usuario se ha actualizado',
                    'data' => $usu,
                ];
            }else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se puede actualizar el usuario',
                ];
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos...!!'
            ];
        }
        echo json_encode($response);
    }

    public function eliminar(Request $request){
        $this->cors->corsJson();
        $usuarioRequest = $request->input('usuario');
        $id = intval($usuarioRequest->id);

        $usuario = Usuario::find($id);
        $response = [];

        if($usuario){
            $usuario->estado = 'I';
            $usuario->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado el usuario', 
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No se ha podido eliminar el usuario', 
            ];
        }
        echo json_encode($response);
    }
    
    public function listUsers(){

        $users = Usuario::where('rol_id', '<>', 4)->where('estado', 'A')->get();
        $resp = [];

        if($users->count() > 0){
            $resp = $users;
            foreach($resp as $item){
                $item->persona;
                $item->rol;
            }
        } 
        
        echo json_encode($resp);
    }

}