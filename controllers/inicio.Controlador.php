<?php
    set_time_limit(0);
    ini_set('default_socket_timeout', 10);
    //VARIABLES GLOBALES
    $FilesPDF=[];
    $FilesXLS=[];
    $FilesXML=[];
    $FilesXMLNoValidos=[];
    $Facturas=[];
    $Mercancias=[];
    $DatosExcel=[];
    //FIN DE  VARIABLES GLOBALES
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
    function ReadFilesOnFolder($path,$TC,$IDECLI){
      /*$archivos=glob($path."/*.{pdf,PDF}", GLOB_BRACE);                                                                     //Adquiere la lista de PDF de la carpeta
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
      }*/
      //Se agrego esta  funcion para leer los XML
      $XML_NoValido='';
      $archivos=glob($path."/*.{xml,XML}", GLOB_BRACE);
      if(sizeof($archivos)!=0){
        $cnt_XML=0;
        $Mercancias=array();  //Agregado para leer todos los numeros de parte. 23-10-2023
        $cont_lineas=0;       //Agregado para leer todos los numeros de parte. 23-10-2023
        foreach($archivos as $XML){
          //var_dump($XML);
          //VARIABLES
          $PesoXML=0;
          $InvoiceXML=0;
          $BultosXML=0;
          $concepto= [];
          $receptor = [];
          $mercancia = [];
          $comercio= [];
          $tax = [];
          $uuid = [];
          $cfdi= [];
          //$Mercancias=array(); -- removido 
          $m=0;
          $c=0;
          $t=0;
          $timbre='';
          $incoterm='';
          $TAXID='';
          $cont_lineas_f=0;  
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
            //var_dump($cfdi,$emisor,$receptor,$concepto );
            if(isset($ns['cce11'])){
              $xml->registerXPathNamespace('cce11', $ns['cce11']);
              $mercancia = $xml->xpath('//cfdi:Comprobante//cfdi:Complemento//cce11:ComercioExterior//cce11:Mercancias//cce11:Mercancia');
              $comercio = $xml->xpath('//cfdi:Comprobante//cfdi:Complemento//cce11:ComercioExterior');
              $tax = $xml->xpath('//cfdi:Comprobante//cfdi:Complemento//cce11:ComercioExterior//cce11:Receptor');
              //var_dump($mercancia,$comercio,$tax);
              $m=count($mercancia);
            }
            if(isset($ns['tfd'])){
              $xml->registerXPathNamespace('tfd', $ns['tfd']);
              $uuid = $xml->xpath('//cfdi:Comprobante//cfdi:Complemento//tfd:TimbreFiscalDigital');
              $t=1;
            }
            //var_dump('BREAK');
            //EN CASO DE UTILIZAR  VARIOS TC,  SE  DEBE  VALIDAR EN ESTA SECCION
            if(utf8_decode($cfdi[0]['Moneda'])=='USD'){
              //SE  DEJARA POR  DEFAULT  EL TIPO DE  CAMBIO EN PESOS, EN CASO DE  OCUAPRSE OTRO TIPO DE CAMBIO SE  TIENE QUE OBTENER MEDIANTE CONSULTA, 
              //Y DEBE SER  REMOVIDO DE LAS  VARIABLE ENVIADAS.
              $TC=1;
            }

            $TC=1;
            /*else if($TC==0){
              $XML_NoValido.=' REQUIERE TC '.str_replace($path,'',$XML).' | ';
              break;
            }*/
            //var_dump('BREAK');
            //Mercancias Conceptos
            for ($x=0;$x<=$c-1;$x++){
              //var_dump('BREAK');
              $Neto=0;$NoIdentificacion='';$Descripcion='';$Cantidad='';$ClaveUnidad='';$ValorUnitario='';
              //Agregados para TCI
              $Fraccion='';
              if(isset($concepto[$x]['NoIdentificacion'])){$NoIdentificacion=utf8_decode($concepto[$x]['NoIdentificacion']);}
              //if(isset($concepto[$x]['Descripcion'])){$Descripcion=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($concepto[$x]['Descripcion']));}  
              if(isset($concepto[$x]['Descripcion'])){$Descripcion=utf8_decode($concepto[$x]['Descripcion']);}        
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
                  //else if (isset($mercancia[$y]['CantidadAduana'])){
                  else if ($NoIdentificacion==$mercancia[$y]['NoIdentificacion']){
                    /*if(utf8_decode($cfdi[0]['Moneda'])!='USD'){
                      if(isset($mercancia[$y]['ValorDolares'])){$ValorUnitario=floatval(utf8_decode($mercancia[$y]['ValorDolares']))/$Cantidad;}

                      //QUITAR BANDERA DE TIPO MONEDA
                    }*/
                    $Fraccion=floatval(utf8_decode($mercancia[$y]['FraccionArancelaria']));
                    $Neto=floatval(utf8_decode($mercancia[$y]['CantidadAduana']));
                    //Decrementos de las  Mercancias
                    unset($mercancia[$y]);
                    $mercancia=array_values($mercancia);
                    $m-=1;
                    break;
                  }
                }
                $TAXID=utf8_decode($tax[0]['NumRegIdTrib']);
                $incoterm=utf8_decode($comercio[0]['Incoterm']); 
              }
              else{
                
              }
              //El peso  neto se  duplica en las  mercancias,  ya que se  desconoce  el bruto
              //NOTA: LAS  LINEAS  COMIENZAN CON  VALOR 1, PERO EL INDICE DEL  ARREGLO COMIENZA EN 0
              //$Mercancias[$cont_lineas]=array($cont_lineas+1,$K_RELF_M,substr($NoIdentificacion,0,22),$ClaveUnidad,$Cantidad,$ValorUnitario,$Descripcion,$Neto,$Neto,$Fraccion); 
              //$Mercancias[$cont_lineas]=array(utf8_decode($cfdi[0]['Serie'])+utf8_decode($cfdi[0]['Folio']),$cont_lineas+1,substr($NoIdentificacion,0,22),$Fraccion,$Descripcion,'','USA','N',$ClaveUnidad,$Cantidad,$ValorUnitario,$Neto,$Cantidad*$ValorUnitario,'0','','SI'); 
              $factura=utf8_decode($cfdi[0]['Serie']).utf8_decode($cfdi[0]['Folio']);
              if($t==1){
                $timbre=utf8_decode($uuid[0]['UUID']);
              }
              $GLOBALS['Mercancias'][$cont_lineas]=array($factura,$cont_lineas_f+1,substr($NoIdentificacion,0,22),$Fraccion,$Descripcion,'','USA','N',$ClaveUnidad,$Cantidad,$ValorUnitario,$Neto,$Cantidad*$ValorUnitario,'0','','SI',$timbre,$TAXID); 
              $PesoXML+=$Neto;
              $InvoiceXML+=$ValorUnitario*$Cantidad;
              $cont_lineas+=1;
              $cont_lineas_f+=1;
            }
            //var_dump('BREAK');
            //$Invoices['XML_EmisorName']=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($emisor[0]['Nombre']));                //REMUEVE CARACTERES ESPECIALES DEL NOMBRE
            //$Invoices['XML_ReceptorName']=preg_replace('([^A-Za-z0-9 ])','#',utf8_decode($receptor[0]['Nombre']));            //REMUEVE CARACTERES ESPECIALES DEL NOMBRE
            $Invoices['INVOICE']=utf8_decode($cfdi[0]['Serie']).utf8_decode($cfdi[0]['Folio']);
            $Invoices['PROVEEDOR']=$IDECLI; 
            
            if(isset($cfdi[0]['Total']) || $cfdi[0]['Total']==0){
              $Invoices['INVOICE_SUBTOTAL']=$InvoiceXML;
              $Invoices['INVOICE_TOTAL']=$InvoiceXML;
            }
            else{
              $Invoices['INVOICE_SUBTOTAL']=utf8_decode($cfdi[0]['SubTotal'])?:0;
              $Invoices['INVOICE_TOTAL']=utf8_decode($cfdi[0]['Total'])?:0;
            }
            //var_dump('BREAK');
            $Invoices['FLETES']='0'; 
            $Invoices['SEGUROS']='0'; 
            $Invoices['EMBALAJES']='0'; 
            $Invoices['OTROS']='0'; 
            $Invoices['METODO']='0';
            $Invoices['MONEDA']='USD';
            $Invoices['PAIS']='MEX';
            //EXTRACCION DE  FECHA DE  FACTURACION
            $f=explode("T",utf8_decode($cfdi[0]['Fecha']));
            //var_dump('BREAK');
            $Invoices['INVOICE_DATE']=$f[0];
            $Invoices['UUID']=$timbre;
            $Invoices['INCOTERM']=$incoterm; 
            //$Invoices['GROSS_WEIGHT']=$PesoXML?:0;
            //$Invoices['NET_WEIGHT']=$PesoXML?:0;
            //$Invoices['QUANTITY']=$BultosXML?:0;  // EQUIVALE A LOS BULTOS TOTALES DE LA FACTURA, LAS  CANTIDADES POR LINEA SE  DECLARAN EN LA  TABLA DE MERCANCIA
            //$Invoices['FILE_INVOICE']=$XML;
            //$Invoices['FILE_PLANTILLA']='';
            //var_dump($Invoices);
            $GLOBALS['FilesXML'][$cnt_XML]['FILE']=$XML;
            $GLOBALS['FilesXML'][$cnt_XML]['LINES']=$cont_lineas_f;
            $GLOBALS['FilesXML'][$cnt_XML]['DATA']=$Invoices;
            $GLOBALS['Facturas'][$cnt_XML]=$Invoices;
            $cnt_XML+=1; 
          }
          else{
            $XML_NoValido.=str_replace($path,'',$XML).' | ';
          }
          //$GLOBALS['Mercancias']=$Mercancias;
        }
      }
      
      return $XML_NoValido;
    }
    function ReadFilesOnFolder_renxml($path,$TC,$IDECLI){
      $XML_NoValido='';
      $archivos=glob($path."/*.{xml,XML}", GLOB_BRACE);
      if(sizeof($archivos)!=0){
        $cnt_XML=0;
        $cont_lineas=0;       //Agregado para leer todos los numeros de parte. 23-10-2023
        foreach($archivos as $XML){
          //VARIABLES
          $informacionAduanera= [];
          $c=0;
          $nPedimento='';
          /* INICIALIZACION DEL XML Y SUS CONCEPTOS */
          libxml_use_internal_errors(true);
          $ValidacionXML= simpleXML_load_file($XML);
          //VAR_DUMP($ValidacionXML);
          if($ValidacionXML!==false){
            $xml = new \SimpleXMLElement($XML, null,true);
            $ns = $xml->getNamespaces(true);
            $cfdi = $xml->xpath('//cfdi:Comprobante');
            $informacionAduanera = $xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto//cfdi:InformacionAduanera');
            $c = count($informacionAduanera);

            $pdftotext=str_ireplace('.xml','.PDF', $XML);
            $lineas=[];
            $cont_lin=0;
            shell_exec('C:/xpdf/pdftotext.exe -simple2 "'.$pdftotext.'"');
            $texto=fopen(str_ireplace('.pdf','.txt',$pdftotext),'r');
            $cont_lin=0;
            $lineas=[];
            while(!feof( $texto)){
              $linea=fgets($texto);
              $linea=preg_replace('[\n|\r|\n\r]','',$linea);
              if(strpos($linea,'Pedimento: ')!==false){
                $PEDIMENTO=explode(' ',$linea);
                rename($pdftotext,$path.$PEDIMENTO[2].'0-'.$PEDIMENTO[3].'-'.$PEDIMENTO[4].'-FC.pdf');
              }
            }
            
            if($c>0){
              $nPedimento=utf8_decode($informacionAduanera[0]['NumeroPedimento']).' - '.utf8_decode($cfdi[0]['Serie']).utf8_decode($cfdi[0]['Folio']);
              $path2=explode('.',$XML);
              //23 24 3649 3024440

              //var_dump('Pedimento '.$path2[0].'.xml',$path.$nPedimento.'.xml');
              //var_dump('Pedimento '.$path2[0].'.pdf',$path.$nPedimento.'.pdf', $PEDIMENTO);
              rename($path2[0].'.xml',$path.'/'.$nPedimento.$cnt_XML.'.xml');
              //rename($path2[0].'.pdf',$path.'/'.$PEDIMENTO[1].'0-'.$PEDIMENTO[2].'-'.$PEDIMENTO[3].'.pdf');
            }
            elseif(isset($cfdi[0]['Folio']) ){
              $nPedimento=utf8_decode($cfdi[0]['Serie']).utf8_decode($cfdi[0]['Folio']);
              $path2=explode('.',$XML);

              //var_dump('Factura '.$path2[0].'.xml',$path.$nPedimento.'.xml');
              //var_dump('Factura '.$path2[0].'.pdf',$path.$nPedimento.'.pdf');
              rename($path2[0].'.xml',$path.'/'.$nPedimento.' - '.$cnt_XML.'.xml');
              //rename($path2[0].'.pdf',$path.'/'.$nPedimento.' - '.$cnt_XML.'.pdf');
            }
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
    function ctrReporteMahle(){

      // arreglos $GLOBALS['Facturas'],$GLOBALS['Mercancias']
      $data= new stdClass();
      $templatePath  = "C:/wamp64/www/PlantillaMAHLE_AFTER/PLANTILLA.xlsx";
      
      $MSG='';
      $id_Factura=0;
      $cont=count($GLOBALS['Facturas']);
      //var_dump($GLOBALS['Facturas']);
      if($cont>0){ 
          //var_dump($cont);
          $filaActual = 2;
          // Crea una instancia de hoja de cálculo
          $spreadsheet = new PHPExcel();	
          // Carga la plantilla de Excel existente
          $spreadsheet = PHPExcel_IOFactory::load($templatePath);
          foreach($GLOBALS['Facturas'] as $folio){
              //var_dump( $filaActual,$folio['INVOICE_TOTAL'],$folio);
              // Obtén la hoja a la que deseas asignar un nombre
              $sheet = $spreadsheet->getSheetByName('Facturas');
              $sheet->getCell('A'.$filaActual)->setValue($folio['UUID']);
              $sheet->getCell('B'.$filaActual)->setValue($folio['PROVEEDOR']);
              //$total=$folio['INVOICE_TOTAL'];
              $sheet->getCell('C'.$filaActual)->setValue(strval(round($folio['INVOICE_TOTAL'],4)));
              $sheet->getCell('D'.$filaActual)->setValue($folio['FLETES']);
              $sheet->getCell('E'.$filaActual)->setValue($folio['SEGUROS']);
              $sheet->getCell('F'.$filaActual)->setValue($folio['EMBALAJES']);
              $sheet->getCell('G'.$filaActual)->setValue($folio['OTROS']);
              $sheet->getCell('H'.$filaActual)->setValue($folio['INCOTERM']);
              $sheet->getCell('I'.$filaActual)->setValue($folio['METODO']);
              $sheet->getCell('J'.$filaActual)->setValue($folio['MONEDA']);
              $sheet->getCell('K'.$filaActual)->setValue($folio['PAIS']);
              $fecha=explode("-",$folio['INVOICE_DATE']);
              $sheet->getCell('L'.$filaActual)->setValue( strval($fecha[2].'/'.$fecha[1].'/'.$fecha[0]));
              $filaActual++;
          }
          //VAR_DUMP($sheet);
          $filaActual = 2;
          foreach($GLOBALS['Mercancias']as $Factura=>$folio){
            //VAR_DUMP($Factura,$folio);
            $sheet = $spreadsheet->getSheetByName('Datos');
            $sheet->getCell('A'.$filaActual)->setValue($folio[16]);
            $sheet->getCell('B'.$filaActual)->setValue(strval($folio[1]));
            $sheet->getCell('C'.$filaActual)->setValue($folio[2]);
            $sheet->getCell('D'.$filaActual)->setValue(strval($folio[3]));
            $sheet->getCell('E'.$filaActual)->setValue($folio[4]);
            $sheet->getCell('F'.$filaActual)->setValue($folio[5]);
            $sheet->getCell('G'.$filaActual)->setValue($folio[6]);
            $sheet->getCell('H'.$filaActual)->setValue($folio[7]);
          //$sheet->getCell('I'.$filaActual)->setValue($folio[8]);
            $sheet->getCell('I'.$filaActual)->setValue('PZA');
            $sheet->getCell('J'.$filaActual)->setValue($folio[9]);
            $sheet->getCell('K'.$filaActual)->setValue(strval(round($folio[10],4)));
            $sheet->getCell('L'.$filaActual)->setValue(strval(round($folio[11]/$folio[9],4)));
            $sheet->getCell('M'.$filaActual)->setValue(strval(round($folio[12],4)));
            $sheet->getCell('N'.$filaActual)->setValue($folio[13]);
            $sheet->getCell('O'.$filaActual)->setValue('NP_'.$folio[2].',NF_'.$folio[0].',C_'.$folio[9].',PR_'.$folio[17].',VM_'.strval(round($folio[12],4)));
            $sheet->getCell('P'.$filaActual)->setValue($folio[15]);
            $sheet->getCell('Q'.$filaActual)->setValue($folio[0]);
            $filaActual++;
        }
          ob_start(); 
          $objWriter=PHPExcel_IOFactory::createWriter($spreadsheet,'Excel5');     
          $objWriter->save('php://output');
          $xlsData = ob_get_contents();
          //var_dump($xlsData);
          //$data->Excel=$xlsData;
          ob_end_clean();
          //$file="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
          $data->ToExcel="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
          $data->NombreReporte='Plantilla';
          $data->Code='OK';
          //$data->Trafico=$trafico;
          //var_dump($data);
          return ($data); 
      }
      else{
          $data->Code='Vacio';
          $data->MSG='Info:: Sin Resultados. No existen registros con los filtros  seleccionados.';
          return ($data);  
      }
  }

    //Nota:  $a += 0 esta forma elimina los  0 al inicio de la  cadena
    class Controladorinicio{
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
        static public function ctrinicioUpFiles($IDEDCLI,$RUTA,$TC){
          $data= new stdClass();
          //FUNCION UTILIZADA PARA RENOMBRAR XML Y PDF DE FORMA MASIVA, TOMA LA FACTURA O PEDIMENTO DEL XML PARA EL RENOMBRADO, EN CASO DE NO EXISTIR NO SE APLICA RENOMBRAMIENTO
          //REQUIERE AJUSTE DE VALIDDACION DE EXISTENCIA DE PDF, ALGUNAS OCASIONES EL XML SE  DUPLICA
          if($IDEDCLI=='MAHLE'){
            ReadFilesOnFolder_renxml($RUTA,$TC,$IDEDCLI);
            $data->Code='Vacio';
          }
          else{
            //var_dump('Entro en el else');
            $Observaciones=array("Cargados"=>0,'No_XML'=>0,'No_PDF'=>0,'XMLSin_PDF'=>'','XML_NoValido'=>'','PDF_NoLayout'=>'','Error'=>'','Factura_Duplicada'=>'','XML_Desconidos'=>'');
            $Observaciones['XML_NoValido']=ReadFilesOnFolder($RUTA,$TC,$IDEDCLI);
            $data=ctrReporteMahle();
          }
          return($data);
        }
    
    }    
?>