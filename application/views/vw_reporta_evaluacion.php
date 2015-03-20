<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="ISO-8859-1" />
<title><?php echo $pagina->titulo_pagina ?></title>
<link href="<?php echo base_url('/css/web20.css') ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
	$(document).ready(function(){
	   $(".boton").button();
	});
-->
</script>
<style type="text/css">
<!--
	h1
    {
        text-align: center;
    }
    .boton
    {
        font-size: 12px;
    }
    h1,h2,h3,h4,h5,h6
    {
        font-weight: bolder;
        color: maroon;
    }
-->
</style>
<style type="text/css">
<!--
    body
{
    background-position: 0px 50px;
} 
    
-->
</style>
<script type="text/javascript" src="<?php echo base_url('/js/publicarJuris.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/js/alertify.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/evaluacion.js') ?>"></script>
<!-- include the core styles -->
<link rel="stylesheet" href="<?php echo base_url('/css/alertify.core.css')?>" />
<!-- include a theme, can be included into the core instead of 2 separate files -->
<link rel="stylesheet" href="<?php echo base_url('/css/alertify.default.css')?>" />

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
<?php

$pagina->menu_genera('Evaluación','Reportes');

$tmpl = array (
                    'table_open'          => '<table border="1" align="center" cellpadding="4" cellspacing="0">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr class="odd">',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );

$this->table->set_template($tmpl);

//echo heading($mesInformacion);

echo br(4);
echo heading($titulo);
echo $this->table->generate();
echo br();

echo $botonPublicar;	
?>
<div id="espera" class="text-center"><?php echo img('/img/loading.gif') ?><h1>GUARDANDO...</h1></div>
<div id="resultado"></div>
<?php
// obsoleto
//echo '<a class="btn btn-success" href="'.site_url('evaluacion/publicarJuris/'.$mes.'/'.$anio).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> PUBLICAR MES</a></center>';

if($reporte == 'juris'){
    if($agregarHC)
        echo '<center><a class="btn btn-success" href="'.site_url('principal/reporte_juris_hc_pdf/'.$mes.'/'.$anio.'/'.$juris.'/'.$reporte).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
    else
        echo '<center><a class="btn btn-success" href="'.site_url('principal/reporte_juris_pdf/'.$mes.'/'.$anio.'/'.$juris.'/'.$reporte).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
}
else
    if($reporte == 'hc')
        echo '<center><a class="btn btn-success" href="'.site_url('principal/reporte_hc_pdf/'.$mes.'/'.$anio.'/'.$hc.'/'.$reporte).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
    else
        echo '<center><a class="btn btn-success" href="'.site_url('principal/reporte_2n_pdf/'.$mes.'/'.$anio.'/'.$hc.'/'.$reporte).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';

echo heading('FECHA DE CORTE DE LA INFORMACION '.$corte.' '.$anio,5);
?>
<br />
</body>
</html>