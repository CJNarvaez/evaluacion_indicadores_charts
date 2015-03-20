<!-- Include your JS library of choice here and fix the path
 
    <script src="javascripts/prototype.js" type="text/javascript"></script>
    <script src="javascripts/jquery/jquery-1.2.6.min.js" type="text/javascript"></script>
 
--->
 
<!-- I am using jQuery --->
<script src="<?php echo base_url('js/jquery-2.0.3.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('js/swfobject.js'); ?>"></script>
<script type="text/javascript">
 
swfobject.embedSWF(
  "<?php echo base_url('open-flash-chart.swf');?>", "my_chart",
  "<?php echo $width;?>", "<?php echo $height; ?>", "9.0.0", "expressInstall.swf",
  {"data-file":"<?php echo site_url($url);?>"} );
 
</script>
 
<script type="text/javascript">
 
OFC = {};
 
OFC.jquery = {
    name: "jQuery",
    version: function(src) { return $('#'+ src)[0].get_version() },
    rasterize: function (src, dst) { $('#'+ dst).replaceWith(OFC.jquery.image(src)) },
    image: function(src) { return "<img src='data:image/png;base64," + $('#'+src)[0].get_img_binary() + "' />"},
    popup: function(src) {
        var img_win = window.open('', 'Charts: Export as Image')
        with(img_win.document) {
            write('<html><head><title>Charts: Export as Image<\/title><\/head><body>' + OFC.jquery.image(src) + '<\/body><\/html>') }
		// stop the 'loading...' message
		img_win.document.close();
     }
}
 
// Using an object as namespaces is JS Best Practice. I like the Control.XXX style.
//if (!Control) {var Control = {}}
//if (typeof(Control == "undefined")) {var Control = {}}
if (typeof(Control == "undefined")) {var Control = {OFC: OFC.jquery}}
 
 
// By default, right-clicking on OFC and choosing "save image locally" calls this function.
// You are free to change the code in OFC and call my wrapper (Control.OFC.your_favorite_save_method)
// function save_image() { alert(1); Control.OFC.popup('my_chart') }
function save_image() { alert('Haz click con el boton derecho en la siguiente imagen y selecciona "Guardar imagen como..."'); OFC.jquery.popup('my_chart') }
function moo() { alert(99); };
</script>
<script src="<?php echo base_url('/js/swfobject.js'); ?>"></script>


		<script type="text/javascript">
$(function () {
	
    $('#container').highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'Speedometer'
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
	        max: 200,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 10,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 10,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: 'km/h'
	        },
	        plotBands: [{
	            from: 0,
	            to: 120,
	            color: '#55BF3B' // green
	        }, {
	            from: 120,
	            to: 160,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: 160,
	            to: 200,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Speed',
	        data: [80],
	        tooltip: {
	            valueSuffix: ' km/h'
	        }
	    }]
	
	}, 
	// Add some life
	function (chart) {
		if (!chart.renderer.forExport) {
		    setInterval(function () {
		        var point = chart.series[0].points[0],
		            newVal,
		            inc = Math.round((Math.random() - 0.5) * 20);
		        
		        newVal = point.y + inc;
		        if (newVal < 0 || newVal > 200) {
		            newVal = point.y - inc;
		        }
		        
		        point.update(newVal);
		        
		    }, 3000);
		}
	});
});
		</script>

<div id="my_chart"></div>

<script src="<?php echo base_url('js/highcharts.js'); ?>"></script>
<script src="<?php echo base_url('js/highcharts-more.js'); ?>"></script>
<script src="<?php echo base_url('js/exporting.js'); ?>"></script>

<div id="container" style="min-width: 310px; max-width: 400px; height: 300px; margin: 0 auto"></div>