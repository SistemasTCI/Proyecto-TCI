//VARIABLES DE SISTEMA
var urlAjaxEdi = "ajax/inicio.ajax.php";               //Variable de URL para llamado ajax.
var TC = 0;                                           //Valor Tipo de Cambio Dolares a Pesos
var NewXML = [];
var SplitCaja = 0;
var SplitCount = 0;
var SplitFacturas = new Array();

var tabla = new Array();                            //Array para almacenar los  valores de una  caja, se  envian  al modal y al constructor para procesar el EDI

var CLI_ABI_KEY = '';                               //Clave ABI Key Cliente
var IMP_ABI_KEY = '';                               //Clave ABI Key Importador
var IMP_NAME = '';                                  //Clave ABI Key Importador
var CONS_ABI_KEY = '';                              //CLave ABI Key de Consignatario
var CONS_NAME = '';                                 //CLave ABI Key de Consignatario

var IDEDCLI = '';                                   //Clave ID de la tabla Cliente
var IDEDIMPCOS = '';                                //Clave ID de la tabla IMPORTADOR CONSIGNATARIO   

var CLI_PATH = '';                                  // Ruta de Archivos para los lcientes
var VistaPanel = 1;                                 //Bandera de  control para  visualizacion de panel principal. 1=Cajas Procesadas de procesar | 2= Entryes procesados por el sistema
var ProductosNuevos = 0;                            //Variable de calculo de productos Nuevos, se  usa al procesar el EDI para  Validacion

//var SCAC= new Array();                            //Lista de Scac de  ABI, se  llena al abrir el modulo. No fue  necesario
var MANUF = new Array();                             //Lista de Manufacturadores por cliente, se llena al llenar  panel principal.
var DATA_EDI = new Array();                           //Lista de las  cajas  en el panel principal, se  utiliza para la carga  masiva, el ultimo registro lleva el valor 0 que significa que no sea procesado hasta que valga 1

var B_Manifest = 0;                                   //Bandera Manifest, indica que opcion para ipresion de Mnifiestos selecciono el usuario.
var A_Manifest = new Array();                         //Almacena los Manifiestos en caso de  seleccionarlos por Entrys por Importador.
/////////////////////////////////////////////////////////////////////////

//Inicio de bloque revisado y actualizado 20220215
function js_inicioInicializar() {
    //Se debe retornar una tabla con los proveedores para ingresarlos al  select, de momento se procesan manual
    $("#inicio_mdlUpFiles_slcPanelProveedor").append("<option value='CLEVIT' data-provid='' data-ruta='C:\\XML_TCI\\AFTER\\'>After Market</option>");
    $("#inicio_mdlUpFiles_slcPanelProveedor").append("<option value='STAHL' data-provid='' data-ruta='C:\\XML_TCI\\STAHL\\'>STAHL</option>");
    $("#inicio_mdlUpFiles_slcPanelProveedor").append("<option value='MAHLE' data-provid='' data-ruta='C:\\XML_TCI\\MAHLE\\'>XAML MAHLE</option>");
    //$("#inicio_mdlUpFiles_slcPanelClientes").append("<option value='" + IDEDCLI + "' data-cliabikey='" + CLIABIKEY  + "' data-ruta='" + ruta + "'>" + Cliente + "</option>");

}

//funcion de ejemplo de ajax
function opEdi_js_MostrarFacturasCajas(idedimpcos, krelfc, Caja) {
    var datos = new FormData();
    datos.append("FillSplitCaja", 'true');
    datos.append("IDEDIMPCOS", idedimpcos);
    datos.append("krelfc", krelfc);
    datos.append("Caja", Caja);
    funcion = 'opEdi_js_FillSplitCaja';
    sweet = '';
    TCI_ajax(urlAjaxEdi, datos, funcion, sweet);
}
///////////////////////////////////////////////////////////////////////////

//convierte el excel para descarga desde el web
function js_opEdiGenerarEdi(datos_edi) {
    var datos = new FormData();
    datos.append("opEdiGenerarEdi", 'true');
    var json = JSON.stringify(datos_edi);
    datos.append("datos_edi", json);
    $.ajax({
        url: "ajax/opEdi.ajax.php",
        type: "POST",
        beforeSend: function () {
            Swal.fire({
                title: 'Procesando!',
                html: 'Espere, se  estan cargando los datos!!',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading()
                },
            });
        },
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        error: function (errorThrown) {
            Swal.close();
            console.log("Error:: ", errorThrown);
        }
    }).done(function (data) {
        Swal.close();
        if (data['op'] == "ok") {
            Swal.fire({
                title: 'Operacion Exitosa!',
                html: "El archivo se  descargara al cerrar la ventana.",
                icon: 'info',
                confirmButtonText: 'Ok'
            });
            js_Resetfrmedi();
            js_MostrarCajas(VistaPanel, 1);

            var $a = $("<a>");
            $a.attr("href", data.file);
            $("body").append($a);
            $a.attr("download", data.entry + ".EDI");
            $a[0].click();
            $a.remove();
        }
        else {
            Swal.fire({
                title: 'Warning!',
                html: data,
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
    });
}

//mdlUpFiles_slcPanelClientes
function ajax_CountFiles(datos) {
    if (datos != "") {
        $('#inicio_mdlUpFiles_XML').val(datos['xml']);
        $('#inicio_mdlUpFiles_PDF').val(datos['pdf']);
        $('#inicio_mdlUpFiles_XLS').val(datos['xls']);
        $('#inicio_mdlUpFiles_Path').val(datos['ruta']);
    }
    else {
        Swal.fire({
            title: 'Warning!',
            html: 'No hay Facturas para Procesar en la carpeta del Importador',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }
}
function adReportes_js_Fecha(){
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth()+1;
    var year = currentDate.getFullYear();
    return day+'-'+month+'-'+year;
}
function ajax_UpFiles(resultado) {
    if(resultado['Code']=='OK'){
        var $a = $("<a>");
        $a.attr("href",resultado["ToExcel"]);
        $("body").append($a);
        $a.attr("download",resultado["NombreReporte"] +' '+ adReportes_js_Fecha() +".xls");
        $a[0].click();
        $a.remove();
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

function ajax_UpFiles_test(datos) {
    if (typeof datos !== 'undefined') {
        if (typeof datos['No_XML'] == 'undefined' || datos['No_XML'] == 1) {
            Swal.fire({
                title: 'Warning!',
                html: 'No hay  Archivos XML para Procesar en la carpeta del Importador',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else if (typeof datos['No_PDF'] == 'undefined' || datos['No_PDF'] == 1) {
            Swal.fire({
                title: 'Warning!',
                html: 'No hay Facturas para Procesar en la carpeta del Importador',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else {
            Swal.fire({
                title: 'Proceso Concluido!',
                html: '<b>Facturas Cargadas:</b> ' + datos['Cargados'] + '<br>' +
                    '<b>PDFs Incorrectos:</b><br>' + datos['PDF_NoLayout'] + '<br>' +
                    '<b>XML sin Facturas:</b><br>' + datos['XMLSin_PDF'] + '<br>' +
                    '<b>XML Dañado:</b><br>' + datos['XML_NoValido'] + '<br>' +
                    '<b>XML Consignatario Nuevo:</b><br>' + datos['XML_Desconidos'] + '<br>' +
                    '<b>Facturas Duplicadas:</b><br>' + datos['Factura_Duplicada'] + '<br>' +
                    '<b>Errores de Carga:</b><br>' + datos['Error'] + '<br>',
                icon: 'info',
                confirmButtonText: 'Ok'
            });
            js_CountFilesEDIS();
            js_MostrarCajas(VistaPanel, 1);
        }
    }
    else {
        Swal.fire({
            title: 'Error!',
            html: datos,
            icon: 'danger',
            confirmButtonText: 'Ok'
        });
    }
}

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#inicio_mdlUpFiles_slcPanelProveedor').change(function () {
        var selected = $("#inicio_mdlUpFiles_slcPanelProveedor").find("option:selected");
        if (selected.text() != "SELECT AN OPTION") {
            console.log(selected.data("ruta"));
            var datos = new FormData();
            datos.append("CountUpFiles", "true");
            datos.append("ruta", selected.data("ruta"));
            funcion = 'ajax_CountFiles';
            sweet = '';
            console.log(urlAjaxEdi, datos, funcion, sweet);
            TCI_ajax(urlAjaxEdi, datos, funcion, sweet);
        }
        else {
            $('#inicio_mdlUpFiles_XML').val('0');
            $('#inicio_mdlUpFiles_PDF').val('0');
            $('#inicio_mdlUpFiles_XLS').val('0');
            $('#inicio_mdlUpFiles_Path').val('');
        }
    });

    $('#inicio_mdlUpFiles_btnUpFiles').click(function () {
        if ($('#inicio_mdlUpFiles_Path').val() == '') {
            Swal.fire({
                title: 'Warning!',
                text: 'Selecciona un Proveedor',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        /*else if($('#opEdi_mdlUpFiles_XML').val() == 0 && $('#opEdi_mdlUpFiles_PDF').val() == 0 && $('#opEdi_mdlUpFiles_XLS').val() == 0){
            Swal.fire({
                title: 'Warning!',
                text: 'No hay Archivos para  procesar.',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }*/
        else {
            var selected = $("#inicio_mdlUpFiles_slcPanelProveedor").find("option:selected");
            var datos = new FormData();
            console.log(selected.data('ruta'));
            datos.append("inicioUpFiles", "true");
            datos.append("IDEDCLI", selected.val());
            datos.append("ruta", selected.data("ruta"));
            datos.append("TC", TC);
            funcion = 'ajax_UpFiles';
            sweet = 'TCI_SwalWait';
            TCI_ajax(urlAjaxEdi, datos, funcion, sweet);
            //Limpieza de datos
            /*var select = document.getElementById("opEdi_mdlUpFiles_slcPanelClientes");
            select.selectedIndex = 0;
            $('#opEdi_mdlUpFiles_XML').val('');
            $('#opEdi_mdlUpFiles_PDF').val('');
            $('#opEdi_mdlUpFiles_XLS').val('');
            $('#opEdi_mdlUpFiles_Path').val('');*/
        }

    });

    $(".btnGenerarEdi").click(function () {
        if ($("#txtmldGenerarEdi_Caja").val() != "") {
            if (ProductosNuevos == 0) {
                var selected = $("#slcPanelClientes").find("option:selected");
                var Scac = document.getElementById('slcmldGenerarEdi_scac');
                var Manuf = document.getElementById('slcmldGenerarEdi_Manufactura');
                if (Scac.options[Scac.selectedIndex].value != '' && $("#txtmldGenerarEdi_Invoice").val() != '' && $("#txtmldGenerarEdi_Puerto").val() != '' && $("#txtmldGenerarEdi_Locacion").val() != '' && $("#txtmldGenerarEdi_Origen").val() != '' && Manuf.options[Manuf.selectedIndex].value != '') {
                    var datos_edi = new Object();
                    datos_edi["EDI_Entry"] = $("#mldGenerarEdi_Entry").html();
                    datos_edi["Invoice"] = $("#txtmldGenerarEdi_Invoice").val();
                    datos_edi["Cus_Ref"] = $("#mldGenerarEdi_cus_ref").val();
                    datos_edi["Date"] = $("#txtmldGenerarEdi_Date").val();
                    datos_edi["Total"] = $("#txtmldGenerarEdi_Total").val();
                    datos_edi["Importer"] = $("#txtmldGenerarEdi_Importer").data('claveimp');
                    datos_edi["InvAsBR"] = $("#txtmldGenerarEdi_Importer").data('invasbr');
                    datos_edi["Consigneer"] = $("#txtmldGenerarEdi_Consigneer").data('clavecons');
                    datos_edi["Customer"] = $("#txtmldGenerarEdi_Customer").val();
                    datos_edi["Referencia"] = $("#txtmldGenerarEdi_Referencia").val();
                    datos_edi["Scac"] = Scac.options[Scac.selectedIndex].value;
                    datos_edi["Caja"] = $("#txtmldGenerarEdi_Caja").val();
                    datos_edi["krelfc"] = $("#txtmldGenerarEdi_Caja").data('krelfc');
                    datos_edi["Puerto"] = $("#txtmldGenerarEdi_Puerto").val();
                    datos_edi["Location"] = $("#txtmldGenerarEdi_Locacion").val();
                    datos_edi["Peso"] = $("#txtmldGenerarEdi_Peso").val();
                    datos_edi["Origen"] = $("#txtmldGenerarEdi_Origen").val();
                    datos_edi["TIOP"] = $("#txtmldGenerarEdi_TIOP").val();
                    datos_edi["Manufactura"] = Manuf.options[Manuf.selectedIndex].value;
                    datos_edi["Bultos"] = $("#txtmldGenerarEdi_Bultos").val();
                    datos_edi["Medida"] = $("#txtmldGenerarEdi_Medida").val();
                    datos_edi["IDEDCLI"] = IDEDCLI;
                    datos_edi["IDIMPCOS"] = IDEDIMPCOS;
                    datos_edi["Ruta"] = selected.data("ruta");
                    datos_edi["Lineas"] = tabla;
                    Object.keys(datos_edi).forEach(function (key) {
                        if (datos_edi[key] === null || datos_edi[key] === undefined) {
                            datos_edi[key] = '';
                        }
                    });
                    js_opEdiGenerarEdi(datos_edi);
                    datos_edi = {};
                }
                else {
                    Swal.fire({
                        title: 'Warning!',
                        html: 'Llene todos los campos para poder  generar el EDI.',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }

            }
            else {
                Swal.fire({
                    title: 'Warning!',
                    html: 'La operacion contiene  productos  nuevos  sin Relacionar, revise los  productos he  intente  de  nuevo.',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            }
        }
        else {
            Swal.fire(
                'Warning!',
                'La operacion debe tener una caja  asignada,  regrese  al panel principal  y  asigne una  caja para continuar.',
                'warning'
            );
        }


    });
});

