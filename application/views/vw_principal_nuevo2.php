<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="ISO-8859-1" />
<title><?php echo $pagina->titulo_pagina; ?></title>
<?php $pagina->html_head(1,1,1); ?>
<style type="text/css">
body {
    background-image: url('<?php echo base_url('/img/bg_home.png') ?>');
}
</style>
<link href="<?php echo base_url('/css/principal.css') ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('js/principal.js') ?>"></script>
</head>
<body>

<?php 
//MENU
$pagina->menu_genera('Evaluación','Menú Principal');
?>

<?php
$this->table->set_template($pagina->tablaTemplate);
?>
<?php echo br(2) ?>

<div class="container">
    <div class="page-header"><h1>MENU PRINCIPAL</h1></div>

<div class="row">
    <div id="capturaCuadro" class="col-md-4 cuadro">
        <div id="captura">
            <div class="centrado">
                <a class="btn btn-default" href="<?php echo site_url('principal/captura'); ?>"><?php echo img('/img/captura.jpg') ?></a>
            </div>
            <div class="rojo centrado">
                <a class="btn btn-default" href="<?php  echo site_url('principal/captura'); ?>">CAPTURA</a>
            </div>
            <br /><br />
        </div>
    </div>
    
    <div id="reportesCuadro" class="col-md-4 cuadro">
        <div class="centrado">
            <a class="btn btn-default" href="<?php echo site_url('principal/reporte'); ?>"><?php echo img('/img/reportes.jpg') ?></a>
        </div>
        <div class="rojo centrado">
            <a class="btn btn-default" href="<?php echo site_url('principal/reporte');?>">REPORTES</a>
        </div>
        <br /><br />
    </div>
    
    <div id="hospitalariosCuadro" class="col-md-4 cuadro">
        <div class="centrado">
            <a class="btn btn-default" href="<?php echo site_url('principal/reporte_ind_hosp_form'); ?>"><?php echo img('/img/ind_hospitalarios.jpeg') ?></a>
        </div>
        <div class="rojo centrado">
            <a class="btn btn-default" href="<?php echo site_url('principal/reporte_ind_hosp_form');?>">IND HOSPITALARIOS</a>
        </div>
        <br /><br />
    </div>
</div>
<?php $pagina->pie('Servicios de Salud de Zacatecas, Direccion de Planeación, Depto. de Programación y Evaluación') ?>
</div>
</body>
</html>
