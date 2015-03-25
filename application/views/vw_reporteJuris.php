<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="ISO-8859-1" />
        <?php $pagina->titulo_pagina ?>
        <?php $pagina->html_head(1,1); ?>
        <link href="<?php echo base_url('/css/web20.css') ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('/css/evaluacion.css') ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url('js/evaluacion.js') ?>"></script>
        <style type="text/css">
<!--
    .slc_amarillo {
        background-color:  yellow;
        color:yellow;  
    }
	.slc_azul {
        background-color: blue;
        color:blue;  
    }
    .slc_rojo {
        background-color: red;
        color:red;  
    }
    .slc_verde {
        background-color: green;
        color:green;  
    }
-->
</style>
    </head>
    <body>
        <?php $pagina->menu_genera('Evaluacion','Reportes') ?>
        <br />
        <?php echo $titulo ?>
        <?php 
            echo $reporteJuris;
        ?>
        <div class="container">
            <div class="col-xs-offset-4 col-xs-4 alert alert-info text-center">
            <strong>Rangos de Semaforización</strong>
            <center>
            <table>
                <tr><td><div class="azul"></div></td><td>Mayor de 120%</td></tr>
                <tr><td><div class="verde"></div></td><td>80 - 120%</td></tr>
                <tr><td><div class="amarillo"></div></td><td>60 - 79.99 %</td></tr>
                <tr><td><div class="rojo"></div></td><td>0 - 59.99 %</td></tr>
            </table></center>
            </div>
        </div>
        <?php
            echo $botonPdf;            
            echo heading('FECHA DE CORTE DE LA INFORMACION '.$corte.' '.$anio,5,'class="text-center"');
        ?>
        
    </body>
</html>