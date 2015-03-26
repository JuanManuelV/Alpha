<!DOCTYPE html>
<html>
    <head>
        <title>CobroGestión</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" media="screen">
        <script src="<?php echo base_url(); ?>js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
    </head>
    
    <!--CUERPO DE LA PAGINA-->
    <body>
        <!--BARRA SUPERIOR-->
        <div class="navbar">
            <div class="navbar-inner">
                    <ul class="nav pull-right" >
                        <li class="active" ><a href="<?php echo base_url()."index.php/con_inicio"; ?>"><i class="icon-home"></i>Inicio</a></li>
                        <li><a href="#">Nosotros</a></li>
                        <li><a href="<?php echo base_url()."index.php/con_login"; ?>">Empresas</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
            </div>
        </div>
        
        <!--PANELES (IZQ: weas)(DER:paso de imagenes)-->
        <div class="container-fluid">
            <div class="row-fluid">
                <!--PANLES IZQUIERDO-->
                <div class="span6">
         
                </div>
                <!--PANEL DERECHO-->
                <div class="span6 offset4">
                        <div id="myCarousel" class="carousel slide">
                            <!-- Carousel items -->
                            <div class="carousel-inner">
                                <div class="active item"><img  src="img/carousel1.jpg" alt="banner1" /></div>
                                <div class="item"><img  src="img/carousel2.jpg" alt="banner2" /></div>
                                <div class="item"><img  src="img/carousel3.jpg" alt="banner3" /></div>
                                <div class="item"><img  src="img/carousel4.jpg" alt="banner4" /></div>
                            </div>
                            <!-- Carousel nav -->
                            <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                            <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
                        </div>
                </div>
            </div>
        </div><!--FINAL PANELES IZQUIERDO Y DERECHO-->

    </body>
</html>