<!DOCTYPE html>
<html>
    <head>
        <title>CobroGestión</title>
        <!--
<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen">
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/less.js"></script>
-->
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">

        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url(); ?>css/OtrasTablas/jquery.dataTables.css" rel="stylesheet" media="screen">
         
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/OtrasTablas/jquery.dataTables.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/MenuDesplegable.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/Tablas.css">
        
        
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-tooltip.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-confirmation.js"></script>

		<script type="text/javascript" charset="utf-8" language="javascript" src="<?php echo base_url(); ?>js/Tablas.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablesorter.js"></script>
        <script src="<?php echo base_url(); ?>js/CobroGestion.js"></script>


        
        <style type="text/css" media="screen"> 
        #Fondo { 
            background-image: url(../img/fondo.jpg); 
            background-repeat: no-repeat; 
            background-position: left top; 
            /*padding-top:68px; 
            margin-bottom:50px; 
            width: auto;
            height: auto;*/
            
            
            background-size: cover;
            -moz-background-size: cover;
            -webkit-background-size: cover;
            -o-background-size: cover;
         
        } 
        .PanelIzquierdo{
             background-color:white;
            filter:alpha(opacity=50); 
            opacity:0.5;
            /*background-color: transparent;  rgb(227, 255, 224)*/
            font-style: inherit;
            color: black;
            border-color:black;
            border: solid;
            width: 300px;
            height: 400px;
            
        }
        .PanelDerecho{            
            background-color:white;
            filter:alpha(opacity=50); 
            opacity:0.5;
            font-style: inherit;
            color: black;
            border-color:black;
            border: solid;
            width: 300px;
            margin-left: 100px; 
            height: 350px;
            
            -webkit-border-radius: 20px;
            -moz-border-radius: 20px;
            border-radius: 20px;
            
        }
        </style>
        
    </head>
    
    <!--CUERPO DE LA PAGINA-->
    <!--<body id="Fondo">-->
    <body >
    </div>
    <table align="center" style="margin-top: 100px">
        <tr>
            <td>
            <div class="PanelDerecho">
                <br />
                <?php echo validation_errors();?>
                <fieldset>
                        <?php
	                       if (isset($mensaje)){
                                echo '<div class="alert alert-error">'.$mensaje.'</div>';
                            }
                        ?>
                 <?php echo form_open('con_login/LogearUsuario'); ?>
                <table align="center" style="width: 250px;">
                    <tr>
                    <td colspan="2"><h1 align="center">INGRESO</h1></td>
                    </tr>
                    <tr>
                     <td width="40%"><input type="radio"  id="chkEmpresaCli"/>Cliente</td>
                    <td width="60%"><input type="radio" id="chkEmpresaCob"/>Empresa de cobranza</td>
                    </tr>
                    <tr>
                    <td colspan="2"> <br /> <br /></td>
                    </tr>
                    <tr>
                    <td><label id="lblEmpresaLogin">EMPRESA</label></td>
                    <td><?php echo iconv('UTF-8', 'ISO-8859-1', form_dropdown('EmpresaCliente', $EmpresaCliente,"",'id="EmpresaLogin"'));?></td>
                    </tr>
                    <tr>                    
                    <td><label id="lblUsuario">USUARIO</label></td>
                    <td><input type="text" id="txtUsuario" name="txtNomOperador" id="txtNomOperador" style="width: 150px;"></td>
                    </tr>
                    <tr>
                    <td><label id="lblClave">CLAVE</label></td>
                    <td><input type="password" id="txtClave" name="txtClaveOperador" id="txtClaveOperador" style="width: 150px;"></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="right"><button type="submit" class="btn" id="btnLogin">Ingresar</button></td>
                    </tr>
                    <td colspan="2">
                        <a href="">¿No puede acceder a su cuenta?</a>
                        <br />
                        <a href="">Ir a versión de prueba</a>
                        </td>
                    </tr>
                </table>
                 <?php echo form_close(); ?>
                
                    
                    <script> 
                        $(document).ready(function(){
                            $('#EmpresaLogin').hide();
                            $('#lblEmpresaLogin').hide();    
                            
                            $('#txtUsuario').hide();
                            $('#lblUsuario').hide(); 
                            $('#lblClave').hide();
                            $('#txtClave').hide(); 
                            $('#btnLogin').hide();
                            
                        });   
                            $('#chkEmpresaCli').click(function(){
                                $('#chkEmpresaCob').prop('checked', false);
                                    $("#EmpresaLogin").empty();
               
                                    $.ajax({
                                            type : 'POST',
                                            data:{ TipoEmpresa: "Cliente" },
                                            url : '<?php echo base_url('index.php/con_login/EmpresasLogin'); ?>',
                                            dataType : 'json',
                                            success : function(data){
                                                $.each(data.EmpresaCliente, function( index, value ) {
                                                    //alert("indice".index."      valor".value);
                                                   $('#EmpresaLogin').append($('<option>', {
                                                       value: index,
                                                       text : value
                                                   }));
                                               });
                    
                                            },                                   
                                            error : function(XMLHttpRequest, textStatus, errorThrown) {
                                                alert("error"+errorThrown);
                                            }
                                   });
                                $('#EmpresaLogin').show();
                                $('#lblEmpresaLogin').show();    
                                $('#txtUsuario').show();
                                $('#lblUsuario').show(); 
                                $('#lblClave').show();
                                $('#txtClave').show(); 
                                $('#btnLogin').show();
                            });
                            
                            $('#chkEmpresaCob').click(function(){
                                $('#chkEmpresaCli').prop('checked', false);
                                    $("#EmpresaLogin").empty();
               
                                    $.ajax({
                                            type : 'POST',
                                            data:{ TipoEmpresa: "Cobranza" },
                                            url : '<?php echo base_url('index.php/con_login/EmpresasLogin'); ?>',
                                            dataType : 'json',
                                            success : function(data){
                                                $.each(data.EmpresaCobranza, function( index, value ) {
                                                   $('#EmpresaLogin').append($('<option>', {
                                                       value: index,
                                                       text : value
                                                   }));
                                               });
                    
                                            },                                   
                                            error : function(XMLHttpRequest, textStatus, errorThrown) {
                                                alert("error"+errorThrown);
                                            }
                                   });
                                $('#EmpresaLogin').show();
                                $('#lblEmpresaLogin').show();    
                                $('#txtUsuario').show();
                                $('#lblUsuario').show(); 
                                $('#lblClave').show();
                                $('#txtClave').show(); 
                                $('#btnLogin').show();   
                                
                            });
                    </script>
               
            </div>
            </td>
            
            <td>
            <div class="PanelDerecho" style="visibility: hidden;">
                <br />
                <h1 align="center">INGRESO</h1>
                 <?php echo validation_errors();?>
                  <fieldset>
                        <?php
	                       if (isset($mensaje)){
                                echo '<div class="alert alert-error">'.$mensaje.'</div>';
                            }
                        ?>
                        <?php echo form_open('con_login/process_login'); ?>
                <table align="center" >
                    <br /><br />
                    
                    <tr>
                        <td>USUARIO</td>
                        <td><input type="text" name="nombre" id="nombre" style="width: 150px;"></td>     
                    </tr>
                    <tr>
                        <td>CLAVE</td>
                        <td><input type="password" name="clave" id="clave" style="width: 150px;"></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="right"><button type="submit" class="btn">Ingresar</button></td>
                    </tr>
                    
                    <tr>
                    <td colspan="2">
                        <a href="">¿No puede acceder a su cuenta?</a>
                        <br />
                        <a href="">Ir a versión de prueba</a>
                        </td>
                    </tr>
                </table>
                <?php echo form_close(); ?>
            </div>
            </td>
            
        </tr>
    </table>
 
    </body>
</html>