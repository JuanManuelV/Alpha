$(document).ready(function() {

    
    
    if($('#TipoUsuario').val()==3){
        $('#pnlOperador').hide();
        $('.DerivarAOperador').hide();
    }
    //#################################################
    //                  OPERADOR
    //#################################################
    $('.EditaOperador').click(function() {
                    $.ajax({
                        type : 'POST',
                        url : $(this).attr("href"),
                        dataType : 'json',
                        success : function(data){ 
                            $('#CodOperadorMod').val(data.OperaCod);
                            $('#NomOperadorMod').val(data.NomOpe);
                            $('#rutOperadorMod').val(data.rutOperador);
                            $('#ApellOperadorMod').val(data.OperaApell);
                            $('#DirOperadorMod').val(data.OperaDir);
                            $('#TelefOperadorMod').val(data.OperaTel);
                            $("#RegionModOperador option:contains(" + data.OperaRegion + ")").attr('selected', 'selected');
                            $('#UsuarioOperadorMod').val(data.OperaUsu); 
                            $('#ClaveOperadorMod').val(data.OperaClave);

//                             
                            $('#mdlModificaOperador').modal();
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {alert("error: "+errorThrown);}
                    });
                    return false;
    });
    
    //#################################################
    //                  DEUDOR
    //#################################################
    $('.boton_editar').click(function() {
        $.ajax({
            type : 'POST',
            url : $(this).attr("href"),
            dataType : 'json',
                        
            success : function(data){    
                $('#ApeDeudorMod').val(data.ApeDeudor);
                $('#RutDeudorMod').val(data.RutDeudor);
                $('#NomDeudorMod').val(data.NomDeudor);
                $('#DirDeudorMod').val(data.dire);
                $('#CiudadDeudorMod').val(data.ciudad);
                $('#ComunaDeudorMod').val(data.com);
               // $('#TelDeudorMod').val(data.telef);
//                $('#MailDeudorMod').val(data.mail);
                $('#TipoDeudorMod').val(data.Tipo);
                $('#CodDeudorMod').val(data.cod);      
                            
                if(data.Tipo=="Juridica"){
                    $('#ApeDeudorMod').hide();
                    $('#ApeDeudorLab').hide();
                }else{
                    $('#ApeDeudorMod').show();
                    $('#ApeDeudorLab').show();
                }
                             
                $('#TipoRutDeudorMod option:selected').text(data.txtTiporut);          
                $('#TipoRutDeudorMod').prop("disabled", true);
                                                  
                $('#myModal3').modal();
            },               
            error : function(XMLHttpRequest, textStatus, errorThrown) {alert("error"+errorThrown);}
        });
        return false;
    });
    
    
    
    
    //#################################################
    //                  DOCUMENTO
    //#################################################
   
    
   
        
    //#################################################
    //              DERIVAR DOCUMENTO
    //#################################################
    
    $('#EmpCobCombo').bind("change", function() {
        if($('#EmpCobCombo').val()!=-1){                    
            $('#EstadoDerivacion').prop('checked', true);
            
        }else{
            $('#EstadoDerivacion').prop('checked', false);
        }
    });
    
    
    
    
    
    
    //-------------------------------------
    //mostrar los operadores para derivar documento
    $('.DerivarAOperador').click(function(){
        $('#CodDocumento').val($(this).attr("id"));
        $('#mdlDerivarOperador').modal();

    });
    
    
    
 
    
});