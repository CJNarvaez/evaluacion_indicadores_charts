<link href="<?php echo base_url('css/web20.css'); ?>" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
	.boton:hover {
	   cursor: pointer;
       background-color: silver;
	}
    #grafica {
        /*height: 350px;*/
    }
    h1,h2,h3,h4,h5,h6
    {
        font-weight: bolder;
        color: maroon;

    }
-->
</style>
<script type="text/javascript">
<!--
	$(document).ready(function(){
	   
	   /* CONVIERTE LOS TITULOS EN BOTONES */
       $('#mensual').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/mensual/'.$datos['porc_ocup_men'].'/'.$datos['med_int_porc_ocup_men'].'/'.$datos['pediatria_porc_ocup_men'].'/'.$datos['ginecologia_porc_ocup_men'].'/'.$datos['trauma_porc_ocup_men'].'/1')?>','',function(){ datos();} );
       });
       $('#acumulado').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/acumulado/'.$datos['porc_ocup_acu'].'/'.$datos['med_int_porc_ocup_acu'].'/'.$datos['pediatria_porc_ocup_acu'].'/'.$datos['ginecologia_porc_ocup_acu'].'/'.$datos['trauma_porc_ocup_acu'].'/2')?>','',function(){ datos();} );
       });       
       $('#ind_rot_men').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/ind_rot_men/'.$datos['ind_rot_men'].'/'.$datos['med_int_ind_rot_men'].'/'.$datos['pediatria_ind_rot_men'].'/'.$datos['ginecologia_ind_rot_men'].'/'.$datos['trauma_ind_rot_men'].'/3')?>','',function(){ datos();} );
       });       
       $('#ind_rot_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/ind_rot_acu/'.$datos['ind_rot_acu'].'/'.$datos['med_int_ind_rot_acu'].'/'.$datos['pediatria_ind_rot_acu'].'/'.$datos['ginecologia_ind_rot_acu'].'/'.$datos['trauma_ind_rot_acu'].'/4')?>','',function(){ datos();} );
       });       
       $('#int_sust_men').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/int_sust_men/'.$datos['int_sust_men'].'/'.$datos['med_int_int_sust_men'].'/'.$datos['pediatria_int_sust_men'].'/'.$datos['ginecologia_int_sust_men'].'/'.$datos['trauma_int_sust_men'].'/5')?>','',function(){ datos();} );
       });       
       $('#int_sust_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/int_sust_acu/'.$datos['int_sust_acu'].'/'.$datos['med_int_int_sust_acu'].'/'.$datos['pediatria_int_sust_acu'].'/'.$datos['ginecologia_int_sust_acu'].'/'.$datos['trauma_int_sust_acu'].'/6')?>','',function(){ datos();} );
       });       
       $('#tasa_mort_bruta').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/tasa_mort_bruta/'.$datos['tasa_mort_bruta'].'/'.$datos['med_int_tasa_mort_bruta'].'/'.$datos['pediatria_tasa_mort_bruta'].'/'.$datos['ginecologia_tasa_mort_bruta'].'/'.$datos['trauma_tasa_mort_bruta'].'/7')?>','',function(){ datos();} );
       });       
       $('#tasa_mort_ajus').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/tasa_mort_ajus/'.$datos['tasa_mort_ajus'].'/'.$datos['med_int_tasa_mort_ajus'].'/'.$datos['pediatria_tasa_mort_ajus'].'/'.$datos['ginecologia_tasa_mort_ajus'].'/'.$datos['trauma_tasa_mort_ajus'].'/8')?>','',function(){ datos();} );
       });       
       $('#dias_est_men').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/dias_est_men/'.$datos['dias_est_men'].'/'.$datos['med_int_dias_est_men'].'/'.$datos['pediatria_dias_est_men'].'/'.$datos['ginecologia_dias_est_men'].'/'.$datos['trauma_dias_est_men'].'/9')?>','',function(){ datos();} );
       });       
       $('#dias_est_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('/principal/graficar_ind_hosp/'.$um.'/'.$mes.'/'.$anio.'/dias_est_acu/'.$datos['dias_est_acu'].'/'.$datos['med_int_dias_est_acu'].'/'.$datos['pediatria_dias_est_acu'].'/'.$datos['ginecologia_dias_est_acu'].'/'.$datos['trauma_dias_est_acu'].'/10')?>','',function(){ datos();} );
       });
       
       /* BOTONES OCUPACION MENSUAL */
       $('#cir_ocup_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/ocup_men'.'/1/1')?>','',function(){ datos();} );
       });
       $('#med_int_ocup_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/ocup_men'.'/1/2')?>','',function(){ datos();} );
       });
       $('#pediatria_ocup_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/ocup_men'.'/1/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_ocup_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/ocup_men'.'/1/4')?>','',function(){ datos();} );
       });
       $('#trauma_ocup_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/ocup_men'.'/1/5')?>','',function(){ datos();} );
       });
       $('#total_ocup_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/ocup_men'.'/1/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES OCUPACION ACUMULADO */
       $('#cir_ocup_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/ocup_acu'.'/2/1')?>','',function(){ datos();} );
       });
       $('#med_int_ocup_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/ocup_acu'.'/2/2')?>','',function(){ datos();} );
       });
       $('#pediatria_ocup_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/ocup_acu'.'/2/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_ocup_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/ocup_acu'.'/2/4')?>','',function(){ datos();} );
       });
       $('#trauma_ocup_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/ocup_acu'.'/2/5')?>','',function(){ datos();} );
       });
       $('#total_ocup_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/ocup_acu'.'/2/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES INDICE DE ROTACION MENSUAL */
       $('#cir_ind_rot_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/ind_rot_men'.'/3/1')?>','',function(){ datos();} );
       });
       $('#med_int_ind_rot_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/ind_rot_men'.'/3/2')?>','',function(){ datos();} );
       });
       $('#pediatria_ind_rot_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/ind_rot_men'.'/3/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_ind_rot_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/ind_rot_men'.'/3/4')?>','',function(){ datos();} );
       });
       $('#trauma_ind_rot_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/ind_rot_men'.'/3/5')?>','',function(){ datos();} );
       });
       $('#total_ind_rot_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/ind_rot_men'.'/3/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES INDICE DE ROTACION ACUMULADO */
       $('#cir_ind_rot_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/ind_rot_acu'.'/4/1')?>','',function(){ datos();} );
       });
       $('#med_int_ind_rot_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/ind_rot_acu'.'/4/2')?>','',function(){ datos();} );
       });
       $('#pediatria_ind_rot_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/ind_rot_acu'.'/4/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_ind_rot_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/ind_rot_acu'.'/4/4')?>','',function(){ datos();} );
       });
       $('#trauma_ind_rot_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/ind_rot_acu'.'/4/5')?>','',function(){ datos();} );
       });
       $('#total_ind_rot_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/ind_rot_acu'.'/4/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES INTERVALO DE SUSTITUCION MENSUAL */
       $('#cir_int_sust_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/int_sust_men'.'/5/1')?>','',function(){ datos();} );
       });
       $('#med_int_int_sust_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/int_sust_men'.'/5/2')?>','',function(){ datos();} );
       });
       $('#pediatria_int_sust_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/int_sust_men'.'/5/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_int_sust_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/int_sust_men'.'/5/4')?>','',function(){ datos();} );
       });
       $('#trauma_int_sust_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/int_sust_men'.'/5/5')?>','',function(){ datos();} );
       });
       $('#total_int_sust_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/int_sust_men'.'/5/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES INTERVALO DE SUSTITUCION ACUMULADO */
       $('#cir_int_sust_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/int_sust_acu'.'/6/1')?>','',function(){ datos();} );
       });
       $('#med_int_int_sust_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/int_sust_acu'.'/6/2')?>','',function(){ datos();} );
       });
       $('#pediatria_int_sust_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/int_sust_acu'.'/6/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_int_sust_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/int_sust_acu'.'/6/4')?>','',function(){ datos();} );
       });
       $('#trauma_int_sust_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/int_sust_acu'.'/6/5')?>','',function(){ datos();} );
       });
       $('#total_int_sust_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/int_sust_acu'.'/6/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES TASA DE MORTALIDAD BRUTA */
       $('#cir_tasa_mort_bruta').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/tasa_mort_bruta'.'/7/1')?>','',function(){ datos();} );
       });
       $('#med_int_tasa_mort_bruta').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/tasa_mort_bruta'.'/7/2')?>','',function(){ datos();} );
       });
       $('#pediatria_tasa_mort_bruta').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/tasa_mort_bruta'.'/7/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_tasa_mort_bruta').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/tasa_mort_bruta'.'/7/4')?>','',function(){ datos();} );
       });
       $('#trauma_tasa_mort_bruta').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/tasa_mort_bruta'.'/7/5')?>','',function(){ datos();} );
       });
       $('#total_tasa_mort_bruta').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/tasa_mort_bruta'.'/7/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES TASA DE MORTALIDAD AJUSTADA */
       $('#cir_tasa_mort_ajus').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/tasa_mort_ajus'.'/8/1')?>','',function(){ datos();} );
       });
       $('#med_int_tasa_mort_ajus').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/tasa_mort_ajus'.'/8/2')?>','',function(){ datos();} );
       });
       $('#pediatria_tasa_mort_ajus').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/tasa_mort_ajus'.'/8/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_tasa_mort_ajus').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/tasa_mort_ajus'.'/8/4')?>','',function(){ datos();} );
       });
       $('#trauma_tasa_mort_ajus').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/tasa_mort_ajus'.'/8/5')?>','',function(){ datos();} );
       });
       $('#total_tasa_mort_ajus').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/tasa_mort_ajus'.'/8/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES DIAS ESTANCIA MENSUAL */
       $('#cir_dias_est_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/dias_est_men'.'/9/1')?>','',function(){ datos();} );
       });
       $('#med_int_dias_est_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/dias_est_men'.'/9/2')?>','',function(){ datos();} );
       });
       $('#pediatria_dias_est_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/dias_est_men'.'/9/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_dias_est_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/dias_est_men'.'/9/4')?>','',function(){ datos();} );
       });
       $('#trauma_dias_est_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/dias_est_men'.'/9/5')?>','',function(){ datos();} );
       });
       $('#total_dias_est_men').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/dias_est_men'.'/9/6')?>','',function(){ datos();} );
       });
       
       /* BOTONES DIAS ESTANCIA ACUMULADO */
       $('#cir_dias_est_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/cir/'.$anio.'/dias_est_acu'.'/10/1')?>','',function(){ datos();} );
       });
       $('#med_int_dias_est_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/med_int/'.$anio.'/dias_est_acu'.'/10/2')?>','',function(){ datos();} );
       });
       $('#pediatria_dias_est_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/pediatria/'.$anio.'/dias_est_acu'.'/10/3')?>','',function(){ datos();} );
       });
       $('#ginecologia_dias_est_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/ginecologia/'.$anio.'/dias_est_acu'.'/10/4')?>','',function(){ datos();} );
       });
       $('#trauma_dias_est_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/trauma/'.$anio.'/dias_est_acu'.'/10/5')?>','',function(){ datos();} );
       });
       $('#total_dias_est_acu').click(function(event){
            $('#grafica').load('<?php echo site_url('principal/graficar_ind_hosp_xum/'.$um.'/total/'.$anio.'/dias_est_acu'.'/10/6')?>','',function(){ datos();} );
       });
       
	});
-->
</script>

<script src="<?php echo base_url('js/highcharts.js'); ?>"></script>
<script src="<?php echo base_url('js/highcharts-more.js'); ?>"></script>
<script src="<?php echo base_url('js/modules/exporting.js'); ?>"></script>

</head>
<body>
<?php
/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */
echo br(5);
echo $titulo;
?>
<div id="grafica" align="center" background-color="red"></div>
<?php 

echo $this->table->generate();
echo br(2); ?>

</body></html>