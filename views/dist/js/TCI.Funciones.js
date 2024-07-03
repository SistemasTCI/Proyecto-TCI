function TCI_ajax(urlAjax,datos,funcion,sweet){
    console.log('Test');
    $.ajax({
        url:urlAjax,
        type:"POST",
        beforeSend : function(){
            if(sweet!=''){
                window[sweet]();
            }
        },
        data:datos,
        //async: false,
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
            Swal.close();
            console.log("Error:: ",errorThrown); 
            console.log(datos);  
        }
    });
}
function TCI_ajax_withoutReturn(urlAjax,datos){
    $.ajax({
        url:urlAjax,
        type:"POST",
        data:datos,
        cache:false,
        contentType:false,
        processData:false,
        dataType:"json",
        error: function(datos,errorThrown){
            console.log("Error:: ",datos);  
        }
    });
}
function TCI_ajax_simetric(urlAjax,datos,funcion,sweet){
    $.ajax({
        url:urlAjax,
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
            Swal.close();
            console.log("Error:: ",errorThrown);  
        }
    });
}
function TCI_SwalWait(){
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
function TCI_FillTable(Tabla,data,search,paging,responsive,info){
    if(search==null){
        search=true;
    }
    if(paging==null){
        paging=true;
    }
    if(responsive==null){
        responsive=true;
    }
    if(info==null){
        info=true;
    }
    $('#'+Tabla).DataTable({
        //dom: 'Bfrtip',
        "destroy":true,
        "data":data,
        "responsive": responsive,
        "select":{style:'single'},
        "searching":      search,
        "paging":         paging,
        "processing": true,
        "bInfo" : info,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'},
            
       // buttons: [
        //    'copy', 'csv', 'excel', 'pdf', 'print'
        //]
    });
}
$('.TCI_ValidarCamposVal').change(function(){
    var field = document.getElementById($(this).attr('id'));
    if($(this).val()==""){
        field.style.border = '3px solid red'; 
    }
    else{
        field.style.border = '3px solid green'; 
    }
    
});
$('.TCI_ValidarCamposTexto').change(function(){
    var field = document.getElementById($(this).attr('id'));
    if($(this).val()==""){
        field.style.border = '3px solid red'; 
    }
    else{
        field.style.border = '3px solid green'; 
    }
    
});