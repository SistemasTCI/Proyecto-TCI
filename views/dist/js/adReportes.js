//Variables  Globales del Sistema
var ReporteNombre='';
var B_ReporteNombre_SinFiltro=0;
var Stp='';
var Filtros= [];
var Contenedor_Filtros="";
var NewContact=[];
var bandera_contactos=0; 
//Fecha
var currentDate = new Date();
var day = currentDate.getDate();
var month = currentDate.getMonth();
var year = currentDate.getFullYear();

//Variables Frecuency
//Las  variable sde  Json pueden  eliminarse, se  tiene que ajustar el proceso para la limpieza del array lista horas tabla. 
var objListaHoras=[];
var objListaHorasTabla=[];
var jsonListaHoras=[];
var contListaHoras=1;
var objListaDias=[];

/////////////////////////////////
function adReportes_js_ajax(datos,funcion,sweet){
    $.ajax({
        url:"ajax/adReportes.ajax.php",
        type:"POST",
        beforeSend : function(){
            if(sweet!=''){
                window[sweet]();
            }
        },
        data:datos,
        cache:false,
        contentType:false,
        processData:false,
        dataType:"json",
        success:function(datos){
            Swal.close();
            if(typeof datos!==undefined){
                if(funcion!=''){
                    window[funcion](datos);
                } 
            }
            else{
                console.log('Error:'+datos);
                alert(datos);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
           console.log('text status=:' + textStatus + ', error thrown:=' +  errorThrown);
           console.log(XMLHttpRequest.responseText);
        //error: function(datos,errorThrown){
            //console.log("AJAX Error datos:: ",datos); 
            //console.log("AJAX Error:: ",errorThrown);  
        }
    });
}
function adReportes_SwalWait(){
    Swal.fire({
        //target: document.getElementById('form-modal'),
        title: 'Procesando!',
        html: 'Espere, se  estan cargando los datos!',
        allowEscapeKey: false,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading()
        },
    });
}
function adReportes_js_InicializarResultado(datos){
    if(datos!=""){
        $('#adReportes_tblReportes').DataTable({
          "destroy":true,
          "data":datos['Reportes'],
          "responsive": true,
          "select":{style:'single'},
          "searching":      true,
          "paging":         true,
        });
    }
    else{
          console.log('Eror:'+datos);
          alert(datos);
    }
}

function adReportes_js_Inicializar(Modulo){
    var datos= new FormData();
    console.log(Modulo);
    datos.append("Inicializar",'true');
    datos.append("Modulo",Modulo);
    funcion= 'adReportes_js_InicializarResultado';
    sweet='';
    adReportes_js_ajax(datos,funcion,sweet);
}

///////////////////////////
//sE  ESTA  REALIZANDO AJUSTE A LA  OPCION DE  FILTROS,  SE  DEBEN  CREAR LOS  FILTROS EN LOS  CONTENEDORES DE  FORMA  DINAMICA
///////////////////////////
function adReportes_ajax_FiltrosDescarga(resultado){
    var cont = Object.keys(resultado).length;
    if(cont>0){
        for(i=0;i<cont;i++){
            //Filtros.push(resultado[i]['TipoFiltro'],resultado[i]['ID_Filtro']);
            Filtro=[];
            Filtro.push(resultado[i]['TipoFiltro'],resultado[i]['Etiqueta'],resultado[i]['IDREPFIL']);
            Filtros.push(Filtro);
            switch(resultado[i]['TipoFiltro']){
                case 'RangoFecha':
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                                '<div class="col-3">'+
                                    '<label>'+resultado[i]['Etiqueta']+'</label>'+ 
                                '</div>'+
                                '<div class="col-4">'+
                                    '<input type="date" value = "'+month+'-01-'+year+'"  class="form-control form-control-sm" name="adReportes_I'+resultado[i]['IDREPFIL']+'"  id="adReportes_I'+resultado[i]['IDREPFIL']+'"  data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false" onkeypress="return event.keyCode != 13;">'+
                                '</div>'+
                                '<div class="col-1">'+
                                    '<label> To</label>'+
                                '</div>'+
                                '<div class="col-4">'+
                                    '<input type="date" value = "'+month+'-'+day+'-'+year+'" class="form-control form-control-sm"  name="adReportes_F'+resultado[i]['IDREPFIL']+'"  id="adReportes_F'+resultado[i]['IDREPFIL']+'"  data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false" onkeypress="return event.keyCode != 13;">'+
                                '</div>'+
                        '</div>');
                    break;
                case 'Cliente':
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                            '<div class="form-group col-12">'+
                                '<label for="adReportes_slcImporter">'+resultado[i]['Etiqueta']+'</label>'+ 
                                '<select class="form-control" id="adReportes_slcImporter" onkeypress="return event.keyCode != 13;"></select>'+
                            '</div>'+ 
                        '</div>');
                    var datos= new FormData();
                    datos.append("ListadoClientes",'true');
                    funcion= 'adReportes_js_Clientes';
                    sweet='';
                    adReportes_js_ajax(datos,funcion,sweet);
                    break;
                case 'Numero':
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                            '<div class="form-group col-12">'+
                                '<label for="adReportes_number'+resultado[i]['IDREPFIL']+'" >'+resultado[i]['Etiqueta']+'</label>'+
                                '<input type="number" class="form-control input-sm" id="adReportes_'+resultado[i]['IDREPFIL']+'" value = "" min="1" onkeypress="return event.keyCode != 13;">'+
                            '</div>'+
                        '</div>');
                    break;
                case 'YY':
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                            '<div class="form-group col-12">'+
                                '<label for="adReportes_'+resultado[i]['IDREPFIL']+'" >'+resultado[i]['Etiqueta']+'</label>'+
                                '<input type="number" class="form-control input-sm" id="adReportes_'+resultado[i]['IDREPFIL']+'" value ="'+year+'" min="2015" max="2050" onkeypress="return event.keyCode != 13;">'+
                            '</div>'+
                        '</div>');
                    break;
                default:
                    //Dejar Texto Default
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                            '<div class="form-group col-12">'+
                                '<label for="adReportes_txt'+resultado[i]['IDREPFIL']+'" >'+resultado[i]['Etiqueta']+'</label>'+
                                '<input type="text" class="form-control input-sm" id="adReportes_'+resultado[i]['IDREPFIL']+'" value = "" onkeypress="return event.keyCode != 13;">'+
                            '</div>'+
                        '</div>');
            }
        }
        $('#adReportes_spReporteTitulo').html(ReporteNombre);
        $('#adReportes_mdlDescargarReporte').modal({backdrop: 'static', keyboard: false});
        $('#adReportes_mdlDescargarReporte').modal('show');
    }
    else{
        var datos= new FormData();
        datos.append("GenerarReporteSinFiltros",'true');
        datos.append("Stp",Stp);
        datos.append("ReporteNombre",ReporteNombre);
        funcion= 'adReportes_js_GenerarReporte';
        sweet='adReportes_SwalWait';
        adReportes_js_ajax(datos,funcion,sweet);
        B_ReporteNombre_SinFiltro=1;
        
    }
}

//A LOS FILTROS  AGREGAR AL FINAL EL FOLIO, Y  GUARDAR  ESTE COMO  ID DEL BOTON
function adReportes_FiltrosFrecuency(){
    console.log(Filtros,Contenedor_Filtros);
    var cont = Object.keys(Filtros).length;
    if(cont>0){
        for(i=0;i<cont;i++){
            console.log(Filtros[i][0]);
            switch( Filtros[i][0]){
                case 'RangoFecha':
                    console.log(Filtros,Contenedor_Filtros,'ENTRO');
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                                '<div class="form-group col-12">'+
                                    '<label>'+ Filtros[i][1]+' Range in days</label>'+ 
                                    '<input type="number" class="form-control input-sm" id="adReportes_'+ Filtros[i][2]+'" value = "" min="1" onkeypress="return event.keyCode != 13;">'+
                                '</div>'+
                        '</div>');
                    break;
                case 'Numero':
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                            '<div class="form-group col-12">'+
                                '<label for="adReportes_'+ Filtros[i][0]+'" >'+ Filtros[i][1]+' </label>'+
                                '<input type="number" class="form-control input-sm" id="adReportes_'+ Filtros[i][2]+'" value = "" min="1" onkeypress="return event.keyCode != 13;">'+
                            '</div>'+
                        '</div>');
                    break;
                case 'YY':
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                            '<div class="form-group col-12">'+
                                '<label for="adReportes_'+ Filtros[i][0]+'" >'+ Filtros[i][1]+' Range in years</label>'+
                                '<input type="number" class="form-control input-sm" id="adReportes_'+ Filtros[i][2]+'" value ="" min="1" max="10" onkeypress="return event.keyCode != 13;">'+
                            '</div>'+
                        '</div>');
                    break;
                default:
                    //Dejar Texto Default
                    $('#'+Contenedor_Filtros).append(
                        '<div class="row">'+
                            '<div class="form-group col-12">'+
                                '<label for="adReportes_txt'+ Filtros[i][0]+'" >'+ Filtros[i][1]+'</label>'+
                                '<input type="text" class="form-control input-sm" id="adReportes_'+ Filtros[i][2]+'" value = "" onkeypress="return event.keyCode != 13;">'+
                            '</div>'+
                        '</div>');
            }
        }
        //$('#adReportes_mdlFrecuencyMail').modal({backdrop: 'static', keyboard: false});
        //$('#adReportes_mdlFrecuencyMail').modal('show');
    }
}

function adReportes_js_Clientes(resultado){
    var cont=Object.keys(resultado).length;
    for(var i=0;i<cont;i++){
        var name=resultado[i]["NAME"];
        var key=resultado[i]["KEY"];
        $("#adReportes_slcImporter").append("<option value='"+key+"'>"+ name + "</option>");
    }
}
function adReportes_js_Fecha(){
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth()+1;
    var year = currentDate.getFullYear();
    return day+'-'+month+'-'+year;
}
function adReportes_js_GenerarReporte(resultado){
    if(resultado['Code']=='OK'){
        var $a = $("<a>");
        $a.attr("href",resultado["ToExcel"]);
        $("body").append($a);
        $a.attr("download",ReporteNombre +' '+ adReportes_js_Fecha() +".xlsx");
        $a[0].click();
        $a.remove();

        if(B_ReporteNombre_SinFiltro==1){
            adReportes_js_ResetVarReport();
            _ReporteNombre_SinFiltro=0;
        }
    }
    else if(resultado['Code']=='Vacio'){
        Swal.fire({
            title: "Info!",
            text: resultado['MSG'],
            icon: "info",
            confirmButtonText: "Cerrar"
            });
    }
    else{
        Swal.fire({
            title: "Danger!",
            text: resultado['MSG'],
            icon: "error",
            confirmButtonText: "Cerrar"
            });
    }
}
function adReportes_js_ResetVarReport(){
    ReporteNombre='';
    Stp='';
    Filtros= [];
    Contenedor_Filtros="";
    bandera_contactos=0; 
}
function adReportes_ListadoImportadores(datos){
    if(datos!=""){
        var cont=Object.keys(datos).length;
        console.log(datos);
        for(var i=0;i<cont;i++){
            var ID=datos[i]["ID"];
            var IDABI=datos[i]["IDABI"];
            var NOMBRE=datos[i]["Nom_Importer"];
            $("#mdlScheduleMail_slcImportadores").append("<option value='"+ID+"' data-idabi='"+IDABI+"'>"+NOMBRE+"</option>");
        }
    }
    else{
        alert(datos);
    }
}
function adReportes_ajax_SaveMailInformation(datos){
    console.log("Creo el correo");
    if(datos=='ok'){
        //console.log("Creo el correo: Mensaje Success con wait 2");
        Swal.fire({
            title: 'Success!',
            html:'Correo creado con exito!',
            icon: 'success',
            //confirmButtonText: 'Ok',
            showConfirmButton: false,
            timer: 1000
            })
            .then(() => {
                console.log("Creo el correo:Entro a "+ $("#mdlScheduleMail_KEY_RELM_C").val());
               if($("#mdlScheduleMail_KEY_RELM_C").val()!=''){
                    console.log("Creo el correo: Validacion !=''");
                    KEY_RELM_C=$("#mdlScheduleMail_KEY_RELM_C").val();
                    Importador=$("#mdlScheduleMail_slcImportadores").text();
                    adReportes_resetmldMail();
                    adReportes_ViewmdlScheduleMail_UPData(KEY_RELM_C,Importador);
                    //ListaReportesAutomaticos($("#mdlListScheduleReport_ReportID").val());
                }
                else{
                    console.log("Creo el correo: Validacion =''");
                    adReportes_resetmldMail();
                    $('#adReportes_mdlScheduleMail').modal('hide');
                    //ListaReportesAutomaticos($("#mdlListScheduleReport_ReportID").val());
                }
                ListaReportesAutomaticos($("#mdlListScheduleReport_ReportID").val());
        });
        
    }
    else{
        Swal.fire({
        title: 'Danger!',
        html:'Error:'+datos,
        icon: 'error',
        confirmButtonText: 'Ok'
        });
    }
}
function adReportes_ajax_ListaReportesAutomaticos(datos){
    if(datos!=''){
        LCHB_FillTable('tbListScheduleReport',datos["ListaReportes"]);
        var cont = Object.keys(datos['FiltrosDescarga']).length;
        if(cont>0){
            //Inicializa los filtros
            Filtros=[];
            for(i=0;i<cont;i++){
                Filtro=[];
                Filtro.push(datos['FiltrosDescarga'][i]['TipoFiltro'],datos['FiltrosDescarga'][i]['ID_Filtro'],datos['FiltrosDescarga'][i]['IDREPFIL']);
                Filtros.push(Filtro);
            }
        }
        console.log(datos['FiltrosDescarga'],Filtros);
    }
    else{
        Swal.fire({
        title: 'Danger!',
        html:'Error:'+datos,
        icon: 'error',
        confirmButtonText: 'Ok'
        });
    }
}
function adReportes_filtros(Reporte){
    var datos= new FormData();
    datos.append("FiltrosDescarga",'true');
    datos.append("Reporte",Reporte);
    funcion= 'adReportes_ajax_FiltrosDescarga';
    sweet='adReportes_SwalWait';
    adReportes_js_ajax(datos,funcion,sweet);
}
function ListaReportesAutomaticos(IdReporte){
    var datos= new FormData();
    datos.append("ListaReportesAutomaticos",'true');
    datos.append("IdReporte",IdReporte);
    funcion= 'adReportes_ajax_ListaReportesAutomaticos';
    sweet='adReportes_SwalWait';
    adReportes_js_ajax(datos,funcion,sweet);
}
function adReportes_ajax_ScheduleMailUPData(datos){
    if(datos!=''){ 
        console.log(datos["ListaContactos"][0][0]);
        if(datos["ListaContactos"][0][0]!='No data'){
            bandera_contactos=1; 
        }
        else{
            bandera_contactos=0; 
        }
        console.log( bandera_contactos);
        LCHB_FillTable('mdlScheduleMail_tblListContacts',datos["ListaContactos"]);
        //$("#mdlScheduleMail_tblListContacts" ).css( "display","table" );   
        $('#mdlScheduleMail_txtEmailSubject').val(datos["Titulo"]);
        $('#mdlScheduleMail_txtEmailBody').val(datos["Cuerpo"]);
    }
    else{
        Swal.fire({
        title: 'Danger!',
        html:'Error:'+datos,
        icon: 'error',
        confirmButtonText: 'Ok'
        });
    }
}
function adReportes_resetmldMail(){
    //$("#mdlScheduleMail_slcImportadores").empty().attr("disabled","false");
    $("#mdlScheduleMail_slcImportadores").empty().append("<option value=''>Importador</option>").removeAttr("disabled");
    $("#mdlScheduleMail_KEY_RELM_C").val('');
    $("#mdlScheduleMail_txtEmailSubject").val('');
    $("#mdlScheduleMail_txtEmailBody").val('');
    $("#mdlScheduleMail_tblListContacts tr>td").remove();
    $("#mdlScheduleMail_tbl_importer_email_pendientes tr>td").remove();
    NewContact=[];
};
function adReportes_ViewmdlScheduleMail_UPData(KEY_RELM_C,nomimp){
    var datos= new FormData();
    datos.append("ScheduleMailUPData",'true');
    datos.append("KEY_RELM_C",KEY_RELM_C);
    funcion= 'adReportes_ajax_ScheduleMailUPData';
    sweet='';
    adReportes_js_ajax(datos,funcion,sweet);
    $("#mdlScheduleMail_slcImportadores").empty().append("<option value='' selected>"+ nomimp + "</option>").attr("disabled","true"); 
    $('#mdlScheduleMail_KEY_RELM_C').val(KEY_RELM_C);

    //Activa el boton de  Send, solo si existe un ID de  reporte.
    $("#mdlScheduleMail_btnsendmail").css( "display","block" ); 

}
function adReportes_ajax_DelSheduleMail(datos){
    if(datos=='ok'){
        Swal.fire({
            title: 'Success!',
            html:'Reporte eliminado!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        ListaReportesAutomaticos($("#mdlListScheduleReport_ReportID").val());
    }
    else{
        Swal.fire({
        title: 'Danger!',
        html:'Error:'+datos,
        icon: 'error',
        confirmButtonText: 'Ok'
        });
    }
}
function adReportes_ajax_DelContactMail(datos){
    if(datos=='ok'){
        Swal.fire({
            title: 'Success!',
            html:'Contacto eliminado!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        KEY_RELM_C=$("#mdlScheduleMail_KEY_RELM_C").val();
        Importador=$("#mdlScheduleMail_slcImportadores").text();
        adReportes_ViewmdlScheduleMail_UPData(KEY_RELM_C,Importador);
    }
    else{
        Swal.fire({
        title: 'Danger!',
        html:'Error:'+datos,
        icon: 'error',
        confirmButtonText: 'Ok'
        });
    }
}
function adReportes_AddHour(hora){
    if(hora!=""){
        objListaHoras.push([hora]);
        objListaHorasTabla.push([contListaHoras,hora,
                    '<div class="btn-group" role="group">'+
                        '<button type="button" class="btn btn-sm btn-dark mdlScheduleMail_tbHourList_remover" data-id='+contListaHoras+'>'+ 
                            '<i class="fas fa-eraser"></i>'+
                        '</button>'+
                    '</div>']
        );
        contListaHoras++;
        jsonListaHoras=[objListaHorasTabla];
        LCHB_FillTable("mdlFrecuencyMail_tbHourList",jsonListaHoras[0]);
        return "ok";
    }
    else{
        return "";
    }  
}
function adReportes_RemoveHour(ID){
    delete jsonListaHoras[0][ID];
    objListaHorasTabla=[];
    objListaHoras=[];
    contListaHoras=1;
    for(var key in jsonListaHoras[0]){
        if(jsonListaHoras[0][key][2]!=''){
            objListaHoras.push([jsonListaHoras[0][key][1]]);
            objListaHorasTabla.push([contListaHoras,jsonListaHoras[0][key][1],
                ' <div class="btn-group" role="group">'+
                    '<button type="button" class="btn btn-sm btn-dark mdlScheduleMail_tbHourList_remover" data-id='+contListaHoras+'>'+ 
                        '<i class="fas fa-eraser"></i>'+
                    '</button>'+
                '</div>'
            ]);
        }
        else{
            objListaHorasTabla.push([contListaHoras,jsonListaHoras[0][key][1],'']);
        }
        contListaHoras++;
    }
    jsonListaHoras=[objListaHorasTabla];
    console.log(jsonListaHoras,objListaHoras);
    LCHB_FillTable("mdlFrecuencyMail_tbHourList",jsonListaHoras[0]);
    return "ok";
}
function adReportes_ajax_SaveFrecuency(datos){
    if(datos=='ok'){
        Swal.fire({
            title: 'Success!',
            html:'Programacion de envios guardada!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    }
    else{
        Swal.fire({
        title: 'Danger!',
        html:'Error:'+datos,
        icon: 'error',
        confirmButtonText: 'Ok'
        });
    }
}
function adReportes_ajax_ViewFrecuencyMail(datos){
    console.log(datos);
    if(datos!='ok'){
        contListaFiltros=Object.keys(datos['filtros']).length;
        for(i=0;i<contListaFiltros;i++){
            $('#adReportes_'+datos['filtros'][i][0]).val(datos['filtros'][i][1]);
        }
        contListaHoras=Object.keys(datos['horas']).length;
        for(i=0;i<contListaHoras;i++){
            objListaHoras.push([datos['horas'][i][1]]);
        }
        contDias=Object.keys(datos['dias']).length;
        console.log(contDias);
        if(contDias>0){
            if(datos['dias'][0][1]=="WD"){
                for(i=0;i<contDias;i++){
                    $('#mdlScheduleMail_chk'+datos['dias'][i][2]).attr('checked',true)
                    objListaDias.push(datos['dias'][i][2]);
                }
            }
            else{
                //Agregar  datos del mes
            }
        }
        contListaHoras++;
        objListaHorasTabla=datos['horas'];
        jsonListaHoras=[objListaHorasTabla];
        LCHB_FillTable("mdlFrecuencyMail_tbHourList",jsonListaHoras[0]);
    }
}
function adReportes_ajax_SendMail(resultado){
    if(resultado['Code']=='OK'){
        Swal.fire({
            title: 'Success!',
            html:'Correo Enviado!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    }
    else if(resultado['Code']=='Vacio'){
        Swal.fire({
            title: "Info!",
            text: resultado['MSG'],
            icon: "info",
            confirmButtonText: "Cerrar"
            });
    }
    else if(resultado['Code']=='Warning'){
        Swal.fire({
            title: "Warning!",
            text: resultado['MSG'],
            icon: "warning",
            confirmButtonText: "Cerrar"
            });
    }
    else{
        Swal.fire({
            title: "Danger!",
            text: resultado['MSG'],
            icon: "error",
            confirmButtonText: "Cerrar"
            });
    }  
}

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    //Prevent  enter
   /* $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });*/

    $('#adReportes_tblReportes').on('click','.adReportes_ViewmdlDescargarReporte',function(){
        ReporteNombre=$(this).data('nombre');
        Stp=$(this).data('stp');
        Contenedor_Filtros='adReportes_frmDescargarReportes';
        adReportes_filtros($(this).data('reporte'));
    });
    $('#adReportes_tblReportes').on('click','.adReportes_ViewmdlListScheduleReport',function(){
        $('#mdlListScheduleReport_ReportName').text($(this).data('nombre'));
        //Solo se  emnciona en las  lineas 475 al 487, se cambiar por una  variable de entorno.
        $('#mdlListScheduleReport_ReportID').val($(this).data('reporte'));

        ReporteNombre=$(this).data('nombre');
        Stp=$(this).data('stp');
        Contenedor_Filtros='adReportes_frmFilterRules';

        ListaReportesAutomaticos($(this).data('reporte'));
        $('#adReportes_mdlListScheduleReport').modal({backdrop: 'static', keyboard: false});
        $('#adReportes_mdlListScheduleReport').modal('show');
    });
    $('.adReportes_btnResetmldDescargar').click(function(){
        $('#adReportes_mdlDescargarReporte').find('form').find('div').remove();
        adReportes_js_ResetVarReport();
    });
    $('.adReportes_btnResetmldListScheduleReport').click(function(){
        adReportes_js_ResetVarReport();
    });
    $('.adReportes_btnResetmldmdlFrecuencyMail').click(function(){
        console.log("Entro al a borrar");
        objListaHoras=[];
        objListaHorasTabla=[];
        jsonListaHoras=[];
        contListaHoras=1;
        objListaDias=[];
        for(i=0;i<7;i++){
            console.log("Entro al a borrar");
            $('#mdlScheduleMail_chk'+i).removeAttr('checked');
        }
        console.log("Entro al a borrar2");
        $('#adReportes_frmFilterRules').find('div').remove();
        console.log("Entro al a borrar3");
        $("#mdlFrecuencyMail_tbHourList tr>td").remove();
        console.log("Entro al a borrar4");
        $('#mdlScheduleMail_FrecuencyMail').find('form').trigger('reset');
        console.log("Entro al a borrar5");
        $("#mdlScheduleMail_KEY_RELM_C").val('');
        console.log("Entro al a borrar6");
    });

    //Cuando se  descarga  no es  necesario  especificar los tipos de  datos en los campos,  JS y PHP los  interpretan como  texto a MYSQL.
    $('.adReportes_btnDescargar').click(function(){
        //var data_impNew= {};
        var valores= [];
        var bandera=0;
        var cont = Object.keys(Filtros).length;
        console.log(Filtros);
        for(i=0;i<cont;i++){
            switch(Filtros[i][0]){
                case 'RangoFecha':
                    if($('#adReportes_I'+Filtros[i][2]).val()!='' || $('#adReportes_F'+Filtros[i][2]).val()!='' ){
                        valores.push($('#adReportes_I'+Filtros[i][2]).val());
                        valores.push($('#adReportes_F'+Filtros[i][2]).val());
                    }
                    else{
                        bandera=1;
                    }
                    break;
                case 'Cliente':
                    var selected=$("#adReportes_slcImporter").find("option:selected");
                    if(selected.val()!=''){
                        valores.push(selected.val());
                    }
                    else{
                        bandera=1;
                    }
                    
                    break;
                default:
                    if($('#adReportes_'+Filtros[i][2]).val()!=''){
                        valores.push($('#adReportes_'+Filtros[i][2]).val());
                    }
                    else{
                        bandera=1;
                    }
            }
        }
        if(bandera==1)
        {
            Swal.fire({
                title: "Warnign!",
                text: "Debe  llenar todos los campos. ",
                icon: "warning",
                confirmButtonText: "Cerrar"
                });
        }
        else{
           //data_impNew.data=data;
            var datos= new FormData();
            datos.append("GenerarReporte",'true');
            var json = JSON.stringify(valores);
            datos.append("Datos",json);
            console.log(Stp);
            datos.append("Stp",Stp);
            datos.append("ReporteNombre",ReporteNombre);
            funcion= 'adReportes_js_GenerarReporte';
            sweet='adReportes_SwalWait';
            adReportes_js_ajax(datos,funcion,sweet); 
        }
    });

    
    $('.adReportes_ViewmdlScheduleMail').click(function(){
        var datos= new FormData();
        datos.append("ListadoImportadores",'true');
        funcion= 'adReportes_ListadoImportadores';
        sweet='';
        adReportes_js_ajax(datos,funcion,sweet);
        //$("#mdlScheduleMail_tblListContacts" ).css( "display","none" );  
         //Oculta el boton de  enviar  correo
         $("#mdlScheduleMail_btnsendmail").css( "display","none" ); 

        $('#adReportes_mdlScheduleMail').modal({backdrop: 'static', keyboard: false});
        $('#adReportes_mdlScheduleMail').modal('show');
    });
    $('#mdlScheduleMail_btnMailAdd').click(function(){
        var email = $('#mdlScheduleMail_txtMailAdd').val();
        if(email!=""){
            var contact= new Object();
            var type = $('#mdlScheduleMail_slc_type_add').val();
            var type_name = $('#mdlScheduleMail_slc_type_add option:selected').attr("name");
            
            $('#mdlScheduleMail_tbl_importer_email_pendientes tr:last').after('<tr class="table-info"><td>New</td><td>'+type_name+'</td><td>'+email+'</td></tr>');
            $('#mdlScheduleMail_txtMailAdd').val('');
            $('#mdlScheduleMail_txtMailAdd').focus();
            contact["type"]=type;
            contact["email"]=email;
            NewContact.push(contact);
            //console.log(NewContact);
        }
        else{
            Swal.fire({
                title: "Warnign!",
                text: "Debe escribir un correo. ",
                icon: "warning",
                confirmButtonText: "Cerrar"
                });
        }
        
    });
    $('.adReportes_SaveMailInformation').click(function(){
    //console.log($("#mdlScheduleMail_slcImportadores").find("option:selected").text());
        if($('#mdlScheduleMail_txtEmailSubject').val()!="" && $("#mdlScheduleMail_slcImportadores").find("option:selected").text()!="Importador")
        {
            var data=new Object();
            console.log($("#mdlScheduleMail_KEY_RELM_C").val());
            data["KEY_RELM_C"]=$("#mdlScheduleMail_KEY_RELM_C").val();
            //data["Importador"]=$('#mdlScheduleMail_slcImportadores').val();
            data["Importador"]=$("#mdlScheduleMail_slcImportadores").find("option:selected").val();
            data["Reporte"]=$('#mdlListScheduleReport_ReportID').val();
            data["Subject"]=$('#mdlScheduleMail_txtEmailSubject').val();
            data["Body"]=$('#mdlScheduleMail_txtEmailBody').val();
            data["Contactos"]=NewContact;
            //console.log(NewContact);
            var json = JSON.stringify(data);
            var datos= new FormData();
            datos.append("datos",json);
            datos.append("SaveMailInformation",'true');
            if($("#mdlScheduleMail_KEY_RELM_C").val()==''){
                datos.append("SaveMailInformation",'New');
            }
            else{
                datos.append("SaveMailInformation",'Update');
            }
            funcion= 'adReportes_ajax_SaveMailInformation';
            sweet='adReportes_SwalWait';
            adReportes_js_ajax(datos,funcion,sweet);
        }
        else{
            Swal.fire({
                title: 'Warning!',
                html:'Seleccione el Importador y el Subject para el correo.',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
                });
        }
        
    });
    $('#tbListScheduleReport').on('click','.adReportes_ViewmdlScheduleMail_UPData',function(){
        adReportes_ViewmdlScheduleMail_UPData($(this).data('key'),$(this).data('nomimp'));
        $('#adReportes_mdlScheduleMail').modal({backdrop: 'static', keyboard: false});
        $('#adReportes_mdlScheduleMail').modal('show');
    });
    $(".adReportes_mdlScheduleMail").click(function(){
        adReportes_resetmldMail();
    });
    $('#tbListScheduleReport').on('click','.adReportes_DelSheduleMail',function(){
        Swal.fire({
            title: 'Desea eliminar este Reporte y sus contactos?',
            text: "Una  vez  aplicado el cambio no podra revertirse!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar reporte!'
          }).then((result) => {
            if (result.isConfirmed) {
                var datos= new FormData();
                datos.append("DelSheduleMail",'true');
                datos.append("KEY_RELM_C",$(this).data("key"));
                funcion= 'adReportes_ajax_DelSheduleMail';
                sweet='';
                adReportes_js_ajax(datos,funcion,sweet);
            }
        });
    });
    $('#mdlScheduleMail_tblListContacts').on('click','.adReportes_DelContactMail',function(){
        Swal.fire({
            title: 'Desea eliminar este contacto?',
            text: "Una  vez  aplicado el cambio no podra revertirse!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar contacto!'
          }).then((result) => {
            if (result.isConfirmed) {
                var datos= new FormData();
                datos.append("DelContactMail",'true');
                datos.append("IDMIC",$(this).data("idmic"));
                funcion= 'adReportes_ajax_DelContactMail';
                sweet='';
                adReportes_js_ajax(datos,funcion,sweet);
            }
        });
    });
    $('#tbListScheduleReport').on('click','.adReportes_ViewmdlFrecuencyMail',function(){
        $('#mdlScheduleMail_KEY_RELM_C').val($(this).data('key'));
        $('#mdlFrecuencyMail_hdd').html('Mail Scheduling for ' + $(this).data('nomimp'));
        //Construir botones
        adReportes_FiltrosFrecuency();
        //
        var datos= new FormData();
        datos.append("ViewFrecuencyMail",'true');
        datos.append("KEY_RELM_C",$(this).data("key"));
        funcion= 'adReportes_ajax_ViewFrecuencyMail';
        sweet='adReportes_SwalWait';
        adReportes_js_ajax(datos,funcion,sweet);
        $('#adReportes_mdlFrecuencyMail').modal({backdrop: 'static', keyboard: false});
        $('#adReportes_mdlFrecuencyMail').modal('show');
    });
    $("#mdlFrecuencyMail_btnTimeAdd").click(function(){
        var hora=$("#mdlFrecuencyMail_txthour").val();
        if( adReportes_AddHour(hora)=='ok'){
            $('#mdlFrecuencyMail_txthour').val('');
            $('#mdlFrecuencyMail_txthour').focus();
        }
        else{
            Swal.fire({
                title: 'Warning!',
                html:'Seleciona una hora!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
    });
/*
    $('#mdlFrecuencyMail_btnTimeAdd').click(function(){
        var HourList= new Object();
        var hour = $('#mdlFrecuencyMail_txthour').val();
        $('#mdlScheduleMail_tbHourList tr:last').after('<tr class="table-info"><td>New</td><td>'+hour+'</td></tr>');
        $('#mdlFrecuencyMail_txthour').val('');
        $('#mdlFrecuencyMail_txthour').focus();
        HourList["hour"]=hour;
        NewTimeList.push(contact);
    });*/
    
    $('#mdlFrecuencyMail_tbHourList').on('click','.mdlScheduleMail_tbHourList_remover',function(){
        var id =($(this).data('id')) - 1;
        if( adReportes_RemoveHour(id)=='ok'){
            $('#mdlFrecuencyMail_txthour').focus();
        }
        else{
            Swal.fire({
                title: 'Warning!',
                html:'Error al eliminar la Hora!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }

    });
    $(".mdlFrecuencyMail_dias").change(function(){
        if($(this).is(':checked')){
            objListaDias.push([$(this).val()]);
        }
        else{
            console.log($(this).val());
            objListaDiasTmp=[];
            for(var key in objListaDias){
                if(objListaDias[key]!=$(this).val()){
                    //delete objListaDias[key];
                    objListaDiasTmp.push([objListaDias[key][0]]);
                }
            }
            objListaDias=objListaDiasTmp;
        }
    });
    $("#mdlFrecuencyMail_btnsave").click(function(){
        var KEY_RELM_C = $("#mdlScheduleMail_KEY_RELM_C").val();
        var datos= new FormData();
        var data=new Object();
        var valores= [];
        var bandera=0;
        var cont = Object.keys(Filtros).length;
        console.log(Filtros);
        for(i=0;i<cont;i++){
            //if(Filtros[i][0]!='Cliente'){
                if($('#adReportes_'+Filtros[i][2]).val()!='' ){
                    //Guarda el tipo de filtro, el valor asignado y el id del ocjeto contenedor
                    valores.push([Filtros[i][0],$('#adReportes_'+Filtros[i][2]).val(),Filtros[i][2]]);
                }
                else{
                    bandera=1;
                }
           // }
        }
        if($("#mdlScheduleMail_rdOptionWK").is(':checked')){
            data["Type"]="WD";
            if(Object.keys(objListaDias).length>0 && Object.keys(objListaHoras).length>0){
                data["Dias"]=objListaDias;
                data["Horas"]=objListaHoras;
            }
            else{
                bandera=1;
            }
        }
        else{
            data["Type"]="MD";
            if($("#mdlScheduleMail_MonthDay").val()!='' && $("#mdlScheduleMail_MonthDayHour").val()!=''){
                data["Dias"]=$("#mdlScheduleMail_MonthDay").val();
                data["Horas"]=$("#mdlScheduleMail_MonthDayHour").val();
            }
            else{
                bandera=1;
            }
        }

        if(bandera==1)
        {
            Swal.fire({
                title: "Warnign!",
                text: "Debe  llenar todos los campos. ",
                icon: "warning",
                confirmButtonText: "Cerrar"
                });
        }
        else{
            datos.append("SaveFrecuency",'true');
            datos.append("KEY_RELM_C",KEY_RELM_C);
            data["ValoresFiltros"]=valores;
            var json = JSON.stringify(data);
            datos.append("datos",json);
            funcion= 'adReportes_ajax_SaveFrecuency';
            sweet='adReportes_SwalWait';
            //sweet='';
            adReportes_js_ajax(datos,funcion,sweet);
        }
    });
    $(".mdlFrecuency_Options").change(function(){
        if($("#mdlScheduleMail_rdOptionWK").is(':checked')){
            $("#mdlScheduleMail_WKContainer *").prop("disabled", false);
            $("#mdlScheduleMail_ODContainer *").prop("disabled", true);
        }
        else{
            $("#mdlScheduleMail_WKContainer *").prop("disabled", true);
            $("#mdlScheduleMail_ODContainer *").prop("disabled", false);
        }
    });
    $(".adReportes_SendMail").click(function(){
        if(bandera_contactos!=0){
            var datos= new FormData();
            datos.append("SendMail",'true');
            datos.append("KEY_RELM_C",$("#mdlScheduleMail_KEY_RELM_C").val());
            datos.append("ReporteNombre",ReporteNombre);
            datos.append("Stp",Stp);
            funcion= 'adReportes_ajax_SendMail';
            sweet='adReportes_SwalWait';
            adReportes_js_ajax(datos,funcion,sweet);
        }
        else{
            Swal.fire({
                title: "Warnign!",
                text: "Agregue un correo a la lista de contactos. ",
                icon: "warning",
                confirmButtonText: "Cerrar"
                });
        }
        
    })
});