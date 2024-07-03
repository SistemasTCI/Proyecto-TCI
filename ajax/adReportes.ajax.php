<?php
require_once "../controllers/adReportes.Controlador.php";
require_once "../models/adReportes.Model.php";
require_once "../tools/PHPExcel/PHPExcel.php";
//require_once "../tools/PHPMailer/phpmailer.php";
require_once "../tools/PHPMailerV2/class.phpmailer.php";
require_once "../tools/PHPMailerV2/class.smtp.php";


//Funciones del Sistema
require_once "../controllers/funciones/TCI.Funciones.php";

if(isset($_POST["Inicializar"])){
    $inicializar=new AjaxadReportes(); 
    $inicializar->Modulo=$_POST['Modulo'];
    $inicializar->ajaxInicializar();    
}
if(isset($_POST["FiltrosDescarga"])){
    $FiltrosDescarga=new  AjaxadReportes();
    $FiltrosDescarga->Reporte=$_POST['Reporte'];
    $FiltrosDescarga->ajaxFiltrosDescarga();    
}
if(isset($_POST["ListadoClientes"])){
    $ListadoClientes=new  AjaxadReportes();
    $ListadoClientes->ajaxListadoClientes();    
}
if(isset($_POST["GenerarReporte"])){
    $GenerarReporte=new  AjaxadReportes();
    $GenerarReporte->Datos=json_decode($_POST['Datos'],true);
    $GenerarReporte->Stp=$_POST['Stp'];
    $GenerarReporte->ReporteNombre=$_POST['ReporteNombre'];
    $GenerarReporte->ajaxGenerarReporte();    
}
if(isset($_POST["GenerarReporteSinFiltros"])){
    $GenerarReporteSinFiltros=new  AjaxadReportes();
    //$GenerarReporteSinFiltros->Datos=new stdClass();
    $GenerarReporteSinFiltros->Stp=$_POST['Stp'];
    $GenerarReporteSinFiltros->ReporteNombre=$_POST['ReporteNombre'];
    $GenerarReporteSinFiltros->ajaxGenerarReporteSinFiltros();      
}
if(isset($_POST["ListadoImportadores"])){
    $ListadoImportadores=new  AjaxadReportes();
    $ListadoImportadores->ajaxListadoImportadores();    
}
if(isset($_POST["SaveMailInformation"])){
    //Se convierte el Json en array, se usa  TRUE para  que se convierta en  array, de lo contrario se convierte en  objeto.
    $datos= json_decode($_POST['datos'],true);
    $SaveMailInformation=new AjaxadReportes();
    $SaveMailInformation->datos=$datos;
    $SaveMailInformation->SaveInformation=$_POST["SaveMailInformation"];
    $SaveMailInformation->ajaxSaveMailInformation();
}
if(isset($_POST["ListaReportesAutomaticos"])){
    $ListaReportesAutomaticos=new  AjaxadReportes();
    $ListaReportesAutomaticos->IdReporte=$_POST['IdReporte'];
    $ListaReportesAutomaticos->ajaxListaReportesAutomaticos();    
}
if(isset($_POST["ScheduleMailUPData"])){
    $ScheduleMailUPData=new  AjaxadReportes();
    $ScheduleMailUPData->Key_RELM_C=$_POST['KEY_RELM_C'];
    $ScheduleMailUPData->ajaxScheduleMailUPData();    
}
if(isset($_POST["DelSheduleMail"])){
    $DelSheduleMail=new  AjaxadReportes();
    $DelSheduleMail->Key_RELM_C=$_POST['KEY_RELM_C'];
    $DelSheduleMail->ajaxDelSheduleMail();    
}
if(isset($_POST["DelContactMail"])){
    $DelContactMail=new  AjaxadReportes();
    $DelContactMail->IDMIC=$_POST['IDMIC'];
    $DelContactMail->ajaxDelContactMail();    
}
if(isset($_POST["SaveFrecuency"])){
    //Se convierte el Json en array, se usa  TRUE para  que se convierta en  array, de lo contrario se convierte en  objeto.
    $datos= json_decode($_POST['datos'],true);
    $SaveFrecuency=new AjaxadReportes();
    $SaveFrecuency->datos=$datos;
    $SaveFrecuency->KEY_RELM_C=$_POST["KEY_RELM_C"];
    $SaveFrecuency->ajaxSaveFrecuency();
}
if(isset($_POST["ViewFrecuencyMail"])){
    $ViewFrecuencyMail=new  AjaxadReportes();
    $ViewFrecuencyMail->KEY_RELM_C=$_POST["KEY_RELM_C"];
    $ViewFrecuencyMail->ajaxViewFrecuencyMail();    
}
if(isset($_POST["SendMail"])){
    $SendMail=new  AjaxadReportes();
    $SendMail->KEY_RELM_C=$_POST["KEY_RELM_C"];
    $SendMail->Stp=$_POST["Stp"];
    $SendMail->ReporteNombre=$_POST["ReporteNombre"];
    $SendMail->ajaxSendMail();    
}

class AjaxadReportes{
    public function ajaxInicializar(){
        $respuesta=ControladoradReportes::ctrInicializar($this->Modulo);
        echo json_encode($respuesta);
    }
    public function ajaxFiltrosDescarga(){
        $FiltrosDescarga=ControladoradReportes::ctrFiltrosDescarga($this->Reporte);
        echo json_encode($FiltrosDescarga);
    }
    public function ajaxListadoClientes(){
        $ListadoClientes=ControladoradReportes::ctrListadoClientes();
        echo json_encode($ListadoClientes);
    }
    public function ajaxGenerarReporte(){
        $GenerarReporte=ControladoradReportes::ctrGenerarReporte($this->Datos,$this->Stp,$this->ReporteNombre);
        if(isset($GenerarReporte->Excel)){
            $GenerarReporte->Excel="";
        }
        //VAR_DUMP($GenerarReporte);
        //echo json_encode($GenerarReporte->ToExcel);
        echo json_encode($GenerarReporte);
        //$json=json_encode($GenerarReporte->ToExcel);
        //var_dump($json,json_encode($GenerarReporte));
        //var_dump(json_last_error_msg());
    }
    public function ajaxGenerarReporteSinFiltros(){
        $GenerarReporteSinFiltros=ControladoradReportes::ctrGenerarReporteSinFiltros($this->Stp,$this->ReporteNombre);
        //var_dump($GenerarReporteSinFiltros);
        if(isset($GenerarReporteSinFiltros->Excel)){
            $GenerarReporteSinFiltros->Excel="";
        }
        echo json_encode($GenerarReporteSinFiltros);
    }
    public function ajaxListadoImportadores(){
        $ListadoImportadores=ControladoradReportes::ctrListadoImportadores();
        echo json_encode($ListadoImportadores);
    }
    public function ajaxSaveMailInformation(){
        $SaveMailInformation=ControladoradReportes::ctrSaveMailInformation($this->datos,$this->SaveInformation);
        echo json_encode($SaveMailInformation);
    }
    public function ajaxListaReportesAutomaticos(){
        $ListaReportesAutomatico=ControladoradReportes::ctrListaReportesAutomatico($this->IdReporte);
        echo json_encode($ListaReportesAutomatico);
    }
    public function ajaxScheduleMailUPData(){
        $ScheduleMailUPData=ControladoradReportes::ctrScheduleMailUPData($this->Key_RELM_C);
        echo json_encode($ScheduleMailUPData);
    }
    public function ajaxDelSheduleMail(){
        $DelSheduleMail=ControladoradReportes::ctrDelSheduleMail($this->Key_RELM_C);
        echo json_encode($DelSheduleMail);
    }
    public function ajaxDelContactMail(){
        $DelContactMail=ControladoradReportes::ctrDelContactMail($this->IDMIC);
        echo json_encode($DelContactMail);
    }
    public function ajaxSaveFrecuency(){
        $SaveFrecuency=ControladoradReportes::ctrSaveFrecuency($this->datos,$this->KEY_RELM_C);
        echo json_encode($SaveFrecuency);
    }
    public function ajaxViewFrecuencyMail(){
        $ViewFrecuencyMail=ControladoradReportes::ctrViewFrecuencyMail($this->KEY_RELM_C);
        echo json_encode($ViewFrecuencyMail);
    }
    public function ajaxSendMail(){
        $SendMail=ControladoradReportes::ctrSendMail($this->KEY_RELM_C,$this->Stp,$this->ReporteNombre);
        echo json_encode($SendMail);
    }
}