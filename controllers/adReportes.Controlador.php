<?php
    class ControladoradReportes{
        static public function ctrInicializar($Modulo){
            $data= new stdClass();
            $Reportes=ModeloadReportes::MdlInicializar($Modulo);
            $cont=count($Reportes);
            if($cont>0){ 
                for($x=0;$x<$cont;$x++){
                    $ID = $Reportes[$x]["IDREP"];
                    $Reporte= $Reportes[$x]["Nombre_Reporte"];
                    $Stp= $Reportes[$x]["SQL"];
                    $Boton= '<div class="btn-group" role="group">'.
                                //'<button type="button" class="btn btn-sm btn-dark adReportes_ViewmdlEnviarCorreo" id="'.$x.'" name="'.$x.'"  data-reporte='.$ID.'>'. 
                                //    '<i class="fas fa-envelope fa-fw"></i>'.
                                //'</button>'.
                                '<button type="button" class="btn btn-sm btn-dark adReportes_ViewmdlListScheduleReport" id="'.$x.'" name="'.$x.'"  data-reporte='.$ID.' data-nombre="'.$Reporte.'" data-stp='.$Stp.'>'. 
                                    '<i class="fas fa-mail-bulk fa-fw"></i>'.
                                '</button>'. 
                                '<button type="button" class="btn btn-sm btn-dark adReportes_ViewmdlDescargarReporte" id="'.$x.'" name="'.$x.'"  data-reporte='.$ID.' data-nombre="'.$Reporte.'" data-stp='.$Stp.'>'. 
                                    '<i class="fas fa-download fa-fw"></i>'.
                                '</button>'. 
                                '<button type="button" class="btn btn-sm btn-dark" data-toggle="tooltip" data-placement="top" title="'.$Reportes[$x]["Descripcion"].'">'. 
                                    '<i class="fas fa-info-circle fa-fw"></i>'.
                                '</button>'.  
                            '</div>';    
                    $data->Reportes[$x]=array($ID,$Reporte,$Boton); 
                }
            }
            else{
                $data->Reportes[0]=array('No data','',''); 
            }
            return ($data); 
        }
        static public function ctrFiltrosDescarga($Reporte){
            $FiltrosDescarga=ModeloadReportes::MdlFiltrosDescarga($Reporte);
            return ($FiltrosDescarga); 
        }
        static public function ctrListadoClientes(){
            $ListadoClientes=ModeloadReportes::MdlListadoClientes();
            return ($ListadoClientes); 
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
                        //$data->ToExcel=$ToExcel->response;
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
        static public function ctrGenerarReporteSinFiltros($Stp,$ReporteNombre){
            $data = new stdClass();
            $GenerarReporte=ModeloadReportes::MdlConsultarReportesSinFiltros($Stp);
            //var_dump($GenerarReporteSinFiltros);
            //$ToExcel=ExportToExcel($GenerarReporteSinFiltros,$ReporteNombre);
            //var_dump($ToExcel);
            //return ($ToExcel);

            if(!empty($GenerarReporte)){
                //El campo Level es de  uso de MYSQL, no se debe  usar  este nombre para las columnas de los reportes.
                if(!isset($GenerarReporte[0]['Level'])){ 
                    $ToExcel=ExportToExcel($GenerarReporte,$ReporteNombre);
                    if($ToExcel!='vacio'){
                        $data->Code='OK';
                        //$data->ToExcel=$ToExcel->response;
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
        static public function ctrListadoImportadores(){
            $ListadoImportadores=ModeloadReportes::MdlListadoImportadores();
            return ($ListadoImportadores); 
        }
        static public function ctrSaveMailInformation($datos,$SaveInformation){
            if($SaveInformation=="New"){
                $K_RELM_C=struuid(true);
                $CrearRelacionImportCorreo=ModeloadReportes::MdlCrearRelacionImportCorreo( $K_RELM_C,$datos['Reporte'],$datos['Importador']);
                if($CrearRelacionImportCorreo!=='ok'){
                    return 'Error RelacionImportCorreo: '.$CrearRelacionImportCorreo; 
                }
            }
            else{
                $K_RELM_C=$datos["KEY_RELM_C"]; 
            }
            $EstructaCorreo=ModeloadReportes::MdlEstructaCorreo($SaveInformation,$K_RELM_C,$datos['Subject'],$datos['Body']);
            if($EstructaCorreo=='ok'){	
                $count=count($datos['Contactos']);
                if($count>0){
                    for($i=0;$i<=$count-1;$i++){
                        $ListadoContactos=ModeloadReportes::MdlListadoContactos($K_RELM_C,$datos['Contactos'][$i]);
                        if($ListadoContactos!=="ok"){
                            return 'Error ListadoContactos: '.$ListadoContactos; 
                        } 
                    }
                    return $ListadoContactos;
                }
                return $EstructaCorreo;
            }
            else{ 
                return 'Error EstructaCorreo: '.$EstructaCorreo; 
            }
        }
        static public function ctrListaReportesAutomatico($IdReporte){
            $data= new stdClass();
            $ListaReportesAutomatico=ModeloadReportes::MdlListaReportesAutomatico($IdReporte);
            $cont=count($ListaReportesAutomatico);
            if($cont>0){ 
                for($x=0;$x<$cont;$x++){
                    $KEY_RELM_C=$ListaReportesAutomatico[$x]["KEY_RELM_C"];
                    $Nom_Importer = $ListaReportesAutomatico[$x]["Nom_Importer"];
                    $Titulo= $ListaReportesAutomatico[$x]["Titulo"];
                    $CFecha=$ListaReportesAutomatico[$x]["f_Alta"];
                    $Botones=     '<div class="btn-group">'.
                                    '<button type="button" class="btn btn-dark btn-smy adReportes_ViewmdlScheduleMail_UPData" id="adReportes_bntMail'.$x.'" name="adReportes_bntMail'.$x.'"  data-key='.$KEY_RELM_C.' data-nomimp="'.$Nom_Importer.'">'. 
                                        '<i class="fas fa-envelope"></i>'.
                                    '</button>'. 
                                    '<button type="button" class="btn btn-dark btn-smy adReportes_ViewmdlFrecuencyMail" id="adReportes_bntSchedule'.$x.'" name="adReportes_bntSchedule'.$x.'"  data-key='.$KEY_RELM_C.' data-nomimp="'.$Nom_Importer.'">'. 
                                        '<i class="fas fa-calendar-alt"></i> '.
                                    '</button>'.
                                    '<button type="button" class="btn btn-dark btn-smy adReportes_DelSheduleMail" id="adReportes_bntDelMail'.$x.'" name="adReportes_bntDelMail'.$x.'"  data-key='.$KEY_RELM_C.'>'. 
                                        '<i class="fas fa-trash-alt"></i>'.
                                    '</button>'.
                                '</div>';       
                    $data->ListaReportes[$x]=array($Nom_Importer,$Titulo,$CFecha,$Botones); 
                }
            }
            else{
                $data->ListaReportes[0]=array('No data','','',''); 
            }
            $FiltrosDescarga=ModeloadReportes::MdlFiltrosDescarga($IdReporte);
            $data->FiltrosDescarga=$FiltrosDescarga; 
            return ($data);   
        }
        static public function ctrScheduleMailUPData($KEY_RELM_C){
            $data= new stdClass();
            $EstructuraCorreo=ModeloadReportes::MdlEstructuraCorreoAuto($KEY_RELM_C);
            $ContactosCorreo=ModeloadReportes::MdlContactosCorreosAuto($KEY_RELM_C);
            $cont=count($ContactosCorreo);
            if($cont>0){ 
                for($x=0;$x<$cont;$x++){
                    $IDMIC=$ContactosCorreo[$x]["IDMIC"];
                    $Contact_Mail = $ContactosCorreo[$x]["Contact_Mail"];
                    $tipo= $ContactosCorreo[$x]["tipo"];
                    $CFecha=$ContactosCorreo[$x]["F_alta"];
                    $Botones=     '<div class="btn-group">'.
                                    '<button type="button" class="btn btn-dark btn-smy adReportes_DelContactMail" id="adReportes_bntDelContact'.$x.'" name="adReportes_bntDelContact'.$x.'"  data-idmic='.$IDMIC.'>'. 
                                        '<i class="fas fa-trash-alt"></i>'.
                                    '</button>'.
                                '</div>';       
                    $data->ListaContactos[$x]=array($tipo ,$Contact_Mail,$CFecha,$Botones); 
                }
            }
            else{
                $data->ListaContactos[0]=array('No data','','',''); 
            }
            $data->Titulo=$EstructuraCorreo[0]['Titulo'];
            $data->Cuerpo=$EstructuraCorreo[0]['Cuerpo'];
            $data->Att_excel=$EstructuraCorreo[0]['Att_excel'];
            return ($data); 
        }
        static public function ctrDelSheduleMail($KEY_RELM_C){
            $DelSheduleMail=ModeloadReportes::MdlDelSheduleMail($KEY_RELM_C);
            return $DelSheduleMail; 
        }
        static public function ctrDelContactMail($IDMIC){
            $DelContactMail=ModeloadReportes::MdlDelContactMail($IDMIC);
            return $DelContactMail; 
        }
        static public function ctrSaveFrecuency($datos,$KEY_RELM_C){
            $SaveFrecuency_Del=ModeloadReportes::MdlSaveFrecuency_Del($KEY_RELM_C);
            if($SaveFrecuency_Del=="ok"){
                $count=count($datos['ValoresFiltros']);
                if($count>0){
                    for($i=0;$i<=$count-1;$i++){
                        $SaveFrecuency_ValoresFiltros=ModeloadReportes::MdlSaveFrecuency_ValoresFiltros($KEY_RELM_C,$datos['ValoresFiltros'][$i][0],$datos['ValoresFiltros'][$i][1],$datos['ValoresFiltros'][$i][2]);
                        if($SaveFrecuency_ValoresFiltros!=="ok"){
                            $SaveFrecuency_Del=ModeloadReportes::MdlSaveFrecuency_Del($KEY_RELM_C);
                            return 'Error Valores Filtros: '.$SaveFrecuency_ValoresFiltros; 
                        } 
                    }
                    $count=count($datos['Dias']);
                    if($count>0){
                        for($i=0;$i<=$count-1;$i++){
                            $SaveFrecuency_Dias=ModeloadReportes::MdlSaveFrecuency_Dias($KEY_RELM_C,$datos['Type'],$datos['Dias'][$i][0]);
                            if($SaveFrecuency_Dias!=="ok"){
                                $SaveFrecuency_Del=ModeloadReportes::MdlSaveFrecuency_Del($KEY_RELM_C);
                                return 'Error Dias: '.$SaveFrecuency_Dias; 
                            } 
                        }
                        $count=count($datos['Horas']);
                        if($count>0){
                            for($i=0;$i<=$count-1;$i++){
                                //var_dump($datos['Horas'][$i][0]);
                                $SaveFrecuency_Horas=ModeloadReportes::MdlSaveFrecuency_Horas($KEY_RELM_C,$datos['Horas'][$i][0]);
                                if($SaveFrecuency_Horas!=="ok"){
                                    $SaveFrecuency_Del=ModeloadReportes::MdlSaveFrecuency_Del($KEY_RELM_C);
                                    return 'Error Horas: '.$SaveFrecuency_Horas; 
                                } 
                            }
                            //Guardo el sigiuente disparo en la BD
                            $NextShoot=ModeloadReportes::MdlSaveFrecuency_NextShoot($KEY_RELM_C);
                            return $NextShoot;
                        }
                        return 'Error Horas: No se  seleccionaron las horas';
                    }
                    return 'Error Dias: No se  seleccionaron los dias';
                }
                return 'Error Filtros: No se capturaron los filtros';
            }
            else{ 
                return 'Error EliminarFrecuencia: '.$SaveFrecuency_Del; 
            }
        }
        static public function ctrViewFrecuencyMail($KEY_RELM_C){
            $data = new stdClass();
            $ViewFrecuencyMail_filters=ModeloadReportes::MdlViewFrecuencyMail_filters($KEY_RELM_C);
            if(!empty($ViewFrecuencyMail_filters)){
                $ViewFrecuencyMail_days=ModeloadReportes::MdlViewFrecuencyMail_days($KEY_RELM_C);
                if(!empty($ViewFrecuencyMail_days)){
                    $ViewFrecuencyMail_hours=ModeloadReportes::MdlViewFrecuencyMail_hours($KEY_RELM_C);
                    $cont=count($ViewFrecuencyMail_hours);
                    if($cont>0){ 
                        for($x=0;$x<$cont;$x++){
                            $ID=$x+1;
                            $Hour= $ViewFrecuencyMail_hours[$x]["Hours"];
                            $Botones=   '<div class="btn-group" role="group">'.
                                            '<button type="button" class="btn btn-sm btn-dark mdlScheduleMail_tbHourList_remover" data-id='.$ID.'>'. 
                                                '<i class="fas fa-eraser"></i>'.
                                            '</button>'.
                                        '</div>';       
                            $data->horas[$x]=array($ID , $Hour,$Botones); 
                        }
                    }
                    else{
                        $data->horas[0]=array('No data','',''); 
                    }
                    $data->filtros=$ViewFrecuencyMail_filters;
                    $data->dias=$ViewFrecuencyMail_days;
                    return $data; 
                }
            }
            else{
                return 'ok';
            }
            
        }
        //Reporte en  proceso
        static public function ctrSendMail($KEY_RELC_M,$Stp,$ReporteNombre){
            //Si tiene  importador  se  queda  asi, si no tiene lo agrego como  filtro.
            //Solo aquellos reporte de  LCHB Se puedes  crear con filtros de  un a  muchos  importadpres.
            //En base al ID del  reporte  se  genera el cuerpo del Perfil y los contactos
            //Calcular los filtros
            //Si  el  reporte  ocupa el nombre de importador, se debe  guardar como filtro.
            $datos= array();
            $cont=0;
            $mailFilters=ModeloadReportes::MdlSendMail_FilterValues($KEY_RELC_M);
            if(count($mailFilters)>0){
                $mailProfile=ModeloadReportes::MdlSendMail_Profile($KEY_RELC_M);
                $mailContact=ModeloadReportes::MdlSendMail_Contact($KEY_RELC_M);
                foreach($mailFilters as $key){
                    switch($key[0]){
                        case 'RangoFecha':
                            $datos[$cont]=date("Y/m/d", strtotime("-".$key[1]." DAYS"));
                            $cont++;
                            $datos[$cont]=date('Y/m/d');
                            break;
                        case 'YY':
                            $datos[$cont]=date("Y", strtotime("-".$key[1]." YEAR"));
                            break;
                        default:
                            $datos[$cont]=$key[1];
                            break;
                    }
                    $cont++;
                }
                $ExcelFile=ControladoradReportes::ctrGenerarReporte($datos,$Stp,$ReporteNombre);
                //var_dump($ExcelFile);
                if($ExcelFile->Code=="OK"){
                    $data = new stdClass();
                    $EnvioCorreo=SendMail($mailProfile,$mailContact,$ExcelFile->Excel,$ReporteNombre);
                    if($EnvioCorreo=="OK"){
                        $data->Code='OK';
                        return $data;
                    }
                    else{
                        $data->Code='Error';
                        $data->MSG=$EnvioCorreo;
                        return $data;
                    }
                }
                else{
                    return $ExcelFile;
                }
            }
            else{
                $data = new stdClass();
                $data->Code='Warning';
                $data->MSG="No se puede enviar el correo. Establezca los valores de los filtros en la seccion de  frecuencia.";
                return $data;
            }
            
            
        }
    }