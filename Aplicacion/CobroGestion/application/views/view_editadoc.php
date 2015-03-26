<!DOCTYPE html>
<HTML  lang = "es" >
    <head>
        <title>CobroGestión</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen">
         
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/MenuDesplegable.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/Tablas.css">

		<script type="text/javascript" charset="utf-8" language="javascript"
         src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" language="javascript" src="<?php echo base_url(); ?>js/Tablas.js"></script>

    </head>
    
    <!--CUERPO DE LA PAGINA-->
    <body onload="">
            
        <input type="text" value="<?php $acciones ?>"/>
    
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
                  
    </body>
</ Html>
