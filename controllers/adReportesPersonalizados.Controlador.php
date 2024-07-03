<?php
    class ControladoradReportesPersonalizados{
        static public function ctrReporteMahleCorreo($trafico,$correo){
            $data= new stdClass();
            $templatePath  = "C:/wamp64/www/PlantillaMAHLE/PLANTILLA.xls";
            $registrosPorHoja = 15;
            $FACTURAS=ModeloPNL::MdlListaFacturasXTrafMahle($trafico);
            $MSG='';
            $cont=count($FACTURAS);
            if($cont>0){ 
                foreach($FACTURAS as $Factura=>$folio){
                    // Realiza tu consulta para obtener los datos 
                    $consulta=ModeloPNL::MdlListaFacturasXPedimentoEnviar($folio[0],$trafico);
                    $encabezado=ModeloPNL::MdlEncabezadoPedimentoEnviar($folio[0],$trafico);
                    // Crea una instancia de hoja de cálculo
                    $spreadsheet = new PHPExcel();	
                    // Carga la plantilla de Excel existente
                    $spreadsheet = PHPExcel_IOFactory::load($templatePath);
                    // Selecciona la hoja activa 
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->getCell('J8')->setValue($encabezado[0][3]);
                    $sheet->getCell('K8')->setValue($encabezado[0][4]);
                    $sheet->getCell('L8')->setValue($encabezado[0][2]);
                    $sheet->getCell('M8')->setValue($encabezado[0][0]);
                    $sheet->getCell('M31')->setValue($encabezado[0][1]);
                    // Inicializa variables para controlar el número de registros y la fila actual
                    $registrosActuales = 0;
                    $hojaActual = 1;
                    $filaActual = 12; // Comenzar en la fila 12
                    foreach ($consulta as $datosFila ) {
                        //VAR_DUmP($dato);
                        // Si se alcanza el límite de registros por hoja, crear una nueva hoja
                        if ($registrosActuales >= $registrosPorHoja) {
                            $spreadsheet->createSheet();
                            $spreadsheet->setActiveSheetIndex($hojaActual);
                            $sheet = $spreadsheet->getActiveSheet();
                            $hojaActual++;
                            $registrosActuales = 0;
                            $filaActual = 12; // Comenzar en la fila 12 de la nueva hoja
                            //Encabezado de la Factura
                            $sheet->getCell('J8')->setValue($encabezado[0][3]);
                            $sheet->getCell('K8')->setValue($encabezado[0][4]);
                            $sheet->getCell('L8')->setValue($encabezado[0][2]);
                            $sheet->getCell('M8')->setValue($encabezado[0][0]);
                            $sheet->getCell('M31')->setValue($encabezado[0][1]);
                        }
                        $sheet->getCell('A'.$filaActual)->setValue($datosFila[0]);
                        $sheet->getCell('B'.$filaActual)->setValue($datosFila[1]);
                        $sheet->getCell('C'.$filaActual)->setValue($datosFila[2]);
                        $sheet->getCell('D'.$filaActual)->setValue($datosFila[3]);
                        $sheet->getCell('E'.$filaActual)->setValue($datosFila[4]);
                        $sheet->getCell('F'.$filaActual)->setValue($datosFila[5]);
                        $sheet->getCell('G'.$filaActual)->setValue($datosFila[6]);
                        $sheet->getCell('H'.$filaActual)->setValue($datosFila[7]);
                        $sheet->getCell('I'.$filaActual)->setValue($datosFila[8]);
                        $sheet->getCell('J'.$filaActual)->setValue($datosFila[9]);
                        $sheet->getCell('K'.$filaActual)->setValue($datosFila[10]);
                        $sheet->getCell('L'.$filaActual)->setValue($datosFila[11]);
                        $sheet->getCell('M'.$filaActual)->setValue($datosFila[12]);
                        $registrosActuales++;
                        $filaActual++;
                    }
                    // Guarda el archivo Excel y lo envía al navegador
                    $objWriter=PHPExcel_IOFactory::createWriter($spreadsheet,'Excel2007'); 
                    ob_start();     
                    $objWriter->save('php://output');
                    $xlsData = ob_get_contents();
                    //$data->Excel=$xlsData;
                    ob_end_clean();
                    $file="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
                    //$mailContact[0][0]='FRodriguez@tramitaciones.com';$correo
                    $mailContact[0][0]=$correo;
                    $mailProfile[0][0]='Plantilla Mahle Berh - Trafico '.$trafico; // titulo
                    $mailProfile[0][1]='Factura: '.$folio[0]; // Subject
                    //echo($file);
                    $Envio=SendMail($mailProfile,$mailContact,$xlsData,'Factura Mahle',$folio[0]);
                    //Agregar condicion de  error, si excel o senmail retornan error, mandar correo con los  errores,
                    if($Envio!='OK'){
                        $MSG.="Error en Correo Factura".$folio[0]."</br>";
                    }
                }
                if($MSG==''){
                    $data->Code='OK';
                }
                else{
                    $data->Code='Error';
                    $data->MSG='Error de BD:: '.$MSG;
                    
                }
            }
            else{
                $data->Code='Vacio';
                $data->MSG='Info:: Sin Resultados. No existen registros con los filtros  seleccionados.';
                return ($data);  
            }
            return ($data);  
        }
        static public function ctrReporteMahle($trafico){
            $data= new stdClass();
            $templatePath  = "C:/wamp64/www/PlantillaMAHLE/PLANTILLA.xls";
            $registrosPorHoja = 15;
            $FACTURAS=ModeloPNL::MdlListaFacturasXTrafMahle($trafico);
            $MSG='';
            $id_Factura=0;
            $cont=count($FACTURAS);
            if($cont>0){ 
                foreach($FACTURAS as $Factura=>$folio){
                    // Realiza tu consulta para obtener los datos 
                    $consulta=ModeloPNL::MdlListaFacturasXPedimentoEnviar($folio[0],$trafico);
                    $encabezado=ModeloPNL::MdlEncabezadoPedimentoEnviar($folio[0],$trafico);
                    $encabezado_direcciones=ModeloPNL::MdlEncabezadoPedimentoDirecciones($folio[0],$trafico);
                    // Crea una instancia de hoja de cálculo
                    $spreadsheet = new PHPExcel();	
                    // Carga la plantilla de Excel existente
                    $spreadsheet = PHPExcel_IOFactory::load($templatePath);
                    // Selecciona la hoja activa 
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->getCell('J8')->setValue($encabezado[0][3]);
                    $sheet->getCell('K8')->setValue($encabezado[0][4]);
                    $sheet->getCell('L8')->setValue($encabezado[0][2]);
                    $sheet->getCell('M8')->setValue($encabezado[0][0]);
                    $sheet->getCell('M31')->setValue($encabezado[0][1]);

                    $sheet->getCell('B28')->setValue($encabezado[0][5]);
                    $sheet->getCell('B31')->setValue($encabezado[0][6]);
                    $sheet->getCell('B12')->setValue($encabezado[0][8]);
                    $sheet->getCell('J12')->setValue($encabezado[0][7]);

                    //ENCABEZADOS DE LAS DIRECCIONES
                   //VAR_DUMP($encabezado_direcciones,$encabezado_direcciones[0][3]);

                    if($encabezado_direcciones[0][3]==4){
                        $sheet->getCell('A7')->setValue('');
                        $sheet->getCell('F7')->setValue('');
                        $sheet->getCell('A2')->setValue($encabezado_direcciones[0][9]);
                        $sheet->getCell('A3')->setValue($encabezado_direcciones[0][10]);
                        $sheet->getCell('A4')->setValue($encabezado_direcciones[0][11]);
                        $sheet->getCell('A5')->setValue($encabezado_direcciones[0][8]);
                        $sheet->getCell('D2')->setValue($encabezado_direcciones[0][5]);
                        $sheet->getCell('D3')->setValue($encabezado_direcciones[0][6]);
                        $sheet->getCell('D4')->setValue($encabezado_direcciones[0][7]);
                        $sheet->getCell('D5')->setValue($encabezado_direcciones[0][4]);
                        $sheet->getStyle('D5')->getAlignment()->setWrapText(true);
                        $sheet->getCell('E2')->setValue($encabezado_direcciones[0][5]);
                        $sheet->getCell('E3')->setValue($encabezado_direcciones[0][6]);
                        $sheet->getCell('E4')->setValue($encabezado_direcciones[0][7]);
                        $sheet->getCell('E5')->setValue($encabezado_direcciones[0][4]);
                        $sheet->getStyle('E5')->getAlignment()->setWrapText(true);
                        $sheet->getCell('F2')->setValue($encabezado_direcciones[0][13]);
                        $sheet->getCell('F3')->setValue($encabezado_direcciones[0][14]);
                        $sheet->getCell('F4')->setValue($encabezado_direcciones[0][15]);
                        $sheet->getCell('F5')->setValue($encabezado_direcciones[0][12]);
                        
                    }
                    
                    // Inicializa variables para controlar el número de registros y la fila actual
                    $registrosActuales = 0;
                    $hojaActual = 1;
                    $filaActual = 12; // Comenzar en la fila 12
                    //$sheet->getCell('K33')->setValue('FACTURA HOJA '+$hojaActual);
                    foreach ($consulta as $datosFila ) {
                        //VAR_DUmP($dato);
                        // Si se alcanza el límite de registros por hoja, crear una nueva hoja
                        if ($registrosActuales >= $registrosPorHoja) {
                            //$spreadsheet->createSheet();
                            $spreadsheet->setActiveSheetIndex($hojaActual);
                            $sheet = $spreadsheet->getActiveSheet();
                            //clonado de  hoja

                            //clonado de hoja

                            $hojaActual++;
                            $registrosActuales = 0;
                            $filaActual = 12; // Comenzar en la fila 12 de la nueva hoja
                            //Encabezado de la Factura
                            $sheet->getCell('J8')->setValue($encabezado[0][3]);
                            $sheet->getCell('K8')->setValue($encabezado[0][4]);
                            $sheet->getCell('L8')->setValue($encabezado[0][2]);
                            $sheet->getCell('M8')->setValue($encabezado[0][0]);
                            $sheet->getCell('M31')->setValue($encabezado[0][1]);
                            $sheet->getCell('B28')->setValue($encabezado[0][5]);
                            $sheet->getCell('B31')->setValue($encabezado[0][6]);
                            //$sheet->getCell('B12')->setValue($encabezado[0][8]);
                            $sheet->getCell('J12')->setValue($encabezado[0][7]);
                            //$sheet->getCell('K33')->setValue('FACTURA HOJA '+$hojaActual);

                            //ENCABEZADOS DE LAS DIRECCIONES

                            if($encabezado_direcciones[0][3]==4){
                                $sheet->getCell('A7')->setValue('');
                                $sheet->getCell('F7')->setValue('');
                                $sheet->getCell('A2')->setValue($encabezado_direcciones[0][9]);
                                $sheet->getCell('A3')->setValue($encabezado_direcciones[0][10]);
                                $sheet->getCell('A4')->setValue($encabezado_direcciones[0][11]);
                                $sheet->getCell('A5')->setValue($encabezado_direcciones[0][8]);
                                $sheet->getCell('D2')->setValue($encabezado_direcciones[0][5]);
                                $sheet->getCell('D3')->setValue($encabezado_direcciones[0][6]);
                                $sheet->getCell('D4')->setValue($encabezado_direcciones[0][7]);
                                $sheet->getCell('D5')->setValue($encabezado_direcciones[0][4]);
                                $sheet->getStyle('D5')->getAlignment()->setWrapText(true);
                                $sheet->getCell('E2')->setValue($encabezado_direcciones[0][5]);
                                $sheet->getCell('E3')->setValue($encabezado_direcciones[0][6]);
                                $sheet->getCell('E4')->setValue($encabezado_direcciones[0][7]);
                                $sheet->getCell('E5')->setValue($encabezado_direcciones[0][4]);
                                $sheet->getStyle('E5')->getAlignment()->setWrapText(true);
                                $sheet->getCell('F2')->setValue($encabezado_direcciones[0][13]);
                                $sheet->getCell('F3')->setValue($encabezado_direcciones[0][14]);
                                $sheet->getCell('F4')->setValue($encabezado_direcciones[0][15]);
                                $sheet->getCell('F5')->setValue($encabezado_direcciones[0][12]);
                                
                            }
                            
                        }
                        $sheet->getCell('A'.$filaActual)->setValue($datosFila[0]);
                        //$sheet->getCell('B'.$filaActual)->setValue($datosFila[1]);
                        $sheet->getCell('C'.$filaActual)->setValue($datosFila[2]);
                        $sheet->getStyle('C'.$filaActual)->getAlignment()->setWrapText(true);
                        $sheet->getCell('D'.$filaActual)->setValue($datosFila[3]);
                        //$sheet->getColumnDimension('D')->setAutoSize(true); // Ajustar el tamaño de la columna D al contenido
                        $sheet->getCell('E'.$filaActual)->setValue($datosFila[4]);
                        //$sheet->getColumnDimension('E')->setAutoSize(true); // Ajustar el tamaño de la columna D al contenido
                        $sheet->getStyle('E'.$filaActual)->getAlignment()->setWrapText(true); // Habilitar el ajuste de texto automático en la celda F
                        $sheet->getRowDimension($filaActual)->setRowHeight(-1); // Restaurar la altura de la fila a la altura predeterminada
                        $sheet->getCell('F'.$filaActual)->setValue($datosFila[5]);
                        
                        $sheet->getCell('G'.$filaActual)->setValue($datosFila[6]);
                        $sheet->getCell('H'.$filaActual)->setValue($datosFila[7]);
                        $sheet->getCell('I'.$filaActual)->setValue($datosFila[8]);
                        //$sheet->getCell('J'.$filaActual)->setValue($datosFila[9]);
                        $sheet->getCell('K'.$filaActual)->setValue($datosFila[10]);
                        $sheet->getCell('L'.$filaActual)->setValue($datosFila[11]);
                        $sheet->getCell('M'.$filaActual)->setValue($datosFila[12]);
                        $sheet->getCell('B29')->setValue($datosFila[13]);
                        $registrosActuales++;
                        $filaActual++;
                    }

                    while ($hojaActual < $spreadsheet->getSheetCount()) {
                        // Eliminar la hoja activa
                        $spreadsheet->removeSheetByIndex($hojaActual);
                        
                    }

                    // SECCION DE  ENVIO POR CORREO
                    // Guarda el archivo Excel y lo envía al navegador
                    ob_start(); 
                    $objWriter=PHPExcel_IOFactory::createWriter($spreadsheet,'Excel2007');     
                    $objWriter->save('php://output');
                    $xlsData = ob_get_contents();
                    //$data->Excel=$xlsData;
                    ob_end_clean();
                    //$file="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
                    $data->ToExcel[$id_Factura]="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
                    $data->NombreReporte[$id_Factura]=$folio;
                    //$data->Excel=$xlsData;
                    $id_Factura++;
                }
                $data->Code='OK';
                $data->Trafico=$trafico;
                return ($data); 
            }
            else{
                $data->Code='Vacio';
                $data->MSG='Info:: Sin Resultados. No existen registros con los filtros  seleccionados.';
                return ($data);  
            }
        }

        static public function ctrReporteMahleListaPedimentos($trafico){
            $data= new stdClass();
            $FACTURAS=ModeloPNL::MdlListaFacturasXTrafMahle($trafico);
            $cont=count($FACTURAS);
            if($cont>0){ 
                $data->Code='Vacio';
                $data->Facturas=$FACTURAS;
                return ($data); 
            }
            else{
                $data->Code='Vacio';
                $data->MSG='Info:: Sin Resultados. No existen registros con los filtros  seleccionados.';
                return ($data);  
            }
        }

        static public function ctrReporteMahlexFactura($factura){
            $data= new stdClass();
            $templatePath  = "C:/wamp64/www/PlantillaMAHLE/PLANTILLA.xls";
            $registrosPorHoja = 15;
            // Realiza tu consulta para obtener los datos 
            $consulta=ModeloPNL::MdlListaFacturasXPedimentoEnviar($factura);
            $encabezado=ModeloPNL::MdlEncabezadoPedimentoEnviar($factura);
            $encabezado_direcciones=ModeloPNL::MdlEncabezadoPedimentoDirecciones($factura,$trafico);


            // Crea una instancia de hoja de cálculo
            $spreadsheet = new PHPExcel();	
            // Carga la plantilla de Excel existente
            $spreadsheet = PHPExcel_IOFactory::load($templatePath);
            // Selecciona la hoja activa 
            //VAR_DUMP($consulta);

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getCell('J8')->setValue($encabezado[0][3]);
            $sheet->getCell('K8')->setValue($encabezado[0][4]);
            $sheet->getCell('L8')->setValue($encabezado[0][2]);
            $sheet->getCell('M8')->setValue($encabezado[0][0]);
            $sheet->getCell('M31')->setValue($encabezado[0][1]);
            //$sheet->getCell('B28')->setValue($encabezado[0][5]);
            //$sheet->getCell('B31')->setValue($encabezado[0][6]);

            
            //ENCABEZADOS DE LAS DIRECCIONES

            if($encabezado_direcciones[0][3]==4){
                $sheet->getCell('A7')->setValue('');
                $sheet->getCell('F7')->setValue('');
                $sheet->getCell('A2')->setValue($encabezado_direcciones[0][9]);
                $sheet->getCell('A3')->setValue($encabezado_direcciones[0][10]);
                $sheet->getCell('A4')->setValue($encabezado_direcciones[0][11]);
                $sheet->getCell('A5')->setValue($encabezado_direcciones[0][8]);
                $sheet->getCell('D2')->setValue($encabezado_direcciones[0][5]);
                $sheet->getCell('D3')->setValue($encabezado_direcciones[0][6]);
                $sheet->getCell('D4')->setValue($encabezado_direcciones[0][7]);
                $sheet->getCell('D5')->setValue($encabezado_direcciones[0][4]);
                $sheet->getStyle('D5')->getAlignment()->setWrapText(true);
                $sheet->getCell('E2')->setValue($encabezado_direcciones[0][5]);
                $sheet->getCell('E3')->setValue($encabezado_direcciones[0][6]);
                $sheet->getCell('E4')->setValue($encabezado_direcciones[0][7]);
                $sheet->getCell('E5')->setValue($encabezado_direcciones[0][4]);
                $sheet->getStyle('E5')->getAlignment()->setWrapText(true);
                $sheet->getCell('F2')->setValue($encabezado_direcciones[0][13]);
                $sheet->getCell('F3')->setValue($encabezado_direcciones[0][14]);
                $sheet->getCell('F4')->setValue($encabezado_direcciones[0][15]);
                $sheet->getCell('F5')->setValue($encabezado_direcciones[0][12]);
                                
            }



            // Inicializa variables para controlar el número de registros y la fila actual
            $registrosActuales = 0;
            $hojaActual = 1;
            $filaActual = 12; // Comenzar en la fila 12
            foreach ($consulta as $datosFila ) {
                //VAR_DUmP($dato);
                // Si se alcanza el límite de registros por hoja, crear una nueva hoja
                if ($registrosActuales >= $registrosPorHoja) {
                    $spreadsheet->createSheet();
                    $spreadsheet->setActiveSheetIndex($hojaActual);
                    $sheet = $spreadsheet->getActiveSheet();
                    $hojaActual++;
                    $registrosActuales = 0;
                    $filaActual = 12; // Comenzar en la fila 12 de la nueva hoja
                    //Encabezado de la Factura
                    $sheet->getCell('J8')->setValue($encabezado[0][3]);
                    $sheet->getCell('K8')->setValue($encabezado[0][4]);
                    $sheet->getCell('L8')->setValue($encabezado[0][2]);
                    $sheet->getCell('M8')->setValue($encabezado[0][0]);
                    $sheet->getCell('M31')->setValue($encabezado[0][1]);
                    $sheet->getCell('B28')->setValue($encabezado[0][5]);
                    $sheet->getCell('B31')->setValue($encabezado[0][6]);
                    $sheet->getCell('B12')->setValue($encabezado[0][8]);
                    $sheet->getCell('J12')->setValue($encabezado[0][7]);
                    //ENCABEZADOS DE LAS DIRECCIONES

                    if($encabezado_direcciones[0][3]==4){
                        $sheet->getCell('A7')->setValue('');
                        $sheet->getCell('F7')->setValue('');
                        $sheet->getCell('A2')->setValue($encabezado_direcciones[0][9]);
                        $sheet->getCell('A3')->setValue($encabezado_direcciones[0][10]);
                        $sheet->getCell('A4')->setValue($encabezado_direcciones[0][11]);
                        $sheet->getCell('A5')->setValue($encabezado_direcciones[0][8]);
                        $sheet->getCell('D2')->setValue($encabezado_direcciones[0][5]);
                        $sheet->getCell('D3')->setValue($encabezado_direcciones[0][6]);
                        $sheet->getCell('D4')->setValue($encabezado_direcciones[0][7]);
                        $sheet->getCell('D5')->setValue($encabezado_direcciones[0][4]);
                        $sheet->getStyle('D5')->getAlignment()->setWrapText(true);
                        $sheet->getCell('E2')->setValue($encabezado_direcciones[0][5]);
                        $sheet->getCell('E3')->setValue($encabezado_direcciones[0][6]);
                        $sheet->getCell('E4')->setValue($encabezado_direcciones[0][7]);
                        $sheet->getCell('E5')->setValue($encabezado_direcciones[0][4]);
                        $sheet->getStyle('E5')->getAlignment()->setWrapText(true);
                        $sheet->getCell('F2')->setValue($encabezado_direcciones[0][13]);
                        $sheet->getCell('F3')->setValue($encabezado_direcciones[0][14]);
                        $sheet->getCell('F4')->setValue($encabezado_direcciones[0][15]);
                        $sheet->getCell('F5')->setValue($encabezado_direcciones[0][12]);
                    }

                }
                $sheet->getCell('A'.$filaActual)->setValue($datosFila[0]);
                //$sheet->getCell('B'.$filaActual)->setValue($datosFila[1]);
                $sheet->getCell('C'.$filaActual)->setValue($datosFila[2]);
                $sheet->getStyle('C'.$filaActual)->getAlignment()->setWrapText(true);
                $sheet->getCell('D'.$filaActual)->setValue($datosFila[3]);
                $sheet->getCell('E'.$filaActual)->setValue($datosFila[4]);
                $sheet->getStyle('E'.$filaActual)->getAlignment()->setWrapText(true);
                $sheet->getCell('F'.$filaActual)->setValue($datosFila[5]);
                $sheet->getCell('G'.$filaActual)->setValue($datosFila[6]);
                $sheet->getCell('H'.$filaActual)->setValue($datosFila[7]);
                //$sheet->getCell('I'.$filaActual)->setValue($datosFila[8]);
                //$sheet->getCell('J'.$filaActual)->setValue($datosFila[9]);
                $sheet->getCell('K'.$filaActual)->setValue($datosFila[10]);
                $sheet->getCell('L'.$filaActual)->setValue($datosFila[11]);
                $sheet->getCell('M'.$filaActual)->setValue($datosFila[12]);
                //$sheet->getCell('B29')->setValue($datosFila[13]);
                $registrosActuales++;
                $filaActual++;
            }
            // SECCION DE  ENVIO POR CORREO
            // Guarda el archivo Excel y lo envía al navegador
            ob_start(); 
            $objWriter=PHPExcel_IOFactory::createWriter($spreadsheet,'Excel2007');     
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $data->ToExcel="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
            $data->NombreReporte=$folio;
            return ($data);     
        }

        static public function ctrGenerarReporte($Datos,$Stp,$ReporteNombre){
            //Ajustar  todas las  respuestas con  mensajes de  error y en caso de  ocurirr, actualmente si  ocurre un error no se regresa el error
            //El sistema se queda colgado porque entra en un error y no puede  retornar un mensaje
            $data = new stdClass();
            $GenerarReporte=ModeloadReportes::MdlConsultarReportes($Datos,$Stp);
            //var_dump($GenerarReporte);
            if(!empty($GenerarReporte)){
                //El campo Level es de  uso de MYSQL, no se debe  usar  este nombre para las columnas de los reportes.
                if(!isset($GenerarReporte[0]['Level'])){ 
                    $ToExcel=ExportToExcel($GenerarReporte,$ReporteNombre);
                    if($ToExcel!='vacio'){
                        $data->Code='OK';
                        $data->ToExcel=$ToExcel->file;
                        $data->Excel=$ToExcel->Excel;
                        return ($data);  
                    }
                    else{
                        $data->Code='Vacio';
                        $data->MSG='Info:: Sin Resultados. No existen registros con los filtros  seleccionados.';
                        return ($data);  
                    }
                }
                $data->Code='Error';
                $data->MSG='Error de BD:: '.$GenerarReporte[0][2];
                return ($data); 
            }
            else{
                $data->Code='Error';
                $data->MSG='Error Conexion a BD:: Retorno Vacio';
                return $data;
            }
        }

        /* FUNCIONES PARA CARTA PORTE */
        static public function ctrReporteCartaPorte($OrdenCarga){
            $nombre_r=explode("/",$OrdenCarga);
            $data= new stdClass();
            $templatePath  = "C:/wamp64/www/PlantillaMAHLE/LAYOUTCP.xls";
            $registrosPorHoja = 7;
            $lineas=ModeloPNL::MdlLineasCartaPorte($OrdenCarga);
            $MSG='';
            $id_linea=0;

            $cont=count($lineas);
            if($cont>0){ 
                // Crea una instancia de hoja de cálculo
                $spreadsheet = new PHPExcel();	
                // Carga la plantilla de Excel existente
                $spreadsheet = PHPExcel_IOFactory::load($templatePath);
                // Selecciona la hoja activa 
                $sheet = $spreadsheet->getActiveSheet();
                // Inicializa variables para controlar el número de registros y la fila actual
                $registrosActuales = 0;
                $hojaActual = 1;
                $filaActual = 7; // Comenzar en la fila 12
                //$sheet->getCell('K33')->setValue('FACTURA HOJA '+$hojaActual);

                $styleArray_error = array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '800000')
                    ),
                    'font' => array(
                        'color' => array('rgb' => 'ffffff')
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    )
                );        


                foreach($lineas as $datosFila){
                        $sheet->getCell('Q'.$filaActual)->setValue($datosFila[0]);
                        if($datosFila[1]==''){
                            $sheet->getStyle('R'.$filaActual)->applyFromArray($styleArray_error);
                            $sheet->getStyle('S'.$filaActual)->applyFromArray($styleArray_error);
                            $sheet->getCell('R'.$filaActual)->setValue("Datos no encontrados");
                            $sheet->getCell('S'.$filaActual)->setValue($datosFila[2]);
                        }
                        else{
                            $sheet->getCell('R'.$filaActual)->setValue($datosFila[1]);
                            $sheet->getCell('S'.$filaActual)->setValue($datosFila[2]);
                        }
                        //$sheet->getColumnDimension('D')->setAutoSize(true); // Ajustar el tamaño de la columna D al contenido
                        $sheet->getCell('T'.$filaActual)->setValue($datosFila[3]);
                        //$sheet->getColumnDimension('E')->setAutoSize(true); // Ajustar el tamaño de la columna D al contenido
                        $sheet->getCell('U'.$filaActual)->setValue($datosFila[4]);
                        //$sheet->getStyle('U'.$filaActual)->getAlignment()->setWrapText(true); // Habilitar el ajuste de texto automático en la celda F
                        //$sheet->getRowDimension($filaActual)->setRowHeight(-1); // Restaurar la altura de la fila a la altura predeterminada
                        $sheet->getCell('V'.$filaActual)->setValue($datosFila[5]);
                        $sheet->getCell('W'.$filaActual)->setValue($datosFila[6]);
                        $sheet->getCell('X'.$filaActual)->setValue($datosFila[7]);
                        $sheet->getCell('Y'.$filaActual)->setValue($datosFila[8]);
                        //$sheet->getCell('J'.$filaActual)->setValue($datosFila[9]);
                        $sheet->getCell('Z'.$filaActual)->setValue($datosFila[9]);
                        $sheet->getCell('AA'.$filaActual)->setValue($datosFila[10]);
                        $sheet->getCell('AB'.$filaActual)->setValue($datosFila[11]);
                        $sheet->getCell('AC'.$filaActual)->setValue($datosFila[12]);

                        $sheet->getCell('A'.$filaActual)->setValue($datosFila[13]);
                        $sheet->getCell('B'.$filaActual)->setValue($datosFila[14]);
                        $sheet->getCell('C'.$filaActual)->setValue($datosFila[15]);
                        $sheet->getCell('D'.$filaActual)->setValue($datosFila[16]);
                        $sheet->getCell('E'.$filaActual)->setValue($datosFila[17]);
                        $sheet->getCell('F'.$filaActual)->setValue($datosFila[18]);
                        $sheet->getCell('G'.$filaActual)->setValue($datosFila[19]);
                        $sheet->getCell('H'.$filaActual)->setValue($datosFila[20]);

                        $sheet->getCell('I'.$filaActual)->setValue($datosFila[21]);
                        $sheet->getCell('J'.$filaActual)->setValue($datosFila[22]);
                        $sheet->getCell('K'.$filaActual)->setValue($datosFila[23]);
                        $sheet->getCell('L'.$filaActual)->setValue($datosFila[24]);
                        $sheet->getCell('M'.$filaActual)->setValue($datosFila[25]);
                        $sheet->getCell('N'.$filaActual)->setValue($datosFila[26]);
                        $sheet->getCell('O'.$filaActual)->setValue($datosFila[27]);
                        $sheet->getCell('P'.$filaActual)->setValue($datosFila[28]);

                        $registrosActuales++;
                        $filaActual++;
                }
                
                // SECCION DE  ENVIO POR CORREO
                // Guarda el archivo Excel y lo envía al navegador
                ob_start(); 
                $objWriter=PHPExcel_IOFactory::createWriter($spreadsheet,'Excel2007');     
                $objWriter->save('php://output');
                $xlsData = ob_get_contents();
                //$data->Excel=$xlsData;
                ob_end_clean();
                //$file="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
                $data->ToExcel[0]="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
                $data->NombreReporte[0]=$OrdenCarga;

                $data->Code='OK';
                $data->Trafico=$OrdenCarga;
                return ($data); 
            }
            else{
                $data->Code='Vacio';
                $data->MSG='Info:: Sin Resultados. No existen registros con los filtros  seleccionados.';
                return ($data);  
            }
        }

        /* REPORTE DE MAHLE COMPONENTES*/

        static public function ctrReporteMahleC($trafico){
            $data= new stdClass();
            $templatePath  = "C:/wamp64/www/PlantillaMAHLE/PLANTILLA.xls";
            $registrosPorHoja = 15;
            $FACTURAS=ModeloPNL::MdlListaFacturasXTrafMahle($trafico);
            $MSG='';
            $id_Factura=0;
            $cont=count($FACTURAS);
            if($cont>0){ 
                foreach($FACTURAS as $Factura=>$folio){
                    // Realiza tu consulta para obtener los datos 
                    $consulta=ModeloPNL::MdlListaFacturasXPedimentoEnviar($folio[0],$trafico);
                    $encabezado=ModeloPNL::MdlEncabezadoPedimentoEnviar($folio[0],$trafico);
                    $encabezado_direcciones=ModeloPNL::MdlEncabezadoPedimentoDireccionesC($folio[0],$trafico);
                    // Crea una instancia de hoja de cálculo
                    $spreadsheet = new PHPExcel();	
                    // Carga la plantilla de Excel existente
                    $spreadsheet = PHPExcel_IOFactory::load($templatePath);
                    // Selecciona la hoja activa 
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->getCell('J8')->setValue($encabezado[0][3]);
                    $sheet->getCell('K8')->setValue($encabezado[0][4]);
                    $sheet->getCell('L8')->setValue($encabezado[0][2]);
                    $sheet->getCell('M8')->setValue($encabezado[0][0]);
                    $sheet->getCell('M31')->setValue($encabezado[0][1]);
                    $sheet->getCell('B28')->setValue($encabezado[0][5]);
                    $sheet->getCell('B31')->setValue($encabezado[0][6]);
                    $sheet->getCell('B12')->setValue($encabezado[0][8]);
                    $sheet->getCell('J12')->setValue($encabezado[0][7]);

                    //ENCABEZADOS DE LAS DIRECCIONES
                    if($encabezado_direcciones[0][3]==4){
                        $sheet->getCell('A7')->setValue('');
                        $sheet->getCell('F7')->setValue('');
                        $sheet->getCell('A2')->setValue($encabezado_direcciones[0][9]);
                        $sheet->getCell('A3')->setValue($encabezado_direcciones[0][10]);
                        $sheet->getCell('A4')->setValue($encabezado_direcciones[0][11]);
                        $sheet->getCell('A5')->setValue($encabezado_direcciones[0][8]);
                        $sheet->getCell('D2')->setValue($encabezado_direcciones[0][5]);
                        $sheet->getCell('D3')->setValue($encabezado_direcciones[0][6]);
                        $sheet->getCell('D4')->setValue($encabezado_direcciones[0][7]);
                        $sheet->getCell('D5')->setValue($encabezado_direcciones[0][4]);
                        $sheet->getStyle('D5')->getAlignment()->setWrapText(true);
                        $sheet->getCell('E2')->setValue($encabezado_direcciones[0][5]);
                        $sheet->getCell('E3')->setValue($encabezado_direcciones[0][6]);
                        $sheet->getCell('E4')->setValue($encabezado_direcciones[0][7]);
                        $sheet->getCell('E5')->setValue($encabezado_direcciones[0][4]);
                        $sheet->getStyle('E5')->getAlignment()->setWrapText(true);
                        $sheet->getCell('F2')->setValue($encabezado_direcciones[0][13]);
                        $sheet->getCell('F3')->setValue($encabezado_direcciones[0][14]);
                        $sheet->getCell('F4')->setValue($encabezado_direcciones[0][15]);
                        $sheet->getCell('F5')->setValue($encabezado_direcciones[0][12]);
                        
                    }
                    
                    // Inicializa variables para controlar el número de registros y la fila actual
                    $registrosActuales = 0;
                    $hojaActual = 1;
                    $filaActual = 12; // Comenzar en la fila 12
                    //$sheet->getCell('K33')->setValue('FACTURA HOJA '+$hojaActual);
                    foreach ($consulta as $datosFila ) {
                        //VAR_DUmP($dato);
                        // Si se alcanza el límite de registros por hoja, crear una nueva hoja
                        if ($registrosActuales >= $registrosPorHoja) {
                            //$spreadsheet->createSheet();
                            $spreadsheet->setActiveSheetIndex($hojaActual);
                            $sheet = $spreadsheet->getActiveSheet();
                            //clonado de  hoja

                            //clonado de hoja

                            $hojaActual++;
                            $registrosActuales = 0;
                            $filaActual = 12; // Comenzar en la fila 12 de la nueva hoja
                            //Encabezado de la Factura
                            $sheet->getCell('J8')->setValue($encabezado[0][3]);
                            $sheet->getCell('K8')->setValue($encabezado[0][4]);
                            $sheet->getCell('L8')->setValue($encabezado[0][2]);
                            $sheet->getCell('M8')->setValue($encabezado[0][0]);
                            $sheet->getCell('M31')->setValue($encabezado[0][1]);
                            $sheet->getCell('B28')->setValue($encabezado[0][5]);
                            $sheet->getCell('B31')->setValue($encabezado[0][6]);
                            //$sheet->getCell('B12')->setValue($encabezado[0][8]);
                            $sheet->getCell('J12')->setValue($encabezado[0][7]);
                            //$sheet->getCell('K33')->setValue('FACTURA HOJA '+$hojaActual);

                            //ENCABEZADOS DE LAS DIRECCIONES

                            if($encabezado_direcciones[0][3]==4){
                                $sheet->getCell('A7')->setValue('');
                                $sheet->getCell('F7')->setValue('');
                                $sheet->getCell('A2')->setValue($encabezado_direcciones[0][9]);
                                $sheet->getCell('A3')->setValue($encabezado_direcciones[0][10]);
                                $sheet->getCell('A4')->setValue($encabezado_direcciones[0][11]);
                                $sheet->getCell('A5')->setValue($encabezado_direcciones[0][8]);
                                $sheet->getCell('D2')->setValue($encabezado_direcciones[0][5]);
                                $sheet->getCell('D3')->setValue($encabezado_direcciones[0][6]);
                                $sheet->getCell('D4')->setValue($encabezado_direcciones[0][7]);
                                $sheet->getCell('D5')->setValue($encabezado_direcciones[0][4]);
                                $sheet->getStyle('D5')->getAlignment()->setWrapText(true);
                                $sheet->getCell('E2')->setValue($encabezado_direcciones[0][5]);
                                $sheet->getCell('E3')->setValue($encabezado_direcciones[0][6]);
                                $sheet->getCell('E4')->setValue($encabezado_direcciones[0][7]);
                                $sheet->getCell('E5')->setValue($encabezado_direcciones[0][4]);
                                $sheet->getStyle('E5')->getAlignment()->setWrapText(true);
                                $sheet->getCell('F2')->setValue($encabezado_direcciones[0][13]);
                                $sheet->getCell('F3')->setValue($encabezado_direcciones[0][14]);
                                $sheet->getCell('F4')->setValue($encabezado_direcciones[0][15]);
                                $sheet->getCell('F5')->setValue($encabezado_direcciones[0][12]);
                                
                            }
                            
                        }
                        $sheet->getCell('A'.$filaActual)->setValue($datosFila[0]);
                        //$sheet->getCell('B'.$filaActual)->setValue($datosFila[1]);
                        $sheet->getCell('C'.$filaActual)->setValue($datosFila[2]);
                        $sheet->getStyle('C'.$filaActual)->getAlignment()->setWrapText(true);
                        $sheet->getCell('D'.$filaActual)->setValue($datosFila[3]);
                        //$sheet->getColumnDimension('D')->setAutoSize(true); // Ajustar el tamaño de la columna D al contenido
                        $sheet->getCell('E'.$filaActual)->setValue($datosFila[4]);
                        //$sheet->getColumnDimension('E')->setAutoSize(true); // Ajustar el tamaño de la columna D al contenido
                        $sheet->getStyle('E'.$filaActual)->getAlignment()->setWrapText(true); // Habilitar el ajuste de texto automático en la celda F
                        $sheet->getRowDimension($filaActual)->setRowHeight(-1); // Restaurar la altura de la fila a la altura predeterminada
                        $sheet->getCell('F'.$filaActual)->setValue($datosFila[5]);
                        
                        $sheet->getCell('G'.$filaActual)->setValue($datosFila[6]);
                        $sheet->getCell('H'.$filaActual)->setValue($datosFila[7]);
                        $sheet->getCell('I'.$filaActual)->setValue($datosFila[8]);
                        //$sheet->getCell('J'.$filaActual)->setValue($datosFila[9]);
                        $sheet->getCell('K'.$filaActual)->setValue($datosFila[10]);
                        $sheet->getCell('L'.$filaActual)->setValue($datosFila[11]);
                        $sheet->getCell('M'.$filaActual)->setValue($datosFila[12]);
                        $sheet->getCell('B29')->setValue($datosFila[13]);
                        $registrosActuales++;
                        $filaActual++;
                    }

                    while ($hojaActual < $spreadsheet->getSheetCount()) {
                        // Eliminar la hoja activa
                        $spreadsheet->removeSheetByIndex($hojaActual);
                        
                    }

                    // SECCION DE  ENVIO POR CORREO
                    // Guarda el archivo Excel y lo envía al navegador
                    ob_start(); 
                    $objWriter=PHPExcel_IOFactory::createWriter($spreadsheet,'Excel2007');     
                    $objWriter->save('php://output');
                    $xlsData = ob_get_contents();
                    //$data->Excel=$xlsData;
                    ob_end_clean();
                    //$file="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
                    $data->ToExcel[$id_Factura]="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
                    $data->NombreReporte[$id_Factura]=$folio;
                    //$data->Excel=$xlsData;
                    $id_Factura++;
                }
                $data->Code='OK';
                $data->Trafico=$trafico;
                return ($data); 
            }
            else{
                $data->Code='Vacio';
                $data->MSG='Info:: Sin Resultados. No existen registros con los filtros  seleccionados.';
                return ($data);  
            }
        }

    }