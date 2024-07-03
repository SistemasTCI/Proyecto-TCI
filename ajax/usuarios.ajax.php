
<?php
require_once  "../controllers/Usuarios.Controlador.php";
require_once  "../models/Usuarios.Model.php";
//require_once "controllers/template.php";



class AjaxUsuarios{
     /*=============================================
    EDITAR USUARIO
    =============================================*/
    public $idUsuario;
    public function ajaxEditarUsuario(){
        $item="IdUsuario";
        $valor= $this-> idUsuario;
        $respuesta=ControladorUsuarios::ctrMostrarUsuarios($item,$valor);
        echo json_encode($respuesta);
    }

    /*=============================================
    ACTIVAR/DESACTIVAR USUARIOS
    =============================================*/
    public $activarUsuario;
    public $activarId;
    public function ajaxActivarUsuario(){
        $tabla="cbris_sys_tb_usuarios";
        $item1="Estado";
        $item2="IdUsuario";
        $valor1=$this->activarUsuario;
        $valor2=$this->activarId;
        $respuesta=ModeloUsuarios::mdlActualizarUsuario($tabla,$item1,$valor1,$item2,$valor2);
    }

    /*=============================================
    VALIDAR USUARIO DUPLICADO
    =============================================*/
    public $validarUsuario;
    //echo "<scrit>console.log('vALIDACION duPLICADO '".$validarUsuario."')</script>";
    public function ajaxValidarUsuario(){
        $item="Usuario";
        $valor=$this -> validarUsuario;
        $respuesta=ControladorUsuarios::ctrMostrarUsuarios($item,$valor);
        echo json_encode($respuesta);
    }

    /*=============================================
    INICILIZAR LA  LISTA DE ROLES EN MODALES
    =============================================*/
    public function ajaxInicializar(){
        $respuesta=ControladorUsuarios::ctrInicializar();
        echo json_encode($respuesta);
    }
}
if(isset($_POST["idUsuario"])){

    $editar=new AjaxUsuarios();
    $editar->idUsuario=$_POST["idUsuario"];
    $editar->ajaxEditarUsuario();
}
if(isset($_POST['activarUsuario'])){
    
    /*echo '<script>
    alert("Entro al Ajax");
    </script>';*/
    $activarUsuario=new AjaxUsuarios();
    $activarUsuario->activarUsuario=$_POST['activarUsuario'];
    $activarUsuario->activarId=$_POST['activarId'];
    $activarUsuario->ajaxActivarUsuario();
}
if(isset($_POST['validarUsuario'])){
    $validarUsuario=new AjaxUsuarios();
    $validarUsuario->validarUsuario=$_POST['validarUsuario'];
    $validarUsuario->ajaxValidarUsuario();
}
if(isset($_POST["Inicializar"])){
    $Inicializar=new AjaxUsuarios();
    $Inicializar->ajaxInicializar();
}
?>