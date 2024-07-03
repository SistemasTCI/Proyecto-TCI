//VARIABLES DE SISTEMA
var urlAjaxEdi="ajax/opEdi.ajax.php";               //Variable de URL para llamado ajax.
var TC=0;                                           //Valor Tipo de Cambio Dolares a Pesos
var NewXML=[];
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
var MANUF= new Array();                             //Lista de Manufacturadores por cliente, se llena al llenar  panel principal.
var DATA_EDI=new Array();                           //Lista de las  cajas  en el panel principal, se  utiliza para la carga  masiva, el ultimo registro lleva el valor 0 que significa que no sea procesado hasta que valga 1

var B_Manifest=0;                                   //Bandera Manifest, indica que opcion para ipresion de Mnifiestos selecciono el usuario.
var A_Manifest=new Array();                         //Almacena los Manifiestos en caso de  seleccionarlos por Entrys por Importador.
/////////////////////////////////////////////////////////////////////////

//Inicio de bloque revisado y actualizado 20220215
function js_opEdiInicializar() {
    var datos = new FormData();
    datos.append("Inicializar", 'true');
    $.ajax({
        url: "ajax/opEdi.ajax.php",
        type: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (datos) {
            if (datos['clientes'] != "") {
                var cont = Object.keys(datos['clientes']).length;
                for (var i = 0; i < cont; i++) {
                    //tRAER  AQUI EL  KEY ABI DEL CLIENTE
                    var CLIABIKEY = datos['clientes'][i]["ABI_KEY"];
                    var IDEDCLI = datos['clientes'][i]["IDEDCLI"];
                    var Cliente = datos['clientes'][i]["Nombre"];
                    //var puerto = datos['clientes'][i]["puerto"];
                    //var location = datos['clientes'][i]["location"];
                    //var origen = datos['clientes'][i]["origen"];
                    //var tiop = datos['clientes'][i]["tiop"];
                    var ruta = datos['clientes'][i]["ruta"];

                    //Inicializacion de Select con los  valores de la BD
                    //Depurar, algunos datos  ya no se utilizan
                    $("#slcPanelClientes").append("<option value='" + IDEDCLI + "' data-cliabikey='" + CLIABIKEY  + "' data-ruta='" + ruta + "'>" + Cliente + "</option>");
                    $("#opEdi_mdlUpFiles_slcPanelClientes").append("<option value='" + IDEDCLI + "' data-cliabikey='" + CLIABIKEY  + "' data-ruta='" + ruta + "'>" + Cliente + "</option>");
                    $("#opEdi_mdlCreateImporters_slcClientes").append("<option value='" + IDEDCLI + "'>" + Cliente + "</option>");
                    $("#opEdi_mdlPrintManifest_slcImporter").append("<option value='" + IDEDCLI + "'>" + Cliente + "</option>");
                    VistaPanel = 1;
                }
                /*Llena la lista de Scac con la  variable  Global SCAC */
                /*Se llena una  sola  vez en todo el codigo alinciializar el modulo. */
                //SCAC=datos['scac'];
                
                var cont = Object.keys(datos['scac']).length;
                for (var i = 0; i < cont; i++) {
                    var CODE = datos['scac'][i]["CODE"];
                    var NAME = datos['scac'][i]["NAME"];
                    var SCAC = CODE + " - " + NAME;
                    $("#slcmldGenerarEdi_scac").append("<option value='" + CODE + "'>" + SCAC + "</option>");
                }
                //console.log(datos['TC']);
                if(datos['TC']=="NoTCPesos"){
                    opedis_js_TC();
                }
                else{
                    TC=datos['TC'][0];
                }
                //let btnPopulate = document.querySelector('button');
                /*let select = document.querySelector('slcmldGenerarEdi_scac');
                let fruits  = datos['scac'];
               
                let options = fruits.map(fruit => `<option value=${fruit.toLowerCase()}>${fruit}</option>`).join('\n');
                select.innerHTML = options;*/

            }
            else {
                alert(datos);
            }
        },
        error: function (datos, errorThrown) {
            console.log("Error:: ", datos);
        }
    });
}
function opedis_js_TC(){
    Swal.fire({
        title: 'Ingresar Tipo de Cambio en Pesos.',
        text: "El tipo de  cambio  no ha  sido  ingresado el dia de hoy, desea ingresarlo?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, dar alta!'
    }).then((result) => {
        if (result.isConfirmed) {
            opedis_js_MostrarAddExchRate();
        }
    })
}
function opedis_js_MostrarAddExchRate(){
    $('#mldAddTC').modal({ backdrop: 'static', keyboard: false });
    $('#mldAddTC').modal('show');
}
//Fin de bloque revisado y actualizado
//Inicio de bloque revisado y actualizado 20220216
function js_MostrarCajas(VistaPanel, MSG) {
    var datos = new FormData();
    var Cliente = document.getElementById('slcPanelClientes');
    /* Inicializar  variables con respecto al Cliente seleccionado*/
    CLI_PATH =  Cliente.options[Cliente.selectedIndex].dataset.ruta;
    IDEDCLI = Cliente.options[Cliente.selectedIndex].value;
    CLI_ABI_KEY = Cliente.options[Cliente.selectedIndex].dataset.cliabikey;

    datos.append("MostrarCajas", "1");
    datos.append("IDEDCLI", IDEDCLI);
    datos.append("VistaPanel", VistaPanel);
    $.ajax({
        url: "ajax/opEdi.ajax.php",
        type: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (datos) {
            if (typeof datos !== 'string') {
                /* Popula el array de de cajas por procesar para carga masiva.*/
                if (typeof datos['DATA_EDI'] != 'undefined' ){
                    DATA_EDI=null;
                    DATA_EDI=datos['DATA_EDI'];
                }
                /* Popula el array de  Manufacturadores por  cliente.*/
                MANUF=null;
                MANUF=datos['MANUF'];
                //Inicializa la barra de botones y textos informativos delpanel del lciente seleccionado
                var group = document.getElementById("opEdi_dashboardgroup");
                group.style.visibility = 'visible';
                var ViewBoxPanel = document.getElementById("opEdi_optionsViewBoxPanel");
                ViewBoxPanel.style.visibility = 'visible';
                var folios = datos['Folios'];
                $('#opEdi_Folios').val('Folios: ' + folios);
                $('#opEdi_RangoIni').val('Last Entry: ' + datos['EntRangoInicio']);
                $('#opEdi_RangoFin').val('Top Entry: ' + datos['EntRangoFin']);
                $('#opEdi_Card_hClientName').html(Cliente.options[Cliente.selectedIndex].text);
                //Finaliza la barra de botones y textos informativos delpanel del lciente seleccionado
                /**********************************************/
                //Observaciones del Importador, agregar  aun boton tipo info
                /********************************************* */
                $('#opEdi_Card_Observaciones').html(datos['Observaciones']);
                var ViewButton = document.getElementById("opEdi_btnProcess");
                var ViewSelect= document.getElementById("slcProcessTaskOfOperations");
                if (VistaPanel == 1) {
                    ViewSelect.style.visibility = 'visible';
                    ViewButton.style.visibility = 'visible';
                    ViewSelect.innerHTML = '';
                    ViewSelect.options[ViewSelect.length]=new Option("Select a Task",'0');
                    ViewSelect.options[ViewSelect.length]=new Option("Process Selected Operations",'1');
                    ViewSelect.options[ViewSelect.length]=new Option("Process All Operations",'2');
                    ViewSelect.options[ViewSelect.length]=new Option("Delete Selected Operations",'3');
                    ViewSelect.options[ViewSelect.length]=new Option("Delete All Operations",'4');
                    var v_thEntry = false;
                    var v_thCat = true;
                }
                else {
                    ViewSelect.innerHTML = '';
                    ViewSelect.options[ViewSelect.length]=new Option("Select a Task",'0');
                    ViewSelect.options[ViewSelect.length]=new Option("Delete Selected Operations",'3');
                    ViewSelect.options[ViewSelect.length]=new Option("Print Manifest",'5');
                    var v_thEntry = true;
                    var v_thCat = false;
                }
                //Llenado de  tabla principal,
                $('#tbCajas').DataTable({
                    "destroy": true,
                    "data": datos['data'],
                    "responsive": true,
                    "columnDefs": [
                        {
                            "targets": [1],
                            "visible": v_thEntry,
                            "searchable": true
                        },
                        {
                            "targets": [9],
                            "visible": v_thCat,
                            "searchable": false
                        }]
                });
                //Cual es la funcion de MSG?
                if (datos['data'][0][0] == 'No data' && MSG == 0) {
                    Swal.fire({
                        title: 'Info!',
                        html: 'No hay operaciones para mostrar.',
                        icon: 'info',
                        confirmButtonText: 'Ok'
                    });
                }
                if (folios < 20) {
                    Swal.fire({
                        title: 'Warning!',
                        html: 'Solo  restan ' + folios + ' entrys, incremente el  rango de  Entrys o consulte al Administrador del Sistema.',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
            }
            else {
                Swal.fire({
                    title: 'Warning!',
                    html: datos,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            }
        },
        error: function (datos, errorThrown) {
            console.log(datos);
        }
    });
}
//Fin de bloque revisado y actualizado
//Inicio de bloque en revision
function js_opEdiFillEDI(caja,krelfc,tcat) {
    var datos = new FormData();
    datos.append("opEdiFillEDI", "1");
    datos.append("caja", caja);
    datos.append("krelfc", krelfc);
    datos.append("TipoCat",tcat);
    //Revisar  si es necesario la  clave ID del registro en la tabla
    //Inicio Bloque de Variables Globales
    datos.append("IDEDIMPCOS", IDEDIMPCOS);
    datos.append("IMP_ABI_KEY", IMP_ABI_KEY);
    datos.append("CONS_ABI_KEY", CONS_ABI_KEY);
    datos.append("VistaPanel", VistaPanel);
    //Fin Bloque de Variables Globales
    
    $.ajax({
        url: "ajax/opEdi.ajax.php",
        type: "POST",
        beforeSend: function () {
            Swal.fire({
                title: 'Procesando!',
                html: 'Espere, se  estan cargando los datos!',
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
        success: function (datos) {
            Swal.close();
            if (datos != "") {
                tabla = datos['data'];
                $('#tblopEdiProcesado').DataTable({
                    "destroy": true,
                    "data": datos['data'],
                    "responsive": true,
                });
                $('#slcmldGenerarEdi_scac option[value="' + datos['OldScac'] + '"]').prop('selected', true);
                $("#slcmldGenerarEdi_Manufactura").append(MANUF[IMP_ABI_KEY]);
                $('#slcmldGenerarEdi_Manufactura option[value="' + datos['OldManuf'] + '"]').prop('selected', true);
                $('#mldGenerarEdi_Entry').html(datos['ENTRY']);
                $("#txtmldGenerarEdi_Date").val(datos['fecha']);
                $("#txtmldGenerarEdi_Invoice").val(datos['factura']);
                $("#mldGenerarEdi_cus_ref").val(datos['cus_ref']);
                $("#txtmldGenerarEdi_Moneda").val(datos['moneda']);
                $("#txtmldGenerarEdi_Tc").val(datos['tc']);
                $("#txtmldGenerarEdi_Subtotal").val(datos['subtotal']);
                $("#txtmldGenerarEdi_Total").val(datos['total']);
                $("#txtmldGenerarEdi_Importer").val(IMP_ABI_KEY + ' - ' + IMP_NAME);
                $("#txtmldGenerarEdi_Importer").data('claveimp', IMP_ABI_KEY);
                $("#txtmldGenerarEdi_Importer").data('tcat', tcat);
                console.log(tcat,$("#txtmldGenerarEdi_Importer").data('tcat'));
                $("#txtmldGenerarEdi_Importer").data('invasbr', datos['InvAsBR']);
                $("#txtmldGenerarEdi_Consigneer").val(CONS_ABI_KEY + ' - ' + CONS_NAME);
                $("#txtmldGenerarEdi_Consigneer").data('clavecons', CONS_ABI_KEY);
                //$("#txtmldGenerarEdi_Customer").val(datos['cliente']);CLI_ABI_KEY // Se  cambio la  clave de  cliente por la  captura desde el inicio
                $("#txtmldGenerarEdi_Customer").val(CLI_ABI_KEY);
                $("#txtmldGenerarEdi_Referencia").val(datos['referencia']);
                $("#txtmldGenerarEdi_Locacion").val(datos['location']);
                $("#txtmldGenerarEdi_Puerto").val(datos['puerto']);
                $("#txtmldGenerarEdi_Origen").val(datos['origen']);
                $("#txtmldGenerarEdi_TIOP").val(datos['tiop']);
                $("#txtmldGenerarEdi_Peso").val(datos['pbruto']);
                $("#txtmldGenerarEdi_Caja").val(datos['caja']);
                $("#txtmldGenerarEdi_Caja").data('krelfc', krelfc);
                $("#txtmldGenerarEdi_Medida").val(datos['UOM']);
                $("#txtmldGenerarEdi_Bultos").val(datos['tbultos']);
                ProductosNuevos = datos['ProductosNuevos'];
            }
            else {
                alert(datos);
            }
        },
        error: function (datos, errorThrown) {
            Swal.close();
            console.log("Errores:: ", errorThrown);
        }
    });
}
//Fin de bloque en revision
function opEdi_js_FillSplitCaja(datos) {
    if (datos != "") {
        $('#opEdi_tbSplitCajas').DataTable({
            "destroy": true,
            "data": datos['Invoices'],
            "responsive": true,
            "select": { style: 'single' },
            "searching": false,
            "paging": false,
        });
    }
    else {
        console.log('Eror:' + datos);
        alert(datos);
    }
}
function opEdi_js_SplitCajaFactura(datos) {
    Swal.fire({
        title: 'Success!',
        text: datos,
        icon: 'info',
        confirmButtonText: 'Ok'
        });
    js_MostrarCajas(VistaPanel,1);
    opEdi_js_MostrarFacturasCajas($('#mdlSplitBox_IDIMPCOS').val(),$('#mdlSplitBox_krelfc').val(),$('#mdlSplitBox_caja').val());
}
function opEdi_ajax_CambiarCaja(datos) {
    if(datos['NewCajaFactura']=='Actualizada'){
        Swal.fire({
        title: 'Success!',
        text: 'La caja fue actualizada.',
        icon: 'info',
        confirmButtonText: 'Ok'
        });
        $('#mdlSplitBox_krelfc').val(datos['K_RELF_C']);
        $('#mdlSplitBox_caja').val(datos['Caja'])
        $('#opEdi_spCaja').html("Caja " + datos['Caja']);
        js_MostrarCajas(VistaPanel,1);
    }
    else{
        Swal.fire({
            title: 'Warning!',
            text: 'No fue  posible realizar la actualizacion.',
            icon: 'warning',
            confirmButtonText: 'Ok'
            });
    }
}
function opEdi_js_MostrarFacturasCajas(idedimpcos,krelfc, Caja) {
    var datos = new FormData();
    datos.append("FillSplitCaja", 'true');
    datos.append("IDEDIMPCOS", idedimpcos);
    datos.append("krelfc", krelfc);
    datos.append("Caja", Caja);
    funcion = 'opEdi_js_FillSplitCaja';
    sweet = '';
    LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
}
function opEdi_ActualizarPesosBultos(datos) {
    if (datos == 'ok') {
        Swal.fire({
            title: 'Success!',
            html: 'Datos Actualizados!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    }
    else {
        Swal.fire({
            title: 'Danger!',
            html: 'Error:' + datos,
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    }
}
function opEdi_EliminarCajaFactura(datos) {
    if (datos == 'ok') {
        Swal.fire(
            'Eliminada!',
            'La Operacion  ha  sido eliminada del  sistema.',
            'success'
        )
        js_MostrarCajas(VistaPanel, 1);
    }
    else {
        Swal.fire({
            title: 'Error!',
            html: 'Error:' + datos,
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    }
}
function opEdi_EliminarFactura(datos) {
    if (datos == 'ok') {
        Swal.fire(
            'Eliminada!',
            'La factura y sus  mercancias fueron eliminadas del Sistema.',
            'success'
        )
        js_MostrarCajas(VistaPanel, 1);
        opEdi_js_MostrarFacturasCajas($('#mdlSplitBox_IDIMPCOS').val(),$('#mdlSplitBox_krelfc').val(), $('#mdlSplitBox_caja').val());
    }
    else {
        Swal.fire({
            title: 'Error!',
            html: 'Error:' + datos,
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    }
}
function opEdi_UpdateProductoCat(datos) {
    if (datos == "ok") {
        var ProdABI = document.getElementById('mldUpdCatABI__SLCCatalogoABI');
        var SPI='';
        var SPI_CODE = document.getElementById('opEdi_mldUpdCatABI_chkTMEC');
        if (SPI_CODE.checked == true) {
            SPI= 'S';
        }
        if( ProdABI.options[ProdABI.selectedIndex].value!='' ){
            tabla[$("#mldUpdCatABI_INX").val()][2] = ProdABI.options[ProdABI.selectedIndex].value;
            tabla[$("#mldUpdCatABI_INX").val()][10] = ProdABI.options[ProdABI.selectedIndex].dataset.descripcion;
            tabla[$("#mldUpdCatABI_INX").val()][11] = ProdABI.options[ProdABI.selectedIndex].dataset.hts;
            tabla[$("#mldUpdCatABI_INX").val()][12] = SPI;
            tabla[$("#mldUpdCatABI_INX").val()][13] = '<div class="btn-group">' +
                '<button type="button" class="btn btn-success btn-smy btnUpdateProducto"id="btnUpdateProducto' + $("#mldUpdCatABI_INX").val() + '" name="btnUpdateProducto' + $("#mldUpdCatABI_INX").val() + '" data-index="' + $("#mldUpdCatABI_INX").val() + '" data-clientekeyproduct="' + $("#mldUpdCatABI_NPCL").val() + '" data-abiproducto="' + ProdABI.options[ProdABI.selectedIndex].value + '" data-abiproductodesc="' + ProdABI.options[ProdABI.selectedIndex].dataset.descripcion + '" data-clientedescripcion="' + $("#mldUpdCatABI_DESC").val() + '" data-spicode="'+SPI+'">' +
                    '<i class="fas fa-check"></i>' +
                '</button>' +
                '</div>';
            $('#tblopEdiProcesado').DataTable({
                "destroy": true,
                "data": tabla,
                "responsive": true,
            });
        }
        else if(tabla[$("#mldUpdCatABI_INX").val()][12]!=SPI){
            tabla[$("#mldUpdCatABI_INX").val()][12] = SPI;
            tabla[$("#mldUpdCatABI_INX").val()][13] = '<div class="btn-group">' +
                '<button type="button" class="btn btn-success btn-smy btnUpdateProducto"id="btnUpdateProducto' + $("#mldUpdCatABI_INX").val() + '" name="btnUpdateProducto' + $("#mldUpdCatABI_INX").val() + '" data-index="' + $("#mldUpdCatABI_INX").val() + '" data-clientekeyproduct="' + $("#mldUpdCatABI_NPCL").val() + '" data-abiproducto="' +  $("#mldUpdCatABI_NPCL").val() + '" data-abiproductodesc="' + ProdABI.options[ProdABI.selectedIndex].dataset.descripcion + '" data-clientedescripcion="' + $("#mldUpdCatABI_DESC").val() + '" data-spicode="'+SPI+'">' +
                    '<i class="fas fa-check"></i>' +
                '</button>' +
                '</div>';
            $('#tblopEdiProcesado').DataTable({
                "destroy": true,
                "data": tabla,
                "responsive": true,
            });
        }
        $('#mldUpdCatABI').find('form').trigger('reset');
        $('#mldUpdCatABI__SLCCatalogoABI').empty().append("<option value=''></option>");
        Swal.fire(
            'Actualizada!',
            'Se  actualizo correctamente el Numero de Parte.',
            'success'
        );
        $('#mldUpdCatABI').modal('hide');
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
function js_slcmldAddCatABI_CatalogoABI(SLC) {
    var datos = new FormData();
    datos.append("mldAddCatABI_CatABI", 'true');
    datos.append("IMP_ABI_KEY", IMP_ABI_KEY);
    $.ajax({
        url: "ajax/opEdi.ajax.php",
        type: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (datos) {
            if (datos != "") {
                var cont = Object.keys(datos).length;
                for (var i = 0; i < cont; i++) {
                    var KEY = datos[i]["KEY"];
                    var NAME = datos[i]["NAME"];
                    var HTS = datos[i]["HTS"];
                    var ARTICULO = KEY + " - " + NAME
                    $("#" + SLC + "").append("<option value='" + KEY + "' data-descripcion='" + NAME + "' data-hts='" + HTS + "'>" + ARTICULO + "</option>");
                }
            }
            else {
                alert(datos);
            }
        },
        error: function (datos, errorThrown) {
            console.log("Error:: ", datos);
        }
    });
}
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
function js_RelProdABI() {
    var SPI='';
    var datos = new FormData();
    datos.append("RelProdABI", 'true');
    var ProdABI = document.getElementById('slcmldAddCatABI_CatalogoABI');
    datos.append("ClaveProductoABI", ProdABI.options[ProdABI.selectedIndex].value);
    datos.append("ClaveProducto", $("#txtmldAddCatABI_NP").val());
    datos.append("DescripcionESP", $("#txtmldAddCatABI_DESC").val());
    datos.append("IMP_ABI_KEY", IMP_ABI_KEY);
    datos.append("CONS_ABI_KEY", $("#txtmldGenerarEdi_Consigneer").data('clavecons'));
    datos.append("tcat", $("#txtmldGenerarEdi_Importer").data('tcat'));
    var SPI_CODE = document.getElementById('opEdi_mldAddCatABI_chkTMEC');
    if (SPI_CODE.checked == true) {
        SPI= 'S';
    }
    datos.append("SPI_CODE", SPI);
    $.ajax({
        url: "ajax/opEdi.ajax.php",
        type: "POST",
        beforeSend: function () {
            Swal.fire({
                title: 'Procesando!',
                html: 'Espere, se  estan cargando los datos!!!',
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
        success: function (datos) {
            if (datos == "ok") {
                /*var cont = tabla.length;
                for (x = 0; x <= cont - 1; x++) {
                    if (tabla[x][2] == $("#txtmldAddCatABI_NP").val()) {
                        tabla[x][2] = ProdABI.options[ProdABI.selectedIndex].value;
                        tabla[x][10] = ProdABI.options[ProdABI.selectedIndex].dataset.descripcion;
                        tabla[x][11] = ProdABI.options[ProdABI.selectedIndex].dataset.hts;
                        tabla[x][12] = SPI;
                        tabla[x][13] = '<div class="btn-group">' +
                            '<button type="button" class="btn btn-success btn-smy btnUpdateProducto" id="btnUpdateProducto'+$i+'">' +
                            '<i class="fas fa-check"></i>' +
                            '</button>' +
                            '</div>';
                        ProductosNuevos--;
                        console.log(ProductosNuevos);
                    }
                }*/


                tabla[$('#txtmldAddCatABI_IN').val()][2] = ProdABI.options[ProdABI.selectedIndex].value;
                tabla[$('#txtmldAddCatABI_IN').val()][10] = ProdABI.options[ProdABI.selectedIndex].dataset.descripcion;
                tabla[$('#txtmldAddCatABI_IN').val()][11] = ProdABI.options[ProdABI.selectedIndex].dataset.hts;
                tabla[$('#txtmldAddCatABI_IN').val()][12] = SPI;
                tabla[$('#txtmldAddCatABI_IN').val()][13] = '<div class="btn-group">' +
                        '<button type="button" class="btn btn-success btn-smy btnUpdateProducto" id="btnUpdateProducto'+$('#txtmldAddCatABI_IN').val()+'" name="btnUpdateProducto'+$('#txtmldAddCatABI_IN').val()+'" data-index="'+$('#txtmldAddCatABI_IN').val()+'" data-clientekeyproduct="'+ $("#txtmldAddCatABI_NP").val() +'" data-abiproducto="'+ ProdABI.options[ProdABI.selectedIndex].value+ '" data-abiproductodesc="'+ProdABI.options[ProdABI.selectedIndex].dataset.descripcion+'" data-clientedescripcion="'+$("#txtmldAddCatABI_DESC").val()+'" data-spicode="'+SPI+'">' +
                        '<i class="fas fa-check"></i>' +
                        '</button>' +
                        '</div>';
                ProductosNuevos--;
                console.log(ProductosNuevos);

                $('#tblopEdiProcesado').DataTable({
                    "destroy": true,
                    "data": tabla,
                    "responsive": true,
                });

                $('#mldAddCatABI').find('form').trigger('reset');
                $('#slcmldAddCatABI_CatalogoABI').empty().append("<option value=''></option>");

                console.log(tabla);
                Swal.fire({
                    title: 'Relacion Correcta!',
                    html: "",
                    icon: 'info',
                    confirmButtonText: 'Ok'
                });
            }
            else {
                alert(datos);
            }
        },
        error: function (datos, errorThrown) {
            console.log("Error:: ", datos);
        }
    });
}
function js_Resetfrmedi() {
    ProductosNuevos = 0;
    console.log(ProductosNuevos);
    $('#mldGenerarEdi').find('form').trigger('reset');
    $('#slcmldGenerarEdi_Manufactura').empty().append("<option value=''></option>");
    //$('#slcmldGenerarEdi_scac').empty().append("<option value=''></option>");
    $('#mldGenerarEdi').modal('hide');
}
function js_CountFilesEDIS() {
    var selected = $("#opEdi_mdlUpFiles_slcPanelClientes").find("option:selected");
    if (selected.text() != "Importer") {
        var datos = new FormData();
        datos.append("CountUpFiles", "true");
        datos.append("ruta", selected.data("ruta"));
        $.ajax({
            url: "ajax/opEdi.ajax.php",
            type: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (datos) {
                if (datos != "") {
                    $('#opEdi_mdlUpFiles_XML').val(datos['xml']);
                    $('#opEdi_mdlUpFiles_PDF').val(datos['pdf']);
                    $('#opEdi_mdlUpFiles_XLS').val(datos['xls']);
                    $('#opEdi_mdlUpFiles_Path').val(datos['ruta']);
                }
                else {
                    alert(datos);
                }
            },
            error: function (datos, errorThrown) {
                console.log("Error:: ", datos);
            }
        });
    }
    else {
        $('#opEdi_mdlUpFiles_XML').val('');
        $('#opEdi_mdlUpFiles_PDF').val('');
        $('#opEdi_mdlUpFiles_XLS').val('');
        $('#opEdi_mdlUpFiles_Path').val('');
    }
}
function js_opEdiUpFiles_B20220311(IDEDCLI, IDABI, IDCONSABI, ruta, tarchivo, tipocat) {
    var datos = new FormData();
    datos.append("opEdiUpFiles", "1");
    datos.append("ruta", ruta);
    datos.append("tarchivo", tarchivo);
    datos.append("IDEDCLI", IDEDCLI);
    datos.append("IMP_ABI_KEY", IDABI);
    datos.append("CONS_ABI_KEY", IDCONSABI);
    datos.append("tipocat", tipocat);
    //console.log(datos);
    console.log(tarchivo),
    $.ajax({
        url: "ajax/opEdi.ajax.php",
        type: "POST",
        beforeSend: function () {
            Swal.fire({
                title: 'Procesando!!',
                html: 'Espere, se  estan cargando los datos!!!!',
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
        success: function (datos) {
            Swal.close();
            
            console.log(datos);
            if (typeof datos !== 'undefined') {
                console.log(datos['No_XML']);
                console.log(datos);
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
                        html:   '<b>Facturas Cargadas:</b> ' + datos['Cargados'] + '<br>' +
                                '<b>PDFs Incorrectos:</b><br>' + datos['PDF_NoLayout'] + '<br>' +
                                '<b>XML sin Facturas:</b><br>' + datos['XMLSin_PDF'] + '<br>' +
                                '<b>XML Consignatario Nuevo:</b><br>' + datos['XML_NoValido'] + '<br>' +
                                '<b>Facturas Duplicadas:</b><br>' + datos['Facturas_Duplicadas'] + '<br>' +
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
        },
        /*complete:function(){

        },*/
        error: function (datos, errorThrown) {
            Swal.close();
            console.log("Errores:: ", errorThrown, " ", datos);
        }
    });
}
function opEdi_ajax_opEdiUpFiles(datos) {
   // console.log(datos);
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
function opEdi_ajax_CargaMasiva(datos){
    Swal.fire({
        title: 'Operacion Exitosa!',
        html: "Los Archivos se encuentran en la carpeta XML del cliente.",
        icon: 'info',
        confirmButtonText: 'Ok'
    });
    js_Resetfrmedi();
    js_MostrarCajas(VistaPanel, 1);
}
function opEdi_ajax_updateABITables(datos){
    if(datos=='ok'){
        Swal.fire({
            title: 'Operacion Exitosa!',
            html: "Tablas Actualizadas.",
            icon: 'info',
            confirmButtonText: 'Ok'
        });
    }
    else{
        Swal.fire({
            title: 'Warning!',
            html: "Error al actualizar las  tablas::"+datos,
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }
}
function opEdi_js_fillTbClients(){
    var datos = new FormData();
    datos.append("fillTbClients", 'true');
    funcion = 'opEdi_ajax_fillTbClients';
    sweet = '';
    LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
}
function opEdi_ajax_fillTbClients(datos){
    $('#opEdi_mdlClientes_tbClientes').DataTable({
        "destroy":true,
        "data":datos['Clients'],
        "responsive": true,
        "select":{style:'single'},
        "searching":      true,
        "paging":         true,
        columnDefs: [
            { width: '5%', targets: [0,3,4,5,6] },
            { width: '10%', targets: 2},
            { width: '10%', targets: [1,7] }
        ],
        //fixedColumns: true
    });
}
function opEdi_js_fillTbImporters(idedcli){
    var datos = new FormData();
    datos.append("fillTbImporters", 'true');
    datos.append("IDEDCLI", idedcli);
    funcion = 'opEdi_ajax_fillTbImporters';
    sweet = '';
    LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
}
function opEdi_ajax_fillTbImporters(datos){
    LCHB_FillTable('opEdi_mdlImporters_tbImporters',datos['Importers'],true,true,false);
}
function opEdi_ajax_ImporterDetail(datos){
    //console.log(datos);
    var OpByInv,OpWthBox,InvAsBR;

    $("#opEdi_mdlImporters_txtConsigName").val(datos['ImporterDetail'][0]['CONS_NAME']);
    $("#opEdi_mdlImporters_txtABIKeyConsig").val(datos['ImporterDetail'][0]['CONS_ABI_KEY']);
    //$("#opEdi_mdlImporters_txtEmisor").val(datos['ImporterDetail'][0]['XML_ImporterName']);
    //$("#opEdi_mdlImporters_txtReceptor").val(datos['ImporterDetail'][0]['XML_ReceptorName']);
    $("#opEdi_mdlImporters_txttInvoice").val(datos['ImporterDetail'][0]['t_archivo']);
    $("#opEdi_mdlImporters_txtCat").val(datos['ImporterDetail'][0]['TipoCat']);
    $("#opEdi_mdlImporters_txtManuf").val(datos['ImporterDetail'][0]['Manuf']);
    $("#opEdi_mdlImporters_txtOrigin").val(datos['ImporterDetail'][0]['origen']);
    $("#opEdi_mdlImporters_txtPort").val(datos['ImporterDetail'][0]['puerto']);
    $("#opEdi_mdlImporters_txtLocation").val(datos['ImporterDetail'][0]['location']);
    $("#opEdi_mdlImporters_txtUOM").val(datos['ImporterDetail'][0]['UOM']);
    $("#opEdi_mdlImporters_txtTypeOP").val(datos['ImporterDetail'][0]['tiop']);
    if(datos['ImporterDetail'][0]['OpByInv']==1){
        OpByInv=true;
    }
    else{
        OpByInv=false;
    }
    if(datos['ImporterDetail'][0]['OpWthBox']==1){
        OpWthBox=true;
    }
    else{
        OpWthBox=false;
    }
    if(datos['ImporterDetail'][0]['InvAsBR']==1){
        InvAsBR=true;
    }
    else{
        InvAsBR=false;
    }
    $("#opEdi_mdlImporters_chkOpByInv").prop("checked",OpByInv);
    $("#opEdi_mdlImporters_chkOpWhtBox").prop("checked",OpWthBox);
    $("#opEdi_mdlImporters_chkInvAsBR").prop("checked",InvAsBR);
    LCHB_FillTable('opEdi_mdlImporters_tbImporterXML',datos['ImporterXML'],false,false,false);
    //$("#opEdi_mdlImporters_slcManuf").append("<option value='" + datos[0]['Manuf'] + "' selected>" + datos[0]['Manuf'] + "</option>");
}
function opEdi_ajax_CreateImporter(datos){
    if(datos=='OK'){
        Swal.fire({
            title: 'Operacion Exitosa!',
            html: "Se dio de alta el Importador.",
            icon: 'info',
            confirmButtonText: 'Ok'
        });
        NewXML=[];
        opEdi_js_fillTbImporters($('#opEdi_mdlImporters_hdIDEDCLI').val());
        opedi_js_mdlCreateImporters_btnclosemdl();
    }
    else{
        Swal.fire({
            title: 'Warning!',
            html: "Error al insertar valores::"+datos,
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }
}
function opEdi_ajax_CreateClient(datos){
    if(datos=='ok'){
        Swal.fire({
            title: 'Operacion Exitosa!',
            html: "Se dio de alta el Cliente.",
            icon: 'info',
            confirmButtonText: 'Ok'
        });
        opEdi_js_fillTbClients();
        opedi_js_mdlCreateClientes_btnclosemdl();
    }
    else{
        Swal.fire({
            title: 'Warning!',
            html: "Error al insertar valores::"+datos,
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }
}
function opedi_js_mdlCreateImporters_btnclosemdl(){
    $('#opEdi_mdlCreateImporters_txtImportName').val('');
    $('#opEdi_mdlCreateImporters_txtABIKeyImport').val('');
    $('#opEdi_mdlCreateImporters_txtConsigName').val('');
    $('#opEdi_mdlCreateImporters_txtABIKeyConsig').val('');
    $('#opEdi_mdlCreateImporters_txtEmisor').val('');
    $('#opEdi_mdlCreateImporters_txtReceptor').val('');
    $('#opEdi_mdlCreateImporters_txtManuf').val('');
    $('#opEdi_mdlCreateImporters_chkOpByInv').prop("checked",false);
    $('#opEdi_mdlCreateImporters_chkOpWhtBox').prop("checked",false);
    $('#opEdi_mdlCreateImporters_chkInvAsBR').prop("checked",false);
    $("#opEdi_mdlCreateImporters_tblnewxml tr>td").remove();
    NewXML=[];
}
function opedi_js_mdlImporters_btnclosemdl(){
    $('#opEdi_mdlImporters_txtConsigName').val('');
    $('#opEdi_mdlImporters_txtABIKeyConsig').val('');
    $('#opEdi_mdlImporters_txtEmisor').val('');
    $('#opEdi_mdlImporters_txtReceptor').val('');
    $('#opEdi_mdlImporters_txtManuf').val('');
    $('#opEdi_mdlImporters_txtOrigin').val('');
    $('#opEdi_mdlImporters_txtPort').val('');
    $('#opEdi_mdlImporters_txtLocation').val('');
    $('#opEdi_mdlImporters_txtTypeOP').val('');
    $('#opEdi_mdlImporters_hdIDEDIMPCOS').val('');
    $('#opEdi_mdlImporters_lblImporterName').text('Importer:');
    $('#opEdi_mdlImporters_chkOpByInv').prop("checked",false);
    $('#opEdi_mdlImporters_chkOpWhtBox').prop("checked",false);
    $('#opEdi_mdlImporters_chkInvAsBR').prop("checked",false);
    $("#opEdi_mdlImporters_tbImporterXML tr>td").remove();
}
function opedi_js_mdlCreateClientes_btnclosemdl(){
    $('#opEdi_mdlCreateClientes_txtClientName').val('');
    $('#opEdi_mdlCreateClientes_txtABIKeyImport').val('');
    $('#opEdi_mdlCreateClientes_txtPath').val('');
    $('#opEdi_mdlCreateClientes_txtFiler').val('');
    $('#opEdi_mdlCreateClientes_txtRangeType').val('');
    $('#opEdi_mdlCreateClientes_txtRangeStart').val('');
    $('#opEdi_mdlCreateClientes_txtRangeLast').val('');
    $('#opEdi_mdlCreateClientes_txtObservaciones').val('');
}
function opEdi_ajax_PrintManifest(datos){
    var UpdateMessage='';
    if(datos['UpdateRequired']==1){
        UpdateMessage='<br> <br> Algunos  Manifiestos no pudieron  generarse, use el boton <strong>Update Manifest</strong> y  vuelva a intentarlo. ';
    }
    Swal.fire({
        title: 'Summary',
        html: "<strong>Manifest Completed: </strong><br>"+ datos['ManifestValidados']+'<br> <br> <strong>Manifest No  Validated:</strong><br> '+datos['ManifestNoValidados'] + UpdateMessage,
        icon: 'info',
        confirmButtonText: 'Ok'
    });
    $('#opEdi_mdlPrintManifest').find('form').trigger('reset');

    /*RESETEAR LA SELECCION */
    ManifiestosImpresos=DATA_EDI.filter(caja=>caja[7]!=0);
    if(Object.keys(ManifiestosImpresos).length>1){
        DATA_EDI.forEach((value,indice)=>{
            if(DATA_EDI[indice][7]==1){
                DATA_EDI[indice][7]=0;
                document.getElementById($("#CHK"+indice).attr('name')).className='btn btn-warning btn-smy btnCheckToProccess';
                document.getElementById($("#CHK"+indice).attr('name')).innerHTML='<i class="fas fa-dot-circle">';
            }
        });  
    } 
}
function opEdi_ajax_UpdateManifestTable(datos){
    var UpdateMessage='';
    if(datos=='OK'){
        UpdateMessage='Los manifiestos  fueron Actualizados';
    }
    else{
        UpdateMessage='No fue posible  actualizar los manifiestos, error:: '+datos;
    }
    Swal.fire({
        title: 'Summary',
        html: UpdateMessage,
        icon: 'info',
        confirmButtonText: 'Ok'
    });
    $('#opEdi_mdlPrintManifest').find('form').trigger('reset');
}
function opedi_js_ProcesarCajas(Option){
        /* Guardar  una  tabla con los datos principales del procesod e EDIS*/

        /*Agregar  condicion para  solo lo que esta  visible en la  tabla, si no esta  visible  no se debe procesar. */
        /*Para el proceso Masivo agregar un checkbox, si este  esta  tildado se  procesa si no lo esta no se procesa guardar  los datos en una  variable para el control */
        /*Se debe evaluar  si los  registros marcados con el checkbox  */
        /*Armar arreglo para mandar a  procesar EDIS */
        /*El array  final llevar el SCAC y en el controlador  llamara las  funciones  individuales, el proceso de  evaluacion de partes  no se debe realizar, se  evalua en proceso anterior. */
        var statusProductos=0;
        var statusCajasxprocesar=0;
        var statusCaja=0;
        var statusScacxcajas=0;
        var cajasxprocesar=new Array(); //Se  agrega al  final el SCAC Capturado

        if(Option==1){
           cajasxprocesar=DATA_EDI.filter(caja=>caja[7]!=0); 
        }
        else{
            cajasxprocesar=DATA_EDI; 
        }
        statusCajasxprocesar=Object.keys(cajasxprocesar).length;
        statusProductos =cajasxprocesar.filter(caja=> caja[6] == 1).length;
        cajasxprocesar.forEach((value,indice)=>{
            console.log(cajasxprocesar[indice][4]);
            var tbSCAC=document.getElementById("tbCajas_txtscac"+value[8]).value;
            if(tbSCAC==''){
                statusScacxcajas=1;
            }
            else if(cajasxprocesar[indice][4]==""){
                statusCaja=1;
            }
            else{
                var tbMANUF=document.getElementById("tbCajas_slcManuf"+value[8]);
                cajasxprocesar[indice][9]=tbSCAC;
                cajasxprocesar[indice][10]=tbMANUF.options[tbMANUF.selectedIndex].value;
                cajasxprocesar[indice][11]=CLI_PATH; // Agrega la ruta de los  EDIS
                cajasxprocesar[indice][12]=IDEDCLI; // Agrega  el codigo del cliente
                cajasxprocesar[indice][13]=CLI_ABI_KEY; // Agrega  el codigo ABI del cliente 
            }
        });

        if(statusCajasxprocesar==0 && Option==1){
            Swal.fire({
                title: 'Warning!',
                text: '!Debe marcar almenos una caja para procesar el EDI!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else if(statusProductos!=0){
            Swal.fire({
                title: 'Warning!',
                text: '!No deben existir productos nuevos!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else if(statusScacxcajas!=0){
            Swal.fire({
                title: 'Warning!',
                text: '!El SCAC debe capturarse!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else if(statusCaja!=0){
            Swal.fire({
                title: 'Warning!',
                text: '!El todas las lineas deben tener  caja!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else{
            /* En esta  seccion se  debe enviar a procesar  el listado de  EDIS */
            var datos = new FormData();
            datos.append("CargaMasiva", 'true');
            var json = JSON.stringify(cajasxprocesar);
            datos.append("datos_edi", json);
            funcion = 'opEdi_ajax_CargaMasiva';
            sweet = 'LCHB_SwalWait';
            LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
        }  
}
function opedi_js_DeleteBoxInvoices(Option){
        Swal.fire({
            title: 'Desea eliminar  estas  operaciones junto a sus facturas  y mercancias?',
            text: "Una  vez  aplicado el cambio no podra revertirse!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar las Operaciones!'
        }).then((result) => {
            if (result.isConfirmed) {
                var A_Operations_rows = new Array(); 
                var c_operations=0;
                if(Option==3){ 
                    cajasxprocesar=DATA_EDI.filter(caja=>caja[7]!=0); 
                }
                else{
                     cajasxprocesar=DATA_EDI; 
                }
                statusCajasxprocesar=Object.keys(cajasxprocesar).length;
                //en lugar de un objeto
                cajasxprocesar.forEach((value,indice)=>{
                    var A_Operations_data =new Array();  
                    A_Operations_data[0]=cajasxprocesar[indice][0];
                    A_Operations_data[1]=cajasxprocesar[indice][3];
                    A_Operations_data[2]=cajasxprocesar[indice][4];
                    A_Operations_rows.push(A_Operations_data);
                    c_operations++;
                });
                
                if(statusCajasxprocesar==0 && Option==3){
                    Swal.fire({
                        title: 'Warning!',
                        text: '!Debe marcar almenos una caja para procesar el EDI!',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
                else{
                    var datos = new FormData();
                    datos.append("EliminarCajaFacturaMasivo", 'true');
                    var json = JSON.stringify(A_Operations_rows);
                    datos.append("Data",json);
                    datos.append("CLI_ABI_KEY",CLI_ABI_KEY);
                    datos.append("VistaPanel",VistaPanel);
                    funcion = 'opEdi_ajax_EliminarCajaFacturaMasivo';
                    sweet = '';
                    //console.log(DATA_EDI);
                    //console.log(A_Operations_rows,VistaPanel,CLI_ABI_KEY);
                    LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
                }
                
            }
        })
}

function opedi_js_PrintManifestToSelectedEntrys(){
    var A_Operations_rows = new Array(); 
    var c_operations=0;
    cajasxprocesar=DATA_EDI.filter(caja=>caja[7]!=0); //Filtra el Arreglo DATA_EDI por el indice  7 ( Identificador de Seleccion)
    statusCajasxprocesar=Object.keys(cajasxprocesar).length;
    cajasxprocesar.forEach((value,indice)=>{
        A_Operations_rows.push(cajasxprocesar[indice][3]);
        c_operations++;
    });     
    if(statusCajasxprocesar==0){
        Swal.fire({
            title: 'Warning!',
            text: '!Debe marcar almenos una caja para procesar el Manifiesto!',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    }
    else{
        var datos = new FormData();
        datos.append("PrintManifestSelectedEntrys", 'true');
        var json = JSON.stringify(A_Operations_rows);
        datos.append("Data",json);
        funcion = 'opEdi_ajax_PrintManifest';
        sweet = '';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
    }
}


function opEdi_ajax_EliminarCajaFacturaMasivo(datos){
    var UpdateMessage='';
    if(datos==0){
        UpdateMessage='Las operaciones fueron eliminadas.';
    }
    else{
        UpdateMessage='Algunas operaciones no fueron eliminadas, intente eliminarlas individualmente o consulte al Departamento de Sistemas.';
    }
    js_MostrarCajas(VistaPanel, 1);
    Swal.fire({
        title: 'Summary',
        html: UpdateMessage,
        icon: 'info',
        confirmButtonText: 'Ok'
    });
}

function Notificaciones_Manifest(){
    var datos= new FormData();
    datos.append("Notificaciones_Manifest",'true');
    funcion= 'ajax_Notificaciones_Manifest';
    sweet='';
    LCHB_ajax_simetric("ajax/LaredoCHB.Funciones.ajax.php",datos,funcion,sweet);
}
function opEdi_ajax_ReadNotifications(datos){
    if(datos=='OK'){
        Swal.fire({
            title: 'Summary',
            html: "All Notifications was cleaned.",
            icon: 'info',
            confirmButtonText: 'Ok'
        });
    }
    else{
        Swal.fire({
            title: 'Summary',
            html: "Error to try clean notifications.",
            icon: 'danger',
            confirmButtonText: 'Ok'
        });
    }
    
}
function ajax_Notificaciones_Manifest(datos){
    if(Notification.permission!=="granted") {
        Notification.requestPermission();
    }
    if('Notification' in window && Notification.permission === 'granted') {
        var c=Object.keys(datos).length
        //var note=[];
        if(datos!="Empty"){
             for(i=0;i<c;i++){
               
                var note = new Notification('CBRIS Manifest Notification', {
                //icon: 'http://192.168.2.124/LaredoCHB/LaredoCHBV2/opEdi',
                body: datos[i]
                });
                note.onclick = function() {
                    //e.preventDefault();
                    //window.open('datos[i]', _blank'');
                    //child =  window.open("http://www.google.com","CBRIS NOTIFICATIONS","width=800,height=400,top=30,resizable=yes");
                   // child =  window.open("_blank","CBRIS NOTIFICATIONS","width=800,height=400,top=30,resizable=yes"); 
                   /*child=undefined; 
                   if ( child ) {
                        child.focus();
                        window.timerID = window.setInterval(function() {            // check every 2 seconds to see if popup is closed yet
                                        if ( child && !child.closed ) {
                                            // keep waiting
                                        } else {
                                            clearInterval(window.timerID);  
                                            alert("Now please provide feedback on your Google experience.");
                                        }
                                    }, 2000);
                    } else {
                        alert("Google cannot open because popups are blocked.");
                    }*/

                    alert(this['body']);
                }
            }
        }
        setTimeout(notificar,600000);
    }
}
function notificar(){
    Notificaciones_Manifest();
}
function opEdi_ajax_AddExchRate(datos){
    if(datos=='OK'){
        Swal.fire({
            title: 'Summary',
            html: "Exchanged rate added.",
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    }
    else{
        Swal.fire({
            title: 'Summary',
            html: "Error to try insert a exchange rate.",
            icon: 'danger',
            confirmButtonText: 'Ok'
        });
    }
}
function opEdi_ajax_showImporterToCopy(datos){
    LCHB_FillTable('opEdi_mdlCopyImporterData_tbImporters',datos['Importers']);
    $('#opEdi_mdlCopyImporterData').modal({ backdrop: 'static', keyboard: false });
    $('#opEdi_mdlCopyImporterData').modal('show');
}
function opEdi_ajax_copyImporterData(datos){
    NewXML=[];
    console.log(datos);
    $('#opEdi_mdlCreateImporters_txtImportName').val(datos['Importer'][0]['IMP_NAME']);
    $('#opEdi_mdlCreateImporters_txtABIKeyImport').val(datos['Importer'][0]['IMP_ABI_KEY']);
    $('#opEdi_mdlCreateImporters_txtConsigName').val(datos['Importer'][0]['CONS_NAME']);
    $('#opEdi_mdlCreateImporters_txtABIKeyConsig').val(datos['Importer'][0]['CONS_ABI_KEY']);
    $('#opEdi_mdlCreateImporters_txtManuf').val(datos['Importer'][0]['Manuf']);
    $('#opEdi_mdlCreateImporters_txtTInv').val(datos['Importer'][0]['t_archivo']);
    document.getElementById("opEdi_mdlCreateImporters_slcTypeOfCat").selectedIndex=datos['Importer'][0]['TipoCat'];
    $('#opEdi_mdlCreateImporters_txtUOM').val(datos['Importer'][0]['UOM']);
    $('#opEdi_mdlCreateImporters_txtOrigin').val(datos['Importer'][0]['origen']);
    $('#opEdi_mdlCreateImporters_txtPort').val(datos['Importer'][0]['puerto']);
    $('#opEdi_mdlCreateImporters_txtLocation').val(datos['Importer'][0]['location']);
    $('#opEdi_mdlCreateImporters_txtTypeOp').val(datos['Importer'][0]['tiop']);
    console.log(datos['Importer'][0]['OpByInv'],datos['Importer'][0]['OpWthBox']);
    if(datos['Importer'][0]['OpByInv']==1){
        $('#opEdi_mdlCreateImporters_chkOpByInv').prop("checked",true);
    }
    else{
        $('#opEdi_mdlCreateImporters_chkOpByInv').prop("checked",false);
    }
    if(datos['Importer'][0]['OpWthBox']==1){
        $('#opEdi_mdlCreateImporters_chkOpWhtBox').prop("checked",true);
    }
    else{
        $('#opEdi_mdlCreateImporters_chkOpWhtBox').prop("checked",false);
    }
    if(datos['Importer'][0]['InvAsBR']==1){
        $('#opEdi_mdlCreateImporters_chkInvAsBR').prop("checked",true);
    }
    else{
        $('#opEdi_mdlCreateImporters_chkInvAsBR').prop("checked",false);
    }
    var cont = Object.keys(datos['Importer_XML']).length;
    console.log(cont);
    if(cont>0){
        var XML= new Object();
        for (var i = 0; i < cont; i++) {
            var Emisor = datos['Importer_XML'][i]['XML_ImporterName'];
            var Receptor =  datos['Importer_XML'][i]['XML_ReceptorName'];
            console.log(Emisor,Receptor);
            $('#opEdi_mdlCreateImporters_tblnewxml tr:last').after('<tr class="table-info"><td>New</td><td>'+Emisor+'</td><td>'+Receptor+'</td></tr>');
            XML["Receptor"]=Receptor;
            XML["Emisor"]=Emisor;
            NewXML.push(XML);
        }
    }
    $('#opEdi_mdlCopyImporterData').modal('hide');
}

function opEdi_ajax_opEdiFillEDI_MergeLines(datos){
    if (datos != "") {
        tabla = datos['tabla'];
        $('#tblopEdiProcesado').DataTable({
            "destroy": true,
            "data": datos['tabla'],
            "responsive": true,
        });
        ProductosNuevos = datos['ProductosNuevos'];
        if($('#opEdi_mldGenerarEdi_chkMgrProd').checked == true){
            Swal.fire({
                title: 'Summary',
                html: "Lines was merged.",
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        }
        else{
            Swal.fire({
                title: 'Summary',
                html: "Lines was parted.",
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        }
        
    }
    else {
        alert(datos);
    }    
}

$(document).ready(function () {
    notificar();

    $('[data-toggle="tooltip"]').tooltip();
    //Inicio de bloque revisado y actualizado 20220215
    $(".btnMostrarCajas").click(function () {
        js_MostrarCajas(VistaPanel, 0);
    });
    //Fin de bloque revisado y actualizado
    //Inicio de bloque  en revision
    $(".chkMostrarCajas").change(function () {
        var Opcion = document.getElementById('opEdi_rdSinProcesar');
        if (Opcion.checked == true) {
            VistaPanel = 1;
            js_MostrarCajas(VistaPanel, 0);
        }
        else {
            VistaPanel = 2;
            js_MostrarCajas(VistaPanel, 0);
        }
    });
    //Fin de bloque  en revision
    //Inicio de bloque en revison
    $("#tbCajas").on('click', '.btnProcesarEdi', function () {
        var Procesar = document.getElementById($(this).attr('name'));
        var caja = Procesar.dataset.caja;
        var krelfc = Procesar.dataset.krelfc;
        var tcat = Procesar.dataset.tcat;
        IDEDIMPCOS=Procesar.dataset.idedimpcos;
        IMP_ABI_KEY=Procesar.dataset.impabikey;
        IMP_NAME=Procesar.dataset.importador;
        CONS_ABI_KEY=Procesar.dataset.consabikey;
        CONS_NAME=Procesar.dataset.consname;
        js_opEdiFillEDI(caja,krelfc,tcat);
        $('#mldGenerarEdi').modal({ backdrop: 'static', keyboard: false });
        $('#mldGenerarEdi').modal('show');
    });
    //Fin de bloque en revison
    $("#tblopEdiProcesado").on('click', '.btnAddProducto', function () {
        var Producto = document.getElementById($(this).attr('name'));
        var index = Producto.dataset.index;
        js_slcmldAddCatABI_CatalogoABI("slcmldAddCatABI_CatalogoABI");
        $("#txtmldAddCatABI_NP").val(tabla[index][2]);
        $("#txtmldAddCatABI_DESC").val(tabla[index][10]);
        $("#txtmldAddCatABI_IN").val(index);
        $('#mldAddCatABI').modal({ backdrop: 'static', keyboard: false });
        $('#mldAddCatABI').modal('show');
    });

    $("#tblopEdiProcesado").on('click', '.btnUpdateProducto', function () {
        var Producto = document.getElementById($(this).attr('name'));
        var index = Producto.dataset.index;
        js_slcmldAddCatABI_CatalogoABI("mldUpdCatABI__SLCCatalogoABI");
        $("#mldUpdCatABI_txtNPCL").val(Producto.dataset.clientekeyproduct + '-' + Producto.dataset.clientedescripcion);
        $("#mldUpdCatABI_NPCL").val(Producto.dataset.clientekeyproduct);
        $("#mldUpdCatABI_DESC").val(Producto.dataset.clientedescripcion);
        $("#mldUpdCatABI_INX").val(index);
        $("#mldUpdCatABI_txtNPABI").val(Producto.dataset.abiproducto + '-' + Producto.dataset.abiproductodesc);
        $("#mldUpdCatABI_hidNPABI").val(Producto.dataset.abiproducto);
        $("#mldUpdCatABI_hidNPABI_DESC").val(Producto.dataset.abiproductodesc);
        if(Producto.dataset.spicode=='S'){
            $("#opEdi_mldUpdCatABI_chkTMEC").prop("checked",true);
        }
        else{
            $("#opEdi_mldUpdCatABI_chkTMEC").prop("checked",false);
        }
        $('#mldUpdCatABI').modal({ backdrop: 'static', keyboard: false });
        $('#mldUpdCatABI').modal('show');
    });

    /*MODALS */

     $(".opEdi_showmdlUpFiles").click(function () {
        $('#opEdi_mdlUpFiles').modal({ backdrop: 'static', keyboard: false });
        $('#opEdi_mdlUpFiles').modal('show');

    });

    $('.opEdi_mdlUpFiles_btnclosemdl').click(function () {
        var select = document.getElementById("opEdi_mdlUpFiles_slcPanelClientes");
        select.selectedIndex = 0;
        $('#opEdi_mdlUpFiles_XML').val('');
        $('#opEdi_mdlUpFiles_PDF').val('');
        $('#opEdi_mdlUpFiles_XLS').val('');
        $('#opEdi_mdlUpFiles_Path').val('');
        $('#opEdi_mdlUpFiles').modal('hide');
    });

    $(".opEdi_showmdlClients").click(function () {
        opEdi_js_fillTbClients();
        $('#opEdi_mdlClientes').modal({ backdrop: 'static', keyboard: false });
        $('#opEdi_mdlClientes').modal('show');

    });

    $('.opEdi_mdlClientes_btnclosemdl').click(function () {
        $('#opEdi_mdlClientes').modal('hide');
    });

    $('.opEdi_showmdlCreateClients').click(function(){
        $('#opEdi_mdlCreateClientes').modal({ backdrop: 'static', keyboard: false });
        $('#opEdi_mdlCreateClientes').modal('show');
    });

    $('.opEdi_mdlCreateClientes_btnclosemdl').click(function () {
        opedi_js_mdlCreateClientes_btnclosemdl();
        $('#opEdi_mdlCreateClientes').modal('hide');
    });

    $("#opEdi_mdlClientes").on('click', '.opEdi_showmdlImporters', function () {
        opEdi_js_fillTbImporters($(this).data('idedcli'));
        $("#opEdi_mdlImporters_ClienteName").html($(this).data('name'));
        $("#opEdi_mdlImporters_hdIDEDCLI").val($(this).data('idedcli'));
        $('#opEdi_mdlImporters').modal({ backdrop: 'static', keyboard: false });
        $('#opEdi_mdlImporters').modal('show');
    });

    $('.opEdi_mdlImporters_btnclosemdl').click(function () {
        opedi_js_mdlImporters_btnclosemdl();
        $('#opEdi_mdlImporters').modal('hide');
    });

    $(".opEdi_showmdlCreateImporters").click(function () {
        $('#opEdi_mdlCreateImporters').modal({ backdrop: 'static', keyboard: false });
        $('#opEdi_mdlCreateImporters').modal('show');

    });

    $(".opEdi_showmdlUploadCatalog").click(function () {
        $('#opEdi_mdlUploadCatalog').modal({ backdrop: 'static', keyboard: false });
        $('#opEdi_mdlUploadCatalog').modal('show');

    });

    $('.opEdi_mdlCreateImporters_btnclosemdl').click(function () {
        opedi_js_mdlCreateImporters_btnclosemdl();
        $('#opEdi_mdlCreateImporters').modal('hide');
    });

    $('.opEdi_mdlUploadCatalog_btnclosemdl').click(function () {
        $('#opEdi_mdlUploadCatalog').modal('hide');
    });

    $(".opEdi_printManifest").click(function(){
        $('#opEdi_mdlPrintManifest').modal({ backdrop: 'static', keyboard: false });
        $('#opEdi_mdlPrintManifest').modal('show');
    });

    $(".opEdi_showmdlCopyImporterData").click(function(){
        var datos = new FormData();
        datos.append("showImporterToCopy", 'true');
        funcion = 'opEdi_ajax_showImporterToCopy';
        sweet = '';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet); 
    });
    $('.opEdi_mdlCopyImporterData_btnclosemdl').click(function () {
        $('#opEdi_mdlCopyImporterData').modal('hide');
    });

    /*MODALS */

    $('#opEdi_mdlCreateImporters_btnCreateImporter').click(function(){
        console.log(NewXML);
        console.log(Object.keys(NewXML).length)
        var TypeCat= document.getElementById('opEdi_mdlCreateImporters_slcTypeOfCat');
        var Cat=TypeCat.options[TypeCat.selectedIndex].value;
        if($("#opEdi_mdlCreateImporters_txtImportName").val()!='' && $("#opEdi_mdlCreateImporters_txtABIKeyImport").val()!='' && $("#opEdi_mdlCreateImporters_txtConsigName").val()!='' && $("#opEdi_mdlCreateImporters_txtABIKeyConsig").val()!='' &&  Object.keys(NewXML).length>0 && Cat>=1){
            var values = new Object();
            values["IDEDCLI"] = $("#opEdi_mdlImporters_hdIDEDCLI").val();
            values["ImportName"] = $("#opEdi_mdlCreateImporters_txtImportName").val();
            values["ABIKeyImport"] = $('#opEdi_mdlCreateImporters_txtABIKeyImport').val();
            values["ConsigName"] = $('#opEdi_mdlCreateImporters_txtConsigName').val();
            values["ABIKeyConsig"] = $('#opEdi_mdlCreateImporters_txtABIKeyConsig').val();
            //values["Emisor"] = $('#opEdi_mdlCreateImporters_txtEmisor').val();
            //values["Receptor"] = $('#opEdi_mdlCreateImporters_txtReceptor').val();
            values["Manuf"] = $('#opEdi_mdlCreateImporters_txtManuf').val();
            values["TInv"] = $('#opEdi_mdlCreateImporters_txtTInv').val();
            //values["Cat"] = $('#opEdi_mdlCreateImporters_txtCat').val();
            values["Origin"] = $('#opEdi_mdlCreateImporters_txtOrigin').val();
            values["Port"] = $('#opEdi_mdlCreateImporters_txtPort').val();
            values["Location"] = $('#opEdi_mdlCreateImporters_txtLocation').val();
            values["TypeOp"] = $('#opEdi_mdlCreateImporters_txtTypeOp').val();
            values["UOM"] = $('#opEdi_mdlCreateImporters_txtUOM').val();
            values["LIST_XML"] =  NewXML;
            if($("#opEdi_mdlCreateImporters_chkOpByInv").checked == true){
                values["OpByInv"] = 1;
            }
            else{
                values["OpByInv"] = 0;
            }
            if($("#opEdi_mdlCreateImporters_chkOpWithoutBox").checked == true){
                values["OpWthBox"] = 1;
            }
            else{
                values["OpWthBox"] = 0;
            }
            if($("#opEdi_mdlCreateImporters_InvAsBR").checked == true){
                values["InvAsBR"] = 1;
            }
            else{
                values["InvAsBR"] = 0;
            }
            values["Cat"] = Cat;
            //console.log(TypeCat,TypeCat.options[TypeCat.selectedIndex].value);
            //var catalogo=TypeCat.options[TypeCat.selectedIndex].value;
            //datos.append("Cat", catalogo);

            var json = JSON.stringify(values);
            var datos = new FormData();
            datos.append("CreateImporter", 'true');
            datos.append("datos", json);
            funcion = 'opEdi_ajax_CreateImporter';
            sweet = '';
            LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
        }
        else{
            Swal.fire({
                title: 'Warning!',
                html: 'Llene todos los campos para poder crear el Importador.',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
    });
    $('#opEdi_mdlCreateClientes_btnCreateCliente').click(function(){
        if($("#opEdi_mdlCreateClientes_txtClientName").val()!='' && $("#opEdi_mdlCreateClientes_txtABIKeyClient").val()!='' && $("#opEdi_mdlCreateClientes_txtPath").val()!='' && $("#opEdi_mdlCreateClientes_txtFiler").val()!=''){
            var values = new Object();
            values["ClientName"] = $("#opEdi_mdlCreateClientes_txtClientName").val();
            values["ABIKeyClient"] = $('#opEdi_mdlCreateClientes_txtABIKeyClient').val();
            values["Path"] = $('#opEdi_mdlCreateClientes_txtPath').val();
            values["Filer"] = $('#opEdi_mdlCreateClientes_txtFiler').val();
            var TipeRange = document.getElementById('opEdi_mdlCreateClientes_slcRangeType');
            values["RangeType"]=TipeRange.options[TipeRange.selectedIndex].value;
            if (TipeRange.options[TipeRange.selectedIndex].value == 0) {
                values["Firts"]=0;
                values["Last"]=0;
            }
            else {
                values["Firts"]=$('#opEdi_mdlCreateClientes_txtRangeStart').val();
                values["Last"]=$('#opEdi_mdlCreateClientes_txtRangeLast').val();
            }
            values["Observaciones"] = $('#opEdi_mdlCreateClientes_txtObservaciones').val();
            var json = JSON.stringify(values);
            var datos = new FormData();
            datos.append("CreateClient", 'true');
            datos.append("datos", json);
            funcion = 'opEdi_ajax_CreateClient';
            sweet = '';
            LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
        }
        else{
            Swal.fire({
                title: 'Warning!',
                html: 'Llene todos los campos para poder crear el Cliente.',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
    });

    $('.mldAddCatABI_btnRefreshCatABI').click(function () {
        $('#slcmldAddCatABI_CatalogoABI').empty().append("<option value=''></option>");
        js_slcmldAddCatABI_CatalogoABI("slcmldAddCatABI_CatalogoABI");

    });

    $('.mldUpdCatABI_btnRefreshCatABI').click(function () {
        $('#mldUpdCatABI__SLCCatalogoABI').empty().append("<option value=''></option>");
        js_slcmldAddCatABI_CatalogoABI("mldUpdCatABI__SLCCatalogoABI");

    });

    $(".mldUpdCatABI_btnRelProdABI").click(function () {
        var datos = new FormData();
        datos.append("UPDProdABI", 'true');
        var ProdABI = document.getElementById('mldUpdCatABI__SLCCatalogoABI');
        if( ProdABI.options[ProdABI.selectedIndex].value!=''){
            datos.append("ClaveProductoABI", ProdABI.options[ProdABI.selectedIndex].value);
        }
        else{
            datos.append("ClaveProductoABI", $('#mldUpdCatABI_hidNPABI').val());
        }
        datos.append("ClaveProducto", $("#mldUpdCatABI_NPCL").val());
        datos.append("IMP_ABI_KEY", IMP_ABI_KEY);
        datos.append("CONS_ABI_KEY", $("#txtmldGenerarEdi_Consigneer").data('clavecons'));
        var SPI_CODE = document.getElementById('opEdi_mldUpdCatABI_chkTMEC');
        if (SPI_CODE.checked == true) {
            datos.append("SPI_CODE", 'S');
        }
        else {
            datos.append("SPI_CODE", '');
        }

        funcion = 'opEdi_UpdateProductoCat';
        sweet = '';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
    });

    $(".btnRelProdABI").click(function () {
        js_RelProdABI();
        //Marca error si se convierte el modal a  form,  pierde los valores de los campos.
        //$('#mldAddCatABI').find('form').trigger('reset');
        $('#mldAddCatABI').modal('hide');
    });

    $(".btnResetfrmedi").click(function () {
        js_Resetfrmedi();
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function js_ProrrateoPeso() {
        nuevo_peso = $('#txtmldGenerarEdi_Peso').val();
        anterior_peso = $("#btnActualizarUnidades").data('peso');
        dif_peso = nuevo_peso - $("#btnActualizarUnidades").data('peso');
        var cont = 0;
        var tcont = Object.keys(tabla).length;
        articulos = $('#tblopEdiProcesado').dataTable({
            destroy: true,
            //Marca error colocarlo true
            paging: false,
            searching: false,
            ordering: false,
            "fnRowCallback": function (nRow, iDisplayIndexFull) {
                $(nRow).children().each(function (index, td) {
                    if (index == 5) {
                        if (tabla[cont][5] == 0) {
                            tabla[cont][5] = nuevo_peso / tcont;
                            $(td).html(tabla[cont][5]);
                            tabla[cont][6] = nuevo_peso / tcont;
                        }
                        else {
                            Porcentaje = (tabla[cont][5] / anterior_peso) * 100;
                            tabla[cont][5] = (tabla[cont][5] + (dif_peso * Porcentaje) / 100)
                            $(td).html(tabla[cont][5]);
                        }
                    }
                    //Si  esta  opcion marca  error, se  debera  recrear  toda la tabla al final del calculo, y eliminar laparte que actualiza la tabla actual.
                    else if (index == 6) {
                        $(td).html(tabla[cont][6]);
                    }
                });
                cont += 1;
                return nRow;
            },
        });
        return nuevo_peso;
    }
    //No es  necesario prorratear las  cantidades.
    function js_ProrrateoBultos() {
        var cont = 0;
        NuevoBultos = $('#txtmldGenerarEdi_Bultos').val();
        articulos = $('#tblopEdiProcesado').dataTable({
            destroy: true,
            paging: false,
            searching: false,
            ordering: false,
            "fnRowCallback": function (nRow) {
                $(nRow).children().each(function (index, td) {
                    if (index == 3) {
                        if (cont == 0) {
                            tabla[cont][3] = NuevoBultos;
                            $(td).html(tabla[cont][3]);
                        }
                        else {
                            tabla[cont][3] = 0;
                            $(td).html(tabla[cont][3]);
                        }
                    }
                });
                cont += 1;
                return nRow;
            },
        });
        return NuevoBultos;
    }
    //Es necesario generar una  funcion que  calcule los valores  prorrateados y los  actualice en la  tabla, 
    $(".btnActualizarUnidades").click(function () {
        var BanderaCambio = 0;
        var datos_PesosBultos = new Object();
        datos_PesosBultos["Peso"] = 0;
        datos_PesosBultos["Bultos"] = 0;
        document.getElementById('txtmldGenerarEdi_Medida').setAttribute('readOnly', true);
        document.getElementById('txtmldGenerarEdi_Bultos').setAttribute('readOnly', true);
        document.getElementById('txtmldGenerarEdi_Peso').setAttribute('readOnly', true);
        if ($("#btnActualizarUnidades").data('peso') != $("#txtmldGenerarEdi_Peso").val()) {
            datos_PesosBultos["Peso"] = js_ProrrateoPeso();
            BanderaCambio = 1;
        }
        if ($("#btnActualizarUnidades").data('bultos') != $("#txtmldGenerarEdi_Bultos").val()) {
            datos_PesosBultos["Bultos"] = js_ProrrateoBultos();
            BanderaCambio = 1;
        }
        var habilitar = document.getElementById("btnHabilitarUnidades");
        habilitar.style.visibility = 'visible';
        var actualizar = document.getElementById("btnActualizarUnidades");
        actualizar.style.visibility = 'hidden';

    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $('.btnResetAddCatABI').click(function () {
        $('#mldAddCatABI').find('form').trigger('reset');
        $('#slcmldAddCatABI_CatalogoABI').empty().append("<option value=''></option>");
    });

    $(".btnHabilitarUnidades").click(function () {
        document.getElementById('txtmldGenerarEdi_Medida').removeAttribute('readonly');
        document.getElementById('txtmldGenerarEdi_Bultos').removeAttribute('readonly');
        document.getElementById('txtmldGenerarEdi_Peso').removeAttribute('readonly');
        $("#btnActualizarUnidades").data('peso', $("#txtmldGenerarEdi_Peso").val());
        $("#btnActualizarUnidades").data('bultos', $("#txtmldGenerarEdi_Bultos").val());
        var habilitar = document.getElementById("btnHabilitarUnidades");
        habilitar.style.visibility = 'hidden';
        var actualizar = document.getElementById("btnActualizarUnidades");
        actualizar.style.visibility = 'visible';

    });

    $('#opEdi_mdlUpFiles_slcPanelClientes').change(function () {
        js_CountFilesEDIS();
    });

    $('#opEdi_mdlUpFiles_btnUpFiles').click(function () {
        if ($('#opEdi_mdlUpFiles_Path').val() == '') {
            Swal.fire({
                title: 'Warning!',
                text: 'Selecciona un Cliente',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else if($('#opEdi_mdlUpFiles_XML').val() == 0 && $('#opEdi_mdlUpFiles_PDF').val() == 0 && $('#opEdi_mdlUpFiles_XLS').val() == 0){
            Swal.fire({
                title: 'Warning!',
                text: 'No hay Archivos para  procesar.',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else{
            var selected = $("#opEdi_mdlUpFiles_slcPanelClientes").find("option:selected");
            var datos = new FormData();
            //console.log(selected.val());
            console.log(selected.data('ruta'));

            datos.append("opEdiUpFiles", "true");
            datos.append("IDEDCLI", selected.val());
            datos.append("ruta", selected.data("ruta"));
            datos.append("TC", TC);
            
            funcion = 'opEdi_ajax_opEdiUpFiles';
            sweet = 'LCHB_SwalWait';
            LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
        }

    });

    $("#tbCajas").on('click', '.opEdi_showmdlSplitBox', function () {
        SplitCaja = 0;
        SplitCaja = $(this).data('cinv') - 1;
        opEdi_js_MostrarFacturasCajas($(this).data('idedimpcos'),$(this).data('krelfc'), $(this).data('caja'));
        $('#opEdi_spCaja').html("Caja " + $(this).data('caja'));
        $('#mdlSplitBox_IDIMPCOS').val($(this).data('idedimpcos'));
        $('#mdlSplitBox_krelfc').val($(this).data('krelfc'));
        $('#mdlSplitBox_caja').val($(this).data('caja'));
        $('#opEdi_mdlSplitBox').modal({ backdrop: 'static', keyboard: false });
        $('#opEdi_mdlSplitBox').modal('show');
    });

    $("#opEdi_tbSplitCajas").on('click', '.chkIsChecked', function () {
        if ($(this).data('status')==0) {
            if (SplitCount < SplitCaja) {
                SplitCount += 1;
                SplitFacturas[$(this).attr("id")] = { 0: $(this).data("krelfm"), '1': $(this).data("invoice") };

                $(this).data('status',1);
                document.getElementById($(this).attr('name')).className='btn btn-success btn-smy chkIsChecked';
                document.getElementById($(this).attr('name')).innerHTML='<i class="fa fa-fw fa-check"></i>';
            
            }
            else {
                Swal.fire({
                    title: 'Warning!',
                    text: '!No se pueden seleccionar todas las facturas!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                $(this).prop("checked", false);
            }
        }
        else {
            id = $(this).attr("id");
            SplitFacturas.splice(id, 2);
            SplitCount -= 1
            
            $(this).data('status',0);
            document.getElementById($(this).attr('name')).className='btn btn-danger chkIsChecked';
            document.getElementById($(this).attr('name')).innerHTML='<i class="fas fa-times"></i>';
        }
    });

    $("#opEdi_tbSplitCajas").on('click', '.opEdi_mdlSplitBox_DelInvoice', function () {
        Swal.fire({
            title: 'Desea eliminar  esta  factura  y mercancias?',
            text: "Una  vez  aplicado el cambio no podra revertirse!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar factura!'
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append("EliminarFactura", 'true');
                datos.append("KRELFM", $(this).data("krelfm"));
                funcion = 'opEdi_EliminarFactura';
                sweet = 'LCHB_SwalWait';
                LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
            }
        })
    });

    $(".btnSplitCajasProcesar").click(function () {
        var data = new Object();
        var datos = new FormData();
        switch ($('#opEdi_mdlSplitBox_slcAcciones').find("option:selected").val()) {
            case '1':
                if ($('#opEdi_mdlSplitBox_txtBox').val() != "" && $('#opEdi_mdlSplitBox_txtSCAC').val() != "") {
                    datos.append("NewCajaFactura", 'true');
                    data['IDEDIMPCOS']=$('#mdlSplitBox_IDIMPCOS').val();
                    data['Oldkrelfc']=$('#mdlSplitBox_krelfc').val();
                    data['OldCaja']=$('#mdlSplitBox_caja').val();
                    data['NewCaja']=$('#opEdi_mdlSplitBox_txtBox').val();
                    data['SCAC']=$('#opEdi_mdlSplitBox_txtSCAC').val();
                    funcion = 'opEdi_ajax_CambiarCaja';
                    //NOTA: 20220317 -> Necesito retornar el nuevo valor ID de la  caja

                    var json = JSON.stringify(data);
                    datos.append("datos",json);
                    sweet = 'LCHB_SwalWait';
                    LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
                }
                else {
                    Swal.fire({
                        title: 'Warning!',
                        text: '!Add the box and the SCAC in the textbox!',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
                break;
            case '2':
                var j = 0;
                var T_arr = [];
                if (Object.keys(SplitFacturas).length > 0) {
                    for (var i in SplitFacturas) {
                        T_arr[j] = { 0: SplitFacturas[i][0], 1: SplitFacturas[i][1] };
                        j += 1;
                    }
                    SplitFacturas = T_arr;
                    data["Facturas"] = SplitFacturas;
                    delete T_arr;
                    delete TSplitFacturas;
                    datos.append("SplitCajaFactura", 'true');
                    funcion = 'opEdi_js_SplitCajaFactura';

                    var json = JSON.stringify(data);
                    datos.append("datos",json);
                    sweet = 'LCHB_SwalWait';
                    LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
                }
                else {
                    Swal.fire({
                        title: 'Warning!',
                        text: '!Seleccione alguna Factura!',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
                break;
            case '3':
                if ($('#opEdi_mdlSplitBox_txtBox').val() != "" && $('#opEdi_mdlSplitBox_txtSCAC').val() != "" ) {
                    if (Object.keys(SplitFacturas).length > 0) {
                        var j = 0;
                        var T_arr = [];
                        for (var i in SplitFacturas) {
                            T_arr[j] = { 0: SplitFacturas[i][0], 1: SplitFacturas[i][1] };
                            j += 1;
                        }
                        SplitFacturas = T_arr;
                        data["Facturas"] = SplitFacturas;
                        delete T_arr;
                        delete TSplitFacturas;
                        datos.append("SplitNewCajaFactura", 'true');
                        data['NewCaja']=$('#opEdi_mdlSplitBox_txtBox').val();
                        data['SCAC']=$('#opEdi_mdlSplitBox_txtSCAC').val();
                        funcion = 'opEdi_js_SplitCajaFactura';

                        var json = JSON.stringify(data);
                        datos.append("datos",json);
                        sweet = 'LCHB_SwalWait';
                        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
                    }
                    else {
                        Swal.fire({
                            title: 'Warning!',
                            text: '!Select an Invoice!',
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
                    }
                }
                else {
                    Swal.fire({
                        title: 'Warning!',
                        text: '!Add the box and the SCAC in the textbox!',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }

                break;
            default:
                Swal.fire({
                    title: 'Warning!',
                    text: '!Select a task!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                break;
        }
    });

    $('.slcSplitBoxAcciones').change(function () {
        if ($(this).val() == "1" || $(this).val() == "3") {
            document.getElementById('opEdi_mdlSplitBox_txtBox').removeAttribute('readonly');
            document.getElementById('opEdi_mdlSplitBox_txtSCAC').removeAttribute('readonly');
        }
        else if ($(this).val() == "2") {
            document.getElementById('opEdi_mdlSplitBox_txtBox').setAttribute('readOnly', true);
            document.getElementById('opEdi_mdlSplitBox_txtSCAC').setAttribute('readOnly', true);
            $('#opEdi_mdlSplitBox_txtBox').val("");
            $('#opEdi_mdlSplitBox_txtSCAC').val("");
        }
        /*else if ($(this).val() == "3") {
            document.getElementById('opEdi_mdlSplitBox_txtBox').removeAttribute('readonly');
            document.getElementById('opEdi_mdlSplitBox_txtSCAC').removeAttribute('readonly');
        }*/
        else {
            document.getElementById('opEdi_mdlSplitBox_txtBox').setAttribute('readOnly', true);
            $('#opEdi_mdlSplitBox_txtBox').val("");
            $('#opEdi_mdlSplitBox_txtSCAC').val("");
        }
    });

    $("#tbCajas").on('click', '.opEdi_deleteBoxInvoice', function () {
        Swal.fire({
            title: 'Desea eliminar  esta  operacion junto a sus factyuras  y mercancias?',
            text: "Una  vez  aplicado el cambio no podra revertirse!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar Operacion!'
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append("EliminarCajaFactura", 'true');
                datos.append("IDEDIMPCOS",$(this).data('idedimpcos'));
                datos.append("KRELFC", $(this).data('krelfc'));
                datos.append("CAJA", $(this).data('caja'));
                funcion = 'opEdi_EliminarCajaFactura';
                sweet = '';
                LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
            }
        })
    });

    $('.btnProcess').click(function(){
        var Option = document.getElementById('slcProcessTaskOfOperations');
        var value= Option.options[Option.selectedIndex].value;
        if(Object.keys(DATA_EDI).length>0){
            switch (value){
            case '1':
                opedi_js_ProcesarCajas(value);
                break;
            case '2':
                opedi_js_ProcesarCajas(value);
                break;
            case '3':
                opedi_js_DeleteBoxInvoices(value);
                break;
            case '4':
                opedi_js_DeleteBoxInvoices(value);
                break;
            case '5':
                opedi_js_PrintManifestToSelectedEntrys();
                break;
            default:
                Swal.fire({
                    title: 'Warning!',
                    text: '!Select an option!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                break;
            };
        }
        else{
            Swal.fire({
                title: 'Warning!',
                text: '!No existen operaciones pendientes!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        
    });

    $("#tbCajas").on('click','.btnCheckToProccess',function(){
        //console.log($(this));
        //console.log(DATA_EDI);
        if($(this).data('status')==0){
            $(this).data('status',1);
            DATA_EDI[$(this).data('id')][7]=1;
            document.getElementById($(this).attr('name')).className='btn btn-success btn-smy btnCheckToProccess';
            document.getElementById($(this).attr('name')).innerHTML='<i class="fa fa-fw fa-check"></i>';
        }
        else{
            $(this).data('status',0);
            DATA_EDI[$(this).data('id')][7]=0;
            document.getElementById($(this).attr('name')).className='btn btn-warning btn-smy btnCheckToProccess';
            document.getElementById($(this).attr('name')).innerHTML='<i class="fas fa-dot-circle">';
        }
    });

    $('.opEdi_updateABITables').click(function(){
        Swal.fire({
            title: 'Actualizacion de  catalogos.',
            text: "El sistema estara  fuera  de servicio durante el proceso de  actualizacion!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, actualizar!'
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append("updateABITables", 'true');
                funcion = 'opEdi_ajax_updateABITables';
                sweet = 'LCHB_SwalWait';
                LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
            }
        })
    });

    $("#opEdi_mdlImporters_tbImporters").on('click','.opEdi_mdlImporters_ImporterDetail',function(){
        $("#opEdi_mdlImporters_lblImporterName").text("Importer: " + $(this).data('impname'));
        $("#opEdi_mdlImporters_hdIDEDIMPCOS").val($(this).data('idedimpcos'));
        var datos = new FormData();
        datos.append("ImporterDetail", 'true');
        datos.append("IDEDIMPCOS",$(this).data('idedimpcos'));
        funcion = 'opEdi_ajax_ImporterDetail';
        sweet = '';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
    });

    $("#opEdi_mdlUploadCatalog_btnUploadFile").click(function(){
        var file_data = $("#opEdi_mdlUploadCatalog_file").prop("files")[0];
        var datos = new FormData();
        datos.append("UploadFile", 'true');
        datos.append("File",file_data);
        funcion = 'opEdi_ajax_UploadFIle';
        sweet = '';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
    });

    $("#opEdi_mdlCreateClientes_slcRangeType").change(function () {
        var TipeRange = document.getElementById('opEdi_mdlCreateClientes_slcRangeType');
        if (TipeRange.options[TipeRange.selectedIndex].value == 0) {
            document.getElementById("opEdi_mdlCreateClientes_txtRangeStart").readOnly = true;
            document.getElementById("opEdi_mdlCreateClientes_txtRangeLast").readOnly = true;
        }
        else {
            document.getElementById("opEdi_mdlCreateClientes_txtRangeStart").readOnly = false;
            document.getElementById("opEdi_mdlCreateClientes_txtRangeLast").readOnly = false;
        }
    });

    $('.opEdi_mdlPrintManifest_chkShowOptionManifest').change(function(){
        var Opcion = document.getElementById($(this).attr('id'));
        console.log(Opcion);
        if (Opcion.value == 0) {
            var entry=false;
            var firts=true;
            var last=true;
            var slcImporter=true;
            var tbEntriesImporter=true;
            B_Manifest=0;
        }
        else if(Opcion.value == 1){
            var entry=true;
            var firts=false;
            var last=false;
            var slcImporter=true;
            var tbEntriesImporter=true;
            B_Manifest=1;
        }
        else{
            var entry=true;
            var firts=true;
            var last=true;
            var slcImporter=false;
            var tbEntriesImporter=false;
            B_Manifest=2;
        }
        document.getElementById("opEdi_mdlPrintManifest_entry").readOnly=entry;
        document.getElementById("opEdi_mdlPrintManifest_firts").readOnly=firts;
        document.getElementById("opEdi_mdlPrintManifest_last").readOnly=last;
        //document.getElementById("opEdi_mdlPrintManifest_slcImporter").disabled=slcImporter;
        //document.getElementById("opEdi_mdlPrintManifest_tbEntriesImporter").readOnly=tbEntriesImporter;
    });

    $("#opEdi_mdlPrintManifest_btnPrintFiles").click(function(){
        console.log(B_Manifest);
        if((B_Manifest==1 &&  ($("#opEdi_mdlPrintManifest_firts").val()=='' || $("#opEdi_mdlPrintManifest_last").val() ==''))  || (B_Manifest==0 && $("#opEdi_mdlPrintManifest_entry").val()=='') ){
            Swal.fire({
                title: 'Warning!',
                text: '!Debe llenar los datos!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else if( B_Manifest==1 && $("#opEdi_mdlPrintManifest_firts").val()>=$("#opEdi_mdlPrintManifest_last").val()){
            Swal.fire({
                title: 'Warning!',
                text: '!El Rango Inicial no debe ser mayor al rango final!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else {
            var data = new Object();
            switch (B_Manifest){
                case 1:
                    data['Type']="Range";
                    data['Data']=[$("#opEdi_mdlPrintManifest_firts").val(),$("#opEdi_mdlPrintManifest_last").val()]
                    break;
                case 2:
                    data['Type']="SelectByImporter";
                    data['Data']=A_Manifest;
                    break;
                default:
                    data['Type']="Entry";
                    data['Data']=[$("#opEdi_mdlPrintManifest_entry").val()]
                    console.log(data);
                    break;
            }
            var datos = new FormData();
            var json = JSON.stringify(data);
            datos.append("PrintManifest", 'true');
            datos.append("Data",json);
            funcion = 'opEdi_ajax_PrintManifest';
            sweet = '';
            LCHB_ajax(urlAjaxEdi, datos, funcion, sweet); 
        }
        
    });

    $('.opEdi_mdlPrintManifest_btnclosemdl').click(function(){
        $('#opEdi_mdlPrintManifest_firts').val('');
        $('#opEdi_mdlPrintManifest_last').val('');
        $('#opEdi_mdlPrintManifest_entry').val('');
        $('#opEdi_mdlPrintManifest').modal('hide');
    });

    $("#opEdi_mdlPrintManifest_btnUpdateManifestTable").click(function(){
        Swal.fire({
            title: 'Actualizacion de Manifiestos.',
            text: "El sistema estara  fuera  de servicio durante el proceso de  actualizacion!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, actualizar!'
        }).then((result) => {
            if (result.isConfirmed) {
               var datos = new FormData();
                datos.append("UpdateManifestTable", 'true');
                funcion = 'opEdi_ajax_UpdateManifestTable';
                sweet = '';
                LCHB_ajax(urlAjaxEdi, datos, funcion, sweet); 
            }
        })

        
    });

    $('.opedis_readNotificaciones').click(function(){
        var datos = new FormData();
        datos.append("ReadNotifications", 'true');
        funcion = 'opEdi_ajax_ReadNotifications';
        sweet = '';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet); 
    });

    $('.mldAddTC_btnAddExchRate').click(function(){
        var datos = new FormData();
        datos.append("AddExchRate", 'true');
        var Currency = document.getElementById('mldAddTC_slcCurrency');
        datos.append("MONEDA", Currency.options[Currency.selectedIndex].value);
        datos.append("VALOR_MONEDA", $("#mldAddTC_txtTC").val());
        funcion = 'opEdi_ajax_AddExchRate';
        sweet = '';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet); 
    });

    $('.mldAddTC_btnResetExchRate').click(function(){
        $('#mldAddTC_txtTC').val('');
        $('#mldAddTC_slcCurrency').empty().append("<option value=''>SELECT CURRENCY</option><option value='MX'>MX</option><option value='EUR'>EUR</option>");
        $('#mldAddTC').modal('hide');
    });

    $('.opEdi_showmldAddTC').click(function(){
        opedis_js_MostrarAddExchRate();
    });

    $('#opEdi_mdlCreateImporters_btnAddXML').click(function(){
        if($('#opEdi_mdlCreateImporters_txtEmisor').val()!=""){
            var XML= new Object();
            var Emisor = $('#opEdi_mdlCreateImporters_txtEmisor').val();
            var Receptor =  $('#opEdi_mdlCreateImporters_txtReceptor').val();
            $('#opEdi_mdlCreateImporters_tblnewxml tr:last').after('<tr class="table-info"><td>New</td><td>'+Emisor+'</td><td>'+Receptor+'</td></tr>');
            $('#opEdi_mdlCreateImporters_txtEmisor').val('');
            $('#opEdi_mdlCreateImporters_txtReceptor').val('');
            $('#opEdi_mdlCreateImporters_txtEmisor').focus();
            XML["Receptor"]=Receptor;
            XML["Emisor"]=Emisor;
            NewXML.push(XML);
        }
        else{
            Swal.fire({
                title: "Warnign!",
                text: "Llene los campos. ",
                icon: "warning",
                confirmButtonText: "Cerrar"
                });
        }
        
    });
    
    $("#opEdi_mdlCopyImporterData_tbImporters").on('click','.opEdi_mdlCopyImporterData_TakeData',function(){
        console.log($(this).data('idedimpcos'));
        var datos = new FormData();
        datos.append("copyImporterData", 'true');
        datos.append("IDEDIMPCOS", $(this).data('idedimpcos'));
        funcion = 'opEdi_ajax_copyImporterData';
        sweet = '';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet); 
    });

    $('#opEdi_mldGenerarEdi_chkMgrProd').change(function(){
        console.log($("#txtmldGenerarEdi_Importer").data('tcat'),$("#txtmldGenerarEdi_Caja").data('krelfc'),$("#txtmldGenerarEdi_Caja").val())
        
        var datos = new FormData();
        datos.append("opEdiFillEDI_MergeLines", true);
        datos.append("caja", $("#txtmldGenerarEdi_Caja").val());
        datos.append("krelfc", $("#txtmldGenerarEdi_Caja").data('krelfc'));
        datos.append("TipoCat",$("#txtmldGenerarEdi_Importer").data('tcat'));
        //Revisar  si es necesario la  clave ID del registro en la tabla
        //Inicio Bloque de Variables Globales
        datos.append("IDEDIMPCOS", IDEDIMPCOS);
        datos.append("IMP_ABI_KEY", IMP_ABI_KEY);
        datos.append("CONS_ABI_KEY", CONS_ABI_KEY);
        datos.append("VistaPanel", VistaPanel);
        //Fin Bloque de Variables Globales
        if($(this).checked == true){
            datos.append("MERGE",1);
        }
        else{
            datos.append("MERGE",0);
        }
        funcion = 'opEdi_ajax_opEdiFillEDI_MergeLines';
        sweet = 'LCHB_SwalWait';
        LCHB_ajax(urlAjaxEdi, datos, funcion, sweet);
    });
});

