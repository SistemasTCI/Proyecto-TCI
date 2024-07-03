//Variables  Globales del Sistema
var ReporteNombre='';
var B_ReporteNombre_SinFiltro=0;
var Stp='';
var Filtros= [];
var Contenedor_Filtros="";
var NewContact=[];
var bandera_contactos=0; 

var maxSimultaneousDownloads = 100; // Máximo número de descargas simultáneas
var currentDownloadCount = 0;

/////////////////////////////////
function adReportesPersonalizados_js_ajax(datos,funcion,sweet){
    console.log(datos,funcion,sweet);
    $.ajax({
        url:"ajax/adReportesPersonalizados.ajax.php",
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
function adReportesPersonalizados_js_ajax_simetric(datos,funcion,sweet){
    console.log(datos,funcion,sweet);
    $.ajax({
        url:"ajax/adReportesPersonalizados.ajax.php",
        type:"POST",
        beforeSend : function(){
            if(sweet!=''){
                window[sweet]();
            }
        },
        data:datos,
        async: false,
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
        error: function(datos,errorThrown){
            console.log('text status=:' + textStatus + ', error thrown:=' +  errorThrown);
            console.log(XMLHttpRequest.responseText);
            Swal.close();
            console.log("Error:: ",errorThrown);  
        }
    });
}
function adReportesPersonalizados_SwalWait(){
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
function adReportesPersonalizados_js_GenerarReportetest(resultado){
    console.log(resultado);
    if(resultado['Code']=='OK'){
        var cont = Object.keys(resultado['ToExcel']).length;
        for (var i = 0; i < cont; i++) {
            console.log(i);
            var $a = $("<a>");
            $a.attr("href",resultado["ToExcel"][i]);
            $("body").append($a);
            $a.attr("download",resultado["NombreReporte"][i][0] +' '+ adReportesPersonalizados_js_Fecha() +".xlsx");
            $a[0].click();
            $a.remove();
        }
        console.log("Proceso Concluido");

        return new Promise(function (resolve, reject) {
            var $a = $("<a>");
            $a.attr("href", url);
            $("body").append($a);
            $a.attr("download", filename + '.xlsx');
        
            $a.on('load', function () {
              $a.remove();
              resolve();
            });
        
            $a[0].click();
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
    else{
        Swal.fire({
            title: "Danger!",
            text: resultado['MSG'],
            icon: "error",
            confirmButtonText: "Cerrar"
            });
    }
}
function adReportesPersonalizados_js_ResetVarReport(){
    ReporteNombre='';
    Stp='';
    Filtros= [];
    Contenedor_Filtros="";
    bandera_contactos=0; 
}
/////////////////////////////////
//DEMOS
function adReportesPersonalizados_js_GenerarReportetest2(resultado){
    console.log(resultado);
    if(resultado['Code']=='OK'){
        var cont = Object.keys(resultado['Facturas']).length;
        for (var i = 0; i < cont; i++) {
            var datos= new FormData();
            //datos.append("datos",json);
            datos.append("FacturaMahle",'true');
            datos.append("Trafico",$('#mdlFacturaMahle_txtTrafico').val());
            console.log(datos);
            //funcion= 'adReportesPersonalizados_ajax_FacturaMahle';
            funcion= 'adReportesPersonalizados_js_GenerarReporte';
            sweet='adReportesPersonalizados_SwalWait';
            adReportesPersonalizados_js_ajax(datos,funcion,sweet);
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
function adReportesPersonalizados_js_GenerarReporteFacturatest3(resultado){
    console.log(resultado);
    if(resultado['Code']=='OK'){
        var cont = Object.keys(resultado['Facturas']).length;
        for (var i = 0; i < cont; i++) {
            console.log(i);
            var $a = $("<a>");
            $a.attr("href",resultado["ToExcel"][i]);
            $("body").append($a);
            $a.attr("download",resultado["NombreReporte"][i][0] +' '+ adReportesPersonalizados_js_Fecha() +".xlsx");
            $a[0].click();
            $a.remove();
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
function adReportesPersonalizados_js_GenerarReporte09292023(resultado) {
    console.log(resultado);
    if (resultado['Code'] === 'OK') {
        return new Promise(function (resolve, reject) {
            var maxSimultaneousDownloads = 100; // Máximo número de descargas simultáneas
            var currentDownloadCount = 0; // Contador de descargas actuales
            var downloadPromises = [];
    
            for (var i = 0; i < resultado['ToExcel'].length; i++) {
                console.log(resultado['ToExcel'].length);
                console.log(i);
                console.log(currentDownloadCount );
                console.log(downloadPromises);
                if (currentDownloadCount >= maxSimultaneousDownloads) {
                    break; // Alcanzamos el límite de descargas simultáneas
                }
        
                var url = resultado['ToExcel'][i];
                var nombre = resultado['NombreReporte'][i][0] + ' ' + adReportesPersonalizados_js_Fecha() + '.xlsx';
                console.log('Nombres:',nombre,url);
                downloadPromises.push(
                    downloadFile(url, nombre)
                    .then(function () {
                        console.log('COntador deDescargas:', currentDownloadCount);
                        currentDownloadCount--;
                    })
                );
                currentDownloadCount++;
            }
    
            console.log(downloadPromises);
            Promise.all(downloadPromises)
            .then(function () {
                console.log("Proceso Concluido");
                resolve();
            });
        });

    } else if (resultado['Code'] === 'Vacio') {
      Swal.fire({
        title: "Info!",
        text: resultado['MSG'],
        icon: "info",
        confirmButtonText: "Cerrar"
      });
      return Promise.resolve(); // Resuelve inmediatamente si no hay descargas
    } else {
      Swal.fire({
        title: "Danger!",
        text: resultado['MSG'],
        icon: "error",
        confirmButtonText: "Cerrar"
      });
      return Promise.reject(); // Rechaza inmediatamente si hay un error
    }
}

//  const JSZip = require('jszip'); // Importa la biblioteca JSZip
//const XLSX = require('xlsx'); // Importa la biblioteca XLSX
//const { saveAs } = require('file-saver'); // Importa file-saver.js para guardar archivos

/*function adReportesPersonalizados_js_GenerarReporte(resultado) {
    console.log(resultado);
    if (resultado['Code'] === 'OK') {
        var zip = new JSZip();
        var maxSimultaneousDownloads = 100; // Máximo número de descargas simultáneas
        var currentDownloadCount = 0; // Contador de descargas actuales

        for (var i = 0; i < resultado['ToExcel'].length; i++) {
            if (currentDownloadCount >= maxSimultaneousDownloads) {
                break; // Alcanzamos el límite de descargas simultáneas
            }

            var url = resultado['ToExcel'][i];
            var nombre = resultado['NombreReporte'][i][0] + ' ' + adReportesPersonalizados_js_Fecha() + '.xls';

            // Agregar el encabezado para una URL de datos Excel base64 desde PHP
            var dataURL = 'data:application/vnd.ms-excel;base64,' + url;

            // Agregar directamente el archivo Excel al zip
            zip.file(nombre, dataURL);
            currentDownloadCount++;
        }

        //zip.file("archivo.txt", "Contenido del archivo de ejemplo");
        zip.generateAsync({ type: "blob" }).then(function (blob) {
            saveAs(blob, "miarchivo.zip"); // Debes tener FileSaver.js para usar saveAs
        });
    } else if (resultado['Code'] === 'Vacio') {
        Swal.fire({
            title: "Info!",
            text: resultado['MSG'],
            icon: "info",
            confirmButtonText: "Cerrar"
        });
    } else {
        Swal.fire({
            title: "Danger!",
            text: resultado['MSG'],
            icon: "error",
            confirmButtonText: "Cerrar"
        });
    }
}*/

////////////////////
//FUNCIONES PARA DESCARGA DE PLANTILLA MAHLE
function adReportesPersonalizados_js_GenerarReporte(resultado) {
    console.log(resultado);
    if (resultado['Code'] === 'OK') {
        var zip = new JSZip();
        var maxSimultaneousDownloads = 100; // Máximo número de descargas simultáneas
        var currentDownloadCount = 0; // Contador de descargas actuales

        function downloadAndAddToZip(i) {
            if (i >= resultado['ToExcel'].length || currentDownloadCount >= maxSimultaneousDownloads) {
                // Todos los archivos han sido procesados o se ha alcanzado el límite de descargas simultáneas
                zip.generateAsync({ type: "blob" }).then(function (blob) {
                    saveAs(blob, resultado['Trafico']+".zip"); // Debes tener FileSaver.js para usar saveAs
                });
                return;
            }

            var url = resultado['ToExcel'][i];
            var nombre = resultado['NombreReporte'][i][0] + ' ' + adReportesPersonalizados_js_Fecha() + '.xlsx';

            // Realizar una solicitud HTTP para obtener el archivo Excel
            fetch(url)
                .then(response => response.arrayBuffer()) // Convertir la respuesta a un ArrayBuffer
                .then(data => {
                    // Agregar el archivo Excel al zip con el nombre correspondiente
                    zip.file(nombre, data);
                    currentDownloadCount++;

                    // Descargar el siguiente archivo
                    downloadAndAddToZip(i + 1);
                })
                .catch(error => {
                    console.error("Error al descargar archivo: " + error);
                    // Continuar con la descarga del siguiente archivo
                    downloadAndAddToZip(i + 1);
                });
        }

        // Comenzar la descarga y agregación de archivos al ZIP
        downloadAndAddToZip(0);
    } else if (resultado['Code'] === 'Vacio') {
        Swal.fire({
            title: "Info!",
            text: resultado['MSG'],
            icon: "info",
            confirmButtonText: "Cerrar"
        });
    } else {
        Swal.fire({
            title: "Danger!",
            text: resultado['MSG'],
            icon: "error",
            confirmButtonText: "Cerrar"
        });
    }
}
function adReportesPersonalizados_js_GenerarReporteCorreo(resultado) {
    if (resultado['Code'] === 'OK') {
        Swal.fire({
        title: "Correos Enviados",
        text: 'Envidos por correo.',
        icon: "success",
        confirmButtonText: "Cerrar"
      });
    } else if (resultado['Code'] === 'Vacio') {
      Swal.fire({
        title: "Info!",
        text: resultado['MSG'],
        icon: "info",
        confirmButtonText: "Cerrar"
      });
      return Promise.resolve(); // Resuelve inmediatamente si no hay descargas
    } else {
      Swal.fire({
        title: "Danger!",
        text: resultado['MSG'],
        icon: "error",
        confirmButtonText: "Cerrar"
      });
      return Promise.reject(); // Rechaza inmediatamente si hay un error
    }
}
function downloadFile(url, filename) {
    return new Promise(function (resolve, reject) {
        console.log('Entro a Descargar:',url,filename);
        var $a = $("<a>");
        $a.attr("href", url);
        $("body").append($a);
        $a.attr("download", filename);
    
        $a.on('load', function () {
            $a.remove();
            resolve();
        });
    
        $a[0].click();
    });
}
function adReportesPersonalizados_js_Fecha(){
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth()+1;
    var year = currentDate.getFullYear();
    return day+'-'+month+'-'+year;
}
function  adReportesPersonalizados_ajax_FacturaMahle(datos){
    if(datos=='ok'){
        Swal.fire({
            title: 'Success!',
            html:'Reporte eliminado!',
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
////////////////////
// FUNCIONES PARA  DESCARGA DE LAYOUT CARTA PORTE
function  adReportesPersonalizados_ajax_CartaPorte(datos){
    if(datos=='ok'){
        Swal.fire({
            title: 'Success!',
            html:'Reporte eliminado!',
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

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#adReportesPersonalizados_tblReportes').on('click','.adReportesPersonalizados_ViewmdlFacturaMahle',function(){
        $('#adReportesPersonalizados_FacturaMahle').modal({backdrop: 'static', keyboard: false});
        $('#adReportesPersonalizados_FacturaMahle').modal('show');
    });
    $('.adReportesPersonalizados_closemdlFacturaMahle').click(function(){
        $("#mdlFacturaMahle_txtTrafico").val('');
    });
    //Cuando se  descarga  no es  necesario  especificar los tipos de  datos en los campos,  JS y PHP los  interpretan como  texto a MYSQL.
    $('.adReportesPersonalizados_btnEnviarTrafico').click(function(){    
        if($('#mdlFacturaMahle_txtTrafico').val()!="" )
        {
            //var data=new Object();
            //data["Trafico"]=$('#mdlFacturaMahle_txtTrafico').val();
            //var json = JSON.stringify(data);

            var datos= new FormData();
            //datos.append("datos",json);
            datos.append("FacturaMahle",'true');
            datos.append("Trafico",$('#mdlFacturaMahle_txtTrafico').val());
            //funcion= 'adReportesPersonalizados_ajax_FacturaMahle';
            funcion= 'adReportesPersonalizados_js_GenerarReporte';
            sweet='adReportesPersonalizados_SwalWait';
            adReportesPersonalizados_js_ajax(datos,funcion,sweet);
        }
        else{
            Swal.fire({
                title: 'Warning!',
                html:'!Escriba el Trafico y el correo!',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
                });
        }
        
    });

    ////////////////////////////
    /*REPORTE MAHLE COMPONENTES*/
    $('#adReportesPersonalizados_tblReportes').on('click','.adReportesPersonalizados_ViewmdlFacturaMahleC',function(){
        $('#adReportesPersonalizados_FacturaMahleC').modal({backdrop: 'static', keyboard: false});
        $('#adReportesPersonalizados_FacturaMahleC').modal('show');
    });
    $('.adReportesPersonalizados_closemdlFacturaMahleC').click(function(){
        $("#mdlFacturaMahle_txtTraficoC").val('');
    });
    //Cuando se  descarga  no es  necesario  especificar los tipos de  datos en los campos,  JS y PHP los  interpretan como  texto a MYSQL.
    $('.adReportesPersonalizados_btnEnviarTraficoC').click(function(){    
        if($('#mdlFacturaMahle_txtTraficoC').val()!="" )
        {
            var datos= new FormData();
            datos.append("FacturaMahleC",'true');
            datos.append("Trafico",$('#mdlFacturaMahle_txtTraficoC').val());
            funcion= 'adReportesPersonalizados_js_GenerarReporte';
            sweet='adReportesPersonalizados_SwalWait';
            adReportesPersonalizados_js_ajax(datos,funcion,sweet);
        }
        else{
            Swal.fire({
                title: 'Warning!',
                html:'!Escriba el Trafico y el correo!',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
                });
        }
    });

    ////////////////////////////
    /* reporte de carta porte*/

    $('#adReportesPersonalizados_tblReportes').on('click','.adReportesPersonalizados_ViewmdlCartaPorte',function(){
        $('#adReportesPersonalizados_CartaPorte').modal({backdrop: 'static', keyboard: false});
        $('#adReportesPersonalizados_CartaPorte').modal('show');
    });
    $('.adReportesPersonalizados_closemdlCartaPorte').click(function(){
        $("#mdlCartaPorte_txtOrdenCarga").val('');
    });
    //Cuando se  descarga  no es  necesario  especificar los tipos de  datos en los campos,  JS y PHP los  interpretan como  texto a MYSQL.
    $('.adReportesPersonalizados_btnEnviarCartaPorte').click(function(){    
        if($('#mdlCartaPorte_txtOrdenCarga').val()!="" )
        {
            var datos= new FormData();
            datos.append("CartaPorte",'true');
            datos.append("OrdenCarga",$('#mdlCartaPorte_txtOrdenCarga').val());
            funcion= 'adReportesPersonalizados_js_GenerarReporte';
            sweet='adReportesPersonalizados_SwalWait';
            adReportesPersonalizados_js_ajax(datos,funcion,sweet);
        }
        else{
            Swal.fire({
                title: 'Warning!',
                html:'!Escriba la orden de carga!',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
                });
        }
        
    });
   
});