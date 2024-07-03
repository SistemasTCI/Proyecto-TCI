<?php
    require_once "conexion.php";
    class ModeloadReportes{
        static public function MdlInicializar($Modulo){
            $sql=  "Select B.IDREP,B.Nombre_Reporte,B.Descripcion,A.IDModulo,B.SQL ,B.F_alta
                    FROM tci.tci_rp_rel_reportes_modulos AS A
                    LEFT JOIN  tci.tci_rp_tb_reportes AS B ON  A.IDREP=B.IDREP 
                    WHERE A.IDModulo=:Modulo";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":Modulo",$Modulo,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlFiltrosDescarga($Reporte){
            $sql="SELECT ID_Filtro,Etiqueta,TipoFiltro,IDREPFIL FROM tci.tci_rp_tb_reportes_filtros where IDREP=:IDREP order by IDREPFIL asc; ";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDREP",$Reporte,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlListadoClientes(){
            $sql="SELECT `KEY`,`NAME` FROM replica_abi_dbf.r_usmst ORDER BY `NAME`";
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
        static public function MdlConsultarReportes($Datos,$stp){

            //SINTAXIS PARA MYSQL
            /*$count=count($Datos);
            $sql='CALL '.$stp.' (';
            for($i=0;$i<=$count-1;$i++){
                $sql.="'".strval($Datos[$i])."'";
                if($i+1>$count-1){
                    $sql.=')';
                }
                else{
                    $sql.=',';
                }
            }*/


            
            //SINTAXIS PARA SQL SERVER

            $sql="EXEC ".$stp." @RangoIni='".$Datos[0]."', @RangoFin='".$Datos[1]."'";
            //VAR_DUMP($sql);

            ////////////////////////////////////////////////////////////////////

            $stmt = conexion::conectarSQL()->prepare($sql);
            if($stmt -> execute()){
                //En caso de error de  conexion con la BD  regresa  vacio
                return $stmt -> fetchAll();
            }
            else{
                $data= array();
                $data[0]=$stmt->errorInfo();
                $data[0]["Level"]="Error";
                return $data;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlConsultarReportesSinFiltros($stp){
            $stmt = conexion::conectar()->prepare("CALL ".$stp." ();");
            //$stmt -> execute();
            //return $stmt -> fetchAll();
            //$stmt->close();
            //$stmt=null;

            if($stmt -> execute()){
                //En caso de error de  conexion con la BD  regresa  vacio
                return $stmt -> fetchAll();
            }
            else{
                $data= array();
                $data[0]=$stmt->errorInfo();
                $data[0]["Level"]="Error";
                return $data;
            }
            $stmt->close();
            $stmt=null;


        }
        static public function MdlListadoImportadores(){
            $sql="SELECT IDABI,ID,Nom_Importer FROM tci.tci_sys_cat_importador ORDER BY Nom_Importer;";
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
        static public function MdlEstructaCorreo($SaveInformation,$K_RELM_C,$Subject,$Body){
            if($SaveInformation=="New"){
                $sql="INSERT INTO tci.tci_rp_cat_mailprofile(KEY_RELM_C,Titulo,Cuerpo,Att_excel,F_ALTA) VALUES(:K_RELM_C,:Subject,:Body,1,NOW());";
            }
            else{
                $sql="UPDATE tci.tci_rp_cat_mailprofile SET Titulo=:Subject,Cuerpo=:Body WHERE KEY_RELM_C=:K_RELM_C;";
            }
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELM_C",$K_RELM_C,PDO::PARAM_STR);
            $stmt-> bindParam(":Subject",$Subject,PDO::PARAM_STR);
            $stmt-> bindParam(":Body",$Body,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'ok';
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlListadoContactos($K_RELM_C,$contacto){
            //var_dump($contacto);
            $sql="INSERT INTO tci.tci_rp_rel_mailimpcont(KEY_RELM_C,Contact_Mail,tipo,F_ALTA) VALUES(:K_RELM_C,:Contact_Mail,:tipo,NOW());";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELM_C",$K_RELM_C,PDO::PARAM_STR);
            $stmt-> bindParam(":Contact_Mail",$contacto['email'],PDO::PARAM_STR);
            $stmt-> bindParam(":tipo",$contacto['type'],PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'ok';
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlCrearRelacionImportCorreo($K_RELM_C,$Reporte,$Importador){
            $sql="INSERT INTO tci.tci_rp_rel_MPIMP(KEY_RELM_C,IDREPORT,IDIMP,F_ALTA) VALUES(:K_RELM_C,:Reporte,:Importador,NOW());";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELM_C",$K_RELM_C,PDO::PARAM_STR);
            $stmt-> bindParam(":Reporte",$Reporte,PDO::PARAM_STR);
            $stmt-> bindParam(":Importador",$Importador,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'ok';
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlListaReportesAutomatico($IdReporte){
            $sql="Select A.KEY_RELM_C,D.Nom_Importer,D.ID,C.Titulo,C.f_Alta 
                    from tci.tci_rp_rel_MPIMP as A left join tci.tci_rp_tb_reportes as B on A.IDREPORT=B.IDREP	
                        LEFT JOIN tci.tci_rp_cat_mailprofile AS C ON A.KEY_RELM_C=C.KEY_RELM_C
                        LEFT JOIN tci.tci_sys_cat_importador AS D ON A.IDIMP=D.ID
                    WHERE B.IDREP=:IdReporte;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IdReporte",$IdReporte,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlEstructuraCorreoAuto($KEY_RELM_C){
            $sql="SELECT Titulo,Cuerpo,Att_excel FROM tci.tci_rp_cat_mailprofile
                    where KEY_RELM_C=:KEY_RELM_C;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlContactosCorreosAuto($KEY_RELM_C){
            $sql="SELECT IDMIC,Contact_Mail,tipo,F_alta FROM tci.tci_rp_rel_mailimpcont
                    where KEY_RELM_C=:KEY_RELM_C;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlDelSheduleMail($KEY_RELM_C){
            $sql="Delete A.*,B.*,C.*,D.*,E.*,F.*,G.* from tci.tci_rp_rel_mpimp as A 
            left join tci.tci_rp_cat_mailprofile as B on A.KEY_RELM_C=B.KEY_RELM_C 
            left join tci.tci_rp_rel_mailimpcont as C on A.KEY_RELM_C=C.KEY_RELM_C
            left join tci.tci_rp_tb_mailfrcdays as D on A.KEY_RELM_C=D.KEY_RELM_C
            left join tci.tci_rp_tb_mailfrchours as E on A.KEY_RELM_C=E.KEY_RELM_C
            left join tci.tci_rp_tb_mailfrcfiltervalues as F on A.KEY_RELM_C=F.KEY_RELM_C
            left join tci.tci_rp_tb_mailfrcshoot as G on A.KEY_RELM_C=G.KEY_RELM_C
            WHERE A.KEY_RELM_C =:KEY_RELM_C;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlDelContactMail($IDMIC){
            $sql="Delete FROM tci.tci_rp_rel_mailimpcont WHERE IDMIC=:IDMIC;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDMIC",$IDMIC,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlSaveFrecuency_Del($KEY_RELM_C){
            $sql="Delete A.*,B.*,C.*,D.* 
                FROM tci.tci_rp_tb_mailfrcdays as A 
                    LEFT JOIN tci.tci_rp_tb_mailfrchours AS B ON A.KEY_RELM_C=B.KEY_RELM_C 
                    LEFT JOIN tci.tci_rp_tb_mailfrcfiltervalues AS C ON A.KEY_RELM_C=C.KEY_RELM_C 
                    LEFT JOIN tci.tci_rp_tb_mailfrcshoot AS D ON A.KEY_RELM_C=D.KEY_RELM_C 
                WHERE A.KEY_RELM_C=:KEY_RELM_C";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlSaveFrecuency_Dias($KEY_RELM_C,$Type,$Dia){
            //var_dump($KEY_RELM_C,$Type,$Dia);
            $sql="INSERT INTO tci.tci_rp_tb_mailfrcdays(KEY_RELM_C,Type_Frc,Frecuency,F_alta) VALUES (:KEY_RELM_C,:Type,:Dia,NOW())";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            $stmt-> bindParam(":Type",$Type,PDO::PARAM_STR);
            $stmt-> bindParam(":Dia",$Dia,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlSaveFrecuency_Horas($KEY_RELM_C,$Hora){
            $sql="INSERT INTO tci.tci_rp_tb_mailfrchours(KEY_RELM_C,Hours,F_alta) VALUES (:KEY_RELM_C,:Hours,NOW())";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            $stmt-> bindParam(":Hours",$Hora,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlSaveFrecuency_ValoresFiltros($KEY_RELM_C,$Type,$Filter_value,$IDFiltro){
            //var_dump($KEY_RELM_C,$Type,$Filter_value);
            $sql="INSERT INTO tci.tci_rp_tb_mailfrcfiltervalues(KEY_RELM_C,IDFiltro,TipoFiltro,Filter_value,F_alta) VALUES (:KEY_RELM_C,:IDFiltro,:Type,:Filter_value,NOW())";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            $stmt-> bindParam(":IDFiltro",$IDFiltro,PDO::PARAM_STR);
            $stmt-> bindParam(":Type",$Type,PDO::PARAM_STR);
            $stmt-> bindParam(":Filter_value",$Filter_value,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlViewFrecuencyMail_filters($KEY_RELM_C){
            $sql="SELECT IDFiltro,Filter_value FROM tci.tci_rp_tb_mailfrcfiltervalues as a WHERE A.KEY_RELM_C=:KEY_RELM_C;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlViewFrecuencyMail_days($KEY_RELM_C){
            $sql="SELECT A.KEY_RELM_C,A.Type_Frc,A.Frecuency FROM tci.tci_rp_tb_mailfrcdays as a WHERE A.KEY_RELM_C=:KEY_RELM_C;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlViewFrecuencyMail_hours($KEY_RELM_C){
            $sql="SELECT B.KEY_RELM_C,B.Hours FROM  tci.tci_rp_tb_mailfrchours as B WHERE B.KEY_RELM_C=:KEY_RELM_C;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlSendMail_Profile($KEY_RELM_C){
            $sql="SELECT Titulo,Cuerpo FROM  tci.tci_rp_cat_mailprofile WHERE KEY_RELM_C=:KEY_RELM_C;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlSendMail_Contact($KEY_RELM_C){
            $sql="SELECT Contact_Mail FROM  tci.tci_rp_rel_mailimpcont WHERE KEY_RELM_C=:KEY_RELM_C;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlSendMail_FilterValues($KEY_RELM_C){
            $sql="SELECT TipoFiltro,Filter_value FROM  tci.tci_rp_tb_mailfrcfiltervalues WHERE KEY_RELM_C=:KEY_RELM_C order by IDFiltro;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlSaveFrecuency_NextShoot($KEY_RELM_C){
            $sql="CALL tci_STP_ADREPORTES_SHOOTDATE(:KEY_RELM_C)";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY_RELM_C",$KEY_RELM_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

    }