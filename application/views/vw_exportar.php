<script type="text/javascript">
<!--
	$(document).ready(function() {
		$( "#link" ).button({
		  icons: {
	           primary: 'ui-icon ui-icon-circle-arrow-s'
	       }
		});
	});
-->
</script>
<link type="text/css" href="<?php echo base_url('css/web20.css')?>" rel="Stylesheet" />
<?php
echo br(5);
echo '<a id="link" href="'.site_url('principal/descarga').'">DESCARGAR DATOS</a>';
echo br(2);

/*$tmpl = array (
                    'table_open'          => '<table border="1" >',

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
              );*/

//$this->table->set_template($tmpl);

//$this->table->set_heading( 'AÑO', 'VIA FINANCIAMIENTO', 'PROYECTO', 'DESCRIPCION PROYECTO', 'AIE' ,'COMPONENTE (AIE)', 'CVE SUBFUNCION','SUBFUNCION', 'UNIDAD', 'MES', 'CVE PARTIDA', 'PARTIDA', 'PRESUPUESTO');

//PARA ARCHIVO ENCABEZADOS//
$data = "JURISDICCIONES\r\n";

if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data))
//if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data))
{
     echo 'ERROR';
}

$data = "MES,AÑO,JURISDICCION,INDICADOR,UNIDAD DE MEDIDA,LOGRO\r\n";

if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data,'a'))
//if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data))
{
     echo 'ERROR';
}
//////////////////////////

foreach($exporta as $row)
{
    //print_r($row);
    //$this->table->add_row($row->anio, $row->via_financiamiento, $row->Proyecto, $row->des_proyecto, $row->cve_AIE, $row->des_AIE_Federal, $row->cve_subfuncion, $row->subfuncion, $row->unidad, $row->mes, $row->id, $row->partida, $row->presupuesto);
   // $row->des_proyecto = str_replace(',','',$row->des_proyecto);
   // $row->des_AIE_Federal = str_replace(',','',$row->des_AIE_Federal);
   // $row->partida = str_replace(',','',$row->partida);
    $data = $row->mes . ',' . $row->anio . ',' . $row->unidad . ',' . $row->indicador . ',' . $row->unidad_medida . ',' . $row->logro . "\r\n";
    if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data,'a'))
    //if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data,'a'))
     echo 'ERROR';

}

$data = "\r\nPRIMER NIVEL\r\n";

if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data,'a'))
//if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data))
{
     echo 'ERROR';
}

$data = "MES,AÑO,CLUES,NOMBRE UNIDAD,INDICADOR,UNIDAD DE MEDIDA,LOGRO\r\n";

if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data,'a'))
//if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data))
{
     echo 'ERROR';
}

foreach($exporta_1er as $row)
{
    //print_r($row);
    //$this->table->add_row($row->anio, $row->via_financiamiento, $row->Proyecto, $row->des_proyecto, $row->cve_AIE, $row->des_AIE_Federal, $row->cve_subfuncion, $row->subfuncion, $row->unidad, $row->mes, $row->id, $row->partida, $row->presupuesto);
    $row->indicador = str_replace(',','',$row->indicador);
   // $row->des_AIE_Federal = str_replace(',','',$row->des_AIE_Federal);
   // $row->partida = str_replace(',','',$row->partida);
    $data = $row->mes . ',' . $row->anio . ',' . $row->clues . ',' . $row->nombre . ',' . $row->indicador . ',' . $row->unidad_medida . ',' . $row->logro . "\r\n";
    if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data,'a'))
    //if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data,'a'))
     echo 'ERROR';

}

$data = "\r\nSEGUNDO NIVEL\r\n";

if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data,'a'))
//if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data))
{
     echo 'ERROR';
}

$data = "MES,AÑO,CLUES,NOMBRE UNIDAD,UNIDAD DE MEDIDA,LOGRO\r\n";

if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data,'a'))
//if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data))
{
     echo 'ERROR';
}

foreach($exporta_2do as $row)
{
    //print_r($row);
    //$this->table->add_row($row->anio, $row->via_financiamiento, $row->Proyecto, $row->des_proyecto, $row->cve_AIE, $row->des_AIE_Federal, $row->cve_subfuncion, $row->subfuncion, $row->unidad, $row->mes, $row->id, $row->partida, $row->presupuesto);
    //$row->indicador = str_replace(',','',$row->indicador);
   // $row->des_AIE_Federal = str_replace(',','',$row->des_AIE_Federal);
   // $row->partida = str_replace(',','',$row->partida);
    $data = $row->mes . ',' . $row->anio . ',' . $row->clues . ',' . $row->nombre . ',' . $row->unidad_medida . ',' . $row->logro . "\r\n";
    if ( ! write_file('C:\wamp\www\evaluacion_indicadores\descargas\reporte.csv', $data,'a'))
    //if ( ! write_file('/opt/lampp/htdocs/poa/descargas/reporte_'.$responsable.'.csv', $data,'a'))
     echo 'ERROR';

}
//echo $this->table->generate();
?>