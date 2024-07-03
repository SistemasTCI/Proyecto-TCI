
function sysPermisos_js_Inicializar(){
    sysPermisos_js_InicializarTbRoles('sysPermisos_SwalWait');
    sysPermisos_js_InicializarTbModulos('sysPermisos_SwalWait');
}
function sysPermisos_js_InicializarTbRoles(sweet){
    var datos= new FormData();
    datos.append("InicializarTbRoles",'true');
    funcion= 'sysPermisos_js_LlenadoTbRoles';
    sysPermisos_js_ajax(datos,funcion,sweet);
}
function sysPermisos_js_ajax(datos,funcion,sweet){
    $.ajax({
        url:"ajax/sysPermisos.ajax.php",
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
                window[funcion](datos);
            }
            else{
                console.log('Error:'+datos);
                alert(datos);
            }
        },
        error: function(datos,errorThrown){
            console.log("Error:: ",datos);  
        }
    });
}
function sysPermisos_SwalWait(){
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
}
function sysPermisos_js_InicializarTbModulos(sweet){
    console.log('entro');
    var datos= new FormData();
    datos.append("InicializarTbModulos",'true');
    funcion= 'sysPermisos_js_LlenadoTbModulos';
    sysPermisos_js_ajax(datos,funcion,sweet);
}
function sysPermisos_MostrarPermisos(rol,sweet){
    var datos= new FormData();
    datos.append("MostrarPermisos",'true');
    datos.append("Rol",rol);
    funcion= 'sysPermisos_MostrarPermisosResultado';
    sysPermisos_js_ajax(datos,funcion,sweet);
}
function sysPermisos_js_ajax_return(datos,funcion,sweet){
    return $.ajax({
        url:"ajax/sysPermisos.ajax.php",
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
            /*if(typeof datos!==undefined){
                var ejemplo= window[funcion](datos);
                console.log(ejemplo);
                return ejemplo;
            }
            else{
                console.log('Error:'+datos);
                alert(datos);
            }*/
        },
        error: function(datos,errorThrown){
            console.log("Error:: ",datos);  
        },
    }).responseText;
    /*
    }).done(function(datos){
        Swal.close();
        if(typeof datos!==undefined){
            var ejemplo= window[funcion](datos);
            console.log(ejemplo);
            return ejemplo;
        }
        else{
            console.log('Error:'+datos);
            alert(datos);
        }
    });*/
}
function sysPermisos_js_InicializarReponse(datos){
    sysPermisos_js_InicializarTbRoles(datos['Roles']);
    sysPermisos_js_InicializarTbModulos(datos['Modulos'])
}
function sysPermisos_js_LlenadoTbRoles(datos){
    if(datos!=""){
        $('#sysPermisos_tblRoles').DataTable({
          "destroy":true,
          "data":datos['Roles'],
          "responsive": true,
          "select":{style:'single'},
          "searching":      false,
          "paging":         false,
        });
    }
    else{
          console.log('Eror:'+datos);
          alert(datos);
    }
}
function sysPermisos_js_LlenadoTbModulos(datos){
    if(datos!=""){
        $('#sysPermisos_tblModulos').DataTable({
          "destroy":true,
          "data":datos['Modulos'],
          "responsive": true,
          "select":{style:'single'},
          "searching":      false,
          "paging":         false,
        });
    }
    else{
          console.log('Eror:'+datos);
          alert(datos);
    }
}
function sysPermisos_AgregarRolResultado(resultado){
    if(resultado=="ok"){
        Swal.fire({
            title: 'Success!',
            text: '!Registro Exitoso!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        $('#sysPermisos_RolDescripcion').val('');
        $('#sysPermisos_Rol').val('')
        sysPermisos_js_Inicializar();
    }
}
function sysPermisos_AgregarModuloResultado(resultado){
    if(resultado=="ok"){
        Swal.fire({
            title: 'Success!',
            text: '!Registro Exitoso!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        $('#sysPermisos_ModuloDescripcion').val('');
        $('#sysPermisos_ModuloNomPagina').val('');
        $('#sysPermisos_Modulo').val('')
        sysPermisos_js_Inicializar();
    }
}
function sysPermisos_RelPermiso(resultado){
    if(resultado=="ok"){
        Swal.fire({
            title: 'Success!',
            text: '!Registro Exitoso!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        //sysPermisos_MostrarPermisos($('#sysPermisos_RelPermisos').val(),'');
    }
}
function sysPermisos_MostrarPermisosResultado(resultado){
    if(resultado!=""){
        $('#sysPermisos_tblModulos').DataTable({
          "destroy":true,
          "data":resultado['MostrarPermisos'],
          "responsive": true,
          "select":{style:'single'},
          "searching":      false,
          "paging":         false,
        });
    }
    else{
          console.log('Eror:'+datos);
          alert(datos);
    }
}
function sysPermisos_ActPermiso(resultado){
    if(resultado=="ok"){
        Swal.fire({
            title: 'Success!',
            text: '!Permiso Activado!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    }
}

function sysPermisos_LlenarMenus(datos){
    var CMP=Object.keys(datos['MenusPadre']).length;
    var CMH=Object.keys(datos['SinMenu']).length;
    for(var i=0;i<CMP;i++){
        $("#sysPermisos_slcMenusPadre").append("<option value='"+datos['MenusPadre'][i]['IDMenu']+"'>"+datos['MenusPadre'][i]['MenuName']+"</option>");
    }
    for(var i=0;i<CMH;i++){
        $("#sysPermisos_slcMenusHoja").append("<option value='"+datos['SinMenu'][i]['IdModulo']+"'>"+datos['SinMenu'][i]['Modulo']+"</option>");
    }
}
function sysPermisos_js_selectmenu(valor){
    var select = document.getElementById("sysPermisos_slcMenusHoja");
    var input = document.getElementById("sysPermisos_txtSubmenu");
    if(valor==0){
        select.style.visibility = 'hidden';
        input.style.visibility = 'visible';
        input.style.width = '70%';
    }
    else{
        select.style.visibility = 'visible';
        input.style.visibility = 'visible';
        input.style.width = '70%';
    }
}

function sysPermisos_AgregarMenu(resultado){
    if(resultado=="ok"){
        Swal.fire({
            title: 'Success!',
            text: '!Registro Exitoso!',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
        $('#sysPermisos_mdlMenus').find('form').trigger('reset');
        sysPermisos_js_selectmenu(0);
    }
}

$(document).ready(function(){

    $('.ViewmdlRoles').click(function(){
        $('#sysPermisos_mdlRoles').modal({backdrop: 'static', keyboard: false});
        $('#sysPermisos_mdlRoles').modal('show');
    });
    $('.ViewmdlModulos').click(function(){
        $('#sysPermisos_mdlModulos').modal({backdrop: 'static', keyboard: false});
        $('#sysPermisos_mdlModulos').modal('show');
    });
    $('.ViewmdlMenus').click(function(){
        var datos= new FormData();
        datos.append("LlenarMenus",'true');
        funcion= 'sysPermisos_LlenarMenus';
        sweet='';
        sysPermisos_js_ajax(datos,funcion,sweet);
        $('#sysPermisos_mdlMenus').modal({backdrop: 'static', keyboard: false});
        $('#sysPermisos_mdlMenus').modal('show');
    });
    $('.sysPermisos_AgregarRol').click(function(){
        var resultado = {};
        var datos= new FormData();
        datos.append("AddRol",'true');
        datos.append("Rol",$('#sysPermisos_Rol').val());
        datos.append("Descripcion",$('#sysPermisos_RolDescripcion').val());
        funcion= 'sysPermisos_AgregarRolResultado';
        sweet='';
        resultado=sysPermisos_js_ajax(datos,funcion,sweet);
    });
    $('.sysPermisos_AgregarModulo').click(function(){
        var resultado = {};
        var datos= new FormData();
        datos.append("AddModulo",'true');
        datos.append("Modulo",$('#sysPermisos_Modulo').val());
        datos.append("Pagina",$('#sysPermisos_ModuloNomPagina').val());
        datos.append("Descripcion",$('#sysPermisos_ModuloDescripcion').val());
        funcion= 'sysPermisos_AgregarModuloResultado';
        sweet='';
        resultado=sysPermisos_js_ajax(datos,funcion,sweet);

    });
    $('#sysPermisos_tblRoles').on('click','.sysPermisos_SelecionaRol',function(){
        $('#sysPermisos_RelPermisos').val($(this).data('rol'));
        sysPermisos_MostrarPermisos($(this).data('rol'),'');
    });
    $('#sysPermisos_tblModulos').on('change','.sysPermisos_SelectPermiso',function(){
        var datos= new FormData();
        var select=$(this).find("option:selected");
        datos.append("RelPermiso",'true');
        datos.append("Rol",$('#sysPermisos_RelPermisos').val());
        datos.append("Modulo", $(this).data('modulo'));
        datos.append("Activo", $(this).data('activo'));
        datos.append("Permiso",select.val());
        funcion= 'sysPermisos_RelPermiso';
        sweet='sysPermisos_SwalWait';
        sysPermisos_js_ajax(datos,funcion,sweet);
    });
    $("#sysPermisos_tblModulos").on('click','.sysPermisos_Activar',function(){
        var datos= new FormData();
        datos.append("ActPermiso",'true');
        datos.append("Rol",$('#sysPermisos_RelPermisos').val());
        datos.append("Modulo", $(this).data('modulo'));
        datos.append("Estado",$(this).data('estado') );
        funcion= 'sysPermisos_ActPermiso';
        sweet='sysPermisos_SwalWait';
        sysPermisos_js_ajax(datos,funcion,sweet);
    });
    $('.sysPermisos_SelecTipoMenu').change(function(){
        sysPermisos_js_selectmenu($(this).val());
    });
    $('.sysPermisos_mdlMenusReset').click(function(){
        $('#sysPermisos_mdlMenus').find('form').trigger('reset');
        $('#sysPermisos_slcMenusHoja').empty();
        $('#sysPermisos_slcMenusPadre').empty();
        sysPermisos_js_selectmenu(0);
        $('#sysPermisos_mdlMenus').modal('hide');
    });
    $('.sysPermisos_AgregarMenu').click(function(){
        if($('#sysPermisos_txtSubmenu').val()==''){
            Swal.fire({
                title: 'Warning!',
                text: '!Debe  escribir el nombre del menu!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
        else{
            var datos= new FormData();
            datos.append("AgregarMenu",'true');
            datos.append("Padre",$("#sysPermisos_slcMenusPadre").find("option:selected").val());
            datos.append("Icono",$("#sysPermisos_txtIcono").val());
            funcion= 'sysPermisos_AgregarMenu';
            sweet='sysPermisos_SwalWait';
            if($("#sysPermisos_slcSubMenu").find("option:selected").val()==0)
            {
                datos.append("Hoja", '');
            }
            else{
                datos.append("Hoja", $("#sysPermisos_slcMenusHoja").find("option:selected").val());
            }
            datos.append("Nombre", $("#sysPermisos_txtSubmenu").val());
            sysPermisos_js_ajax(datos,funcion,sweet);
        }
    });

});

