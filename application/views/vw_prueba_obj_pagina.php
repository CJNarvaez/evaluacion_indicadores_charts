<!DOCTYPE HTML>
<html>
<head>
<title>
<?php echo $pagina->titulo_pagina; ?>
</title>
<link href="<?php echo base_url('css/nav-justificado.css') ?>" rel="stylesheet" type="text/css" />
<?php echo $pagina->jquery; ?>
<?php echo $pagina->bootstrap; ?>
<style type="text/css">
<!--
.jumbotron
{
    background-image: url('<?php echo $pagina->encabezado_fondo; ?>');
}
body
{
    background-color: lightgrey;
}
.prueba
{
    background-color: white;
}
-->
</style>
</head>
<body>
<div class="container">
<?php echo $pagina->menu_genera('Evaluación','Menú Principal','justificado'); ?>

<?php echo $pagina->encabezado('Evaluación','Esta es una prueba de un "Subtitulo"'); ?>

<div class="prueba">
<div class="page-header">
<h1>Este es un encabezado</h1>
</div>

<?php echo $nac_x_cesarea->nombre.": ".$nac_x_cesarea->total; ?>
</div>
<?php echo $pagina->pie('SSZ Direccion de Planeacion 2014'); ?>
</div><!-- /container -->
</body>
</html>