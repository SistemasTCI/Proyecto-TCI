<?php
    require_once "conexion.php";
    class ModeloPNL{
        static public function MdlListaFacturasXPedimentoEnviar($factura,$trafico){//RECIBE FACTURA, RETORNA MULTIPLES
            /*$sql="  select  b.facgref as TRAFICO,B.facgferec,B.FACGNOFAC,C.Cantidad as QtyExported,B.FACGBULTOS as BULTOS,C.NUMPARTE as 'Part Number','' as 'CustomPartClient',
                        (SELECT PARTDESCESP
                        FROM [SLAM].[Aduana].[dbo].[tblPartes]
                        WHERE partcli=B.FACGCli AND PARTNUM=C.NUMPARTE) AS 'COMMERCIAL INVOICE',C.NumFraccion as HTS,SUBSTRING(C.NumFraccion,9,2) as NICO,
                        C.PaisOrigen,C.PesoKgs AS 'PESONETO',B.FACGPESOBRUTO AS 'PESO BRUTO','' AS 'CUSTOMORDER',C.PreUnitario AS 'UNITPRICE',C.Total as 'Amount/Importe',B.facgValorMerc,C.RENGLON
                    FROM (SELECT FACGNOFAC,facgferec,FACGBULTOS,FACGCli,FACGPESOBRUTO,facgValorMerc,factura_id,facgref FROM [SLAM].[Aduana].[dbo].[tblfactgen]) AS B LEFT JOIN 
                        (SELECT Factura_id,RENGLON,Cantidad,NUMPARTE,NumFraccion,PaisOrigen,PesoKgs,PreUnitario,Total FROM [SLAM].[Aduana].[dbo].[tblclasifica]) as C ON B.factura_id=C.Factura_id
                    WHERE  B.FACGCli=10320 and B.[FACGNOFAC]='89081432';";*/
            $sql="  select  C.Cantidad as QtyExported,B.FACGBULTOS as BULTOS,C.NUMPARTE as 'Part Number','' as 'CustomPartClient',
                        (SELECT PARTDESCESP
                        FROM [SLAM].[Aduana].[dbo].[tblPartes]
                        WHERE partcli=B.FACGCli AND PARTNUM=C.NUMPARTE) AS 'COMMERCIAL INVOICE',LEFT(C.NumFraccion,8) as HTS,SUBSTRING(C.NumFraccion,9,2) as NICO,
                        C.PaisOrigen,C.PesoKgs AS 'PESONETO',B.FACGPESOBRUTO AS 'PESO BRUTO','' AS 'CUSTOMORDER',C.PreUnitario AS 'UNITPRICE',C.Total as 'Amount/Importe',UA
                    FROM (SELECT FACGNOFAC,facgferec,FACGBULTOS,FACGCli,FACGPESOBRUTO,facgValorMerc,factura_id,facgref FROM [SLAM].[Aduana].[dbo].[tblfactgen]) AS B LEFT JOIN 
                        (SELECT Factura_id,RENGLON,Cantidad,NUMPARTE,NumFraccion,PaisOrigen,PesoKgs,PreUnitario,Total,UA FROM [SLAM].[Aduana].[dbo].[tblclasifica]) as C ON B.factura_id=C.Factura_id
                    WHERE  (B.FACGCli=10320 or B.FACGCli=4 ) and (B.[FACGNOFAC]=:factura and B.[facgref]=:trafico);";
            $stmt = conexion::conectarSQL()->prepare($sql);
            $stmt-> bindParam(":factura",$factura,PDO::PARAM_STR);
            $stmt-> bindParam(":trafico",$trafico,PDO::PARAM_STR);
            if($stmt -> execute()){
                //var_dump($stmt -> fetchAll());
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
    
        static public function MdlEncabezadoPedimentoEnviar($factura,$trafico){//RECIBE FACTURA, RETORNA UN VALOR
            $sql="  SELECT [FACGNOFAC],facgValorMerc,year(facgfefac) as YY,month(facgfefac) as MM,day(facgfefac) as DD ,FACGINCOTERM,FACGMONEDA,
                            FACGPESOBRUTO, bulcanti
                    FROM [SLAM].[Aduana].[dbo].[tblfactgen] as A left join 
                            (SELECT BULREF,sum(bulcanti) bulcanti
                            FROM [SLAM].[Aduana].[dbo].[TBLBULTOS] 
                            group by  BULREF) as B on [FACGREF]=BULREF
                    WHERE  (FACGCli=10320 or FACGCli=4 ) and ([FACGNOFAC]=:factura and [FACGREF]=:trafico);";
            $stmt = conexion::conectarSQL()->prepare($sql);
            //var_dump($sql,$stmt);
            $stmt-> bindParam(":factura",$factura,PDO::PARAM_STR);
            $stmt-> bindParam(":trafico",$trafico,PDO::PARAM_STR);
            if($stmt -> execute()){
                //var_dump($sql,$stmt -> fetchAll());
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        static public function MdlEncabezadoPedimentoDirecciones($factura,$trafico){//RECIBE FACTURA, RETORNA UN VALOR 'I24-005962
            //VAR_DUMP($factura,$trafico);
            /*$sql="  
                SELECT	b.*,A.traCli,
                    (SELECT Nom FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as RZ_CLI,
                    (SELECT concat(Dir,  ' N:', NUMEXT ) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as DR_CLI,
                    (SELECT concat(Cd,', ', Edo,  ' ', Cp,' ',Pais) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as CD_CLI,
                    (SELECT concat('RFC: ', RFC) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as TID_CLI,
                    (SELECT proNom FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =b.facgprov) as RZ_PRV,
                    (SELECT concat(NUMEXT,' ', proDir,  ' ', NUMINT ) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =b.facgprov) as DR_PRV,
                    (SELECT concat(proCd,', ', proEdo,  ' ', proCp,' ',proPais) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =b.facgprov) as CD_PRV,
                    (SELECT concat('Tax id: ', proIRS) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =b.facgprov) as TID_PRV,
                    (SELECT proNom FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as RZ_EM,
                    (SELECT concat(NUMEXT,' ', proDir,  ' ', NUMINT ) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as DR_EM,
                    (SELECT concat(proCd,', ', proEdo,  ' ', proCp,' ',proPais) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as CD_EM,
                    (SELECT concat('Tax id: ', proIRS) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as TID_EM
                FROM [SLAM].[Aduana].[dbo].[Trafico] AS A LEFT JOIN 
                (SELECT top 1 facgprov,facgref,facgnofac FROM [SLAM].[Aduana].[dbo].[tblfactgen] ) AS B on a.traReferencia=b.facgref
                WHERE a.traReferencia=:trafico and b.facgnofac=:factura;
            ";*/
            $sql="  SELECT	A.traCli,
                        (SELECT Nom FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as RZ_CLI,
                        (SELECT concat(Dir,  ' N:', NUMEXT ) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as DR_CLI,
                        (SELECT concat(Cd,', ', Edo,  ' ', Cp,' ',Pais) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as CD_CLI,
                        (SELECT concat('RFC: ', RFC) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as TID_CLI,
                        (SELECT proNom FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.traProCli) as RZ_PRV,
                        (SELECT concat(NUMEXT,' ', proDir,  ' ', NUMINT ) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.traProCli) as DR_PRV,
                        (SELECT concat(proCd,', ', proEdo,  ' ', proCp,' ',proPais) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.traProCli) as CD_PRV,
                        (SELECT concat('Tax id: ', proIRS) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.traProCli) as TID_PRV,
                        (SELECT proNom FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as RZ_EM,
                        (SELECT concat(NUMEXT,' ', proDir,  ' ', NUMINT ) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as DR_EM,
                        (SELECT concat(proCd,', ', proEdo,  ' ', proCp,' ',proPais) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as CD_EM,
                        (SELECT concat('Tax id: ', proIRS) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as TID_EM
                    FROM [SLAM].[Aduana].[dbo].[Trafico] AS A
                    WHERE traReferencia=:trafico;";
            $stmt = conexion::conectarSQL()->prepare($sql);
            $stmt-> bindParam(":trafico",$trafico,PDO::PARAM_STR);
            //$stmt-> bindParam(":factura",$factura,PDO::PARAM_STR);
            if($stmt -> execute()){
                //var_dump($stmt -> fetchAll());
                return $stmt -> fetchAll();
            }
            else{
                //var_dump($sql);
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        

        static public function MdlListaFacturasXPedMahle(){//RECIBE PEDIMENTO, RETORNA FACTURAS
            $sql=" SELECT facgnofac
            FROM (SELECT traPedimento,traReferencia FROM [SLAM].[Aduana].[dbo].[trafico])  as A 
                LEFT JOIN (SELECT FACGREF,facgnofac FROM [SLAM].[Aduana].[dbo].[tblfactgen]) AS B ON A.traReferencia = B.FACGREF
            WHERE replace(A.traPedimento,'3649-','')='3018990'; ";
            $stmt = conexion::conectarSQL()->prepare($sql);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
    
        static public function MdlListaFacturasXTrafMahle($trafico){//RECIBE TRAFICO, RETORNA FACTURAS
            $sql=" 
                SELECT facgnofac
                FROM [SLAM].[Aduana].[dbo].[tblfactgen]
                WHERE FACGREF=:trafico;";
            $stmt = conexion::conectarSQL()->prepare($sql);
            $stmt-> bindParam(":trafico",$trafico,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
        /*FUNCIONES PARA CARTA PORTE */

            
        static public function MdlLineasCartaPorte($Orden){//RECIBE TRAFICO, RETORNA FACTURAS
            $sql=" 
            
			SELECT --,
			[PedFacParNumeroParte] AS 'np',
			CLAVE_SAT AS 'Cve SAT Mercancia',	
			ISNULL(DESCRIPCION_SAT,CONCAT('DESC TCI: ',(select  top 1 PARTDESCESP FROM [SLAM].[Aduana].[dbo].[tblPartes] WHERE partcli=10320 and PARTNUM=[PedFacParNumeroParte] ))) AS 'Descripcion',
			[PedFacParCantidadUMAdicional] AS 'CantidaD',
			'H87' AS 'Cve Unidad	',
			PESOKGS_P'Peso en KG	',
			'KGM' AS 'Clave de Unidad de peso',
			[PedFacParValorMonedaFactura] AS 'Valor Mercancia',
			[PedFacParFraccion] AS 'Cve Fracc. Arancelaria',	
			traPedimento AS 'Pedimento Simplificado',	
			1 'Tipo de Materia',
			18 'Documentacion Aduanera',	
			TIPO_REG 'Regimen Aduanero',
			'TRAMITACIONES GROUP FORWARDING INC' as C_Nombre,
			'TCI960703M9A' as 'C_RFC/TAXID',	
			'PORT DRIVE.' as C_Calle,
			'Texas' as C_Estado,
			'Laredo' as C_Municipio,	
			'UNITEC INDUSTRIAL PARK' AS C_Colonia,	
			'USA' AS C_Pais,	
			'78045' AS C_CP,
			'DOSG TRANSPORTES S.A. DE C.V.' as D_Nombre,
			'TDO1212017J1' as 'D_RFC/TAXID',	
			'Libramiento Mex. II KM 16' as D_Calle,
			'Tamaulipas' as D_Estado,
			'Nuevo Laredo' as D_Municipio,	
			'' AS D_Colonia,	
			'Mex' AS D_Pais,	
			'88365' AS D_CP
			FROM (
                     SELECT [CONTROL] AS RELACION,
                            CAJA
                     FROM [SLAM].[Aduana].[dbo].[RELCARGAg]
                 ) AS RELACIONG
                     LEFT JOIN (
                                   SELECT *
                                   FROM (
                                            SELECT [CONTROL], [REFERENCIA] AS RED, [BULTOS], [CLIENTE_ID], pesokgs,
												(SELECT TOP 1 TIPO_REG 
															FROM [SLAM].[Aduana].[dbo].[TBLnOTAREVGEN] WHERE REFERENCIA= [REFERENCIA]) AS TIPO_REG 
                                            FROM [SLAM].[Aduana].[dbo].[RELCARGAd]
                                        ) AS RELACION
										     LEFT JOIN 
														(SELECT --A.traCli as Cliente,A.traImpExp,A.traProCli as ProveedorCliente,A.traFechaCruce,A.traPedimento,B.FACGNOFAC,a.TRAPDESTINO,C.PaisOrigen,C.NUMPARTE,
														A.traReferencia,concat(right(year(A.trafechaCruce),2),'-',A.traAduana,'-',A.traPedimento) as traPedimento,C.PesoKgs AS PESOKGS_P,
														b.facgnofac,'0' [PedFacParMaterial],c.DescFra AS [PedFacParDescripcion],c.UniMedFac as [PedFacParUMAdicional],
														c.Cantidad AS [PedFacParCantidadUMAdicional],
														iva AS [PedFacParIva],c.total as [PedFacParValorMonedaFactura],
														c.total as [PedFacParValorUSD],'None' [PedFacParCategoria],NumFraccion as [PedFacParFraccion],
														c.PaisOrigen as [PaisOrigen],c.TLC as [PedFacParTLC],c.PROSEC as [PedFacParProSec],REGLA8VA [PedFacParRecla8va],'0' [PedFacParAdvalorem],
														'0' [PedFacParRegla2da],c.fp as [PedFacParFormaPago],c.METVAL as [PedFacParMetodoValoracion],FP_IVA [PedFacParFormaPagoIva],renglon as [PedFacParSecFra],
														facgnofac [PedFacId],
														c.UniMedTar as [PedFacParUMTAdicional],c.CantTar as [PedFacParCantidadUMT],PreUnitario as [PedFacParPrecioUnitario],
														C.NUMPARTE as [PedFacParNumeroParte],UA AS [PedFacParUMAdicionalDescripcion],
														UniMedTar as [PedFacParUMTAdicionalDescripcion],'' [PedFacParCove],Renglon as [PedFacParSecuenciaAduanet],
														CASE WHEN LEN(NumFraccion) = 10 THEN SUBSTRING(NumFraccion, 9, 2) ELSE '' END AS  [PedFacParSubFraccion],
														'0' [PedFacParTasIva],
														(SELECT top 1 [NPARTE] FROM [TCI].[dbo].[TCI_tbl_RP_CartaP_CatPartesSAT] WHERE ClaveSAT  COLLATE Latin1_General_CI_AS=C.NUMPARTE and Cliente=10320) AS CLAVE_SAT,
														(SELECT top 1 [DescripcionSAT] FROM [TCI].[dbo].[TCI_tbl_RP_CartaP_CatPartesSAT] WHERE ClaveSAT  COLLATE Latin1_General_CI_AS=C.NUMPARTE and Cliente=10320) AS DESCRIPCION_SAT
														FROM [SLAM].[Aduana].[dbo].[trafico] as A LEFT JOIN 
															 [SLAM].[Aduana].[dbo].[tblfactgen] AS B ON A.traReferencia=B.facgref and A.PREF=B.PREF LEFT JOIN 
															 [SLAM].[Aduana].[dbo].[tblclasifica] as C ON B.factura_id=C.Factura_id) AS PARTES ON RELACION.RED = PARTES.traReferencia
                               ) AS RELACIOND ON RELACIONG.RELACION = RELACIOND.CONTROL
            WHERE [CLIENTE_ID] =10320 and CONTROL=:orden;   
            ";
            $stmt = conexion::conectarSQL()->prepare($sql);
            $stmt-> bindParam(":orden",$Orden,PDO::PARAM_STR);
            if($stmt -> execute()){
                return $stmt -> fetchAll();
            }
            else{
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }

        /* */
        static public function MdlEncabezadoPedimentoDireccionesC($factura,$trafico){//RECIBE FACTURA, RETORNA UN VALOR 'I24-005962
            //VAR_DUMP($factura,$trafico);
            $sql="  
               SELECT	b.*,A.traCli,
                    (SELECT Nom FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as RZ_CLI,
                    (SELECT concat(Dir,  ' N:', NUMEXT ) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as DR_CLI,
                    (SELECT concat(Cd,', ', Edo,  ' ', Cp,' ',Pais) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as CD_CLI,
                    (SELECT concat('RFC: ', RFC) FROM [SLAM].[Aduana].[dbo].[Clientes] WHERE [cliente_id]=A.traCli) as TID_CLI,
                    (SELECT proNom FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =b.facgprov) as RZ_PRV,
                    (SELECT concat(NUMEXT,' ', proDir,  ' ', NUMINT ) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =b.facgprov) as DR_PRV,
                    (SELECT concat(proCd,', ', proEdo,  ' ', proCp,' ',proPais) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =b.facgprov) as CD_PRV,
                    (SELECT concat('Tax id: ', proIRS) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =b.facgprov) as TID_PRV,
                    (SELECT proNom FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as RZ_EM,
                    (SELECT concat(NUMEXT,' ', proDir,  ' ', NUMINT ) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as DR_EM,
                    (SELECT concat(proCd,', ', proEdo,  ' ', proCp,' ',proPais) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as CD_EM,
                    (SELECT concat('Tax id: ', proIRS) FROM [SLAM].[Aduana].[dbo].[ProCli] WHERE [proveedor_id] =A.TRACONSIGNA_ID) as TID_EM
                FROM [SLAM].[Aduana].[dbo].[Trafico] AS A LEFT JOIN 
                (SELECT top 1 facgprov,facgref,facgnofac FROM [SLAM].[Aduana].[dbo].[tblfactgen] WHERE facgnofac=:factura ) AS B on a.traReferencia=b.facgref
                WHERE a.traReferencia=:trafico --and b.facgnofac='3590'
                --WHERE a.traReferencia=:trafico and b.facgnofac=:factura;
            ";
            $stmt = conexion::conectarSQL()->prepare($sql);
            $stmt-> bindParam(":trafico",$trafico,PDO::PARAM_STR);
            $stmt-> bindParam(":factura",$factura,PDO::PARAM_STR);
            if($stmt -> execute()){
                //var_dump($stmt -> fetchAll());
                return $stmt -> fetchAll();
            }
            else{
                //var_dump($sql);
                return "error ".$sql;
            }
            $stmt->close();
            $stmt=null;
        }
    }