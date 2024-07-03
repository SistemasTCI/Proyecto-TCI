<?php
require_once "../controllers/funciones/TCI.Funciones.php";
require_once "../models/TCI.Model.php";

if(isset($_POST["Notificaciones_Manifest"])){
    $Notificaciones_Manifest=ControladorTCI::ctrNotificaciones_Manifest();
    echo json_encode($Notificaciones_Manifest);     
}

?>