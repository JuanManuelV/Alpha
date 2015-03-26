$(document).ready(function() {
    $('.DerivarAdmin').hide();
    $('#TablaAcciones').tablesorter();
    
    if($('#TipoUsuario').val()==3){
        $('#pnlOperador').hide();
        $('.btnDevolverCliente').hide();
        $('.DerivarAOperador').hide();
        $('.DerivarAdmin').show();
    }
    
    
    

    
    
function addCommas(nStr)
            {
            	nStr += '';
            	x = nStr.split('.');
            	x1 = x[0];
            	x2 = x.length > 1 ? '.' + x[1] : '';
            	var rgx = /(\d+)(\d{3})/;
            	while (rgx.test(x1)) {
            		x1 = x1.replace(rgx, '$1' + '.' + '$2');
            	}
            	return x1 + x2;
            }
   
    
    $('.btnEditarDocumento').click(function() {
        //alert($(this).attr("href"));    
        $('#TablaHistorial').empty();
        $('#OperaCobranza').attr("disabled", true);
        $('#EmpCobCombo').val(0);
        $('#OperaCobranza').val(0);
        $('#EstadoDerivacion').prop('checked', false);
        $('#OperadorSeleccionado').prop('checked', false);
        $('#GuardaModAccion').val('Guardar');
        $('#GuardaModAccion').prop("disabled",false);                                        
        $('#btnDerivar').attr("disabled", false);  
        //alert($(this).attr("href"));
        $.ajax({
            type : 'POST',
            url : $(this).attr("href"),
            dataType : 'json',
            success : function(data){
                //---   CALCULA LOS DIAS DE MORA (FECHA VENCIMIENTO-DIA ACTUAL)-------
                var d = new Date();
                var strDate = d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear();
                            
                  //---  CALCULA LOS REMANENETES  -------
                var TotalAbono,Remanente,ValorDeuda;
                            
                ValorDeuda=data.precio;                                                     
                            
                if(data.TotalAbonos[0].sumaabono == null || data.TotalAbonos[0].sumaabono == ''){
                    TotalAbono = 0;
                }else {            
                    TotalAbono = data.TotalAbonos[0].sumaabono;
                }
                            
                Remanente=ValorDeuda-TotalAbono;
                //alert(data.TotalAbonos[0].sumaabono);
                
                //alert(TotalAbono);
                //alert(coddoc);
                //-------------------------------------
                $('#MontoAbono_doc').val("$ "+ addCommas(TotalAbono));
                $('#MontoRemanente_doc').val("$ "+ addCommas(Remanente));
                                        
                            
                //$('#MontoAbono_doc').val("$ "+ TotalAbono);
                //$('#MontoRemanente_doc').val("$ "+ Remanente);
                
                 $('#CodigoDocumento').val(data.coddoc);
                 $('#Cod_doc').val(data.cod);
                 $('#accion_doc').val(data.accion);
                 $('#deudor_doc').val(data.deudor);
                 $('#Tipo_doc').val(data.tipodoc);
                 $('#ConPago_doc').val(data.conpago);
                 $('#pgest_doc').val(data.pgestion);
                 $('#Precio_doc').val("$ "+ addCommas(ValorDeuda));
                 $('#observacion_doc').val(data.obs);
                 $('#EstadoDoc').val(data.estadoActual);
                 
                            //$('#InfoComercial').val(data.infocomer);
                            //console.log("Largo Historial: "+data.datos_historial.length);
                            //tabla con informacion del historia
                            html="<h4>Historial</h4>";
                            //html +="<table cellpadding='0' cellspacing='0' border='0' >";
                            html +="<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered'>";
                            html +="<thead>";
                            html +="<tr>";
                            html +="<th width='45%'><font size=1>Observación</font></th>";
                            //html +="<th width='20%'><font size=1>Prox. gestión</font></th>";
                            html +="<th width='20%'><font size=1>Fecha cambio</font></th>";
                            html +="<th width='30%'><font size=1>Usuario</font></th>";
                            html +="</tr>";
                            html +="</thead>";
                            html +="<tbody>";
                            for(var i=0;i<=data.datos_historial.length-1;i++){
                            html +="<tr>";
                            html +="<td ><font size=1>"+data.datos_historial[i].AccionPersona+"</font></td>";
                            //html +="<td><font size=1>"+data.datos_historial[i].ProxGestion+"</font></td>";
                            html +="<td><font size=1>"+data.datos_historial[i].FechaCambio+"</font></td>";
                            //html +="<td><font size=1>"+data.datos_historial[i].txtNomOperador+" // "+data.datos_historial[i].Observacion"</font></td>";
                            html +="<td><font size=1>"+data.datos_historial[i].txtNomOperador+"</font></td>";    
                            //html +="<td><font size=1>"+"jperez"+"</td>";
                            html +="</tr>";
                            }
                            html +="</tbody>";
                            html +="</table>";
                            
                                                        //TABLA HISTORIAL ABONOS
                            html2="<h4>Historial</h4>";
                            html2 +="<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered'>";
                            html2 +="<thead>";
                            html2 +="<tr>";
                            html2 +="<th width='45%'><font size=1>Monto</font></th>";
                            html2 +="<th width='20%'><font size=1>Fecha</font></th>";
                            
                            html2 +="</tr>";
                            html2 +="</thead>";
                            html2 +="<tbody>";
                            for(var i=0;i<=data.Historial_abonos.length-1;i++){
                            html2 +="<tr>";
                            html2 +="<td ><font size=1>"+data.Historial_abonos[i].MontoAbono+"</font></td>";
                            html2 +="<td><font size=1>"+data.Historial_abonos[i].FechaRegistro+"</font></td>";
                               
                            html2 +="</tr>";
                            }
                            html2 +="</tbody>";
                            html2 +="</table>";
                            
                 //if(data.obs=="DOCUMENTO DERIVADO"){
//                    $('#GuardaModAccion').prop("disabled",true);
//                 }
//                            
                 if(data.infocomer=="Existe"){
                                $('#EstadoPublicaInfoComer').prop('checked', true);
                                $('#PublicarInfoComer').attr("disabled", true);
                                $('#EstadoPublicaInfoComer').attr("disabled", false);
                            }else{
                                $('#EstadoPublicaInfoComer').prop('checked', false);
                                 $('#PublicarInfoComer').attr("disabled", false );
                                $('#EstadoPublicaInfoComer').attr("disabled", true);
                                $('#FlagInformeComercial').val("VACIO");
                            }
                            
                 $('#TablaHistorial').append(html);    
                 $('#TablaHistorialAbonosEMP').append(html2);                        
                 $('#mdlModificaDocDerivado').modal();
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {alert("error"+errorThrown);}
        });
        return false;    
            
    });
    

    
    
    $('.btnDevolverCliente').click(function (){
        //alert($(this).attr("id"));
        $('#seqDocumento').val($(this).attr("id"));
        $('#mdlVolverDerivacion').modal();
    });
    
     $('.EditaOperador').click(function() {
                    $.ajax({
                        type : 'POST',
                        url : $(this).attr("href"),
                        dataType : 'json',
                        success : function(data){ 
                            $('#CodOperadorMod').val(data.OperaCod);
                            $('#NomOperadorMod').val(data.NomOpe);
                            $('#rutOperadorMod').val(data.rutOperador);
                            $('#DirOperadorMod').val(data.OperaDir);
                            $('#TelefOperadorMod').val(data.OperaTel);
                            $('#UsuarioOperadorMod').val(data.OperaUsu);
                            $('#ClaveOperadorMod').val(data.OperaClave);
                             
                            $('#mdlModificaOperador').modal();
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {alert("error: "+errorThrown);}
                    });
                    return false;
    });
    
    $('.DerivarAOperador').click(function(){
        $('#CodDocumento').val($(this).attr("id"));
        $('#mdlDerivarOperador').modal();

    });
    
    $('.DerivarAdmin').click(function(){
        $('#SeqDocumento').val($(this).attr("id"));
        $('#mdlDerivarAdministrador').modal();
    });
    
    
    $('.DocumentosOperador').click(function(){
        $.ajax({
            type : 'POST',
            url : $(this).attr("href"),
            dataType : 'json',
            success : function(data){ 
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {alert("error: "+errorThrown);}
        });
        return false;        
    });
    

});