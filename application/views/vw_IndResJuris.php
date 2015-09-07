<?php //print_r($reporte); ?>
<div class="jumbotron text-center resultado">
	<h2><strong><?php echo $reporte->nombre ?></strong> <small><?php echo $reporte->mesTxt ?> 2014</small></h2>
</div>

<div class="row resultado">
	<div class="col-xs-12">
		<table class="table table-hover">
		    <thead>
		    	<tr class="success">
			    	<th>ZACATECAS</th>
					<th>OJOCALIENTE</th>
					<th>FRESNILLO</th>
					<th>RIO GRANDE</th>
					<th>JALPA</th>
					<th>TLALTENANGO</th>
					<th>CONCEPCION DEL ORO</th>
				</tr>
		    </thead>
		    <tbody>
		        <tr>
		        	<td><?php echo number_format($reporte->logroEstatal['01'],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal['02'],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal['03'],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal['04'],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal['05'],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal['06'],2) ?></td>
					<td><?php echo number_format($reporte->logroEstatal['07'],2) ?></td>
		        </tr>
		    </tbody>
		    <tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text-right"><h2>ESTATAL: </h2></td>
					<td><h2><strong><?php echo number_format($reporte->logroEstatal['estatal'],2) ?></strong></h2></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
