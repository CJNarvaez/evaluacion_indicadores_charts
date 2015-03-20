<!DOCTYPE html>
<html lang="es">
  <head>
  <meta charset="ISO-8859-1" />

    <title>Sistema de Evaluación</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('/css/bootstrap.min.css') ?>" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('/css/cover.css') ?>" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <style type="text/css">
<!--
body 
{
    background-image: url("<?php echo base_url('img/portada2-2.jpg') ?>");
    background-size: 100% 100%;
}
-->
</style>
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand"><?php echo img(base_url('img/logo2.png'));?></h3>
              <ul class="nav masthead-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Contact</a></li>
              </ul>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading"><strong>SISTEMA DE EVALUACIÓN</strong></h1>
            <p class="lead">Dirección de Planeación</p>
            <p class="lead">
              <a href="<?php echo site_url('principal/principal') ?>" class="btn btn-lg btn-success">Iniciar</a>
            </p>
          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>Servicios de Salud, Dirección de Planeación, 2014</a>.</p>
            </div>
          </div>

        </div>

      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="<?php echo base_url('js/jquery-2.1.1.min.js') ?>"></script>
    <script src="<?php echo base_url('js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('js/docs.min.js') ?></script>
  </body>
</html>