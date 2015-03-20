<link href="<?php //echo base_url('/css/web20.css') ?>" rel="stylesheet" type="text/css" />
<script src="<?php //echo base_url('/css/bootstrap-theme.min.css') ?>"></script>
<style type="text/css">
<!--
	.boton {
	   font-size: 10px;
	}
    #contenido {
        position: absolute;
        top:0;
        left:0;
        right:0;
        bottom:0;
        margin: auto;
        background: #83C24A;
        width: 607px;
        height: 190px;
    }
    h1,h2,h3,h4,h5,h6
    {
        color: #004000;
        font-family: sans-serif;
        font-weight: bolder;
        line-height: 5px;
        color: maroon;
        text-align: center;
    }
-->
</style>
<script type="text/javascript">
<!--
	$(document).ready(function(){
	   $('.boton').button();
	})
-->
</script>
<?php
	$tmpl = array (
                    'table_open'          => '<table class="table table-bordered" align="center">',

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
?>
<?php echo br(7) ?>

<div class="container">
<h1>MENU PRINCIPAL</h1>
<?php
$this->table->add_row('<center><a class="btn btn-default" href="'.site_url('principal/captura').'">'.img('/img/captura.jpg').'</a></center>','<center><a class="btn btn-default" href="'.site_url('principal/reporte').'">'.img('/img/reportes.jpg').'</a></center>','<center><a class="btn btn-default" href="'.site_url('principal/reporte_ind_hosp_form').'">'.img('/img/ind_hospitalarios.jpeg').'</a></center>');
$celda = array('data' => '<a class="btn btn-default" href="'.site_url('principal/captura').'">CAPTURA</a>',
                'align' => 'center',
                'bgcolor' => 'maroon');
$celda2 = array('data' => '<a class="btn btn-default" href="'.site_url('principal/reporte').'">REPORTES</a>',
                'align' => 'center',
                'bgcolor' => 'maroon');
$celda3 = array('data' => '<a class="btn btn-default" href="'.site_url('principal/reporte_ind_hosp_form').'">IND HOSPITALARIOS</a>',
                'align' => 'center',
                'bgcolor' => 'maroon');
$this->table->add_row($celda,$celda2,$celda3);
echo br(2);
echo $this->table->generate();
//echo '<center><a class="boton" href="'.site_url('principal/captura').'">CAPTURA</a>';
//echo br();
//echo '<a class="boton" href="'.site_url('principal/reporte').'">REPORTES</a></center>';
?>
</div>
