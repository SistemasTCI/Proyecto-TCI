<?php
require_once "../controllers/opEdi.Controlador.php";
require_once "../models/opEdi.Model.php";
require_once "../controllers/funciones/LaredoCHB.Funciones.php";
require_once "../tools/PHPExcel/PHPExcel.php";
require_once "../tools/PHPExcel/PHPExcel/Writer/Excel2007.php";
require_once "../base_aux/Reports/opEdi.Report.Manifest.php";

if(isset($_POST["Inicializar"])){
    $SlClientes=new AjaxopEdi();
    $SlClientes->ajaxSlClientes();
}
if(isset($_POST["MostrarCajas"])){
    $tbCarpetas=new AjaxopEdi();
    $tbCarpetas->ajaxtbCajas($_POST["IDEDCLI"],$_POST["VistaPanel"]);
}
//Funcion obsoleta por cambio de  Cajas a  BD
if(isset($_POST["opEdiLeerFactura"])){
    $opEdiLeerFactura=new AjaxopEdi();
    $opEdiLeerFactura->ajaxopEdiLeerFactura($_POST["ruta"],$_POST["tarchivo"],$_POST['IDEDCLI'],$_POST['TipoCat']);
}

if(isset($_POST["opEdiFillEDI"])){
    $respuesta=ControladoropEdi::ctropEdiFillEDI($_POST["krelfc"],$_POST["caja"],$_POST['IMP_ABI_KEY'],$_POST['CONS_ABI_KEY'],$_POST['IDEDIMPCOS'],$_POST['TipoCat'],$_POST['VistaPanel']);
    echo json_encode($respuesta);
}
if(isset($_POST["opEdiFillEDI_MergeLines"])){
    //VAR_DUMP($_POST['IMP_ABI_KEY'],$_POST['CONS_ABI_KEY'],$_POST['TipoCat'],$_POST['IDEDIMPCOS'],$_POST["krelfc"],$_POST["caja"],$_POST['VistaPanel'],$_POST['MERGE']);
    $opEdiFillEDI_MergeLines=ControladoropEdi::ctropEdiFillEDI_LINES($_POST['IMP_ABI_KEY'],$_POST['CONS_ABI_KEY'],$_POST['TipoCat'],$_POST['IDEDIMPCOS'],$_POST["krelfc"],$_POST["caja"],$_POST['VistaPanel'],$_POST['MERGE']);
    echo json_encode($opEdiFillEDI_MergeLines);
}
if(isset($_POST["opEdiUpFiles"])){
    $respuesta=ControladoropEdi::ctropEdiUpFiles($_POST['IDEDCLI'],$_POST["ruta"],$_POST["TC"]);
    echo json_encode($respuesta);
}
if(isset($_POST["mldAddCatABI_CatABI"])){
    $CatABI=new AjaxopEdi();
    $CatABI->ajaxslcmldAddCatABI_CatalogoABI($_POST["IMP_ABI_KEY"]);
}
if(isset($_POST["opEdiGenerarEdi"])){
    //Se convierte el Json en array, se usa  TRUE para  que se convierta en  array, de lo contrario se convierte en  objeto.
    $datos_edi= json_decode($_POST['datos_edi'],true);
    $opEdiGenerarEdi=new AjaxopEdi();
    $opEdiGenerarEdi->ajaxmldGenerarEdi_btnProcEdi($datos_edi);
}
if(isset($_POST["RelProdABI"])){
    $respuesta=ControladoropEdi::ctrRelProdABI($_POST["ClaveProductoABI"],$_POST["ClaveProducto"],$_POST["DescripcionESP"],$_POST["IMP_ABI_KEY"],$_POST["CONS_ABI_KEY"],$_POST["SPI_CODE"],$_POST["tcat"]);
    echo json_encode($respuesta);
}
if(isset($_POST["CountUpFiles"])){
    $CountUpFiles=new AjaxopEdi();
    $CountUpFiles->ajaxCountUpFiles($_POST["ruta"]);
}
if(isset($_POST["FillSplitCaja"])){
    $FillSplitCaja=new AjaxopEdi();
    $FillSplitCaja->ajaxFillSplitCaja($_POST["IDEDIMPCOS"],$_POST["Caja"],$_POST["krelfc"]);
}
if(isset($_POST["SplitCajaFactura"])){
    $datos= json_decode($_POST['datos'],true);
    $SplitCajaFactura=ControladoropEdi::ctrSplitCajaFactura($datos);
    echo json_encode($SplitCajaFactura);
}
if(isset($_POST["SplitNewCajaFactura"])){
    $datos= json_decode($_POST['datos'],true);
    $SplitNewCajaFactura=ControladoropEdi::ctrSplitNewCajaFactura($datos);
    echo json_encode($SplitNewCajaFactura);
}
if(isset($_POST["NewCajaFactura"])){
    $datos= json_decode($_POST['datos'],true);
    $NewCajaFactura=ControladoropEdi::ctrNewCajaFactura($datos);
    echo json_encode($NewCajaFactura);
}
if(isset($_POST["ActualizarPesosBultos"])){
    $datos_PesosBultos= json_decode($_POST['datos_PesosBultos'],true);
    $ActualizarPesosBultos=new AjaxopEdi();
    $ActualizarPesosBultos->ajaxActualizarPesosBultos($_POST["IDEDCLI"],$_POST["KRELFC"],$_POST["CAJA"],$datos_PesosBultos);
}
if(isset($_POST["EliminarCajaFactura"])){
    $EliminarCajaFactura=new AjaxopEdi();
    $EliminarCajaFactura->ajaxEliminarCajaFactura($_POST["IDEDIMPCOS"],$_POST["KRELFC"],$_POST["CAJA"]);
}
if(isset($_POST["UPDProdABI"])){
    $UPDProdABI=new AjaxopEdi();
    $UPDProdABI->ajaxUPDProdABI($_POST["ClaveProductoABI"],$_POST["ClaveProducto"],$_POST["IMP_ABI_KEY"],$_POST["CONS_ABI_KEY"],$_POST["SPI_CODE"]);
}
if(isset($_POST["EliminarFactura"])){
    $EliminarFactura=new AjaxopEdi();
    $EliminarFactura->ajaxEliminarFactura($_POST["KRELFM"]);
}
if(isset($_POST["CargaMasiva"])){
    //Se convierte el Json en array, se usa  TRUE para  que se convierta en  array, de lo contrario se convierte en  objeto.
    $datos_edi= json_decode($_POST['datos_edi'],true);
    $CargaMasiva=new AjaxopEdi();
    $CargaMasiva->ajaxCargaMasiva($datos_edi);
}
if(isset($_POST["updateABITables"])){
    $updateABITables=new AjaxopEdi();
    $updateABITables->ajaxupdateABITables();
}

/* PASO DIRECTO AL CONTROLADOR */
if(isset($_POST["fillTbClients"])){
    $fillTbClients=ControladoropEdi::ctrfillTbClients();
    echo json_encode($fillTbClients);
}
if(isset($_POST["fillTbImporters"])){
    $fillTbImporters=ControladoropEdi::ctrfillTbImporters($_POST['IDEDCLI']);
    echo json_encode($fillTbImporters);
}
if(isset($_POST["ImporterDetail"])){
    $ImporterDetail=ControladoropEdi::ctrImporterDetail($_POST['IDEDIMPCOS']);
    echo json_encode($ImporterDetail);
}
if(isset($_POST["CreateImporter"])){
    $datos= json_decode($_POST['datos'],true);
    $CreateImporter=ControladoropEdi::ctrCreateImporter($datos);
    echo json_encode($CreateImporter);
}
if(isset($_POST["UploadFile"])){
    $UploadFile=ControladoropEdi::ctrUploadFile($_POST['File']);
    echo json_encode($UploadFile);
}
if(isset($_POST["CreateClient"])){
    $datos= json_decode($_POST['datos'],true);
    $CreateClient=ControladoropEdi::ctrCreateClient($datos);
    echo json_encode($CreateClient);
}
if(isset($_POST["PrintManifest"])){
    $datos= json_decode($_POST['Data'],true);
    $PrintManifest=ControladoropEdi::ctrPrintManifest($datos);
    echo json_encode($PrintManifest);
}
if(isset($_POST["UpdateManifestTable"])){
    $UpdateManifestTable=ControladoropEdi::ctrUpdateManifestTable();
    echo json_encode($UpdateManifestTable);
}
if(isset($_POST["EliminarCajaFacturaMasivo"])){
    $datos= json_decode($_POST['Data'],true);
    $EliminarCajaFacturaMasivo=ControladoropEdi::ctrEliminarCajaFacturaMasivo($datos,$_POST['CLI_ABI_KEY'],$_POST['VistaPanel']);
    echo json_encode($EliminarCajaFacturaMasivo);
}
if(isset($_POST["ReadNotifications"])){

    $ReadNotifications=ControladoropEdi::ctrReadNotifications();
    echo json_encode($ReadNotifications);
}
if(isset($_POST["AddExchRate"])){
    $AddExchRate=ControladoropEdi::ctrAddExchRate($_POST['MONEDA'],$_POST['VALOR_MONEDA']);
    echo json_encode($AddExchRate);
}

if(isset($_POST["PrintManifestSelectedEntrys"])){
    $datos= json_decode($_POST['Data'],true);
    $AddExchRate=ControladoropEdi::ctrPrintManifestSelectedEntrys($datos);
    echo json_encode($AddExchRate);
}
if(isset($_POST["showImporterToCopy"])){
    $showImporterToCopy=ControladoropEdi::ctrshowImporterToCopy();
    echo json_encode($showImporterToCopy);
}
if(isset($_POST["copyImporterData"])){
    $copyImporterData=ControladoropEdi::ctrcopyImporterData($_POST['IDEDIMPCOS']);
    echo json_encode($copyImporterData);
}

class AjaxopEdi{
    public function ajaxSlClientes(){
        $SlClientes=ControladoropEdi::ctrSlClientes();
        echo json_encode($SlClientes);
    }
    //Funcion Obsoleta por  cambio a Cajas en BD
    public function ajaxtbCarpetas($clave){
        $respuesta=ControladoropEdi::ctrtbCarpetas($clave);
        echo json_encode($respuesta);
    }
    public function ajaxtbCajas($IDEDCLI,$VistaPanel){
        $respuesta=ControladoropEdi::ctrtbCajas($IDEDCLI,$VistaPanel);
        echo json_encode($respuesta);
    }
    //Revisar  si esta  funcion se  va a seguir usando
    public function ajaxopEdiLeerFactura($ruta,$tarchivo,$clave_ABI,$TipoCat){
        if($tarchivo=='XML'){
            $respuesta=ControladoropEdi::ctrReadXML($ruta,$clave_ABI,$TipoCat);
        }
        else if($tarchivo=='XML'){
            $respuesta=ControladoropEdi::ctrReadXML($ruta,$clave_ABI,$TipoCat);
        }
        echo json_encode($respuesta);
    }
    public function ajaxslcmldAddCatABI_CatalogoABI($IMP_ABI_KEY){
        $respuesta=ControladoropEdi::ctrslcmldAddCatABI_CatalogoABI($IMP_ABI_KEY);
        echo json_encode($respuesta);
    }
    public function ajaxmldGenerarEdi_btnProcEdi($datos_edi){
        $respuesta=ControladoropEdi::ctrmldGenerarEdi_btnProcEdi($datos_edi);
        echo json_encode($respuesta);
    }  
    public function ajaxCountUpFiles($ruta){
        $respuesta=ControladoropEdi::ctrCountUpFiles($ruta);
        echo json_encode($respuesta);
    }   
    public function ajaxFillSplitCaja($IDEDIMPCOS,$Caja,$krelfc){
        $respuesta=ControladoropEdi::ctrFillSplitCaja($IDEDIMPCOS,$Caja,$krelfc);
        echo json_encode($respuesta);
    }    
    public function ajaxActualizarPesosBultos($IDEDCLI,$KRELFC,$CAJA,$datos_PesosBultos){
        $ActualizarPesosBultos=ControladoropEdi::ctrActualizarPesosBultos($IDEDCLI,$KRELFC,$CAJA,$datos_PesosBultos);
        echo json_encode($ActualizarPesosBultos);
    }
    public function ajaxEliminarCajaFactura($IDEDIMPCOS,$KRELFC,$CAJA){
        $EliminarCajaFactura=ControladoropEdi::ctrEliminarCajaFactura($IDEDIMPCOS,$KRELFC,$CAJA);
        echo json_encode($EliminarCajaFactura);
    }
    public function ajaxUPDProdABI($ClaveProductoABI,$ClaveProducto,$IMP_ABI_KEY,$CONS_ABI_KEY,$SPI_CODE){
        $UPDProdABI=ControladoropEdi::ctrUPDProdABI($ClaveProductoABI,$ClaveProducto,$IMP_ABI_KEY,$CONS_ABI_KEY,$SPI_CODE);
        echo json_encode($UPDProdABI);
    }
    public function ajaxEliminarFactura($KRELFM){
        $EliminarFactura=ControladoropEdi::ctrEliminarFactura($KRELFM);
        echo json_encode($EliminarFactura);
    }
    public function ajaxCargaMasiva($datos_edi){
        $CargaMasiva=ControladoropEdi::ctrCargaMasiva($datos_edi);
        echo json_encode($CargaMasiva);
    }
    public function ajaxupdateABITables(){
        $updateABITables=ControladoropEdi::ctrupdateABITables();
        echo json_encode($updateABITables);
    }
}

?>