<link href="<?php echo base_url('/css/web20.css') ?>" rel="stylesheet" type="text/css" />
<?php
	$tmpl = array (
                    'table_open'          => '<table border="0" cellpadding="4" cellspacing="0">',

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
?>
<style type="text/css">
<!--
	h1,h2,h3,h4,h5,h6
    {
        color: #004000;
        font-family: sans-serif;
        font-weight: bolder;
        line-height: 5px;
        color: maroon;
    }
   #imagen {
        position: absolute;
        right: 50px;
   }
-->
</style>
<?php
	$image_properties = array(
          'src' => '/img/estadistica.jpg',
          'alt' => 'Estadistica',
          'class' => 'post_images',
          'width' => '360',
          'height' => '270'
);
?>
<script type="text/javascript">
<!--
	$(document).ready(function(){
	   $('#enviar').button();
	})
-->
</script>
<?php echo link_tag('css/estilo.css') ?>

<div id="content" align="center">
<?php 
echo heading('SERVICIOS DE SALUD DE ZACATECAS');
echo heading('DIRECCION DE PLANEACION',3);
//echo heading('DEPARTAMENTO DE PROGRAMACION Y EVALUACION',4);
echo heading('SISTEMA DE EVALUACION',4) ?>

<br />
<?php if(isset($error)){ ?>
<div id="error">
<?php
    echo $error;
?>
</div>
<?php
} 
  echo validation_errors();
?>
<div id="login">
<?php 
    echo form_open('/login/verificar'); 
    $this->table->set_template($tmpl);
    $celda = array('data' => '<font color="white" size="2px"><strong>INICIAR SESION</strong><font>',
                    'colspan' => 2,
                    'align' => 'center',
                    'bgcolor' => '#005E00');
    $this->table->add_row($celda);
    $this->table->add_row('USUARIO',form_input('username',set_value('username')));
    $this->table->add_row('PASSWORD',form_password('password'));
    $celda = array('data' => form_submit('Enviar','ENTRAR','id="enviar"'),
                    'colspan' => 2,
                    'align' => 'center',
                    'bgcolor' => '#005E00');
    $this->table->add_row($celda);
    echo $this->table->generate();
?>
</div>
<div id="imagen"><?php echo img($image_properties) ?></div>
</form>
<br />
</div>
