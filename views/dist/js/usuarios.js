function usuarios_js_Inicializar(){
    var datos=new FormData();
    datos.append("Inicializar","true");
    $.ajax({
        url:"ajax/usuarios.ajax.php",
        method:"POST",
        data:datos,
        cache:false,
        contentType:false,
        processData:false,
        dataType:"json",
        success:function(datos){
            var cont=Object.keys(datos).length;
            for(var i=0;i<cont;i++){
                var IdRol=datos[i]["IdRol"];
                var RolName=datos[i]["Rol_Name"];
                $("#nuevoPerfil").append("<option value='"+RolName+"'>"+RolName+"</option>");
                $("#editarPerfil").append("<option value='"+RolName+"'>"+RolName+"</option>");
            }
        }
    }) 
} 
 
 /*=============================================
    AGREGAR FOTO
    =============================================*/

$(".nuevaFoto").change(function(){
    var imagen=this.files[0];
    //console.log("imagen",imagen["type"]);

    //Validar el tamaño de la imagen

	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/
    if(imagen["type"]!="image/jpeg" && imagen["type"]!="image/png"){
        $(".nuevaFoto").val("");
        Swal.fire({
            title: "Error al subir la  imagen!",
            text: "!La imagen debe  estar en formato jpg o png!",
            icon: "error",
            confirmButtonText: "Cerrar"

        });
    }
    else if(imagen["size"]>2000000){
        $(".nuevaFoto").val("");
        Swal.fire({
            title: "Error al subir la  imagen!",
            text: "!La imagen no debe pasar de  2Mb!",
            icon: "error",
            confirmButtonText: "Cerrar"

        });
    }
    else{
        var datosImagen= new FileReader;
        datosImagen.readAsDataURL(imagen);
        $(datosImagen).on("load",function(event){
            var rutaImagen=event.target.result;
            $(".previsualizar").attr("src",rutaImagen);
        })

    }

})

 /*=============================================
    EDITAR USUARIO
    =============================================*/


$(".btnEditarUsuario").click(function(){
    
    var idUsuario=$(this).attr("idUsuario");
    //console.log("idUsuario",idUsuario);
    var datos= new FormData();
    datos.append("idUsuario",idUsuario);
    $.ajax({
        url:"ajax/usuarios.ajax.php",
        type:"POST",
        data:datos,
        cache:false,
        contentType:false,
        processData:false,
        dataType:"json",
        success:function(respuesta){
            //console.log("idUsuario",respuesta)
            $("#editarUsuario").val(respuesta['Usuario']);
            $("#UsuarioPerfil").html(respuesta['Perfil']);
            $("#UsuarioPerfil").val(respuesta['Perfil']);
            $("#passwordActual").val(respuesta['Password']);
            $("#fotoActual").val(respuesta['Foto']);
            if(respuesta['Foto']!=""){
                $(".previsualizar").attr("src",respuesta['Foto']);
            }
            /*else{
                alert('No se cargo foto '+respuesta['Foto']);
                //$(".previsualizar").attr("src", rutaImagen);
            }*/


        },
        //error: function(respuesta,errorThrown){
        error: function(respuesta,errorThrown){
            //alert('custom message. Error: ' + errorThrown);
            console.log("Error; ",respuesta);
           
        }
    })
    //console.log("Datos",$_POST["idUsuario"]);
})

 /*=============================================
    ACTIVAR/DESACTIVAR USUARIOS
    =============================================*/

$(".btnActivar").click(function(){
    var idUsuario=$(this).attr("idUsuario");
    console.log("idUsuario",idUsuario);
    var estadoUsuario=$(this).attr("estadoUsuario");
    var datos =new FormData();
    datos.append("activarId",idUsuario);
    datos.append("activarUsuario",estadoUsuario);
    $.ajax({
        url:"ajax/usuarios.ajax.php",
        method:"POST",
        data:datos,
        cache:false,
        contentType:false,
        processData:false,
        dataType:"json",
        sucess:function(respuesta){

        },
        error: function(respuesta,errorThrown){
            //alert('custom message. Error: ' + errorThrown);
            console.log("Error; ",respuesta);
           
        }
    })
})


 /*=============================================
    VALIDAR  USUARIO DUPLICADO
    =============================================*/
$("#nuevoUsuario").change(function(){
    $(".alert").remove();
    var usuario=$(this).val();
    var datos=new FormData();
    datos.append("validarUsuario",usuario);
    $.ajax({
        url:"ajax/usuarios.ajax.php",
        method:"POST",
        data:datos,
        cache:false,
        contentType:false,
        processData:false,
        dataType:"json",
        success:function(respuesta){
        if(respuesta !=false){
            $("#nuevoUsuario").parent().after("<div class='alert alert-warning'>El usuario ya existe!!!</div>");
            $("#nuevoUsuario").val("");
        }
        else{ 
            $("#nuevoUsuario").parent().after("<div class='alert alert-warning'  style='display: none'>Usuario Disponible</div>");
            //$("#nuevoUsuario").val("");
        }

        //console.log("Retorno",retorno);
        /*if(respuesta!=false){
            //console.log("Entro al Verdadero",respuesta," ",retorno);
            //alert('Entro al verdadero, valores:  '+respuesta['Usuario']+respuesta['Perfil']+respuesta['Password']+respuesta['Foto']);
            //$("#nuevoUsuario").parent().after("<div class='alert alert-warning'>Entro al verdadero </div>");
            //$("#nuevoUsuario").val("");
             $("#nuevoUsuario").parent().after("<div class='alert alert-warning'>El usuario ya existe!!!</div>");
            $("#nuevoUsuario").val("");
        }
        else{
               
            $("#nuevoUsuario").parent().after("<div class='alert alert-warning'  style='display: none'>Usuario Disponible</div>");
                
        }*/

        }

    })
})

 /*=============================================
    ELIMINAR USUARIO
    =============================================*/
$(".btnEliminarUsuario").click(function(){
    var idUsuario=$(this).attr("idUsuario");
    var fotoUsuario=$(this).attr("fotoUsuario");
    var usuario=$(this).attr("Usuario");
    swal.fire({
        title:'Se  eliminara el contacto seleccionado!!!',
        text: 'Estas  seguro de eliminarlo?',
        icon: 'warning',
        showCancelButtonColor:'#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Borrar Usuario'
    }).then((result)=>{
        if(result.value){
            window.location="index.php?ruta=usuarios&idusuario="+idUsuario+"&usuario="+usuario+"&fotousuario="+fotoUsuario;
        }

    })
})

$(document).ready(function(){

    
});

/*$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();

});*/
