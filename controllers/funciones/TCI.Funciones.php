<?php
//LLEVAR UN DICCIONARIO DONDE SE  INICIALICEN  TODAS LAS  VARIABLES  GLOBALES
$_SESSION['Notificacion_Manifiestos']='';

function struuid($entropy){
	$s=uniqid("",$entropy);
    $num= hexdec(str_replace(".","",(string)$s));
    $index = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base= strlen($index);
    $out = '';
    for($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
        $a = floor($num / pow($base,$t));
        $out = $out.substr($index,$a,1);
        $num = $num-($a*pow($base,$t));
    }
	return $out;
}

function ExportToExcel($respuesta,$ReporteNombre){
    //aGREGAR  VALIDACION
    $data = new stdClass();
    $cont=count($respuesta);
    if($cont>0){
        $c_name=array_keys($respuesta[0]);
        $cnt=count($c_name);
        for($i=0;$i<$cnt;$i++){
            if(is_numeric($c_name[$i])){
                unset($c_name[$i]);
                
            }
        }
        $c_name=array_values($c_name);
        $cnt=count($c_name);
        $abc=range("A","Z");
        //Estructura constante
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->
            getProperties()
                ->setCreator("www.tramitaciones.com")
                ->setLastModifiedBy("IT TCI")
                ->setTitle("Report of KPI")
                ->setSubject("Report of KPI")
                ->setDescription("Report of KPI")
                ->setKeywords("TCI Report")
                ->setCategory("Metricos");
        $styleArray_titulo = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '002060')
            ),
            'font' => array(
                'color' => array('rgb' => 'ffffff')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
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
        $style_array=array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getCell('A1')->setValue('GRUPO TRAMITACIONES - '.$ReporteNombre);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray_titulo);
        $objPHPExcel->getActiveSheet()->getStyle( 'A1' )->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->mergeCells($abc[0].'1:'.$abc[$cnt-1].'1');
        $objPHPExcel->getActiveSheet()->getCell('A2')->setValue('Date Report '.date('m/d/Y'));
        $objPHPExcel->getActiveSheet()->mergeCells($abc[0].'2:'.$abc[$cnt-1].'2');
        $objPHPExcel->getActiveSheet()->getStyle( 'A2' )->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray_titulo);
        
        //Evita que los  campos en el rango  se contraigan en  modo cientifico
        $objPHPExcel->getActiveSheet()->getStyle($abc[0].'2:'.$abc[$cnt-1].strval($cont+3))->getNumberFormat()->setFormatCode('0');

        for($i=0;$i<$cnt;$i++){
            $objPHPExcel->getActiveSheet()->getCell( $abc[$i].'3')->setValue($c_name[$i]);
        }
        $objPHPExcel->getActiveSheet()->getStyle( $abc[0].'3:'.$abc[$cnt-1].'3' )->getFont()->setBold( true );
        $c = 4;	
        $v = 0;
        if($cont>0){ 
            for($x=0;$x<$cont;$x++){
                for($j=0;$j<$cnt;$j++){
                    $objPHPExcel->getActiveSheet()->getCell($abc[$j].$c)->setValue($respuesta[$x][$c_name[$j]]);
                }
                $v = $v+1;
                $c += 1;
            }
            foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
                $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
            }
            $objPHPExcel->getActiveSheet()->setTitle('TCI Reports');
            $objPHPExcel->setActiveSheetIndex(0);
            //header('Content-Type: application/xlsx');vnd.ms-excel
            ob_start();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="report_importer.xlsx"');
            //header('Cache-Control: max-age=0');      
            $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007'); 
            //ob_start();     
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            $data->Excel=$xlsData;
            ob_end_clean();
            //SE PUEDE  ENVIAR  SIN LA CABECERA
            /*$data->response =  array(
                'Code' => 'OK',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );*/
            //$data->response=$response;
            $data->file="data:application/vnd.ms-excel;base64,".base64_encode($xlsData);
            return $data;
        }
    }
    else{
        return "vacio";
    }
    
}

//Revisar la  funcion del envio actomativo, esta actualizada, ajustar esta  
function SendMail($mailProfile,$mailContact,$ExcelFile,$ReporteName){
    $mail = new PHPMailer;
    //$mail->isSMTP(); 
    $mail->Mailer = "smtp";
    $mail->Host = 'smtp.office365.com';
    $mail->Port = 587; 
    $mail->SMTPAuth = true; 
    $mail->Username = 'itnotificaciones@tramitaciones.com';
    $mail->Password = 'XHl5iXWQ03dfyylAswRX'; 
    $mail->SMTPSecure = 'TLS'; 
    $mail->Timeout=120;
    //Concluye Valores por defecto

    $mail->From = 'itnotificaciones@tramitaciones.com';
    $mail->FromName = 'TCI - No reply';
    $mail->ClearAllRecipients();
    $c_mail=count($mailContact);
    for($x=0;$x<$c_mail;$x++){
        $mail->AddAddress($mailContact[$x][0]);
    }
    //$mail->AddAddress('FRodriguez@tramitaciones.com'); // Para Pruebas, deshabilitar el for  y activar esta linea
    // set email format to HTML
    $mail->IsHTML(true);            
    $mail->clearAttachments();
    // De momento  sirve solo para  agreagar excel, colocar condiciones para  aceptar mas  formatos.
    //$mail->AddAttachment($ExcelFile);
    $mail->AddStringAttachment($ExcelFile, $ReporteName .' '.date('m-d-Y').".xlsx");
    //Agregar importador
    $mail->Subject = $mailProfile[0][0];
    //El body es  standard,  se puede  ajustar para dejar un  formato mas  visual.              
    $mail->Body =   '<html>
                        <head>
                            <style type="text/css">
                                #customers {
                                    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                                    border-collapse: collapse;
                                    width: 100%;
                                }
                                #customers td, #customers th {
                                    border: 1px solid #ddd;
                                    padding: 8px;
                                    font-size:12px;
                                }
                                #customers tr:nth-child(even){background-color: #f2f2f2;}
                                #customers tr:hover {background-color: #ddd;}
                                #customers th {
                                    padding-top: 12px;
                                    padding-bottom: 12px;
                                    text-align: left;
                                    background-color: #1598b3;
                                    color: white;
                                }
                                .status2 {
                                    background-color: #e44747;
                                    color: #FFF;
                                }
                                .status7 {
                                    background-color: #53a728;
                                    color: #FFF;
                                }
                            </style>
                        </head>
                        <body style="background-color: #f5f5f5;">
                            <div>
                                <table style="border: 1px solid #dcdada;background-color: #FFF;border-spacing: 0;width: 100%;">
                                    <tr>
                                        <td style="font-size: 18px;font-family: monospace;    text-align: center;padding: 15px;background-color: #3f51b5;color: #FFF;" >
                                            <div>GRUPO TRAMITACIONES </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;font-family: monospace;padding: 10px;" >
                                            <div>'.$mailProfile[0][1].'</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;padding: 15px;background-color: #a7a7a7;" colspan="2">
                                            <a href="#" style="color: #fffefe;padding: 8px 50px;text-decoration: none;font-family: monospace;">Do not Replay this message</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </body>
                    </html>';                       
    if(!$mail->Send()){
        $error='';
        $error.= "Mailer Error: " . $mail->ErrorInfo;
        return $error;
    }
    else{
        return "OK";
    }
}

class ControladorTCI{
    static public function ctrNotificaciones_Manifest(){
        if($_SESSION['Notificacion_Manifiestos']=='' || $_SESSION['Notificacion_Manifiestos'] < date("Y-m-d H:i:s", strtotime('-10 minutes'))){
            $_SESSION['Notificacion_Manifiestos']=date("Y-m-d H:i:s", time());
            $Importers=ModeloTCI::MdlNotificationImporters();
            if(isset($Importers)){
                $manifest=array();
                $c_registros=0;
                foreach($Importers as $row_Imp){
                    $Manifest=ModeloTCI::MdlNotificationManifest($row_Imp[0]);
                    $manifest[$c_registros][0]=$row_Imp[0].': ';
                    $manifest[$c_registros][1]='';
                    foreach($Manifest as $row){
                        $manifest[$c_registros][1].=$row[0].' at '.$row[1].' '.$row[2] .'   |    ';
                    }
                    $c_registros+=1;
                }
                return ($manifest); 
            }
            else{
                return 'Empty'; 
            }
        }
        else{
            return 'Empty'; 
        }
    }
}


?>