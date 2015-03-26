<!DOCTYPE html>
<html>
    <head>
        <title>CobroGestión</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
               
        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen">
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/CB_Administrador.js"></script>
        
        <script type="text/javascript">
        // CAMBIO DE ESTADOS DE LOS FORMULARIOS
            $(document).ready(function(){
                //set checkbox estados vacios
                $('.clean').prop('checked', false);
                
                $('#AccionSeleccionado').bind("change", function() { 
                    var elegido=$(this).val();
                    $('.clean').prop('checked', false);
                    
                    $.ajax({
                        type: 'POST',
                        data: { Codigo : elegido },
                        url:'<?php echo base_url('index.php/administradorCON/SistemaCON/BuscaAccionesCheck');?>',
                        dataType:'json',
                        success : function(data){
                            //console.log("data.RelAcciones" + data.RelAcciones);
                            if(data.RelEstados != null) {
                                $.each(data.RelEstados, function( index, value ) {
                                    $('.accion_'+value).prop('checked', true);
                                });
                            }
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("No existen relaciones.");                          
                        }            
                    });
                });
                
                $('#EstadoSeleccionado').bind("change", function() {  
                     var elegido=$(this).val();
                     $('.clean').prop('checked', false);
                     
                    $.ajax({
                        type: 'POST',
                        data: { Codigo : elegido },
                        url:'<?php echo base_url('index.php/administradorCON/SistemaCON/BuscaEstadosCheck');?>',
                        dataType:'json',
                        success : function(data){
                            //console.log("data.RelAcciones" + data.RelAcciones);
                            if(data.RelAcciones != null) {
                                $.each(data.RelAcciones, function( index, value ) {
                                    $('.estado_'+value).prop('checked', true);
                                });
                            }
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("No existen relaciones.");                          
                        }            
                    });
                });
                
                
                $('#btnEstadosAcciones').click(function(){
                    $('#mdlEstadosAccion').modal();
                });
                
                $('#btnAccionesEstados').click(function(){
                    $('#mdlAccionEstados').modal();
                });
                
                
                $('#btnConfigEstados').click(function(){
                    var items = [];
                    $("input[name='estados[]']:checked").each(function(){items.push($(this).val());});
                    $("#AccionesSeleccionadas").val(items);
                    $('#seqEstado').val($('#EstadoSeleccionado').val());
                    $('#mdlConfirmacion').modal();
                    //$('#').val();        
                });
                
                 $('#btnConfigAcciones').click(function(){
                    var items = [];
                    $("input[name='acciones[]']:checked").each(function(){items.push($(this).val());});
                    $("#EstadosSeleccionados").val(items);
                    $('#seqAccion').val($('#AccionSeleccionado').val());
                    $('#mdlConfirmacion2').modal();
                    //$('#').val();        
                });
            
            });
        </script>
        
    </head>
    <!--CUERPO DE LA PAGINA-->
    <body>
    
    
    <br />
        <!--BARRA SUPERIOR-->
        <div class="row-fluid offset1">
            <div class="span1"></div>
            <div class="span10">
                <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="brand" href="#">ADMINISTRADOR</a>
                        
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
    
        <div class="well span12 offset2" style="margin:center; width:1000px;border-style:solid;border-width:5px;box-shadow: 2px 2px 5px #999;" >
        <!--BARRA LATERAL-->
        <div class="tabbable">
            <ul class="nav nav-tabs">
                 <li class="active"><a href="#1" data-toggle="tab">Clientes</a></li>
                 <li><a href="#PanelEmpresaCobranza" data-toggle="tab">Empresas de cobranza</a></li>
                 <li><a href="#2" data-toggle="tab">Sistema</a></li>
                 <li><a href="#3" data-toggle="tab">Historial</a></li>
                 <li><a href="#4" data-toggle="tab">Abonos</a></li>
            </ul>
            <!--CONTENIDO DE LOS PANELES-->
            <div class="tab-content">
                 <!--PANEL AGREGAR CLIENTES-->
                 <div class="tab-pane active" id="1" style="">
                    <?php if (isset($DatosClientes)){ ?>
                        <table class="table table-hover" >
                            <tr>
                                <th>Rut</th>
                                <th>Nombre</th>                                
                                <th>Tel&eacute;fono</th>
                                <th>Fecha creaci&oacute;n</th>
                                <th>Estado</th>
                                <th width="15%"></th>
                            </tr>
                            <?php foreach ($DatosClientes->result() as $row){?>
                            <tr>
                                <td><?php echo $row->rutCliente;?></td>
                                <td><?php echo $row->txtNomCliente;?></td>
                                <td><?php echo $row->txtTelCliente;?></td>
                                <td><?php echo $row->dtFechaCreacion;?></td>
                                <td><?php echo $row->txtNomEstadoCL?></td>
                                <td>
                                <a class="btn btnEditarCliente" href="<?php echo base_url('index.php/administradorCON/ClientesCON/CargaCliente/'.$row->codigo); ?>"><i class="icon-pencil"></i></a>
                                <a class="btn btn-danger" id="btnBloqueaCliente" rel="tooltip" title="BLOQUEAR CLIENTE" href="<?php echo base_url('index.php/administradorCON/ClientesCON/BloqueaCliente/'.$row->codigo); ?>"><i class="icon-lock"></i></a>
                                <a class="btn btn-success" id="" rel="tooltip" title="DESBLOQUEAR CLIENTE" href="<?php echo base_url('index.php/administradorCON/ClientesCON/DesbloqueaCliente/'.$row->codigo); ?>"><i class="icon-lock"></i></a>
                                <!--<a class="btn btn_eliminar"  href=""><i class="icon-trash"></i></a>-->
                                </td>
                            </tr>
                            <?php }?>
                        </table>
                    <?php }?>
                    <a class="btn" data-toggle="modal" href="#mdlAgregarCliente" >Agregar Cliente</a>
                    
                </div>
                
                
                <!--##########################-->
                <!--# PANEL EMPRESA COBRANZA #-->
                <!--##########################-->
                <div class="tab-pane" id="PanelEmpresaCobranza" style="">
                   <?php if (isset($DatosEmpresaCobranza)){ ?>
                        <table class="table table-hover" >
                            <tr>
                                <th>Rut</th>
                                <th>Nombre</th>                                
                                <th>Tel&eacute;fono</th>
                                <th>Fecha creaci&oacute;n</th>
                                <th>Estado</th>
                                <th width="15%"></th>
                            </tr>
                            <?php foreach ($DatosEmpresaCobranza->result() as $row){?>
                            <tr>
                                <td><?php echo $row->rutCliente;?></td>
                                <td><?php echo $row->txtNomCliente;?></td>
                                <td><?php echo $row->txtTelCliente;?></td>
                                <td><?php echo $row->dtFechaCreacion;?></td>
                                <td><?php echo $row->txtNomEstadoCL?></td>
                                <td>
                                <a class="btn btnEditarEmpresa" href="<?php echo base_url('index.php/administradorCON/ClientesCON/CargaCliente/'.$row->codigo); ?>"><i class="icon-pencil"></i></a>
                                <a class="btn btn-danger" id="btnBloqueaCliente" rel="tooltip" title="BLOQUEAR CLIENTE" href="<?php echo base_url('index.php/administradorCON/ClientesCON/BloqueaCliente/'.$row->codigo); ?>"><i class="icon-lock"></i></a>
                                <a class="btn btn-success" id="" rel="tooltip" title="DESBLOQUEAR CLIENTE" href="<?php echo base_url('index.php/administradorCON/ClientesCON/DesbloqueaCliente/'.$row->codigo); ?>"><i class="icon-lock"></i></a>
                                <!--<a class="btn btn_eliminar"  href=""><i class="icon-trash"></i></a>-->
                                </td>
                            </tr>
                            <?php }?>
                        </table>
                    <?php }?>
                   
                   <a class="btn" data-toggle="modal" href="#mdlAgregarEmpresaCobranza" >Agregar Empresa de Cobranza</a>
                    
                </div>
                <!--##########################-->
                <!--#      PANEL SISTEMA     #-->
                <!--##########################-->
                <div class="tab-pane" id="2">
                    <table>
                        <tr>
                        <td colspan="2">Configuración del sistema</td>
                        </tr>
                        <tr>
                        <td>
                            <div class="well">
                                <a class="btn" data-toggle="modal" id="btnEstadosAcciones" name="btnEstadosAcciones">Estados - Acciones</a>  
                                <a class="btn" data-toggle="modal" id="btnAccionesEstados" name="btnAccionesEstados">Acciones - Estados</a>  
                            </div>
                        </td>
                        </tr>
                    </table>
                    <p>Configuración del sistema</p>
                    <br />
                    <a class="btn" data-toggle="modal" href="#myModal2" >Tipo clientes</a>
                    <a class="btn" data-toggle="modal" href="#myModal2" >Tipo clientes</a>
                    
                    
                    <!--VENTANA EMERGENTE NUEVO DEUDOR-->
                    <div class="modal hide" id="myModal2">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        
                        <div class="modal-body">
                            <fieldset>
                                <div class="control-group">
                                     <?php echo form_open('con_administrador/agregar_tipo_cliente'); ?>
                                    <div class="clearfix">
                                        <input type="text" placeholder="Tipo" name="tipo" id="tipo">
                                    </div>
                                        
                                    <textarea class="span7" rows="3" placeholder="Descripción " name="desc" id="desc" ></textarea>
     
                                    <button type="submit" class="btn">Agregar</button>
                                    <?php echo form_close(); ?>
                                </div>
                            </fieldset>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
                        </div>
                    </div>   <!--FIN VENTANA EMERGENTE-->
                    
                    <a class="btn" data-toggle="modal" href="#myModal6" >Etapa cobranza</a>
                    <!--VENTANA EMERGENTE NUEVO DEUDOR-->
                    <div class="modal hide" id="myModal6">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        
                        <div class="modal-body">
                            <fieldset>
                                <div class="control-group">
                                     <?php echo form_open('con_administrador/agregar_etapa_cobranza'); ?>
                                    <div class="clearfix">
                                        <input type="text" placeholder="Tipo" name="tipo" id="tipo">
                                    </div>
                                        
                                    <textarea class="span7" rows="3" placeholder="Descripción " name="desc" id="desc" ></textarea>
     
                                    <button type="submit" class="btn">Agregar</button>
                                    <?php echo form_close(); ?>
                                </div>
                            </fieldset>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
                        </div>
                    </div>   <!--FIN VENTANA EMERGENTE-->
                </div><!--FIN PANEL CONTACTO COBRANZA--> 
                
                
                <!--PANEL HISTORIAL-->
                <div class="tab-pane" id="3">
                    <p>Historial de acciones</p>
                    <?php if (isset($Historial)){ ?>
                        <table class="table table-hover">
                            <tr>
                                <th>Operador</th>
                                <th>Accion</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                            <?php foreach ($Historial->result() as $row){?>
                            <tr>
                                <td><?php echo $row->Usuario;?></td>
                                <td><?php echo $row->Accion;?></td>
                                <td><?php echo $row->Fecha;?></td>
                                <td>
                                    <a class="btn btn_editar"  href="<?php// echo base_url('index.php/con_administrador/carga_cliente/'.$row->codigo); ?>"><i class="icon-pencil"></i>Editar</a>
                                    <a class="btn btn_eliminar"  href="<?php// echo base_url('index.php/con_administrador/elimina_cliente/'.$row->codigo); ?>"><i class="icon-trash"></i>Eliminar</a>
                                </td>
                            </tr>
                            </tr>
                            <?php }?>
                        </table>
                    <?php }?> 
                    
                    
                    <!--FILTRO-->
                    <div class="well">
                         <p>Filtro</p>
                         <div class="btn-group">
                            <button class="btn">Cliente</button>
                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Acción<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Llamar</a></li>
                                <li><a href="#">Visitar</a></li>
                                <li><a href="#">E-mail</a></li>
                            </ul>
                        </div>
                    </div>
                    <hr />
                    <hr />
                    <hr />
                    
                </div><!--FIN PANEL HISTORIAL-->
                
                <!---------------------------->
                <!--    PANEL ABONOS    ------>
                <!---------------------------->
                <div class="tab-pane" id="4">
                    <p>Historial de abonos</p>
                    <?php if (isset($OBJDocumento)){ ?>
                        <table class="table table-hover">
                            <tr>
                                <th>ID Documento</th>
                                <th>Monto</th>
                                <th>Total Abonos</th>
                              </tr>
                            <?php foreach ($OBJDocumento->result() as $row){?>
                            <tr>
                                <td><?php echo $row->Codigo;?></td>
                                <td><?php echo $row->Monto;?></td>
                                <td><?php echo $row->NumDocumento;?></td>
                                <td>
                                    <a class="btn btn_eliminar"  href="<?php echo base_url('index.php/administradorCON/con_administrador/EliminarAbono/'.$row->Codigo); ?>"><i class="icon-trash"></i>Eliminar</a>
                                </td>
                            </tr>
                            </tr>
                            <?php }?>
                        </table>
                    <?php }?> 
                </div>
            </div><!--FIN CONTENIDO PANELES-->
        </div><!--FIN BARRA LATERAL-->
    </div>
    
    <!----------------------------------->
    <!-------MODAL NUEVO CLIENTE--------->
    <!----------------------------------->  
    <div class="modal hide" id="mdlAgregarCliente">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Nuevo cliente</h3>
        </div>
                        
        <div class="modal-body">
            <fieldset>
            <div class="control-group">
                <?php echo form_open('administradorCON/ClientesCON/NuevoCliente'); ?>
                <table align="center">
                    <tr>
                        <td><label>Nombre</label><input type="text" name="txtNomCliente" id="txtNomCliente" ></td> 
                        <td><label>RUT</label><input type="text" name="rutCliente" id="rutCliente" ></td>                   
                    </tr>
                    <tr>
                        <td><label>Ciudad</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegionesChile', $RegionesChile,"",'id="CiudadAgregaCliente"'));?></td> 
                        <td><label>Direcci&oacute;n</label><input type="text" name="txtDirCliente" id="txtDirCliente" ></td>     
                    </tr>
                    <tr>
                        <td colspan="2"><label>Tel&eacute;fono</label><input type="text" name="txtTelCliente" id="txtTelCliente" ></td>
                    </tr>                    
                    <tr>
                        <td colspan="2">
                        <br />
                        <h4>Datos administrador</h4>
                        <div class="well">
                            <table>
                            <tr>
                            <td><label>Usuario</label><input type="text" name="txtUsuCliente" id="txtUsuCliente" ></td> 
                            <td><label>Contraseña</label><input type="text" name="ClaveCliente" id="ClaveCliente" ></td>   
                            </tr>
                            </table>
                        </div>
                        </td>                
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <a href="#" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                        <button type="submit" class="btn btn-success" id="btnAgregarCliente">Agregar</button>
                        </td>
                    </tr>
                </table>
                <?php echo form_close(); ?>
            </div>
            </fieldset>
        </div>
    </div> 
    
    <!----------------------------------->
    <!-----MODAL MODIFICAR CLIENTE------->
    <!----------------------------------->  
    <div class="modal hide fade fade" id="mdlModificarCliente">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos cliente</h3>
        </div>
                        
        <div class="modal-body">
            <fieldset>
            <div class="control-group">
                <?php echo form_open('administradorCON/ClientesCON/ModificarCliente'); ?>
                <input type="hidden" id="CodClienteMod" name="CodEmpresaMod" />
                <input type="hidden" id="TipoCliente" name="TipoCliente" />
                <table align="center">
                    <tr>
                    <td><label>Nombre</label><input type="text" name="txtNomClienteMod" id="txtNomClienteMod" ></td> 
                    <td><label>RUT</label><input type="text" name="rutClienteMod" id="rutClienteMod" ></td>                   
                    </tr>
                    <tr>
                    <td><label>Ciudad</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegionesChile', $RegionesChile,"",'id="CiudadAgregaCliente"'));?></td> 
                    <td><label>Direcci&oacute;n</label><input type="text" name="txtDirClienteMod" id="txtDirClienteMod" ></td>     
                    </tr>
                    <tr>
                    <td colspan="2"><label>Tel&eacute;fono</label><input type="text" name="txtTelClienteMod" id="txtTelClienteMod" ></td>
                    </tr>                    
                    
                    <tr>
                    <td></td>
                    <td>
                    <a href="#" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                    <button type="submit" class="btn btn-success" id="btnAgregarCliente">Modificar</button>
                    </td>
                    </tr>
                </table>
                <?php echo form_close(); ?>
            </div>
            </fieldset>
        </div>
    </div> 
    
    <!----------------------------------->
    <!--MODAL NUEVO EMPRESA DE COBRANZA-->
    <!----------------------------------->  
    <div class="modal hide" id="mdlAgregarEmpresaCobranza">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Nuevo empresa de cobranza</h3>
        </div>
                        
        <div class="modal-body">
            <fieldset>
            <div class="control-group">
                <?php echo form_open('administradorCON/EmpresaCobranzaCON/NuevoCliente'); ?>
                <table align="center">
                    <tr>
                        <td><label>Nombre</label><input type="text" name="txtNomCliente" id="txtNomCliente" ></td> 
                        <td><label>RUT</label><input type="text" name="rutCliente" id="rutCliente" ></td>                   
                    </tr>
                    <tr>
                        <td><label>Ciudad</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegionesChile', $RegionesChile,"",'id="CiudadAgregaCliente"'));?></td> 
                        <td><label>Direcci&oacute;n</label><input type="text" name="txtDirCliente" id="txtDirCliente" ></td>     
                    </tr>
                    <tr>
                        <td colspan="2"><label>Tel&eacute;fono</label><input type="text" name="txtTelCliente" id="txtTelCliente" ></td>
                    </tr>                    
                    <tr>
                        <td colspan="2">
                        <br />
                        <h4>Datos administrador</h4>
                        <div class="well">
                            <table>
                            <tr>
                            <td><label>Usuario</label><input type="text" name="txtUsuCliente" id="txtUsuCliente" ></td> 
                            <td><label>Contraseña</label><input type="text" name="ClaveCliente" id="ClaveCliente" ></td>   
                            </tr>
                            </table>
                        </div>
                        </td>                
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <a href="#" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                        <button type="submit" class="btn btn-success" id="btnAgregarCliente">Agregar</button>
                        </td>
                    </tr>
                </table>
                <?php echo form_close(); ?>
            </div>
            </fieldset>
        </div>
    </div> 
    
    <!----------------------------------->
    <!-----MODAL MODIFICAR EMPRESA------->
    <!----------------------------------->  
    <div class="modal hide fade fade" id="mdlModificarEmpresa">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Datos empresa de cobranza</h3>
        </div>
                      
        <div class="modal-body">
            <fieldset>
            <div class="control-group">
                <?php echo form_open('administradorCON/ClientesCON/ModificaEmpresa'); ?>
                <input type="hidden" id="CodEmpresaMod" name="CodEmpresaMod" />
                <input type="hidden" id="TipoOperador" name="TipoOperador" />
                <table align="center">
                    <tr>
                    <td><label>Nombre</label><input type="text" name="txtNomEmpresaMod" id="txtNomEmpresaMod" ></td> 
                    <td><label>RUT</label><input type="text" name="rutEmpresaMod" id="rutEmpresaMod" ></td>                   
                    </tr>
                    <tr>
                    <td><label>Ciudad</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('RegionesChile', $RegionesChile,"",'id=""'));?></td> 
                    <td><label>Direcci&oacute;n</label><input type="text" name="txtDirEmpresaMod" id="txtDirEmpresaMod" ></td>     
                    </tr>
                    <tr>
                    <td colspan="2"><label>Tel&eacute;fono</label><input type="text" name="txtTelEmpresaMod" id="txtTelEmpresaMod" ></td>
                    </tr>                    
                    
                    <tr>
                    <td></td>
                    <td>
                    <a href="#" class="btn btn-danger" data-dismiss="modal">Cerrar</a>
                    <button type="submit" class="btn btn-success" id="">Modificar</button>
                    </td>
                    </tr>
                </table>
                <?php echo form_close(); ?>
            </div>
            </fieldset>
        </div>
    </div> 
    
    
    <div class="modal hide fade fade" id="mdlEstadosAccion">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Configuraci&oacute;n Estados - Acciones</h3>
        </div>
                        
        <div class="modal-body">        
            <fieldset>
            <div class="control-group">
                
                <table>
                <tr>
                <td align="right" width="50%;"><label>Estados</label></td>
                <td align="left" width="50%;"><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('EstadoDeuda', $EstadoDeuda,"",'id="EstadoSeleccionado"'));?></td>
                </tr>
               
                <br />
                <tr>
                <td align="right"><input type="checkbox" name="estados[]" value="1" id="Estados[]" class="estado_1 clean"/></td>
                <td><label>LLamar</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="estados[]" value="2" id="Estados[]" class="estado_2 clean"/></td>
                <td><label>Enviar email</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="estados[]" value="3" id="Estados[]" class="estado_3 clean"/></td>
                <td><label>Recaudar</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="estados[]" value="5" id="Estados[]" class="estado_5 clean"/></td>
                <td><label>Visitar</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="estados[]" value="6" id="Estados[]" class="estado_6 clean"/></td>
                <td><label>Repactar</label></td>
                </tr>
                 <tr>
                <td align="right"><input type="checkbox" name="estados[]" value="7" id="Estados[]" class="estado_7 clean"/></td>
                <td><label>Enviar carta</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="estados[]" value="8" id="Estados[]" class="estado_8 clean"/></td>
                <td><label>Cobranza Judicial</label></td>
                </tr>
                </table>   
                
                <button type="submit" class="btn btn-success" id="btnConfigEstados">Guardar</button>
                <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
            </div>
            </fieldset>
        </div>
     </div> 
     
     <div class="modal hide fade fade" id="mdlAccionEstados">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Configuraci&oacute;n Acciones - Estados</h3>
        </div>
                        
        <div class="modal-body">        
            <fieldset>
            <div class="control-group">
                
                <table>
                <tr>
                <td align="right" width="50%;"><label>Acciones</label></td>
                <td align="left" width="50%;"><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('AccionesDeuda', $AccionesDeuda,"",'id="AccionSeleccionado"'));?></td>
                </tr>
                <br />
          
                <tr>
                <td align="right"><input type="checkbox" name="acciones[]" id="acciones[]" class="accion_4 clean" value="4"/></td>
                <td><label>Pagado</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="acciones[]" id="acciones[]" class="accion_5 clean" value="5"/></td>
                <td><label>Anulado</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="acciones[]" id="acciones[]" class="accion_6 clean" value="6"/></td>
                <td><label>Protestado</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="acciones[]" id="acciones[]" class="accion_1 clean" value="1"/></td>
                <td><label>Bloqueado</label></td>
                </tr>
                <tr>
                <td align="right"><input type="checkbox" name="acciones[]" id="acciones[]" class="accion_0 clean" value="0"/></td>
                <td><label>En Proceso</label></td>
                </tr>                
                </table>   
                
                <button type="submit" class="btn btn-success" id="btnConfigAcciones">Guardar</button>
                <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
            </div>
            </fieldset>
        </div>
     </div> 
     
    
    <!------------------------------------->
    <!--    MODAL MENSAJE CONFIRMACION   -->
    <!------------------------------------->
    
    <div class="modal hide fade" id="mdlConfirmacion">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Derivar documento</h4>
        </div>
        <div class="modal-body">
            <fieldset >
                <?php echo form_open('administradorCON/SistemaCON/ConfigEstadosAcciones'); ?>
                <h3>¡¡ADVERTENCIA!!</h3>
                <h6>Si guarda estos cambios borrara las relaciones establecidas para este estado y se conservaran las ingresadas.</h6>
               
                <br />
               
                <input type="hidden" name="AccionesSeleccionadas" id="AccionesSeleccionadas" />
                <input type="hidden" name="seqEstado" id="seqEstado" />
                
                
                <a href="#" class="btn" data-dismiss="modal">Cancelar</a>
                <input class="btn btn-success" type="submit"  value="Confirmar"/>
                <?php echo form_close(); ?>
            <fieldset >        
        </div>
    </div> 
    
    <div class="modal hide fade" id="mdlConfirmacion2">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4>Derivar documento</h4>
        </div>
        <div class="modal-body">
            <fieldset >
                <?php echo form_open('administradorCON/SistemaCON/ConfigAccionesEstados'); ?>
                <h3>¡¡ADVERTENCIA!!</h3>
                <h6>Si guarda estos cambios borrara las relaciones establecidas para esta accion y se conservarán las ingresadas.</h6>
               
                <br />
               
                <input type="hidden" name="EstadosSeleccionados" id="EstadosSeleccionados" />
                <input type="hidden" name="seqAccion" id="seqAccion" />
                
                
                <a href="#" class="btn" data-dismiss="modal">Cancelar</a>
                <input class="btn btn-success" type="submit"  value="Confirmar"/>
                <?php echo form_close(); ?>
            <fieldset >        
        </div>
    </div> 
    
    </body>
</html>


                    <!--<tr>
                    <td><label>Tipo cliente</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('TipoCliente', $TipoCliente,"",'id="TipoAgregaCliente"'));?></td>
                    <td><label>Estado cliente</label><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('EstadoCliente', $EstadoCliente,"",'id="EstadoAgregaCliente"'));?></td>
                    </tr>
                    <tr id="pnlPermisos">
                        <td colspan="2">
                        <div class="well"><input type="checkbox" value="value" title="title" /></div>
                        </td>
                    </tr>-->