<!DOCTYPE HTML>
<html>
<head>
<script type="text/javascript">
function datos (){
    $('#cirugia').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'CIRUGIA',
            style: {
                        fontSize: '13px'
                    }
	    },
	    
	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: <?php echo $semaforo['total'] ?>,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 5,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 13,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: ''
	        },
	        plotBands: [{
	            from: 0,
	            to: <?php echo $semaforo['rojo1'] ?>,
	            color: '#DF5353' // red
	        }, {
	            from: <?php echo $semaforo['rojo1'] ?>,
	            to: <?php echo $semaforo['amarillo'] ?>,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: <?php echo $semaforo['amarillo'] ?>,
	            to: <?php echo $semaforo['verde'] ?>,
	            color: '#55BF3B' // green
	        }, {
	            from: <?php echo $semaforo['verde'] ?>,
	            to: <?php echo $semaforo['total'] ?>,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Ocup',
	        data: [<?php echo $porc_ocup_men ?>],
	        tooltip: {
	            valueSuffix: ' %'
	        }
	    }]
	
	});
    
    $('#med_int').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'MED INT',
            style: {
                        fontSize: '13px'
                    }
	    },
	    
	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: <?php echo $semaforo['total'] ?>,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 5,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 13,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: ''
	        },
	        plotBands: [{
	            from: 0,
	            to: <?php echo $semaforo['rojo1'] ?>,
	            color: '#DF5353' // red
	        }, {
	            from: <?php echo $semaforo['rojo1'] ?>,
	            to: <?php echo $semaforo['amarillo'] ?>,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: <?php echo $semaforo['amarillo'] ?>,
	            to: <?php echo $semaforo['verde'] ?>,
	            color: '#55BF3B' // green
	        }, {
	            from: <?php echo $semaforo['verde'] ?>,
	            to: <?php echo $semaforo['total'] ?>,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Ocup',
	        data: [<?php echo $med_int_porc_ocup_men ?>],
	        tooltip: {
	            valueSuffix: ' %'
	        }
	    }]
	
	});
    $('#pediatria').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'PEDIATRIA',
            style: {
                        fontSize: '13px'
                    }
	    },
	    
	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: <?php echo $semaforo['total'] ?>,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 5,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 13,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: ''
	        },
	        plotBands: [{
	            from: 0,
	            to: <?php echo $semaforo['rojo1'] ?>,
	            color: '#DF5353' // red
	        }, {
	            from: <?php echo $semaforo['rojo1'] ?>,
	            to: <?php echo $semaforo['amarillo'] ?>,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: <?php echo $semaforo['amarillo'] ?>,
	            to: <?php echo $semaforo['verde'] ?>,
	            color: '#55BF3B' // green
	        }, {
	            from: <?php echo $semaforo['verde'] ?>,
	            to: <?php echo $semaforo['total'] ?>,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Ocup',
	        data: [<?php echo $pediatria_porc_ocup_men ?>],
	        tooltip: {
	            valueSuffix: ' %'
	        }
	    }]
	
	});
    $('#ginecologia').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'GINECOLOGIA',
            style: {
                        fontSize: '13px'
                    }
	    },
	    
	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: <?php echo $semaforo['total'] ?>,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 5,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 13,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: ''
	        },
	        plotBands: [{
	            from: 0,
	            to: <?php echo $semaforo['rojo1'] ?>,
	            color: '#DF5353' // red
	        }, {
	            from: <?php echo $semaforo['rojo1'] ?>,
	            to: <?php echo $semaforo['amarillo'] ?>,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: <?php echo $semaforo['amarillo'] ?>,
	            to: <?php echo $semaforo['verde'] ?>,
	            color: '#55BF3B' // green
	        }, {
	            from: <?php echo $semaforo['verde'] ?>,
	            to: <?php echo $semaforo['total'] ?>,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Ocup',
	        data: [<?php echo $ginecologia_porc_ocup_men ?>],
	        tooltip: {
	            valueSuffix: ' %'
	        }
	    }]
	
	});
    $('#ginecologia').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'GINECOLOGIA',
            style: {
                        fontSize: '13px'
                    }
	    },
	    
	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: <?php echo $semaforo['total'] ?>,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 5,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 13,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: ''
	        },
	        plotBands: [{
	            from: 0,
	            to: <?php echo $semaforo['rojo1'] ?>,
	            color: '#DF5353' // red
	        }, {
	            from: <?php echo $semaforo['rojo1'] ?>,
	            to: <?php echo $semaforo['amarillo'] ?>,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: <?php echo $semaforo['amarillo'] ?>,
	            to: <?php echo $semaforo['verde'] ?>,
	            color: '#55BF3B' // green
	        }, {
	            from: <?php echo $semaforo['verde'] ?>,
	            to: <?php echo $semaforo['total'] ?>,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Ocup',
	        data: [<?php echo $ginecologia_porc_ocup_men ?>],
	        tooltip: {
	            valueSuffix: ' %'
	        }
	    }]
	
	});
    $('#trauma').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'TRAUMA',
            style: {
                        fontSize: '13px'
                    }
	    },
	    
	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: <?php echo $semaforo['total'] ?>,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 5,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 13,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: ''
	        },
	        plotBands: [{
	            from: 0,
	            to: <?php echo $semaforo['rojo1'] ?>,
	            color: '#DF5353' // red
	        }, {
	            from: <?php echo $semaforo['rojo1'] ?>,
	            to: <?php echo $semaforo['amarillo'] ?>,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: <?php echo $semaforo['amarillo'] ?>,
	            to: <?php echo $semaforo['verde'] ?>,
	            color: '#55BF3B' // green
	        }, {
	            from: <?php echo $semaforo['verde'] ?>,
	            to: <?php echo $semaforo['total'] ?>,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Ocup',
	        data: [<?php echo $trauma_porc_ocup_men ?>],
	        tooltip: {
	            valueSuffix: ' %'
	        }
	    }]
	
	});
    $('#container').highcharts({
            colors: ['maroon', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', 
             '#FF9655', '#FFF263', '#6AF9C4'],
            chart: {
                type: 'column'
            },
            title: {
                text: 'Porcentaje de Ocupacion Mensual'
            },
            subtitle: {
                text: 'Todos los Servicios <?php echo $anio ?>'
            },
            xAxis: {
                type: 'category',
                labels: {
                    
                    style: {
                        fontSize: '10px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Porcentaje'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: '<b>{point.y:.1f} %</b>',
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


</head>

<body>
<table>
<tr>
<td rowspan="2"><div id="container" style="min-width: 500px; max-width: 520px; height: 400px; margin: 0 auto"></div></td>
<td><div id="cirugia" style="min-width: 150px; max-width: 200px; height: 200px; margin: 0 auto"></div></td>
<td><div id="pediatria" style="min-width: 150px; max-width: 200px; height: 200px; margin: 0 auto"></div></td>
<td rowspan="2"><div id="trauma" style="min-width: 150px; max-width: 200px; height: 200px; margin: 0 auto"></div></td>
</tr>
<tr>
<td><div id="med_int" style="min-width: 150px; max-width: 200px; height: 200px; margin: 0 auto"></td>
<td><div id="ginecologia" style="min-width: 150px; max-width: 200px; height: 200px; margin: 0 auto"></div></td>
</tr>
</table>

</body></html>