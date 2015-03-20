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
        color: #004000;
        font-family: sans-serif;
        font-weight: bolder;
        
        color: maroon;
        text-align: center;
    }
    #contenido {
        position: absolute;
        top:0;
        left:0;
        right:0;
        bottom:0;
        margin: auto;
        width: 550px;
        height: 470px;
    }
    
    body
{
    background-image: url('<?php echo base_url('/img/bg_home.png') ?>');
    background-repeat: no-repeat;
    background-position: 0px 50px;
} 
    
-->
</style>
<script type="text/javascript">
<!--
	$(document).ready(function() {
		$( ".boton" ).button({
		  icons: {
	           primary: 'ui-icon ui-icon-circle-arrow-s'
	       }
		});
	});
-->
</script>
<?php

$tmpl = array (
                    'table_open'          => '<table align="center" width="500" border="0" cellpadding="4" cellspacing="0">',

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

$jurisdicciones = array('todas' => 'ESTATAL',
                        '01' => 'ZACATECAS',
                        '02' => 'OJOCALIENTE',
                        '03' => 'FRESNILLO',
                        '04' => 'RIO GRANDE',
                        '05' => 'JALPA',
                        '06' => 'TLALTENANGO',
                        '07' => 'CONCEPCION DEL ORO');
$btn_reporte = array('data' => form_submit('generar','REPORTE','class="btn btn-sm btn-success"'),
                     'colspan' => 2,
                     'align' => 'center');

$this->table->add_row('<div class="form-group"><label class="col-md-4 control-label" for="mes">MES:</label>','<div class="col-md-4">'.form_dropdown('mes',$meses_jur['meses'],'','class="form-control"').'</div></div>');
$this->table->add_row('<div class="form-group"><label class="col-md-4 control-label" for="anio">AÑO:</label>','<div class="col-md-4">'.form_dropdown('anio',$meses_jur['anios'],'','class="form-control"').'</div></div>');
$this->table->add_row('<div class="form-group"><label class="col-md-4 control-label" for="juris">JURISDICCION:</label>','<div class="col-md-4">'.form_dropdown('juris',$jurisdicciones,'','class="form-control"').'</div></div>');
$this->table->add_row($btn_reporte);
?>
</head>
<body>
<?php 
if(isset($reporteAdmin))
    $pagina->menu_genera('Evaluación','Administrar Evaluacion');
else
    $pagina->menu_genera('Evaluación','Reportes');
?>
<div class="container">
<div class="page-header text-center"><h1>REPORTES</h1></div>
<div class="row">
<div class="col-md-3" style="padding-right:20px; border-right: 1px solid #ccc;">
<?php 
//echo br(4);
if($administrador)
    echo form_open('principal/reporte_juris','class="form-horizontal"');
else
    echo form_open('evaluacion/reporteJuris','class="form-horizontal"');
?>
<fieldset>
<legend><h3>JURISDICCIONAL</h3></legend>
<?php
//echo $this->table->generate();
echo '<div class="form-group"><label class="col-md-6 control-label" for="mes">MES:</label><div class="col-md-6">'.form_dropdown("mes",$meses_jur['meses'],'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-6 control-label" for="anio">AÑO:</label><div class="col-md-6">'.form_dropdown('anio',$meses_jur['anios'],'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-6 control-label" for="juris">JURISDICCION:</label><div class="col-md-6">'.form_dropdown('juris',$jurisdicciones,'','class="form-control"').'</div></div>';
//echo '<div class="form-group">
//  <label class="col-md-4 control-label" for="singlebutton"></label>
//  <div class="col-md-4">';
echo '<center>'.form_submit('generar','REPORTE','class="btn btn-sm btn-success"').'</center>';
//echo '</div></div>';
echo form_close();
?>
</fieldset>
<br /><br />
</div>
<div class="col-md-3" style="padding-right:20px; border-right: 1px solid #ccc;">
<?php 
if($administrador)
    echo form_open('principal/reporte_hc','class="form-horizontal"');
else
    echo form_open('evaluacion/reporteHc','class="form-horizontal"');
?>
<fieldset>
<legend><h3>PRIMER NIVEL H.C.</h3></legend>
<?php

//$hc['todas'] = "ESTATAL";
$hc = array( 'todas' => "ESTATAL",
            "ZSSSA002136" => "HC VICTOR ROSALES",
            "ZSSSA001861" => "HC TRANCOSO",
            "ZSSSA001016" => "HC OJOCALIENTE",
            "ZSSSA001313" => "HC SOMBRERETE",
            "ZSSSA001506" => "HC VALPARAISO",
            "ZSSSA000695" => "HC JUAN ALDAMA", 
            "ZSSSA000572" => "HC JALPA (DR. CALIXTO MEDINA MEDINA)",                                                            
            "ZSSSA000700" => "HC JUCHIPILA",                        
            "ZSSSA000922" => "HC NOCHISTLAN DE MEJIA",
            "ZSSSA001395" => "HC TABASCO",
            "ZSSSA002141" => "HC VILLA DE COS");

$this->table->add_row('<label class="col-md-4 control-label" for="mes">MES:</label>','<div class="col-md-4">'.form_dropdown('mes',$meses_jur['meses']).'</div>');
$this->table->add_row('AÑO:',form_dropdown('anio',$meses_jur['anios']));
$this->table->add_row('HOSPITAL COMUNITARIO',form_dropdown('hc',$hc));
$this->table->add_row($btn_reporte);
echo '<div class="form-group"><label class="col-md-6 control-label" for="mes">MES:</label><div class="col-md-6">'.form_dropdown("mes",$meses_jur['meses'],'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-6 control-label" for="anio">AÑO:</label><div class="col-md-6">'.form_dropdown('anio',$meses_jur['anios'],'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-6 control-label" for="hc">HOSPITAL:</label><div class="col-md-6">'.form_dropdown('hc',$hc,'','class="form-control"').'</div></div>';
//echo $this->table->generate();
echo '<center>'.form_submit('generar','REPORTE','class="btn btn-sm btn-success"').'</center>';
echo form_close();
?>
</fieldset>
<br /><br />
</div>

<div class="col-md-3" style="padding-right:20px; border-right: 1px solid #ccc;">
<?php 
//echo br(4);
if($administrador)
    echo form_open('principal/reporte_juris_hc','class="form-horizontal"');
else
    echo form_open('evaluacion/reporteJurisHc','class="form-horizontal"');
?>
<fieldset>
<legend><h3>JURISDICCIONAL Y H.C.</h3></legend>
<?php
//echo $this->table->generate();
echo '<div class="form-group"><label class="col-md-6 control-label" for="mes">MES:</label><div class="col-md-6">'.form_dropdown("mes",$meses_jur['meses'],'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-6 control-label" for="anio">AÑO:</label><div class="col-md-6">'.form_dropdown('anio',$meses_jur['anios'],'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-6 control-label" for="juris">JURISDICCION:</label><div class="col-md-6">'.form_dropdown('juris',$jurisdicciones,'','class="form-control"').'</div></div>';
//echo '<div class="form-group">
//  <label class="col-md-4 control-label" for="singlebutton"></label>
//  <div class="col-md-4">';
echo '<center>'.form_submit('generar','REPORTE','class="btn btn-sm btn-success"').'</center>';
//echo '</div></div>';
echo form_close();
?>
</fieldset>
<br /><br />
</div>
<div class="col-md-3">
<?php 
if($administrador)
    echo form_open('principal/reporte_2n','class="form-horizontal"');
else
    echo form_open('evaluacion/reporte2Nivel','class="form-horizontal"');
?>
<fieldset>
<legend><h3>2DO NIVEL H.G., H.C. y H DE ESPECIALIDAD</h3></legend>
<?php
//$this->table->add_row('MES:',form_dropdown('mes',$meses_jur['meses']));
//$this->table->add_row('AÑO:',form_dropdown('anio',$meses_jur['anios']));
//$this->table->add_row('HOSPITAL',form_dropdown('hghehc',$hghehc));
//$this->table->add_row($btn_reporte);

$hghehc = array( "todas" => "ESTATAL",
                "ZSSSA013143" => "HOSPITAL GENERAL ZACATECAS LUZ GONZALEZ COSIO",
                "ZSSSA000152" => "HG FRESNILLO (DR. JOSE HARO AVILA)",
                "ZSSSA000613" => "HG JEREZ",
                "ZSSSA012853" => "HG LORETO",
                "ZSSSA012450" => "HOSPITAL DE LA MUJER ZACATECANA",
                "ZSSSA012771" => "HOSPITAL DE ESPECIALIDADES DE SALUD MENTAL",
                "ZSSSA013172" => "URGENCIAS",
                "ZSSSA002136" => "HC VICTOR ROSALES",
                "ZSSSA001861" => "HC TRANCOSO",
                "ZSSSA001016" => "HC OJOCALIENTE",
                "ZSSSA001313" => "HC SOMBRERETE",
                "ZSSSA001506" => "HC VALPARAISO",
                "ZSSSA000695" => "HC JUAN ALDAMA",
                "ZSSSA000572" => "HC JALPA (DR. CALIXTO MEDINA MEDINA)",
                "ZSSSA000700" => "HC JUCHIPILA",
                "ZSSSA000922" => "HC NOCHISTLAN DE MEJIA",                                
                "ZSSSA001395" => "HC TABASCO",
                "ZSSSA002141" => "HC VILLA DE COS"                                                                                                                                                                                                                                

);

echo '<div class="form-group"><label class="col-md-6 control-label" for="mes">MES:</label><div class="col-md-6">'.form_dropdown("mes",$meses_jur['meses'],'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-6 control-label" for="anio">AÑO:</label><div class="col-md-6">'.form_dropdown('anio',$meses_jur['anios'],'','class="form-control"').'</div></div>';
echo '<div class="form-group"><label class="col-md-6 control-label" for="hghehc">HOSPITAL:</label><div class="col-md-6">'.form_dropdown('hghehc',$hghehc,'','class="form-control"').'</div></div>';
//echo $this->table->generate();
echo '<center>'.form_submit('generar','REPORTE','class="btn btn-sm btn-success"').'</center>';
echo form_close();
echo br();
?>
</fieldset><br /><br />
</div>
</div>
</div>
</body>
