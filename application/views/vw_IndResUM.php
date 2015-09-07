<?php //print_r($reporte); ?>
<div class="jumbotron text-center resultado">
	<h2><strong><?php echo $reporte->nombre ?></strong> <small><?php echo $reporte->mesTxt ?> 2014</small></h2>
</div>

<div class="row resultado">
	<div class="col-xs-12">
		<table class="table table-hover table-condensed">
		    <thead>
		    	<tr class="success">
		    		<?php if (isset($completo))	echo "<th>HG ZACATECAS</th>"?>
			    	<th>HG FRESNILLO</th>
					<th>HG LORETO</th>
					<th>HG JEREZ</th>
					<th>H. MUJER</th>
				</tr>
		    </thead>
		    <tbody>
		        <tr>
		        	<?php if (isset($completo))	echo "<td>".number_format($reporte->logroEstatal[259],2)."</td>"?>
		        	<td><?php echo number_format($reporte->logroEstatal[13],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal[231],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal[57],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal[203],2) ?></td>
		        </tr>
		    </tbody>
		    <tfoot>
				<tr>
					<td></td>
					<td></td>
					<td class="text-right"><h2>ESTATAL: </h2></td>
					<td><h2><strong><?php echo number_format($reporte->logroEstatal['estatal'],2) ?></strong></h2></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
