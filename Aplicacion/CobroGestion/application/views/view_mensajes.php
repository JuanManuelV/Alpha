<!DOCTYPE html>
<html>
    <head>
        <title>CobroGestión</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen">
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
   
   <!--JSON QUE CARGA LOS DATOS DEL contacto-->        
        <script type="text/javascript">
            $(document).ready(function(){
                $('.ver_mensaje').click(function() {
                    //alert("asdasd->"+ $(this).attr("href"));
                    $.ajax({
                        type : 'POST',
                        url : $(this).attr("href"),
                        dataType : 'json',
                        success : function(data){
                            $('#cod_msj').val(data.cod);
                            $('#emisor_msj').val(data.emisor);
                            $('#fecha_msj').val(data.fecha);
                            $('#msj_msj').val(data.msj);
                            $('#asunto_msj').val(data.asunto);
                            $('#conv_msj').val(data.conv);
                             
                            $('#VerDoc').modal();
                        },
               
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("error"+errorThrown);
                        }
                    });
                    return false;
               });
            });
        </script>
    </head>
    
    <!--CUERPO DE LA PAGINA-->
    <body>
        <!--BARRA SUPERIOR-->
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container">
                    <ul class="nav" >
                        <li><a href="index.html"><i class="icon-home"></i>Inicio</a></li>
                        <li><a href="<?php echo base_url()."index.php/con_usuariovalido"; ?>">Cuenta</a></li>
                        <li><a href="Mensajes.html"><i class="icon-envelope"></i>Mensajes</a></li>
                        <li><a href="#">Ayuda</a></li>
                        <li><a href="#">Acerca de...</a></li>
                        <li class="pull-right"><a href="<?php echo base_url()."index.php/con_login/logout"; ?>"><i class="icon-off"></i>Salir</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!--BUSQUEDA DE MENSAJES-->
        <div class="span8 offset4">
            <form class="well form-search">
                <input type="text" class="input-medium search-query">
                <a class="btn"><i class="icon-search"></i></a>
            </form>
        </div>
        <br /><br /><br /><br /><br />
        
        <!--BARRA LATERAL-->
        <div class="tabbable tabs-left">
            <ul class="nav nav-tabs">
                <li><a href="#1" data-toggle="tab">Redactar nuevo</a></li>
                <li class="active"><a href="#2" data-toggle="tab">Mensajes</a></li>
                <li><a href="#3" data-toggle="tab">Otra opcion</a></li>
            </ul>
            <!--CONTENIDO PANELES-->
            <div class="tab-content">
                <!--PANEL NUEVO MENSAJE-->
                <div class="tab-pane" id="1">
                    <a class="btn" href="#"><i class="icon-trash"></i>Borrar</a>
                    
                   
                    <fieldset>
                        <div class="control-group">
                            <div class="well">
                                <?php echo form_open('con_mensajes/enviar'); ?>
                                <label>Para:<?php echo form_dropdown('Operadores', $Operadores);?></label>
                                <div class="clearfix">
                                    <label>Asunto:</label>
                                    <input type="text" name="Asunto" id="Asunto">
                                </div>
                                <div class="clearfix">
                                    <label>Mensaje</label>
                                    <textarea id="msj" name="msj"></textarea>
                                </div>
                                <input type="submit" value="Enviar" /> 
                                <?php echo form_close(); ?> 
                            </div>
                        </div>  
                    </fieldset>
                </div>
    
                
                <!--PANEL MENSAJES NUEVOS-->
                <div class="tab-pane active" id="2"> 
                    <?php if (isset($Mensajes)){ ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>De</th>
                            <th>Asunto</th>
                            <th>Fecha</th>
                            <th>Opciones</th>
                        </tr>
                        <?php foreach ($Mensajes->result() as $row){?>
                        <tr>
                            <th><?php echo $row->Emisor;?></th>
                            <td><?php echo $row->Asunto;?></td>
                            <td><?php echo $row->FechaEnvio;?></td>
                            <td>
                                <div class="btn-group">
                                    <a class="btn ver_mensaje"  href="<?php echo base_url('index.php/con_mensajes/mostrar_msj/'.$row->Codigo); ?>"><i class="icon-pencil"></i></a>   
                                    <a class="btn" href="<?php echo base_url('index.php/con_mensajes/elimina_msj/'.$row->Conversacion);?>"><i class="icon-trash"></i></a>
                                </div>
                            </td>     
                        </tr>
                        <?php }?>
                    </table>
                    <?php }?> 
                </div><!--FIN PANEL MENSAJES NUEVOS-->  
                
                <!--PANEL MENSAJES OTRA OPCION-->
                <div class="tab-pane" id="3"> 
                    <p>OTRAS OPCIONES</p>
                </div><!--FIN PANEL OTRAS OPCIONES-->              
            </div><!--FIN CONTENIDOS PANEL-->
        </div><!--FIN BARRA LATERAL-->
        <div class="modal hide" id="VerDoc">
                        <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                            <p>Documentos</p>
                        </div>
                        
                        <div class="modal-body"> 
                            <fieldset>
                                <div class="control-group">
                                
                                    <?php echo form_open('con_mensajes/responder'); ?>
                                   
                                   <div class= "well" >
                                       <div class="row">
                                                <div class="span6">
                                                    <div class="row">
                                                        <div class="span3">
                                                            <div class="clearfix">
                                                                  <label>Codigo</label>
                                                                  <input type="text" name="cod_msj" id="cod_msj" >
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="clearfix">
                                                                <label>Conversacion</label>
                                                                <input type="text" name="conv_msj" id="conv_msj">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                       <div class="row">
                                            <div class="span6">
                                                <div class="row">
                                                    <div class="span3">
                                                        <div class="clearfix">
                                                            <label>De</label>
                                                            <input type="text" name="emisor_msj" id="emisor_msj">
                                                        </div>
                                                    </div>
                                                    <div class="span3">
                                                        <div class="clearfix">
                                                            <label>Fecha</label>
                                                            <input type="text" name="fecha_msj" id="fecha_msj">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="clearfix">
                                            <label>Asunto</label>
                                            <input type="text" name="asunto_msj" id="asunto_msj" >
                                         </div>
                                        
                                    </div>
                                    
                                    <div class= "well" >
                                        <label>Mensaje</label>
                                        <textarea id="msj_msj" name="msj_msj"></textarea>
                                        <label>Respuesta</label>
                                        <textarea id="respuesta_msj" name="respuesta_msj"></textarea>
                                    </div> 
                                    
                                     <button type="submit" class="btn">Responder</button>
                                     <?php echo form_close(); ?>
                                </div>
                            </fieldset>
                        </div>    
                        
                        <div class="modal-footer">
                            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
                        </div>
                    </div><!--FIN DEVNATAN EMERGENTE AGREGAR DOCUMENTO-->
    </body>
</html>