<!DOCTYPE HTML>
<link href="<?php echo base_url('/css/web20.css') ?>" rel="stylesheet" type="text/css" />
<link type="text/css" href="<?php echo base_url('css/ssz/jquery-ui-1.10.3.custom.css'); ?>" rel="Stylesheet" />      
<script type="text/javascript" src="<?php echo base_url('js/jquery-2.0.3.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery-ui-1.10.3.custom.min.js') ?>"></script>
<script type="text/javascript">
<!--
	$(document).ready(function(){
	   $('#enviar').button();
	})
-->
</script>
<style type="text/css">
<!--
	#login {
        position: absolute;
        top:0;
        left:0;
        right:0;
        bottom:0;
        margin: auto;
        width: 243px;
        height: 250px;
    }
-->
</style>
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

body
{
background-image: url('<?php echo base_url('/img/bg_home.png') ?>');
background-repeat: no-repeat;
} 

</style>
<style type="text/css">
<!--
	h1,h2,h3,h4,h5,h6
    {
        color: #004000;
        font-family: sans-serif;
        font-weight: bolder;
        line-height: 5px;
        color: maroon;
        text-align: center;
    }
   #imagen {
        position: absolute;
        right: 50px;
   }
-->
</style>
<link type="text/css" href="<?php echo base_url('css/ssz/jquery-ui-1.10.3.custom.css'); ?>" rel="Stylesheet" />      
<script type="text/javascript" src="<?php echo base_url('js/jquery-2.0.3.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/jquery-ui-1.10.3.custom.min.js') ?>"></script>
<body>
<?php echo br(4);?>

<?php 
echo heading(lang('login_heading'));
//echo heading('DIRECCION DE PLANEACION',3);
//echo heading('DEPARTAMENTO DE PROGRAMACION Y EVALUACION',4);
echo heading(lang('login_subheading'),4) ?>

<h1><?php //echo lang('login_heading');?></h1>
<p><?php //echo lang('login_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>
<div id="login">
<?php echo form_open("auth/login");?>


<?php
    $this->table->set_template($tmpl);
    $celda = array('data' => '<font color="white" size="2px"><strong>INICIAR SESION</strong><font>',
                    'colspan' => 2,
                    'align' => 'center',
                    'bgcolor' => '#005E00');
    $this->table->add_row($celda);
    $this->table->add_row('USUARIO',form_input($identity));
    $this->table->add_row('PASSWORD',form_input($password));
    $celda = array('data' => form_submit('Enviar','ENTRAR','id="enviar"'),
                    'colspan' => 2,
                    'align' => 'center',
                    'bgcolor' => '#005E00');
    $this->table->add_row($celda);
    echo $this->table->generate();
?>


  <p>
    <?php //echo lang('login_identity_label', 'identity');?>
    <?php //echo form_input($identity);?>
  </p>

  <p>
    <?php //echo lang('login_password_label', 'password');?>
    <?php //echo form_input($password);?>
  </p>

  <p>
    <?php echo lang('login_remember_label', 'remember');?>
    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
  </p>


  <p><?php //echo form_submit('submit', lang('login_submit_btn'));?></p>

<?php echo form_close();?>
<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
</div>
<?php
	$image_properties = array(
          'src' => '/img/estadistica.jpg',
          'alt' => 'Estadistica',
          'class' => 'post_images',
          'width' => '360',
          'height' => '270'
);
?>
<div id="imagen"><?php echo img($image_properties) ?></div>