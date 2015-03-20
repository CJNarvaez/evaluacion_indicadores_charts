<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="ISO-8859-1" />
<title><?php echo $pagina->titulo_pagina ?></title>
<?php $pagina->html_head(1,1,1); ?>

<style type="text/css">
<!--
    h1,h2,h3,h4,h5,h6
    {

        font-weight: bolder;

        color: maroon;

    }
body
{
    background-image: url('<?php echo base_url('/img/bg_home.png') ?>');
    background-repeat: no-repeat;
    background-position: 0px 50px;
} 
-->
</style>
</head>
<body>
<?php $pagina->menu_genera('Evaluación','Ind. Hospitalarios');?>
<div class="container">
<div class="col-md-12">
<?php 
echo br(3);
$attributes = array('class' => 'form-horizontal', 'role' => 'form');
            echo form_open("principal/reporte_ind_hosp", $attributes); ?>

<!-- Form Name -->
<div class="page-header"><h1 class="text-center">INDICADORES HOSPITALARIOS</h1></div>

<?php

$tmpl = array (
                    'table_open'          => '<table border="0" cellpadding="4" cellspacing="0" align="center">',

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

//$um = array(180 => 180);
/*$meses = array('01' => 'ENERO',
                '02' => 'FEBRERO',
                '03' => 'MARZO',
                '04' => 'ABRIL',
                '05' => 'MAYO',
                '06' => 'JUNIO',
                '07' => 'JULIO',
                '08' => 'AGOSTO',
                '09' => 'SEPTIEMBRE',
                '10' => 'OCTUBRE',
                '11' => 'NOVIEMBRE',
                '12' => 'DICIEMBRE');*/

$this->db->select('anio');
$this->db->distinct();
$consulta = $this->db->get('saeh_reporte_ant');

foreach($consulta->result() as $ren)
    $anio[$ren->anio] = $ren->anio;

$ren = array('data' => '<center>UNIDAD MEDICA</center>', 'colspan' => '2');
$ren2 = array('data' => form_dropdown('um',$um), 'colspan' => '2', 'align' => 'center');
$this->table->set_heading($ren);
$this->table->add_row($ren2);
$mes_celda = array('data' => 'MES', 'align' => 'right');
$anio_celda = array('data' => 'AÑO', 'align' => 'right');
$this->table->add_row($mes_celda,form_dropdown('mes',$meses));
$this->table->add_row($anio_celda,form_dropdown('anio',$anio));

echo '<div class="form-group"><label class="col-md-4 control-label" for="um">UNIDAD MEDICA:</label><div class="col-md-4">'.form_dropdown('um',$um,'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-4 control-label" for="mes">MES:</label><div class="col-md-4">'.form_dropdown('mes',$meses,'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-4 control-label" for="anio">AÑO:</label><div class="col-md-4">'.form_dropdown('anio',$anio,'','class="form-control"').'</div></div>';
echo '<center>'.form_submit('generar','REPORTE','class="btn btn-sm btn-success"').'</center>';
//echo $this->table->generate();
?>
<br />
</form>
</div>
</div>
</body>