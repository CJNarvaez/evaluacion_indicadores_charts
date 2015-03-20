<!DOCTYPE html>
<html lang="es">
  <head>
  <meta charset="ISO-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>.:EVALUACION:. Iniciar Sesion</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('/css/font-awesome.css') ?>" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('/css/signin.css') ?>" rel="stylesheet" />

<style type="text/css">
<!--
	body {
background-image: url('<?php  echo base_url('/img/estadistica.jpg') ?>');
}
-->
</style>  
  </head>
<body>
<div class="jumbotron text-center"><h2><img src="<?php echo base_url('/img/logo.png') ?>" />Dirección de Planeación <small>Sistema de Evaluación</small></h2></div>
</div>                          
    <div class="container">      
      <?php $attributes = array('class' => 'form-signin', 'role' => 'form');
            echo form_open("auth/login", $attributes); ?>
        <?php echo $message; ?>
        <h2 class="form-signin-heading">Introduce tus datos</h2>
        <input name="identity" id="identity" type="user" class="form-control" placeholder="Usuario" required autofocus >
        <input name="password" type="password" class="form-control" placeholder="Password" required>
        <label class="checkbox">
          <input type="checkbox" value="1" id="remember" name="remember"> Recuerda mis datos
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesion</button>
      </form>

    </div> <!-- /container -->


<!-- Site footer -->
  <div class="footer text-center">
        <p>&copy; Servicios de Salud de Zacatecas, Direccion de Planeación, Depto. de Programación y Evaluación</p>
  </div>
  </body>
</html>