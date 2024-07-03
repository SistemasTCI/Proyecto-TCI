<?php
require_once "../controllers/inicio.Controlador.php";
require_once "../models/opEdi.Model.php";
require_once "../controllers/funciones/TCI.Funciones.php";
include "../tools/PHPExcel/PHPExcel.php";
include "../tools/PHPExcel/PHPExcel/Writer/Excel2007.php";

if(isset($_POST["Inicializar"])){
    $SlClientes=ControladoropEdi::ctrSlClientes();
    echo json_encode($SlClientes);
}
//lectura de xml
if(isset($_POST["inicioUpFiles"])){
    //var_dump($_POST['IDEDCLI'],$_POST["ruta"],$_POST["TC"]);
    $respuesta=Controladorinicio::ctrinicioUpFiles($_POST['IDEDCLI'],$_POST["ruta"],$_POST["TC"]);
    echo json_encode($respuesta);
}
///este se queda
if(isset($_POST["opEdiGenerarEdi"])){
    //Se convierte el Json en array, se usa  TRUE para  que se convierta en  array, de lo contrario se convierte en  objeto.
    $datos_edi= json_decode($_POST['datos_edi'],true);
    $respuesta=ControladoropEdi::ctrmldGenerarEdi_btnProcEdi($datos_edi);
    echo json_encode($respuesta);
}

//Se queda para el proyecto de XML 20102023
if(isset($_POST["CountUpFiles"])){
    $respuesta=Controladorinicio::ctrCountUpFiles($_POST['ruta']);
    echo json_encode($respuesta);
}
?>