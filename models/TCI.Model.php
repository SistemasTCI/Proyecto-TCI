<?php
require_once "conexion.php";
class ModeloTCI{
    static public function MdlNotificationManifest($Importer){
        $sql="Select ENTRY,CERTIFIED_DATE,CERTIFIED_HOUR from tci_OP_EDIS_VW_NOTIFICATIONS_MANIFEST WHERE IMPORTER=:Importer";
        //$sql="Select * from tci_ma_tb_contenedores";
        $stmt = conexion::conectar()->prepare($sql);
        $stmt-> bindParam(":Importer",$Importer,PDO::PARAM_STR);
        if($stmt -> execute()){
            return $stmt -> fetchAll();
        }
        else{
            return "error ".$sql;
        }
        $stmt->close();
        $stmt=null;  
	}
    static public function MdlNotificationImporters(){
        $sql="Select IMPORTER from tci_OP_EDIS_VW_NOTIFICATIONS_MANIFEST GROUP BY IMPORTER";
        $stmt = conexion::conectar()->prepare($sql);
        if($stmt -> execute()){
            return $stmt -> fetchAll();
        }
        else{
            return "error ".$sql;
        }
        $stmt->close();
        $stmt=null;  
	}
}