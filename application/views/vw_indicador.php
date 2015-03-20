<!DOCTYPE html>
<html lang="es">
<head>
<title><?php echo $pagina->titulo_pagina; ?></title>
<?php $pagina->html_head(1,1,1) ?>
<style type="text/css">
<!--
	.bs-wizard {margin-top: 40px;}

/*Form Wizard*/
.bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
.bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
.bs-wizard > .bs-wizard-step + .bs-wizard-step {}
.bs-wizard > .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 16px; margin-bottom: 5px;}
.bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 14px;}
.bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #fbe8aa; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
.bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #fbbd19; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
.bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
.bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #fbe8aa;}
.bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
.bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
.bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
.bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f5f5f5;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
.bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
.bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
.bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
-->
</style>
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
<?php 
//MENU
$pagina->menu_genera('Evaluación','Captura');
?>

<div class="container">

<?php
//echo br(3);
$tmpl = array (
                    'table_open'          => '<table class="table table-striped table-condensed table-hover">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr id="odd">',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );

$this->table->set_template($tmpl);

$this->table->set_heading('<center>INDICADORES</center>');

//print_r($ind);

foreach($ind as $ren)
{
    $programa = $ren['descripcion_programa'];
    $this->table->add_row('<a href="'.site_url('/indicador/unidad_medida/'.$ren['id_ind'].'/'.$nivel).'"><i class="fa fa-pencil-square-o"></i> '.$ren['descripcion_ind'].'</a>');
}
?>
<div class="page-header text-center"><h1>CAPTURA</h1></div>

<div class="row bs-wizard" style="border-bottom:0;">
                
    <div class="col-xs-3 bs-wizard-step complete">
      <div class="text-center bs-wizard-stepnum">Paso 1</div>
      <div class="progress"><div class="progress-bar"></div></div>
      <a href="#" class="bs-wizard-dot"></a>
      <div class="bs-wizard-info text-center">Programa: <?php echo $programa ?></div>
    </div>
    
    <div class="col-xs-3 bs-wizard-step active"><!-- complete -->
      <div class="text-center bs-wizard-stepnum">Paso 2</div>
      <div class="progress"><div class="progress-bar"></div></div>
      <a href="#" class="bs-wizard-dot"></a>
      <div class="bs-wizard-info text-center">Seleccionar Indicador</div>
    </div>
    
    <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
      <div class="text-center bs-wizard-stepnum">Paso 3</div>
      <div class="progress"><div class="progress-bar"></div></div>
      <a href="#" class="bs-wizard-dot"></a>
      <div class="bs-wizard-info text-center">Seleccionar Unidad de Medida</div>
    </div>
    
    <div class="col-xs-3 bs-wizard-step disabled"><!-- active -->
      <div class="text-center bs-wizard-stepnum">Paso 4</div>
      <div class="progress"><div class="progress-bar"></div></div>
      <a href="#" class="bs-wizard-dot"></a>
      <div class="bs-wizard-info text-center">Reportar Logro</div>
    </div>
</div>
<?php
echo '<div class="row"><div class="col-md-3"></div><div class="col-md-6">';
echo $this->table->generate();
echo '</div><div class="col-md-3"></div></div>';

?>
</div>
</div>