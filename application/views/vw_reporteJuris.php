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
            echo $botonPdf;

           /* if($reporte == 'juris'){
                if($agregarHC)
                    echo '<center><a class="btn btn-success" href="'.site_url('principal/reporte_juris_hc_pdf/'.$mes.'/'.$anio.'/'.$juris.'/'.$reporte).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
                else
                    echo '<center><a class="btn btn-success" href="'.site_url('evaluacion/reporte_juris_pdf/'.$mes.'/'.$anio.'/'.$juris.'/'.$reporte).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
            }
            else
                if($reporte == 'hc')
                    echo '<center><a class="btn btn-success" href="'.site_url('principal/reporte_hc_pdf/'.$mes.'/'.$anio.'/'.$hc.'/'.$reporte).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
                else
                    echo '<center><a class="btn btn-success" href="'.site_url('principal/reporte_2n_pdf/'.$mes.'/'.$anio.'/'.$hc.'/'.$reporte).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
            */
            echo heading('FECHA DE CORTE DE LA INFORMACION '.$corte.' '.$anio,5,'class="text-center"');
        ?>
    </body>
</html>