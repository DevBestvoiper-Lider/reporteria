<?php

class ControllersFirewall{

################ CARGAR IP ################

    static public function ctrAgregarIP(){
        if (isset($_POST["Nuevaip"])) {

                    ############ VALIDAR IMAGEN ############
                    $tabla = "e_firewall";
                    
                    $datos = array("ip" => $_POST["Nuevaip"]); 

                    $res = ModeloFirewall::mdlIngresarIP($tabla, $datos);


                    print_r ($datos);

                    if($res == "ok"){
                        echo '<script>
                    
                        swal.fire({
                            icon: "success",
                            title: "IP agregada con exito!",
                            showConfirmButton: true,
                            confirmButtonText: "cerrar",
                            closeOnConfirm: false
                        }).then ((result)=>{
                            if(result.value){
                                window.location = "Anti-hack";
                             }
                        });
                    
                    </script>';
                    }

        }
    }

################ MOSTRAR IPS ################


    static public function ctrMostrarIps($item, $valor){
        
        $tabla = "e_firewall";

        $respuesta = ModeloFirewall::MdlMostrarIps( $tabla, $item, $valor);

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
                                        window.location = "Anti-hack";
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
                                    window.location = "Anti-hack";
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

                                window.location = "Anti-hack";
        
                             }
                        });
                     </script>';
            }

        }

    }

    /********** BORRAR IP **********/

    static public function ctrBorrarIP(){

        if(isset($_GET["idIp"])) {

            $tabla = "e_firewall";
            $datos = $_GET["idIp"];

            $respuesta = ModeloFirewall::mdlBorrarIP($tabla, $datos);

            if ($respuesta == "ok"){
                echo '
                    <script>
                
                        swal.fire({
                            icon: "success",
                            title: "¡IP Eliminada Correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "cerrar",
                            closeOnConfirm: false
                        }).then ((result)=>{
                            if(result.value){
                                window.location = "Anti-hack";
                            }
                        });
                </script>';
            }

        }

    }

}