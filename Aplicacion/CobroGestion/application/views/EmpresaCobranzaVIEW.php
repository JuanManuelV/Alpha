<!DOCTYPE html>
<html>
    <head>
        <title>CobroGestión</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
               
        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen">
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/Administrador.js"></script>
        <script src="<?php echo base_url(); ?>js/CB_EmpresaCobranza.js"></script>
        <script src="<?php echo base_url(); ?>js/CobroGestion.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablesorter.js"></script>
        <!--JSON QUE CARGA LOS DATOS DEL CLIENTE--> 
        <script type="text/javascript">

            $(document).ready(function(){
                
                
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
                
                $('#btnHistorial').click(function(){
                $('#mdlHistorial').modal();
                
            });
                      //REALIZA UN NUEVO ABONO
    $('#btnNuevoAbonoEMP').click(function(){
        //alert("toma boton");
        $('#txtMontoAbonoEMP').val('');
            
        $('#txtMontoDeudaEMP').val($('#Precio_doc').val());
        $('#txtRemanenteEMP').val($('#MontoRemanente_doc').val());
       //         $('#lblRemanente').val($('#MontoRemanente').val());
        $('#mdlAbonoDeudaEMP').modal('show');            
    });  
    
    
                //MUESTRA HISTORIAL ABONOS
            $('#btnHistorialAbonoEMP').click(function(){ 
                //$('#TablaHistorialAbonos').empty();
                $('#mdlHistorialAbonosEMP').modal('show');
            });
    
     //   AGREGA NUEVO ABONO Y CALCULA REMANENTE
           $('#btnAbonarDeudaEMP').click(function(){
           // alert($('#txtMontoDeudaEMP').val().replace('$ ','').replace(/[.]/gi,''));
//            alert($('#MontoAbono_doc').val().replace('$ ','').replace(/[.]/gi,''));
//            alert($('#txtMontoAbonoEMP').val().replace('$ ','').replace(/[.]/gi,''));
//            alert($('#txtRemanenteEMP').val().replace('$ ','').replace(/[.]/gi,''));
                $('#msjNuevoAbonoEMP').text("");
                $('#btnNuevoAbonoEMP').hide();
            
                var NuevoAbono=0,TotalAbono=0,Suma=0,MontoDeuda,Remanente,NuevoRemanente;
                
                MontoDeuda=Number($('#txtMontoDeudaEMP').val().replace('$ ','').replace(/[.]/gi,''));
                TotalAbono=Number($('#MontoAbono_doc').val().replace('$ ','').replace(/[.]/gi,''));
                NuevoAbono=Number($('#txtMontoAbonoEMP').val().replace('$ ','').replace(/[.]/gi,''));
                Remanente=Number($('#txtRemanenteEMP').val().replace('$ ','').replace(/[.]/gi,''));
               
                if(NuevoAbono>Remanente){
                    $('#msjNuevoAbonoEMP').append("Abono superior a la deuda.");  
                }else{
                    Suma=TotalAbono+NuevoAbono;
                    NuevoRemanente=MontoDeuda-Suma;
                    $('#MontoAbono_doc').val("$ "+addCommas(Suma));
                    $('#MontoRemanente_doc').val("$ "+addCommas(NuevoRemanente));
                    $('#mdlAbonoDeudaEMP').modal('hide');
                    
                    $('#NuevoAbonoOcultoEMP').val(NuevoAbono);
                }
            });    
                
                
$('#NuevoEstadoDeuda').bind("change", function() {
        $("#TipoAccionGestionaEMP").empty();
        
        var elegido=$(this).val();
                    
        $('#TipoAccionGestionaEMP').append($('<option>', {
            value: "-1",
            text : " - Selecione una acción - "
        }));
        
        //alert(elegido);
        $.ajax({
            type: 'POST',
            data: { Codigo : elegido },
            url:'<?php echo base_url('index.php/empresaCobranzaCON/DocumentosCON/Prueba/');?>',
            //url:'<?php echo base_url('index.php/con_usuariovalido/Prueba/');?>',
            dataType:'json',
              
            success : function(data){
                //alert(data.NuevasAcciones);
                $.each(data.NuevasAcciones, function( index, value ) {
                    $('#TipoAccionGestionaEMP').append($('<option>', {
                        value: index,
                        text : value
                    }));
                });
            },
            
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                ////alert("error"+errorThrown);
            }           
        });
    });
            });
        </script>
        
    </head>
    <!--CUERPO DE LA PAGINA-->
    <body>
    
    <input type="hidden" value="<?php echo $tipoopera ?>" id="TipoUsuario" name="TipoUsuario" >
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
        
        <div class="well span12 offset2" style="margin:center; width:1300px;border-style:solid;border-width:5px;box-shadow: 2px 2px 5px #999;" >
        <!--BARRA LATERAL-->
        <div class="tabbable">
            <ul class="nav nav-tabs">
                 <li class="active"><a href="#PanelDocumentos" data-toggle="tab">Documentos recibidos</a></li>
                 <li><a href="#PanelOperador" id="pnlOperador" data-toggle="tab">Operadores</a></li>
            </ul>
            
            <!--CONTENIDO DE LOS PANELES-->
            <div class="tab-content" >                 
                <div class="tab-pane active" id="PanelDocumentos" style=""> 
                    <?php if (isset($DocumentosDerivados)){ ?>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TablaAcciones">
                            <tr>
                            
                            <th>N° Doc</th><th>Deudor</th><th>Monto deuda</th><th width="81px;">Deuda Remanente</th><th width="50px;">Dias de Mora</th><th>Tipo</th><th>Acción</th><th>Estado</th><th>Fecha de emisión</th><th>Próxima gestión</th><th width="15%;">Opciones</th>
                            </tr>
                            <?php foreach ($DocumentosDerivados->result() as $row){
                                
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
                                ?>
                            <tr>
                                <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->NumDocumento);?></a></td>
                                <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->nombres)." ".iconv('UTF-8', 'ISO-8859-1',$row->apellidos);?></a></td>
                                <td><?php echo "$ ".iconv('UTF-8', 'ISO-8859-1',$row->Monto);?></a></td>
                                <td><?php echo "$ ".number_format($row->Remanente, 0, '', '.');?></td>
                                <td><?php echo $days?></td>
                                <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->Nombre); ?></a></td>
                                <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->NomAccion);?></a></td>
                                <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->txtNomEstadoDeuda);?></a></td>
                                <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->FechaEmision);?></a></td>
                                <td><?php echo iconv('UTF-8', 'ISO-8859-1',$row->ProxGestion);?></a></td>
                                
                                <td>
                                <a class="btn btnEditarDocumento btn-success" id=""  rel="tooltip" title="GESTIONAR ACCION"  href="<?php echo base_url('index.php/empresaCobranzaCON/DocumentosCON/CargaDocumento/'.$row->Documento); ?>"><i class="icon-pencil"></i></a>
                                <a class="btn btnDevolverCliente btn-primary"  rel="tooltip" title="DEVOLVER AL CLIENTE" id="<?php echo $row->Documento ?>"><i class="icon-envelope"></i></a>
                                <a class="btn DerivarAOperador btn-warning"  rel="tooltip" title="ASIGNAR A UN OPERADOR" id="<?php echo $row->Documento ?>" ><i class="icon-envelope"></i></a>
                                <a class="btn DerivarAdmin btn-warning " rel="tooltip" title="DERIVAR AL ADMINISTRADOR"  id="<?php echo $row->Documento ?>"><i class="icon-envelope"></i></a>
                                </td>
                            </tr>
                            <?php }?>
                        </table>
                    <?php }?>
                               
                </div>
                
                <div class="tab-pane" id="PanelOperador" style=""> 
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
                            <a class="btn EditaOperador btn-success pull-left" rel="tooltip" title="EDITAR OPERADOR"  href="<?php echo base_url('index.php/empresaCobranzaCON/EmpresaCobranzaCON/carga_operador/'.$row->codigo); ?>"><i class="icon-pencil"></i></a>   
                               
                            <a class="btn DocumentosOperador btn-warning pull-right" rel="tooltip" title="VER DOCUMENTOS ASIGNADOS" ><i class="icon-list"></i></a>
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



    <!----------------------------------->
    <!-------MODAL MODIFICA DOCUMENTO---->
    <!----------------------------------->  
    <div class="modal hide fade fade" id="mdlModificaDocDerivado">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos documento</h3>
        </div>
                        
        <div class="modal-body"> 
            <fieldset >
            <div class="control-group">
            
                <?php echo form_open('empresaCobranzaCON/DocumentosCON/EditaDocumento'); ?>
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
                    <td><label>Estado</label><input class="input" type="text" name="EstadoDoc" id="EstadoDoc" readonly></td>
                    <td><label>Nuevo estado</label><?php echo form_dropdown('EstadoDeuda', $EstadoDeuda,"",'id="NuevoEstadoDeuda"');?></td>
                    </tr>
                    <tr>
                    <td><label>Deudor</label><input class="input" type="text" name="deudor_doc" id="deudor_doc" readonly><a id="btnDatosContacto" class="btn" data-toggle="modal" rel="tooltip" title="CONTACTOS"><i class="icon-user"></i></a></td>
                    <td><label>Vencimiento</label><input type="text"  name="ConPago_doc" id="ConPago_doc" readonly></td>
                    </tr>     
                    <tr>
                    <td><label>Acción</label><input type="text" name="accion_doc" id="accion_doc" readonly></td>
                    <td><label>Nueva acción</label><?php echo form_dropdown('TipoAccionGestiona', $TipoAccion,"",'id="TipoAccionGestionaEMP"');?></td>
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
                    <label>Monto abonado</label><input type="text" name="MontoAbono_doc" id="MontoAbono_doc" style="width: 150px;" readonly>
                    <a id="btnNuevoAbonoEMP" class="btn" data-toggle="modal" rel="tooltip" title="NUEVO ABONO"><i class="icon-plus-sign"></i></a>
                    <a id="btnHistorialAbonoEMP" class="btn" data-toggle="modal" rel="tooltip" title="HISTORIAL ABONO"><i class="icon-list"></i></a>
                    </td>                 
                    </tr>
                    <tr>
                    <td><label>Monto remanente</label><input type="text" name="MontoRemanente_doc" id="MontoRemanente_doc" readonly></td>
                    <td><input type="hidden" name="NuevoAbonoOcultoEMP" id="NuevoAbonoOcultoEMP"  ></td>    
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
                <input type="text"  id="FlagInformeComercial" name="FlagInformeComercial" style="visibility: hidden;"/>
                <br />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                    <input class="btn btn-success" type="submit"  value="Guardar" align="center" id="GuardaModAccion" />                    
                    <input class="btn btn-small" align=""  value="Historial" align="center" id="btnHistorial" style="width: 5em;"/>
                    <?php echo form_close(); ?>
                </div>
            </fieldset>
            <!--</form>-->
        </div>    
    </div>
    

    
    <!------------------------------------->
    <!--    MODAL MENSAJE CONFIRMACION   -->
    <!------------------------------------->
    
    <div class="modal hide fade fade" id="mdlVolverDerivacion">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Devolver documento</h4>
        </div>
        <div class="modal-body">
            <fieldset >
                <?php echo form_open('empresaCobranzaCON/DocumentosCON/DevuelveCliente'); ?>
                <h6>Devolverá, al cliente original, los permisos de edición del documento.</h6>
                <br />
                
                <input type="hidden" name="seqDocumento" id="seqDocumento" />     
                
                
                <a href="#" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                <input class="btn btn-success" type="submit"  value="Confirmar"  id="btnConfirmarMensaje" />
                <?php echo form_close(); ?>
            <fieldset >        
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
            <?php echo form_open('empresaCobranzaCON/EmpresaCobranzaCON/agregar_operador'); ?>
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
                <?php echo form_open('empresaCobranzaCON/EmpresaCobranzaCON/modifica_operador'); ?>  
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
            <?php echo form_open('empresaCobranzaCON/EmpresaCobranzaCON/AsignarOperador'); ?>
            
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
    
    <!------------------------------------->
    <!-- MODAL DERIVAR AL ADMINISTRADOR  -->
    <!------------------------------------->
    
    <div class="modal hide fade" id="mdlDerivarAdministrador">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Derivar documento</h4>
        </div>
        <div class="modal-body">
            <fieldset >
            <?php echo form_open('empresaCobranzaCON/EmpresaCobranzaCON/DerivarAdministrador'); ?>
            
                <h6>Ustede perderá los permisos de edición del documento.</h6>
                <input type="hidden" id="SeqDocumento" name="SeqDocumento" />   
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
    
    </body>
    
    <!--MODAL QUE MUESTRA LA INFORMACION DE ABONOS A UN DOCUMENTO-->
     <div class="modal hide fade" id="mdlAbonoDeudaEMP">
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
                    <td><label>Total deuda</label><input class="input" type="text" name="txtMontoDeudaEMP" id="txtMontoDeudaEMP" readonly></td>
                    <td><label>Remanente</label><input type="text" name="txtRemanenteEMP" id="txtRemanenteEMP" class="input" readonly></td>
                    </tr>
                    <tr>
                    <td><label>Monto abono</label><input class="input" type="text" name="txtMontoAbonoEMP" id="txtMontoAbonoEMP" onkeypress="ValidarNuevoAbono()" /></td>
                    <td><label>Fecha abono</label><input class="input" type="text" name="txtFechaPagoEMP" id="txtFechaPagoEMP" readonly="true"/></td>
                    </tr>
                    <tr>
                    <td colspan="2"><label id="msjNuevoAbonoEMP"></label></td>
                    </tr>
                    
            </table>
               <input class="btn btn-success" type="submit"  value="Abonar"  id="btnAbonarDeudaEMP" />
            </fieldset>
        </div> 
     </div>
     
          <!--MODAL QUE MUESTRA EL HISTORIAL DE ABONOS-->
     <div class="modal hide fade" id="mdlHistorialAbonosEMP">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Historial abonos</h4>
        </div>
        <div class="modal-body">
            <fieldset>          
                <div class="well" id="TablaHistorialAbonosEMP">               
                </div>                
            </fieldset>
        </div> 
     </div>
</html>