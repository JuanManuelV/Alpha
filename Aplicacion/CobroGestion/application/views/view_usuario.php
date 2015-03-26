<!DOCTYPE html>
<HTML  lang = "es" >
    <head>
        <title>CobroGestión</title>
        
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">

        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url(); ?>css/OtrasTablas/jquery.dataTables.css" rel="stylesheet" media="screen">
         
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/OtrasTablas/jquery.dataTables.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/CB_Cliente.js"></script>
        <script src="<?php echo base_url(); ?>js/MenuDesplegable.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/Tablas.css">
        
        
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-tooltip.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-confirmation.js"></script>

		<script type="text/javascript" charset="utf-8" language="javascript" src="<?php echo base_url(); ?>js/Tablas.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablesorter.js"></script>
        <script src="<?php echo base_url(); ?>js/CobroGestion.js"></script>

        <script type="text/javascript">
        function parseDate(str) {
            var mdy = str.split('/')
            return new Date(mdy[2], mdy[0]-1, mdy[1]);
        }

        function daydiff(first, second) {
            return (second-first)/(1000*60*60*24);
        }
 
           
        // CAMBIO DE ESTADOS DE LOS FORMULARIOS
        $(document).ready(function(){

            //  ACEPTA SOLO NUMEROS
            $("#txtMontoAbono").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) || 
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) 
                {
                         // let it happen, don't do anything
                         return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
            
            
            //MUESTRA HISTORIAL ABONOS
            $('#btnHistorialAbono').click(function(){ 
                //$('#TablaHistorialAbonos').empty();
                $('#mdlHistorialAbonos').modal('show');
            });
           
           //REALIZA UN NUEVO ABONO
           $('#btnNuevoAbono').click(function(){
                $('#txtMontoAbono').val('');
            
                $('#txtMontoDeuda').val($('#Precio_doc').val());
                $('#txtRemanente').val($('#MontoRemanente').val());
                //$('#lblRemanente').val($('#MontoRemanente').val());
                $('#mdlAbonoDeuda').modal('show');            
           });
           
           //   AGREGA NUEVO ABONO Y CALCULA REMANENTE
           $('#btnAbonarDeuda').click(function(){
                $('#msjNuevoAbono').text("");
                $('#btnNuevoAbono').hide();
            
                var NuevoAbono=0,TotalAbono=0,Suma=0,MontoDeuda,Remanente,NuevoRemanente;
                
                MontoDeuda=Number($('#Precio_doc').val().replace('$ ','').replace(/[.]/gi,''));
                TotalAbono=Number($('#MontoAbono').val().replace('$ ','').replace(/[.]/gi,''));
                NuevoAbono=Number($('#txtMontoAbono').val().replace('$ ','').replace(/[.]/gi,''));
                Remanente=Number($('#txtRemanente').val().replace('$ ','').replace(/[.]/gi,''));
                
               // alert($('#Precio_doc').val());
//                alert($('#Precio_doc').val().replace('$ ',''));
//                alert($('#Precio_doc').val().replace(/[.]/gi,''));
//                alert(" "+MontoDeuda+" "+TotalAbono+" "+NuevoAbono+" "+Remanente+" fin");
                
                if(NuevoAbono>Remanente){
                    //alert(NuevoAbono+" Mayor que "+Remanente);
                    $('#msjNuevoAbono').append("Abono superior a la deuda.");  
                }else{
                    Suma=TotalAbono+NuevoAbono;
                    NuevoRemanente=MontoDeuda-Suma;
                    //alert(NuevoAbono+" Menor que "+Remanente);
                    $('#MontoAbono').val("$ "+addCommas(Suma));
                    $('#MontoRemanente').val("$ "+addCommas(NuevoRemanente));
                    $('#mdlAbonoDeuda').modal('hide');
                    
                    $('#NuevoAbonoOculto').val(NuevoAbono);
                }
            });
           
           
           //BUSCA DATOS DE CONTACTOS DEL DEUDOR Y LOS MUESTRA EN UN MODAL
           $('#btnDatosContacto').click(function(){
                //alert($('#deudor_doc').val());
                
                $.ajax({
                type: 'POST',
                data: { Codigo : $('#CodigoDocumento').val() },
                url:'<?php echo base_url('index.php/con_usuariovalido/GetDatosContactos/');?>',
                dataType:'json',                
                success : function(data){
                    $('#TablaContactos').empty();
                    $('#txtPrueba').val(data.prueba);
                    
                    //tabla con informacion del historia
                    html="<h4>Contactos</h4>";
                            //html +="<table cellpadding='0' cellspacing='0' border='0' >";
                            html +="<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered'>";
                            html +="<thead>";
                            html +="<tr>";
                            html +="<th width='35%'><font size=1>NOMBRE</font></th>";
                            html +="<th width='25%'><font size=1>TELEFONO</font></th>";
                            html +="<th width='20%'><font size=1>MAIL</font></th>";
                            html +="<th width='20%'><font size=1>ESTADO</font></th>";                            
                            html +="</tr>";
                            html +="</thead>";
                            html +="<tbody>";
                            for(var i=0;i<=data.DatosContacto.length-1;i++){
                                html +="<tr>";
                                html +="<td ><font size=1>"+data.DatosContacto[i].Nombre+" "+data.DatosContacto[i].Apellidos+"</font></td>";
                                html +="<td><font size=1>"+data.DatosContacto[i].Telefono+"</font></td>";    
                                html +="<td><font size=1>"+data.DatosContacto[i].Mail+"</font></td>";                
                                html +="<td><font size=1>"+data.DatosContacto[i].Vigencia+"</font></td>";    
                                html +="</tr>";
                            }
                            html +="</tbody>";
                            html +="</table>";                           
                            
                            $('#TablaContactos').append(html);
                    
                    $('#mdlDatosContactos').modal('show');
                },               
            
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("error"+errorThrown);
                }       
                    
            });               
            
           });
           
           
           $("#RecaudaDatos").click(function() {
               if ($('#TipoAccion').val()==3){
                            $("#num1Add").val($("#txtNum1").val());
                            $("#num2Add").val($("#txtNum2").val());
                            $("#num3Add").val($("#txtNum3").val());
                        
                            $("#LugRecauda").val($("#LugarRecauda").val());
                            $("#ConRecauda").val($("#ContactoRecauda").val());
                            $("#HrRecauda").val($("#HoraRecauda").val());
                            $("#FormaPago").val($("#TipoDocRecauda").val());
                        }
                        
                        if ($('#TipoAccionGestiona').val()==3){
                            $("#num1Mod").val($("#txtNum1").val());
                            $("#num2Mod").val($("#txtNum2").val());
                            $("#num3Mod").val($("#txtNum3").val());
                        
                            $("#LugRecaudaMod").val($("#LugarRecauda").val());
                            $("#ConRecaudaMod").val($("#ContactoRecauda").val());
                            $("#HrRecaudaMod").val($("#HoraRecauda").val());
                            $("#FormaPagoMod").val($("#TipoDocRecauda").val());
                        }
                        if ($('#TipoAccionMulti').val()==3){
                            $("#num1Multi").val($("#txtNum1").val());
                            $("#num2Multi").val($("#txtNum2").val());
                            $("#num3Multi").val($("#txtNum3").val());
                            
                            $("#LugRecaudaMulti").val($("#LugarRecauda").val());
                            $("#ConRecaudaMulti").val($("#ContactoRecauda").val());
                            $("#HrRecaudaMulti").val($("#HoraRecauda").val());
                            $("#FormaPagoMulti").val($("#TipoDocRecauda").val());
                        }
            });
            
            
        //MOSTRAR DATOS RECAUDACION
        $('#btnDatosRecaudacion').click(function(){  
            //alert("leo");
            $('#MostrarLugarRecauda').val($('#CodigoDocumento').val());
            $('#MostrarTipoDocRecauda').attr("disabled", true);
            $('#MostrartxtNum1').attr("disabled", true);
            
            $.ajax({
                type: 'POST',
                data: { Codigo : $('#CodigoDocumento').val() },
                url:'<?php echo base_url('index.php/con_usuariovalido/GetDatosRecaudacion/');?>',
                dataType:'json',                
                success : function(data){
                    $('#MostrarLugarRecauda').val(data.Direccion);
                    $('#MostrarContactoRecauda').val(data.NombreContacto);
                    $('#MostrarHoraRecauda').val(data.Hora);
                    $('#MostrarTipoDocRecauda option:selected').text(data.SeqDocumentoPago);
                    $('#MostrartxtNum1 option:selected').text(data.SeqBancoOrigen);
                    $('#MostrartxtNum2').val(data.Dato2);
                    $('#MostrartxtNum3').val(data.Dato3);
                    
                    
                    //alert($();
                    if($('#MostrarTipoDocRecauda option:selected').val()==0){
                        //alert("vale -->");
                        $('#MostrartxtNum3').hide();
                        $('#MostrarlblNum3').hide();
                    }
                    
                    
                    $('#mdlDatosRecaudacion').modal('show');
                    //alert(data.seqDocumentoPago);
                },
                
            
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("error"+errorThrown);
                }       
                    
            });                    
         });
            //-----------------------------------
               //   PERSISTENCIA DE FORMULARIOA
               //-----------------------------------
               //OPERADOR
               $('#BtnNuevoOperador').click(function(){
                    $("#NomOperador").val('');
                    $("#ApellOperador").val('');
                    $("#DirOperador").val('');
                    $("#TelefOperador").val('');
                    $("#MailOperador").val('');
                    $("#UsuarioOperador").val('');
                    $("#ClaveOperador").val('');
                    $('#RegionAgregaOperado').val(0);
                });
               //CONTACTO
              $('#BtnNuevoContacto').click(function() {
                    $('#DeudorContactoAdd').val(0);
                    $('#TipoContactoAdd').val(0);
                    $('#NomContacto').val('');
                    $('#ApeContacto').val('');
                    $('#RutContacto').val('');
                    $('#CargoContacto').val('');
                    $('#MailContacto').val('');
                    $('#TelContacto').val('');
                    $('#DirContacto').val('');
                    $('#ObsContacto').val('');
               });  
               //DEUDOR
               $('#BtnNuevoDeudor').click(function() {
                    $('#TPersona').val(0);                    
                    $('#NomDeudor').val('');
                    $('#ApeDeudor').val('');
                    $('#RutDeudor').val('');
                    $('#telefono').val('');
                    $('#mail').val('');
                    $('#dir').val('');
                    $('#ciudad').val('');
                    $('#comuna').val('');
                    $("#nomDeudorLab").html("Nombre");
                    $("#apeDeudorLab").show();
                    $("#ApeDeudor").show();                        
                    $("#apeDeudorLab").html("Apellido");        
               });
            
            
            
             /*JS PARA SOLICITAR DATOS DE RECAUDACION*/
               $('#TipoAccion').bind("change", function() {
                    if ($('#TipoAccion').val()==3){
                        $('#InfoRecauda').modal('show'); 
                    }
               });
            
            //DOCUMENTO (PANEL ACCIONES)
               $('#BtnNuevoDocumento').click(function() {
                 $('#EstadoDerivacion').prop('checked', false);
                    //$('#btnGuardaDoc').hide();//boton guardar desabilitado
                    $('#btnGuardaDoc').attr('disabled',true);
                    
                    $('#EmpCobCombo').val(0);
                    $('#TipoDocAdd').val(-1);
                    $('#TipoDeudorDocAdd').val(0);
                    $('#TipoAccion').val(0);
                    $('#NumDocumento').val('');
                    $('#FechaVence').val('');
                    $('#ProxGes1').val('');
                    $('#Precio').val('');
                    $('#observacion').val('');  
                    //$('#TipoDeudorDocAdd').val('');
               });
               
               //si selecciona una fecha de proxima gestion habilita el goton guardar
              // $('#datetimepicker3').change(function() {
//                     alert("hola1");
//                     $('#btnGuardaDoc').attr('disabled',false);//boton guardar desabilitado
//               });
//               $('#Calendario').blur( function() {
//                alert("hola");
//                    $('#btnGuardaDoc').attr('disabled',false);//boton guardar desabilitado
//               });
               $('#Calendario').click(function(){
                    $('#btnGuardaDoc').attr('disabled',false);//boton guardar desabilitado
                
               });
               //if($('#ProxGes1').val()!=""){
//                alert("agrego fecha");
//               }
            
            
              //alert("1");
              
              
              $('#TPersonaOculto').bind("change", function() {
                    if ($('#TPersonaOculto').val()==1){
                        $("#nomDeudorOcultoLab").html("Nombre");
                        $("#apellDeudorOcultoLab").show();
                        $("#ApellDeudorOculto").show();
                    }else{
                        $("#nomDeudorOcultoLab").html("Razon Social");
                        $("#apellDeudorOcultoLab").hide();
                        $("#ApellDeudorOculto").hide();
                    }                    
               });
               $('#TPersona').bind("change", function() {
                    if ($('#TPersona').val()==1){
                        $("#nomDeudorLab").html("Nombre");
                        $("#apeDeudorLab").show();
                        $("#ApeDeudor").show();                        
                        $("#apeDeudorLab").html("Apellido");                       
                        javascript.Getid()
                    }else{
                        $("#nomDeudorLab").html("Razon Social");
                        $("#ApeDeudor").hide();
                        $("#apeDeudorLab").hide();
                    }                    
               });
              
              
              
              
                    $("#lblNum1").show();
                    $("#lblNum1").html("Banco");
                    $("#txtNum1").show();
                    $("#lblNum2").show();
                    $("#lblNum2").html("ID vale vista");
                    $("#txtNum2").show();
                    $("#lblNum3").hide();
                    $("#txtNum3").hide();
            
            $('#TipoDocRecauda').bind("change", function() {
                if ($('#TipoDocRecauda').val()==0){
                    //alert("1");
                    $("#lblNum1").show();
                    $("#lblNum1").html("Banco");
                    $("#txtNum1").show();
                    $("#lblNum2").show();
                    $("#lblNum2").html("ID vale vista");
                    $("#txtNum2").show();
                    $("#lblNum3").hide();
                    $("#txtNum3").hide();
                }
                if ($('#TipoDocRecauda').val()==1){
                    //alert("2");
                    $("#lblNum1").show();
                    $("#lblNum1").html("Banco");
                    $("#txtNum1").show();
                    $("#lblNum2").show();
                    $("#lblNum2").html("Horario");
                    $("#txtNum2").show();
                    $("#lblNum3").show();                    
                    $("#lblNum3").html("Contacto");
                    $("#txtNum3").show();
                }
                if ($('#TipoDocRecauda').val()==2){
                    //alert("3");
                    $("#lblNum1").show();
                    $("#lblNum1").html("Banco");
                    $("#txtNum1").show();
                    $("#lblNum2").show();
                    $("#lblNum2").html("C&oacute;digo transferencia");
                    $("#txtNum2").show();
                    
                    $("#lblNum3").html("Titular de cuenta");
                    $("#lblNum3").show();
                    $("#txtNum3").show();
                }
            });
            
            
            //  CAMBIA CHECK VIGENCIA DE CONTACTO
            $('#btnVigencia').click(function(){
                //alert($('#lblEstadoContacto').text());
                if($('#lblEstadoContacto').text()=="Vigente"){     
                    
                    $('#lblEstadoContacto').empty();
                    $('#lblEstadoContacto').append('No Vigente');
                    $('#flagVigencia').val("No Vigente");
                    
                    $('#btnVigencia').prop('value', 'Dejar Vigente');
                    //alert("Vigente");
                    
                }else{
                    $('#lblEstadoContacto').empty();
                    $('#lblEstadoContacto').append('Vigente');
                    $('#flagVigencia').val("Vigente");
                    
                    $('#btnVigencia').prop('value', 'Dejar No Vigente');
                    //alert("No vigente");
                }
            });
            
            //  EDITA CONTACTOS
            $('.boton_editar_contacto').click(function() {
                    //alert("asdasd->"+ $(this).attr("href"));  
                    
                    $('#chkContactoVigente').attr('disabled', true);
                    $('#chkContactoNoVigente').attr('disabled', true);
                                                         
                    $.ajax({
                        type : 'POST',
                        url : $(this).attr("href"),
                        dataType : 'json',
                        success : function(data){
                            $('#codContacto').val(data.cod);
                            $('#deudoresContacto').val(data.deudor);
                            $('#TipoContacto').val(data.tipo);
                            $('#nomContacto').val(data.nom);
                            $('#dirContacto').val(data.dire);
                            $('#telContacto').val(data.telef);
                            $('#mailContacto').val(data.mail);
                            $('#obserContacto').val(data.obs);
                            $('#apellContacto').val(data.apellido);
                            $('#rutContacto').val(data.rut);
                            $('#CargoContactoMod').val(data.cargo);
                            $('#RegionModContacto option:selected').text(data.reg);
                            
                            
                            //  ESTADO DE VIGENCIA DEL CONTACTO
                            if(data.Vigencia=="Vigente"){    
                                $('#lblEstadoContacto').append('Vigente');
                                //$('#chkContactoVigente').attr("checked", true);
//                                $('#chkContactoNoVigente').attr("checked", false);
//                                
                                $('#btnVigencia').prop('value', 'Dejar No Vigente');
                            }
                            if(data.Vigencia=="No Vigente"){
                                $('#lblEstadoContacto').append('No Vigente');
                                
                                $('#btnVigencia').prop('value', 'Dejar Vigente');
                            }         
                            
                            $('#myModal16').modal();
                        },
               
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("error"+errorThrown);
                        }
                    });
                    return false;
               });
            /*JS PARA AGREGAR UN DEUDOR AL CREAR UN DOCUMENTO*/  
               $('#AgregaDeudor').click(function(){
                    //alert("leo");
                    $('#myModal155').modal('show'); 
               });
            
            $('#btnHistorial').click(function(){
                $('#mdlHistorial').modal();
                
            });
            
            
            $('#EstadoModificaDoc').bind("change", function() {
                    $("#TipoAccionGestiona").empty();
                    var elegido=$(this).val();
                    
                    $('#TipoAccionGestiona').append($('<option>', {
                                               value: "-1",
                                               text : " - Selecione una acción - "
                                }));
                    //alert(elegido);
                    $.ajax({
                        type: 'POST',
                        data: { Codigo : elegido },
                        url:'<?php echo base_url('index.php/con_usuariovalido/Prueba/');?>',
                        dataType:'json',
                        success : function(data){
                            $.each(data.NuevasAcciones, function( index, value ) {
                                $('#TipoAccionGestiona').append($('<option>', {
                                    value: index,
                                    text : value
                                }));
                            });
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("error"+errorThrown);
                        }            
                    });
            });    
            
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
            
             $('.edita_documento').click(function(){
                    $('#TablaHistorialAbonos').empty();
                    $('#TablaHistorial').empty();  
                    
                    $('#btnNuevoAbono').show();
                    //setea datos
                    $('#NuevoAbonoOculto').val('');
                    $('#OperaCobranza').attr("disabled", true);
                    $('#EmpCobCombo').val(0);
                    $('#OperaCobranza').val(0);
                    $('#EstadoDerivacion').prop('checked', false);
                    $('#OperadorSeleccionado').prop('checked', false);
                    $('#GuardaModAccion').val('Guardar');
                    $('#GuardaModAccion').prop("disabled",false);                                        
                    $('#btnDerivar').attr("disabled", false);     
                    
                    $.ajax({
                        type : 'POST',
                        url : $(this).attr("href"),
                        dataType : 'json',
                        success : function(data){
                            //---   CALCULA LOS DIAS DE MORA (FECHA VENCIMIENTO-DIA ACTUAL)-------
                            var d = new Date();
                            var strDate = d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear();
                            
                            //alert("vence-> "+parseDate(data.conpago)+" Hoy-> "+parseDate(strDate));
//                            
//                            alert(daydiff(parseDate(data.conpago), parseDate(strDate)));
                            
                            //alert("total abonos "+data.TotalAbonos[0].sumaabono);
                            //console.log("data: "+data.TotalAbonos[0]->sumaabono);
                            //console.log("data: "+data.TotalAbonos[0]);
                            
                            //---  CALCULA LOS REMANENETES  -------
                            var TotalAbono,Remanente,ValorDeuda;
                            
                            ValorDeuda=data.precio;                                                     
                            
                            if(data.TotalAbonos[0].sumaabono == null || data.TotalAbonos[0].sumaabono == ''){
                                TotalAbono = 0;
                            } else {
                                TotalAbono = data.TotalAbonos[0].sumaabono;
                            }
                            
                            Remanente=ValorDeuda-TotalAbono;
                            //-------------------------------------
                            
                            
                            $('#MontoAbono').val("$ "+ addCommas(TotalAbono));
                            $('#MontoRemanente').val("$ "+ addCommas(Remanente));
                            
                            $('#CodigoDocumento').val(data.coddoc);
                            $('#Cod_doc').val(data.cod);
                            $('#accion_doc').val(data.accion);
                            $('#deudor_doc').val(data.deudor);
                            $('#Tipo_doc').val(data.tipodoc);
                            $('#ConPago_doc').val(data.conpago);
                            $('#pgest_doc').val(data.pgestion);
                            $('#Precio_doc').val("$ "+ addCommas(data.precio));
                            $('#observacion_doc').val(data.obs);
                            
                            $('#EstadoDoc').val(data.nomDeuda);
                            
                            //  BOTON DATOS RECAUDACION
                            if(data.accion=="Recaudar"){
                                $('#btnDatosRecaudacion').show();
                            }else{
                                $('#btnDatosRecaudacion').hide();
                            }                    
                            
                            
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
                            
                            //si esta derivado
                            if(data.derivado=="t"){
                                alert("DOCUMENTO DERIVADO");
                                $('#GuardaModAccion').attr("disabled", true);
                                $('#btnDerivar').attr("disabled", true);  
                                $('#observacion_doc').attr("disabled", true);
                                $('#EstadoModificaDoc').attr("disabled", true);  
                                $('#TipoAccionGestiona').attr("disabled", true);
                                $('#FechaNueva').attr("disabled", true); 
                            }else{
                                $('#btnDerivar').attr("disabled", false);  
                                $('#observacion_doc').attr("disabled", false);
                                $('#EstadoModificaDoc').attr("disabled", false);
                               
                               
                                $('#TipoAccionGestiona').attr("disabled", false);
                                 $('#FechaNueva').attr("disabled", false); 
                                
                                
                                $("#EstadoModificaDoc").empty();  
                                $('#TipoAccionGestiona').empty();
                                $('#TipoAccionGestiona').append($('<option>', {
                                               value: "-1",
                                               text : " - Selecione una acción - "
                                }));                          
                                
                                $.ajax({
                                    type : 'POST',
                                    data: { Codigo : $('#accion_doc').val() },
                                    url : '<?php echo base_url('index.php/con_usuariovalido/CargaEstados/');?>',
                                    dataType : 'json',
                                    success : function(data){
                                        $.each(data.NuevosEstados, function( index, value ) {
                                            $('#EstadoModificaDoc').append($('<option>', {
                                               value: index,
                                               text : value
                                           }));
                                        });
                                    }
                                });           
                            }
                            //TABLA HISTORIAL DOCUMENTO
                            //console.log("Largo Historial: "+data.datos_historial.length);
                            html="<h4>Historial</h4>";
                            html +="<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered'>";
                            html +="<thead>";
                            html +="<tr>";
                            html +="<th width='45%'><font size=1>Observación</font></th>";
                            html +="<th width='20%'><font size=1>Fecha cambio</font></th>";
                            html +="<th width='30%'><font size=1>Usuario</font></th>";
                            html +="</tr>";
                            html +="</thead>";
                            html +="<tbody>";
                            for(var i=0;i<=data.datos_historial.length-1;i++){
                            html +="<tr>";
                            html +="<td ><font size=1>"+data.datos_historial[i].AccionPersona+"</font></td>";
                            html +="<td><font size=1>"+data.datos_historial[i].FechaCambio+"</font></td>";
                            html +="<td><font size=1>"+data.datos_historial[i].txtNomOperador+"</font></td>";    
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
                            
                            $('#TablaHistorialAbonos').append(html2);
                            $('#TablaHistorial').append(html);                             
                            $('#myModal114').modal();
                        },               
                        error : function(XMLHttpRequest, textStatus, errorThrown) {alert("error"+errorThrown);}
                    });
                    return false;
            });
        });
        
        //DATOS PARA RECAUDACION
        $(document).ready(function(){
            $('#TipoAccionGestiona').bind("change", function() {
                if ($('#TipoAccionGestiona').val()==3){
                    $('#InfoRecauda').modal('show'); 
                            //alert("info recaudacion");
                }
            });
        });
//        
//            $('#btnBuscarFiltroAccion').click(function() {
//                var fDesde = $('#fecha_desde').val();
//                var fHasta = $('#fecha_hasta').val();
//                $('#FechaDesdeFiltro').val(fDesde);
//                $('#FechaHastaFiltro').val(fHasta);
//            });
//        $(function () {
            
    

             
        
        
            $('#BtnSubmit').val('Actualizar');
            //------------------------------------------------
            //
            //      JSON DE CARGAS DE DATOS
            //
            //------------------------------------------------
            $(document).ready(function(){
                
                <?php if (isset($tab)){
                //echo "->".$tab."<-";
                echo "$('a[href=\"#".$tab."\"]').tab('show');";
                } ?> 

                $('#pruebamensaje').val()=$tipoopera;
                
                $('#btnPublicarInfoComer').click(function(){
                    if ($('#PublicarInfoComer').attr('checked')) {
                        if($('#EstadoPublicaInfoComer').attr("disabled", true)){
                            $('#EstadoPublicaInfoComer').prop('checked', true);
                            $('#PublicarInfoComer').prop('checked', false);
                            $('#EstadoPublicaInfoComer').attr("disabled", false);
                            $('#PublicarInfoComer').attr("disabled", true);
                        }
                    }
                    
                    if (!$('#EstadoPublicaInfoComer').attr('checked')) {
                        if($('#PublicarInfoComer').attr("disabled", true)){
                            alert("Hola");
                            $('#EstadoPublicaInfoComer').attr("disabled", true);
                            $('#PublicarInfoComer').attr("disabled", false);
                        }
                    }
                });

               
               
            //================================================
            //             CAMBIA EL TIPO DE PERSONA
            //================================================
                
               $('#TipoAccionMulti').bind("change", function() {
                    if ($('#TipoAccionMulti').val()==3){
                        $('#InfoRecauda').modal('show'); 
                        $("#RecaudaMasiva :input").each(function(){$(this).val('');});
                        //alert("info recaudacion");
                    }
               });
               
               
               
               
               
               
               
              
               
               //CAMBIA LOS TEXTBOX PARA INGRESAR DATOS DEL DOCUMENTO
                $("#lblNum3").hide();
                $("#txtNum3").hide();
               
            
            
            
            $('#OperaCobranza').bind("change", function() {
                if($('#OperaCobranza').val()!='-1'){
                    $('#GuardaModAccion').val('Derivar');
                    $('#OperadorSeleccionado').prop('checked',true);
                }else{
                    $('#OperadorSeleccionado').prop('checked',false);
                }
                
            });
            
           // $('#GuardaModAccion').click(function(){
//                $('#ModalConfirmacion').modal();
//            });
//            $('#btnConfirmarMensaje').click(function(){
//                $.ajax({
//                      type: "POST",
//                      url: "con_usuariovalido/modifica_doc",
//                      //data: { "codigo" :  $('#PruebaLLamada').val() },
////                      success: function(data){
////                          alert(data);
////                      }
//                 });
//                
//            });
            
            
            //llama a una funcion del controlador
            $('#EmpCobCombo').bind("change", function() {
                    if($('#EmpCobCombo').val()!='-1'){
                        $('#OperaCobranza').attr("disabled", false);                        
                    }else{
                        $('#OperaCobranza').attr("disabled", true); 
                    }
                 //$.ajax({
//                      type: "POST",
//                      url: "con_usuariovalido/index",
//                      data: { "codigo" :  $('#PruebaLLamada').val() },
//                      success: function(data){
//                          //alert(data);
//                      }
//                 });
             });
            });
            
            
            
            
        </script>

    </head>
    <!--CUERPO DE LA PAGINA-->
    <body style="">     
        <br />
        <!--BARRA SUPERIOR-->
        <div class="row-fluid offset1">
            <div class="span1"></div>
            <div class="span10">
                <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="brand" href="#">COBRO GESTION</a>
                        
                        <ul class="nav pull-right" >
                            <li><a href="index.html"><i class="icon-home icon-white"></i>Inicio</a></li>
                            <li><a href="#"><i class="icon-user icon-white"></i>Cuenta</a></li>
                            <li><a href="<?php echo base_url()."index.php/con_mensajes"; ?>"><i class="icon-envelope icon-white"></i>Mensajes</a></li>
                            <li><a href="<?php echo base_url()."index.php/con_login/logout"; ?>"><i class="icon-off icon-white"></i>Salir</a></li>                          
                        </ul>
                    </div>
                </div>
                </div>
            </div>
            <div class="span1"></div>
        </div>
        <input type="hidden" value="<?php echo $tipoopera ?>" id="TipoUsuario" name="TipoUsuario" >

        <br />
        <div class="well span12 offset1" style="margin:center; width:1300px;border-style:solid;border-width:5px;box-shadow: 2px 2px 5px #999;" >
        <!--BARRA LATERAL-->
        <div class="tabbable" id="PanelGeneral">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#1" data-toggle="tab">Acciones</a></li>
                <li><a href="#PanelDeudores" data-toggle="tab">Deudores</a></li>
                <!--<li><a href="#PanelDocumentos" data-toggle="tab">Documentos</a></li>-->
                <li><a href="#PanelContactos" data-toggle="tab">Contactos</a></li>
                <li><a href="#PanelOperador" id="pnlOperador" data-toggle="tab">Operadores</a></li>
            </ul>
            
            <!--CONTENIDO DE LOS PANELES-->
            <div class="tab-content">
            
                <!--##########################-->
                <!--#     PANEL ACCIONES     #-->
                <!--##########################-->
                 <div class="tab-pane active" id="1">
                                        
                    <!--FILTRO-->
                    <p>Filtro</p>
                    <div class="well" >
                        <div class="row-fluid" style="margin-top: 0px;">
                            <?php echo form_open('con_usuariovalido/index'); ?>
                            
                            <table align="center">
                                <tr>
                                    <td width="20%">Desde</td>
                                    <td width="30%">
                                    <div id="datetimepicker0" class="input-append date">
                                        <input class="input" id="fecha_desde" name="fecha_desde" data-format="dd/MM/yyyy" type="text"></input>
                                        <span class="add-on"><i data-time-icon="icon-time" class="icon-calendar"></i></span>
                                    </div>
                                    </td>
                                    <td width="20%" ><label style="margin-left: 20px;">Hasta</label></td>
                                    <td width="30%">
                                    <div id="datetimepicker1" class="input-append date">
                                        <input class="input" id="fecha_hasta" name="fecha_hasta" data-format="dd/MM/yyyy" type="text"></input>
                                        <span class="add-on"><i data-time-icon="icon-time" class="icon-calendar"></i></span>
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Gestiones por realizar</td>
                                    <td><?php echo form_dropdown('TramoAccion', $TramoAccion);?></td>
                                    <td><label style="margin-left: 20px;">Deudor</label></td>
                                    <td><?php echo  iconv('UTF-8', 'ISO-8859-1', form_dropdown('DeudorFiltro', $DeudorFiltro));?></td>
                                </tr>
                                <tr>
                                    <td>Acción</td>
                                    <td><?php echo form_dropdown('AccionFiltro', $AccionFiltro);?></td>
                                    <td><label style="margin-left: 20px;">Estado</label></td>
                                    <td><?php echo form_dropdown('EstadoDeuda', $EstadoDeuda);?></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td ><button class="btn btn-large btn-primary pull-right" type="submit" id="btnBuscarFiltroAccion">BUSCAR</button></td>
                                   
                                </tr>   
                                                          
                            </table>
                            <?php echo form_close(); ?>
                            <p id="FechaDesdeFiltro"></p><p id="FechaHastaFiltro" ></p>   
                        </div>
                    </div>
                    
                    <p>Proximas acciones</p>
                    
                    <fieldset> 
                    <?php echo form_open('con_usuariovalido/prueba_check',array("id","main")); ?>
                    <?php if (isset($datos_doc)){ ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TablaAcciones">
  	                     <thead>
                            <tr>
                            <th></th><th width="30px;">N° Doc</th><th width="200px;">Deudor</th><th width="81px;">Monto deuda</th><th width="81px;">Deuda Remanente</th><th width="50px;">Dias de Mora</th><th>Tipo</th><th>Acción</th><th>Estado</th><th>Fecha de emisión</th><th>Próxima gestión</th><th width="150px;">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($accion_cobranza->result() as $row){  
                                    if($row->Derivado == "f"){
                                        echo "<td><input type='checkbox' name='acciones[]' value='".$row->Documento."' id='acciones[]' /></td>";
                                    }else{
                                        echo "<td></td>";
                                    }                                 
                                     
                                    $FechaVence=$row->ConPago;
                                    $FechaHoy=date("Y-m-d");
                                    
                                    $datediff = strtotime($FechaHoy) - strtotime($FechaVence);
                                    
                                  //  $auxVence=explode('-',$FechaVence);
//                                    $dtVence=mktime(0,0,0,$auxVence[0],$auxVence[1],$auxVence[2]);
//                                    $auxHoy=explode('-',$FechaHoy);
//                                    $dtHoy=mktime(0,0,0,$auxHoy[0],$auxHoy[1],$auxHoy[2]);
//                                    
                                    if($datediff>0){
                                        $days=floor($datediff/(60*60*24));
                                        //$days="vence>hoy";
                                    }else{
                                        //$days="vence<hoy";
                                        $days=floor($datediff/(60*60*24));
                                    }
                                    //printf('%d Dias.',$days);
                                    
                                    //$diff = abs(strtotime($FechaVence) - strtotime($FechaHoy));
//                                    $years = floor($diff / (365*60*60*24));
//                                    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//                                    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                                    
                                    //printf('%d Anos, %d meses, %d dias', $years, $months, $days);
                                ?>
                                <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->NumDocumento;?></a></td>
                                <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo iconv('UTF-8', 'ISO-8859-1',$row->nombres)." ".iconv('UTF-8', 'ISO-8859-1',$row->apellidos);?></a></td>
                                <td><a class="edita_documento pull-right" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo "$ ".number_format($row->Monto, 0, '', '.');?></a></td>
                                <td><a class="edita_documento pull-right" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo "$ ".number_format($row->Remanente, 0, '', '.');?></a></td>
                               
                                <td><a class="edita_documento pull-right" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $days?></a></td>
                                <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->Nombre; ?></a></td>
                                <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->NomAccion;?></a></td>
                                <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->txtNomEstadoDeuda;?></a></td>
                                <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->FechaEmision;?></a></td>
                                <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->ProxGestion;?></a></td>
                                <td>
                                    <a class="btn edita_documento btn-success pull-left" rel="tooltip" title="GESTIONAR ACCION"  href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><i class="icon-pencil"></i></a>   
                                    <a class="btn DerivarAOperador btn-warning"  rel="tooltip" title="ASIGNAR A UN OPERADOR" id="<?php echo $row->Documento ?>" ><i class="icon-envelope"></i></a>
                                    <a class="btn btn-danger pull-right" rel="tooltip" title="ELIMINAR ACCION" href="<?php echo base_url('index.php/con_usuariovalido/elimina_doc/'.$row->Documento);?>"><i class="icon-trash"></i></a>
                               </td> 
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                    <div class="well">
                    <h4>Acciones masivas</h4>
                        <a class="btn submit" id="btn_edicion_masiva" rel="tooltip" title="COMENTAR"><i class="icon-list-alt"></i>Comentar</a>
                        <input class="btn btn-success" type=""  value="Pasar a informes comerciales"  id="btnInformeComercial" />
                        <input class="btn btn-danger" type=""  value="Derivar"  id="btnDerivarMasivo" />
                    </div>
                    <?php echo form_close(); ?>
                    </fieldset>          
                    
                    <a id="BtnNuevoDocumento" class="btn" data-toggle="modal" href="#myModal44"><i class="icon-file"></i>Agregar Documento</a>         
                          
                    <?php }?> 
                </div>
                
                <!--##########################-->
                <!--#                        #-->
                <!--#     PANEL DEUDORES     #-->
                <!--#                        #-->
                <!--##########################-->
                <div class="tab-pane" id="PanelDeudores">
                    <p>Filtro</p>
                    <div class="well">
                        <?php echo form_open('con_usuariovalido/index'); ?>
                        <table align="center">
                        <tr>
                            <tr>
                            <td></td>
                            <td><label>Nombre</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('NomDeudorFil', $NomDeudorFil));?></td>    
                            <td><label>RUT</label><?php echo form_dropdown('RutDeudorFil', $RutDeudorFil);?></td>
                            <td></td>
                            </tr> 
                           <!--
 <tr>
                            <td><label >Ciudad</label><?php echo iconv('UTF-8', 'ISO-8859-1',form_dropdown('CiudadDeudorFil', $CiudadDeudorFil));?></td>
                            </tr>
-->
                        </tr> 
                        <tr>
                            <td colspan="3"></td>
                            <td ><button class="btn btn-large btn-primary pull-right" type="submit">BUSCAR</button></td>
                                   
                        </tr>                            
                        </table>
                        <?php echo form_close(); ?>
                    </div>
                    
                    <p>Deudores</p>
                    <?php if (isset($datos_deudores)){ ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example1">
                	<thead>
                        <tr>
                            <th>Nombre</th><th>Dirección</th><!--<th>Teléfono</th><!--<th>Mail</th><th>Ciudad</th>--><th width="100px;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos_deudores->result() as $row){?>
                        <tr>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1', $row->nombres)." ".iconv('UTF-8', 'ISO-8859-1',$row->apellidos);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->direccion);?></td>
                            <!--<td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->telefono);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->mail);?></td>
                            <td><?php echo $row->Region; ?>  </td>-->
                            <td>
                            <a class="btn boton_editar btn-success pull-left" rel="tooltip" title="EDITAR DEUDOR"  href="<?php echo base_url('index.php/con_usuariovalido/carga_deudor/'.$row->codigo); ?>"><i class="icon-pencil"></i></a>   
                            <a class="btn btn-danger pull-right" rel="tooltip" title="ELIMINAR DEUDOR" href="<?php echo base_url('index.php/con_usuariovalido/elimina_deudor/'.$row->codigo); ?>"><i class="icon-trash"></i></a>
                            </td>  
                        </tr>
                        <?php }?>
                    </tbody>
                    </table>
                    <?php echo form_close(); ?>
                    <?php }?> 
                        
                    <a id="BtnNuevoDeudor" class="btn" data-toggle="modal" href="#myModal1" >Agregar deudor</a>
                  
                 </div>
                 
                 <!--##########################-->
                 <!--#                        #-->
                 <!--#     PANEL DOCUMENTOS   #-->
                 <!--#                        #-->
                 <!--##########################-->
                 <div class="tab-pane" id="PanelDocumentos">
                    <p>Filtro</p>
                    <div class="well">
                    
                    </div>
                    
                    <p>Documentos</p>
                    <div class="well">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tblDocumento">
         	          <thead>
                        <tr>
                            <th>N° documento</th><th>Deudor</th><th>Monto</th><th>Tipo</th><th>Acción</th><th width="100px;">Opciones</th>
                        </tr>
                        </thead>
	                   <tbody>
                       <?php foreach ($accion_cobranza->result() as $row){?>
                        <tr>                            
                            <th><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->NumDocumento;?></a></th>
                            <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->nombres." ".$row->apellidos;?></a></td>
                            <td><a class="edita_documento pull-right" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo '$ '.$row->Monto;?></a></td>
                            <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->Nombre; ?></a></td>
                            <td><a class="edita_documento" href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><?php echo $row->NomAccion;?></a></td>
                            <td>
                                <a class="btn edita_documento btn-success pull-left" rel="tooltip" title="GESTIONAR ACCION"  href="<?php echo base_url('index.php/con_usuariovalido/edita_accion/'.$row->Documento); ?>"><i class="icon-pencil"></i></a>   
                                <a class="btn btn-danger pull-right" rel="tooltip" title="ELIMINAR ACCION" href="<?php echo base_url('index.php/con_usuariovalido/elimina_doc/'.$row->Documento);?>"><i class="icon-trash"></i></a>
                             </td>                                 
                        </tr>
                        <?php }?>
                       </tbody>
                       </table>
                    </div>
                    
                    <a id="BtnNuevoDocumento" class="btn" data-toggle="modal" href="#myModal44"><i class="icon-file"></i>Documento</a>
                 </div>
                  
                 
                 <!--##########################-->
                 <!--#                        #-->
                 <!--#     PANEL CONTACTOS    #-->
                 <!--#                        #-->
                 <!--##########################-->
                 
                 <div class="tab-pane" id="PanelContactos">
                 <p>Filtro</p>
                    <div class="well">
                        <?php echo form_open('con_usuariovalido/index'); ?>
                        <table align="center">
                            <tr>
                            <td></td>
                            <td><label>Deudor</label><?php echo iconv('UTF-8', 'ISO-8859-1',form_dropdown('NomDeudorFilContacto', $NomDeudorFilContacto));?></td>   
                            <td><label>Contactos</label><?php echo iconv('UTF-8', 'ISO-8859-1',form_dropdown('NomContacto', $NomContacto));?></td>   
                            <td></td>
                            </tr>  
                            <tr>
                            <td></td>
                            <td><label>Tipo</label><?php echo form_dropdown('TipoContFiltro', $TipoContFiltro);?></td>
                            <td colspan="2"></td>
                            </tr> 
                            <tr>
                            <td colspan="3"></td>
                            <td ><button class="btn btn-large btn-primary pull-right" type="submit">BUSCAR</button></td>
                                   
                            </tr> 
                                                   
                        </table>
                        <?php echo form_close(); ?>
                    </div>
                    <p>Contactos</p>
                      <?php if (isset($datos_contacto)){ ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                	<thead>
                        <tr>
                            <!--<th>Deudor</th>--><th>Contacto</th><th>Tipo</th><th>Estado</th><th>Dirección</th><th>Teléfono</th><th>Mail</th><th >Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos_contacto->result() as $row){?>
                        <tr>
                            <!--<td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->nombres)." ".iconv('UTF-8', 'ISO-8859-1',$row->apellidos);?></td>-->
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->Nombre)." ".iconv('UTF-8', 'ISO-8859-1',$row->Apellidos);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->NomTipoContacto);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->Vigencia);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->Direccion);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->Telefono);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->Mail);?></td>  
                            <td>                     
                                   
                                
                                <!--<a class="btn pull-left" rel="tooltip" title="DEJAR NO VIGENTE" href="<?php echo base_url('index.php/con_usuariovalido/UPDVigenciaOperador/'.$row->codigo); ?>"><i class=""></i>Vigencia</a>-->
                                <a class="btn boton_editar_contacto btn-success pull-left" rel="tooltip" title="EDITAR CONTACTO"  href="<?php echo base_url('index.php/con_usuariovalido/carga_contacto/'.$row->codigo); ?>"><i class="icon-pencil"></i></a>
                                <a class="btn btn-danger pull-right" rel="tooltip" title="ELIMINAR CONTACTO" href="<?php echo base_url('index.php/con_usuariovalido/elimina_contacto/'.$row->codigo); ?>"><i class="icon-trash"></i></a>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                    </table>
                    <?php }?> 
                    <a id="BtnNuevoContacto" class="btn" data-toggle="modal" href="#myModal9"><i class="icon-user"></i>Agregar contacto</a>
                    
                 </div>
                 
                 <!--##########################-->
                 <!--#                        #-->
                 <!--#     PANEL OPERADORES   #-->
                 <!--#                        #-->
                 <!--##########################-->
                 <div class="tab-pane" id="PanelOperador">
                    <p>Filtro</p>
                    <div class="well">
                        <?php echo form_open('con_usuariovalido/index'); ?>
                        <table align="center">
                            <tr>
                            <td></td>
                            <td>Nombre</td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',form_dropdown('NombreOperador', $NombreOperador));?></td>    
                            <td></td>
                            </tr>  
                            <tr>
                            <td></td>
                            <td>Usuario</td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',form_dropdown('UsuarioOperador', $UsuarioOperador));?></td>
                            <td></td>
                            </tr> 
                            <tr>
                            <td colspan="3"></td>
                            <td ><button class="btn btn-large btn-primary pull-right" type="submit">BUSCAR</button></td>
                                   
                            </tr>                        
                        </table>
                        <?php echo form_close(); ?>
                    </div>
                    <p>Operadores</p>
                    
                    
                    <?php if (isset($Operadores)){ ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example3">
                	<thead>
                        <tr>
                            <th>C&oacute;digo</th><th>Nombre</th><th>Rut</th><th>Dirección</th><th>Teléfono</th><th>Usuario</th><th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($Operadores->result() as $row){?>
                        <tr>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->codigo);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->txtNomOperador);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->rutOperador); ?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->txtDirOperador);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->txtTelOperador);?></td>
                            <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->txtUsuOperador);?></td>
                            <td>
                                <a class="btn EditaOperador btn-success pull-left" rel="tooltip" title="EDITAR OPERADOR"  href="<?php echo base_url('index.php/con_usuariovalido/carga_operador/'.$row->codigo); ?>"><i class="icon-pencil"></i></a>   
                                <a class="btn btn-danger pull-right" rel="tooltip" title="ELIMINAR OPERADOR" href="<?php echo base_url('index.php/con_usuariovalido/elimina_operador/'.$row->codigo); ?>"><i class="icon-trash"></i></a>
                                
                            </td>     
                        </tr>
                        <?php }?>
                    </tbody>
                    </table>
                    <?php }?>           
                    
                    <a id="BtnNuevoOperador" class="btn" data-toggle="modal" href="#ModOperador"><i class="icon-file"></i>Nuevo Operador</a>
                    
                 </div>
            </div>
        </div>
    </div>
    
    <br />
        
     <!--VENTANA EMERGENTE NUEVO DEUDOR-->
    <div class="modal hide fade" id="myModal155" style="z-index: 1500;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Nuevo deudor</h3>
        </div>
                                                                
        <div class="modal-body">
            <fieldset>     
            <div class="control-group">
                <table>
                    <tr>
                    <td><label>Tipo</label><?php echo form_dropdown('TipoPersona', $TipoPersona,"",'id="TPersonaOculto"');?></td>
                    <td></td>
                    </tr>
                    <tr>
                    <td><label id="nomDeudorOcultoLab">Nombre</label><input type="text" name="NomDeudorOculto" id="NomDeudorOculto"></td>
                    <td><label id="apellDeudorOcultoLab">Apellidos</label><input type="text" name="ApellDeudorOculto" id="ApellDeudorOculto"></td>
                    </tr>
                    <tr>
                    <td><label>Tipo ID tributario</label><?php echo form_dropdown('TipoRut', $TipoRut,"",'id="TipoRutDeudorOculto"');?></td>
                    <td><label>ID tributario</label><input type="text" name="RutDeudorOculto" id="RutDeudorOculto"></td>
                    </tr>
                    <tr>
                    <td><label>Regi&oacute;n</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegChile', $RegChile,"",'id="RegionDeudorOculto"'));?></td>
                    <td><label>Comuna</label><input type="text" name="ComunaDeudorOculto" id="ComunaDeudorOculto"></td>
                    </tr>
                    <td colspan="2"><label>Dirección</label><input type="text"  name="DirDeudorOculto" id="DirDeudorOculto" style="width: 100%;"></td>
                    </tr>
                   
                </table>
            </div>
            </fieldset>
        </div> 
                                                               
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal" id="AgergaDeudorOculto">Agregar</a>
        </div>
    </div>
    <script type="text/javascript">
        $('#AgergaDeudorOculto').click(function() {

            var NewOption = $('<option>');
            NewOption.attr('value',"NuevoDeudor").text($('#NomDeudorOculto').val()+" "+$('#ApellDeudorOculto').val());
            $('#TipoDeudorDocAdd').append(NewOption);
            $("#TipoDeudorDocAdd option[value='NuevoDeudor']").attr("selected","selected");
        });
    </script>
        
         
    <!----------------------------->
    <!--MODAL PARA EDICION MASIVA-->
    <!----------------------------->          
    <div class="modal hide fade fade" id="EdicionMasiva">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Comentar</h3>
        </div>
                                                                
        <div class="modal-body">
            <fieldset>    
            <div  class="control-group">
                <?php echo form_open('con_usuariovalido/EditaDocs'); ?>
                <input type="hidden" name="num1Multi" id="num1Multi" />
                <input type="hidden" name="num2Multi" id="num2Multi" />
                <input type="hidden" name="num3Multi" id="num3Multi" />
                
                <input type="hidden" name="LugRecaudaMulti" id="LugRecaudaMulti" />
                <input type="hidden" name="ConRecaudaMulti" id="ConRecaudaMulti" />
                <input type="hidden" name="HrRecaudaMulti" id="HrRecaudaMulti" />
                <input type="hidden" name="FormaPagoMulti" id="FormaPagoMulti" />
                <input type="hidden" name="modal_acciones" id="modal_acciones" />                                  
                                    
                <table align="center">
               	    <tr>
                   	<td><label>Nueva acción</label></td>
                   	<td><?php echo form_dropdown('TipoAccion', $TipoAccion,"",'id="TipoAccionMulti"');?></td>
                   	<tr>
                   	<tr>
                   	<td><label>Próxima gestión</label></td>
                   	<td>
                    <div id="datetimepicker6" class="input-append date">
                    <input class="input" id="ProxGestionMulti" name="ProxGestionMulti" data-format="dd/MM/yyyy" type="text"></input>
                    <span class="add-on"><i data-time-icon="icon-time" class="icon-calendar"></i></span>
                    </div>
                    </td>
                   	<tr>
                    <td><label>Comentario</label></td>
                   	<td><textarea id="comen" name="comen"></textarea></td>
                   	<tr>
                </table>
                
                <input id="BtnSubmit" type="submit" value="Guardar" align="center" />
                <?php echo form_close(); ?>
            </div>                                              
            </fieldset>
        </div> 
        
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>
    </div>

    <!----------------------------------->
    <!--MODAL PARA RECAUDAR INFORMACION-->
    <!----------------------------------->
    <div class="modal hide fade fade" id="InfoRecauda" style="z-index: 1500;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos recaudación</h3>
        </div>
                  
        <div class="modal-body">
            <form id="RecaudaMasiva">
                <fieldset>
                <table align="center">
                    <tr>
                    <td><label>Lugar</label></td>
                    <td><input  type="text" id="LugarRecauda"/></td>
                    </tr>
                    <tr>
                    <td><label>Contacto</label></td>
                    <td><input  type="text" id="ContactoRecauda"/></td>
                    </tr>
                    <tr>
                    <td><label>Hora</label></td>
                    <td><input type="text" id="HoraRecauda"/></td>
                    </tr>
                    <tr>
                    <td><label>Tipo de Documento de Pago</label></td>
                    <td><?php echo form_dropdown('TipoDocRecauda', $TipoDocRecauda,"",'id ="TipoDocRecauda"');?></td>
                    </tr>
                    <tr>
                    <td><label id="lblNum1">Banco</label><?php echo iconv('UTF-8', 'ISO-8859-1',  form_dropdown('BancosChile', $BancosChile,"",'id="txtNum1"'));?></td>
                    <td><label id="lblNum2">ID vale vista</label><input type="text" id="txtNum2" /></td>
                    </tr>
                    <tr >
                    <td><label id="lblNum3" >Contacto</label><input  type="text" id="txtNum3"  /></td>
                    <td></td>
                    </tr>
                </table>
                
                </fieldset>
            </form>
        </div>
                  
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal" id="RecaudaDatos">Guardar</a>
        </div>
    </div>  
    
    <!----------------------------------->
    <!-------MODAL AGREGAR DEUDOR-------->
    <!----------------------------------->    
    <div class="modal hide fade" id="myModal1">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>Nuevo deudor</h3>
        </div>
                        
        <div class="modal-body">
            <fieldset>                
            <div class="control-group">
                <?php echo form_open('con_usuariovalido/agrega_deudor'); ?>
                <table align="center">
                    <tr>
                    <td><label>Tipo</label><?php echo form_dropdown('TPersona', $TipoPersona,"",'id ="TPersona"');?></td>
                    </tr>
                    <tr>
                    <td><label id="nomDeudorLab">Nombre</label><input type="text" name="NomDeudor" id="NomDeudor"></td>
                    <td><label id="apeDeudorLab">Apellidos</label><input type="text"  name="ApeDeudor" id="ApeDeudor"></td>
                    </tr>                    
                    <tr>
                    <td><label>Tipo ID tributario</label><?php echo form_dropdown('TipoRut', $TipoRut,"",'id="TipoRutDeudors"');?></td>
                    <td><label>ID tributario</label><input type="text" name="RutDeudor" id="RutDeudor"></td>
                    </tr>
                    <tr>
                    <td><label>Regi&oacute;n</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegChile', $RegChile,"",'id="RegionDeudor"'));?></td>
                    <td><label>Comuna</label><input type="text" name="comuna" id="comuna"></td>
                    </tr>
                    <tr>
                    <td colspan="2"><label>Dirección</label><input type="text"  name="dir" id="dir" style="width: 100%;"></td>
                    </tr>
                </table>
                <button type="submit" class="btn">Guardar</button>
                <?php echo form_close(); ?>
            </div>
            </fieldset>
        </div>
                           
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>
    </div> 
    
    <!----------------------------------->
    <!-------MODAL MODIFICAR DEUDOR------>
    <!----------------------------------->  
    <div class="modal hide fade" id="myModal3">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos deudor</h3>
        </div>
                        
        <div class="modal-body"> 
            <fieldset>                
            <div class="control-group">
                <?php echo form_open('con_usuariovalido/modificar_deudor'); ?>
                <input type="hidden" name="CodDeudorMod" id="CodDeudorMod" readonly="true">
                <table align="center">
                    <tr>
                    <td><label>Tipo</label><input type="text"name="TipoDeudorMod" id="TipoDeudorMod" readonly="true"></td>
                    </tr>
                    <tr>
                    <td><label>Nombre</label><input type="text"name="NomDeudorMod" id="NomDeudorMod"></td>
                    <td><label id="ApeDeudorLab">Apellidos</label><input type="text"name="ApeDeudorMod" id="ApeDeudorMod"></td>
                    </tr>
                    <tr>
                    <td><label>Tipo ID tributario</label><?php echo form_dropdown('TipoRut', $TipoRut,"",'id="TipoRutDeudorMod"');?></td>
                    <td><label>ID tributario</label><input type="text" name="RutDeudorMod" id="RutDeudorMod" readonly="true"></td>
                    </tr>
                    <tr>
                    <td><label>Regi&oacute;n</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegChile', $RegChile,"",'id="CiudadDeudorMod"'));?></td>
                    <td><label>Comuna</label><input type="text" name="ComunaDeudorMod" id="ComunaDeudorMod"></td>
                    </tr>
                    <tr>
                    <td colspan="2"><label>Dirección</label><input type="text"  name="DirDeudorMod" id="DirDeudorMod"></td>
                    </tr>
                </table>
                <input type="submit" value="Guardar" />
                <?php echo form_close(); ?>
            </div>
            </fieldset>                        
        </div>
        
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>
    </div>   
    
    <!----------------------------------->
    <!-------MODAL AGREGAR CONTACTO------>
    <!----------------------------------->
    <div class="modal hide fade" id="myModal9">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Nuevo contacto</h3>
        </div>
                        
        <div class="modal-body">            
            <fieldset>                            
            <div class="control-group">
            <?php echo form_open('con_usuariovalido/agregar_contacto'); ?>            
                <table align="center">
                    <tr>       
                    <td><label>Deudor</label><?php echo iconv('UTF-8', 'ISO-8859-1',form_dropdown('deudores', $deudores,"",'id="DeudorContactoAdd"'));?></td>  
                   	<td><label>Tipo contacto</label><?php echo form_dropdown('TipoContacto', $TipoContacto,"",'id="TipoContactoAdd"');?></td>                        	
                   	</tr>
                   	<tr>
                   	<td><label>Nombres</label><input type="text"  name="NomContacto" id="NomContacto"></td>  
                    <td><label>Apellidos</label><input type="text"  name="ApeContacto" id="ApeContacto"></td>  
                   	</tr>
                    <td colspan="2"><label>Cargo</label><input type="text"  name="CargoContacto" id="CargoContacto"></td>            	
                   	</tr>
                    <tr>
                    <td><label>Mail</label><input type="text"  name="MailContacto" id="MailContacto"></td>  
                   	<td><label>Teléfono</label><input type="text" name="TelContacto" id="TelContacto"></td>                                	
                   	</tr>
                    <tr>
                    <td><label>Regi&oacute;n</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegChile', $RegChile,"",'id="RegionContacto"'));?></td>
                    <td><label>Dirección</label><input type="text" name="DirContacto" id="DirContacto"></td>
                    </tr> 
                    <tr>
                    <td colspan="2"><label>Observación</label><textarea id="ObsContacto" name="ObsContacto" style="width: 100%;"></textarea></td>
                    </tr>                     	
                </table>
                <input type="submit" value="Guardar" id="BtnAgregaContacto" />
                <?php echo form_close(); ?>
            </div>
            </fieldset>  
        </div>
    
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>   
    </div> 
    
    <!----------------------------------->
    <!--MODAL PARA EDITAR UN CONTACTO---->
    <!----------------------------------->
    <div class="modal hide fade" id="myModal16">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos contacto</h3>
        </div>
                            
        <div class="modal-body">
            <fieldset>
            <div class="control-group">   
                <?php echo form_open('con_usuariovalido/ModificaContacto'); ?>  
                <input type="hidden" name="codContacto" id="codContacto" />
                <table align="center">                    
                    <tr>                  
                    <td><label>Deudor</label><input name="deudoresContacto" id="deudoresContacto" disabled="true" /></td>  
                   	<td><label>Tipo contacto</label><input name="TipoContacto" id="TipoContacto" disabled="true" /></td>                        	
                   	</tr>
                   	<tr>
                   	<td><label>Nombres</label><input type="text"  name="nomContacto" id="nomContacto"/></td>  
                    <td><label>Apellidos</label><input type="text"  name="apellContacto" id="apellContacto"/></td>  
                   	</tr>
                    <td colspan="2"><label>Cargo</label><input type="text"  name="CargoContactoMod" id="CargoContactoMod"></td>            	
                   	</tr>
                    <tr>
                    <tr>
                    <td><label>Mail</label><input type="text"  name="mailContacto" id="mailContacto"/></td>  
                   	<td><label>Teléfono</label><input type="text" name="telContacto" id="telContacto"/></td>                                	
                   	</tr>
                    <tr>
                    <td><label>Regi&oacute;n</label><?php echo iconv('UTF-8', 'ISO-8859-1',form_dropdown('RegChile', $RegChile,"",'id="RegionModContacto"'));?></td>
                    <td><label>Dirección</label><input type="text" name="dirContacto" id="dirContacto"/></td>
                    </tr>
                    <tr>
                    <td colspan="2"><label>Observación</label><textarea id="obserContacto" name="obserContacto" style="width: 100%;"></textarea></td>
                    </tr>
                    <tr >
                    <td>
                        <table class="well" >
                        <tr>
                            <td><label title="">ESTADO CONTACTO: </label></td>
                            <td ><label id="lblEstadoContacto"></label></td>
                            <input  type="hidden"  id="flagVigencia" name="flagVigencia"/>
                        </tr>                        
                        </table>
                    </td>
                    <td>
                        <input  id="btnVigencia" type="button" value="" />
                        
                    </td>
                    </tr>
                </table>
                <input type="submit" value="Guardar" />
                <?php echo form_close(); ?>
            </div>
            </fieldset>
        </div>
        
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>        
    </div>
    
    
    <!----------------------------------->
    <!--MODAL PARA AGREGAR UN OPERADOR--->
    <!----------------------------------->
    <div class="modal hide fade" id="ModOperador">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Nuevo operador</h3>
        </div>
                        
        <div class="modal-body">
            <fieldset>  
            <?php echo form_open('con_usuariovalido/agregar_operador'); ?>
            <div class="well"> 
            <input type="hidden" name="CodOperador" id="CodOperador" /> 
                <table align="center">
                    	<tr>
                    	<td><label>Nombre</label><input class="input" type="text" name="NomOperador" id="NomOperador"></td>                    	
                        <td><label>Apellidos</label><input class="input" type="text" name="ApellOperador" id="ApellOperador"></td>                    	
                    	<tr>
                        <tr>
                        <td><label>Tipo ID tributario</label><?php echo form_dropdown('TipoRut', $TipoRut,"",'id=""');?></td>
                        <td><label>ID tributario</label><input type="text"  name="rutOperador" id="rutOperador" ></td> 
                        <tr>
                    	<tr>
                        <td><label>Regi&oacute;n</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegChile', $RegChile,"",'id="RegionAgregaOperado"'));?></td>
                    	<td><label>Direcci&oacute;n</label><input type="text"  name="DirOperador" id="DirOperador"></td>
                    	<tr>
                        <tr>
                    	<td><label>Teléfono</label><input type="text" name="TelefOperador" id="TelefOperador"></td>                    	
                                    	
                    	<tr>
                    </table> 
            </div>  
            
            <div class="well">
                <table align="center">
           	        <tr>
                   	<td><label>Usuario</label><input type="text" name="UsuarioOperador" id="UsuarioOperador"></td>
                   	<td><label>Clave</label><input type="text" name="ClaveOperador" id="ClaveOperador"></td>
                   	</tr>
                </table>
            </div>
            
            <button type="submit" class="btn" id="AgregaOperador">Guardar</button>
            <?php echo form_close(); ?> 
            </fieldset>
        </div>
        <script>
        </script>
        
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>
    </div> 
    
    <!----------------------------------->
    <!-------MODAL MODIFICA OPERADOR----->
    <!----------------------------------->  
    <div class="modal hide fade" id="mdlModificaOperador">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos operador</h3>
        </div>
                        
        <div class="modal-body">
            <fieldset>
            <div class="control-group">   
                <?php echo form_open('con_usuariovalido/modifica_operador'); ?>  
                <div class="well"> 
                    <input type="hidden" name="CodOperadorMod" id="CodOperadorMod" /> 
                    <input type="hidden" name="CodUsuarioMod" id="CodUsuarioMod" />
                <table align="center">
                    	<tr>
                    	<td colspan="2"><label>Nombre</label><input class="input" type="text" name="NomOperadorMod" id="NomOperadorMod"></td>                    	
                       
                    	<tr>
                        <tr>
                        <td><label>Tipo ID tributario</label><?php echo form_dropdown('TipoRut', $TipoRut,"",'id="TipoRutOperador"');?></td>
                        <td><label>ID tributario</label><input type="text"  name="rutOperadorMod" id="rutOperadorMod" ></td> 
                        <tr>
                    	<tr>
                        <td><label>Regi&oacute;n</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegChile', $RegChile,"",'id="RegionModOperador"'));?></td>
                    	<td><label>Direcci&oacute;n</label><input type="text"  name="DirOperadorMod" id="DirOperadorMod"></td>
                    	<tr>
                        <tr>
                    	<td><label>Teléfono</label><input type="text" name="TelefOperadorMod" id="TelefOperadorMod"></td>                    	
                                    	
                    	<tr>
                    </table> 
            </div>  
            
            <div class="well">
                <table align="center">
           	        <tr>
                   	<td><label>Usuario</label><input type="text" name="UsuarioOperadorMod" id="UsuarioOperadorMod"></td>
                   	<td><label>Clave</label><input type="text" name="ClaveOperadorMod" id="ClaveOperadorMod"></td>
                   	</tr>
                </table>
            </div>
                <input type="submit" value="Guardar" />
                <?php echo form_close(); ?>
            </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>
    </div>     
    
    <!----------------------------------->
    <!-------MODAL AGREGAR DOCUMENTO----->
    <!----------------------------------->  
    <div class="modal hide fade fade" id="myModal44" style="position: fixed;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Nuevo documento</h3>
        </div>
                        
        <div class="modal-body"> 
            <fieldset>
            <div class="control-group">
                <?php echo form_open('con_usuariovalido/agrega_doc'); ?>
                 <input type="hidden" name="num1Add" id="num1Add" />
                <input type="hidden" name="num2Add" id="num2Add" />
                <input type="hidden" name="num3Add" id="num3Add" />
                
                <input type="hidden" name="TDeudorHidden" id="TDeudorHidden" />
                <input type="hidden" name="NomDeudorHidden" id="NomDeudorHidden" />
                <input type="hidden" name="ApellDuedorHidden" id="ApellDuedorHidden" />
                <input type="hidden" name="DirDeudorHidden" id="DirDeudorHidden" />
                <input type="hidden" name="CiuDeudorHidden" id="CiuDeudorHidden" />
                <input type="hidden" name="ComDeudorHidden" id="ComDeudorHidden" />
                <input type="hidden" name="TelDeudorHidden" id="TelDeudorHidden" />
                <input type="hidden" name="MailDeudorHidden" id="MailDeudorHidden" />
                <input type="hidden" name="TipoTribDeudorHidden" id="TipoTribDeudorHidden" />
                <input type="hidden" name="IdTribDeudorHidden" id="IdTribDeudorHidden" />
                                                  
                <input type="hidden" name="LugRecauda" id="LugRecauda" />
                <input type="hidden" name="ConRecauda" id="ConRecauda" />
                <input type="hidden" name="HrRecauda" id="HrRecauda" />
                <input type="hidden" name="FormaPago" id="FormaPago" />
                
                <table align="center">
                    <tr>                    
                    <td><label>N° documento</label><input type="text" name="NumDocumento" id="NumDocumento"></td>
                    <td><label>Tipo</label><?php echo form_dropdown('TipoDocumento', $TipoDocumento,"",'id="TipoDocAdd"');?></td>
                    </tr>
                    <tr>   
                    <td>
                    <label>Fecha de vencimiento</label>
                        <div id="datetimepicker2" class="input-append date">
                            <input id="FechaVence" name="FechaVence" data-format="dd/MM/yyyy" type="text" style="width: 80%;"></input>
                            <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                        </div>
                    </td>
                    <td><label>Acción</label><?php echo form_dropdown('TipoAccion', $TipoAccion,"",'id="TipoAccion"');?></td>
                    </tr>
                    
                    <tr>
                    <td>
                    <label>Deudor</label><?php echo iconv('UTF-8', 'ISO-8859-1',form_dropdown('deudores', $deudores,"",'id="TipoDeudorDocAdd"'));?><a class="btn" id="AgregaDeudor" name="AgregaDeudor"><i class="icon-plus"></i></a>
                    </td>
                    <td><label>Próxima gestión</label>
                    <div id="datetimepicker3" class="input-append date">
                        <input id="ProxGes1" name="ProxGes1" data-format="dd/MM/yyyy" type="text" style="width: 80%;"></input>
                        <span class="add-on" id="Calendario"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                    </div>
                    </td>
                    </tr>
                    <tr>
                    <td colspan="2"><label>Monto</label><input type="text" name="Precio" id="Precio"></td>
                    </tr>
                    <tr>
                    <td colspan="2"><label>Observación</label><textarea id="observacion" name="observacion" style="width: 100%;"></textarea></td>
                    </tr>
                </table>
                <input class="btn" type="submit" value="Guardar" align="center"  id="btnGuardaDoc"/>
                <?php echo form_close(); ?>
            </fieldset>
        </div>    
                        
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>
    </div>
    
    <!----------------------------------->
    <!-------MODAL MODIFICA DOCUMENTO---->
    <!----------------------------------->  
    <div class="modal hide fade fade" id="myModal114">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos documento</h3>
        </div>
                        
        <div class="modal-body"> 
            <fieldset >
            <div class="control-group">
            
                <?php echo form_open('con_usuariovalido/modifica_doc'); ?>
                <input type="hidden" name="num1Mod" id="num1Mod" />
                <input type="hidden" name="num2Mod" id="num2Mod" />
                <input type="hidden" name="num3Mod" id="num3Mod" />
                
                <input type="hidden" name="LugRecaudaMod" id="LugRecaudaMod" />
                <input type="hidden" name="ConRecaudaMod" id="ConRecaudaMod" />
                <input type="hidden" name="HrRecaudaMod" id="HrRecaudaMod" />
                <input type="hidden" name="FormaPagoMod" id="FormaPagoMod" />
                
                <input type="hidden" name="CodigoDocumento" id="CodigoDocumento" />
                <table align="center">
                    <tr>
                    <td><label>N° documento</label><input class="input" type="text" name="Cod_doc" id="Cod_doc" readonly></td>
                    <td><label>Tipo</label><input type="text" name="Tipo_doc" id="Tipo_doc" class="input" readonly></td>
                    </tr>
                    <tr>
                    <td><label>Estado</label><input class="input" type="text" name="EstadoDoc" id="EstadoDoc" readonly/></td>
                    <td><label>Nuevo estado</label><?php echo form_dropdown('EstadoDeuda', $EstadoDeuda,"",'id="EstadoModificaDoc"');?></td>
                    </tr>
                    <tr>
                    <td><label>Deudor</label><input class="input" type="text" name="deudor_doc" id="deudor_doc" readonly><a id="btnDatosContacto" class="btn" data-toggle="modal" rel="tooltip" title="CONTACTOS"><i class="icon-user"></i></a></td>
                    <td><label>Vencimiento</label><input type="text"  name="ConPago_doc" id="ConPago_doc" readonly></td>
                    </tr>   
                    <tr>
                    <td>
                    <label>Acción</label>
                    <input type="text" name="accion_doc" id="accion_doc" readonly>
                    
                    <a id="btnDatosRecaudacion" class="btn" data-toggle="modal" rel="tooltip" title="DATOS RECAUDACION"><i class="icon-eye-open"></i></a>
                    </td>
                    <td><label>Nueva acción</label><?php echo form_dropdown('TipoAccionGestiona', $TipoAccion,"",'id="TipoAccionGestiona"');?></td>
                    </tr>
                    <tr>
                    <td><label>Próxima gestión</label><input type="text" name="pgest_doc" id="pgest_doc" readonly></td>
                    <td><label>Nueva gestión</label>
                         <div id="datetimepicker5" class="input-append date">
                         <input id="FechaNueva" name="FechaNueva" type="text" style="width: 80%;"></input>
                         <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                         </div>
                    </td>
                    </tr>
                    
                    <tr>
                    <td><label>Monto</label><input type="text" name="Precio_doc" id="Precio_doc" readonly></td>   
                    <td>
                    <label>Monto abonado</label><input type="text" name="MontoAbono" id="MontoAbono" style="width: 150px;" readonly>
                    <a id="btnNuevoAbono" class="btn" data-toggle="modal" rel="tooltip" title="NUEVO ABONO"><i class="icon-plus-sign"></i></a>
                    <a id="btnHistorialAbono" class="btn" data-toggle="modal" rel="tooltip" title="HISTORIAL ABONO"><i class="icon-list"></i></a>
                    </td>                 
                    </tr>
                    <tr>
                    <td><label>Monto remanente</label><input type="text" name="MontoRemanente" id="MontoRemanente" readonly></td>
                    <td><input type="hidden" name="NuevoAbonoOculto" id="NuevoAbonoOculto"  ></td>    
                    </tr>
                    <tr>
                        <td colspan="2"><label>Observación</label><textarea id="observacion_doc" name="observacion_doc" style="width: 100%;"></textarea></td>
                    </tr>
                </table>  
                <br />
                <table style="margin-left: 40px;">
                    <tr>
                        <td width="10%"><input type="checkbox" id="PublicarInfoComer" name="PublicarInfoComer" /></td>
                        <td width="90%"><label>Publicar en Informes Comerciales</label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="EstadoPublicaInfoComer" id="EstadoPublicaInfoComer" disabled="disabled"/></td>
                        <td><label>Actualmente Publicado en Informes Comerciales</label></td>
                    </tr>
                    
                </table>
                <input type="text" name="FlagInformeComercial" id="FlagInformeComercial" style="visibility: hidden;">
 
                <br />
                    
               
                </div>
               
                    <input class="btn" type="submit"  value="Guardar" align="center" id="GuardaModAccion" style="width: 5em;"/>
                    <input class="btn btn-danger" type=""  value="Derivar"  id="btnDerivar" style="width: 5em;"/>
                    <input class="btn btn-small" align=""  value="Historial" align="center" id="btnHistorial" style="width: 5em;"/>
                    <?php echo form_close(); ?>                     
                </div>
            </fieldset>
            <!--</form>-->
           
                        
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>
    </div>
   <script>
   
   $('#btnDerivarMasivo').bind("click",function(){
        
            var items = [];
            $("input[name='acciones[]']:checked").each(function(){items.push($(this).val());});
            $("#DocDerivaMasivo").val(items);
            
        $('#OperaCobranza').val(0);
        $('#EmpCobCombo').val(0);
        $('#EstadoDerivacion').prop('checked', false);
        $('#OperadorSeleccionado').prop('checked', false);
        
        $('#ModalConfirmacion').modal('show'); 
    });
   $('#btnDerivar').bind("click",function(){
        $('#ModalConfirmacion').modal('show'); 
        $('#DerivarCodigoDoc').val($('#CodigoDocumento').val());
        $('#OperaCobranza').val(0);
        $('#EmpCobCombo').val(0);
        $('#EstadoDerivacion').prop('checked', false);
        $('#OperadorSeleccionado').prop('checked', false);
    });
   </script>
    <!------------------------------------->
    <!--    MODAL MENSAJE CONFIRMACION   -->
    <!------------------------------------->
    
    <div class="modal hide fade" id="ModalConfirmacion">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Derivar documento</h4>
        </div>
        <div class="modal-body">
            <fieldset >
                <?php echo form_open('con_usuariovalido/DerivarDocumento'); ?>
                <h3>¡¡ADVERTENCIA!!</h3>
                <h6>Al derivar Ud. transferirá la gestión de la cobranza a otra empresa, y por tanto pueden incurrir en gastos adicionales.</h6>
                <h6>Además, perderá los privilegios para realizar cambios.</h6>
                <br />
                <h6>Si desea continúar, seleccione la siguiente información:</h6>
                <input type="hidden" name="DerivarCodigoDoc" id="DerivarCodigoDoc" />
                <input type="hidden" name="DocDerivaMasivo" id="DocDerivaMasivo" />  
                    <br />
                    <div class="well">
                    <table align="center">
                        <tr>
                        <td><?php echo form_dropdown('EmpresaCobranza', $EmpresaCobranza,"",'id="EmpCobCombo"');?></td>
                        <!--<td><input type="checkbox" id="EstadoDerivacion" disabled="disabled" />Escalado</td>-->
                        </tr>
                    </table>
                    </div>
                    
                    <a href="#" class="btn" data-dismiss="modal">Cancelar</a>
                    <input class="btn btn-success" type="submit"  value="Confirmar"  id="btnConfirmarMensaje" disabled="true" />
                <?php echo form_close(); ?>
            <fieldset >        
        </div>
    </div> 
    <script>
        $('#EmpCobCombo').bind("change", function() {
            if($('#EmpCobCombo').val()=="-1"){
                $('#btnConfirmarMensaje').attr("disabled",true);
                //alert("no habilita el boton.");
            }else{
                $('#btnConfirmarMensaje').attr("disabled",false);
                //alert("habilita el boton.");
            }
            //alert($('#EmpCobCombo').val());            
        });
      //  $('#btnInformeComercial').click(function(){
//            
//            var items = [];
//            $("input[name='acciones[]']:checked").each(function(){items.push($(this).val());});
//            $("#DocInformesComerciales").val(items);
//            $('#mdlMensajeConfirmacion').modal('show');
//            
//        });
    </script>
     <!------------------------------------->
    <!--    MODAL MENSAJE CONFIRMACION   -->
    <!------------------------------------->
    
    <div class="modal hide fade" id="mdlMensajeConfirmacion">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Derivar documento</h4>
        </div>
        <div class="modal-body">
            <fieldset >
                <?php echo form_open('con_usuariovalido/InformeComercialMasivo'); ?>
                
                <h6>Se pasará a informes comerciales todos los documentos seleccionados.</h6>
                <br />
                <!--<h6>Si desea continúar, seleccione la siguiente información:</h6>
                <input type="hidden" name="DerivarCodigoDoc" id="DerivarCodigoDoc" />-->
                <input type="hidden" name="DocInformesComerciales" id="DocInformesComerciales" />     
                
                
                <a href="#" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                <input class="btn btn-success" type="submit"  value="Confirmar"  id="btnConfirmarMensaje" />
                <?php echo form_close(); ?>
            <fieldset >        
        </div>
    </div> 
    <script>
           
        $('#btnInformeComercial').click(function(){
            
            var items = [];
            $("input[name='acciones[]']:checked").each(function(){items.push($(this).val());});
            $("#DocInformesComerciales").val(items);
            $('#mdlMensajeConfirmacion').modal('show');
            
        });
    </script>
    <!------------------------------------->
    <!--    MODAL DERIVAR A UN OPERADOR  -->
    <!------------------------------------->
     
    <div class="modal hide fade" id="mdlDerivarOperador">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Derivar documento</h4>
        </div>
        <div class="modal-body">
            <fieldset >
            <?php echo form_open('con_usuariovalido/AsignarOperador'); ?>
            
                <input type="hidden" id="CodDocumento" name="CodDocumento" />
                
                <?php echo form_dropdown('NombreOperador', $NombreOperador,"",'id="txtAsignarOperador"');?>    
                <br />
                <br />
                
                <a href="#" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                <input class="btn btn-success" type="submit"  value="Confirmar"  id="btnConfirmarMensaje" />
            <?php echo form_close(); ?>
            <fieldset >        
        </div>
    </div> 
    
    
    <div class="modal hide fade" id="mdlHistorial">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Historial documento</h4>
        </div>
        <div class="modal-body">
            <fieldset >
            <div class="well" id="TablaHistorial">
               
            </div>
            <fieldset >     
        
        </div> 
    
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>   
     </div>
     
     <!--MODAL QUE MUESTRA LA INFORMACION DE RECAUDACION-->
     <div class="modal hide fade" id="mdlDatosContactos">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Contactos</h4>
        </div>
        <div class="modal-body">
            <fieldset>          
                <div class="well" id="TablaContactos">               
                </div>
                <!--<a id="BtnNuevoContactoModal" class="btn" href="#myModal9" data-toggle="modal">Nuevo deudor</a>-->
            </fieldset>
        </div> 
     </div>
     
     <!--MODAL QUE MUESTRA EL HISTORIAL DE ABONOS-->
     <div class="modal hide fade" id="mdlHistorialAbonos">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Historial abonos</h4>
        </div>
        <div class="modal-body">
            <fieldset>          
                <div class="well" id="TablaHistorialAbonos">               
                </div>                
            </fieldset>
        </div> 
     </div>
     
     <!--MODAL QUE MUESTRA LA INFORMACION DE ABONOS A UN DOCUMENTO-->
     <div class="modal hide fade" id="mdlAbonoDeuda">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Abonar Deuda</h4>
        </div>
        <div class="modal-body">
            <fieldset>          
            <table align="center">
                <tr>
                <td colspan="2"><h6>Advertencia, una vez registrado, el abono no es reversible.</h6><label id="lblRemanente"></label></td>
                </tr>
                    <tr>
                    <td><label>Total deuda</label><input class="input" type="text" name="txtMontoDeuda" id="txtMontoDeuda" readonly></td>
                    <td><label>Remanente</label><input type="text" name="txtRemanente" id="txtRemanente" class="input" readonly></td>
                    </tr>
                    <tr>
                    <td><label>Monto abono</label><input class="input" type="text" name="txtMontoAbono" id="txtMontoAbono" onkeypress="ValidarNuevoAbono()" /></td>
                    <td><label>Fecha abono</label><input class="input" type="text" name="txtFechaPago" id="txtFechaPago" readonly="true"/></td>
                    </tr>
                    <tr>
                    <td colspan="2"><label id="msjNuevoAbono"></label></td>
                    </tr>
                    
            </table>
               <input class="btn btn-success" type="submit"  value="Abonar"  id="btnAbonarDeuda" />
            </fieldset>
        </div> 
     </div>
     
     
     
     <!----------------------------------->
    <!--MODAL PARA RECAUDAR INFORMACION-->
    <!----------------------------------->
    <div class="modal hide fade fade" id="mdlDatosRecaudacion" style="z-index: 1500;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos recaudación</h3>
        </div>
                  
        <div class="modal-body">
                <fieldset>
                <table align="center">
                    <tr>
                    <td><label>Lugar</label></td>
                    <td><input  type="text" id="MostrarLugarRecauda" name="MostrarLugarRecauda" readonly="true"/></td>
                    </tr>
                    <tr>
                    <td><label>Contacto</label></td>
                    <td><input  type="text" id="MostrarContactoRecauda" name="MostrarContactoRecauda" readonly="true"/></td>
                    </tr>
                    <tr>
                    <td><label>Hora</label></td>
                    <td><input type="text" id="MostrarHoraRecauda" name="MostrarHoraRecauda" readonly="true"/></td>
                    </tr>
                    <tr>
                    <td><label>Tipo de Documento de Pago</label></td>
                    <td><?php echo form_dropdown('TipoDocRecauda', $TipoDocRecauda,"",'id ="MostrarTipoDocRecauda"');?></td>
                    </tr>
                    <tr>
                    <td><label id="MostrarlblNum3lblNum1">Banco</label><?php echo iconv('UTF-8', 'ISO-8859-1',  form_dropdown('BancosChile', $BancosChile,"",'id="MostrartxtNum1"'));?></td>
                    <td><label id="MostrarlblNum3lblNum2">ID vale vista</label><input type="text" id="MostrartxtNum2" name="MostrartxtNum2" readonly="true" /></td>
                    </tr>
                    <tr >
                    <td><label id="MostrarlblNum3" >Contacto</label><input  type="text" id="MostrartxtNum3" name="MostrartxtNum3" readonly="true" /></td>
                    <td></td>
                    </tr>
                </table>
                
                </fieldset>
        </div>
    </div>  
     
     
     
     
     
    </body>
</ Html>