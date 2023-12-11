<?php

require_once '../controllers/firewall.controllers.php';
require_once '../models/firewall.models.php';



class AjaxUsuarios{


    // EDITAR USUARIO

    public $idUsuario;

    public function ajaxEditarUsuario(){

        $item = "id";
        $valor = $this->idUsuario;
        
        $res = ControllersUsuarios::CtrMostrarUsuarios($item, $valor);

        echo json_encode($res);

    }

    /*********************************
    * REVISAR USUARIO
    ********************************/

    public $validarUsuario;

    public function ajaxValidarUsuario(){

        $item = "usuario";
        $valor = $this->validarUsuario;
        
        $res = ControllersUsuarios::CtrMostrarUsuarios($item, $valor);

        echo json_encode($res);

    }

}

// EDITAR USUARIO

if (isset($_POST["idUsuario"])){

    $editar = new AjaxUsuarios();
    $editar -> idUsuario = $_POST["idUsuario"];
    $editar -> ajaxEditarUsuario();
}


/*********************************
 REVISAR USUARIO
********************************/

if(isset($_POST["validarUsuario"])){

    $valUsuario = new AjaxUsuarios();
    $valUsuario -> validarUsuario = $_POST["validarUsuario"];
    $valUsuario -> ajaxValidarUsuario();


}
