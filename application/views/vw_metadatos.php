<script type="text/javascript">
$(document).ready(function () {
    $('#container').highcharts({
        title: {
            text: '<?php echo $titulo ?>'
        },
        yAxis: {
                title: {
                    text: 'Valor'
                }
            },
        xAxis: {
            title: {
                text:''
            },
            labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '10px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                },
            categories: <?php echo json_encode($categorias) ?>
        },
        series: [{
            type: 'column',
            name: 'Logro',
            data: <?php echo json_encode($logros) ?>
        }, {
            type: 'spline',
            name: 'Meta',
            data: <?php echo json_encode($metas)  ?>,
            marker: {
            	lineWidth: 2,
            	lineColor: Highcharts.getOptions().colors[3],
            	fillColor: 'white'
            }
        }]
    });
});

    

		</script>
<style type="text/css">
<!--
    h2
    {
        color: maroon;
        font-weight: bolder;
    }
-->
</style>
<?php 
        echo br(3);
        echo heading($titulo_rep,2);
        echo '<div id="container" style="min-width: 310px; max-width: 750px; height: 400px; margin: 0 auto"></div>';
        echo br();
        echo $tabla_ind;
        echo br();
        echo $tabla_metas;
        echo br(3);
?>

<script src="<?php echo base_url('/js/highcharts.js') ?>"></script>
<script src="<?php echo base_url('/js/modules/exporting.js')?>"></script>
<!--//EL TEMA SE TIENE CARGAR DESPUES DE ESTOS 2 DE ARRIBA -->
<script src="<?php echo base_url('/js/themes/propio.js') ?>"></script>

