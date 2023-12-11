<?php

class ControllersUsuarios{

################ INGRESAR USUARIO ################

    static publiC function ctrIngresoUsuario(){

        if(isset($_POST["ingUsuario"])){

            if (preg_match('/^[-a-zA-Z0-9\s]+$/', $_POST["ingUsuario"])){

                $encrypt = crypt ($_POST["ingPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                $tabla = "e_usuariosclientes";
                $item = "usuario";
                $valor = $_POST["ingUsuario"];
    
                $respuesta = ModeloUsuarios::MdlMostrarUsuarios( $tabla, $item, $valor);

                if(@$respuesta["usuario"] == @$_POST["ingUsuario"] && @$respuesta["password"] == $encrypt){

                    $_SESSION["iniciarsession"] = "ok";
                    $_SESSION["id"] = $respuesta["id"];
                    $_SESSION["nombre"] = $respuesta["nombre"];
                    $_SESSION["usuario"] = $respuesta["usuario"];
                    $_SESSION["perfil"] = $respuesta["perfil"];
                    $_SESSION["customeraccountname"] = $respuesta["customeraccountname"];
                    $_SESSION["foto"] = $respuesta["foto"];
                    $_SESSION["estado"] = $respuesta["estado"];

                    /*============ FECHA ULTIMO LOGIN ============*/

                    date_default_timezone_set('America/Bogota');

                    $fecha = date('Y-m-d');
                    $hora = date('H:i:s');

                    $fechaactual = $fecha.' '.$hora;

                    $item1 = "ultimo_login";
                    $valor1 = $fechaactual;

                    $item2 = "id";
                    $valor2 = $respuesta["id"];

                    $ultimoLogin = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);

                    if ($ultimoLogin = "ok"){
                        echo '<script>
                    
                        window.location = "dashboard";

                        </script>';
                    }

                }else {
                    echo '<script>
                    
                    swal.fire ({
                        icon: "error",
                        title: "¡ERROR! contraseña Y/O usuario incorrecto",
                        showConfirmButton: true,
                        confirmButtonText: "cerrar",
                        closeOnConfirm: false
    
                    }).then((result)=>{
                        window.location = "dashboard";
                    
                    });
    
                    </script>';
    
                }

            }

        }

    }

################ CREAR USUARIO ################

    static public function ctrCrearUsuario(){
        if (isset($_POST["nuevoUsuario"])) {

            if (preg_match('/^[-a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[-a-zA-Z0-9]+$/', $_POST["nuevoUsuario"])){

                    ############ VALIDAR IMAGEN ############

                    $encrypt = crypt ($_POST["nuevoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                    $tabla = "e_usuariosclientes";
                    
                    $datos = array("nombre" => $_POST["nuevoNombre"],
                                    "usuario" => $_POST["nuevoUsuario"],
                                    "password" => $encrypt,
                                    "customeraccountid" => $_POST["nuevoAccountID"], 
                                    "customeraccountname" => $_POST["nuevoAccountName"],  
                                    "perfil" => $_POST["nuevoPerfil"],
                                    "foto" => $ruta); 

                    $res = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);

                    if($res == "ok"){
                        echo '<script>
                    
                        swal.fire({
                            icon: "success",
                            title: "¡Usuario creado!",
                            showConfirmButton: true,
                            confirmButtonText: "cerrar",
                            closeOnConfirm: false
                        }).then ((result)=>{
                            if(result.value){
                                window.location = "usuarios";
                             }
                        });
                    
                    </script>';
                    }


                } else {
                    echo '<script>
                    
                        swal.fire({
                            icon: "error",
                            title: "¡El usuario no puede ir vacio o llevar caracteres especiales!",
                            showConfirmButton: true,
                            confirmButtonText: "cerrar",
                            closeOnConfirm: false

                        }).then ((result)=>{

                            if(result.value){

                                window.location = "usuarios";
        
                             }
                        });
                    
                    </script>';
                }

        }
    }

################ MOSTRAR USUARIO ################


    static public function ctrMostrarUsuarios($item, $valor){
        
        $tabla = "e_usuariosclientes";

        $respuesta = ModeloUsuarios::MdlMostrarUsuarios( $tabla, $item, $valor);

        return $respuesta;
    }


################ EDITAR USUARIO ################


    static public function ctrEditarUsuario(){

        if(isset($_POST["editarUsuario"])){

            if (preg_match('/^[-a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])){

                ############ VALIDAR IMAGEN ############
                $tabla = "e_usuariosclientes";

                if ($_POST["editarPassword"] != ""){

                    if (preg_match('/^[-a-zA-Z0-9]+$/', $_POST["editarPassword"])){
                        $encrypt = crypt($_POST["editarPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                    }else {

                        echo '<script>
                    
                                swal.fire({
                                    icon: "error",
                                    title: "¡La contraseña no puede ir vacia o llevar caracteres especiales!",
                                    showConfirmButton: true,
                                    confirmButtonText: "cerrar",
                                    closeOnConfirm: false
                                }).then ((result)=>{

                                    if(result.value){
                                        window.location = "usuarios";
                                    }
                                });
                            
                            </script>';
                    }

                } else {
                    $encrypt =  $_POST["passwordActual"];
                }

                $datos = array("nombre" => $_POST["editarNombre"],
                                "usuario" => $_POST["editarUsuario"], 
                                "password" => $encrypt,
                                "perfil" => $_POST["editarPerfil"],
                                "foto" => $ruta);

                $respuesta = ModeloUsuarios::MdlEditarUsuario($tabla, $datos);

                if ($respuesta == "ok"){
                    echo '
                        <script>
                    
                            swal.fire({
                                icon: "success",
                                title: "¡Usuario editado Correctamente!",
                                showConfirmButton: true,
                                confirmButtonText: "cerrar",
                                closeOnConfirm: false
                            }).then ((result)=>{
                                if(result.value){
                                    window.location = "usuarios";
                                }
                            });
                    </script>';
                }


            } else {
                echo '<script>
                    
                        swal.fire({
                            icon: "error",
                            title: "¡El nombre no puede ir vacio o llevar caracteres especiales!",
                            showConfirmButton: true,
                            confirmButtonText: "cerrar",
                            closeOnConfirm: false

                        }).then ((result)=>{

                            if(result.value){

                                window.location = "usuarios";
        
                             }
                        });
                     </script>';
            }

        }

    }

    /********** BORRAR USUARIO **********/

    static public function ctrBorrarUsuario(){

        if(isset($_GET["idUsuario"])) {

            $tabla = "e_usuariosclientes";
            $datos = $_GET["idUsuario"];

            if($_GET["fotoUsuario"] != "") {
                unlink($_GET["fotoUsuario"]);
                rmdir('views/img/users/'.$_GET["usuario"]);
            }

            $respuesta = ModeloUsuarios::mdlBorrarUsuario($tabla, $datos);

            if ($respuesta == "ok"){
                echo '
                    <script>
                
                        swal.fire({
                            icon: "success",
                            title: "¡Usuario Eliminado Correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "cerrar",
                            closeOnConfirm: false
                        }).then ((result)=>{
                            if(result.value){
                                window.location = "usuarios";
                            }
                        });
                </script>';
            }

        }

    }


    
################ ACTUALIZAR CONTRASEÑA ################


static public function ctrActualizarPassword(){

    if(isset($_POST["CambiarPassword"])){

            ############ VALIDAR IMAGEN ############
            $tabla = "e_usuariosclientes";

            if ($_POST["CambiarPassword"] == $_POST["contraseña1"]){

                if (isset($_POST["CambiarPassword"])){
                    $encrypt = crypt($_POST["CambiarPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                    $datos = array("usuario" => $_SESSION["usuario"], 
                            "password" => $encrypt);

                    $respuesta = ModeloUsuarios::mdlActualizarPassword($tabla, $datos);

                    if ($respuesta == "ok"){
                        echo '
                            <script>
                        
                                swal.fire({
                                    icon: "success",
                                    title: "¡Contraseña cambiada Correctamente!",
                                    showConfirmButton: true,
                                    confirmButtonText: "cerrar",
                                    closeOnConfirm: false
                                }).then ((result)=>{
                                    if(result.value){
                                        window.location = "salir";
                                    }
                                });
                        </script>';
                        unset($_SESSION["estado"]);
                    }



                }else {

                    echo '<script>
                
                            swal.fire({
                                icon: "error",
                                title: "¡La contraseña no puede ir vacia!",
                                showConfirmButton: true,
                                confirmButtonText: "cerrar",
                                closeOnConfirm: false
                            }).then ((result)=>{

                                if(result.value){
                                    window.location = "dashboard";
                                }
                            });
                        
                        </script>';
                }

            } else {
                    echo '<script>
                        
                            swal.fire({
                                icon: "error",
                                title: "¡Las contraseñas no coinciden!",
                                showConfirmButton: true,
                                confirmButtonText: "cerrar",
                                closeOnConfirm: false
        
                            }).then ((result)=>{
        
                                if(result.value){
        
                                    window.location = "dashboard";
            
                                 }
                            });
                         </script>';
                }

         } 

    }
}

