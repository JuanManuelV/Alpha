$(document).ready(function() {
    $('#btnEstadosAcciones').click(function(){
        $('#mdlEstadosAccion').modal();
    });
    

    
    //      JS CON AJAX PARA EDICION DE UN CLIENTE
    $('.btnEditarCliente').click(function() {
        $.ajax({
            type : 'POST',
            url : $(this).attr("href"),
            dataType : 'json',
            success : function(data){
                $('#CodClienteMod').val(data.codigo);
                $('#txtNomClienteMod').val(data.nombre);
                $('#rutClienteMod').val(data.rut);
                //$('#seqCiudadCliente').val(data.ciudad);
                $('#txtDirClienteMod').val(data.direccion);
                $('#txtTelClienteMod').val(data.telefono);
                $('#fechaCreacion').val(data.creacion);
                //$('#seqEstadoCliente').val(data.estado);
                $('#TipoCliente').val(data.tipo);
                
                $('#mdlModificarCliente').modal();
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {alert("error"+errorThrown);}
        });
        return false;            
    });
    
    
     //      JS CON AJAX PARA EDICION DE UNA EMRPESA
    $('.btnEditarEmpresa').click(function() {
        $.ajax({
            type : 'POST',
            url : $(this).attr("href"),
            dataType : 'json',
            success : function(data){
                $('#CodEmpresaMod').val(data.codigo);
                $('#txtNomEmpresaMod').val(data.nombre);
                $('#rutEmpresaMod').val(data.rut);
                //$('#seqCiudadCliente').val(data.ciudad);
                $('#txtDirEmpresaMod').val(data.direccion);
                $('#txtTelEmpresaMod').val(data.telefono);
                $('#dtFechaCreacion').val(data.creacion);
                //$('#seqEstadoCliente').val(data.estado);
                $('#TipoOperador').val(data.tipo);
                
                $('#mdlModificarEmpresa').modal();
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {alert("error"+errorThrown);}
        });
        return false;            
    });
   
    
    
});