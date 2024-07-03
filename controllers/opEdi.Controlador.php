<?php

    //VARIABLES GLOBALES
    $FilesPDF=[];
    $FilesXLS=[];
    $FilesXML=[];
    $FilesXMLNoValidos=[];
    //FIN DE  VARIABLES GLOBALES
    function CAT_KINUGAWA($detalle_factura_archivo,$TMP_valores_factura){
      //VAR_DUMP($detalle_factura_archivo);
      $readExcel='C:/CBRIS/XML/1-CATALOGO/KINUGAWA.xlsx';
      $PDF_NoLayout='';
      $cont_archivos=0;
      $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';$CUST_REF='';
      $inputFileType = PHPExcel_IOFactory::identify($readExcel);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($readExcel);
      $sheetNames = $objPHPExcel->getSheetNames();
      $sheet = $objPHPExcel->setActiveSheetIndexByName('KINUGAWA');
      $c_data = $objPHPExcel->setActiveSheetIndexByName('KINUGAWA')->getHighestRow();
      for($i=2;$i<=$c_data;$i++){
        $INVOICE=$sheet->getCell('A'.$i)->getValue().$sheet->getCell('B'.$i)->getValue();
        $CAJA=$sheet->getCell('E'.$i)->getValue();
        $SCAC=$sheet->getCell('D'.$i)->getValue();
        $BRUTO=$sheet->getCell('C'.$i)->getValue();
        $FILE='';//Para los catalogos no se debe enviar  el nombre del archivo
        $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE,'CUST_REF'=>$CUST_REF);
        $cont_archivos+=1;
      }
      
      $data['detalle_factura_archivo']=$detalle_factura_archivo;
      $data['PDF_NoLayout']=$PDF_NoLayout;
      //var_dump($data);
      return $data;
    }
    function CAT_ZOPPAS($detalle_factura_archivo,$TMP_valores_factura){
      //$TMP_valores_factura=array('BRUTO'=>0,'NETO'=>0,'BULTOS'=>0,'INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'','CUST_REF'=>'','VALUE'=>0,'SERIE'=>'');
      //VAR_DUMP($detalle_factura_archivo);
      $readExcel='C:/CBRIS/XML/1-CATALOGO/ZOPPAS.xlsx';
      $PDF_NoLayout='';
      $cont_archivos=0;
      $BRUTO=0;$NETO=0;$BULTOS=0;$INVOICE='';$CAJA='';$SCAC='';$FILE='';$CUST_REF='';$SERIE='';$VALUE=0;
      $inputFileType = PHPExcel_IOFactory::identify($readExcel);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($readExcel);
      $sheetNames = $objPHPExcel->getSheetNames();
      $sheet = $objPHPExcel->setActiveSheetIndexByName('ZOPPAS');
      $c_data = $objPHPExcel->setActiveSheetIndexByName('ZOPPAS')->getHighestRow();
      for($i=2;$i<=$c_data;$i++){
        $INVOICE=$sheet->getCell('A'.$i)->getValue().$sheet->getCell('B'.$i)->getValue();
        $CAJA=$sheet->getCell('F'.$i)->getValue();
        $SCAC=$sheet->getCell('G'.$i)->getValue();
        $BRUTO=$sheet->getCell('C'.$i)->getValue();
        $BULTOS=$sheet->getCell('E'.$i)->getValue();
        //var_dump($BULTOS,);
        $FILE='';//Para los catalogos no se debe enviar  el nombre del archivo
        $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE,'CUST_REF'=>$CUST_REF,'VALUE'=>$VALUE,'SERIE'=>$SERIE);
        $cont_archivos+=1;
      }
      
      $data['detalle_factura_archivo']=$detalle_factura_archivo;
      $data['PDF_NoLayout']=$PDF_NoLayout;
      //var_dump($data);
      return $data;
    }
    function CAT_PANELREY($Contenedor_datos){
      //var_dump('entro a funcion'/*,$Contenedor_datos*/);
      //BUSCA EL PEDIDO EN EL XLS PARA MACHEARLO AL SISTEMA
      $readExcel='C:/CBRIS/XML/1-CATALOGO/PANELREY.xlsx';
      $cont_archivos=0;
      $inputFileType = PHPExcel_IOFactory::identify($readExcel);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($readExcel);
      $sheetNames = $objPHPExcel->getSheetNames();
      $sheet = $objPHPExcel->setActiveSheetIndexByName('PANELREY');
      $c_data = $objPHPExcel->setActiveSheetIndexByName('PANELREY')->getHighestRow();
      $buscar_pedido='';
      for($i=2;$i<=$c_data;$i++){
        $buscar_pedido=$sheet->getCell('A'.$i)->getValue();
        //var_dump($Contenedor_datos->TMP_valores_factura['CUST_REF'],$buscar_pedido);
        if(trim($buscar_pedido)==$Contenedor_datos->TMP_valores_factura['CUST_REF']){
          $Contenedor_datos->TMP_valores_factura['SCAC']=$sheet->getCell('C'.$i)->getCalculatedValue();
          $Contenedor_datos->TMP_valores_factura['CAJA']=' ';
          //$sheet->removeRow($i);
          $sheet->setCellValue('A'.$i, '' );
          $sheet->setCellValue('B'.$i, '' );
          //$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel ,'Excel2007');
          //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          //header('Cache-Control: max-age=0');
          //$objWriter->save($readExcel);

          //SE  REALIZO  ACTUALIZACION A LA CLASE C:\wamp64\www\LaredoCHB\LaredoCHBV2\tools\PHPExcel\PHPExcel\Writer\Excel2007\Worksheet.php
          //LA CONDICION COLUMNAS  MARCABA EL ERROR  DE NO COUNTABLE VALUE, SE  AGREGO VALIDACION IF IS_ARRAY, SI LA FUNCION DE EXCEL FALLA EN OTROS MODULOS  REMOVER LA  VALIDACION EN LA LINEA 768
          $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
          $objWriter->save($readExcel);
          break;
        }
        else if($i+1>$c_data){
          $Contenedor_datos->TMP_valores_factura['CUST_REF']='';
          break;
        }
      }
      return  $Contenedor_datos;
    }
    function CAT_PLANTILLA_XLS($Contenedor_datos){
      $readExcelPath=$Contenedor_datos->Path;
      $readExcelSheet=$Contenedor_datos->Sheet;
      $cont_archivos=0;
      $inputFileType = PHPExcel_IOFactory::identify($readExcelPath);
      $objReader = PHPExcel_IOFactory::createReader($inputFileType);
      $objPHPExcel = $objReader->load($readExcelPath);
      $sheetNames = $objPHPExcel->getSheetNames();
      $sheet = $objPHPExcel->setActiveSheetIndexByName($readExcelSheet);
      $c_data = $objPHPExcel->setActiveSheetIndexByName($readExcelSheet)->getHighestRow();
      for($i=2;$i<=$c_data;$i++){
        $Contenedor_datos->TMP_valores_factura['BRUTO']=$sheet->getCell('H'.$i)->getCalculatedValue();
        $Contenedor_datos->TMP_valores_factura['NETO']=$sheet->getCell('G'.$i)->getCalculatedValue();
        $Contenedor_datos->TMP_valores_factura['BULTOS']=$sheet->getCell('F'.$i)->getCalculatedValue();
        $Contenedor_datos->TMP_valores_factura['INVOICE']=$sheet->getCell('B'.$i)->getCalculatedValue();
        $Contenedor_datos->TMP_valores_factura['CAJA']=$sheet->getCell('E'.$i)->getCalculatedValue();
        $Contenedor_datos->TMP_valores_factura['SCAC']=$sheet->getCell('D'.$i)->getCalculatedValue();
        $Contenedor_datos->TMP_valores_factura['FILE']='';
        $Contenedor_datos->TMP_valores_factura['CUST_REF']=$sheet->getCell('C'.$i)->getCalculatedValue();
        $Contenedor_datos->detalle_factura_archivo[$cont_archivos]=$Contenedor_datos->TMP_valores_factura;
        $cont_archivos++;
      }
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;

    }
    //EL CODIGO DE  REVISION DE PLANTILLAS SE  PUEDE REDUCIR, UNA FUNCION PUEDE  HACER EL CICLO Y OTRA PROCESA LOS  VALORES.

    //$TMP_valores_factura['BRUTO'] 
    //$TMP_valores_factura['SCAC']
    //$TMP_valores_factura['BULTOS']
    //$TMP_valores_factura['INVOICE']
    //$TMP_valores_factura['CAJA']
    //$TMP_valores_factura['VALUE']
    //$TMP_valores_factura['CUST_REF']


    function RevisionCondicionesPDF($Contenedor_datos /*$TMP_valores_factura,$CONDICIONES*/){
      var_dump('Revision de Condiciones PDF:');
      var_export($Contenedor_datos);
      
      $b_condicion=0; 
      //foreach($Contenedor_datos->CONDICIONES AS $concepto){
      //var_dump($Contenedor_datos->FUNCIONES_EXTRACCION );
      foreach($Contenedor_datos->FUNCIONES_EXTRACCION AS $concepto){
        if($Contenedor_datos->TMP_valores_factura[$concepto[0]]==''){
          $b_condicion=1;
          break;
        }
      }
      if($b_condicion==1){
        $Contenedor_datos->PDF_NoLayout.=$Contenedor_datos->TMP_valores_factura['FILE']; //.'<br>';
      }
      else if($b_condicion==0){
        $Contenedor_datos->detalle_factura_archivo[$Contenedor_datos->cont_archivos]=$Contenedor_datos->TMP_valores_factura;
        $Contenedor_datos->cont_archivos+=1;
      }
      return $Contenedor_datos;
    }
    function ProcesarValorExtraido($funciones,$valor){
      $array_valor=explode($funciones[7],$valor);
      if(count($array_valor)>1){
        switch ($funciones[6]){
          case 'SUMAR':
            $valor=$array_valor[0]+$array_valor[$funciones[8]];
            break;
          case 'CONCATENAR':
            $valor=$array_valor[0].$array_valor[$funciones[8]];
            break;
          default:
            $valor=$array_valor[$funciones[8]];
            break;
        }
      }
      return $valor;
    }
    //USAR METODO RECURRENTE
    function ProcesarArchivosPDF($TMP_valores_factura,$Contenedor_datos){
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        //var_dump($Contenedor_datos->Renglon_PDF); //IMPRIME EL VALOR DE LA LINEA SUPERIOR DEL DOCUMENTO/FACTURA
        
        if(strpos($pdftotext['DATA'][$Contenedor_datos->Renglon_PDF],$Contenedor_datos->PalabraClave_PDF)!==false){                         //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          $TEMPLATE_VALORES=$TMP_valores_factura;
          $C_Valores=0;
          $FUNCIONES_EXTRACCION=$Contenedor_datos->FUNCIONES_EXTRACCION;
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array. 
          foreach($pdftotext['DATA'] as $index_linea=>$linea ){                                         // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
            foreach($FUNCIONES_EXTRACCION as $key_valor=>$funciones){                 //RECORRE LAS  FUNCIONES  DE EXTRACCION DE  DATOS,  UNA POR CADA  VALOR A EXTRAER
              //var_dump(count($FUNCIONES_EXTRACCION),$linea,$funciones,$funciones[1]);
              //VAR_DUMP($funciones); //TRAE INVOICE FACTURA/INVOICE FIRST YYY CUST_REF PEDIDO LAST
              if(strpos($linea,$funciones[1])!==false){                                                 //COMIENZA  LA EXTRACCION SI LA PALABRA CLAVE DE LA FUNCION SE ENCUENTRA EN LA LINEA.
                $linea_procesar=$pdftotext['DATA'][$index_linea+$funciones[2]];                         //COLOCA EL PUNTERO ( INDICA LA LINEA A LA QUE SE APLICARAN LOS FILTROS), EL VALOR PUEDE  SER  POSITIVO O NEGATIVO, DEPENDIENDO DEL  VALOR A BUSCAR
                $array_linea_procesar=explode($funciones[3],$linea_procesar);                           //DIVIDE LA LINEA EN BASE ELDIVISOR DECLARADO EN ALA FUNCION                
                if($funciones[4]=='LAST'){                                                              //RECORRE EL ARRAY DE  DERECHA A IZQUIERDA Y SE  MUEVE  N POSICIONES DEFINICDAS POR  RECORRER.
                  $INICIO_EXTRACCION=(count($array_linea_procesar)-1) -  $funciones[5];
                }
                else {
                  $INICIO_EXTRACCION= $funciones[5];                                                    //RECORRE EL ARRAY DE IZQUIERDA A DERECHA Y SE  MUEVE  N POSICIONES DEFINICDAS POR  RECORRER.
                }
                $valor_procesado=trim($array_linea_procesar[$INICIO_EXTRACCION]);
                //VAR_DUMP($valor_procesado); //LLEVO EL VALOR DE FACTURA/INVOICE Y PEDIDO INTERNO No/INTERNAL ORDERNo
                //AGREGAR  FUNCION PARA  OPCIONES ESPECIALES, EN CASO DE  QUE EL  VALOR VENGA PEGADO CON OTRA SECCION DE CARACTERES
                //OPCIONES: SUMAR, CONCATENAR, EXTRAER
                //VAR_DUMP($funciones[6]);
                if($funciones[6]!=''){                                                                  //APLICA  FUNCIONES EXTRA AL VALOR PORCESADO, PARA EL CASO DE  CADENAS CON BASURA.
                  $valor_procesado=ProcesarValorExtraido($funciones,$valor_procesado);  
                }
                $valor_procesado=str_replace(array("$",",","-"),'',$valor_procesado);                   //REMUEVE LOS CARACTERES ESPECIALES DEL  VALOR PROCESADO
                $TEMPLATE_VALORES[$funciones[0]]=trim($valor_procesado);                                //ELIMINA LOS ESPACIOSN EN BLANCO AL INICIO Y AL FINAL DEL  VALOR PROCESADO
                $C_Valores++;
                unset($FUNCIONES_EXTRACCION[$key_valor]);                                               //reducir la  funcion extraida
                //$FUNCIONES_EXTRACCION=array_values($FUNCIONES_EXTRACCION);
                //break;                                                                                  //SI ENCUENTRA UNA COINCIDENCIA ROMPE EL CICLO
                                                                                                          //NOTA: NO SE PUEDE  ROMPER EL  CICLO, ALGUNAS  FUNCIONES USAN LA MISMA LINEA PARA  BUSCAR  MAS DE 1  VALOR
              }
            }
                      
            $FUNCIONES_EXTRACCION=array_values($FUNCIONES_EXTRACCION);
            if($C_Valores>=count($Contenedor_datos->FUNCIONES_EXTRACCION)){
              break;
            }
          }

          $TEMPLATE_VALORES['FILE']=$pdftotext['FILE'];
          //VAR_DUMP($TEMPLATE_VALORES);//SEAGREGA LA DIRECCION DEL PDF AL ARRAY DE 10, QUEDAN 7 VACIOS
          $Contenedor_datos->TMP_valores_factura=$TEMPLATE_VALORES;
          //SE REALIZA  REVISION DE  FUNCIONES ESPECIALES, APLICA AL ARCHIVO EN EVALUACION DE  ESTA VUELTA
          foreach($Contenedor_datos->FUNCIONES_ESPECIALES as $funcion){
            $Contenedor_datos=$funcion[0]($Contenedor_datos);
          }
          //FIN DE  REVISION DE  FUNCIONES ESPECIALES
          //var_dump('Procesar Valor Extraido: ');
          //var_dump($Contenedor_datos); //SE AGREGO EL SCAC Y CAJA, PERO CAJA VA VACIA

          $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
     // if(isset($Contenedor_datos->Renglon_PDF) and)
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      var_dump('Procesar PDF:');
      VAR_export($data);
      return $data;
    }
    function AutoPDF($IDEDIMPCOS){
      //var_dump('entro a procesar  XML PDF '.$IDEDIMPCOS);
      $TMP_valores_factura=array('BRUTO'=>0,'NETO'=>0,'BULTOS'=>0,'INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'','CUST_REF'=>'','VALUE'=>0,'SERIE'=>'');
      $detalle_factura_archivo[]=$TMP_valores_factura; //ALMACENA COMO ARREGLO EL LISTADO DE PDF'S QUE COINCIDEN CON EL TEMPLATE DE VALORES
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $Contenedor_datos->FUNCIONES_EXTRACCION= ModeloopEdi::Mdlautopdf_valores($IDEDIMPCOS);
      $Contenedor_datos->FUNCIONES_ESPECIALES= ModeloopEdi::Mdlautopdf_funciones($IDEDIMPCOS);
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      $PalabraClave_PDF=ModeloopEdi::Mdlautopdf_archivos($IDEDIMPCOS);
      //var_dump( $PalabraClave_PDF,$Contenedor_datos->FUNCIONES_EXTRACCION,$Contenedor_datos->FUNCIONES_ESPECIALES);
      $Contenedor_datos->PalabraClave_PDF=$PalabraClave_PDF[0][0];
      $Contenedor_datos->Renglon_PDF=$PalabraClave_PDF[0][1];
      $data=ProcesarArchivosPDF($TMP_valores_factura,$Contenedor_datos);
      var_dump('Auto PDF:');
      var_export($data);
      return $data;
    }
    function EDI1($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                            //  BSL   - INNOVIA FILMS AMERICA, INC.
      var_dump('entro a procesar  XML normal');
      //NOTA: $TMP_valores_factura PUEDE  MARCAR ERROR YA QUE SUS VALORESE  SE ESTAN EDITANDO  TRAS CADA  VUELTA, PUEDE  DARSE EL CASO DE  QUE NO CONTENGA UN  DATO EN SU CICLO ACTUAL PERO EL ANTERIOR SI LO  TUVIERA
      //NOTA: SE  PUEDE  ENVIAR  EL IMPORTER ID  POR LA FUNCION, SOLO SE  DEBE RECIBIR LA  OPCION DE  SI TIENE PDF, ENVIA EL ID Y CON EL ID EXTRAEMOS LAS CONDICIONES
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('BRUTO','NETO','BULTOS','INVOICE','CAJA');
      //ESTRUCTURA FUNCIONES DE  EXTRACCION DE DATOS EN PDF
      /*
        0 -> CONCEPTO, NOMBRE DEL VALOR A  EXTRAER DEL PDF
        1 -> REFERENCIA, TEXTO PARA  UBICAR LA POSICION DEL  CONCEPTO A  EXTRAER
        2 -> TARGET, UBICACION DEL INDICE DEL ARREGLO DE LINEAS, 0 ES EL INDICE DONDE SE ENCONTRO LA REFERENCIA, EL VALOR  POSITIVO AUMENTA EL INDICE EL NEGATIVO LO DISMINUYE ( SI LOS INDICES SE TRUNCAN MARCA ERROR CORREGIRLO)
        3 -> SPLIT, CADENA O CARACTERER DE  REFERENCIA QUE SE  USARA PARA DIVIDIR LA LINEA Y PASARLA A UN ARREGLO
        4 -> INICIO EXTRACCION, INDICA LA POSICION DEL INDICE DEL ARRAY GENERADO CON LA LINEA FIRTS-> PRIMER  INDICE DEL ARREGLO | LAST -> ULTIMO  INDICE DEL ARREGLO
        5 -> RECORRER, INDICA  CUANTAS POSICIONES DEL  ARRAY DE LA LINEA SE  RECORRERAN EN BASE  EL INICIO DE LA  EXTRACCION
        6 -> PROCESAR, FUNCION PARA PROCESSAR LOS  VALORES  EXTRAIDOS, SE APLICA CUANDO ESTOS VIENEN CONCATENADOS CON OTRO VALOR, APLICABLE SOLO CUANDO ELA RREGLO SEA MAYOR A 0
              OPCIONES: SUMAR, CONCATENAR, EXTRAER
        7 -> SPLIT_PROCESAR, DIVIDE LA CADENA EN  PARTES
        8 -> POSICION A EXTRAER.
            NOTA: LA POSICION  ASIGNADA  SERA EL INDICE A  CONCATENAR O SUMAR RESPECTO AL INDICE 0, EN CASO DE  EXTRAER SE DECLARA EL INDICE A  EXTRAER  
      */
      //$FUNCIONES_EXTRACCION=ModeloopEdi::Mdlautopdf_valores($IDEDIMPCOS);
      //VAR_DUMP($FUNCIONES_EXTRACCION_TEST);
      /*$FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('BRUTO','PESO BRUTO (KG)',0,' ','FIRTS',3,'','',0);
      $FUNCIONES_EXTRACCION[1]=array('NETO','PESO NETO (KG)',0,' ','FIRTS',7,'','',0);
      $FUNCIONES_EXTRACCION[2]=array('CAJA','BOX NO/LICENSE PLATE:',0,' ','FIRTS',3,'','',0);
      $FUNCIONES_EXTRACCION[3]=array('BULTOS','NUMBER OF PALLETS:',0,' ','LAST',0,'','',0);
      $FUNCIONES_EXTRACCION[4]=array('INVOICE','INVOICE:',0,' ','LAST',0,'','',0);*/
      //$Contenedor_datos->FUNCIONES_EXTRACCION= $FUNCIONES_EXTRACCION;
      $Contenedor_datos->FUNCIONES_EXTRACCION= ModeloopEdi::Mdlautopdf_valores($IDEDIMPCOS);
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;

      ////$PalabraClave_PDF='TMP170130QQ9';
      ////$Renglon_PDF=5;
      //$Contenedor_datos->PalabraClave_PDF='TMP170130QQ9';
      //$Contenedor_datos->Renglon_PDF=5;
      $PalabraClave_PDF=ModeloopEdi::Mdlautopdf_archivos($IDEDIMPCOS);
      $Contenedor_datos->PalabraClave_PDF=$PalabraClave_PDF[0][0];
      $Contenedor_datos->Renglon_PDF=$PalabraClave_PDF[0][1];
      /*SE  CAMBIO ESTA SECCION POR UNA FUNCION, SE COMENTARA CON DOBLE // */
      ////foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        ////if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          ////unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array. 
          ////foreach($pdftotext['DATA'] as $index_linea=>$linea ){                                                       // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
            //SE DEBE REALIZAR OTRO CICLO QUE  BUSQUE EN LAS  CONDICIONES
            //SI ENCUENTRA LA REFERENCIA DEBE RECORRER OTRO CICLO PARA  PROCESAR LAS FUNCIONES
            /*if(strpos($linea,'PESO BRUTO (KG)')!==false){
              $pb_i=strpos($linea,')')+1;
              $pb_f=strpos($linea,' PESO NETO');
              $pb=substr($linea,$pb_i,$pb_f - $pb_i);
              $pb=str_replace(' ','',$pb);
              $pb=str_replace(',','',$pb);
              $pn_i=strpos($linea,')',$pb_f)+1;
              $pn_f=strpos($linea,' TOTAL');
              $pn=substr($linea,$pn_i,$pn_f - $pn_i);
              $pn=str_replace(' ','',$pn);
              $pn=str_replace(',','',$pn);
              $TMP_valores_factura['BRUTO']=$pb;
              $TMP_valores_factura['NETO']=$pn;
            }
            else if(strpos($linea,'BOX NO/LICENSE PLATE:')!==false){
              $cj_i=strpos($linea,':')+1;
              $cj_f=strpos($linea,'/');
              $cj=substr($linea,$cj_i,$cj_f-$cj_i);
              $cj=explode('/',$cj);
              $TMP_valores_factura['CAJA']=trim($cj[0]);
            }
            else if(strpos($linea,'NUMBER OF PALLETS:')!==false){
              $bl_i=strpos($linea,':')+1;
              $bl=trim(substr($linea,$bl_i,5));
              $TMP_valores_factura['BULTOS']=$bl;
            }
            else if(strpos($linea,'INVOICE:')!==false){
              $inv_i=strpos($linea,':')+1;
              $inv=substr($linea,$inv_i,strlen($linea)-$inv_i);
              $inv=trim($inv);
              $TMP_valores_factura['INVOICE']=$inv;
            }*/
            
            ////foreach($FUNCIONES_EXTRACCION as $key_valor=>$funciones){
              ////if(strpos($linea,$funciones[1])!==false){
                ////$linea_procesar=$pdftotext['DATA'][$index_linea+$funciones[2]];
                ////$array_linea_procesar=explode($funciones[3],$linea_procesar);
                ////if($funciones[4]=='LAST'){
                ////  $funciones[4]=count($array_linea_procesar)-1;
                ////}
                ////else if($funciones[4]=='FIRTS'){
                ////  $funciones[4]=0;
                ////}
                ////$valor_procesado=trim($array_linea_procesar[$funciones[4]]);
                ////$valor_procesado=str_replace(array("$",",","-"),'',$valor_procesado);
                ////$TMP_valores_factura[$funciones[0]]=trim($valor_procesado);
              ////}
            ////}
          ////}
          ////$TMP_valores_factura['FILE']=$pdftotext['FILE'];
          //var_dump( $TMP_valores_factura);
          ////$Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          ////$Contenedor_datos=RevisionCondiciones($Contenedor_datos);
        ////}
      ////}
      ////$GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      ////$data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;
      ////$data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      $data=ProcesarArchivosPDF($TMP_valores_factura,$Contenedor_datos);
      return $data;
    }
    function EDI22($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  MBRCK - MASTER BRICK CO
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $Contenedor_datos->FUNCIONES_EXTRACCION= ModeloopEdi::Mdlautopdf_valores($IDEDIMPCOS);
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      $PalabraClave_PDF=ModeloopEdi::Mdlautopdf_archivos($IDEDIMPCOS);
      $Contenedor_datos->PalabraClave_PDF=$PalabraClave_PDF[0][0];
      $Contenedor_datos->Renglon_PDF=$PalabraClave_PDF[0][1];
      $data=ProcesarArchivosPDF($TMP_valores_factura,$Contenedor_datos);
      /*REQUIERE HACER UNA  FUNCION PARA PROCESAR LAS  FUNCIONES PERSONALIZADAS */
      return $data;
      
      /*$Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $Contenedor_datos->CONDICIONES=array('BRUTO','BULTOS','INVOICE','CAJA','SCAC');
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;

      $PalabraClave_PDF='LSC941027FL2';
      $Renglon_PDF=1;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Constantes
          $val=explode(' ',$pdftotext['DATA'][2]);
          $TMP_valores_factura['INVOICE']=$val[7].$val[9];
          //Dinamicos
          foreach($pdftotext['DATA'] as $linea ){                                                       // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
            if(strpos($linea,'Observations: ')!==false){
              $val=explode('BLDS',str_replace('Observations: ','',$linea));
              $val[0]=str_replace(' ','',$val[0]);
              $val_2=explode('/',$val[0]);
              if(count($val_2)>1){
                $TMP_valores_factura['BULTOS']=intval($val_2[0])+intval($val_2[1]);
              }
              else{
                $TMP_valores_factura['BULTOS']=$val_2[0];
              }
              $val_3=explode('/',$val[1]);
              $TMP_valores_factura['BRUTO']=trim($val_3[count($val_3)-2]);
            }
            else if(strpos($linea,'PLANA: ')!==false){
              $val=explode('/',$linea);
              $TMP_valores_factura['CAJA']=trim($val[1]);
            }
            else if(strpos($linea,'SCAC:')!==false){
              $val=explode(':',$linea);
              $TMP_valores_factura['SCAC']=trim($val[1]);
            }
          }
          //SECCION CONSTANTE
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          $Contenedor_datos=RevisionCondiciones($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;*/
    }
    /*function EDI23($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  WELLR - ALWAYS FRESH FARMS FRUNATURAL

      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $Contenedor_datos->CONDICIONES=array('INVOICE','BRUTO','CAJA','SCAC');
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;

      $PalabraClave_PDF='FRU1410043E6';
      $Renglon_PDF=3;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Constantes
          $val=explode(' ',$pdftotext['DATA'][8]);
          $TMP_valores_factura['INVOICE']=str_replace('-','',$val[1]);
          //Dinamicos
          foreach($pdftotext['DATA'] as $linea ){                                                       // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
            if(strpos($linea,'PESO TOTAL(KG):')!==false){
              $val=explode(' ',$linea);
              $TMP_valores_factura['BRUTO']=$val[2];
            }
            else if(strpos($linea,'CAJA: ')!==false){
              $val=explode(' ',$linea);
              $TMP_valores_factura['CAJA']=$val[1];
            }
            else if(strpos($linea,'CODIGO ALFA:')!==false){
              $val=explode(' ',$linea);
              $TMP_valores_factura['SCAC']=$val[4];
            }
          }
          //SECCION CONSTANTE
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          $Contenedor_datos=RevisionCondiciones($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }*/
    function EDI25bck($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  PRRG  - FOUNDATION BUILDING MATERIALS
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $Contenedor_datos->CONDICIONES=array('INVOICE','CUST_REF','SCAC','CAJA');
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;

      $PalabraClave_PDF='0004000415';
      $Renglon_PDF=15;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                                        //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Constantes
          $TMP_valores_factura['INVOICE']=$pdftotext['DATA'][1];
          $val=explode('METHOD',$pdftotext['DATA'][3]);
          $TMP_valores_factura['CUST_REF']=trim($val[1]);
          $TMP_valores_factura['SCAC']=CAT_PANELREY($TMP_valores_factura['CUST_REF']);
          $TMP_valores_factura['CAJA']=' ';
          //Dinamicos
          //SECCION CONSTANTE
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    //Esta rutina procesa 2 PDF
    function EDI26($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  WELLR - HORTIGEN PRODUCE SA DE CV
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //Contenedor_datos->CONDICIONES=array('INVOICE','BRUTO','CAJA');

      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('BRUTO');
      $FUNCIONES_EXTRACCION[2]=array('CAJA');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='HORTIGEN PRODUCE S.A DE C.V';
      $Renglon_PDF=1;
      //Depuracion de Archivos
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(count($pdftotext['DATA'])>1){
          if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                        //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
            unset($GLOBALS['FilesPDF'][$key]);                                                          //Al ser  localizado  la factura  se elimina del Array.
            //Inicio Constantes
            $val=explode('HORTIGEN PRODUCE ',$pdftotext['DATA'][2]);
            $TMP_valores_factura['INVOICE']=trim($val[1]);
            //Fin Constantes
            //Inicio Dinamicos
            foreach($pdftotext['DATA'] as $key_line=>$linea ){                                            // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
              if(strpos($linea,'Comentarios')!==false){
                $val=explode(' ',$pdftotext['DATA'][$key_line+1]);
                //$PO=$val[1];
                $MANIFEST=$val[3];
                /* REALIZAR LA  BUSQUEDA DEL  MANIFIESTO MEDIANTE OTRO CICLO FOR*/
                foreach($GLOBALS['FilesPDF'] as  $key_manifest=>$pdftotext_manifest){
                  if(count($pdftotext_manifest['DATA'])>1){
                    if(strpos($pdftotext_manifest['DATA'][1],$MANIFEST)!==false){
                      foreach($pdftotext_manifest['DATA'] as $key_line_manifest=>$linea_manifest ){
                        if(strpos($linea_manifest,'de Caja: ')!==false){
                          $val=explode('Caja: ',$linea_manifest);
                          $TMP_valores_factura['CAJA']=$val[1];
                        }
                        else if(strpos($linea_manifest,'Peso Bruto')!==false){
                          $val=explode(' ',$linea_manifest);
                          $TMP_valores_factura['BRUTO']=str_replace(',','',$val[3]);
                        }
                      }
                      $GLOBALS['FilesPDF'][$key_manifest]['DATA']=array('MANIFEST PROCESSED');
                      break;
                    }
                  }
                }
              }
            }
            //Fin Dinamicos
            //SECCION CONSTANTE
            $TMP_valores_factura['FILE']=$pdftotext['FILE'];
            $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
            $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
          }
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI27($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  PRRG  - GYPSUM MANAGEMENT AND SUPPLY
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CUST_REF');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='0004000057';
      $Renglon_PDF=13;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Constantes
          $TMP_valores_factura['INVOICE']=$pdftotext['DATA'][1];
          $val=explode('METHOD',$pdftotext['DATA'][3]);
          $TMP_valores_factura['CUST_REF']=trim($val[1]);
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;

          $Contenedor_datos=CAT_PANELREY($Contenedor_datos);
          $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI28($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  GRADEN- AMERICAN FUJI SEAL INC
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('INVOICE','CAJA','BULTOS','BRUTO');

      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BULTOS');
      $FUNCIONES_EXTRACCION[3]=array('BRUTO');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='AMERICA FUJI SEAL';
      $Renglon_PDF=16;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                                 //  Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //  Al ser  localizado  la factura  se elimina del Array.
          //Inicio Constantes
          $val=explode(': ',$pdftotext['DATA'][23]);
          $TMP_valores_factura['INVOICE']=str_replace('-','',trim($val[1]));

          /*$val=explode(' ',$pdftotext['DATA'][32]);
          $BRUTO=str_replace(",","",trim($val[1]));*/

          $str=$pdftotext['DATA'][24].$pdftotext['DATA'][25];
          $str=str_replace("truck:","", $str);
          $str=trim(str_replace("Seguro:","", $str));
          $val=explode(' ',$str);
          $TMP_valores_factura['CAJA']=$val[0];
          //Fin Constantes
          //Inicio Dinamicos
          foreach($pdftotext['DATA'] as $linea ){                                                       // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
            if(strpos($linea,'TOTAL ')!==false){
              $val=str_replace(',','',$linea);
              $val=explode(' ',$val);
              $TMP_valores_factura['BULTOS']=$val[1];
              $TMP_valores_factura['BRUTO'] =$val[3];
            }
          }
          //Fin Dinamicos
          //SECCION CONSTANTE
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI31($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  WELLR - HORTINVEST (MEXICO)
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('INVOICE','CAJA','BRUTO');
      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='HME060314D14';
      $Renglon_PDF=1;
      //Depuracion de Archivos
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(count($pdftotext['DATA'])>1){
          if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                                     //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
            unset($GLOBALS['FilesPDF'][$key]);                                                          //Al ser  localizado  la factura  se elimina del Array.
            //Inicio Constantes
            $val=explode(' Folio/Invoice #: ',$pdftotext['DATA'][1]);
            $val_2=explode(' ',$val[1]);
            $TMP_valores_factura['INVOICE']=$val_2[0].intval($val_2[1]);
            $FOLIO=intval($val_2[1]);
            //Fin Constantes
            //Inicio Dinamicos
            foreach($GLOBALS['FilesPDF'] as  $key_ci=>$pdftotext_ci){
              if(count($pdftotext_ci['DATA'])>29){
                if(strpos($pdftotext_ci['DATA'][29],strval($FOLIO))!==false){
                  $val=explode(' ',$pdftotext_ci['DATA'][39]);
                  $TMP_valores_factura['BRUTO']=str_replace(',','',$val[1]);
                  $val=explode(' ',$pdftotext_ci['DATA'][48]);
                  $TMP_valores_factura['CAJA']=$val[4];
                  $GLOBALS['FilesPDF'][$key_ci]['DATA']=array('CI PROCESSED');
                  break;
                }
              }
            }
            //Fin Dinamicos
            //SECCION CONSTANTE
            $TMP_valores_factura['FILE']=$pdftotext['FILE'];
            $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
            $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
          }
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    //Esta  rutina procesa XLS
    //NO SE MODIFICO LA  QUE MANEJA  XLS
    function EDI32($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  WELLN - MEGA FRESCOS DEL BAJIO
      //VAR_DUMP($detalle_factura_archivo);
      $path='C:/CBRIS/XML/WELLDEX_NORTE';
      $PDF_NoLayout='';
      $cont_archivos=0;
      $archivos_excel=glob($path."/*.{xlsx,XLSX,XLS,xls}", GLOB_BRACE);
      foreach($archivos_excel as  $readExcel){
        $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';$CUST_REF='';
        $inputFileType = PHPExcel_IOFactory::identify($readExcel);
	      $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	      $objPHPExcel = $objReader->load($readExcel);
        $sheetNames = $objPHPExcel->getSheetNames();
        if(in_array('Packing List',$sheetNames)){
          $sheet = $objPHPExcel->setActiveSheetIndexByName('Packing List');
          if(strpos($sheet->getCell("A1")->getValue(),'Mastronardi Produce Packing list')!==false){
            $INVOICE=strval($sheet->getCell("C9")->getValue());
            $CAJA=$sheet->getCell("G6")->getValue();
          }
          else{
            $PDF_NoLayout.=$readExcel.'<br>';
          } 
        }
        $FILE=$readExcel;
        $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE,'CUST_REF'=>$CUST_REF);
        $cont_archivos+=1;
      }
      $data['detalle_factura_archivo']=$detalle_factura_archivo;
      $data['PDF_NoLayout']=$PDF_NoLayout;
      return $data;
    }
    function EDI33($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  D&G   - MERIT DESIGN (MUEBLES PIAVE)
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('INVOICE','CAJA','BRUTO');

      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='271680567';
      $Renglon_PDF=8;
      //Depuracion de Archivos
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(count($pdftotext['DATA'])>1){
          if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                        //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
            unset($GLOBALS['FilesPDF'][$key]);                                                          //Al ser  localizado  la factura  se elimina del Array.
            //Inicio Constantes
            $val=explode(' ',$pdftotext['DATA'][9]);
            $val_2=explode('/',$val[1]);
            $TMP_valores_factura['INVOICE']=trim($val_2[0].'/'.$val_2[1].'/'.$val_2[2].'/'.intval($val_2[3]));
            $val=explode(' ',$pdftotext['DATA'][12]);
            $TMP_valores_factura['CAJA']=$val[count($val)-1];
            //Fin Constantes
            //Inicio Dinamicos
            foreach($pdftotext['DATA'] as $key_line=>$linea ){                                            // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
              if(strpos($linea,'Origen:')!==false){
                $val=explode(' ',$pdftotext['DATA'][$key_line+1]);
                $PO=$val[2];
                /* REALIZAR LA  BUSQUEDA DEl PO MEDIANTE OTRO CICLO FOR*/
                foreach($GLOBALS['FilesPDF'] as  $key_po=>$pdftotext_po){
                  if(count($pdftotext_po['DATA'])>1){
                    if(strpos($pdftotext_po['DATA'][12],$PO)!==false){
                      $val=explode(' ',$pdftotext_po['DATA'][8]);
                      $TMP_valores_factura['BRUTO']=str_replace(',','',$val[3]);
                      $GLOBALS['FilesPDF'][$key_po]['DATA']=array('PO PROCESSED');
                      break;
                    }
                  }
                }
              }
            }
            //Fin Dinamicos
            //SECCION CONSTANTE
            $TMP_valores_factura['FILE']=$pdftotext['FILE'];
            $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
            $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
          }
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI34($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  D&G   - URBAN ROADS
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('INVOICE','BRUTO','CAJA');

      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='MAL030314H84';
      $Renglon_PDF=6;
      //Depuracion de Archivos
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(count($pdftotext['DATA'])>1){
          if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                        //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
            unset($GLOBALS['FilesPDF'][$key]);                                                          //Al ser  localizado  la factura  se elimina del Array.
            //Inicio Constantes
            $TMP_valores_factura['INVOICE']=str_replace(' ','',$pdftotext['DATA'][2]);
            $val=explode('Orden de compra ',$pdftotext['DATA'][16]);
            $PO=trim($val[1]);
            //Fin Constantes
            //Inicio Dinamicos
            foreach($GLOBALS['FilesPDF'] as  $key_pk=>$pdftotext_pk){
              if(count($pdftotext_pk['DATA'])>1){
                if(strpos($pdftotext_pk['DATA'][2],$PO)!==false){
                  foreach($pdftotext_pk['DATA'] as $key_linea=>$linea_pk){
                    if(strpos($linea_pk,'TOTAL ')!==false){
                      $val=explode(' ',$linea_pk);
                      $TMP_valores_factura['BRUTO'] =str_replace(',','',$val[4]);
                    }
                    else if(strpos($linea_pk,'BOX: ')!==false){
                      $val=explode(' ',$linea_pk);
                      $TMP_valores_factura['CAJA']=$val[1];
                    }
                  }
                  $GLOBALS['FilesPDF'][$key_pk]['DATA']=array('PO PROCESSED');
                  break;
                }
              }
            }
            //Fin Dinamicos
            //SECCION CONSTANTE
            $TMP_valores_factura['FILE']=$pdftotext['FILE'];
            $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
            $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
          }
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI35($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  D&G   - MERIT DESIGN (EXPORT MOBILLY)
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $Contenedor_datos->CONDICIONES=array('INVOICE','BRUTO','SCAC','CAJA');

      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $FUNCIONES_EXTRACCION[3]=array('SCAC');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;


      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='EMM200605LG7';
      $Renglon_PDF=1;
      //Depuracion de Archivos
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(count($pdftotext['DATA'])>1){
          if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                        //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
            unset($GLOBALS['FilesPDF'][$key]);                                                          //Al ser  localizado  la factura  se elimina del Array.
            //Inicio Constantes
            $val=explode(' ',$pdftotext['DATA'][0]);
            $TMP_valores_factura['INVOICE']=trim($val[8]);
            $val=explode(' ',$pdftotext['DATA'][11]);
            $FOLIO=$val[6];
            //Fin Constantes
            //Inicio Dinamicos
            foreach($GLOBALS['FilesPDF'] as  $key_ficha=>$pdftotext_ficha){
              if(count($pdftotext_ficha['DATA'])>1){
                if(strpos($pdftotext_ficha['DATA'][25],$FOLIO)!==false){
                  $val=explode(' ',$pdftotext_ficha['DATA'][6]);
                  $REFERENCIA=$val[count($val)-1];
                  $val=explode(' ',$pdftotext_ficha['DATA'][14]);
                  $TMP_valores_factura['BRUTO'] =str_replace(',','',$val[1]);
                  $val=explode(' ',$pdftotext_ficha['DATA'][count($pdftotext_ficha['DATA'])-5]);
                  $TMP_valores_factura['SCAC']=str_replace(',','',$val[1]);
                  $GLOBALS['FilesPDF'][$key_ficha]['DATA']=array('FICHA PROCESSED');
                  //BUSQUEDA EN  CARTA DE INSTRUCCIONES
                  foreach($GLOBALS['FilesPDF'] as  $key_ci=>$pdftotext_ci){
                    if(count($pdftotext_ci['DATA'])>1){
                      if(strpos($pdftotext_ci['DATA'][3],$REFERENCIA)!==false){
                        $val=explode(' ',$pdftotext_ci['DATA'][12]);
                        $TMP_valores_factura['CAJA']=str_replace(',','',$val[1]);
                        $GLOBALS['FilesPDF'][$key_ci]['DATA']=array('CI PROCESSED');
                        break;
                      }
                    }
                  }
                  break;
                }
              }
            }
            //Fin Dinamicos
            //SECCION CONSTANTE
            $TMP_valores_factura['FILE']=$pdftotext['FILE'];
            $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
            $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
          }
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI36($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  WELLN - KINUGAWA(SUBARU)
      return  CAT_KINUGAWA($detalle_factura_archivo);
    }
    function EDI37($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  WELLN - KINUGAWA(NISSAN)
      return  CAT_KINUGAWA($detalle_factura_archivo);
    }
    function EDI38($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  WELLN - TEPRO
      return  CAT_KINUGAWA($detalle_factura_archivo);
    }

    //FUNCION CON 2 ARCHIVOS / CONTIENE UNA  FUNCION PARA ELIMINAR TODOS LOS CARACTERES ESPECIALES DE UNA CADENA
    function EDI40($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  GERBER - GERBER
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $Contenedor_datos->CONDICIONES=array('INVOICE','CAJA','SCAC','BRUTO','VALUE');
      
      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $FUNCIONES_EXTRACCION[3]=array('SCAC');
      $FUNCIONES_EXTRACCION[4]=array('VALUE');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='Globe Union Industrial Corp';
      $Renglon_PDF=12;
      //Depuracion de Archivos
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(count($pdftotext['DATA'])>1){
          if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                        //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
            unset($GLOBALS['FilesPDF'][$key]);                                                          //Al ser  localizado  la factura  se elimina del Array.
            //Inicio Constantes
            $val=explode(' ',$pdftotext['DATA'][2]);
            $TMP_valores_factura['INVOICE']=substr($val[count($val)-1],1);
            $FOLIO=substr($val[count($val)-1],1);
            //Fin Constantes
            //Inicio Dinamicos
            foreach($pdftotext['DATA'] as  $key_line=>$pdftotext_line){
              if(count($pdftotext['DATA'])>1){
                if(strpos($pdftotext_line,'Sub Total:')!==false){
                  $val=explode(' ',$pdftotext_line);
                  $TMP_valores_factura['VALUE']=str_replace(array("$", ","),'',$val[count($val)-1]);
                }
                if(strpos($pdftotext_line,'Peso:')!==false){
                  $val=explode(' ',$pdftotext_line);
                  $TMP_valores_factura['BRUTO']=str_replace(',','',$val[count($val)-3]);
                }
              }
            }
            //Fin Dinamicos
            foreach($GLOBALS['FilesPDF'] as  $key_pro=>$pdftotext_pro){
              if(count($pdftotext_pro['DATA'])>1){
                if(strpos($pdftotext_pro['DATA'][0],$FOLIO)!==false){
                  $val=explode(' ',$pdftotext_pro['DATA'][2]);
                  $TMP_valores_factura['CAJA']=$val[count($val)-1];
                  $val=explode(' ',$pdftotext_pro['DATA'][5]);
                  $TMP_valores_factura['SCAC']=$val[1];
                  $GLOBALS['FilesPDF'][$key_pro]['DATA']=array('PROFORMA PROCESSED');
                  break;
                }
              }
            }
            //SECCION CONSTANTE
            $TMP_valores_factura['FILE']=$pdftotext['FILE'];
            $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
            $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
          }
        }
      }
      //VAR_DUMP($Contenedor_datos);
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI93($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  D&G   - AMERICAN FURNITURE
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('INVOICE','BRUTO','SCAC','CAJA');
      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $FUNCIONES_EXTRACCION[3]=array('SCAC');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='MME9811242C1';
      $Renglon_PDF=7;
      //Depuracion de Archivos
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Inicio Constantes
          $val=explode(' ',$pdftotext['DATA'][4]);
          $TMP_valores_factura['INVOICE']=$val[0].$val[1];
          $val=explode(' ',$pdftotext['DATA'][12]);
          $folio_fiscal=$val[count($val)-1];
          //Fin Constantes
          //Inicio Dinamicos
          //Fin Dinamicos
          /* REALIZAR LA  BUSQUEDA DEL  FOLIO FISCAL MEDIANTE OTRO CICLO FOR*/
          foreach($GLOBALS['FilesPDF'] as  $key_ficha=>$pdftotext_ficha){
            if(count($pdftotext_ficha['DATA'])>1){
              if(strpos($pdftotext_ficha['DATA'][25],$folio_fiscal)!==false){
                $val=explode(' ',$pdftotext_ficha['DATA'][14]);
                $TMP_valores_factura['BRUTO'] =str_replace(',','',$val[1]);
                foreach($pdftotext_ficha['DATA'] as $key_line_ficha=>$linea_ficha){
                  if(strpos($linea_ficha,'CAJA:')!==false){
                    $val=explode(' ',$pdftotext_ficha['DATA'][$key_line_ficha+2]);
                    $TMP_valores_factura['SCAC']=$val[1];
                    $TMP_valores_factura['CAJA']=$val[count($val)-1];
                  }
                }
                $GLOBALS['FilesPDF'][$key_ficha]['DATA']=array('MANIFEST PROCESSED');
                break;
              }
            }
          }
          /*FIN DE  SEGUNDO CICLO */
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    /*function EDI108($detalle_factura_archivo,$TMP_valores_factura){                                                          //  KASAI  - KASAI
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      $Contenedor_datos->CONDICIONES=array('INVOICE','CAJA','BRUTO');
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='KASAI MEXICANA, S.A. DE C.V.';
      $Renglon_PDF=1;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Inicio Constantes
          $val=explode(' ',$pdftotext['DATA'][1]);
          $TMP_valores_factura['INVOICE']=str_replace('-','',$val[count($val)-1]);
          $val=explode(' ',$pdftotext['DATA'][4]);
          $TMP_valores_factura['CAJA']=$val[count($val)-1];
          //Fin Constantes
          //Inicio Dinamicos
          //Fin Dinamicos
          foreach($pdftotext['DATA'] as $linea ){                                                       // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
            if(strpos($linea,'TOTAL')!==false ){
              $val=explode(' ',$linea);
              if(count($val)==6){
                $TMP_valores_factura['BRUTO'] =$val[1];
              }
            }
          }
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          $Contenedor_datos=RevisionCondiciones($Contenedor_datos);
        }
      }
      var_dump($Contenedor_datos);
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }*/
    function EDI109($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                           //  D&G   - AMERICAN FURNITURE NERO LUPO
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('INVOICE','BRUTO','SCAC','CAJA');
      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $FUNCIONES_EXTRACCION[3]=array('SCAC');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='NLU100611QR7';
      $Renglon_PDF=5;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Inicio Constantes
          $val=explode(' ',$pdftotext['DATA'][2]);
          $TMP_valores_factura['INVOICE']=count($val)-1;
          $val=explode(' ',$pdftotext['DATA'][13]);
          $orden_compra=$val[count($val)-1];
          //Fin Constantes
          //Inicio Dinamicos
          //Fin Dinamicos
          /* REALIZAR LA  BUSQUEDA DEL  FOLIO FISCAL MEDIANTE OTRO CICLO FOR*/
          foreach($GLOBALS['FilesPDF'] as  $key_ficha=>$pdftotext_ficha){
            if(count($pdftotext_ficha['DATA'])>1){
              if(strpos($pdftotext_ficha['DATA'][25],$orden_compra)!==false){
                $val=explode(' ',$pdftotext_ficha['DATA'][14]);
                $TMP_valores_factura['BRUTO'] =str_replace(',','',$val[1]);
                foreach($pdftotext_ficha['DATA'] as $key_line_ficha=>$linea_ficha){
                  if(strpos($linea_ficha,'CAJA:')!==false){
                    $val=explode(' ',$pdftotext_ficha['DATA'][$key_line_ficha+2]);
                    $TMP_valores_factura['SCAC']=$val[1];
                    $TMP_valores_factura['CAJA']=$val[count($val)-1];
                  }
                }
                $GLOBALS['FilesPDF'][$key_ficha]['DATA']=array('MANIFEST PROCESSED');
                break;
              }
            }
          }
          /*FIN DE  SEGUNDO CICLO */
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI124($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                          //  TUPY  - TELAMON 
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('INVOICE','CAJA','BRUTO');
      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='TELAMON INTERNATIONAL CORP';
      $Renglon_PDF=14;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Inicio Constantes
          $val=explode(' ',$pdftotext['DATA'][4]);
          $TMP_valores_factura['INVOICE']=str_replace('-','',$val[count($val)-1]);
          //Fin Constantes
          //Inicio Dinamicos
          //Fin Dinamicos
          foreach($pdftotext['DATA'] as $id_lin=>$linea ){                                                       // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
            if(strpos($linea,'Sello Clave Pedimento')!==false ){
              $val=explode(' ',$pdftotext['DATA'][$id_lin+1]);
              $TMP_valores_factura['CAJA'] =$val[0];
            }
            else if(strpos($linea,'Tipo de Cambio Vendor')!==false ){
              $val=explode(' ',$pdftotext['DATA'][$id_lin+1]);
              $TMP_valores_factura['BRUTO']=$val[count($val)-1];
            }
          }
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI125($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                          //  TUPY  - WGS
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->cont_archivos=0;
      //$Contenedor_datos->CONDICIONES=array('INVOICE','CAJA','BRUTO');
      $FUNCIONES_EXTRACCION=[];
      $FUNCIONES_EXTRACCION[0]=array('INVOICE');
      $FUNCIONES_EXTRACCION[1]=array('CAJA');
      $FUNCIONES_EXTRACCION[2]=array('BRUTO');
      $Contenedor_datos->FUNCIONES_EXTRACCION=$FUNCIONES_EXTRACCION;

      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      
      $PalabraClave_PDF='WGS GLOBAL SERVICES LC';
      $Renglon_PDF=14;

      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        if(strpos($pdftotext['DATA'][$Renglon_PDF],$PalabraClave_PDF)!==false){                       //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
          unset($GLOBALS['FilesPDF'][$key]);                                                            //Al ser  localizado  la factura  se elimina del Array.
          //Inicio Constantes
          $val=explode(' ',$pdftotext['DATA'][4]);
          $TMP_valores_factura['INVOICE']=str_replace('-','',$val[count($val)-1]);
          //Fin Constantes
          //Inicio Dinamicos
          //Fin Dinamicos
          foreach($pdftotext['DATA'] as $id_lin=>$linea ){                                                       // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
            if(strpos($linea,'Sello Digital del CFDI')!==false ){
              $val=explode(' ',$pdftotext['DATA'][$id_lin-1]);
              $TMP_valores_factura['CAJA'] =$val[0];
            }
            else if(strpos($linea,'Tipo de Cambio Vendor')!==false ){
              $val=explode(' ',$pdftotext['DATA'][$id_lin+1]);
              $TMP_valores_factura['BRUTO']=$val[count($val)-1];
            }
          }
          $TMP_valores_factura['FILE']=$pdftotext['FILE'];
          $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
          //var_dump($Contenedor_datos);
          $Contenedor_datos=RevisionCondicionesPDF($Contenedor_datos);
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$Contenedor_datos->detalle_factura_archivo;                      //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$Contenedor_datos->PDF_NoLayout;
      return $data;
    }
    function EDI159($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                              //  WELLN - ZOPPAS
      return  CAT_ZOPPAS($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS);
    }
    function EDI166($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS){                                                          //  ADE LICONA  - FRUTIVA
      $Contenedor_datos=new stdClass();
      $Contenedor_datos->PDF_NoLayout='';
      $Contenedor_datos->TMP_valores_factura=$TMP_valores_factura;
      $Contenedor_datos->detalle_factura_archivo=$detalle_factura_archivo;
      $Contenedor_datos->Path='C:/CBRIS/XML/1-CATALOGO/FRUTIVA.xlsx';
      $Contenedor_datos->Sheet='FRUTIVA';
      $EDI166=CAT_PLANTILLA_XLS($Contenedor_datos);
      $data['detalle_factura_archivo']=$EDI166['detalle_factura_archivo'];                      
      $data['PDF_NoLayout']=$EDI166['PDF_NoLayout'];
      return $data;
    }

    //////////////////////////////////          EN REPARACION         ///////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // OMNISOURCE                       - PDF
    function EDI2T($ruta){
      $data=[];
      $cont_archivos=0;
      $archivos=glob($ruta."/*.{pdf,PDF}", GLOB_BRACE);
      if(sizeof($archivos)!=0){
        $detalle_factura_archivo[]=array('BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
        $PDF_NoLayout='';
        $Error='';
        /*foreach($archivos as  $pdftotext){
          $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';
          $cmd = '"C:/xpdf/pdftotext.exe -simple2 '.$pdftotext.'"';
          shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
          $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
          $cont_lin=0;
          while(!feof($texto)){
            $linea=fgets($texto);
            $linea=preg_replace('[\n|\r|\n\r]','',$linea);
            //Rompe el ciclo si la primer linea del PDF no contiene el nobre del importador
            if($cont_lin==0){
              if(strpos($linea,'OMNISOURCE MEXICO, S.A. DE C.V.')==false){
                $PDF_NoLayout.=$pdftotext.'<br>';
                break;
              }
            }
            if($cont_lin==1){
              $line_inv_split=explode(' ',$linea);
              $INVOICE=$line_inv_split[5].ltrim($line_inv_split[6],'0');
            }
            if(strpos($linea,'Caja:')!==false){
              $line_box_indexI=strpos($linea,'Caja:')+1;
              $line_box_indexF=strpos($linea,'placas:');
              $line_box=substr($linea,$line_box_indexI,$line_box_indexF - $line_box_indexI);
              $line_box=explode(' ',$line_box);
              $CAJA=str_replace(',','',$line_box[1]);
            }
            $cont_lin+=1;
          }
          // S epuede  identificar si cumple con el  Layou pero no si  tiene el mismo Layout esperado
          $FILE=$pdftotext;
          $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE);
          $cont_archivos+=1;
        }*/

        $Files_INV=array();
        var_dump($Files_INV);
        $cont_files=0;
        foreach($archivos as  $pdftotext){
          $cmd = '"C:/xpdf/pdftotext.exe -simple2 '.$pdftotext.'"';
          shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
          $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
          $cont_lin=0;
          while(!feof($texto)){
            $linea=fgets($texto);
            $linea=preg_replace('[\n|\r|\n\r]','',$linea);
            if($cont_lin==0){
              if(strpos($linea,'OMNISOURCE MEXICO')===false){
                $PDF_NoLayout.=$pdftotext.'<br>';
                break;
              }
            }
            $Files_INV[$cont_files]['ARRAY'][$cont_lin]=$linea;
            $Files_INV[$cont_files]['FILE']=$pdftotext;
            $cont_lin+=1;
          }
          if($cont_lin>0){
            $cont_files+=1;
          }
        }

        //Recorre el Array si  existen valores
        var_dump(empty($Files_INV));
        var_dump(isset($Files_INV));
        var_dump($Files_INV);
        if(!empty($Files_INV)){
          foreach($Files_INV as $INV){
            $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';
            $c=count($INV['ARRAY']);
            for($i=0;$i<=$c-1;$i++){
              if(strpos($INV['ARRAY'][$i],'Factura-CFDI')!==false){
                $exp_inv=explode(' ',$INV['ARRAY'][$i+1]);
                $c_exp_inv=count($exp_inv);
                $INVOICE=$exp_inv[$c_exp_inv-1];
                $i++;
                continue;
              }
              if(strpos($INV['ARRAY'][$i],'Caja/Container: ')!==false){
                $exp_caja=explode('Caja/Container: ',$INV['ARRAY'][$i]);
                $CAJA=str_replace(',','',$exp_caja[1]);
                $query=ModeloopEdi::MdlAMS($CAJA);
                if(!empty($query)){
                  $BRUTO=$query[0]['WEIGHT'];
                  $SCAC=$query[0]['SCAC'];
                }
                else{
                  $Error.=' Contenedor Inexistente: '.$CAJA.'<br>';
                  $INVOICE='';$CAJA='';
                }
                break;
              }
              if($i+1>=$c-1){
                $Error.=' Factura Sin Caja: '.$INV['FILE'].'<br>';
                $INVOICE='';$CAJA='';
                break;
              }
            }
            $FILE=$INV['FILE'];
            $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE); 
            $cont_archivos+=1;
          }
        }
        $data['PDF_NoLayout']=$PDF_NoLayout;
        $data['Error']=$Error;
        $data['detalle_factura_archivo']=$detalle_factura_archivo;
        return $data;
      }
      return 'NoArchivos';
    }
    // ENVASES UNIVERSALES DE MEXICO    - PDF X 2
    function EDI3T(){
      //DESDE EL LLAMADO SE EVALUA SI EXISTEN ARCHIVOS
      $cont_archivos=0;
      $detalle_factura_archivo[]=array('BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
      $PDF_NoLayout='';
      //Se  requiere  recorrer  todo el arreglo de FILES, para  identificar los  archivos que le pertenecen a este importador
      foreach($archivos as $pdftotext){                                                                                         //Se  requieren 2  ciclos para las vueltas, y tras cada coincidencia  descartarlo para evitar reduncia innecesaria
          if(!in_array($pdftotext, $PkList)){                                                                                   //Agregar  condicion para identificar las  facturas de  este Importador                                                                          
            $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';                                               //Inicializacion de  variables para
                                                                                                                                //Marcar el Archivo como perteneciente al  importador      
            while(!feof( $texto)){                                                                                              //En caso de  utiliazr 2 archivos
              $linea=fgets($texto);
              if(strpos($linea,'ENVASES UNIVERSALES DE MEXICO S.A. P.I. DE C.V.')!==false){
                $inv=str_replace('MUEBLES PIAVE S.A. DE C.V Comercio Exterior - Ingreso','',$linea);
                $inv=str_replace(' ','',$inv);
                $inv=preg_replace('[\n|\r|\n\r]','',$inv);
                $INVOICE=$inv;
                //Busca la caja, al manejar  2 pdf  uno trae el peso y otro trae la caja
                $PackingList=glob($path."/*.{pdf,PDF}", GLOB_BRACE);
                if(sizeof($PackingList)!=0){
                  foreach( $PackingList as  $PackingListtotext){
                    shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$PackingListtotext.'"');
                    $PKLtexto=fopen(str_ireplace('.pdf','.txt',$PackingListtotext),'r');
                    while(!feof($PKLtexto)){
                      $PKLLine=fgets($PKLtexto);
                      if(strpos($PKLLine,'FACTURA NO:')!==false && strpos($PKLLine,$INVOICE)!==false){
                        $PKLlineaDatosExtra=substr($PKLLine,0,strpos( $PKLLine,'BRUTO TOTAL:'));
                        $pb=str_replace($PKLlineaDatosExtra,'', $PKLLine);
                        $pb=str_replace('BRUTO TOTAL:','',$pb);
                        $pb=explode(' ',$pb);
                        //La cadena  inicia con espacio en blanco,  al realizar el explode  usa el registro 0 para colocar ''
                        $BRUTO=$pb[1];
                        unlink($PackingListtotext);
                        array_push($PkList,$PackingListtotext);
                        break(2);
                      }
                    }
                  }
                }
              }
              else if(strpos($linea,'NUMERO DE REMOLQUE O CAJA')!==false){
                $lineaDatosExtra=substr($linea,0,strpos($linea,'NUMERO DE REMOLQUE O CAJA'));
                $CAJA=str_replace($lineaDatosExtra,'',$linea);
                $CAJA=str_replace('NUMERO DE REMOLQUE O CAJA','',$CAJA);
                $CAJA=str_replace(' ','',$CAJA);
                $CAJA=preg_replace('[\n|\r|\n\r]','',$CAJA);
              }
            }
            //SI LOS CAMPOS VIENEN  VACIOS, EL ARCHIVO NO  TIENE LA  ESTRUCTURA DEL PDF
            $FILE=$pdftotext;
            $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE);
            $cont_archivos+=1;
          }
      }
      $data['detalle_factura_archivo']=$detalle_factura_archivo;
      $data['PDF_NoLayout']=$PDF_NoLayout;
      return $data;
      
    }
    // ALWAYS FRESH FARM                - PDF
    function EDI9T($ruta){
      $path=$ruta;
      $cont_archivos=0;
      $archivos=glob($path."/*.{pdf,PDF}", GLOB_BRACE);
      if(sizeof($archivos)!=0){
        $detalle_factura_archivo[]=array('BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
        $PDF_NoLayout='';
        //$BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$FILE='';
        foreach($archivos as  $pdftotext){
          $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';
          $cmd = '"C:/xpdf/pdftotext.exe -simple2 '.$pdftotext.'"';
          shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
          $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
          $cont_lin=0;
          while(!feof( $texto)){
            $linea=fgets($texto);
            if(strpos($linea,' AWF')!==false){
              $pf=explode(' ',$linea);
              //$detalle_factura_archivo[$cont_archivos]['PROFORMA']=$pf[4];
              $archivos_excel=glob($path."/*.{xlsx,XLSX,XLS,xls}", GLOB_BRACE);
              foreach($archivos_excel as  $readExcel){
                $inputFileType = PHPExcel_IOFactory::identify($readExcel);
	              $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	              $objPHPExcel = $objReader->load($readExcel);
                $sheetNames = $objPHPExcel->getSheetNames();
                if(in_array('PackingList',$sheetNames)){
                  $sheet = $objPHPExcel->setActiveSheetIndexByName('PackingList');
                  if($pf[4]==$sheet->getCell("O3")->getValue()){
                    //$detalle_factura_archivo[$cont_archivos]['CAJA']=$sheet->getCell("O20")->getValue();
                    $CAJA=$sheet->getCell("O20")->getValue();
                    unlink($readExcel);
                    break;
                  } 
                }
              }
            }
            else if($cont_lin==1){
              //Agregar  Aqui condicion de  si el PDF cumple la estructura
              $INVOICE=str_replace(' ',"",$linea);
            }
            $cont_lin+=1;
          }
          //SI LOS CAMPOS VIENEN  VACIOS, EL ARCHIVO NO  TIENE LA  ESTRUCTURA DEL PDF
          if($INVOICE=='' ||  $CAJA==''){
            $PDF_NoLayout.=$pdftotext.'<br>';
          }
          $FILE=$pdftotext;
          $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE);
          $cont_archivos+=1;
        }
        $data['detalle_factura_archivo']=$detalle_factura_archivo;
        $data['PDF_NoLayout']=$PDF_NoLayout;
        return $data;
      }
      return 'NoArchivos';
    }
    // INVERAGRO - MASTRONARDI          - XLS
    function EDI12T($ruta){
      $path=$ruta;
      $cont_archivos=0;
      $archivos_excel=glob($path."/*.{xlsx,XLSX,XLS,xls}", GLOB_BRACE);
      if(sizeof($archivos_excel)!=0){
        $detalle_factura_archivo[]=array('BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
        $PDF_NoLayout='';
        //$BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$FILE='';
        foreach($archivos_excel as  $readExcel){
          $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';
          $inputFileType = PHPExcel_IOFactory::identify($readExcel);
	        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	        $objPHPExcel = $objReader->load($readExcel);
          $sheetNames = $objPHPExcel->getSheetNames();
          if(in_array('Packing list',$sheetNames)){
            $sheet = $objPHPExcel->setActiveSheetIndexByName('Packing list');
            if($sheet->getCell("A1")->getValue()=="Mastronardi Produce Packing list"){
              $INVOICE=$sheet->getCell("C9")->getValue();
              $CAJA=$sheet->getCell("G6")->getValue();
            }
            else{
              $PDF_NoLayout.=$pdftotext.'<br>';
            } 
          } 
          //SI LOS CAMPOS VIENEN  VACIOS, EL ARCHIVO NO  TIENE LA  ESTRUCTURA DEL PDF
          $FILE=$readExcel;
          $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE);
          $cont_archivos+=1;
        }
        $data['detalle_factura_archivo']=$detalle_factura_archivo;
        $data['PDF_NoLayout']=$PDF_NoLayout;
        return $data;
      }
      return $detalle_factura_archivo;
    }
    // MERIT DESIGN STUDIO INC          - PDF
    function EDI14T($ruta){
      $path=$ruta;
      $cont_archivos=0;
      $archivos=glob($path."/*.{pdf,PDF}", GLOB_BRACE);
      $PkList=[];
      if(sizeof($archivos)!=0){
        $detalle_factura_archivo[]=array('BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
        $PDF_NoLayout='';
        foreach($archivos as $pdftotext){
          if(!in_array($pdftotext, $PkList)){
            $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';
            shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
            $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
            while(!feof( $texto)){
              $linea=fgets($texto);
              if(strpos($linea,'MUEBLES PIAVE S.A. DE C.V Comercio Exterior - Ingreso')!==false){
                $inv=str_replace('MUEBLES PIAVE S.A. DE C.V Comercio Exterior - Ingreso','',$linea);
                $inv=str_replace(' ','',$inv);
                $inv=preg_replace('[\n|\r|\n\r]','',$inv);
                $INVOICE=$inv;
                //Busca la caja, al manejar  2 pdf  uno trae el peso y otro trae la caja
                $PackingList=glob($path."/*.{pdf,PDF}", GLOB_BRACE);
                if(sizeof($PackingList)!=0){
                  foreach( $PackingList as  $PackingListtotext){
                    shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$PackingListtotext.'"');
                    $PKLtexto=fopen(str_ireplace('.pdf','.txt',$PackingListtotext),'r');
                    while(!feof($PKLtexto)){
                      $PKLLine=fgets($PKLtexto);
                      if(strpos($PKLLine,'FACTURA NO:')!==false && strpos($PKLLine,$INVOICE)!==false){
                        $PKLlineaDatosExtra=substr($PKLLine,0,strpos( $PKLLine,'BRUTO TOTAL:'));
                        $pb=str_replace($PKLlineaDatosExtra,'', $PKLLine);
                        $pb=str_replace('BRUTO TOTAL:','',$pb);
                        $pb=explode(' ',$pb);
                        //La cadena  inicia con espacio en blanco,  al realizar el explode  usa el registro 0 para colocar ''
                        $BRUTO=$pb[1];
                        unlink($PackingListtotext);
                        array_push($PkList,$PackingListtotext);
                        break(2);
                      }
                    }
                  }
                }
              }
              else if(strpos($linea,'NUMERO DE REMOLQUE O CAJA')!==false){
                $lineaDatosExtra=substr($linea,0,strpos($linea,'NUMERO DE REMOLQUE O CAJA'));
                $CAJA=str_replace($lineaDatosExtra,'',$linea);
                $CAJA=str_replace('NUMERO DE REMOLQUE O CAJA','',$CAJA);
                $CAJA=str_replace(' ','',$CAJA);
                $CAJA=preg_replace('[\n|\r|\n\r]','',$CAJA);
              }
            }
            //SI LOS CAMPOS VIENEN  VACIOS, EL ARCHIVO NO  TIENE LA  ESTRUCTURA DEL PDF
            $FILE=$pdftotext;
            $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE);
            $cont_archivos+=1;
          }
        }
        $data['detalle_factura_archivo']=$detalle_factura_archivo;
        $data['PDF_NoLayout']=$PDF_NoLayout;
        return $data;
      }
      return 'NoArchivos';
    }
    // AGRICOLA ORGANICA - MASTRONARDI  - XLS
    function EDI17T($ruta){
      $path=$ruta;
      $cont_archivos=0;
      $archivos_excel=glob($path."/*.{xlsx,XLSX,XLS,xls}", GLOB_BRACE);
      if(sizeof($archivos_excel)!=0){
        $detalle_factura_archivo[]=array('BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
        $PDF_NoLayout='';
        //$BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$FILE='';
        foreach($archivos_excel as  $readExcel){
          $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';
          $inputFileType = PHPExcel_IOFactory::identify($readExcel);
	        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	        $objPHPExcel = $objReader->load($readExcel);
          $sheetNames = $objPHPExcel->getSheetNames();
          if(in_array('Formato Manifiesto',$sheetNames)){
            $sheet = $objPHPExcel->setActiveSheetIndexByName('Formato Manifiesto');
            if(strpos($sheet->getCell("B2")->getValue(),'MASTRONARDI BERRYWORLD AMERICA LLC')!==false){
              $inv=explode(' ',$sheet->getCell("B4")->getValue());
              $INVOICE=$inv[1];
              $CAJA=$sheet->getCell("B6")->getValue();
            }
            else{
              $PDF_NoLayout.=$pdftotext.'<br>';
            } 
          }
          $FILE=$readExcel;
          $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE);
          $cont_archivos+=1;
        }
        $data['detalle_factura_archivo']=$detalle_factura_archivo;
        $data['PDF_NoLayout']=$PDF_NoLayout;
        return $data;
      }
      return 'NoArchivos';
    }
    // VEGGIE PRIME                     - XML
    function EDI19T($ruta){
      $data=[];
      $cont_archivos=0;
      $archivos=glob($ruta."/*.{pdf,PDF}", GLOB_BRACE);
      if(sizeof($archivos)!=0){
        $detalle_factura_archivo[]=array('BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
        $PDF_NoLayout='';
        //$BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$FILE='';
        foreach($archivos as  $pdftotext){
          $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';
          $cmd = '"C:/xpdf/pdftotext.exe -simple2 '.$pdftotext.'"';
          shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
          $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
          $cont_lin=0;
          while(!feof($texto)){
            $linea=fgets($texto);
            $linea=preg_replace('[\n|\r|\n\r]','',$linea);
            //Rompe el ciclo si la primer linea del PDF no contiene el nobre del importador
            if($cont_lin==0){
              if(strpos($linea,'VEGGIE PRIME, SAPI DE CV')==false){
                $PDF_NoLayout.=$pdftotext.'<br>';
                break;
              }
            }
            if($cont_lin==1){
              $line_inv_split=explode(' ',$linea);
              $INVOICE=$line_inv_split[5].ltrim($line_inv_split[6],'0');
            }
            if(strpos($linea,'Caja:')!==false){
              $line_box_indexI=strpos($linea,'Caja:')+1;
              $line_box_indexF=strpos($linea,'placas:');
              $line_box=substr($linea,$line_box_indexI,$line_box_indexF - $line_box_indexI);
              $line_box=explode(' ',$line_box);
              $CAJA=str_replace(',','',$line_box[1]);
            }
            $cont_lin+=1;
          }
          // S epuede  identificar si cumple con el  Layou pero no si  tiene el mismo Layout esperado
          $FILE=$pdftotext;
          $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE);
          $cont_archivos+=1;
        }
        $data['detalle_factura_archivo']=$detalle_factura_archivo;
        $data['PDF_NoLayout']=$PDF_NoLayout;
        return $data;
      }
      return 'NoArchivos';
    }
    //Procesar de  forma independiente
    // MEXILINK                         - PDF
    function EDI20T($ruta){
      $data=[];
      $cont_archivos=0;
      $archivos=glob($ruta."/*.{pdf,PDF}", GLOB_BRACE);
      if(sizeof($archivos)!=0){
        $detalle_factura_archivo[]=array('SELLO'=>'','CURRENCY'=>'','UMO'=>'','INVDATE'=>'','INVSUBTOTAL'=>'','INVTOTAL'=>'','BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
        $PDF_NoLayout='';
        //Convierte el PKL en un array
        $Files_PKL[]=array();
        $cont_files=0;
        foreach($archivos as  $pdftotext){
          $cmd = '"C:/xpdf/pdftotext.exe -simple2 '.$pdftotext.'"';
          shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
          $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
          $cont_lin=0;
          while(!feof($texto)){
            $linea=fgets($texto);
            $linea=preg_replace('[\n|\r|\n\r]','',$linea);
            if($cont_lin==0){
              if(strpos($linea,'PACKING LIST PAGE NO')===false){
                $PDF_NoLayout.=$pdftotext.'<br>';
                break;
              }
            }
            $Files_PKL[$cont_files]['ARRAY'][$cont_lin]=$linea;
            $Files_PKL[$cont_files]['FILE']=$pdftotext;
            $cont_lin+=1;
          }
          if($cont_lin>0){
            $cont_files+=1;
          }
        }
        //Convierte las  Facturas en  un array
        $Files_INV[][]=array();
        $cont_files=0;
        foreach($archivos as  $pdftotext){
          $cmd = '"C:/xpdf/pdftotext.exe -simple2 '.$pdftotext.'"';
          shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
          $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
          $cont_lin=0;
          while(!feof($texto)){
            $linea=fgets($texto);
            $linea=preg_replace('[\n|\r|\n\r]','',$linea);
            if($cont_lin==0){
              if(strpos($linea,'Unilever Asia Private Limited INVOICE')===false){
                break;
              }
            }
            $Files_INV[$cont_files][$cont_lin]=$linea;
            $cont_lin+=1;
          }
          if($cont_lin>0){
            $cont_files+=1;
          }
        }

        //Recorre los PKL nuscando las  coincidencias con  facturas
        foreach($Files_PKL as $PKL){
          //Info: Se limpian las  variables  tras  cada vuelta de ciclo.
          $SELLO='';$CURRENCY='';$UMO='';$INVDATE='';$INVSUBTOTAL='';$INVTOTAL='';$BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$FILE='';
          $Array_SELLO_PKL=explode(' ',$PKL['ARRAY'][16]);
          $SELLO=$Array_SELLO_PKL[4];
          $Array_Embarque_PKL=explode(' ',$PKL['ARRAY'][7]);
          $EmbarquePKL=$Array_Embarque_PKL[3];
          $Count_Files_INV=0;
          foreach($Files_INV as $INV){
            $Array_Embarque_INV=explode('DN NO : ',$INV[9]);
            $EmbarqueINV=$Array_Embarque_INV[1];
            if($EmbarquePKL==$EmbarqueINV){
              //Conceptos en  ubicaciones constantes.
              $datos_inv=explode(' ',$INV[5]);
              $count=count($datos_inv);
              $CURRENCY=$datos_inv[$count-1];
              $ex_date_inv=explode('.',$datos_inv[$count-2]);
              $INVDATE=$ex_date_inv[2].'/'.$ex_date_inv[1].'/'.$ex_date_inv[0];
              $INVOICE=$datos_inv[$count-3];
              //Recorrer los  archivos en busca de los  conceptos con ubicaciones  aleatorias
              foreach($PKL['ARRAY'] as $Lin_PKL){
                if(strpos($Lin_PKL,'Peso bruto ')!==false){
                  $array_Lin_PKL=explode(' ',$Lin_PKL);
                  $BRUTO=str_replace(',','',$array_Lin_PKL[4]);
                }
                if(strpos($Lin_PKL,'Peso Neto ')!==false){
                  $array_Lin_PKL=explode(' ',$Lin_PKL);
                  $NETO=str_replace(',','',$array_Lin_PKL[4]);
                }
              }
              foreach($INV as $Lin_INV){
                if(strpos($Lin_INV,'Total ')!==false){
                  $array_Lin_INV=explode(' ',$Lin_INV);
                  if(count($array_Lin_INV)<7){
                    $BULTOS=str_replace(',','',$array_Lin_INV[1]);
                    $UMO=str_replace(',','',$array_Lin_INV[2]);
                    $INVSUBTOTAL=str_replace(',','',$array_Lin_INV[3]);
                    $INVTOTAL=str_replace(',','',$array_Lin_INV[5]);
                    break;
                  }
                }
              }
              unset($Files_INV[$Count_Files_INV]);
              $Files_INV =array_values($Files_INV);
              break;
            }
            //Nota: Colocar Observacion de  PKL sin factura, en caso de  que no le encuentre  datos mandar  mensaje de  error.
            $Count_Files_INV+=1;
          }
          $FILE=$PKL['FILE'];

          $detalle_factura_archivo[$cont_archivos]=array('SELLO'=>$SELLO,'CURRENCY'=>$CURRENCY,'UMO'=>$UMO,'INVDATE'=>$INVDATE,'INVSUBTOTAL'=> $INVSUBTOTAL,'INVTOTAL'=> $INVTOTAL,'BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE); 
          $cont_archivos+=1;
        }
        $data['detalle_factura_archivo']=$detalle_factura_archivo;
        $data['PDF_NoLayout']=$PDF_NoLayout;
        return $data;
      }
      return 'NoArchivos';
    }
    // MEXILINK                         - XLS
    function EDI20XLST($archivos,$datos_gnl,$IDEDIMPCOS,$plantilla,$ruta,$Invoices,$Mercancias,$Observaciones){
      //VAR_DUMP($archivos,$datos_gnl,$IDEDCLI,$plantilla,$ruta,$Invoices,$Mercancias,$Observaciones);
      $data = [];
      $cnt_merc=0;
      $ImporterName=ModeloopEdi:: MdlReceptor_Name($IDEDIMPCOS);// --> Esta  funcion se  actualizo por una que tra valores multiples
      foreach($archivos as $readExcel){
        $inputFileType = PHPExcel_IOFactory::identify($readExcel);
	      $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	      $objPHPExcel = $objReader->load($readExcel);
        $sheetNames = $objPHPExcel->getSheetNames();
        //Info: Valida si la  Operacion Corresponde al Importador.
        if(in_array('CARGA',$sheetNames)){
          $sheet = $objPHPExcel->setActiveSheetIndexByName('CARGA');
          if($sheet->getCell("D3")->getValue()=="Unilever de Centroamerica S,A"){
            //$INVOICE=$sheet->getCell("C9")->getValue();
            $c_dg=count($datos_gnl);
            //Info: Recorre los Datos  Generales en busca de una coincidencia en los Sellos
            for($i=0;$i<=$c_dg-1;$i++){
              if($sheet->getCell("J9")->getValue()==$datos_gnl[$i]['SELLO']){
                $K_RELF_M=struuid(true);
                $Invoices['FILE_INVOICE']=$readExcel;
                $Invoices['FILE_PLANTILLA']=$datos_gnl[$i]['FILE'];
                $Invoices['IMPORTER']=$IDEDIMPCOS;
                $Invoices['INVOICE']=$datos_gnl[$i]['INVOICE'];
                $Invoices['INVOICE_DATE']=$datos_gnl[$i]['INVDATE'];
                $Invoices['INVOICE_SUBTOTAL']=$datos_gnl[$i]['INVSUBTOTAL'];
                $Invoices['CURRENCY']=$datos_gnl[$i]['CURRENCY'];
                $Invoices['INVOICE_EXCHANGE']=NULL;
                $Invoices['INVOICE_TOTAL']=$datos_gnl[$i]['INVTOTAL'];
                //LA PLANTILLA NO SOLO TRAE  PESO  BRUTO, NO SE  REQUIERE  CALCULOS ADICIONALES
                $Invoices['GROSS_WEIGHT']=$datos_gnl[$i]['BRUTO'];
                $Invoices['NET_WEIGHT']=$datos_gnl[$i]['BRUTO'];
                $Invoices['QUANTITY']=$datos_gnl[$i]['BULTOS'];
                $Invoices['SCAC']=$datos_gnl[$i]['SCAC'];
                $Invoices['BOX_NO']=$sheet->getCell("J7")->getValue();
                $Invoices['K_RELF_M']=$K_RELF_M;
              
                //Proceso de carga de Mercancias
                $cont_lineas=1;
                $hr = $sheet->getHighestRow(); 
	              //$hc = $sheet->getHighestColumn();
                for ($x=11;$x<=$hr;$x++){
                  if(strpos($sheet->getCell("A".$x)->getValue(),'Realizado por:')!==false){
                    break;
                  }
                  $NoIdentificacion=$sheet->getCell("C".$x)->getCalculatedValue();
                  $Descripcion=$sheet->getCell("D".$x)->getOldCalculatedValue();
                  $HTS='';
                  $Cantidad=$sheet->getCell("F".$x)->getCalculatedValue();
                  $ClaveUnidad='KGM';
                  $ValorUnitario=null;
                  $Bruto=$sheet->getCell("J".$x)->getOldCalculatedValue();
                  //$Mercancias[$cont_lineas-1]=array($cont_lineas,$K_RELF_M,$NoIdentificacion,$ClaveUnidad,$Cantidad,$ValorUnitario,$Descripcion,$HTS);
                  $Mercancias[$cont_lineas-1]=array($cont_lineas,$K_RELF_M,$NoIdentificacion,$ClaveUnidad,$Cantidad,$ValorUnitario,$Descripcion,$HTS,$Bruto,$Bruto); 
                  $cont_lineas+=1;  
                }

                $data['Invoices'][$cnt_merc]=$Invoices;
                $data['Mercancias'][$cnt_merc]=$Mercancias;
                $cnt_merc+=1;
                //Mejora: Agregar codigo para eliminar la factura  del $datos_gnl, asi evitamos  subir 2 veces la misma  factura en la  ejecucion.
                break;
              }
              else{
                $Observaciones['XMLSin_PDF'].=str_replace($ruta.'/','',$readExcel).' <br> ';
              }
            }
          }
          else{
            $Observaciones['XML_NoValido'].=str_replace($ruta.'/','',$readExcel).' <br> ';
          }
        }
        else{
          $Observaciones['XML_NoValido'].=str_replace($ruta.'/','',$readExcel).' <br> ';
        }  
      }
      $data['Observaciones']=$Observaciones;
      return $data;
    }
    //ANALISAR  EXCEL DE ENCUENTRO    - XLS
    function EDI7BCK($ruta){
      $path=$ruta;
      $cont_archivos=0;
      $archivos=glob($path."/*.{xlsx,XLSX,XLS,xls}", GLOB_BRACE);
      if(sizeof($archivos)!=0){
        $detalle_factura_archivo[]=array('BRUTO'=>'','NETO'=>'','BULTOS'=>'','INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'');
        $PDF_NoLayout='';
        //$BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$FILE='';
        foreach($archivos as  $readExcel){
          $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';
          $inputFileType = PHPExcel_IOFactory::identify($readExcel);
          $objReader = PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($readExcel);
          $sheetNames = $objPHPExcel->getSheetNames();
          if(in_array('Packing list',$sheetNames)){
            $sheet = $objPHPExcel->setActiveSheetIndexByName('Packing list');
            if($sheet->getCell("C1")->getValue()=="Mastronardi Produce Packing list"){
              $INVOICE=str_replace(' ',"",$sheet->getCell("C9")->getValue());
              $CAJA=$sheet->getCell("H6")->getValue();
              $Manifiesto=glob($path."/*.{pdf,PDF}", GLOB_BRACE);
              if(sizeof($Manifiesto)!=0){
                foreach( $Manifiesto as  $Manifiestototext){
                  shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$Manifiestototext.'"');
                  $MNFtexto=fopen(str_ireplace('.pdf','.txt',$Manifiestototext),'r');
                  $Manifiesto_lineas=[];
                  $cont_lin=0;
                  while(!feof($MNFtexto)){
                    $MNFLine=fgets($MNFtexto);
                    $MNFLine=preg_replace('[\n|\r|\n\r]','',$MNFLine);
                    $Manifiesto_lineas[$cont_lin]=$MNFLine;
                    if(strpos($MNFLine,'Peso Bruto:')!==false){
                      $BRUTO_Array=explode('Peso Bruto: ',$MNFLine);
                      $BRUTO_Array=explode(' ',$BRUTO_Array[1]);
                      $BRUTO=str_replace(',','',$BRUTO_Array[0]);
                    }
                    $cont_lin+=1;
                  }
                  if(strpos($Manifiesto_lineas[0],'INC INDUSTRIAL INVERAGRO S DE PR DE RL DE CV MANIFIESTO')===false){
                    break;
                  }
                  //Romper  ciclo solo cuando se  encuentre el  Peso,  agregar bandera para  localizar  peso
                  if(strpos($Manifiesto_lineas[6],'#PO:')!==false){
                    unlink($Manifiestototext);
                    break;
                  }
                  else{
                    $BRUTO='';
                  }
                }
              }
            } 
            else{
              $PDF_NoLayout.=$pdftotext.'<br>';
            } 
          }
          //SI LOS CAMPOS VIENEN  VACIOS, EL ARCHIVO NO  TIENE LA  ESTRUCTURA DEL PDF
          $FILE=$readExcel;
          $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$FILE);
          //VAR_DUMP($detalle_factura_archivo[$cont_archivos]);
          $cont_archivos+=1;
        }
        $data['detalle_factura_archivo']=$detalle_factura_archivo;
        $data['PDF_NoLayout']=$PDF_NoLayout;
        return $data;
      }
      return $detalle_factura_archivo;
    }   
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Funcion Standar para  procesamiento de  XML

    /*function SearchInPdf($CATALOGO){
      $PDF_NoLayout='';
      $cont_archivos=0;
      foreach($GLOBALS['FilesPDF'] as  $key=>$pdftotext){
        $BRUTO='';$NETO='';$BULTOS='';$INVOICE='';$CAJA='';$SCAC='';$FILE='';$CUST_REF='';$PO='';$MANIFEST='';$REFERENCE='';
        if(count($pdftotext['DATA'])>1){
          if(strpos($pdftotext['DATA'][1],'HORTIGEN PRODUCE S.A DE C.V')!==false){                        //Identificador del archivo,  utiliza una  cadena como  identificador de los PDF para relacionarlo con el Importador
            unset($GLOBALS['FilesPDF'][$key]);                                                          //Al ser  localizado  la factura  se elimina del Array.
            foreach($pdftotext['DATA'] as $key_line=>$linea ){                                            // Busca en el Arreglo de lineas los  valores que nos interesa del archivo.
              if(strpos($linea,'Comentarios')!==false){
                $val=explode(' ',$pdftotext['DATA'][$key_line+1]);
                $PO=$val[1];
                $MANIFEST=$val[3];
              }
            }
            //Fin Dinamicos
            if($BRUTO=='' || $INVOICE=='' || $CAJA==''){                                   //SI LOS CAMPOS VIENEN  VACIOS, EL ARCHIVO NO  TIENE LA  ESTRUCTURA DEL PDF
              $PDF_NoLayout.=$pdftotext['FILE'].'<br>';
              continue;
            }
            $detalle_factura_archivo[$cont_archivos]=array('BRUTO'=>$BRUTO,'NETO'=>$NETO,'BULTOS'=>$BULTOS,'INVOICE'=>$INVOICE,'CAJA'=>$CAJA,'SCAC'=>$SCAC,'FILE'=>$pdftotext['FILE'],'CUST_REF'=>$CUST_REF);
            $cont_archivos+=1;
          }
        }
      }
      $GLOBALS['FilesPDF'] =array_values($GLOBALS['FilesPDF']);                                         //Ordena el Arreglo GLOBAL, se  reducen  su indices
      $data['detalle_factura_archivo']=$detalle_factura_archivo;                                        //Datos retornados al  finalizar la revision
      $data['PDF_NoLayout']=$PDF_NoLayout;
      return $data;
    }*/
    function XML($datos_gnl,$IDEDIMPCOS,$plantilla,$Observaciones,$IDXML){
      //PROCESA XML CON PLANTILLA, si no trae plantilla deja los valores tal cual se  capturaron del  XML en ReadFilesOnFolder
          
      $data = [];
            
      $IDEDIMPCOS_ReceptorName=ModeloopEdi::MdlReceptor_Name($IDEDIMPCOS);                                                            //OBTIENE EL IDENTIFICADOR DEL XML PARA EL IMPORTADOR
      
      //SE  AGREGO UN FOREACH PARA RECORRER TODOS LOS  XML DEL  IMPORTADOR, SI ENCUENTRA UNA  COINCIDENCIA CON EL  XML, SIGNIFICA QUE EL XML ESTA DADO DE ALTA, ESTE  CICLO PERMITE UTILIZA R VARIOS  XML EN LA MISMA  OPERACION
      //VAR_DUMP($IDEDIMPCOS_ReceptorName);
      //foreach( $IDEDIMPCOS_XML_LIST as $IDEDIMPCOS_ReceptorName){
      //var_dump($datos_gnl,$IDEDIMPCOS_ReceptorName,$IDXML);

      $cnt_inv=0;                                                                                                                     //INDICE DE XML INVOICES IDENTIFICADAS PARA EL IMPORTADOR
      $bandera_xml=0;                                                                                                                 //Lleva el control del XML del Importador: 0 -> No existen XML | 1-> Tiene XML por evaluar

      foreach($IDEDIMPCOS_ReceptorName as $id_key=>$importer_consignee_loop){                                                          // 10/25/2022SE AGREGO PARA HACER EL LOOP DE TODOS LOS RESULTADOS DE XML CONFIGURATION DE DETALLES
                 
          foreach($GLOBALS['FilesXML'] as $key =>$XML){                                                                                   // Procesa todos los XML

            $XML_NETO=0;
            //SE AGREGA LINEA PARA DESCARTAR SI HAY DOS RESPUESTAS
                                                                              
            if($importer_consignee_loop[0]==strtoupper($XML['DATA']['XML_ReceptorName']) && $importer_consignee_loop[1]==strtoupper($XML['DATA']['XML_EmisorName'])){                                     // Busca los XML del Importador, si no se  sube la plantilla se  cargan con los datos del XML
              //CONDICION ORIGINAL, SOLO RECIBIA EL PRIMER RESULTADO DE LA CONFIGURACION DEL XML DEL CONSIGNEE
              //if(strtoupper($IDEDIMPCOS_ReceptorName[0])==strtoupper($XML['DATA']['XML_ReceptorName']) && strtoupper($IDEDIMPCOS_ReceptorName[1])==strtoupper($XML['DATA']['XML_EmisorName'])){                                     // Busca los XML del Importador, si no se  sube la plantilla se  cargan con los datos del XML
                            
              $bandera_xml=1;  
              unset($GLOBALS['FilesXML'][$key]);
              
              $XML['DATA']['IMPORTER']=$IDEDIMPCOS;
              if($plantilla!=0){                                                                                            // Si el Importador  Maneja Plantilla entra al ciclo a buscar los valores que le corresponden
                $c_dg=count($datos_gnl);
                var_dump('Contenido de datos_gnl: ');
                var_export($datos_gnl);                
                $b_datosgnl=0;                                                                                    // Cuenta los valores en el arreglo de PDF
                for($i=0;$i<=$c_dg-1;$i++){                                                                        // Recorre el arreglo de los PDF
                  //La funcion original  comparaba las  facuras, se  cambio por  una que busque la  factura del PDF en el XML
                  //Original:  if(($XML['DATA']['INVOICE_SERIE'].$XML['DATA']['INVOICE'])==$datos_gnl[$i]['INVOICE']){
                  
                  if($datos_gnl[$i]['INVOICE']!='' && strpos(($XML['DATA']['INVOICE_SERIE'].$XML['DATA']['INVOICE']),$datos_gnl[$i]['SERIE'].$datos_gnl[$i]['INVOICE'])!==false){                                                              
                  //OBSERVACION CARGA LOS DATOS DE LA FACTURA PERO NO MUESTRO LOS ITEMS :if(isset($XML['DATA']['INVOICE_SERIE']) and isset($XML['DATA']['INVOICE'])){  
                    $XML['DATA']['FILE_PLANTILLA']=$datos_gnl[$i]['FILE'] ;                                                       //Se  utiliza para eliminar el PDF DE LA PLANTILLA en caso de  existir
                    /*SECCION DE  CALCULO DE  PESOS DE LA MERCANCIA  Y  LA FACTURA */
                    //NOTA: SI EL  NETO ES  0 EL BRUTO ES 0 
                    //NOTA: SI NO EXISTE PESO EN EL XML  LAS LINEAS  NO TRAEN PESO

                    //SE SALTARA EL PROCESO EN CASO DE QUE NO EXISTA PESO EN EL XML O EN LA PLANTILLA
                    //NOTA: LA PLANTILLA  SIEMPRE ARROJARA EL PESO EN EL CAMPO DE BRUTO 
                    //SI EL PESO BRUTO DE LA PLANTILLA ES DIFERENTE DE 0 SE PROCEDE CON EL PRORRATEO
                    
                    if(floatval($datos_gnl[$i]['BRUTO'])!=0){
                      if($XML['DATA']['NET_WEIGHT']==0){    //Si el peso neto viene  vacio se debe colocar el peso bruto de la plantilla // En caso de que el  valor peso venga  vacio del XML se debe colocar el valor bruto del PDF
                        $XML['DATA']['NET_WEIGHT']=floatval($datos_gnl[$i]['NETO']); 
                        $XML_NETO=1;
                      }
                      else{
                        $Diff_BN=floatval($datos_gnl[$i]['BRUTO'])-floatval($XML['DATA']['NET_WEIGHT']);
                        $XML_NETO=2;
                      }
                      $XML['DATA']['GROSS_WEIGHT']=floatval($datos_gnl[$i]['BRUTO']);
                      // CUANDO NO EXISTEN PESOS EN LAS  XML SE  PRORRATEA  EL PESO  DEL BRUTO OBTENIDO DE LA PLANTILLA
                      $c_m=count($XML['DATA']['MERCANCIAS']);
                      for($j=0;$j<=$c_m-1;$j++){
                        if($XML_NETO==1){
                          $XML['DATA']['MERCANCIAS'][$j][7]=floatval($datos_gnl[$i]['BRUTO'])/$c_m;
                          $XML['DATA']['MERCANCIAS'][$j][8]=floatval($datos_gnl[$i]['NETO'])/$c_m;
                        }
                        else if($Diff_BN!=0){
                          $Porcentaje=(floatval($XML['DATA']['MERCANCIAS'][$j][7])/floatval($XML['DATA']['NET_WEIGHT']))*100;
                          $XML['DATA']['MERCANCIAS'][$j][7]= round(floatval( $XML['DATA']['MERCANCIAS'][$j][7])+($Diff_BN*$Porcentaje)/100,2);
                        }
                      }
                    }
                    if($datos_gnl[$i]['CAJA']!=''){
                      $XML['DATA']['BOX_NO']=$datos_gnl[$i]['CAJA'];
                    }
                    //var_dump($datos_gnl[$i]['BULTOS']);
                    if($datos_gnl[$i]['BULTOS']===null || $datos_gnl[$i]['BULTOS']>0){
                      $XML['DATA']['QUANTITY']=$datos_gnl[$i]['BULTOS'];
                    }
                    if($datos_gnl[$i]['SCAC']!=''){
                      $XML['DATA']['SCAC']=$datos_gnl[$i]['SCAC'];
                    }
                    if($datos_gnl[$i]['CUST_REF']!=''){
                      $XML['DATA']['CUST_REF']=$datos_gnl[$i]['CUST_REF'];
                    }
                    if(floatval($datos_gnl[$i]['VALUE'])!=0){
                      //$Cantidad 4 ,$ValorUnitario 5
                      $Diff_INVTOT=floatval($datos_gnl[$i]['VALUE'])-floatval($XML['DATA']['INVOICE_TOTAL']);
                      $c_m=count($XML['DATA']['MERCANCIAS']);
                      for($j=0;$j<=$c_m-1;$j++){
                        $Porcentaje=((floatval($XML['DATA']['MERCANCIAS'][$j][4])*floatval($XML['DATA']['MERCANCIAS'][$j][5]))/floatval($XML['DATA']['INVOICE_TOTAL']))*100;
                        $ValorTotalPorLinea=(floatval($XML['DATA']['MERCANCIAS'][$j][4])*floatval($XML['DATA']['MERCANCIAS'][$j][5]))+($Diff_INVTOT*$Porcentaje)/100;
                        $XML['DATA']['MERCANCIAS'][$j][5]= round($ValorTotalPorLinea/$XML['DATA']['MERCANCIAS'][$j][4],2);
                      }
                      $XML['DATA']['INVOICE_TOTAL']=$datos_gnl[$i]['VALUE'];
                    }
                    unset($datos_gnl[$i]);
                    $datos_gnl=array_values($datos_gnl);
                    $b_datosgnl=1; 
                    break;
                  }
                }
                if($b_datosgnl==0){                                                                                               //Bandera, si  tiene  valor existen valores en el arrglo datos_gnl
                  //var_dump($XML);
                  $Observaciones['XMLSin_PDF'].=$XML['FILE'].' <br> ';//INDICA QUE LA FACTURA DEL XML NO  CONCUERDA CON LA DE LOS ARCHIVOS.
                  continue;
                }
              }
              //SI SE ENCUENTRA EL XML PASARLO A LA  VARIABLE  DATA  Y  ELIMINAR SU REGISTRO DEL ARREGLO DEL XML
              $data['Invoices'][$cnt_inv]=$XML['DATA'];
              $cnt_inv+=1;
            }                                                                                                            
          }
        }

        if($bandera_xml==1){
          $GLOBALS['FilesXML']=array_values($GLOBALS['FilesXML']);
        }
        $data['Observaciones']=$Observaciones;
        //Retornar  los datos  generales que no se  eliminaron,  son archivos  pdf que se  cargaron pero no se  subio su XML
        //$datos_gnl['FILE']
      //}
      return $data;
    }
    function ReadFilesOnFolder($path,$TC){
      $archivos=glob($path."/*.{pdf,PDF}", GLOB_BRACE);                                                                     //Adquiere la lista de PDF de la carpeta
      if(sizeof($archivos)!=0){
        $cont_PDF=0;
        foreach($archivos as $pdftotext){                                                                                         //Se  requieren 2  ciclos para las vueltas, y tras cada coincidencia  descartarlo para evitar reduncia innecesaria                                                                            
            shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
            $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
            $cont_lin=0;
            $lineas=[];
            while(!feof( $texto)){
              $linea=fgets($texto);
              $linea=preg_replace('[\n|\r|\n\r]','',$linea);
              $lineas[$cont_lin]=$linea;
              $cont_lin+=1;
            }
            //Una  vez  que termine de  pasar el archivo a un arreglo, buscar  su identificador, si existe  pasarlo al  arreglo
            $GLOBALS['FilesPDF'][$cont_PDF]['FILE']=$pdftotext;                                                                   // Se  utiliza
            $GLOBALS['FilesPDF'][$cont_PDF]['IMP']=0;                                                                             // No se  uso 
            $GLOBALS['FilesPDF'][$cont_PDF]['LINES']=$cont_lin;                                                                   // No se uso
            $GLOBALS['FilesPDF'][$cont_PDF]['DATA']=$lineas;                                                                      // Se utiliza
            $GLOBALS['FilesPDF'][$cont_PDF]['TYPE_FILE']='PDF';                                                                   // No se  usa
            $GLOBALS['FilesPDF'][$cont_PDF]['WORKED']=0;                                                                          // No se usa
            $cont_PDF+=1;
        }
      }
      //Se agrego esta  funcion para leer los XML
      $XML_NoValido='';
      $archivos=glob($path."/*.{xml,XML}", GLOB_BRACE);
      if(sizeof($archivos)!=0){
        $cnt_XML=0;
        foreach($archivos as $XML){
          //VARIABLES
          $PesoXML=0;
          $InvoiceXML=0;
          $BultosXML=0;
          $concepto= [];
          $receptor = [];
          $mercancia = [];
          $cfdi= [];
          $Mercancias=array();
          $m=0;
          $c=0;
          $cont_lineas=0;
          $K_RELF_M=struuid(true);
          /* INICIALIZACION DEL XML Y SUS CONCEPTOS */
          libxml_use_internal_errors(true);
          $ValidacionXML= simpleXML_load_file($XML);
          //VAR_DUMP($ValidacionXML);
          if($ValidacionXML!==false){
            $xml = new \SimpleXMLElement($XML, null,true);
            $ns = $xml->getNamespaces(true);
            $cfdi = $xml->xpath('//cfdi:Comprobante');
            $emisor = $xml->xpath('//cfdi:Comprobante//cfdi:Emisor');
            $receptor = $xml->xpath('//cfdi:Comprobante//cfdi:Receptor');
            $concepto = $xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto');
            $c = count($concepto);
            if(isset($ns['cce11'])){
              $xml->registerXPathNamespace('cce11', $ns['cce11']);
              $mercancia = $xml->xpath('//cfdi:Comprobante//cfdi:Complemento//cce11:ComercioExterior//cce11:Mercancias//cce11:Mercancia');
              $m=count($mercancia);
            }

            //EN CASO DE UTILIZAR  VARIOS TC,  SE  DEBE  VALIDAR EN ESTA SECCION
            if(utf8_decode($cfdi[0]['Moneda'])=='USD'){
              //SE  DEJARA POR  DEFAULT  EL TIPO DE  CAMBIO EN PESOS, EN CASO DE  OCUAPRSE OTRO TIPO DE CAMBIO SE  TIENE QUE OBTENER MEDIANTE CONSULTA, 
              //Y DEBE SER  REMOVIDO DE LAS  VARIABLE ENVIADAS.
              $TC=1;
            }
            else if($TC==0){
              $XML_NoValido.=' REQUIERE TC '.str_replace($path,'',$XML).' | ';
              break;
            }
            
            //Mercancias Conceptos
            for ($x=0;$x<=$c-1;$x++){
              $Neto=0;$NoIdentificacion='';$Descripcion='';$Cantidad='';$ClaveUnidad='';$ValorUnitario='';
              if(isset($concepto[$x]['NoIdentificacion'])){$NoIdentificacion=utf8_decode($concepto[$x]['NoIdentificacion']);}
              if(isset($concepto[$x]['Descripcion'])){$Descripcion=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($concepto[$x]['Descripcion']));}        
              if(isset($concepto[$x]['Cantidad'])){
                $Cantidad=utf8_decode($concepto[$x]['Cantidad']);
                $BultosXML+=floatval(utf8_decode($concepto[$x]['Cantidad']));
              }
              if(isset($concepto[$x]['ClaveUnidad'])){$ClaveUnidad=utf8_decode($concepto[$x]['ClaveUnidad']);}
              if(isset($concepto[$x]['ValorUnitario'])){
                $ValorUnitario=utf8_decode($concepto[$x]['ValorUnitario'])*$TC;
              }
              
              //Ciclo para  recorrer Mercancias
              if($m>0){                                                                                                     //Este  ciclo  recorre las mercancias, para  buscar la  clave 01, cuando la encuentra  decrementa las  variables de la  mercancia y asigna  un valor a NETO
                for($y=0;$y<=$m-1;$y++){

                 if(utf8_decode($mercancia[$y]['UnidadAduana'])!="01" || $NoIdentificacion!=$mercancia[$y]['NoIdentificacion']){
                    //VAR_DUMP($m);
                    continue;
                  }
                  else if (isset($mercancia[$y]['CantidadAduana'])){
                    /*if(utf8_decode($cfdi[0]['Moneda'])!='USD'){
                      if(isset($mercancia[$y]['ValorDolares'])){$ValorUnitario=floatval(utf8_decode($mercancia[$y]['ValorDolares']))/$Cantidad;}

                      //QUITAR BANDERA DE TIPO MONEDA
                    }*/
                    $Neto=floatval(utf8_decode($mercancia[$y]['CantidadAduana']));
                    //Decrementos de las  Mercancias
                    unset($mercancia[$y]);
                    $mercancia=array_values($mercancia);
                   
                    $m-=1;
                    break;
                  }
                }
                
              }
              
              //El peso  neto se  duplica en las  mercancias,  ya que se  desconoce  el bruto
              //NOTA: LAS  LINEAS  COMIENZAN CON  VALOR 1, PERO EL INDICE DEL  ARREGLO COMIENZA EN 0
              $Mercancias[$cont_lineas]=array($cont_lineas+1,$K_RELF_M,substr($NoIdentificacion,0,22),$ClaveUnidad,$Cantidad,$ValorUnitario,$Descripcion,$Neto,$Neto); 
              $PesoXML+=$Neto;
              $InvoiceXML+=$ValorUnitario*$Cantidad;
              $cont_lineas+=1;
            }
            $Invoices['XML_EmisorName']=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($emisor[0]['Nombre']));                //REMUEVE CARACTERES ESPECIALES DEL NOMBRE
            $Invoices['XML_ReceptorName']=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($receptor[0]['Nombre']));            //REMUEVE CARACTERES ESPECIALES DEL NOMBRE
            $Invoices['INVOICE']=utf8_decode($cfdi[0]['Folio']);
            $Invoices['INVOICE_SERIE']=utf8_decode($cfdi[0]['Serie']);
            //EXTRACCION DE  FECHA DE  FACTURACION
            $f=explode("T",utf8_decode($cfdi[0]['Fecha']));
            $Invoices['INVOICE_DATE']=$f[0];
            if(isset($cfdi[0]['Total']) || $cfdi[0]['Total']==0){
              $Invoices['INVOICE_SUBTOTAL']=$InvoiceXML;
              $Invoices['INVOICE_TOTAL']=$InvoiceXML;
            }
            else{
              $Invoices['INVOICE_SUBTOTAL']=utf8_decode($cfdi[0]['SubTotal'])?:0;
              $Invoices['INVOICE_TOTAL']=utf8_decode($cfdi[0]['Total'])?:0;
            }
            $Invoices['INVOICE_SUBTOTAL']=utf8_decode($cfdi[0]['SubTotal'])?:0;
            //$Invoices['CURRENCY']=utf8_decode($cfdi[0]['Moneda']);
            $Invoices['CURRENCY']='USD';
            $Invoices['INVOICE_EXCHANGE']=utf8_decode($cfdi[0]['TipoCambio'])?:($TC!=0?(1/$TC):0);
            //$Invoices['INVOICE_TOTAL']=utf8_decode($cfdi[0]['Total'])?:0;
            $Invoices['K_RELF_M']=$K_RELF_M;  

            $Invoices['GROSS_WEIGHT']=$PesoXML?:0;
            $Invoices['NET_WEIGHT']=$PesoXML?:0;
            $Invoices['QUANTITY']=$BultosXML?:0;  // EQUIVALE A LOS BULTOS TOTALES DE LA FACTURA, LAS  CANTIDADES POR LINEA SE  DECLARAN EN LA  TABLA DE MERCANCIA
            $Invoices['MERCANCIAS']=$Mercancias;
            $Invoices['IMPORTER']='';                                                                                       // Estos valores los obtendremos de la  funcion XML
            $Invoices['BOX_NO']='';                                                                                         // Estos valores los obtendremos de la  funcion XML en caso de  que exista plantilla
            $Invoices['SCAC']='';                                                                                           // Estos valores los obtendremos de la  funcion XML en caso de  que exista plantilla
            $Invoices['FILE_INVOICE']=$XML;
            $Invoices['FILE_PLANTILLA']='';
            $GLOBALS['FilesXML'][$cnt_XML]['FILE']=$XML;
            $GLOBALS['FilesXML'][$cnt_XML]['IMP']=0;                                                                        // No se  usa
            $GLOBALS['FilesXML'][$cnt_XML]['LINES']=$cont_lineas;
            $GLOBALS['FilesXML'][$cnt_XML]['DATA']=$Invoices;
            $GLOBALS['FilesXML'][$cnt_XML]['TYPE_FILE']='XML';                                                              // No se  usa
            $GLOBALS['FilesXML'][$cnt_XML]['WORKED']=0;                                                                     // No se usa
            $cnt_XML+=1; 
          }
          else{
            $XML_NoValido.=str_replace($path,'',$XML).' | ';
          }
        }
      }
      return $XML_NoValido;
    }
    /*Actualizacion el 20220314: En proceso de Actualizacion, se pretende  minimizar los  ciclos por oprracion*/
    function ctrUpFiles($IDEDIMPCOS,$tarchivo,$ruta,$Observaciones,$OpByInv,$IDXML,$OpWthBox){
      
      $data = [];
      $c_inv=0;
      $plantilla=0;
      $funcion='EDI'.$IDEDIMPCOS;
      
      //$detalle_factura_archivo[]=array('BRUTO'=>0,'NETO'=>0,'BULTOS'=>0,'INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'','CUST_REF'=>'','VALUE'=>0);
      //$TMP_valores_factura=array('BRUTO'=>0,'NETO'=>0,'BULTOS'=>0,'INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'','CUST_REF'=>'','VALUE'=>0);
      $TMP_valores_factura=array('BRUTO'=>0,'NETO'=>0,'BULTOS'=>0,'INVOICE'=>'','CAJA'=>'','SCAC'=>'','FILE'=>'','CUST_REF'=>'','VALUE'=>0,'SERIE'=>'');
      $detalle_factura_archivo[]=$TMP_valores_factura; //ALMACENA COMO ARREGLO EL LISTADO DE PDF'S QUE COINCIDEN CON EL TEMPLATE DE VALORES
      //var_dump($funcion); = EDI142
      
      
      if(function_exists($funcion) && $tarchivo=="XLS"){
        $datos_gnl=$funcion($ruta);
        $plantilla=1;
        //var_dump($Invoices);
        $Observaciones['PDF_NoLayout']=$datos_gnl['PDF_NoLayout'];
        if($datos_gnl['detalle_factura_archivo']!='NoArchivos'){
          $funcion=$funcion.$tarchivo;
          //var_dump($Invoices);
          $data=$funcion($archivos,$datos_gnl['detalle_factura_archivo'],$IDEDIMPCOS,$plantilla,$ruta,$Invoices,$Mercancias,$Observaciones);
        }
      }
      else if(function_exists($funcion) && $tarchivo=="XML"){                                                                                                         
        $datos_gnl=$funcion($detalle_factura_archivo,$TMP_valores_factura,$IDEDIMPCOS);               //Mejora: Agregar validacion aqui de si no hay  archivos de  datos  generales
        $plantilla=1;
        //ERROR: Si retorna  un mensaje de no archivos, no es posible llamar datos_gnl como array  
        $Observaciones['PDF_NoLayout']=$datos_gnl['PDF_NoLayout'];
        $data=XML($datos_gnl['detalle_factura_archivo'],$IDEDIMPCOS,$plantilla,$Observaciones,$IDXML);
      }
      else if($tarchivo=="XML/PDF"){                                                                                                                
        //ECHO 'XMLPDF';
        $datos_gnl=AutoPDF($IDEDIMPCOS);                                                             //Mejora: Agregar validacion aqui de si no hay  archivos de  datos  generales        
        $plantilla=1;
        var_dump('CtrUpFiles if XML/PDF:');
        var_export($datos_gnl); //TRAE EL ESQUELOTO DE PARA LOS VALORES DE LAS MERCANCIAS
        //ERROR: Si retorna  un mensaje de no archivos, no es posible llamar datos_gnl como array  
        $Observaciones['PDF_NoLayout']=$datos_gnl['PDF_NoLayout'];
        $data=XML($datos_gnl['detalle_factura_archivo'],$IDEDIMPCOS,$plantilla,$Observaciones,$IDXML);
      }
      else if($tarchivo=="XML"){
        $datos_gnl[0]=$TMP_valores_factura;
        $data=XML($datos_gnl,$IDEDIMPCOS,$plantilla,$Observaciones,$IDXML);
      }
      //Mejora: Agregar Validacion para el caso donde  data traiga algun error.
      //20220318 VAR_DUMP($data,$data['Invoices'],$data['Invoices'][0]['MERCANCIAS']);     
      if(isset($data['Invoices'])){
        $c_inv=count($data['Invoices'])-1;
        for($i=0;$i<=$c_inv;$i++){
          if($OpByInv==1){
            $data['Invoices'][$i]['K_RELF_C']=struuid(true);
          }
          else{
            $data['Invoices'][$i]['K_RELF_C']='';
          }
          if($OpWthBox==1){
            $data['Invoices'][$i]['BOX_NO']=' ';
          }
          $query=ModeloopEdi::MdlImporter_InsertCaja($data['Invoices'][$i]);
          if($query!==1 && $query!==0){
            $data['Observaciones']['Error'].=' Invoice '.$data['Invoices'][$i]['FILE_INVOICE'].':'.$query;
          }
          else if($query===0){
            $data['Observaciones']['Factura_Duplicada'].=' Invoice '.$data['Invoices'][$i]['FILE_INVOICE'];
          }
          else{
            $b_mercancias=0;
            foreach($data['Invoices'][$i]['MERCANCIAS'] as $linea){
              $query=ModeloopEdi::MdlImporter_InsertMercancia($linea);
              if($query!="ok"){
                $data['Observaciones']['Error'].=' Mercancia: '.$data['Invoices'][$i]['FILE_INVOICE'].':'.$query;
                //Agregar  validacion en base  roll back, de momento se  realizara  delete  con query,
                $query=ModeloopEdi::MdlImporter_DeleteCaja($data['Invoices'][$i]['K_RELF_M']);
                $query=ModeloopEdi::MdlImporter_DeleteMercancias($data['Invoices'][$i]['K_RELF_M']);
                $b_mercancias=1;
                break;
              }     
            }
            if($b_mercancias==0){
              $data['Observaciones']['Cargados']+=1;
              unlink($data['Invoices'][$i]['FILE_INVOICE']);
              if($data['Invoices'][$i]['FILE_INVOICE']!='' && $plantilla!=0 && $data['Invoices'][$i]['FILE_PLANTILLA']!=''){
                //AGREGAR OPCION PARA  BORRAR LOS PAKING LIST EN CASO DE  TENER
                //AGREGAR A DATOS_GNL UN CAPTO TIPO ARRAY QUE ALMACENE LOS ARCHIVOS
                //AL LLEGAR A ESTA PARTE  RECORRER EL ARRAY Y BORAR LOS ARCHIVOS
                unlink($data['Invoices'][$i]['FILE_PLANTILLA']);
              }
            }
          }
        }

      }
      else{
        //Colocar mensaje de  que no se cumplio la estructura de procesamiento
        //$data['Observaciones']['Error'].=$Observaciones['Error'];
        //$Observaciones=$data['Observaciones'];
      }
      $Observaciones=$data['Observaciones'];
      return $Observaciones;
    }

    //Nota:  $a += 0 esta forma elimina los  0 al inicio de la  cadena
    class ControladoropEdi{
        static public function ctrSlClientes(){
            $data= new stdclass();
            $clientes=ModeloopEdi::MdlClientes('All','1');
            $scac=ModeloopEdi::MdlmldGenerarEdi_scac();
            $data->clientes=$clientes;
            $data->scac=$scac;
            $TC=ModeloopEdi::MdlCOnsultarUltimoTCPesos();
            //VAR_DUMP($TC,isset($TC),empty($TC));
            if(empty($TC)){
              $data->TC="NoTCPesos";
            }
            else{
              $data->TC=$TC[0];
            }
            return ($data);  
        }
        //Inicio de bloque revisado y actualizado 20220216
        static public function ctrtbCajas($IDEDCLI,$VistaPanel){
          $data= new stdclass();
          $cliente=ModeloopEdi::MdlClientes($IDEDCLI,'3');
          if(isset($cliente)){
            $cajas=ModeloopEdi::MdlImporter_Cajas($IDEDCLI,$VistaPanel);
            //Inicio Proceso para obtener  array de manufacturadores
            $Importadores=array_unique(array_column($cajas,'IMP_ABI_KEY'));
            $Manufacturadores=array();
            foreach($Importadores as $imp){
              $Manuf=ModeloopEdi::MdlmldGenerarEdi_manuf($imp);
              $list='';
              foreach($Manuf as $man){
                $list.='<option value='.$man[0].' >'.$man[1].'</option>';
              }
              $Manufacturadores+=[$imp=>$list];
            }
            /*Agrega al objeto la lista de Manufacturadores de los importadores del cliente. */
            $data->MANUF=$Manufacturadores;
            //Fin Proceso para obtener  array de manufacturadores
        
            $cont=count($cajas);
            if($cont>0){ 
                for($x=0;$x<$cont;$x++){
                    $IDEDIMPCOS = $cajas[$x]['IDEDIMPCOS'];
                    $importador = $cajas[$x]['IMP_NAME'];
                    $IMP_ABI_KEY = $cajas[$x]['IMP_ABI_KEY'];
                    $consignatario= $cajas[$x]['CONS_NAME'];
                    $CONS_ABI_KEY = $cajas[$x]['CONS_ABI_KEY'];
                    $t_archivo = $cajas[$x]['t_archivo'];
                    $t_cat = $cajas[$x]['TipoCat'];

                    $origen = $cajas[$x]['origen'];
                    $tiop = $cajas[$x]['tiop'];
                    $puerto= $cajas[$x]['puerto'];
                    $location = $cajas[$x]['location'];

                    $krelfc = $cajas[$x]['K_RELF_C'];
                    $box = $cajas[$x]['BOX_NO'];
                    $invoice = $cajas[$x]['INVOICES'];
                    $gross = round($cajas[$x]['GROSS_WEIGHT'],2);
                    $quantity = round($cajas[$x]['QUANTITY'],2);
                    $amount = $cajas[$x]['AMOUNT'];
                    if(isset($cajas[$x]['EDI_ENTRY'])){
                      $entry=$cajas[$x]['EDI_ENTRY'];
                    }
                    else{
                      $entry='';
                    }
                    
                    $date = $cajas[$x]['START_DATE'];
                    if($VistaPanel<>'1'){
                      $StatusCat = 0; //BANDERA DE PRODUCTOS NUEVOS, SE  COLOCA  EN 0 PARA  PRODUCTOS  EXISTENTES EN CATALOGO
                      $scac=   $cajas[$x]['SCAC'];
                      $manuf=  $cajas[$x]['MANUF'];
                      $catalogo='';
                      $Boton= '<div class="btn-group">'.
                                '<div class="col-6">'.
                                  '<button type="button" class="btn btn-primary btn-smy btnProcesarEdi" id="btnProcesar'.$x.'" name="btnProcesar'.$x.'" data-tarchivo='.$t_archivo.' data-tcat='. $t_cat .'data-origen ='.$origen.' data-tiop='.$tiop.' data-puerto='.$puerto.' data-location='.$location.' data-caja="'.$box.'" data-krelfc="'.$krelfc.'" data-idedimpcos="'.$IDEDIMPCOS .'" data-importador="'. $importador.'" data-impabikey="'.$IMP_ABI_KEY .'" data-consname="'.$consignatario.'" data-consabikey="'.$CONS_ABI_KEY.'">'. 
                                      '<i class="fas fa-file-invoice"></i>'.
                                  '</button>'.
                                '</div>'. 
                                '<div class="col-6"><button class="btn btn-warning btn-smy btnCheckToProccess" id="CHK'.$x.'" name="CHK'.$x.'" data-status=0 data-id='.$x.'><i class="fas fa-dot-circle"></i></button></div>'. 
                            '</div>';   
                    }
                    else{
                      $StatusCat = $cajas[$x]['PRODNOCAT'];
                      $manuf = $cajas[$x]['MANUF'];

                      $scac=  '<input type="text" class="form-control" id="tbCajas_txtscac'.$x.'"  value = "'.$cajas[$x]['SCAC'].'" tabindex="0">';
                      $manuf= '<select class="form-control" id="tbCajas_slcManuf'.$x.'" >'.str_replace($manuf,$manuf.' selected',$Manufacturadores[$IMP_ABI_KEY]).'</select>';
                      if($StatusCat==0){
                        $catalogo='<button class="btn btn-success btn-xs" id="tbCajas_btncatstat'.$x.'">PRODUCTS ADDED</button>';
                      }
                      else{
                        $catalogo='<button class="btn btn-danger btn-xs" id="tbCajas_btncatstat'.$x.'">NEW PRODUCTS</button>';
                      }
                      $Boton= '<div class="btn-group">'.
                                '<div class="col-10">'.
                                  '<button type="button" class="btn btn-primary btn-smy btnProcesarEdi" id="btnProcesar'.$x.'" name="btnProcesar'.$x.'"  data-tarchivo='.$t_archivo.' data-tcat='. $t_cat .' data-origen ='.$origen.' data-tiop='.$tiop.' data-puerto='.$puerto.' data-location='.$location.' data-caja="'.$box.'" data-krelfc="'.$krelfc.'" data-idedimpcos="'.$IDEDIMPCOS .'" data-importador="'. $importador.'" data-impabikey="'.$IMP_ABI_KEY .'" data-consname="'.$consignatario.'" data-consabikey="'.$CONS_ABI_KEY.'">'. 
                                      '<i class="fas fa-file-invoice"></i>'.
                                  '</button>'.
                                  '<button type="button" class="btn btn-secondary btn-smy opEdi_showmdlSplitBox" id="btnSplit'.$x.'" name="btnSplit'.$x.'" data-idedimpcos="'.$IDEDIMPCOS .'" data-caja="'.$box.'" data-krelfc="'.$krelfc.'" data-cinv="'.$invoice.'" >'. 
                                      '<i class="fas fa-truck-moving"></i>'.
                                  '</button>'.
                                  '<button type="button" class="btn btn-danger btn-smy opEdi_deleteBoxInvoice" id="btnDelBoxInv'.$x.'" name="btnDelBoxInv'.$x.'" data-idedimpcos="'.$IDEDIMPCOS .'" data-caja="'.$box.'" data-krelfc="'.$krelfc.'" data-cinv="'.$invoice.'">'. 
                                      '<i class="fas fa-trash"></i>'.
                                  '</button>'.
                                '</div>'. 
                                '<div class="col-2"><button class="btn btn-warning btn-smy btnCheckToProccess" id="CHK'.$x.'" name="CHK'.$x.'" data-status=0 data-id='.$x.'><i class="fas fa-dot-circle"></i></button></div>'. 
                            '</div>';   
                    }
                    $data->data[$x]=array($importador,$entry,$box,$invoice,$gross,$quantity, "$".number_format($amount,2),$scac,$manuf,$catalogo,$date,$Boton);
                    //Guarda una tabla con los datos requeridos para realizar la consulta del FILLEDI, se utilizara para una carga Masiva.
                    //if($VistaPanel==1){
                      /* 
                      DATA_EDI[0] -> IDIMPCOS Indice de  tabla de Importadores Condignatarios
                      DATA_EDI[1] -> IMP_ABI_KEY Codigo de  ficha del importador en ABI
                      DATA_EDI[2] -> CONS_ABI_KEY Codigo de  ficha del consignatario en ABI
                      DATA_EDI[3] -> $krelfc Key de la  caja en la tabla de  Cajas
                      DATA_EDI[4] -> box Caja a procesar
                      DATA_EDI[5] -> VistaPanel Indica si se muestra el panel de pendientes(1) o panel de procesados(2)
                      DATA_EDI[6] -> StatusCat Indica  si la caja tiene  productos nuevos
                      DATA_EDI[7] -> 0 Bandera para identificar si la caja se  va a procesar en el evento masivo, 0 no aplica | 1 Aplica
                      DATA_EDI[8] -> x Indice del registro de la tabla, se  utiliza para 
                      DATA_EDI[9] -> '' Espacio para  capturar el SCAC
                      DATA_EDI[10] -> manuf Manufacturador default del Importador
                      */
                      $data->DATA_EDI[$x]=array($IDEDIMPCOS,$IMP_ABI_KEY, $CONS_ABI_KEY,$krelfc,$box,$VistaPanel,$StatusCat,0,$x,'',$manuf);
                    //}
                    $data->CInv=$invoice;
                }
            }else{
                $data->data[0]=array('No data','','','','','','','','','','',''); 
            }
            $data->EntRangoInicio=$cliente[0]['Consecutivo'];
            $data->EntRangoFin=$cliente[0]['EntRangoFin'];
            $data->Folios=$cliente[0]['Folios'];
            $data->Observaciones=$cliente[0]['Observaciones'];
            
            return ($data);  
          }
          else{
            return "Error: No se detecto el Cliente.";  
          }
          
        }
        //Fin de bloque revisado y actualizado
        //Validar si est Obsoleta
        static public function ctrReadXML($ruta,$clave_ABI,$TipoCat){

          switch ($clave_ABI){
            case 1946:
              $datos_gnl=ctrEDI1946($ruta);
              break;
            default:
            $datos_gnl=array();
          }
          $cont_archivos=0;
          $cont_lineas=1;
          $data=new stdClass();
          $path=$_POST["ruta"];
          $archivos=glob($path."/*.{xml,XML}", GLOB_BRACE);
          $datos[][]=array("Line"=>0,"Factura"=>'',"NoIdentificacion"=>'',"Bultos"=>'',"Cantidad"=>'',"Peso"=>'',"ClaveUnidad"=>'',"Descripcion"=>'',"ValorUnitario"=>'',"Importe"=>'',
                        'Acciones'=>"",'HTS'=>"");
          $pbruto=0;
          $caja='';
          $tbultos=0;
          foreach($archivos as  $XML){
            $xml = simplexml_load_file($XML);
            $ns = $xml->getNamespaces(true);
            $cfdi = $xml->xpath('//cfdi:Comprobante');
            $receptor = $xml->xpath('//cfdi:Comprobante//cfdi:Receptor');
            $factura = utf8_decode($cfdi[0]['Folio']);
            $fecha = utf8_decode($cfdi[0]['Fecha']);
            $subtotal = utf8_decode($cfdi[0]['SubTotal']);
            $moneda = utf8_decode($cfdi[0]['Moneda']);
            $tc = utf8_decode($cfdi[0]['TipoCambio']);
            $total = utf8_decode($cfdi[0]['Total']);
            $importador = preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($receptor[0]['Nombre']));
            $c = count($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto'));
            $concepto = $xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto');
            $bultos=0;
            $bruto=0;
            for ($x=0;$x<=$c-1;$x++){
              //Coloca el peso y  bulto a  la primer linea de la mercancia de  cada  factura
              if($x==0)
              {
                if(sizeof($datos_gnl)!=0){
                  $c_dg=count($datos_gnl);
                  for($i=0;$i<=$c_dg-1;$i++){
                    if($factura==$datos_gnl[$i]['INVOICE']){
                      $bruto=round($datos_gnl[$i]['BRUTO']);
                      $bultos=$datos_gnl[$i]['BULTOS'];
                      $caja=$datos_gnl[$i]['CAJA'];
                      $pbruto+=$bruto;
                      $tbultos+=$bultos;
                    }
                  }
                }
              }
              if(isset($concepto[$x]['NoIdentificacion'])){
                //SI no  esta  lo damos de  alta
                //Si esta,  traemos de ABI su descripcion, numero de  parte, HTS y  HTS1
                $query = ModeloopEdi::MdlReadXML_CatManual($clave_ABI,$TipoCat,$concepto[$x]['NoIdentificacion']);
                //Colocar accion enc aso de  error, actualmente  entra a esta  funcion.
                if(!empty($query)){
                  $NoIdentificacion=$query["PRODUCTO"];
                  $Descripcion=$query["DESCRIPCION"];
                  $HTS=$query["HTS"];
                  $Acciones='<div class="btn-group">'.
                  '<button type="button" class="btn btn-success btn-smy btnUpdateProducto">'.
                                              '<i class="fas fa-check"></i>'.
                                            '</button>'. 
                                          '</div>';
                }
                else{
                  $NoIdentificacion=utf8_decode($concepto[$x]['NoIdentificacion']);
                  if(isset($concepto[$x]['Descripcion'])){$Descripcion=utf8_decode($concepto[$x]['Descripcion']);}
                  $HTS='';
                  $Acciones='<div class="btn-group">'.
                                '<button type="button" class="btn btn-danger btn-smy btnAddProducto" id="btnAddProd'.$cont_lineas.'" name="btnAddProd'.$cont_lineas.'" data-index="'.($cont_lineas-1).'" data-toggle="tooltip" data-original-title="No esta en el Catalogo" >'. 
                                    '<i class="fas fa-plus"></i> Add'.
                                '</button>'. 
                            '</div>'; 
                }
              }
              if(isset($concepto[$x]['Cantidad'])){$Cantidad=utf8_decode($concepto[$x]['Cantidad']);}
              if(isset($concepto[$x]['ClaveUnidad'])){$ClaveUnidad=utf8_decode($concepto[$x]['ClaveUnidad']);}
              if(isset($concepto[$x]['ValorUnitario'])){$ValorUnitario=utf8_decode($concepto[$x]['ValorUnitario']);}
              if(isset($concepto[$x]['Importe'])){$Importe=utf8_decode($concepto[$x]['Importe']);}
              $datos[$cont_lineas-1]=array($cont_lineas,$factura,$NoIdentificacion,$bultos,$Cantidad,$bruto,$ClaveUnidad,$ValorUnitario,$Importe,$Descripcion,$HTS,$Acciones);
              $cont_lineas+=1;
            }
            $cont_archivos+=1;
          }
          $data->data=$datos;
          $data->factura=$factura;
          $f=explode("T",$fecha);
          $data->fecha=$f[0];
          $data->subtotal=$subtotal;
          $data->moneda=$moneda;
          $data->tc=$tc;
          $data->total=$total;
          $data->importador=$importador;
          $data->referencia=str_pad(substr($factura,0,9), 9, "0", STR_PAD_LEFT);
          $Clv_Cliente=ModeloopEdi::MdlImporter_Cliente($clave_ABI);
          $data->cliente=$Clv_Cliente[0];
          $scac=ModeloopEdi::MdlmldGenerarEdi_scac();
          $data->scac=$scac;
          $manuf=ModeloopEdi::MdlmldGenerarEdi_manuf($clave_ABI);
          $data->manuf=$manuf;
          $data->pbruto=$pbruto;
          $data->caja=$caja;
          $data->tbultos=$tbultos;
          return ($data);
        }
        static public function ctrslcmldAddCatABI_CatalogoABI($IMP_ABI_KEY){
          $respuesta=ModeloopEdi::MdlslcmldAddCatABI_CatalogoABI($IMP_ABI_KEY);
          return ($respuesta);  
        }
        static public function ctrmldGenerarEdi_btnProcEdi($datos_edi){
            //VALORES DE CBRIS
            //TOMA EL  ULTIMO ENTRY
            //VAR_DUMP($datos_edi);
            //die();
            $Accion=0;
            $query=ModeloopEdi::MdlClientes($datos_edi['IDEDCLI'],'2');
            $efill =$query[0]['codigo_cb'];
            //Es  necesario  antualizar al momento de la  ejecucion, de lo contrario puede  marcar error de  multiples entryes
            //Agregar  validacion de  EDIS
            //Se debe  enviar que tipo de  catalago  utiliza para  realizar la  validacion  y  el  incremento a la  tabla  correspondiente
            //Se debe  separar la funcion, el proceso del edi y las  validaciones  deben estar  aparte.
            if($datos_edi['EDI_Entry']!='' || is_null($datos_edi['EDI_Entry'])){
              $entry=$datos_edi['EDI_Entry'];
              $Accion=3;
              //Si es  verdadero se  salta hasta la  bandera EDI
              goto EDI;
            }
            else{
              $entry=$query[0]['Consecutivo'];
              $Accion=2;
            }
            if($query[0]['Folios']>0){
              //PRIMERO  BUSCARA  SI HAY FOLIOS  PENDIENTES POR  ASIGNAR
              $EntryReciclado=ModeloopEdi::MdlmldGenerarEdi_ConsultarEntryReciclado($query[0]['CatRango'],$datos_edi['IDEDCLI']);
              //VAR_DUMP($EntryReciclado);
              if(count($EntryReciclado)>0){
                ModeloopEdi::MdlmldGenerarEdi_EliminarEntryReciclado($EntryReciclado[0]['ENTRY_LIBERADO']);
                $entry=$EntryReciclado[0]['ENTRY_LIBERADO'];
                //VAR_DUMP($entry);
                goto EDI;
              }
              if($query[0]['CatRango']==0){
                $query=ModeloopEdi::MdlmldGenerarEdi_IncRango_Importadores($datos_edi['IDEDCLI'],(int)$entry+1);
              }
              else{
                $query=ModeloopEdi::MdlmldGenerarEdi_IncRango_CAT((int)$entry+1,$query[0]['CatRango']);
              }
              if($query=='ok'){
                  EDI:
                  //VALORES DEL  MODAL
                  //$fact = $datos_edi['Invoice'];
                  //$fecha = $datos_edi['InvDate'];                            //REVISAR SI ES LA FECHA DE  FACTURA O DE  ENTRY
                  $importer = $datos_edi['Importer'];
                  $consigner = $datos_edi['Consigneer'];
                  $customer = $datos_edi['Customer'];
                  $scac = $datos_edi['Scac'];
                  $caja = $datos_edi['Caja'];
                  $puerto = $datos_edi['Puerto'];
                  $locacion = $datos_edi['Location'];
                  //$peso = $datos_edi['Peso'];
                  $origen = $datos_edi['Origen'];
                  $manufactura = $datos_edi['Manufactura'];
                  $bultos = $datos_edi['Bultos'];
                  $medida = $datos_edi['Medida'];
                  $referencia = $datos_edi['Referencia'];
                  $ruta = $datos_edi['Ruta'];                                   //requiere  recibir la ruta del la carpeta
                  $lineas = $datos_edi['Lineas'];
                  $TIOP=$datos_edi['TIOP'];                                     //SE  AGREGARON ESTOS  2 VALORES
                  $edifile = $ruta.'/EDIS'.'/'.$entry.'.EDI';                   //PREPARAMOS EL ARCHIVO EDI
                  if (file_exists($edifile)){
                    unlink($edifile);
                  }
                  $a = fopen($edifile,'a'); 
                  //INICIAMOS PROCESO DE FORMATEO DE EDI
                  $var = '';
                  $cont_lineas = count($datos_edi['Lineas']);
                  for($x=0;$x<=$cont_lineas-1;$x++){
                      //PROCESAMIENTO DE  FECHA
                      $fechaent = strtotime($datos_edi['Date']);                //REVISAR SI ES LA FECHA DE  FACTURA O DE  ENTRY
                      $m = date('m',$fechaent);
                      $d = date('d',$fechaent);

                      //EXTRACCION DE DATOS DE LOS PRODUCTOS
                      $clavexml = $lineas[$x][2];
                      //CALCULO DE IMPORTE POR LINEA
                      $v_net = number_format($lineas[$x][4]*$lineas[$x][8],2,'','.');
                      $v_net = str_replace('.','',$v_net);
                      //EXTRACCION DE PESO 
                      $g_peso = number_format($lineas[$x][5],0,'.','');
                      $g_peso = str_replace('.','',$g_peso);
                      $n_peso = number_format($lineas[$x][6],0,'.','');
                      $n_peso = str_replace('.','',$n_peso);
                      $punit = round($lineas[$x][7],2);
                      $punit = str_replace('.','',$punit);
                      //CALCULO DE  VALORES DEL PRODUCTO (MT2,MT3,ETC)
                      $value =  ROUND($lineas[$x][4]*$lineas[$x][14],2);
                      $value =  strval($value);
                      //$value='';
                      //INICIA  CREACION DE EDI $EDI['Cus_Ref']=$data->cus_ref
                      if($datos_edi['Cus_Ref']!='' || $datos_edi['Cus_Ref']!=null){                               // EVALUA SI EL EDI TRAE CUS REFERENCE
                        //$var.= str_pad(substr($datos_edi['Cus_Ref'],0,10), 10, " ", STR_PAD_RIGHT); 	            //(ESTE DATO LLENA EL CAMPO CUST REF DE LA PRIMER  VENTANA AL CAMBIARLO  TODAS LAS  FACTURAS  SE  CAMBIARAN EN LA SEGUNDA PANTALLA)
                        $var.= str_pad(substr($datos_edi['Cus_Ref'],-10), 10, " ", STR_PAD_RIGHT);
                      }
                      else{
                        //$var.= str_pad(substr($lineas[$x][1],0,10), 10, " ", STR_PAD_RIGHT); 			                // Invoice                    --> 10 
                        $var.= str_pad(substr($lineas[$x][1],-10), 10, " ", STR_PAD_RIGHT);
                      }
                      $var.= str_pad($lineas[$x][0], 3, "0", STR_PAD_LEFT); 				                              // Line                       --> 03
                      $var.= str_pad($efill, 4, " ", STR_PAD_RIGHT); 			                                        // Filer Code                 --> 04
                      $var.= str_pad($entry, 7, "0", STR_PAD_LEFT); 			                                        // Entry NO                   --> 07
                      $var.= str_pad(substr($importer,0,6), 6, "0", STR_PAD_LEFT);  		                          // Importer                   --> 06
                      $var.= str_pad(substr($consigner,0,6), 6, "0", STR_PAD_LEFT); 		                          // Consignee                  --> 06
                      $var.= str_pad(substr($customer,0,6), 6, "0", STR_PAD_LEFT); 		                          // Shipper                    --> 06
                      $var.= substr(date('Y',$fechaent),-2);												                              // Year                       --> 02
                      $var.= str_pad($m, 2, "0", STR_PAD_LEFT); 												                          // Month                      --> 02
                      $var.= str_pad($d, 2, "0", STR_PAD_LEFT);												                            // Day                        --> 02
                      $var.= substr($lineas[$x][12],0,1); 												                                // HTS / SPI CODE             --> 01 Extraer este  valir del  Sistema, antes tenia N   20/07/01 Validar el Nafta
                      $var.= str_pad(str_replace('.','',$lineas[$x][11]), 11, " ", STR_PAD_RIGHT); 							  // HTS DUTIABLE               --> 11
                      $var.= str_pad($v_net, 11, "0", STR_PAD_LEFT);									                              // HTS DUT/VALUE VALOR NETO2D --> 11
                      $var.= '           ';									                                                      // 9802 HTS  N/D              --> 11
                      //$var.= str_pad($punit, 11, "0", STR_PAD_LEFT);									                            // 9802 N/D VALUE             --> 11
                      $var.= '00000000000';	
                      $var.= '           ';									                                                      // 9801 HTS PACK              --> 11
                      $var.= '00000000000';	                        									                            // 9801 PACK  VAL             --> 11
                      $var.= str_pad(substr($TIOP,0,2), 2, "0", STR_PAD_RIGHT);  											            // Tipo de Operacion          --> 02 Extraer de Sistema, no dejar  default.
                      $var.= '     '; 											                                                      // Flight number              --> 05
                      $var.= str_pad(round(substr($n_peso,0,6)),6,"0",STR_PAD_LEFT);                              // Weight Kilos               --> 06
                      $var.= str_pad(substr($lineas[$x][10],0,25),25," ",STR_PAD_RIGHT); 				                  // Descripcion                --> 25
                      $var.= str_pad(round(substr($n_peso,0,6)/.453592,0),6,"0",STR_PAD_LEFT); 		                // Weight Libras              --> 06
                      $var.= str_pad(round(substr($lineas[$x][4],0,9),0),9,"0",STR_PAD_LEFT); 								    // Cantidad                   --> 09
                      $var.= '000000075';															                                            // FREIGHT CHGS               --> 09 donde se saca esto ?? freight charges 9
                      $var.= str_pad(round(substr($lineas[$x][3],0,5),0),5," ",STR_PAD_LEFT);                     // Numero de Paquetes         --> 05 A diferencia del Sr Adolfo, este  sistema envia las  paletas declaradas en los pdf, si no existen se  sumarizan las cantidades para 
                                                                                                                  //                                   enviarlas en un solo registro, en lugar la cantidad, ejemplo Innovia.
                      $var.= '           ';														                                            // Blanck space               --> 11
                      $var.= str_pad(substr($origen,0,2), 2, "0", STR_PAD_RIGHT);   															// Country of Origin          --> 02 los usuarios lo cambiaran en ABI cuando lo requieran, Tomar del  Sistema, no dejar default
                      $var.= str_pad(trim($clavexml), 23, " ", STR_PAD_RIGHT); 					                          // Part Number                --> 23 
                      $var.= str_pad(substr($manufactura,0,16), 16, " ", STR_PAD_RIGHT); 						              // Manufacture ID Key MID     --> 16
                      $var.= str_pad($scac, 4, " ", STR_PAD_RIGHT); 																              // Scac                       --> 04
                      $var.= str_pad(substr($caja,0,16), 16, " ", STR_PAD_RIGHT); 								                // Caja Trailer               --> 16
                      $var.= '   '; 																                                              // FILER for future use       --> 03
                      $var.= str_pad(substr($medida,0,5), 5, " ", STR_PAD_RIGHT);														      // Unit of Measure Codes UMC  --> 05
                      $var.= '     ';	                                                                            // Cantidad de  Facturas      --> 05
                      $var.= 'N    ';                                                                             // Multicountries             --> 05 
                      //$var.= '                                   ';								                                // Notes                      --> 35 
                      $var.= str_pad(substr($referencia,0,35),35," ",STR_PAD_RIGHT);                              // Notes                      --> 35    
                      $var.= str_pad($puerto, 4, " ", STR_PAD_RIGHT); 																            // Port                       --> 04
                      $var.= str_pad(substr($locacion,0,4), 4, " ", STR_PAD_RIGHT); 							                // Location                   --> 04
                      $var.= 'A'; 																	                                              // Action                     --> 01
                      $var.= ' '; 																	                                              // Future Use                 --> 01 
                      $var.= str_pad(round(substr($g_peso,0,6),0),6,"0",STR_PAD_LEFT);                            // Peso / GROSS WGT           --> 06
                      $var.= 'N'; 																	                                              // Additional info            --> 01
                      $var.= '            '; 														                                          // Air Way - AWB              --> 12 NO SE USAN EN  ABI, NO APLICAN CAMBIOS
                      $var.= '          '; 														                                            // Blank space future use     --> 10 NO SE USAN EN  ABI, NO APLICAN CAMBIOS
                      $var.= '    '; 																                                              // Cont key - PROD. CONT      --> 04 NO SE USAN EN  ABI, NO APLICAN CAMBIOS
                      if($datos_edi['InvAsBR']==1){                                                               // Referencia                 --> 09 BROKER REF (SI SE AGREGA  AQUI  SE  COLOCA EL DATO EN EL CAMPO DE BROKER REF)
                        $I_reference=strpos($referencia,'-');
                        $S_reference=substr($referencia,0,$I_reference);
                        $var.= str_pad(substr($S_reference,0,9),9," ",STR_PAD_RIGHT);
                      }
                      else{
                        $var.= str_pad(substr($entry,0,9),9," ",STR_PAD_RIGHT); 				                            
                      }
                      
                      $var.= ' '; 																	                                              // FTZ STATUS                 --> 01
                      $var.= '        ';															                                            // FTZ FILDATE                --> 08
                      $var.='     ';															                                                // PLANT NUMBER               --> 05
                      $var.=' ';																                                                  // HTS SEC SPI                --> 01
                      //$var.='0';																                                                  // ENTRY DIG                  --> 01 NO SE USAN EN  ABI, NO APLICAN CAMBIOS
                      //$var.='000000000000';                                                                     // CBP QTY 1-3ra ventana QTY2 --> 09 
                      $var.=str_pad(substr(str_replace('.','',ROUND($value,0)),0,9),9,"0",STR_PAD_LEFT);
                      //$var.='000000000000';                                                                     // CBP QTY 2                  --> 12 
                      //$var.='000000000000';                                                                     // CBP QTY 3                  --> 12
                      //$var.='           ';                                                                      // HTS 9903 FOR COUNTRY       --> 11
                      //$var.='           ';                                                                      // HTS 9903 FOR PRODUCT       --> 11
                      fwrite($a,utf8_encode($var).PHP_EOL);
                      $var='';
                  }
                  fclose($a);
                  $K_RELF_C_new='';
                  if($datos_edi['krelfc']==''){
                    $K_RELF_C_new=struuid(true);
                    $Accion=1;
                  }
                  //Codigo Original
                  //$query=ModeloopEdi::MdlUpdateEntryBox($Accion,$datos_edi['IDEDCLI'],$entry,$datos_edi['Caja'],$datos_edi['krelfc'], $K_RELF_C_new,str_pad($scac, 4, " ", STR_PAD_RIGHT),str_pad(substr($manufactura,0,16), 16, " ", STR_PAD_RIGHT));
                  //PARA LA NORMALIZACION DE LA  TABLA LA  CONSULTA  UPDATE SE ELIMINA PARA CREAR  UN  INSERT A LA NUEVA  TABLA
                  //La  funcion insertra  y  actualiza al mismo  tiempo.
                  $query=ModeloopEdi::MdlInsertEntryAuto($Accion,$datos_edi['IDIMPCOS'],$entry,$datos_edi['Caja'],$datos_edi['krelfc'], $K_RELF_C_new,$scac,$manufactura,str_pad(substr($origen,0,2), 2, "0", STR_PAD_RIGHT),str_pad($puerto, 4, " ", STR_PAD_RIGHT),str_pad(substr($TIOP,0,2), 2, "0", STR_PAD_RIGHT),str_pad(substr($locacion,0,4), 4, " ", STR_PAD_RIGHT));
                  if($query=='ok'){	
                    header("Content-type: text/plain");
                    header("Content-Disposition: attachment; filename=".$edifile);
                    ob_start();     
                    readfile($edifile);
                    $txtData = ob_get_contents();
                    ob_end_clean();
                    $response =  array(
                      'op' => 'ok',
                      'entry' =>$entry,
                      'file' => "data:text/plain;base64,".base64_encode($txtData)
                    );
                    return $response;
                  }
                  else{ 
                    return 'Error Asignando Entry a Caja: '.$query; 
                  }
              }
              else{ 
                  return 'Error de  Entries: '.$query; 
              }
            }
            else{
              return 'No quedan Folios para procesar la operacion, consulte al Administrador.'; 
            }
        }

        static public function ctrCountUpFiles($ruta){
          $data = new stdClass();
          $archivos=glob($ruta."/*.{pdf,PDF}", GLOB_BRACE);
          if(isset($archivos)){$data->pdf=count($archivos);}
          else{ $data->pdf=0;}
          $archivos=glob($ruta."/*.{xml,XML}", GLOB_BRACE);
          if(isset($archivos)){$data->xml=count($archivos);}
          else{$data->xml=0;}
          $archivos=glob($ruta."/*.{xls,XLS,xlsx,XLSX}", GLOB_BRACE);
          if(isset($archivos)){$data->xls=count($archivos);}
          else{$data->xls=0;}
          $data->ruta=str_replace('C:/','',$ruta);
          //print_r($data);
          return ($data);  
        }
        static public function ctropEdiFillEDI_LINES($IMP_ABI_KEY,$CONS_ABI_KEY,$TipoCat,$IDEDIMPCOS,$krelfc,$caja,$VistaPanel,$MERGE){
          //VAR_DUMP($IMP_ABI_KEY,$CONS_ABI_KEY,$TipoCat,$IDEDIMPCOS,$krelfc,$caja,$VistaPanel,$MERGE);
          $data = new stdClass();
          $caja=ModeloopEdi::MdlEDIS_LINES($IDEDIMPCOS,$krelfc,$caja,$VistaPanel,$MERGE);
          
          $tabla[]=array();
          $count_c=count($caja);
          //VAR_DUMP($caja,$count_c);
          $ProductosNuevos=0;
          for($i=0;$i<=$count_c-1;$i++){
            $invoice=$caja[$i]['INVOICE']; 
            //var_dump($IMP_ABI_KEY,$CONS_ABI_KEY,$TipoCat,$caja[$i]['KeyProduct']);
            $query = ModeloopEdi::MdlReadXML_CatManual($IMP_ABI_KEY,$CONS_ABI_KEY,$TipoCat,$caja[$i]['KeyProduct']);
            if(!empty($query)){
              $article=$query["PRODUCTO"];
              $description=$query["DESCRIPCION"];
              $HTS=$query["HTS"];
              $SPI=$query["SPI_CODE"];
              $qty2=$query['Value'];
              $Acciones='<div class="btn-group">'.
                          '<button type="button" class="btn btn-success btn-smy btnUpdateProducto"  id="btnUpdateProducto'.$i.'" name="btnUpdateProducto'.$i.'" data-index="'.$i.'" data-clientekeyproduct="'.$caja[$i]['KeyProduct'].'" data-abiproducto="'.$article.'" data-abiproductodesc="'.$description.'" data-clientedescripcion="'.$caja[$i]['Description'].'" data-spicode="'.$SPI.'" >'.
                              '<i class="fas fa-check"></i>'.
                          '</button>'. 
                        '</div>';
            }
            else{
              $article=utf8_decode($caja[$i]['KeyProduct']);
              $description=utf8_decode($caja[$i]['Description']);
              $HTS='';
              $SPI='';
              $qty2=0;
              $Acciones='<div class="btn-group">'.
                            '<button type="button" class="btn btn-danger btn-smy btnAddProducto" id="btnAddProd'.$i.'" name="btnAddProd'.$i.'" data-index="'.$i.'" data-clientekeyproduct="'.$caja[$i]['KeyProduct'].'" data-toggle="tooltip" data-original-title="No esta en el Catalogo" >'. 
                                '<i class="fas fa-plus"></i> Add'.
                            '</button>'. 
                        '</div>'; 
              $ProductosNuevos+=1;
            }
            /*Esta  parte del Codigo  guarda  en la primer  linea el total de  Bultos o Cantidad*/
            //En caso de  Varias  Facturas  Suma las  Cantidades  y Pesos de  todas las  
            if($caja[$i]['LINE']==1){
              $pallets=round($caja[$i]['Quantity'],2);
            }
            else{
              $pallets=0;
              $gross=0;
            }
            $lin_gross=round($caja[$i]['Lin_Gross_Weight'],2);
            $lin_net=round($caja[$i]['Lin_Net_Weight']);
            $qty_merc=round($caja[$i]['QTY_MERC'],6);
            $unit=$caja[$i]['KeyUnit'];
            $amount_merc=round($caja[$i]['Amount'],6);
            $line_total=round((float)$qty_merc*(float)$amount_merc,2);
            $tabla[$i]=array($i+1,$invoice,$article,$pallets,$qty_merc,$lin_gross,$lin_net,$unit,$amount_merc,$line_total,$description,$HTS,$SPI,$Acciones,$qty2);
            //LA DE  QTY2 SE  UTILIZA PARA EL CALCULO DE METROS CUADRADOS   METROS CUBICOS
          }
          $data->ProductosNuevos=$ProductosNuevos;
          $data->tabla=$tabla;
          return $data;
        }   

        static public function ctropEdiFillEDI($krelfc,$caja,$IMP_ABI_KEY,$CONS_ABI_KEY,$IDEDIMPCOS,$TipoCat,$VistaPanel){
          $data = new stdClass();
          $Detail_OP=ModeloopEdi::MdlEDIS_DETAILS($IDEDIMPCOS,$krelfc,$caja,$VistaPanel);
          $lineas=ControladoropEdi::ctropEdiFillEDI_LINES($IMP_ABI_KEY,$CONS_ABI_KEY,$TipoCat,$IDEDIMPCOS,$krelfc,$caja,$VistaPanel,0);
          $data->data=$lineas->tabla;
          if($Detail_OP[0]['C_DATE']!=''){
            $data->fecha=date("Y-m-d", strtotime($Detail_OP[0]['C_DATE']));
          }
          else{
            $data->fecha=date('Y-m-d');
          }
          $data->ENTRY=$Detail_OP[0]['ENTRY'];
          $data->factura=$Detail_OP[0]['INVOICE'];
          $data->cus_ref=$Detail_OP[0]['CUST_REF'];
          $data->subtotal=$Detail_OP[0]['Invoice_SubTotal'];
          $data->moneda=$Detail_OP[0]['Currency'];
          $data->tc=$Detail_OP[0]['Invoice_Exchange'];
          $data->total=$Detail_OP[0]['Invoice_Total'];
          $data->referencia=str_pad(strval($Detail_OP[0]['INVOICE']).'-'.strval($Detail_OP[0]['C_INV']), 35, " ", STR_PAD_RIGHT);//SE AGREGARAN COMO NOTAS
          $data->scac='';//Se utiliza?
          $data->OldScac=$Detail_OP[0]['SCAC'];
          $data->manuf=''; //Se utiliza?
          $data->OldManuf=$Detail_OP[0]['Manufacturer'];
          $data->pbruto=$Detail_OP[0]['Gross_Weight'];
          $data->tbultos=$Detail_OP[0]['Quantity'];
          $data->caja=$Detail_OP[0]['BOX_NO'];
          $data->puerto=$Detail_OP[0]['puerto'];
          $data->origen=$Detail_OP[0]['origen'];
          $data->location=$Detail_OP[0]['location'];
          $data->tiop=$Detail_OP[0]['tiop'];
          $data->UOM=$Detail_OP[0]['UOM'];
          $data->InvAsBR=$Detail_OP[0]['InvAsBR'];
          $data->ProductosNuevos=$lineas->ProductosNuevos; //ACTUALIZAR EL EN JS
          return ($data);  
        }
        
        //RESPALDO DEFUNCION
        /*static public function ctropEdiFillEDI($krelfc,$caja,$IMP_ABI_KEY,$CONS_ABI_KEY,$IDEDIMPCOS,$TipoCat,$VistaPanel){
          $data = new stdClass();
          $tabla[]=array();
          $pbruto=0;
          $tbultos=0;
          //SEPARAR LAS OPERACIONES,  EXTRAER  2  CONSULTAS UNA PARA LAS PARTES  Y OTRA POR LOS DATOS GENERALES.
          //$caja=ModeloopEdi::MdlEDIS($IDEDIMPCOS,$krelfc,$caja,$VistaPanel,0); // REMOVIDA SE  DIVIDIO EN  2 
          $caja=ModeloopEdi::MdlEDIS_LINES($IDEDIMPCOS,$krelfc,$caja,$VistaPanel,0);
          $Detail_OP=ModeloopEdi::MdlEDIS_DETAILS($IDEDIMPCOS,$krelfc,$caja,$VistaPanel);

          $count_c=count($caja);
          $ProductosNuevos=0;
          //ESTA SECCION SE  CALCULA  EN BASE LOS NUMEROS DE PARTE DEL  IMPORTADOR,SE DEBE  ENVIAR EL  ABIKEY 
          //SE  TOMA  EL CATALOGO EN BASE EL IMPORTADOR
          for($i=0;$i<=$count_c-1;$i++){
            $reference[$i]=$caja[$i]['INVOICE'];
            $invoice=$caja[$i]['INVOICE']; 
            $query = ModeloopEdi::MdlReadXML_CatManual($IMP_ABI_KEY,$CONS_ABI_KEY,$TipoCat,$caja[$i]['KeyProduct']);
            //var_dump($query);
            if(!empty($query)){
              $article=$query["PRODUCTO"];
              $description=$query["DESCRIPCION"];
              $HTS=$query["HTS"];
              $SPI=$query["SPI_CODE"];
              $qty2=$query['Value'];
              $Acciones='<div class="btn-group">'.
                          '<button type="button" class="btn btn-success btn-smy btnUpdateProducto"  id="btnUpdateProducto'.$i.'" name="btnUpdateProducto'.$i.'" data-index="'.$i.'" data-clientekeyproduct="'.$caja[$i]['KeyProduct'].'" data-abiproducto="'.$article.'" data-abiproductodesc="'.$description.'" data-clientedescripcion="'.$caja[$i]['Description'].'" data-spicode="'.$SPI.'" >'.
                              '<i class="fas fa-check"></i>'.
                          '</button>'. 
                        '</div>';
            }
            else{
              $article=utf8_decode($caja[$i]['KeyProduct']);
              $description=utf8_decode($caja[$i]['Description']);
              $HTS='';
              $SPI='';
              $qty2=0;
              $Acciones='<div class="btn-group">'.
                            '<button type="button" class="btn btn-danger btn-smy btnAddProducto" id="btnAddProd'.$i.'" name="btnAddProd'.$i.'" data-index="'.$i.'" data-clientekeyproduct="'.$caja[$i]['KeyProduct'].'" data-toggle="tooltip" data-original-title="No esta en el Catalogo" >'. 
                                '<i class="fas fa-plus"></i> Add'.
                            '</button>'. 
                        '</div>'; 
              $ProductosNuevos+=1;
            }
            //Esta  parte del Codigo  guarda  en la primer  linea el total de  Bultos o Cantidad//
            //En caso de  Varias  Facturas  Suma las  Cantidades  y Pesos de  todas las  
            if($caja[$i]['LINE']==1){
              $pallets=round($caja[$i]['Quantity'],2);
              $tbultos+=(float)$caja[$i]['Quantity'];
              $pbruto+=(float)$caja[$i]['Gross_Weight'];
            }
            else{
              $pallets=0;
              $gross=0;
            }
            $lin_gross=round($caja[$i]['Lin_Gross_Weight'],2);
            $lin_net=round($caja[$i]['Lin_Net_Weight']);
            $qty_merc=round($caja[$i]['QTY_MERC'],6);
            $unit=$caja[$i]['KeyUnit'];
            $amount_merc=round($caja[$i]['Amount'],6);
            $line_total=round((float)$qty_merc*(float)$amount_merc,2);
            $tabla[$i]=array($i+1,$invoice,$article,$pallets,$qty_merc,$lin_gross,$lin_net,$unit,$amount_merc,$line_total,$description,$HTS,$SPI,$Acciones,$qty2);
            //LA DE  QTY2 SE  UTILIZA PARA EL CALCULO DE METROS CUADRADOS   METROS CUBICOS
          }
          $data->data=$tabla;

          if($caja[0]['C_DATE']!=''){
            $data->fecha=date("Y-m-d", strtotime($caja[0]['C_DATE']));
          }
          else{
            $data->fecha=date('Y-m-d');
          }
          $data->ENTRY=$caja[0]['ENTRY'];
          $data->factura=$caja[0]['INVOICE'];
          $data->cus_ref=$caja[0]['CUST_REF'];
          $data->subtotal=$caja[0]['Invoice_SubTotal'];
          $data->moneda=$caja[0]['Currency'];
          $data->tc=$caja[0]['Invoice_Exchange'];
          $data->total=$caja[0]['Invoice_Total'];
          $reference=array_unique($reference);
          $data->referencia=str_pad(strval($reference[0]).'-'.strval(count($reference)), 35, " ", STR_PAD_RIGHT);//SE AGREGARAN COMO NOTAS
          $data->scac='';//Se utiliza?
          $data->OldScac=$caja[0]['SCAC'];
          $data->manuf=''; //Se utiliza?
          $data->OldManuf=$caja[0]['Manufacturer'];
          $data->pbruto=$pbruto;
          $data->ProductosNuevos=$ProductosNuevos;
          $data->tbultos=$tbultos;
          $data->caja=$caja[0]['BOX_NO'];
          $data->puerto=$caja[0]['puerto'];
          $data->origen=$caja[0]['origen'];
          $data->location=$caja[0]['location'];
          $data->tiop=$caja[0]['tiop'];
          $data->UOM=$caja[0]['UOM'];
          $data->InvAsBR=$caja[0]['InvAsBR'];
          return ($data);  
        }*/
        static public function ctrFillSplitCaja($IDEDIMPCOS,$Caja,$krelfc){
          $Invoices=ModeloopEdi::MdlFillSplitCaja($IDEDIMPCOS,$Caja,$krelfc);
          $cont=count($Invoices);
          if($cont>0){ 
              for($x=0;$x<$cont;$x++){
                  //Este campo solo tra el  id de la factura, en caso de  que la factura exista 2 veces
                  $krelfm = $Invoices[$x]['K_RELF_M'];
                  $invoice = $Invoices[$x]['Invoice'];
                  $invoicet = $Invoices[$x]['Invoice_Total'];
                  $gross = $Invoices[$x]['Gross_Weight'];
                  $quantity = $Invoices[$x]['Quantity'];
                  $Boton= '<div class="btn-group">'.
                            '<div class="row">'.
                              '<div class="col-6">'.
                                //'<button class="chkIsChecked chkIsChecked1" id="mdlSplitBoxCHK'.$x.'" name="mdlSplitBoxCHK'.$x.'" data-invoice="'.$invoice .'" data-krelfm="'.$krelfm .'" ><i class="fas fa-times"></i></button>'.
                                '<button class="btn btn-danger chkIsChecked" id="mdlSplitBoxCHK'.$x.'" name="mdlSplitBoxCHK'.$x.'" data-invoice="'.$invoice .'" data-krelfm="'.$krelfm .'" data-status=0 ><i class="fas fa-times"></i></button>'.
                              '</div>'.
                              '<div class="col-6">'.
                                '<button type="button" class="btn btn-danger btn-smy opEdi_mdlSplitBox_DelInvoice"  id="mdlSplitBox_DelInvoice'.$x.'" name="mdlSplitBox_DelInvoice'.$x.'" data-invoice="'.$invoice .'" data-krelfm="'.$krelfm .'" >'.
                                  '<i class="fas fa-trash"></i>'.
                                '</button>'.  
                              '</div>'.
                            '</div>'.  
                          '</div>';



                          
                  $data->Invoices[$x]=array($invoice,$invoicet,$gross,$quantity,$Boton); 
              }
          }else{
              $data->Invoices[0]=array('No data','','','',''); 
          } 
          return ($data);
        }
        static public function ctrSplitCajaFactura($datos){
          $K_RELF_C=struuid(true);
          $resultado='';
          foreach($datos['Facturas'] as $inv => $val){
            $UpInv=ModeloopEdi::MdlSplitCajaFactura($val[0],$K_RELF_C,$val[0]);
            $resultado.=$UpInv.' '.$val[1].' ';
          }
          return ($resultado);
        }
        static public function ctrSplitNewCajaFactura($Datos){
          $K_RELF_C=struuid(true);
          $resultado='';
          foreach($Datos['Facturas'] as $inv => $val){
            $UpInv=ModeloopEdi::MdlSplitNewCajaFactura($val[0],$K_RELF_C,$Datos['NewCaja'],$Datos['SCAC']);
            $resultado.=$UpInv.' '.$val[1].' ';
          }
          return ($resultado);
        }
        static public function ctrNewCajaFactura($Datos){
          $data=new stdclass();
          $K_RELF_C=struuid(true);
          $NewCajaFactura=ModeloopEdi::MdlNewCajaFactura($K_RELF_C,$Datos);
          $data->NewCajaFactura= $NewCajaFactura;
          $data->K_RELF_C= $K_RELF_C;
          $data->Caja= $Datos['NewCaja'];
          return ($data);
        }
        static public function ctrActualizarPesosBultos($IDEDCLI,$KRELFC,$CAJA,$datos_PesosBultos){
          $ActualizarPesosBultos=ModeloopEdi::MdlActualizarPesosBultos($IDEDCLI,$KRELFC,$CAJA,$datos_PesosBultos['Peso'],$datos_PesosBultos['Bultos']);
          //$ActualizarPesosBultos=ModeloopEdi::MdlActualizarPesosBultos_decimas($IDEDCLI,$KRELFC,$CAJA,$datos_PesosBultos['Peso'][1],$datos_PesosBultos['Bultos'][1]);
          return ($ActualizarPesosBultos);
        }
        static public function ctrEliminarCajaFactura($IDEDIMPCOS,$KRELFC,$CAJA){
          $EliminarCajaFactura=ModeloopEdi::MdlEliminarCajaFactura($IDEDIMPCOS,$KRELFC,$CAJA);
          return ($EliminarCajaFactura);
        }
        static public function ctrRelProdABI($ClaveProductoABI,$ClaveProducto,$DescripcionESP,$IMP_ABI_KEY,$CONS_ABI_KEY,$SPI_CODE,$tcat){
          $respuesta=ModeloopEdi::MdlRelProdABI(substr($ClaveProductoABI,0,23),substr($ClaveProducto,0,23),substr($DescripcionESP,0,25),substr($IMP_ABI_KEY,0,6),substr($CONS_ABI_KEY,0,6),$SPI_CODE,$tcat);
          return ($respuesta);  
        }
        static public function ctrUPDProdABI($ClaveProductoABI,$ClaveProducto,$IMP_ABI_KEY,$CONS_ABI_KEY,$SPI_CODE){
          $UPDProdABI=ModeloopEdi::MdlUPDProdABI($ClaveProductoABI,$ClaveProducto,$IMP_ABI_KEY,$CONS_ABI_KEY,$SPI_CODE);
          return ($UPDProdABI);
        }
        static public function ctrEliminarFactura($KRELFM){
          $EliminarFactura=ModeloopEdi::MdlEliminarFactura($KRELFM);
          return ($EliminarFactura);
        }
        static public function ctrCargaMasiva($datos_edi){
          //var_dump($datos_edi);
                      /* 
                      DATA_EDI[0] -> IDIMPCOS Indice de  tabla de Importadores Condignatarios
                      DATA_EDI[1] -> IMP_ABI_KEY Codigo de  ficha del importador en ABI
                      DATA_EDI[2] -> CONS_ABI_KEY Codigo de  ficha del consignatario en ABI
                      DATA_EDI[3] -> $krelfc Key de la  caja en la tabla de  Cajas
                      DATA_EDI[4] -> box Caja a procesar
                      DATA_EDI[5] -> VistaPanel Indica si se muestra el panel de pendientes(1) o panel de procesados(2)
                      DATA_EDI[6] -> StatusCat Indica  si la caja tiene  productos nuevos
                      DATA_EDI[7] -> 0 Bandera para identificar si la caja se  va a procesar en el evento masivo, 0 no aplica | 1 Aplica
                      DATA_EDI[8] -> x Indice del registro de la tabla, se  utiliza para 
                      DATA_EDI[9] -> '' Espacio para  capturar el SCAC
                      DATA_EDI[10] -> manuf Manufacturador default del Importador
                      DATA_EDI[11] -> Ruta
                      DATA_EDI[12] -> IDEDCLI
                      DATA_EDI[13] -> abi_key_clIENTE
                      */
          //$data = new stdClass();
          $EDI = new stdClass();
          $EDI = array();
          $c_edi=count($datos_edi);
          foreach($datos_edi as $ToEDI){
            $data=ControladoropEdi::ctropEdiFillEDI($ToEDI[3],$ToEDI[4],$ToEDI[1],$ToEDI[2],$ToEDI[0],'1',$ToEDI[5]);
            //var_dump($data);
            /*campos no utlizados
              $data['subtotal']
              $data['moneda']
              $data['tc']
              $data['subtotal']
              $data['importador'] --> Revisar si desde el inicio se puede  traer el nombre
              $data['consignatario'] --> Revisar si desde el inicio se puede  traer el nombre
              $data['scac']
              $data['Oldscac']
              $data['manuf']
              $data['OldManuf']
              $data['caja']
              $data['ProductosNuevos']

              Agregar  a data el custom reference y  validarlo en el procesamiento del EDI
            */
            $EDI['EDI_Entry']=$data->ENTRY;
            $EDI['Invoice']=$data->factura;
            $EDI['Cus_Ref']=$data->cus_ref;
            $EDI['Date']=$data->fecha;
            $EDI['Total']=$data->total;
            $EDI['Importer']=$ToEDI[1];
            $EDI['Consigneer']=$ToEDI[2];
            $EDI['Customer']=$ToEDI[13]; // Se puede quitar la consulta del cliente,  ya  viene desde el inicio
            $EDI['Referencia']=$data->referencia;
            $EDI['Scac']=$ToEDI[9];
            $EDI['Caja']=$ToEDI[4];
            $EDI['krelfc']=$ToEDI[3];
            $EDI['Puerto']=$data->puerto;
            $EDI['Location']=$data->location;
            $EDI['Peso']=$data->pbruto;
            $EDI['Origen']=$data->origen;
            $EDI['TIOP']=$data->tiop;
            $EDI['Manufactura']=$ToEDI[10];
            $EDI['Bultos']=$data->tbultos;
            $EDI['Medida']=$data->UOM; //Se  va  default
            $EDI['IDEDCLI']=$ToEDI[12];
            $EDI['Ruta']=$ToEDI[11]; // No se  agrego al DATA_EDI, se  inserto en el envio a esta funcion.
            $EDI['IDIMPCOS']=$ToEDI[0];
            $EDI['InvAsBR']=$ToEDI[0];
            $EDI['Lineas']=$data->data; 
            $respuesta=ControladoropEdi::ctrmldGenerarEdi_btnProcEdi($EDI);
          }
        }
        static public function ctrupdateABITables(){
          $updateABITables=ModeloopEdi::MdlupdateABITables();
          return ($updateABITables);
        }
        static public function ctropEdiUpFiles($IDEDCLI,$RUTA,$TC){
          //Cambiar el ciclo, en lugar de  buscar  todos los  importadores  solo buscar los que  coincidan con el  XML
          //Al  ejecutarse la  funcion ReadFilesOnFolder,  se puede  obtener una lista de  Receptores, que puede  usarse para  obtener el listado de clientes.
          //EL resto del codigo queda igual,
          //Como  segunda mejora, se puede  hacer que  ReadFilesOnFolder genere  arrays con los receptores
          //Debe  lleer  todos los  archivos  y segregarlos, para que la  consulta por  Importador  sea directa,  
          //se  ahorrarian los  cicloq eu recorren todos los XML y PDF  por  importador
          $Observaciones=array("Cargados"=>0,'No_XML'=>0,'No_PDF'=>0,'XMLSin_PDF'=>'','XML_NoValido'=>'','PDF_NoLayout'=>'','Error'=>'','Factura_Duplicada'=>'','XML_Desconidos'=>'');
          $Observaciones['XML_NoValido']=ReadFilesOnFolder($RUTA,$TC);

          $listImpOfCli=ModeloopEdi::MdllistImpOfCli($IDEDCLI);

          foreach($listImpOfCli as $importer){
           // echo $importer[1];
            $Observaciones=ctrUpFiles($importer[0],$importer[1],$RUTA,$Observaciones,$importer[3],$importer[4],$importer[5]);
          }
          $Observaciones['XML_Desconidos']=array_column( $GLOBALS['FilesXML'],'FILE');
          //AL FINALIZAR EL PROCESO  INICIALIZAR LAS  VARIABLES  GLOBALES: $GLOBALS['FilesPDF']  |  $GLOBALS['FilesXML'] 
          unset($GLOBALS['FilesPDF']  ,  $GLOBALS['FilesXML'] );
          return $Observaciones;
          //Crear consulta para traer la lista de importadores con los datos para la funcion de carga de  archivos
          //La  funcion de carga de  arvhivos  trae una  funcion para  leer errores de  archivos,  se  debe adaptar para que realice un array de los archivos que le corresponden a cada  importador
          //funcion a  recorrer: Se debe agregar el tipo de catalogo
        }
        static public function ctrfillTbClients(){
          $fillTbClients=ModeloopEdi::MdlfillTbClients();
          $c_fillTbClients=count($fillTbClients);
          if($c_fillTbClients>0){ 
              for($x=0;$x<$c_fillTbClients;$x++){
                //codigo_CB,CatRango,EntRangoInicio,EntRangoFin,CONSECUTIVO,Observaciones,f_alta
                  $IDEDCLI= $fillTbClients[$x]['IDEDCLI'];
                  $ABI_KEY = $fillTbClients[$x]['ABI_KEY'];
                  $nombre = $fillTbClients[$x]['nombre'];
                  $ruta = $fillTbClients[$x]['ruta'];
                  $codigo_CB = $fillTbClients[$x]['codigo_CB'];
                  $CatRango = $fillTbClients[$x]['CatRango'];
                  $EntRangoInicio= $fillTbClients[$x]['EntRangoInicio'];
                  $EntRangoFin = $fillTbClients[$x]['EntRangoFin'];
                  $CONSECUTIVO = $fillTbClients[$x]['CONSECUTIVO'];
                  $f_alta = $fillTbClients[$x]['f_alta'];
                  $Boton= '<div class="row">'.
                              '<button type="button" class="btn btn-secondary btn-smy opEdi_showmdlImporters" id="opEdi_mdlClientes_tbClientes_showImporters'.$x.'" name="opEdi_mdlClientes_tbClientes_showImporters'.$x.'" data-idedcli="'.$IDEDCLI .'" data-name="'.$nombre .'" >'.
                                '<i class="fas fa-users-cog"></i>'.
                              '</button>'.
                          '</div>';       
                  $data->Clients[$x]=array($ABI_KEY,$nombre,$ruta,$codigo_CB, $CatRango,$EntRangoInicio,$EntRangoFin,$CONSECUTIVO,$f_alta/*$Observaciones,*/,$Boton); 
              }
          }else{
              $data->Clients[0]=array('No data','','','','','','','','',''); 
          } 
          return ($data);
        }
        static public function ctrfillTbImporters($IDEDCLI){
          $fillTbImporters=ModeloopEdi::MdlfillTbImporters($IDEDCLI);
          $c_fillTbImporters=count($fillTbImporters);
          if($c_fillTbImporters>0){ 
              for($x=0;$x<$c_fillTbImporters;$x++){
                  $IDEDIMPCOS= $fillTbImporters[$x]['IDEDIMPCOS'];
                  $ABI_KEY = $fillTbImporters[$x]['IMP_ABI_KEY'];
                  $IMP_NAME  = $fillTbImporters[$x]['IMP_NAME'];
                  $Boton= '<div class="row">'.
                              '<button type="button" class="btn btn-secondary btn-smy opEdi_mdlImporters_ImporterDetail" id="opEdi_mdlImporters_btnDetail'.$x.'" name="opEdi_mdlImporters_btnDetail'.$x.'" data-idedimpcos="'.$IDEDIMPCOS .'" data-impname="'.$IMP_NAME .'" >'.
                                'Details'.
                              '</button>'.
                          '</div>';       
                  $data->Importers[$x]=array( $IDEDIMPCOS,$ABI_KEY,$IMP_NAME,$Boton); 
              }
          }else{
              $data->Importers[0]=array('No data','',''); 
          } 
          return ($data);
        }
        static public function ctrImporterDetail($IDEDIMPCOS){
          $data=new stdClass();
          $ImporterDetail=ModeloopEdi::MdlImporterDetail($IDEDIMPCOS);
          $data->ImporterDetail=$ImporterDetail;
          $ImporterXML=ModeloopEdi::MdlImporterDetail_XML($IDEDIMPCOS);
          $data->ImporterXML=$ImporterXML;
          return $data;
        }
        static public function ctrCreateImporter($datos){
          //$datos['Emisor']=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($datos['Emisor']));  
          //$datos['Receptor']=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($datos['Receptor']));    
          $CreateImporter=ModeloopEdi::MdlCreateImporter($datos);
          //VAR_DUMP($CreateImporter);
          if(!empty($CreateImporter)){
            foreach($datos['LIST_XML'] as $XML){
              $XML['Emisor']=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($XML['Emisor']));
              $XML['Receptor']=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($XML['Receptor'])); 
              $XMLtoImporter=ModeloopEdi::MdlInsertXMLtoImporter($CreateImporter[0][0],$XML);
              //EN CASO DE  ERROR  AGREGAR UN ROLLBACK, MANUAKLMENTE PUEDE  TOMARSE TODO LO INGRESADO AL IMPORTADOR Y ELIMINARLO.
            }
          }
          //VAR_DUMP($CreateImporter);
          return $XMLtoImporter;
        }
        static public function ctrUploadFile($file){
          //carga de  catalogos de  partes
          var_dump($file);
        }
        static public function ctrCreateClient($datos){    
          $CreateClient=ModeloopEdi::MdlCreateClient($datos);
          return ($CreateClient);
        }
        static public function ctrPrintManifest($datos){
          $data=new stdClass();
          $data->ManifestNoValidados='';
          $data->ManifestValidados='';
          $datos_ciclo=array();
          $B_UpdateManifestTable=0;
          switch ($datos["Type"]){
            case "Range":
              $Entrys=$datos["Data"][1]-$datos["Data"][0];
              for($i=0;$i<=$Entrys;$i++){
                array_push($datos_ciclo,$datos["Data"][0]+$i);
              }
              break;
            case "SelectByImporter":
              $datos_ciclo=$datos["Data"];
              break;
            case "SelectByEntry":
                $C_Entrys=count($datos["Data"]);
                for($i=0;$i<=$C_Entrys-1;$i++){
                  array_push($datos_ciclo,$datos["Data"][$i]);
                }
                break; 
            default:
              array_push($datos_ciclo,$datos["Data"][0]);
              break;
          }

          for($i=0;$i<=count($datos_ciclo)-1;$i++){
            $DataManifest=ModeloopEdi::MdlDataManifest($datos_ciclo[$i]);
            if(empty($DataManifest)) {
              $data->ManifestNoValidados.=$datos_ciclo[$i].' | ';
              $B_UpdateManifestTable=1;
            }
            else{
              $UpdatePrintedManifest=ModeloopEdi::MdlUpdatePrintManifest($datos_ciclo[$i]); //INDICA  SI EL  MANIFIESTO YA FUE  IMPRESO,  ACTIVA LA BANDERA PARA NO ENVIAS  NOTIFICACIONES
              rptManifiestos($DataManifest[0]);
              if($UpdatePrintedManifest!='OK'){
                $data->ManifestNoValidados.='No Updated: '.$datos_ciclo[$i].' | ';
              }
              $data->ManifestValidados.=$datos_ciclo[$i].' | ';
            }
          }
          $data->UpdateRequired=$B_UpdateManifestTable;
          return $data;
        }
        static public function ctrUpdateManifestTable(){
          exec('copy F:\ABI_8BH\USRES_E.DBF G:\ABI_8BH');
          $UpdateManifestTable=ModeloopEdi::MdlUpdateManifest();
          return $UpdateManifestTable;
        }
        static public function ctrPrintManifestSelectedEntrys($datos){
          $datos_ciclo=array();

          foreach($datos as $index=>$Key){
            $ManifestToPrint=ModeloopEdi::MdlTakeEntry($Key);
            array_push($datos_ciclo,$ManifestToPrint[0]);

          }
          $datos=["Type"=>"SelectByEntry","Data"=>$datos_ciclo];
          $PrintManifest=ControladoropEdi::ctrPrintManifest($datos);
          return  $PrintManifest;
        }
        static public function ctrEliminarCajaFacturaMasivo($Datos,$CLI_ABI_KEY,$VistaPanel){
          //realizar  ajuste en caso de  falla al aliminar  registros,  se  debe  hacer un  roollback de la operacion.
          $b_OperacionesEliminadas=0; //0 todas eliminadas,1 algunas  no  fueron eliminadas
          $c_registros=count($Datos);
          if($VistaPanel==2){
            foreach($Datos as $value){
              $LiberarEntry=ModeloopEdi::MdlLiberarEntry($value[1]);
              if ($LiberarEntry!='ok'){
                //PERSONALIZAR EL MENSAJE DE  ERROR PARA ENTRIES NO LIBERADOS
                $b_OperacionesEliminadas=1;
              }
            }
          }
          foreach($Datos as $value){
            $EliminarCajaFactura=ModeloopEdi::MdlEliminarCajaFactura($value[0],$value[1],$value[2]);
            if ($EliminarCajaFactura!='ok'){
              $b_OperacionesEliminadas=1;
            }
          }
          return $b_OperacionesEliminadas;
        }
        static public function ctrReadNotifications(){
          $ReadNotifications=ModeloopEdi::MdlReadNotifications();
          return $ReadNotifications;
        }
        static public function ctrAddExchRate($MONEDA,$VALOR_MONEDA){
          $AddExchRate=ModeloopEdi::MdlAddExchRate($MONEDA,$VALOR_MONEDA);
          return $AddExchRate;
        }
        static public function ctrshowImporterToCopy(){
          $showImporterToCopy=ModeloopEdi::MdlshowImporterToCopy();
          $c_fillTbImporters=count($showImporterToCopy);
          if($c_fillTbImporters>0){ 
              for($x=0;$x<$c_fillTbImporters;$x++){
                  $IDEDIMPCOS= $showImporterToCopy[$x]['IDEDIMPCOS'];
                  $IMPORTER= $showImporterToCopy[$x]['IMP_ABI_KEY'];
                  $IMP_NAME  = $showImporterToCopy[$x]['IMP_NAME'];
                  $CONSIGNEE = $showImporterToCopy[$x]['CONS_NAME'];
                  $Boton= '<div class="row">'.
                              '<button type="button" class="btn btn-secondary btn-smy opEdi_mdlCopyImporterData_TakeData" id="opEdi_mdlCopyImporterData_btnTakeData'.$x.'" name="opEdi_mdlCopyImporterData_btnTakeData'.$x.'" data-idedimpcos="'.$IDEDIMPCOS .'">'.
                                'Take Data'.
                              '</button>'.
                          '</div>';       
                  $data->Importers[$x]=array($IMPORTER,$IMP_NAME,$CONSIGNEE,$Boton); 
              }
          }else{
              $data->Importers[0]=array('No data','',''); 
          } 
          return ($data);
        }
        static public function ctrcopyImporterData($IDEDIMPCOS){
          $data= new stdclass();
          $data->Importer=ModeloopEdi::MdlcopyImporterData($IDEDIMPCOS);
          $data->Importer_XML=ModeloopEdi::MdlcopyImporterData_XML($IDEDIMPCOS);
          return $data;
        }     
    }    
?>