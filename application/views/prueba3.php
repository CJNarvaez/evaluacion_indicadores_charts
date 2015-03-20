<?php
$servicios = array();
$titulos = array();

$titulos[1] = '% OCUPACION MENSUAL'; 
$titulos[2] = '% OCUPACION ACUMULADO';
$titulos[3] = 'IND ROTACION MENSUAL';
$titulos[4] = 'IND ROTACION ACUMULADO';
$titulos[5] = 'INT SUSTITUCION MENSUAL';
$titulos[6] = 'INT SUSTITUCION ACUMULADO';
$titulos[7] = 'TASA MORTALIDAD BRUTA';
$titulos[8] = 'TASA MORTALIDAD AJUSTADA';
$titulos[9] = 'DIAS ESTANCIA MENSUAL';
$titulos[10] = 'DIAS ESTANCIA ACUMULADO';

$servicios[1] = 'CIRUGIA';
$servicios[2] = 'MEDICINA INTERNA'; 
$servicios[3] = 'PEDIATRIA';
$servicios[4] = 'GINECOLOGIA';
$servicios[5] = 'TRAUMATOLOGIA';
$servicios[6] = 'TOTAL';

?>
<script type="text/javascript">
function datos (){
    $('#container').highcharts({
            colors: ['maroon', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', 
             '#FF9655', '#FFF263', '#6AF9C4'],
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo $titulos[$titulo] ?>'
            },
            subtitle: {
                text: '<?php echo $servicios[$servicio] ?>'
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: 0,
                    align: 'center',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Ocupacion (porcentaje)'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Ocupacion: <b>{point.y:.1f}%</b>',
            },
            series: [{
                name: 'Population',
                data: [<?php $inicio = 1; 
                            for($i=0;$i<$mes;$i++){
                                if($inicio != 1)
                                    echo " , ";
                                echo "['".$meses[$i]."', ".$datos[$i]."]";
                                $inicio++;
                              } ?>
                ],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 4,
                    y: 10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 3px black'
                    }
                }
            }]
        });
};
</script>

<div id="container" style="min-width: 500px; max-width: 520px; height: 400px; margin: 0 auto"></div>
</body></html>