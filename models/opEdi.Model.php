<?php
    require_once "conexion.php";
    class ModeloopEdi{
        static public function MdlClientes($IDEDCLI,$opcion){
            if($opcion=='1'){
                $sql="Select ABI_KEY,IDEDCLI,Nombre,ruta,observaciones from tci_op_edis_tb_clientes ORDER BY NOMBRE"; //LLENA EL SLECT BOX
            }
            else if($opcion=='2'){
                //TOMA LOS  CONSECUTIVOS PARA EL ENTRY
                $sql="Select codigo_cb,CatRango,
                (CASE WHEN CatRango=0 THEN EntRangoFin ELSE (SELECT RANGOFINAL FROM tci.tci_sys_tb_consecutivos where IDCON=CatRango) end)-
				(CASE WHEN CatRango=0 THEN Consecutivo ELSE (SELECT Consecutivo FROM tci.tci_sys_tb_consecutivos where IDCON=CatRango) end) as Folios, 
                CASE WHEN CatRango=0 THEN Consecutivo ELSE (SELECT Consecutivo FROM tci.tci_sys_tb_consecutivos where IDCON=CatRango) end as Consecutivo
                from tci_op_edis_tb_clientes
                where IDEDCLI=:IDEDCLI";
            }
            else{
                //Query para obtener los rangos de  consecutivos para el cliente y el total de codigos  disponibles
                //LLENA EL  DHASBOARD DE  FOLIOS
                $sql="Select Observaciones,
                CASE WHEN CatRango=0 THEN EntRangoInicio ELSE (SELECT RANGOINICIO FROM tci.tci_sys_tb_consecutivos where IDCON=CatRango) end as EntRangoInicio,
                CASE WHEN CatRango=0 THEN EntRangoFin ELSE (SELECT RANGOFINAL FROM tci.tci_sys_tb_consecutivos where IDCON=CatRango) end as EntRangoFin, 
                CASE WHEN CatRango=0 THEN Consecutivo ELSE (SELECT Consecutivo FROM tci.tci_sys_tb_consecutivos where IDCON=CatRango) end as Consecutivo,
                (CASE WHEN CatRango=0 THEN EntRangoFin ELSE (SELECT RANGOFINAL FROM tci.tci_sys_tb_consecutivos where IDCON=CatRango) end)-
				(CASE WHEN CatRango=0 THEN Consecutivo ELSE (SELECT Consecutivo FROM tci.tci_sys_tb_consecutivos where IDCON=CatRango) end) as Folios 
                from tci_op_edis_tb_clientes
                where IDEDCLI=:IDEDCLI";
            }
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        //Funcion para  asignar  numeros  aleatorios y unicos 
        /*static public function test($clave,$opcion){
            $sql="update tci.tci_op_edis_tb_cajas set K_RELF_C='".$opcion."'  where Rel_Entry='".$clave."'";
            var_dump($sql);
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt -> execute()){
                return 'ok';
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }*/

        /* ESTA CONSULTA YA NO ES  NECESARIA A PARTIR DEL 2022/03/11*/
        /*static public function MdlImporter_Cliente($clave){
            $sql="SELECT CLIENTE_KEY FROM (Select  DISTINCT ACT AS  CLIENTE_KEY, MAX(CONS) AS IMPORTADOR_KEY,CONS_TO AS IMPORTADOR_NAME from replica_abi_dbf.r_usinv GROUP BY ACT,CONS_TO) as A WHERE IMPORTADOR_KEY=cast(:IMPORTADOR_KEY as int) limit 1";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IMPORTADOR_KEY",$clave,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetch();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }*/

        /*static public function MdlReceptor_Name($IDXML){
            $sql="SELECT UPPER(XML_ReceptorName) as RECEPTOR,UPPER(XML_ImporterName) as EMISOR FROM tci_op_edis_tb_importadores_XML WHERE IDXML=:IDXML";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDXML",$IDXML,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetch();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }*/

        /*** 
         * SE MODIFICO ESTA CONSULTA EL MES 10/25/2022
         * EN LA ANTEROIR SE UTILIZABA EL IDXML DE LA TABLA tci_op_edis_tb_importadores_XML
         * AHORA SE USA IDEDIMPCOS PARA PODER TOMAR TODOS LOS TODAS LAS POSIBLES XML CONFIGURATION DEL CONSIGNEE
         * EL OTRO CAMBIO FUE EN FETCH, SE CAMBIO A FETCHALL MISMA RAZON TOMAR TODOS LOS RESULTADOS Y NO EL PRIMERO
         * ***/
        static public function MdlReceptor_Name($IDEDIMPCOS){
            $sql="SELECT UPPER(XML_ReceptorName) as RECEPTOR,UPPER(XML_ImporterName) as EMISOR FROM tci_op_edis_tb_importadores_XML WHERE IDEDIMPCOS=:IDEDIMPCOS";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlConsultar_Emisor_Name($IDEDIMPCOS, $XML_EmisorName){
            $sql="SELECT UPPER(XML_ReceptorName) as RECEPTOR,UPPER(XML_ImporterName) as EMISOR, IDXML FROM tci_op_edis_tb_importadores_XML WHERE IDEDIMPCOS=:IDEDIMPCOS AND XML_ImporterName=:XML_EmisorName";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            $stmt-> bindParam(":XML_EmisorName",$XML_EmisorName,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetch();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        

        static public function MdlmldGenerarEdi_scac(){
            $sql="SELECT CODE,ifnull(NAME,'EMPTY NAME') AS NAME FROM abi_usref6 ORDER BY CODE";
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

        static public function MdlmldGenerarEdi_manuf($IDEDCLI){
            $sql="SELECT  MID,ifnull(MID_NAM,'EMPTY NAME') AS NAME FROM abi_usmid WHERE `KEY`=CAST(:IDEDCLI AS INT) AND id1='M' ORDER BY MID_NAM";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlReadXML_CatManual($IMP_ABI_KEY,$CONS_ABI_KEY,$tipocat,$prod){
            if($tipocat==1){
                $sql="SELECT B.KEY AS PRODUCTO,B.NAME AS DESCRIPCION,B.HTS AS HTS,`Value`,SPI_CODE FROM tci.tci_op_edis_tb_catpart AS A LEFT JOIN abi_usw_prow AS B ". 
                "ON A.ClaveProductoABI=B.KEY and cast(A.ClaveCliente as int)=B.ACCT WHERE A.tipo_cat=:TipoCat and A.ClaveProducto=:PROD and A.ClaveCliente=:IMP_ABI_KEY;";
                $stmt = conexion::conectar()->prepare($sql);
                $stmt-> bindParam(":IMP_ABI_KEY",$IMP_ABI_KEY,PDO::PARAM_STR);
                $stmt-> bindParam(":PROD",$prod,PDO::PARAM_STR);
                $stmt-> bindParam(":TipoCat",$tipocat,PDO::PARAM_STR);
            }
            else if($tipocat==2){
                $sql="SELECT B.KEY AS PRODUCTO,B.NAME AS DESCRIPCION,B.HTS AS HTS,`Value`,SPI_CODE FROM tci.tci_op_edis_tb_catpart AS A LEFT JOIN abi_usw_prow AS B ". 
                "ON A.ClaveProductoABI=B.KEY and cast(A.ClaveCliente as int)=B.ACCT WHERE A.tipo_cat=:TipoCat and A.ClaveProducto=:PROD and A.ClaveCliente=:IMP_ABI_KEY and Consigne=:CONS;";
                $stmt = conexion::conectar()->prepare($sql);
                $stmt-> bindParam(":IMP_ABI_KEY",$IMP_ABI_KEY,PDO::PARAM_STR);
                $stmt-> bindParam(":PROD",$prod,PDO::PARAM_STR);
                $stmt-> bindParam(":CONS",$CONS_ABI_KEY,PDO::PARAM_STR);
                $stmt-> bindParam(":TipoCat",$tipocat,PDO::PARAM_STR);
            }
            /*else{
                $sql="SELECT ClaveProducto AS PRODUCTO,DescripcionESP AS  DESCRIPCION,HTS_USA AS HTS,`Value`,if(SPI_CODE='',0,1) as SPI_CODE  FROM tci.tci_op_edis_tb_catpart WHERE ClaveProducto=:PROD AND ClaveCliente=:IMP_ABI_KEY;";
            }*/
            if($stmt -> execute()){
                return $stmt -> fetch();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlslcmldAddCatABI_CatalogoABI($IMP_ABI_KEY){
            $sql="SELECT `KEY`,`NAME`,HTS FROM abi_usw_prow where ACCT=cast(:IMP_ABI_KEY as int)";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IMP_ABI_KEY",$IMP_ABI_KEY,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlmldGenerarEdi_IncRango_Importadores($IDEDCLI,$EntryNew){
            $sql="UPDATE tci_op_edis_tb_clientes SET CONSECUTIVO=:EntryNew WHERE IDEDCLI=:IDEDCLI";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_STR);
            $stmt-> bindParam(":EntryNew",$EntryNew,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'ok';
            }
            else{
                return 'error '.$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlmldGenerarEdi_IncRango_CAT($EntryNew,$CatRango){
            $sql="UPDATE tci_sys_tb_consecutivos SET CONSECUTIVO=:EntryNew where IDCON=:CatRango";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":EntryNew",$EntryNew,PDO::PARAM_STR);
            $stmt-> bindParam(":CatRango",$CatRango,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'ok';
            }
            else{
                return 'error '.$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlImporter_ValidateInvoice($datos){
            //VAR_DUMP($datos['INVOICE_SERIE'].$datos['INVOICE']);
            //VAR_DUMP($datos);
            $sql="INSERT INTO tci.tci_op_edis_tb_cajas (K_RELF_C,K_RELF_M,BOX_NO,INVOICE,INVOICE_DATE,INVOICE_SUBTOTAL,INVOICE_TOTAL,CURRENCY,INVOICE_EXCHANGE,
                IMPORTER,GROSS_WEIGHT,NET_WEIGHT,QUANTITY,SCAC,CUST_REF,C_USER,C_DATE) VALUES('',:K_RELF_M,:BOX_NO,:INVOICE,:INVOICE_DATE,:INVOICE_SUBTOTAL,:INVOICE_TOTAL,:CURRENCY,:INVOICE_EXCHANGE,
                :IMPORTER,:GROSS_WEIGHT,:NET_WEIGHT,:QUANTITY,:SCAC,:CUST_REF,'MANUAL',now())";
            $INV=$datos['INVOICE_SERIE'].$datos['INVOICE'];
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":INVOICE",$INV,PDO::PARAM_STR);
            $stmt-> bindParam(":IMPORTER",$datos['IMPORTER'],PDO::PARAM_STR);
            if($stmt -> execute()){
                var_dump($stmt->fetchColumn());
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlImporter_InsertCaja($datos){
            VAR_DUMP($datos['INVOICE_SERIE'].$datos['INVOICE']);
            //VAR_DUMP($datos);
            $sql="INSERT INTO tci.tci_op_edis_tb_cajas (K_RELF_C,K_RELF_M,BOX_NO,INVOICE,INVOICE_DATE,INVOICE_SUBTOTAL,INVOICE_TOTAL,CURRENCY,INVOICE_EXCHANGE,
                IMPORTER,GROSS_WEIGHT,NET_WEIGHT,QUANTITY,SCAC,CUST_REF,C_USER,C_DATE) VALUES(:K_RELF_C,:K_RELF_M,:BOX_NO,:INVOICE,:INVOICE_DATE,:INVOICE_SUBTOTAL,:INVOICE_TOTAL,:CURRENCY,:INVOICE_EXCHANGE,
                :IMPORTER,:GROSS_WEIGHT,:NET_WEIGHT,:QUANTITY,:SCAC,:CUST_REF,'MANUAL',now())";
            /*$sql="  INSERT INTO tci.tci_op_edis_tb_cajas (K_RELF_C,K_RELF_M,BOX_NO,INVOICE,INVOICE_DATE,INVOICE_SUBTOTAL,INVOICE_TOTAL,CURRENCY,INVOICE_EXCHANGE,IMPORTER,GROSS_WEIGHT,NET_WEIGHT,QUANTITY,SCAC,CUST_REF,C_USER,C_DATE)
                    Select A.*
                    FROM (Select '' as K_RELF_C,:K_RELF_M as K_RELF_M,:BOX_NO as BOX_NO,:INVOICE as INVOICE,:INVOICE_DATE as INVOICE_DATE,:INVOICE_SUBTOTAL as INVOICE_SUBTOTAL,:INVOICE_TOTAL as INVOICE_TOTAL,
                        :CURRENCY as CURRENCY,:INVOICE_EXCHANGE as INVOICE_EXCHANGE,:IMPORTER as IMPORTER,:GROSS_WEIGHT as GROSS_WEIGHT,:NET_WEIGHT as NET_WEIGHT,:QUANTITY as QUANTITY,:SCAC as SCAC,
                        :CUST_REF as CUST_REF,'MANUAL' as C_USER,now() AS C_DATE) AS A 
                    WHERE (A.IMPORTER,A.INVOICE) not in (select IMPORTER,invoice from tci.tci_op_edis_tb_cajas)";*/
            $INV=$datos['INVOICE_SERIE'].$datos['INVOICE'];
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_C",$datos['K_RELF_C'],PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_M",$datos['K_RELF_M'],PDO::PARAM_STR);
            $stmt-> bindParam(":BOX_NO",$datos['BOX_NO'],PDO::PARAM_STR);
            $stmt-> bindParam(":INVOICE",$INV,PDO::PARAM_STR);
            $stmt-> bindParam(":INVOICE_DATE",$datos['INVOICE_DATE'],PDO::PARAM_STR);
            $stmt-> bindParam(":INVOICE_SUBTOTAL",$datos['INVOICE_SUBTOTAL'],PDO::PARAM_STR);
            $stmt-> bindParam(":INVOICE_TOTAL",$datos['INVOICE_TOTAL'],PDO::PARAM_STR);
            $stmt-> bindParam(":CURRENCY",$datos['CURRENCY'],PDO::PARAM_STR);
            $stmt-> bindParam(":INVOICE_EXCHANGE",$datos['INVOICE_EXCHANGE'],PDO::PARAM_STR);
            $stmt-> bindParam(":IMPORTER",$datos['IMPORTER'],PDO::PARAM_STR);
            $stmt-> bindParam(":GROSS_WEIGHT",$datos['GROSS_WEIGHT'],PDO::PARAM_STR);
            $stmt-> bindParam(":NET_WEIGHT",$datos['NET_WEIGHT'],PDO::PARAM_STR);
            $stmt-> bindParam(":QUANTITY",$datos['QUANTITY'],PDO::PARAM_STR);
            $stmt-> bindParam(":SCAC",$datos['SCAC'],PDO::PARAM_STR);
            $stmt-> bindParam(":CUST_REF",$datos['CUST_REF'],PDO::PARAM_STR);  
            if($stmt -> execute()){
                //var_dump($stmt->rowCount());
                return $stmt->rowCount();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlImporter_DeleteCaja($K_RELF_M){
            $sql="Delete FROM tci.tci_op_edis_tb_cajas WHERE K_RELF_M=:K_RELF_M";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_M",$K_RELF_M,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetch();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlImporter_DeleteMercancias($K_RELF_M){
            $sql="Delete FROM tci.tci_op_edis_tb_cajas_mercancia WHERE K_RELF_M=:K_RELF_M";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_M",$K_RELF_M,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetch();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlImporter_InsertMercancia($datos){
            $sql="INSERT INTO tci.tci_op_edis_tb_cajas_mercancia (Line,K_RELF_M,KeyProduct,`Description`,KeyUnit,Quantity,Amount,Gross_Weight,Net_Weight,C_Date) 
                VALUES(:Line,:K_RELF_M,:KeyProduct,:Description,:KeyUnit,:Quantity,:Amount,:Gross_Weight,:Net_Weight,now())";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_M",$datos[1],PDO::PARAM_STR);
            $stmt-> bindParam(":Line",$datos[0],PDO::PARAM_STR);
            $stmt-> bindParam(":KeyProduct",$datos[2],PDO::PARAM_STR);
            $stmt-> bindParam(":Description",$datos[6],PDO::PARAM_STR);
            $stmt-> bindParam(":KeyUnit",$datos[3],PDO::PARAM_STR);
            $stmt-> bindParam(":Quantity",$datos[4],PDO::PARAM_STR);
            $stmt-> bindParam(":Amount",$datos[5],PDO::PARAM_STR);
            $stmt-> bindParam(":Gross_Weight",$datos[7],PDO::PARAM_STR);
            $stmt-> bindParam(":Net_Weight",$datos[8],PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        /* Inicio bloque Revisado y actualizado 20220216*/
        static public function MdlImporter_Cajas($IDEDCLI,$VistaPanel){           
            if($VistaPanel=='1')
                //Trae las  cajas  pendientes por procesar  con el  identificador de productos 
                $sql=   "Select B.IDEDIMPCOS,B.IMP_NAME,B.IMP_ABI_KEY,B.CONS_NAME,B.CONS_ABI_KEY,B.t_archivo,A.K_RELF_C,A.BOX_NO,count(A.invoice) as INVOICES,sum(A.Gross_Weight) AS 'GROSS_WEIGHT',
                        sum(A.Quantity) AS 'QUANTITY',sum(A.Invoice_Total) AS 'AMOUNT',MIN(A.C_DATE) AS 'START_DATE', max(IF(E.Importer IS NULL,0,1)) AS PRODNOCAT,MANUF,A.SCAC,B.TipoCat,B.origen,B.tiop,B.puerto,B.location
                        from  tci.tci_op_edis_tb_cajas AS A 
                            LEFT JOIN tci.tci_op_edis_tb_importadores_consignatarios AS B ON A.IMPORTER=B.IDEDIMPCOS
                            LEFT JOIN tci.tci_op_edis_tb_clientes AS D ON B.IDEDCLI=D.IDEDCLI
                            LEFT JOIN tci.tci_op_edis_tb_entryauto  as C on A.K_RELF_C=C.K_RELF_C
                            LEFT JOIN tci.tci_op_edis_vw_BoxProdNoCat AS E ON B.IDEDIMPCOS=E.Importer AND A.K_RELF_M=E.K_RELF_M
                        where C.K_RELF_C IS NULL  AND D.IDEDCLI=:IDEDCLI
                        group by A.Importer,A.BOX_NO,A.K_RELF_C ORDER BY START_DATE DESC;";
            else{
                $sql="  Select C.IDEDIMPCOS,C.IMP_NAME,A.EDI_ENTRY,C.IMP_ABI_KEY,C.CONS_NAME,C.CONS_ABI_KEY,C.t_archivo,A.K_RELF_C,B.BOX_NO,count(B.invoice) as INVOICES,sum(B.Gross_Weight) AS 'GROSS_WEIGHT',
                        sum(B.Quantity) AS 'QUANTITY',sum(B.Invoice_Total) AS 'AMOUNT',A.C_DATE AS 'START_DATE',A.MANUFACTURER AS MANUF,A.SCAC,C.TipoCat,C.origen,C.tiop,C.puerto,C.location
                        from  tci.tci_op_edis_tb_entryauto AS A 
                            LEFT JOIN tci.tci_op_edis_tb_cajas  AS B ON A.K_RELF_C=B.K_RELF_C
                            LEFT JOIN tci.tci_op_edis_tb_importadores_consignatarios AS C ON B.IMPORTER=C.IDEDIMPCOS
                        where C.IDEDCLI=:IDEDCLI
                        group by A.K_RELF_C ORDER BY START_DATE DESC;";
            }
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        /* Fin bloque Revisado y actualizado*/
        /* Inicio bloque en revision*/
        static public function MdlEDIS($IDEDCLI,$krelfc,$caja,$VistaPanel,$Merge){
            //var_dump($IDEDCLI,$krelfc,$caja,$VistaPanel);
            $sql="CALL tci.tci_STP_OPEDIS_BOX_INVOICES(:IDEDCLI,:KRELFC,:BOX_NO,:VISTAPANEL,:MERGE);";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_INT);
            $stmt-> bindParam(":KRELFC",$krelfc,PDO::PARAM_STR);
            $stmt-> bindParam(":BOX_NO",$caja,PDO::PARAM_STR);
            $stmt-> bindParam(":VISTAPANEL",$VistaPanel,PDO::PARAM_STR);
            $stmt-> bindParam(":MERGE",$Merge,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        /* Fin bloque en revision*/

        static public function MdlEDIS_DETAILS($IDEDCLI,$krelfc,$caja,$VistaPanel){
            //var_dump($IDEDCLI,$krelfc,$caja,$VistaPanel);
            $sql="CALL tci.tci_STP_OPEDIS_BOX_INVOICES_DETAILS(:IDEDCLI,:KRELFC,:BOX_NO,:VISTAPANEL);";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_INT);
            $stmt-> bindParam(":KRELFC",$krelfc,PDO::PARAM_STR);
            $stmt-> bindParam(":BOX_NO",$caja,PDO::PARAM_STR);
            $stmt-> bindParam(":VISTAPANEL",$VistaPanel,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlEDIS_LINES($IDEDCLI,$krelfc,$caja,$VistaPanel,$Merge){
            //var_dump($IDEDCLI,$krelfc,$caja,$VistaPanel);
            $sql="CALL tci.tci_STP_OPEDIS_BOX_INVOICES_LINES(:IDEDCLI,:KRELFC,:BOX_NO,:VISTAPANEL,:COMBINAR);";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_INT);
            $stmt-> bindParam(":KRELFC",$krelfc,PDO::PARAM_STR);
            $stmt-> bindParam(":BOX_NO",$caja,PDO::PARAM_STR);
            $stmt-> bindParam(":VISTAPANEL",$VistaPanel,PDO::PARAM_STR);
            $stmt-> bindParam(":COMBINAR",$Merge,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        //MdlInsertEntryAuto
        static public function MdlInsertEntryAuto($Accion,$IDEDCLI,$entry,$box,$krelfc,$krelfc_new,$SCAC,$Manufacturer,$origen,$puerto,$tiop,$location){
            //Acciones: 1-Nuevo ENtry con nueva Clave Unica; 2- Nuevo Entry se mantiene la clave unica; 3- Actualizacion de Valores en entry Pasado
            if($Accion==1){
                $sql="INSERT INTO tci.tci_op_edis_tb_entryauto (EDI_ENTRY,K_RELF_C,IMPORTER,SCAC,MANUFACTURER,PUERTO,ORIGEN,TIOP,`LOCATION`,C_USER,C_DATE)
                    VALUES (:EDI_ENTRY,:K_RELF_C,:IMPORTER,:SCAC,:MANUFACTURER,:PUERTO,:ORIGEN,:TIOP,:LOCATION,'MANUAL',NOW())";
                
                $query=ModeloopEdi::MdlUpdateEntryBox($krelfc,$krelfc_new,$box,$IDEDCLI);
                if($query!=='ok'){	
                    return 'Error'.$query;
                }
                $KC=strval($krelfc_new);   
            }
            else if($Accion==2){
                $sql="INSERT INTO tci.tci_op_edis_tb_entryauto (EDI_ENTRY,K_RELF_C,IMPORTER,SCAC,MANUFACTURER,PUERTO,ORIGEN,TIOP,`LOCATION`,C_USER,C_DATE)
                VALUES (:EDI_ENTRY,:K_RELF_C,:IMPORTER,:SCAC,:MANUFACTURER,:PUERTO,:ORIGEN,:TIOP,:LOCATION,'MANUAL',NOW())";
                $KC=strval($krelfc);
            }
            else{
                 $sql="UPDATE tci.tci_op_edis_tb_entryauto
                    SET  SCAC=:SCAC,MANUFACTURER=:MANUFACTURER,PUERTO=:PUERTO,ORIGEN=:ORIGEN,TIOP=:TIOP,`LOCATION`=:LOCATION,C_USER='MANUAL',UPD_DATE=NOW()
                    WHERE K_RELF_C=:K_RELF_C";
                $KC=strval($krelfc);
            }
            $stmt = conexion::conectar()->prepare($sql);
            if($Accion==1 || $Accion==2){
                $stmt-> bindParam(":IMPORTER",$IDEDCLI,PDO::PARAM_STR);
                $stmt-> bindParam(":EDI_ENTRY",$entry,PDO::PARAM_STR);
            }
            $stmt-> bindParam(":K_RELF_C",$KC,PDO::PARAM_STR);
            $stmt-> bindParam(":SCAC",$SCAC,PDO::PARAM_STR);
            $stmt-> bindParam(":MANUFACTURER",$Manufacturer,PDO::PARAM_STR);
            $stmt-> bindParam(":ORIGEN",$origen,PDO::PARAM_STR);
            $stmt-> bindParam(":PUERTO",$puerto,PDO::PARAM_STR);
            $stmt-> bindParam(":TIOP",$tiop,PDO::PARAM_STR);
            $stmt-> bindParam(":LOCATION",$location,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'ok';
            }
            else{
                return 'error '.$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlUpdateEntryBox($krelfc,$krelfc_new,$box,$IDEDCLI){
            $sql="UPDATE tci.tci_op_edis_tb_cajas AS A
                SET K_RELF_C=:K_RELF_NEW
                WHERE (BOX_NO=:BOX_NO and K_RELF_C=:K_RELF_C) AND A.IMPORTER=:IDEDCLI";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":BOX_NO",$box,PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_C",$krelfc,PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_NEW",$krelfc_new,PDO::PARAM_STR);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'ok';
            }
            else{
                return 'error '.$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlFillSplitCaja($IDEDIMPCOS,$Caja,$krelfc){
            $sql="SELECT K_RELF_M,Invoice,Invoice_Total,Gross_Weight,Quantity FROM tci.tci_op_edis_tb_cajas where BOX_NO=:BOX_NO and K_RELF_C=:K_RELF_C and Importer=:IDEDIMPCOS;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":BOX_NO",$Caja,PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_C",$krelfc,PDO::PARAM_STR);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return 'error '.$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlSplitCajaFactura($K_RELF_M,$K_RELF_C){
            $sql="UPDATE tci.tci_op_edis_tb_cajas  SET K_RELF_C=:K_RELF_C,C_Date=now() where K_RELF_M=:K_RELF_M;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_C",$K_RELF_C,PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_M",$K_RELF_M,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'Actualizada';
            }
            else{
                return 'Error ';
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlSplitNewCajaFactura($K_RELF_M,$K_RELF_C,$NewCaja,$SCAC){
            $sql="UPDATE tci.tci_op_edis_tb_cajas  SET K_RELF_C=:K_RELF_C,C_Date=now(),BOX_NO=:NewCaja,SCAC=:SCAC where K_RELF_M=:K_RELF_M;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_C",$K_RELF_C,PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_M",$K_RELF_M,PDO::PARAM_STR);
            $stmt-> bindParam(":NewCaja",$NewCaja,PDO::PARAM_STR);
            $stmt-> bindParam(":SCAC",$SCAC,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'Actualizada';
            }
            else{
                return 'Error ';
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlNewCajaFactura($K_RELF_C,$Datos){
            
            $sql="UPDATE tci.tci_op_edis_tb_cajas AS A LEFT JOIN tci.tci_op_edis_tb_importadores_consignatarios AS B ON A.IMPORTER=B.IDEDIMPCOS
                    SET A.BOX_NO=:NewCaja , K_RELF_C=:K_RELF_C,SCAC=:SCAC
                    WHERE BOX_NO=:OldCaja AND K_RELF_C=:OLD_K_RELF_C AND B.IDEDIMPCOS=:IDEDIMPCOS";       
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_C",$K_RELF_C,PDO::PARAM_STR);
            $stmt-> bindParam(":OLD_K_RELF_C",$Datos['Oldkrelfc'],PDO::PARAM_STR);
            $stmt-> bindParam(":IDEDIMPCOS",$Datos['IDEDIMPCOS'],PDO::PARAM_STR);
            $stmt-> bindParam(":OldCaja",$Datos['OldCaja'],PDO::PARAM_STR);
            $stmt-> bindParam(":NewCaja",$Datos['NewCaja'],PDO::PARAM_STR);
            $stmt-> bindParam(":SCAC",$Datos['SCAC'],PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'Actualizada';
            }
            else{
                return 'Error ';
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlImporterConsName($clave){
            $sql="SELECT A.`NAME` FROM abi_usmst as A WHERE `KEY`=cast(:KEY as int)";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":KEY",$clave,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetch();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        //Analizar  las  funciones de los pesos
        static public function MdlActualizarPesosBultos($IDEDCLI,$KRELFC,$CAJA,$Peso,$Bultos){
            //var_dump($IDEDCLI,$KRELFC,$CAJA,strval($Peso),strval($Bultos));
            $Peso=strval($Peso);
            $Bultos=strval($Bultos);
            $sql="UPDATE tci.tci_op_edis_tb_cajas AS A LEFT  JOIN tci.tci_op_edis_tb_cajas_mercancia AS B  ON A.K_RELF_M=B.K_RELF_M
            SET A.Gross_Weight=if(:Gross_Weight>0,:Gross_Weight,A.Gross_Weight),
                B.Gross_Weight=if(:Gross_Weight>0,round(b.Net_Weight+((:Gross_Weight - a.Net_Weight)*((b.Net_Weight/a.Net_Weight)*100)/100),2),B.Gross_Weight),
                A.Quantity=if(:Quantity>0,:Quantity,A.Quantity)
            WHERE BOX_NO=:CAJA AND K_RELF_C=:K_RELF_C AND Importer=:IDEDCLI";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_C",$KRELFC,PDO::PARAM_STR);
            $stmt-> bindParam(":Gross_Weight",$Peso,PDO::PARAM_STR);
            $stmt-> bindParam(":Quantity",$Bultos,PDO::PARAM_STR);
            $stmt-> bindParam(":CAJA",$CAJA,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        /*static public function MdlActualizarPesosBultos_decimas($IDEDCLI,$KRELFC,$CAJA,$Peso,$Bultos){
            $sql="UPDATE tci.tci_op_edis_tb_cajas SET Gross_Weight=if(:Gross_Weight>0,(Gross_Weight)+(:Gross_Weight),Gross_Weight), Quantity=if(:Quantity>0,(Quantity)+(:Quantity),Quantity)
            WHERE BOX_NO=:CAJA AND Rel_entry is null AND Importer=:IDEDCLI order by IDTBCJ asc limit 1";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_C",$KRELFC,PDO::PARAM_STR);
            $stmt-> bindParam(":Gross_Weight",strval($Peso),PDO::PARAM_STR);
            $stmt-> bindParam(":Quantity",strval($Bultos),PDO::PARAM_STR);
            $stmt-> bindParam(":CAJA",$CAJA,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }*/

        static public function MdlEliminarCajaFactura($IDEDIMPCOS,$KRELFC,$CAJA){
            $sql="Delete A.*,B.* FROM tci.tci_op_edis_tb_cajas as A left join tci.tci_op_edis_tb_cajas_mercancia  as B on A.K_RELF_M=B.K_RELF_M WHERE A.IMPORTER=:IDEDIMPCOS AND K_RELF_C=:K_RELF_C AND BOX_NO=:CAJA;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            $stmt-> bindParam(":K_RELF_C",$KRELFC,PDO::PARAM_STR);
            $stmt-> bindParam(":CAJA",$CAJA,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlRelProdABI($ClaveProductoABI,$ClaveProducto,$DescripcionESP,$IMP_ABI_KEY,$CONS_ABI_KEY,$SPI_CODE,$tcat){
            //RELACIONAR  EN BASE  TIPO DE  CATALOGO
            //var_dump($ClaveProductoABI,$ClaveProducto,$DescripcionESP,$IMP_ABI_KEY,$CONS_ABI_KEY,$SPI_CODE,$tcat);
            if($tcat==1){
                $CONS_ABI_KEY='';
            }
            $sql="INSERT INTO tci.tci_op_edis_tb_catpart (tipo_cat,ClaveCliente,ClaveProducto,SPI_CODE,DescripcionESP,ClaveProductoABI,Consigne,F_Alta)".
                " values(:tcat,:IDEDIMPCOS,:ClaveProducto,:SPI_CODE,:DescripcionESP,:ClaveProductoABI,:Consigne,now())";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":ClaveProductoABI",$ClaveProductoABI,PDO::PARAM_STR);
            $stmt-> bindParam(":ClaveProducto",$ClaveProducto,PDO::PARAM_STR);
            $stmt-> bindParam(":DescripcionESP",$DescripcionESP,PDO::PARAM_STR);
            $stmt-> bindParam(":IDEDIMPCOS",$IMP_ABI_KEY,PDO::PARAM_STR);
            $stmt-> bindParam(":Consigne",$CONS_ABI_KEY,PDO::PARAM_STR);
            $stmt-> bindParam(":SPI_CODE",$SPI_CODE,PDO::PARAM_STR);
            $stmt-> bindParam(":tcat",strval($tcat),PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }

        static public function MdlUPDProdABI($ClaveProductoABI,$ClaveProducto,$IMP_ABI_KEY,$CONS_ABI_KEY,$SPI_CODE){
            //LA ACTUALIZACION NO  DEPENDE DEL TIPO DE  CATALOGO
            $sql="UPDATE tci.tci_op_edis_tb_catpart SET ClaveProductoABI=:ClaveProductoABI,F_Alta=now(),SPI_CODE=:SPI_CODE
            WHERE ClaveProducto=:ClaveProducto AND ClaveCliente=:IDEDIMPCOS and Consigne=:Consigne";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":ClaveProductoABI",$ClaveProductoABI,PDO::PARAM_STR);
            $stmt-> bindParam(":ClaveProducto",$ClaveProducto,PDO::PARAM_STR);
            $stmt-> bindParam(":IDEDIMPCOS",$IMP_ABI_KEY,PDO::PARAM_STR);
            $stmt-> bindParam(":Consigne",$CONS_ABI_KEY,PDO::PARAM_STR);
            $stmt-> bindParam(":SPI_CODE",$SPI_CODE,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        static public function MdlEliminarFactura($KRELFM){
            $sql="Delete A.*,B.* FROM tci.tci_op_edis_tb_cajas as A left join tci.tci_op_edis_tb_cajas_mercancia  as B on A.K_RELF_M=B.K_RELF_M WHERE A.K_RELF_M=:K_RELF_M;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_M",$KRELFM,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;  
        }
        /*Colocar para que lo  utiliz, se  usa  con el  EDI 16 */
        static public function MdlAMS($CAJA){
            $sql="SELECT SCAC,WEIGHT FROM replica_abi_dbf.r_usams as A  WHERE CONCAT(A.IEQUIP,A.NEQUIP)=:CAJA AND ENTRY IS NULL LIMIT 1";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":CAJA",$CAJA,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null; 
        }
        static public function MdlupdateABITables(){
            $sql="CALL tci.tci_STP_OPEDIS_UPDATE_ABITABLES();";
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdllistImpOfCli($IDCliente){
            $sql="Select a.IDEDIMPCOS,a.t_archivo,a.TipoCat,a.OpByInv,b.IDXML,a.OpWthBox 
                    FROM tci.tci_op_edis_tb_importadores_consignatarios as a 
                    left join tci_op_edis_tb_importadores_XML as  b on a.IDEDIMPCOS=b.IDEDIMPCOS where IDEDCLI=:IDCLIENTE;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDCLIENTE",$IDCliente,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlfillTbClients(){
            $sql="SELECT IDEDCLI,ABI_KEY,nombre,ruta,codigo_CB,CASE WHEN CatRango=0 THEN 'MANUAL' ELSE 'AUTO' END AS CatRango,EntRangoInicio,EntRangoFin,CONSECUTIVO,Observaciones,f_alta FROM tci.tci_op_edis_tb_clientes;";
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
        static public function MdlfillTbImporters($IDEDCLI){
            $sql="SELECT IDEDIMPCOS,IMP_ABI_KEY,IMP_NAME FROM tci.tci_op_edis_tb_importadores_consignatarios where IDEDCLI=:IDEDCLI;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$IDEDCLI,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlshowImporterToCopy(){
            $sql="SELECT IDEDIMPCOS,IMP_ABI_KEY,IMP_NAME,CONS_NAME FROM tci.tci_op_edis_tb_importadores_consignatarios";
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
        static public function MdlcopyImporterData($IDEDIMPCOS){
            $sql="SELECT * FROM tci.tci_op_edis_tb_importadores_consignatarios where IDEDIMPCOS=:IDEDIMPCOS";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlcopyImporterData_XML($IDEDIMPCOS){
            $sql="SELECT XML_ImporterName,XML_ReceptorName FROM tci.tci_op_edis_tb_importadores_xml where IDEDIMPCOS=:IDEDIMPCOS";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlImporterDetail($IDEDIMPCOS){
            $sql="SELECT CONS_ABI_KEY,CONS_NAME,XML_ImporterName,XML_ReceptorName,Manuf,t_archivo,TipoCat,origen,tiop,puerto,location,UOM,OpByInv,OpWthBox,InvAsBR FROM tci.tci_op_edis_tb_importadores_consignatarios where IDEDIMPCOS=:IDEDIMPCOS;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlImporterDetail_XML($IDEDIMPCOS){
            //var_dump($IDEDIMPCOS);
            $sql="select XML_ImporterName,XML_ReceptorName from tci_op_edis_tb_importadores_XML where IDEDIMPCOS=:IDEDIMPCOS;";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlCreateImporter($datos){
            $sql="INSERT INTO  tci.tci_op_edis_tb_importadores_consignatarios  (IDEDCLI,IMP_ABI_KEY,IMP_NAME,XML_ImporterName,CONS_ABI_KEY,CONS_NAME,XML_ReceptorName,Manuf,t_archivo,F_Alta,TipoCat,origen,tiop,puerto,location,UOM,OpByInv,OpWthBox,InvAsBR)".
                " values(:IDEDCLI,:IMP_ABI_KEY,:IMP_NAME,:XML_EmisorName,:CONS_ABI_KEY,:CONS_NAME,:XML_ReceptorName,:Manuf,:t_archivo,now(),:TipoCat,:Origin,:TypeOp,:Port,:Location,:UOM,:OpByInv,:OpWthBox,:InvAsBR) ".
                " RETURNING IDEDIMPCOS";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDCLI",$datos['IDEDCLI'],PDO::PARAM_STR);
            $stmt-> bindParam(":IMP_ABI_KEY",$datos['ABIKeyImport'],PDO::PARAM_STR);
            $stmt-> bindParam(":IMP_NAME",$datos['ImportName'],PDO::PARAM_STR);
            $stmt-> bindParam(":CONS_ABI_KEY",$datos['ABIKeyConsig'],PDO::PARAM_STR);
            $stmt-> bindParam(":CONS_NAME",$datos['ConsigName'],PDO::PARAM_STR);
            $stmt-> bindParam(":XML_EmisorName",$datos['Emisor'],PDO::PARAM_STR);
            $stmt-> bindParam(":XML_ReceptorName",$datos['Receptor'],PDO::PARAM_STR);
            $stmt-> bindParam(":Manuf",$datos['Manuf'],PDO::PARAM_STR);
            $stmt-> bindParam(":t_archivo",$datos['TInv'],PDO::PARAM_STR);
            $stmt-> bindParam(":TipoCat",$datos['Cat'],PDO::PARAM_STR);
            $stmt-> bindParam(":Origin",$datos["Origin"],PDO::PARAM_STR);
            $stmt-> bindParam(":Port",$datos["Port"],PDO::PARAM_STR);
            $stmt-> bindParam(":Location",$datos['Location'],PDO::PARAM_STR);
            $stmt-> bindParam(":TypeOp",$datos['TypeOp'],PDO::PARAM_STR);
            $stmt-> bindParam(":UOM",$datos['UOM'],PDO::PARAM_STR);
            $stmt-> bindParam(":OpByInv",$datos['OpByInv'],PDO::PARAM_STR);
            $stmt-> bindParam(":OpWthBox",$datos['OpWthBox'],PDO::PARAM_STR);
            $stmt-> bindParam(":InvAsBR",$datos['InvAsBR'],PDO::PARAM_STR);
            if($stmt -> execute()){
                return  $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlInsertXMLtoImporter($IDImporter,$datos){
            $sql="INSERT INTO  tci.tci_op_edis_tb_importadores_XML(IDEDIMPCOS,XML_ImporterName,XML_ReceptorName,C_DATE)".
                " values(:IDEDIMPCOS,:Emisor,:Receptor,DATE(NOW()))";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDImporter,PDO::PARAM_STR);
            $stmt-> bindParam(":Emisor",$datos['Emisor'],PDO::PARAM_STR);
            $stmt-> bindParam(":Receptor",$datos['Receptor'],PDO::PARAM_STR);
            if($stmt -> execute()){
                return "OK";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlCreateClient($datos){
            $sql="INSERT INTO  tci.tci_op_edis_tb_clientes  (ABI_KEY,nombre,ruta,codigo_CB,CatRango,EntRangoInicio,EntRangoFin,CONSECUTIVO,Observaciones,f_alta)".
                " values(:ABI_KEY,:nombre,:ruta,:codigo_CB,:CatRango,:EntRangoInicio,:EntRangoFin,:CONSECUTIVO,:Observaciones,NOW())";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":ABI_KEY",$datos['ABIKeyClient'],PDO::PARAM_STR);
            $stmt-> bindParam(":nombre",$datos['ClientName'],PDO::PARAM_STR);
            $stmt-> bindParam(":ruta",$datos['Path'],PDO::PARAM_STR);
            $stmt-> bindParam(":codigo_CB",$datos['Filer'],PDO::PARAM_STR);
            $stmt-> bindParam(":CatRango",$datos['RangeType'],PDO::PARAM_STR);
            $stmt-> bindParam(":EntRangoInicio",$datos['Firts'],PDO::PARAM_STR);
            $stmt-> bindParam(":EntRangoFin",$datos['Last'],PDO::PARAM_STR);
            $stmt-> bindParam(":CONSECUTIVO",$datos['Firts'],PDO::PARAM_STR);
            $stmt-> bindParam(":Observaciones",$datos['Observaciones'],PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlDataManifest($entry){
            $sql="SELECT * FROM tci.tci_op_edis_tb_manifest where entry=convert(:entry,int)";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":entry",$entry,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlUpdatePrintManifest($entry){
            $sql="UPDATE tci.tci_op_edis_tb_manifest SET CREATED_PDF=1 where entry=convert(:entry,int)";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":entry",$entry,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'OK';
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlUpdateManifest(){
            $sql="CALL tci.tci_STP_OPEDIS_UPDATE_MANIFEST();";
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt -> execute()){
                return "OK";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlLiberarEntry($K_RELF_C){
            $sql="  INSERT INTO tci_OP_EDIS_TB_ENTRIES_RECICLADOS(TIPO_CONSECUTIVO,IDEDCLI,ENTRY_LIBERADO,C_DATE)
                    Select C.CatRango,B.IDEDCLI,A.EDI_ENTRY,NOW() 
                    from tci.tci_op_edis_tb_entryauto as A
                        LEFT JOIN tci.tci_op_edis_tb_importadores_consignatarios AS B ON A.IMPORTER=B.IDEDIMPCOS
                        LEFT JOIN tci.tci_op_edis_tb_clientes AS C ON B.IDEDCLI=C.IDEDCLI
                    where K_RELF_C=:K_RELF_C";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_C",$K_RELF_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlmldGenerarEdi_ConsultarEntryReciclado($CATALOGO,$IDEDCLI){
            if($CATALOGO==0){
                $condicion=$IDEDCLI;
                $sql="  SELECT ENTRY_LIBERADO
                    FROM tci.tci_op_edis_tb_entries_reciclados
                    WHERE IDEDCLI=:condicion
                    ORDER BY ENTRY_LIBERADO ASC
                    LIMIT 1";
            }
            else{
                $condicion=$CATALOGO;
                $sql="  SELECT ENTRY_LIBERADO
                    FROM tci.tci_op_edis_tb_entries_reciclados
                    WHERE TIPO_CONSECUTIVO=:condicion
                    ORDER BY ENTRY_LIBERADO ASC
                    LIMIT 1";
            }
            $stmt = conexion::conectar()->prepare($sql);
            //$stmt-> bindParam(":K_RELF_C",$CATALOGO,PDO::PARAM_STR);
            $stmt-> bindParam(":condicion",$condicion,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlmldGenerarEdi_EliminarEntryReciclado($ENTRY){
            $sql="DELETE FROM tci_OP_EDIS_TB_ENTRIES_RECICLADOS  WHERE ENTRY_LIBERADO=:ENTRY";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":ENTRY",$ENTRY,PDO::PARAM_STR);
            if($stmt -> execute()){
                return "ok";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        //ESTA  TABLA CONTIENE LAS PLANTILLAS DE  AUTOMATIZACION DE  PDF
        static public function Mdlautopdf_valores($IDEDIMPCOS){
            $sql="Select a.CONCEPTO,a.REFERENCIA_CONCEPTO,TARGET,SPLITTER,INICIO_EXTRACCION,RECORRER,PROCESAR,SPLIT_PROCESAR,POSICION_EXTRAER
            from tci.tci_op_edis_tb_autopdf_valores as a
                inner join tci.tci_op_edis_tb_autopdf_archivos as b on a.IDAUTOPLANT=b.IDAUTOPLANT
            where b.IDEDIMPCOS=:IDEDIMPCOS";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        //ESTA  TABLA CONTIENE LA  RELACION DE PDF CON LAS  PLANTILLAS
        static public function Mdlautopdf_archivos($IDEDIMPCOS){
            $sql="Select REFERENCIA_ARCHIVO,INDICE_FILA from tci.tci_op_edis_tb_autopdf_archivos where IDEDIMPCOS=:IDEDIMPCOS";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function Mdlautopdf_funciones($IDEDIMPCOS){
            $sql="SELECT FUNCION FROM tci.tci_op_edis_tb_autopdf_funciones_personalizadas where IDEDIMPCOS=:IDEDIMPCOS";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":IDEDIMPCOS",$IDEDIMPCOS,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlReadNotifications(){
            $sql="update tci.tci_op_edis_tb_manifest set STAT_NOT=1 where ENTRY in (select entry from tci_OP_EDIS_VW_NOTIFICATIONS_MANIFEST);";
            $stmt = conexion::conectar()->prepare($sql);
            if($stmt -> execute()){
                return "OK";
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlCOnsultarUltimoTCPesos(){
            $sql="SELECT VALOR_MONEDA FROM tci_SYS_TB_TCUSD where fecha_tc=date(now()) and MONEDA='MX'";
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
        static public function MdlAddExchRate($MONEDA,$VALOR_MONEDA){
            $sql="INSERT INTO tci_SYS_TB_TCUSD(MONEDA,FECHA_TC,VALOR_MONEDA) VALUES(:MONEDA,DATE(NOW()),:VALOR_MONEDA) ";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":MONEDA",$MONEDA,PDO::PARAM_STR);
            $stmt-> bindParam(":VALOR_MONEDA",$VALOR_MONEDA,PDO::PARAM_STR);
            if($stmt -> execute()){
                return 'OK';
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        static public function MdlTakeEntry($K_RELF_C){
            $sql="SELECT EDI_ENTRY FROM  tci.tci_op_edis_tb_entryauto WHERE K_RELF_C=:K_RELF_C";
            $stmt = conexion::conectar()->prepare($sql);
            $stmt-> bindParam(":K_RELF_C",$K_RELF_C,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetch();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

    }
?>