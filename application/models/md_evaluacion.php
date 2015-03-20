<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_evaluacion extends CI_Model
{
    private $tmpl = array (
                    'table_open'          => '<table border="1">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );
    public function publicarJuris($mes,$anio,$juris)
    {
        {
        
        //GUARDA PARA ENVIAR A LA VISTA
        $datos['mes'] = $mes;
        $datos['anio'] = $anio;
        $datos['juris'] = $juris;
        $datos['reporte'] = "juris";
        $datos['agregarHC'] = 0;
        
        //PARA LOS TITULOS DE LA TABLA
        $titulosTabla[1] = "PROMOCION DE LA SALUD";
        $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
        $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
        $titulosTabla[4] = "REGULACION SANITARIA";
        $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
        //////////////////////////////////
        
        $this->load->model('md_indicador');
        $indicadorPublicar = new MD_indicador(1);        
        $unidades_medida = array();

        //ESTA ES LA LINEA ORIGINAL
        $this->db->where_not_in('id',array(7,8,9,10,20,21,22));
        
        //ESTA LINEA ES PARA PUBLICAR SOLO UNOS INDICADORES
        //$this->db->where_in('id',array(79,78,93,57,167,168,169,172,73,76,55,165,96,140));
        
        $this->db->order_by('departamento,h');
        $consulta = $this->db->get('eval_unidad_medida');
        foreach($consulta->result() as $ren)
        {
            $unidades_medida[] = $ren->id;
            $unidadMedidaDepartamento[$ren->id] = $ren->departamento;
        }
        
        $this->load->helpers(array('form','html','url'));
        $this->load->library('table'); 
        
        foreach($unidades_medida as $row2)
        {
            $sumar = array();
            //echo $row2."<br />";
            
            //TRAE LOS NOMBRES DE LAS VARIABLES QUE SE VAN SUMAR PARA EL CALCULO            
            $uni_med = $indicadorPublicar->uni_med_calc($row2);
            
            //$juris = $this->md_indicador->um_juris($juris);
            //print_r($uni_med);
            
            //GUARDA LAS METAS, NOMBRES DE VARIABLES PARA EL CALCULO, ETC.
            foreach($uni_med as $ren)
            {
                $indicador = $ren['id'];
                $descripcion = $ren['descripcion'];
                $h = $ren['h'];
                $programa = $ren['programa'];
                $var = explode('+',$ren['calculo']);
                $j1 = $ren['juris1'];
                $j2 = $ren['juris2'];
                $j3 = $ren['juris3'];
                $j4 = $ren['juris4'];
                $j5 = $ren['juris5'];
                $j6 = $ren['juris6'];
                $j7 = $ren['juris7'];
                $acumular = $ren['acumular'];
                $qhc = $ren['qhc'];
            }
            //print_r($var);
            //echo "qhc: ".$qhc."<br />";
            
            //CONSULTA LOS VALORES DE LA VARIABLE UNO POR UNO PARA SUMARLOS DESPUES
            foreach ($var as $ren){
                //print_r($ren);
                //echo br();
                $operacion = strstr($ren,'/');
                //echo strlen($operacion)."<br />";
                if(strlen($operacion) != 0)
                    $ren = substr($ren,0,strlen($ren)-2);
                //echo br();
                $operacion = substr($operacion,1,1);
                //echo $operacion;
                //echo br();
                $cadena = substr($ren,0,4);
                //print_r($cadena);
                //echo br();
                //SI NO ES DE UN RESPONSABLE DE PROGRAMA
                if($cadena != "RESP")
                    $sumar[$ren] = $indicadorPublicar->valor_ind($ren,$mes,$anio,$juris,'soloJuris',$acumular,$operacion,$qhc);
                //SI ES DE UN RESPONSABLE DE PROGRAMA
                else
                    $sumar[$ren] = $indicadorPublicar->valor_ind_jur($ren,$mes,$anio,$juris);
              // print_r($sumar[$ren]);
            }
            $total = 0;
            //print_r($total);

            //SUMA LOS VALORES PARA LLEGAR AL CALCULO FINAL
            foreach($sumar as $ren)
               foreach($ren as $row){
                    //print_r($row['logro']);
                    //echo "<br />";
                $total = $total + $row['logro'];}
            
            //GUARDA LAS METAS EN LAS VARIABLES DE JURIS
            switch ($juris)
        {
            case "01": $meta = $j1;
                       break;
            case "02": $meta = $j2;
                       break;
            case "03": $meta = $j3;
                       break;
            case "04": $meta = $j4;
                       break;
            case "05": $meta = $j5;
                       break;
            case "06": $meta = $j6;
                       break;
            case "07": $meta = $j7;
                       break;
            default :  $meta = $j1 + $j2 + $j3 + $j4 + $j5 + $j6 + $j7;
                        break;
        }
            
            //CALCULA EL PORCENTAJE DE LOGRO POR LA META SI ES DIFERENTE DE CERO
            if($meta != 0)
                $porcentaje_logro = ($total * 100) / $meta;
            else
                $porcentaje_logro = 0;
            
            //SEMAFORIZA EL VALOR
            if($porcentaje_logro >= 120)
                $semaforo = "azul.jpg";
            else            
                if($porcentaje_logro >= 80)
                    $semaforo = "verde.jpg";
                else
                    if( $porcentaje_logro >= 60)
                        $semaforo = "amarillo.jpg";
                    else
                        if($porcentaje_logro < 60)
                            $semaforo = "rojo.jpg";
            
            //AGREGA EL NOMBRE DE LA IMAGEN PARA SEMAFORO
            $celda = array('data' => img('/img/'.$semaforo),
                            'align' => 'center');
            
            $valores = $this->meta_parcial($meta,$total,$mes,$acumular);
            $celda_total = array('data' => '<a href="'.site_url('/principal/metadatos').'/'.$row2.'/juris'.'/'.$mes.'/'.$anio.'/'.$juris.'" target="_blank">'.number_format($total).'</a>', 'align' => 'right' );                                                        
            $meta_parcial_celda = array('data' => number_format($valores['meta_parcial'],0), 'align'=>'right');
            $meta_logro_parcial_celda = array('data' => number_format($valores['porcentaje_logro_parcial'],2), 'align'=>'right');
            $meta_celda = array('data' => number_format($meta), 'align'=>'right');
            $meta_logro_anual_celda = array('data' => number_format($porcentaje_logro,2), 'align'=>'right');
            
            if($mes < 10)
                $mesAuxiliar = '0'.$mes;
            else
                $mesAuxiliar = $mes;
            
            //$this->table->add_row( $h,$programa,$indicador,$descripcion,$celda_total,$meta_parcial_celda,$meta_logro_parcial_celda,$valores['celda'],$meta_celda,$meta_logro_anual_celda );    
            $datos2[] = array('mes' => $mes,
                              'anio' => $anio,
                              'anioMes' => $anio.$mesAuxiliar,
                              'indicador' => $indicador,
                              'departamento' => $titulosTabla[$unidadMedidaDepartamento[$row2]],
                              'jurisdiccion' => $juris,
                              'logro' => $total,
                              'metaMes' => $valores['meta_parcial'],
                              'semaforo' => $valores['semaforo_parcial'],
                              'metaLogroMes' => $valores['porcentaje_logro_parcial'],
                              'metaAnual' => $meta,
                              'metaLogroAnual' => $porcentaje_logro);
            //print_r($datos2);
            //echo "<br />";
        }
        //print_r($datos2);
        $this->db->insert_batch('evaluacionvalidadajuris',$datos2);

    }
    }
    public function publicarJurisHc($mes,$anio,$juris)
    {
        {
        
        //GUARDA PARA ENVIAR A LA VISTA
        $datos['mes'] = $mes;
        $datos['anio'] = $anio;
        $datos['juris'] = $juris;
        $datos['reporte'] = "juris";
        $datos['agregarHC'] = 1;
        
        //PARA LOS TITULOS DE LA TABLA
        $titulosTabla[1] = "PROMOCION DE LA SALUD";
        $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
        $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
        $titulosTabla[4] = "REGULACION SANITARIA";
        $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
        //////////////////////////////////
        
        $this->load->model('md_indicador');
        $indicadorPublicar = new MD_indicador(1);
                
        $unidades_medida = array();

        $this->db->where_not_in('id',array(7,8,9,10,20,21,22));
        $this->db->order_by('departamento,h');
        $consulta = $this->db->get('eval_unidad_medida');
        foreach($consulta->result() as $ren)
        {
            $unidades_medida[] = $ren->id;
            $unidadMedidaDepartamento[$ren->id] = $ren->departamento;
        }
        
        $this->load->helpers(array('form','html','url'));
        $this->load->library('table'); 
        
        foreach($unidades_medida as $row2)
        {
            $sumar = array();
            //echo $row2."<br />";
            
            //TRAE LOS NOMBRES DE LAS VARIABLES QUE SE VAN SUMAR PARA EL CALCULO            
            $uni_med = $indicadorPublicar->uni_med_calc($row2);
            
            //$juris = $this->md_indicador->um_juris($juris);
            //print_r($uni_med);
            
            //GUARDA LAS METAS, NOMBRES DE VARIABLES PARA EL CALCULO, ETC.
            foreach($uni_med as $ren)
            {
                $indicador = $ren['id'];
                $descripcion = $ren['descripcion'];
                $h = $ren['h'];
                $programa = $ren['programa'];
                $var = explode('+',$ren['calculo']);
                $j1 = $ren['J1'];
                $j2 = $ren['J2'];
                $j3 = $ren['J3'];
                $j4 = $ren['J4'];
                $j5 = $ren['J5'];
                $j6 = $ren['J6'];
                $j7 = $ren['J7'];
                $acumular = $ren['acumular'];
                $qhc = $ren['qhc'];
            }
            //print_r($var);
            //echo "qhc: ".$qhc."<br />";
            
            //CONSULTA LOS VALORES DE LA VARIABLE UNO POR UNO PARA SUMARLOS DESPUES
            foreach ($var as $ren){
                //print_r($ren);
                //echo br();
                $operacion = strstr($ren,'/');
                //echo strlen($operacion)."<br />";
                if(strlen($operacion) != 0)
                    $ren = substr($ren,0,strlen($ren)-2);
                //echo br();
                $operacion = substr($operacion,1,1);
                //echo $operacion;
                //echo br();
                $cadena = substr($ren,0,4);
                //print_r($cadena);
                //echo br();
                //SI NO ES DE UN RESPONSABLE DE PROGRAMA
                if($cadena != "RESP")
                    $sumar[$ren] = $indicadorPublicar->valor_ind($ren,$mes,$anio,$juris,'todas',$acumular,$operacion);
                //SI ES DE UN RESPONSABLE DE PROGRAMA
                else
                    $sumar[$ren] = $indicadorPublicar->valor_ind_jur($ren,$mes,$anio,$juris);
              // print_r($sumar[$ren]);
            }
            $total = 0;
            //print_r($total);

            //SUMA LOS VALORES PARA LLEGAR AL CALCULO FINAL
            foreach($sumar as $ren)
               foreach($ren as $row){
                    //print_r($row['logro']);
                    //echo "<br />";
                $total = $total + $row['logro'];}
            
            //GUARDA LAS METAS EN LAS VARIABLES DE JURIS
            switch ($juris)
        {
            case "01": $meta = $j1;
                       break;
            case "02": $meta = $j2;
                       break;
            case "03": $meta = $j3;
                       break;
            case "04": $meta = $j4;
                       break;
            case "05": $meta = $j5;
                       break;
            case "06": $meta = $j6;
                       break;
            case "07": $meta = $j7;
                       break;
            default :  $meta = $j1 + $j2 + $j3 + $j4 + $j5 + $j6 + $j7;
                        break;
        }
            
            //CALCULA EL PORCENTAJE DE LOGRO POR LA META SI ES DIFERENTE DE CERO
            if($meta != 0)
                $porcentaje_logro = ($total * 100) / $meta;
            else
                $porcentaje_logro = 0;
            
            //SEMAFORIZA EL VALOR
            if($porcentaje_logro >= 120)
                $semaforo = "azul.jpg";
            else            
                if($porcentaje_logro >= 80)
                    $semaforo = "verde.jpg";
                else
                    if( $porcentaje_logro >= 60)
                        $semaforo = "amarillo.jpg";
                    else
                        if($porcentaje_logro < 60)
                            $semaforo = "rojo.jpg";
            
            //AGREGA EL NOMBRE DE LA IMAGEN PARA SEMAFORO
            $celda = array('data' => img('/img/'.$semaforo),
                            'align' => 'center');
            
            $valores = $this->meta_parcial($meta,$total,$mes,$acumular);
            $celda_total = array('data' => '<a href="'.site_url('/principal/metadatos').'/'.$row2.'/juris'.'/'.$mes.'/'.$anio.'/'.$juris.'" target="_blank">'.number_format($total).'</a>', 'align' => 'right' );                                                        
            $meta_parcial_celda = array('data' => number_format($valores['meta_parcial'],0), 'align'=>'right');
            $meta_logro_parcial_celda = array('data' => number_format($valores['porcentaje_logro_parcial'],2), 'align'=>'right');
            $meta_celda = array('data' => number_format($meta), 'align'=>'right');
            $meta_logro_anual_celda = array('data' => number_format($porcentaje_logro,2), 'align'=>'right');
            
            if($mes < 10)
                $mesAuxiliar = '0'.$mes;
            else
                $mesAuxiliar = $mes;
            
            //$this->table->add_row( $h,$programa,$indicador,$descripcion,$celda_total,$meta_parcial_celda,$meta_logro_parcial_celda,$valores['celda'],$meta_celda,$meta_logro_anual_celda );    
            $datos2[] = array('mes' => $mes,
                              'anio' => $anio,
                              'anioMes' => $anio.$mesAuxiliar,
                              'indicador' => $indicador,
                              'departamento' => $titulosTabla[$unidadMedidaDepartamento[$row2]],
                              'jurisdiccion' => $juris,
                              'logro' => $total,
                              'metaMes' => $valores['meta_parcial'],
                              'semaforo' => $valores['semaforo_parcial'],
                              'metaLogroMes' => $valores['porcentaje_logro_parcial'],
                              'metaAnual' => $meta,
                              'metaLogroAnual' => $porcentaje_logro);
            //print_r($datos2);
            //echo "<br />";
        }
        //print_r($datos2);
        $this->db->insert_batch('evaluacionvalidadajurishc',$datos2);

    }
    }
    public function publicarHc($mes,$anio,$hc)
    {
        // $this->output->enable_profiler();
        //$mes = $this->input->post('mes');
//        $anio = $this->input->post('anio');
//        $hc = $this->input->post('hc');

        //echo $hc."<br />";
        
        $datos['mes'] = $mes;
        $datos['anio'] = $anio;
        $datos['hc'] = $hc;
        $datos['reporte'] = "hc";
        //print_r($hc);
        
        //PARA LOS TITULOS DE LA TABLA
        $titulosTabla[1] = "PROMOCION DE LA SALUD";
        $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
        $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
        $titulosTabla[4] = "REGULACION SANITARIA";
        $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
        //////////////////////////////////
        
        $this->load->model('md_indicador');
        $indicadorPublicar = new MD_indicador(1);
        
        $unidades_medida = array();
        
        //$this->db->where_not_in('id',array(7,8,9,10,20,21,22));
        $this->db->order_by('departamento,h');
        $consulta = $this->db->get('eval_unidad_medida_1er');
        
        foreach($consulta->result() as $ren)
        {
            $unidades_medida[] = $ren->id;
            $unidadMedidaDepartamento[$ren->id] = $ren->departamento;
        }
        $this->load->helpers(array('form','html','url'));
        $this->load->library('table'); 
        
        foreach($unidades_medida as $row2)
        {
            $sumar = array();
            //echo $row2."<br />";            
            $uni_med = $indicadorPublicar->uni_med_calc_hc($row2);
            //$juris = $this->md_indicador->um_juris($juris);
            //print_r($uni_med);
            foreach($uni_med as $ren)
            {
                $indicador = $ren['indicador'];
                $idInd = $ren['id'];
                $descripcion = $ren['descripcion'];
                $h = $ren['h'];
                $programa = $ren['programa'];
                $var = explode('+',$ren['calculo']);
                $calera = $ren['calera'];
                $trancoso = $ren['trancoso'];
                $ojocaliente = $ren['ojocaliente'];
                $sombrerete = $ren['sombrerete'];
                $valparaiso = $ren['valparaiso'];
                $juan_aldama = $ren['juan_aldama'];
                $jalpa = $ren['jalpa'];
                $juchipila = $ren['juchipila'];
                $tabasco = $ren['tabasco'];
                $nochistlan = $ren['nochistlan'];
                $villa_de_cos = $ren['villa_de_cos'];
                $acumular = $ren['acumular'];
            }
            //print_r($var);
            //echo br();
            foreach ($var as $ren){
               // print_r($ren);
               // echo br();
               
               $operacion = strstr($ren,'/');
                //echo strlen($operacion)."<br />";
                if(strlen($operacion) != 0)
                    $ren = substr($ren,0,strlen($ren)-2);
                //echo br();
                $operacion = substr($operacion,1,1);
               
               $cadena = substr($ren,0,4);
               //print_r($cadena);
               //echo br();
               //AQUI TRAE EL VALOR DEL INDICADOR EN EL MES
                if($cadena != "RESP")
                    $sumar[$ren] = $indicadorPublicar->valor_ind_hc($ren,$mes,$anio,$hc,'H.C.',$acumular,$operacion);
                else
                    $sumar[$ren] = $indicadorPublicar->valor_ind_hc_resp($ren,$mes,$anio,$hc);
            }
            $total = 0;
            //print_r($total);

            foreach($sumar as $ren)
               foreach($ren as $row)
                   // print_r($row['logro']);
                $total = $total + $row['logro'];
            
            //print_r($hc);
            switch ($hc)
            {
              /*  case 'ESTATAL': $meta = $calera + $trancoso + $ojocaliente + $sombrerete +
                                        $valparaiso + $juan_aldama + $jalpa + $juchipila +
                                        $tabasco + $nochistlan + $villa_de_cos;
                                break;*/
                case 'ZSSSA000572': $meta = $jalpa;
                                break;
                case 'ZSSSA000695': $meta = $juan_aldama;
                                break;
                case 'ZSSSA000700': $meta = $juchipila;
                                break;
                case 'ZSSSA000922': $meta = $nochistlan;
                                break;
                case 'ZSSSA001016': $meta = $ojocaliente;
                                break;
                case 'ZSSSA001313': $meta = $sombrerete;
                                break;
                case 'ZSSSA001395': $meta = $tabasco;
                                break;
                case 'ZSSSA001506': $meta = $valparaiso;
                                break;
                case 'ZSSSA001861': $meta = $trancoso;
                                break;
                case 'ZSSSA002136': $meta = $calera;
                                break;
                case 'ZSSSA002141': $meta = $villa_de_cos;
                                break;
                default: $meta = $calera + $trancoso + $ojocaliente + $sombrerete +
                                        $valparaiso + $juan_aldama + $jalpa + $juchipila +
                                        $tabasco + $nochistlan + $villa_de_cos;
                                break;
            }
            if($meta != 0)
                $porcentaje_logro = ($total * 100) / $meta;
            else
                $porcentaje_logro = 0;
            
            if($porcentaje_logro >= 120)
                $semaforo = "azul.jpg";
            else
            
                if($porcentaje_logro >= 80)
                    $semaforo = "verde.jpg";
                else
                    if( $porcentaje_logro >= 60)
                        $semaforo = "amarillo.jpg";
                    else
                        if($porcentaje_logro < 60)
                            $semaforo = "rojo.jpg";
            
            $celda = array('data' => img('/img/'.$semaforo),
                            'align' => 'center');
            
            $valores = $this->meta_parcial($meta,$total,$mes,$acumular);
            
            $celda_total = array('data' => '<a href="'.site_url('/principal/metadatos').'/'.$row2.'/hc/'.$mes.'/'.$anio.'/'.$hc.'" target="_blank">'.number_format($total).'</a>', 'align' => 'right' );
                                                                    
            $meta_parcial_celda = array('data' => number_format($valores['meta_parcial'],0), 'align'=>'right');
            $meta_logro_parcial_celda = array('data' => number_format($valores['porcentaje_logro_parcial'],2), 'align'=>'right');
            $meta_celda = array('data' => number_format($meta), 'align'=>'right');
            $meta_logro_anual_celda = array('data' => number_format($porcentaje_logro,2), 'align'=>'right');
            
            //$this->table->add_row( $h,$programa,$indicador,$descripcion,$celda_total,$meta_parcial_celda,$meta_logro_parcial_celda,$valores['celda'],$meta_celda,$meta_logro_anual_celda );
            
            if($mes < 10)
                $mesAuxiliar = '0'.$mes;
            else
                $mesAuxiliar = $mes;
            //echo "indicador: ".$indicador;
            
            $datos2[] = array('mes' => $mes,
                              'anio' => $anio,
                              'anioMes' => $anio.$mesAuxiliar,
                              'indicador' => $idInd,
                              'departamento' => $titulosTabla[$unidadMedidaDepartamento[$row2]],
                              'unidadMedica' => $hc,
                              'logro' => $total,
                              'metaMes' => $valores['meta_parcial'],
                              'semaforo' => $valores['semaforo_parcial'],
                              'metaLogroMes' => $valores['porcentaje_logro_parcial'],
                              'metaAnual' => $meta,
                              'metaLogroAnual' => $porcentaje_logro);
            
            //$this->table->add_row( $h,$programa,$indicador,$descripcion,number_format($meta),number_format($total),$celda,number_format($porcentaje_logro,2) );    
            //print_r($total);
            //echo "<br />";
        }
        
        $this->db->insert_batch('evaluacionvalidadaprimernivel',$datos2);
    }
    public function publicar2Nivel($mes,$anio,$hc)
    {
        //$this->output->enable_profiler();
        //$mes = $this->input->post('mes');
//        $anio = $this->input->post('anio');
//        $hc = $this->input->post('hghehc');
        
        $datos['mes'] = $mes;
        $datos['anio'] = $anio;
        $datos['hc'] = $hc;
        $datos['reporte'] = "2n";
        
        $this->load->model('md_indicador');
        $indicador = new MD_indicador(1);
        $unidades_medida = array();
        for($i = 1; $i<= 34;$i++)
            if($i != 26 && $i != 24 && $i != 10 && $i != 18 && $i != 19)
                $unidades_medida[] = $i;
        $this->load->helpers(array('form','html','url'));
        $this->load->library('table'); 
        
        foreach($unidades_medida as $row2)
        {
            $sumar = array();
            //echo $row2."<br />";            
            $uni_med = $indicador->uni_med_calc_2n($row2);
            //$juris = $this->md_indicador->um_juris($juris);
            //print_r($uni_med);
            foreach($uni_med as $ren)
            {
                //$indicador = $ren['indicador'];
                $descripcion = $ren['descripcion'];
                $h = $ren['h'];
                $programa = $ren['programa'];
                $var = explode('+',$ren['calculo']);
                $zacatecas = $ren['zacatecas'];
                $fresnillo = $ren['fresnillo'];
                $jerez = $ren['jerez'];
                $loreto = $ren['loreto'];
                $calera = $ren['calera'];
                $trancoso = $ren['trancoso'];
                $ojocaliente = $ren['ojocaliente'];
                $sombrerete	= $ren['sombrerete'];
                $valparaiso = $ren['valparaiso'];
                $juan_aldama = $ren['juan_aldama'];
                $jalpa = $ren['jalpa'];
                $juchipila = $ren['juchipila'];
                $tabasco = $ren['tabasco'];
                $nochistlan = $ren['nochistlan'];
                $villa_de_cos = $ren['villa_de_cos'];
                $mujer = $ren['mujer'];
                $psiquiatrico = $ren['psiquiatrico'];
                $acumular = $ren['acumular'];
                $idInd = $ren['id'];
            }
            //print_r($var);
            //echo br();
            foreach ($var as $ren){
               // print_r($ren);
               // echo br();
               $cadena = substr($ren,0,4);
               //print_r($cadena);
               //echo br();
                if($cadena != "RESP")
                {
                    if($cadena != "SAEH")
                    {
                        $sumar[$ren] = $indicador->valor_ind_2n($ren,$mes,$anio,$hc,array('H.C.','H.E.','H.G.'),$acumular);
                    }
                    else
                    {
                        //echo $mes."<br />";
                        $sumar[$ren] = $indicador->valor_ind_2n_saeh($ren,$mes,$anio,$hc,array('H.C.','H.E.','H.G.'),$acumular);
                    }
                }
                else
                    $sumar[$ren] = $indicador->valor_ind_2do_resp($ren,$mes,$anio,$hc);
            }
            $total = 0;
            //print_r($sumar['SAEH6']);

            foreach($sumar as $ren)
               foreach($ren as $row)
                   // print_r($row['logro']);
                $total = $total + $row['logro'];
            
            switch ($hc)
            {
                case 'ZSSSA000152': $meta = $fresnillo;
                                    break;
                case 'ZSSSA013172': $meta = 0;
                                    break;
                case 'ZSSSA000572': $meta = $jalpa;
                                    break;
                case 'ZSSSA000613': $meta = $jerez;
                                    break;
                case 'ZSSSA000695': $meta = $juan_aldama;
                                    break;
                case 'ZSSSA000700': $meta = $juchipila;
                                    break;
                case 'ZSSSA000922': $meta = $nochistlan;
                                    break;
                case 'ZSSSA001016': $meta = $ojocaliente;
                                    break;
                case 'ZSSSA001313': $meta = $sombrerete;
                                    break;
                case 'ZSSSA001395': $meta = $tabasco;
                                    break;
                case 'ZSSSA001506': $meta = $valparaiso;
                                    break;
                case 'ZSSSA001861': $meta = $trancoso;
                                    break;
                case 'ZSSSA002136': $meta = $calera;
                                    break;
                case 'ZSSSA002141': $meta = $villa_de_cos;
                                    break;
                case 'ZSSSA012450': $meta = $mujer;
                                    break;
                case 'ZSSSA012771': $meta = $psiquiatrico;
                                    break;
                case 'ZSSSA012853': $meta = $loreto;
                                    break;
                case 'ZSSSA013143': $meta = $zacatecas;
                                    break;
                
                case 'todas': $meta = $zacatecas + $fresnillo + $jerez +
                                      $loreto + $calera + $trancoso +
                                      $ojocaliente + $sombrerete + $valparaíso +
                                      $juan_aldama + $jalpa + $juchipila +
                                      $tabasco + $nochistlan + $villa_de_cos +
                                      $mujer + $psiquiatrico;
                                break;
            }
    
            if($meta != 0)
                $porcentaje_logro = ($total * 100) / $meta;
            else
                $porcentaje_logro = 0;
            
            if($porcentaje_logro >= 120)
                $semaforo = "azul.jpg";
            else
            
                if($porcentaje_logro >= 80)
                    $semaforo = "verde.jpg";
                else
                    if( $porcentaje_logro >= 60)
                        $semaforo = "amarillo.jpg";
                    else
                        if($porcentaje_logro < 60)
                            $semaforo = "rojo.jpg";
            
            $celda = array('data' => img('/img/'.$semaforo),
                            'align' => 'center');
            
            $valores = $this->meta_parcial($meta,$total,$mes,$acumular);
            
            $celda_total = array('data' => '<a href="'.site_url('/principal/metadatos').'/'.$row2.'/2n/'.$mes.'/'.$anio.'/'.$hc.'" target="_blank">'.number_format($total).'</a>', 'align' => 'right' );
                                                                    
            $meta_parcial_celda = array('data' => number_format($valores['meta_parcial'],0), 'align'=>'right');
            $meta_logro_parcial_celda = array('data' => number_format($valores['porcentaje_logro_parcial'],2), 'align'=>'right');
            $meta_celda = array('data' => number_format($meta), 'align'=>'right');
            $meta_logro_anual_celda = array('data' => number_format($porcentaje_logro,2), 'align'=>'right');
            
            //$this->table->add_row( $h,$programa,$descripcion,$celda_total,$meta_parcial_celda,$meta_logro_parcial_celda,$valores['celda'],$meta_celda,$meta_logro_anual_celda );
            //$datos2[] = array('h' => $h,
//                              'programa' => $programa,
//                              'descripcion' => $descripcion,
//                              'total' => number_format($total),
//                              'meta_parcial' => number_format($valores['meta_parcial'],0),
//                              's_parcial' => $valores['celda']['data'],
//                              'meta_logro_p' => number_format($valores['porcentaje_logro_parcial'],2),
//                              'meta' => number_format($meta),
//                              's' => $celda['data'],
//                              'meta_logro' => number_format($porcentaje_logro,2));
            if($mes < 10)
                $mesAuxiliar = '0'.$mes;
            else
                $mesAuxiliar = $mes;
                
            $datos2[] = array('mes' => $mes,
                              'anio' => $anio,
                              'anioMes' => $anio.$mesAuxiliar,
                              'indicador' => $idInd,
                              'departamento' => 0,
                              'unidadMedica' => $hc,
                              'logro' => $total,
                              'metaMes' => $valores['meta_parcial'],
                              'semaforo' => $valores['semaforo_parcial'],
                              'metaLogroMes' => $valores['porcentaje_logro_parcial'],
                              'metaAnual' => $meta,
                              'metaLogroAnual' => $porcentaje_logro);
            //$this->table->add_row( $h,$programa,$descripcion,number_format($meta),number_format($total),$celda,number_format($porcentaje_logro,2) );    
            //print_r($total);
            //echo "<br />";
        }
        $this->db->insert_batch('evaluacionvalidadasegundonivel',$datos2);
    }
    public function meta_parcial($meta,$total,$mes,$acumular)
    {
        if($acumular != 'NO')
        {       
            $meta_parcial = $meta / 12;
            $meta_parcial = $meta_parcial * $mes;
        }
        else  
            $meta_parcial = $meta;                        
                                
        if($meta_parcial != 0)
            $porcentaje_logro_parcial = ($total * 100) / $meta_parcial;
        else
            $porcentaje_logro_parcial = 0;
        
        if($porcentaje_logro_parcial >= 120)
            $semaforo_parcial = "azul";
        else            
            if($porcentaje_logro_parcial >= 80)
                $semaforo_parcial = "verde";
            else
                if( $porcentaje_logro_parcial >= 60)
                    $semaforo_parcial = "amarillo";
                else
                    if($porcentaje_logro_parcial < 60)
                        $semaforo_parcial = "rojo";
        
        if($meta == 0)
            $semaforo_parcial = "blanco";
        
        $res['meta_parcial'] = $meta_parcial;
        $res['porcentaje_logro_parcial'] = $porcentaje_logro_parcial;
        $res['semaforo_parcial'] = $semaforo_parcial;        
        $res['celda'] = array('data' => img('/img/'.$semaforo_parcial),
                        'align' => 'center');                                                
        
        return $res;
    }
    public function reporteJuris($mes,$anio,$juris)
    {
        //$this->output->enable_profiler();
        $this->load->library("table");
        if($mes < 10)
            $mes = "0".$mes;
        $this->db->where('anioMes',$anio.$mes);
        if($juris != 'todas')
            $this->db->where('jurisdiccion',$juris);
        else
            $this->db->where('jurisdiccion','0');
        $this->db->order_by('dpto_orden,h');
        $consulta = $this->db->get('vw_reportejuris');
        //print_r($consulta->result_array());
        
        //PARA LOS TITULOS DE LA TABLA
        $titulosTabla[1] = "PROMOCION DE LA SALUD";
        $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
        $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
        $titulosTabla[4] = "REGULACION SANITARIA";
        $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
        //////////////////////////////////
        
        $this->table->set_template($this->tmpl);
        
        $mes_txt = $this->convertir_mes_txt($mes);
        
        $celda1 = array('data' => 'META ANUAL',
                            'bgcolor' => 'yellow');
        $celda2 = array('data' => 'S',
                            'bgcolor' => 'yellow');
        $celda3 = array('data' => '% META-LOGRO ANUAL',
                            'bgcolor' => 'yellow');
        $celda4 = array('data' => '<font color="white">META '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        $celda5 = array('data' => '<select id="slc_semaforo" class="dropdown" onchange="muestra(this.value)">
        	<option class="slc_azul" value="azul">A</option>
            <option class="slc_verde" value="verde">V</option>
            <option class="slc_amarillo" value="amarillo">A</option>
        	<option class="slc_rojo" value="rojo">R</option>
        	<option value="blanco">B</option>
        	<option value="todos" selected="true">T</option>
        </select>',
                            'bgcolor' => 'green');
        $celda6 = array('data' => '<font color="white">% META-LOGRO '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        
        $this->table->set_heading('H','PROGRAMA','DESCRIPCION','UNIDAD DE MEDIDA','LOGRO',$celda4,$celda6,$celda5,$celda1,$celda3);
        
        $vinculoMetadatos = 0; 
        $this->load->model('md_informacion');
        $mesActual = $this->md_informacion->mesActualPublicado('juris');
        if($mesActual == $mes)
            $vinculoMetadatos = 1;
            
        //echo "mes actual: ".$mesActual;
        
        foreach($consulta->result() as $ren)
        {
            //PARA LOS TITULOS DEL DEPARTAMENTO
            if($titulosTabla[$ren->dpto_orden] != ""){
                $estiloTituloDepartamento = array('data' => $titulosTabla[$ren->dpto_orden],
                                                    'bgcolor' => 'lightgreen',
                                                    'colspan' => '3');
                $this->table->add_row($estiloTituloDepartamento);
                $titulosTabla[$ren->dpto_orden] = "";
            }
            ///////////////////////////////////
            
            if($vinculoMetadatos)
                $logro = array('data' => '<a href="'.site_url('/principal/metadatos/'.$ren->id.'/juris/'.$mes.'/'.$anio.'/'.$juris).'" target="_blank">'.number_format($ren->logro,0).'</a>',
                            'align' => 'right');    
            else
                $logro = array('data' => number_format($ren->logro,0),
                            'align' => 'right');
            
            $metaMes = array('data' => number_format($ren->metaMes,0),
                             'align' => 'right');
            $metaLogroMes = array('data' => number_format($ren->metaLogroMes,2),
                                    'align' => 'right');
            $metaAnual = array('data' => number_format($ren->metaAnual,0),
                                'align' => 'right');
            $metaLogroAnual = array('data' => number_format($ren->metaLogroAnual,2),
                                    'align' => 'right');         
                        
            $this->table->add_row($ren->h,$ren->programa,$ren->descripcion,$ren->unidad,$logro,$metaMes,$metaLogroMes,'<div class="'.$ren->semaforo.'"></div>',$metaAnual, $metaLogroAnual);
        }
        return $this->table->generate();
    }
    
    public function reporteJurisHc($mes,$anio,$juris)
    {
        //$this->output->enable_profiler();
        $this->load->library("table");
        if($mes < 10)
            $mes = "0".$mes;
        $this->db->where('anioMes',$anio.$mes);
        if($juris != 'todas')
            $this->db->where('jurisdiccion',$juris);
        else
            $this->db->where('jurisdiccion','0');
        $this->db->order_by('dpto_orden,h');
        $consulta = $this->db->get('vw_reportejurishc');
        //print_r($consulta->result_array());
        
        //PARA LOS TITULOS DE LA TABLA
        $titulosTabla[1] = "PROMOCION DE LA SALUD";
        $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
        $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
        $titulosTabla[4] = "REGULACION SANITARIA";
        $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
        //////////////////////////////////
        
        $this->table->set_template($this->tmpl);
        
        $mes_txt = $this->convertir_mes_txt($mes);
        
        $celda1 = array('data' => 'META ANUAL',
                            'bgcolor' => 'yellow');
        $celda2 = array('data' => 'S',
                            'bgcolor' => 'yellow');
        $celda3 = array('data' => '% META-LOGRO ANUAL',
                            'bgcolor' => 'yellow');
        $celda4 = array('data' => '<font color="white">META '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        $celda5 = array('data' => '<select id="slc_semaforo" class="dropdown" onchange="muestra(this.value)">
        	<option class="slc_azul" value="azul">A</option>
            <option class="slc_verde" value="verde">V</option>
            <option class="slc_amarillo" value="amarillo">A</option>
        	<option class="slc_rojo" value="rojo">R</option>
        	<option value="blanco">B</option>
        	<option value="todos" selected="true">T</option>
        </select>',
                            'bgcolor' => 'green');
        $celda6 = array('data' => '<font color="white">% META-LOGRO '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        
        $this->table->set_heading('H','PROGRAMA','DESCRIPCION','UNIDAD DE MEDIDA','LOGRO',$celda4,$celda6,$celda5,$celda1,$celda3);
        
        $vinculoMetadatos = 0; 
        
        $this->load->model('md_informacion');
        $mesActual = $this->md_informacion->mesActualPublicado('jurishc');
        
        if($mesActual == $mes)
            $vinculoMetadatos = 1;
        
        foreach($consulta->result() as $ren)
        {
            //PARA LOS TITULOS DEL DEPARTAMENTO
            if($titulosTabla[$ren->dpto_orden] != ""){
                $estiloTituloDepartamento = array('data' => $titulosTabla[$ren->dpto_orden],
                                                    'bgcolor' => 'lightgreen',
                                                    'colspan' => '3');
                $this->table->add_row($estiloTituloDepartamento);
                $titulosTabla[$ren->dpto_orden] = "";
            }
            ///////////////////////////////////
            
            if($vinculoMetadatos)
                $logro = array('data' => '<a href="'.site_url('/principal/metadatos/'.$ren->id.'/jurishc/'.$mes.'/'.$anio.'/'.$juris).'" target="_blank">'.number_format($ren->logro,0).'</a>',
                            'align' => 'right');
            else
                $logro = array('data' => number_format($ren->logro,0),
                            'align' => 'right');
            
            $metaMes = array('data' => number_format($ren->metaMes,0),
                             'align' => 'right');
            $metaLogroMes = array('data' => number_format($ren->metaLogroMes,2),
                                    'align' => 'right');
            $metaAnual = array('data' => number_format($ren->metaAnual,0),
                                'align' => 'right');
            $metaLogroAnual = array('data' => number_format($ren->metaLogroAnual,2),
                                    'align' => 'right');         
                        
            $this->table->add_row($ren->h,$ren->programa,$ren->descripcion,$ren->unidad,$logro,$metaMes,$metaLogroMes,'<div class="'.$ren->semaforo.'"></div>',$metaAnual, $metaLogroAnual);
        }
        return $this->table->generate();
    }
    
    public function reporteHc($mes,$anio,$hc)
    {
        //$this->output->enable_profiler();
        $this->load->library("table");
        if($mes < 10)
            $mes = "0".$mes;
        $this->db->where('anioMes',$anio.$mes);
        if($hc != 'todas')
            $this->db->where('unidadMedica',$hc);
        else
            $this->db->where('unidadMedica','todas');
        $this->db->order_by('dpto_orden,h');
        $consulta = $this->db->get('vw_reportehc');
        //print_r($consulta->result_array());
        
        //PARA LOS TITULOS DE LA TABLA
        $titulosTabla[1] = "PROMOCION DE LA SALUD";
        $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
        $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
        $titulosTabla[4] = "REGULACION SANITARIA";
        $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
        //////////////////////////////////
        
        $this->table->set_template($this->tmpl);
        
        $mes_txt = $this->convertir_mes_txt($mes);
        
        $celda1 = array('data' => 'META ANUAL',
                            'bgcolor' => 'yellow');
        $celda2 = array('data' => 'S',
                            'bgcolor' => 'yellow');
        $celda3 = array('data' => '% META-LOGRO ANUAL',
                            'bgcolor' => 'yellow');
        $celda4 = array('data' => '<font color="white">META '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        $celda5 = array('data' => '<select id="slc_semaforo" class="dropdown" onchange="muestra(this.value)">
        	<option class="slc_azul" value="azul">A</option>
            <option class="slc_verde" value="verde">V</option>
            <option class="slc_amarillo" value="amarillo">A</option>
        	<option class="slc_rojo" value="rojo">R</option>
        	<option value="blanco">B</option>
        	<option value="todos" selected="true">T</option>
        </select>',
                            'bgcolor' => 'green');
        $celda6 = array('data' => '<font color="white">% META-LOGRO '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        $celda6 = array('data' => '<font color="white">% META-LOGRO '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        
        $this->table->set_heading('H','PROGRAMA','DESCRIPCION','UNIDAD DE MEDIDA','LOGRO',$celda4,$celda6,$celda5,$celda1,$celda3);
        
        $vinculoMetadatos = 0; 
        
        $this->load->model('md_informacion');
        $mesActual = $this->md_informacion->mesActualPublicado('hc');
        
        if($mesActual == $mes)
            $vinculoMetadatos = 1;
        
        foreach($consulta->result() as $ren)
        {
            //PARA LOS TITULOS DEL DEPARTAMENTO
            if($titulosTabla[$ren->dpto_orden] != ""){
                $estiloTituloDepartamento = array('data' => $titulosTabla[$ren->dpto_orden],
                                                    'bgcolor' => 'lightgreen',
                                                    'colspan' => '3');
                $this->table->add_row($estiloTituloDepartamento);
                $titulosTabla[$ren->dpto_orden] = "";
            }
            ///////////////////////////////////
            
            if($vinculoMetadatos)
                $logro = array('data' => '<a href="'.site_url('/principal/metadatos/'.$ren->id.'/hc/'.$mes.'/'.$anio.'/'.$hc).'" target="_blank">'.number_format($ren->logro,0).'</a>',
                                'align' => 'right');
            else
                $logro = array('data' => number_format($ren->logro,0),
                                'align' => 'right');
            $metaMes = array('data' => number_format($ren->metaMes,0),
                             'align' => 'right');
            $metaLogroMes = array('data' => number_format($ren->metaLogroMes,2),
                                    'align' => 'right');
            $metaAnual = array('data' => number_format($ren->metaAnual,0),
                                'align' => 'right');
            $metaLogroAnual = array('data' => number_format($ren->metaLogroAnual,2),
                                    'align' => 'right');         
                        
            $this->table->add_row($ren->h,$ren->programa,$ren->descripcion,$ren->unidad,$logro,$metaMes,$metaLogroMes,'<div class="'.$ren->semaforo.'"></div>',$metaAnual, $metaLogroAnual);
        }
        return $this->table->generate();
    }
    
    public function reporte2Nivel($mes,$anio,$hc)
    {
        //$this->output->enable_profiler();
        $this->load->library("table");
        if($mes < 10)
            $mes = "0".$mes;
        $this->db->where('anioMes',$anio.$mes);
        if($hc != 'todas')
            $this->db->where('unidadMedica',$hc);
        else
            $this->db->where('unidadMedica','todas');
        $this->db->order_by('h');
        $consulta = $this->db->get('vw_reporte2nivel');
        //print_r($consulta->result_array());
        
        //PARA LOS TITULOS DE LA TABLA
        //$titulosTabla[1] = "PROMOCION DE LA SALUD";
//        $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
//        $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
//        $titulosTabla[4] = "REGULACION SANITARIA";
//        $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
        //////////////////////////////////
        
        $this->table->set_template($this->tmpl);
        
        $mes_txt = $this->convertir_mes_txt($mes);
        
        $celda1 = array('data' => 'META ANUAL',
                            'bgcolor' => 'yellow');
        $celda2 = array('data' => 'S',
                            'bgcolor' => 'yellow');
        $celda3 = array('data' => '% META-LOGRO ANUAL',
                            'bgcolor' => 'yellow');
        $celda4 = array('data' => '<font color="white">META '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        $celda5 = array('data' => '<select id="slc_semaforo" class="dropdown" onchange="muestra(this.value)">
        	<option class="slc_azul" value="azul">A</option>
            <option class="slc_verde" value="verde">V</option>
            <option class="slc_amarillo" value="amarillo">A</option>
        	<option class="slc_rojo" value="rojo">R</option>
        	<option value="blanco">B</option>
        	<option value="todos" selected="true">T</option>
        </select>',
                            'bgcolor' => 'green');
        $celda6 = array('data' => '<font color="white">% META-LOGRO '.$mes_txt."</font>",
                            'bgcolor' => 'green');
        
        $this->table->set_heading('H','PROGRAMA','UNIDAD DE MEDIDA','LOGRO',$celda4,$celda6,$celda5,$celda1,$celda3);
        
        $vinculoMetadatos = 0; 
        
        $this->load->model('md_informacion');
        $mesActual = $this->md_informacion->mesActualPublicado('2nivel');
        
        if($mesActual == $mes)
            $vinculoMetadatos = 1;
            
        //echo "mes actual: ".$mesActual;
        
        foreach($consulta->result() as $ren)
        {
            //PARA LOS TITULOS DEL DEPARTAMENTO
            //if($titulosTabla[$ren->dpto_orden] != ""){
//                $estiloTituloDepartamento = array('data' => $titulosTabla[$ren->dpto_orden],
//                                                    'bgcolor' => 'lightgreen',
//                                                    'colspan' => '3');
//                $this->table->add_row($estiloTituloDepartamento);
//                $titulosTabla[$ren->dpto_orden] = "";
//            }
            ///////////////////////////////////
            
            if($vinculoMetadatos)
                $logro = array('data' => '<a href="'.site_url('/principal/metadatos/'.$ren->id.'/2n/'.$mes.'/'.$anio.'/'.$hc).'" target="_blank">'.number_format($ren->logro,0).'</a>',
                                'align' => 'right');
            else
                $logro = array('data' => number_format($ren->logro,0),
                                'align' => 'right');
                
            $metaMes = array('data' => number_format($ren->metaMes,0),
                             'align' => 'right');
            $metaLogroMes = array('data' => number_format($ren->metaLogroMes,2),
                                    'align' => 'right');
            $metaAnual = array('data' => number_format($ren->metaAnual,0),
                                'align' => 'right');
            $metaLogroAnual = array('data' => number_format($ren->metaLogroAnual,2),
                                    'align' => 'right');         
                        
            $this->table->add_row($ren->h,$ren->programa,$ren->unidad,$logro,$metaMes,$metaLogroMes,'<div class="'.$ren->semaforo.'"></div>',$metaAnual, $metaLogroAnual);
        }
        return $this->table->generate();
    }
    
    public function reporteJuris_pdf($mes,$anio,$juris)
    {
        //$this->output->enable_profiler();
        $this->load->library("table");
        if($mes < 10)
            $mes = "0".$mes;
        $this->db->where('anioMes',$anio.$mes);
        if($juris != 'todas')
            $this->db->where('jurisdiccion',$juris);
        else
            $this->db->where('jurisdiccion','0');
        $this->db->order_by('dpto_orden,h');
        $consulta = $this->db->get('vw_reportejuris');
        //print_r($consulta->result_array());
        
        foreach($consulta->result() as $ren)
        {
            $datos['datos'][] = array('idInd' => $ren->id,
                              'h' => $ren->h,
                              'departamento' => $ren->departamento,
                              'programa' => $ren->programa,
                              'indicador' => $ren->descripcion,
                              'descripcion' => $ren->unidad,
                              'total' => number_format($ren->logro),
                              'meta_parcial' => number_format($ren->metaMes,0),
                              's_parcial' => $ren->semaforo,
                              'meta_logro_p' => number_format($ren->metaLogroMes,2),
                              'meta' => number_format($ren->metaAnual),
                              's' => $ren->semaforo,
                              'meta_logro' => number_format($ren->metaLogroAnual,2));
             $datos['unidadMedidaDepartamento'][$ren->id] = $ren->dpto_orden;           
            
            //$this->table->add_row($ren->h,$ren->programa,$ren->descripcion,$ren->unidad,$logro,$metaMes,$metaLogroMes,'<div class="'.$ren->semaforo.'"></div>',$metaAnual, $metaLogroAnual);
        }
        return $datos;
    }
    
    public function reporteJurisHc_pdf($mes,$anio,$juris)
    {
        //$this->output->enable_profiler();
        $this->load->library("table");
        if($mes < 10)
            $mes = "0".$mes;
        $this->db->where('anioMes',$anio.$mes);
        if($juris != 'todas')
            $this->db->where('jurisdiccion',$juris);
        else
            $this->db->where('jurisdiccion','0');
        $this->db->order_by('dpto_orden,h');
        $consulta = $this->db->get('vw_reportejurishc');
        //print_r($consulta->result_array());
        
        foreach($consulta->result() as $ren)
        {
            $datos['datos'][] = array('idInd' => $ren->id,
                              'h' => $ren->h,
                              'departamento' => $ren->departamento,
                              'programa' => $ren->programa,
                              'indicador' => $ren->descripcion,
                              'descripcion' => $ren->unidad,
                              'total' => number_format($ren->logro),
                              'meta_parcial' => number_format($ren->metaMes,0),
                              's_parcial' => $ren->semaforo,
                              'meta_logro_p' => number_format($ren->metaLogroMes,2),
                              'meta' => number_format($ren->metaAnual),
                              's' => $ren->semaforo,
                              'meta_logro' => number_format($ren->metaLogroAnual,2));
             $datos['unidadMedidaDepartamento'][$ren->id] = $ren->dpto_orden;           
            
            //$this->table->add_row($ren->h,$ren->programa,$ren->descripcion,$ren->unidad,$logro,$metaMes,$metaLogroMes,'<div class="'.$ren->semaforo.'"></div>',$metaAnual, $metaLogroAnual);
        }
        return $datos;
    }
    
    public function reporteHc_pdf($mes,$anio,$hc)
    {
        //$this->output->enable_profiler();
        $this->load->library("table");
        if($mes < 10)
            $mes = "0".$mes;
        $this->db->where('anioMes',$anio.$mes);
        if($hc != 'todas')
            $this->db->where('unidadMedica',$hc);
        else
            $this->db->where('unidadMedica','todas');
        $this->db->order_by('dpto_orden,h');
        $consulta = $this->db->get('vw_reportehc');
        //print_r($consulta->result_array());
        
        foreach($consulta->result() as $ren)
        {
            $datos['datos'][] = array('idInd' => $ren->id,
                              'h' => $ren->h,
                              'departamento' => $ren->departamento,
                              'programa' => $ren->programa,
                              'indicador' => $ren->descripcion,
                              'descripcion' => $ren->unidad,
                              'total' => number_format($ren->logro),
                              'meta_parcial' => number_format($ren->metaMes,0),
                              's_parcial' => $ren->semaforo,
                              'meta_logro_p' => number_format($ren->metaLogroMes,2),
                              'meta' => number_format($ren->metaAnual),
                              's' => $ren->semaforo,
                              'meta_logro' => number_format($ren->metaLogroAnual,2));
             $datos['unidadMedidaDepartamento'][$ren->id] = $ren->dpto_orden;           
            
            //$this->table->add_row($ren->h,$ren->programa,$ren->descripcion,$ren->unidad,$logro,$metaMes,$metaLogroMes,'<div class="'.$ren->semaforo.'"></div>',$metaAnual, $metaLogroAnual);
        }
        return $datos;
    }
    
    public function reporte2Nivel_pdf($mes,$anio,$hc)
    {
        //$this->output->enable_profiler();
        $this->load->library("table");
        if($mes < 10)
            $mes = "0".$mes;
        $this->db->where('anioMes',$anio.$mes);
        if($hc != 'todas')
            $this->db->where('unidadMedica',$hc);
        else
            $this->db->where('unidadMedica','todas');
        $this->db->order_by('h');
        $consulta = $this->db->get('vw_reporte2nivel');
        //print_r($consulta->result_array());
        
        foreach($consulta->result() as $ren)
        {
            $datos['datos'][] = array('idInd' => $ren->id,
                              'h' => $ren->h,
                              'departamento' => $ren->departamento,
                              'programa' => $ren->programa,
                              'indicador' => $ren->descripcion,
                              'descripcion' => $ren->unidad,
                              'total' => number_format($ren->logro),
                              'meta_parcial' => number_format($ren->metaMes,0),
                              's_parcial' => $ren->semaforo,
                              'meta_logro_p' => number_format($ren->metaLogroMes,2),
                              'meta' => number_format($ren->metaAnual),
                              's' => $ren->semaforo,
                              'meta_logro' => number_format($ren->metaLogroAnual,2));
             $datos['unidadMedidaDepartamento'][$ren->id] = $ren->dpto_orden;           
            
            //$this->table->add_row($ren->h,$ren->programa,$ren->descripcion,$ren->unidad,$logro,$metaMes,$metaLogroMes,'<div class="'.$ren->semaforo.'"></div>',$metaAnual, $metaLogroAnual);
        }
        return $datos;
    }
    
    function convertir_mes_txt($mes)
    {
        switch($mes)
        {
            case '01': $mes_txt = 'ENERO';
                        break;
            case '02': $mes_txt = 'FEBRERO';
                        break;
            case '03': $mes_txt = 'MARZO';
                        break;
            case '04': $mes_txt = 'ABRIL';
                        break;
            case '05': $mes_txt = 'MAYO';
                        break;
            case '06': $mes_txt = 'JUNIO';
                        break;
            case '07': $mes_txt = 'JULIO';
                        break;
            case '08': $mes_txt = 'AGOSTO';
                        break;
            case '09': $mes_txt = 'SEPTIEMBRE';
                        break;
            case '10': $mes_txt = 'OCTUBRE';
                        break;
            case '11': $mes_txt = 'NOVIEMBRE';
                        break;
            case '12': $mes_txt = 'DICIEMBRE';
                        break;
        }
        return $mes_txt;
    }
}

?>