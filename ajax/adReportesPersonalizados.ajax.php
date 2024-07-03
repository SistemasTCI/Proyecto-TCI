<?php
require_once "../controllers/adReportesPersonalizados.Controlador.php";
require_once "../models/adReportesPersonalizados.Model.php";
require_once "../tools/PHPExcel/PHPExcel.php";
//require_once "../tools/PHPMailer/phpmailer.php";
require_once "../tools/PHPMailerV2/class.phpmailer.php";
require_once "../tools/PHPMailerV2/class.smtp.php";


//Funciones del Sistema
require_once "../controllers/funciones/TCI.Funciones.php";

if(isset($_POST["FacturaMahle"])){
    //var_dump($_POST["FacturaMahle"],$_POST['Trafico']);
    $GenerarReporte=ControladoradReportesPersonalizados::ctrReporteMahle($_POST['Trafico']);
    /*if(isset($GenerarReporte->Excel)){
        $GenerarReporte->Excel="";
    }*/
    echo json_encode($GenerarReporte);
}
if(isset($_POST["XFactura"])){
    $GenerarReporte=ControladoradReportesPersonalizados::ctrReporteMahlexFactura($_POST['Factura']);
    echo json_encode($GenerarReporte);
}
/*if(isset($_POST["FacturaMahle"])){
    $respuesta=ControladoropEdi::ctropEdiFillEDI($_POST["krelfc"],$_POST["caja"],$_POST['IMP_ABI_KEY'],$_POST['CONS_ABI_KEY'],$_POST['IDEDIMPCOS'],$_POST['TipoCat'],$_POST['VistaPanel']);
    echo json_encode($respuesta); 03944/24
}*/
if(isset($_POST["CartaPorte"])){
    $GenerarReporte=ControladoradReportesPersonalizados::ctrReporteCartaPorte($_POST['OrdenCarga']);
    echo json_encode($GenerarReporte);
}


if(isset($_POST["FacturaMahleC"])){
    $GenerarReporte=ControladoradReportesPersonalizados::ctrReporteMahleC($_POST['Trafico']);
    echo json_encode($GenerarReporte);
}