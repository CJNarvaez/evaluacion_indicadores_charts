<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 * 
 * Esta Clase es para consultar los Años y meses reportados en la BD para llenar los combos
 * de la pagina reporte
 */

class Md_reporte extends CI_Model
{
    public $anios,$meses;
    
    public function __construct($administrador = 0)
    {
        $this->db->select('mes, anio');
        $this->db->distinct();
        $this->db->order_by('mes');
        if($administrador)
            $consulta = $this->db->get('vw_evaluacion_juris');
        else
            $consulta = $this->db->get('vw_reportejuris');
        
        $anio_inicial = 0;
        foreach($consulta->result() as $ren)
        {
            if($ren->anio != $anio_inicial)
                $this->anios[$ren->anio] = $ren->anio;
            
            switch($ren->mes)
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
            
            $this->meses[$ren->mes] = $mes_txt;
            $anio_inicial = $ren->anio;
        } 
    }
    function regresar_datos()
    {
        $datos = array(
                        'meses' => $this->meses,
                        'anios' => $this->anios
                        );
        return $datos;
    }
    
    /**
     * Reporte del Administrador de HC
     * @param str mes
     * @param int anio
     * @param str um
     * @return Array
     */
    function administradorHC($mes, $anio, $hc)
    {
        //CONSULTAR FECHA DE LA INFORMACION
        $this->load->model('md_informacion');
        $mesInformacion = $this->md_informacion->mesActual();

        $datos['mes'] = $mes;
        $datos['anio'] = $anio;
        $datos['hc'] = $hc;
        $datos['reporte'] = "hc";
        
        $this->load->model('md_indicador');
        $indicadorNuevo = new MD_indicador(1);
        
        $unidades_medida = array();
        for($i = 1; $i<= 82;$i++)
            $unidades_medida[] = $i;
        $this->load->helpers(array('form','html','url'));
        $this->load->library('table'); 
        
        foreach($unidades_medida as $row2)
        {
            $sumar = array();
            //echo $row2."<br />";            
            $uni_med = $indicadorNuevo->uni_med_calc_hc($row2);
            //$juris = $indicadorNuevo->um_juris($juris);
            //print_r($uni_med);
            foreach($uni_med as $ren)
            {
                $indicador = $ren['indicador'];
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
                    $sumar[$ren] = $indicadorNuevo->valor_ind_hc($ren,$mes,$anio,$hc,'H.C.',$acumular,$operacion);
                else
                    $sumar[$ren] = $indicadorNuevo->valor_ind_hc_resp($ren,$mes,$anio,$hc);
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
                case 'todas': $meta = $calera + $trancoso + $ojocaliente + $sombrerete +
                                        $valparaiso + $juan_aldama + $jalpa + $juchipila +
                                        $tabasco + $nochistlan + $villa_de_cos;
                                break;
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
            
            $celda_total = array('data' => '<a href="'.site_url('/principal/metadatos').'/'.$row2.'/hc/'.$mes.'/'.$anio.'/'.$hc.'/1" target="_blank">'.number_format($total).'</a>', 'align' => 'right' );
                                                                    
            $meta_parcial_celda = array('data' => number_format($valores['meta_parcial'],0), 'align'=>'right');
            $meta_logro_parcial_celda = array('data' => number_format($valores['porcentaje_logro_parcial'],2), 'align'=>'right');
            $meta_celda = array('data' => number_format($meta), 'align'=>'right');
            $meta_logro_anual_celda = array('data' => number_format($porcentaje_logro,2), 'align'=>'right');
            
            $this->table->add_row( $h,$programa,$indicador,$descripcion,$celda_total,$meta_parcial_celda,$meta_logro_parcial_celda,$valores['celda'],$meta_celda,$meta_logro_anual_celda );
            $datos2[] = array('h' => $h,
                              'programa' => $programa,
                              'indicador' => $indicador,
                              'descripcion' => $descripcion,
                              'total' => number_format($total),
                              'meta_parcial' => number_format($valores['meta_parcial'],0),
                              's_parcial' => $valores['celda']['data'],
                              'meta_logro_p' => number_format($valores['porcentaje_logro_parcial'],2),
                              'meta' => number_format($meta),
                              's' => $celda['data'],
                              'meta_logro' => number_format($porcentaje_logro,2));
            //$this->table->add_row( $h,$programa,$indicador,$descripcion,number_format($meta),number_format($total),$celda,number_format($porcentaje_logro,2) );    
            //print_r($total);
            //echo "<br />";
        }
        
        $hc_txt = $indicadorNuevo->um_txt($hc);

        $this->load->model('md_fechas');
        $mes_txt = $this->md_fechas->convertir_mes_txt($mes);
        //$mes_txt = $this->convertir_mes_txt($mes);        
        
        if($hc_txt == "TODOS")
            $texto_titulo = "LOS H.C.";
        else
            $texto_titulo = "";        
        
        if($mes_txt != 'ENERO')
            $datos['titulo'] = "REPORTE".br().$hc_txt." ".$texto_titulo." ".$mes_txt;
        else
            $datos['titulo'] = "REPORTE".br().$hc_txt." ".$texto_titulo." <br />".$mes_txt;
        //$this->table->set_heading('H','PROGRAMA','DESCRIPCION','UNIDAD DE MEDIDA','META','LOGRO','S','% META-LOGRO');
        
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
        
        //$datos['titulo'] = "REPORTE".br().$titulo." ".$mes_txt;
        $this->table->set_heading('H','PROGRAMA','DESCRIPCION','UNIDAD DE MEDIDA','LOGRO',$celda4,$celda6,$celda5,$celda1,$celda3);
        
        $this->load->model('md_informacion');
        $datos['corte'] = $this->md_informacion->fecha_corte($anio);
        
        $this->load->view('vw_encabezado');
        $mnu['selec'] = 'reporte';
        //$this->load->view('vw_menu',$mnu);
        
        $this->load->model('Md_pagina');
        $datos['pagina'] = new Md_Pagina;
        
        if($mesInformacion == $mes)
            $datos['botonPublicar'] = '<input id="btn_publicar" type="button" class="btn btn-success" onclick="publicarHc('.$mes.','.$anio.')" value="PUBLICAR MES" />';
        else
            $datos['botonPublicar'] = '';
        
        return $datos;
        //$this->load->view("vw_reporta_evaluacion",$datos);
        //$this->load->view('vw_footer');
        
        //$this->table->set_heading('DESCRIPCION','UNIDAD DE MEDIDA','VALOR');
        //echo $this->table->generate();
    }
    
    /**
     * Reporte del Administrador de Segundo Nivel
     * @param str mes
     * @param int anio
     * @param str um
     * @return Array
     */
    function Administrador2n($mes,$anio,$hc)
    {
        //$this->output->enable_profiler();
        //CONSULTAR FECHA DE LA INFORMACION
        $this->load->model('md_informacion');
        $mesInformacion = $this->md_informacion->mesActual();
        
        //GUARDA LOS VALORES PARA PASARLOS POSTERIORMENTE A LA VISTA
        $datos['mes'] = $mes;
        $datos['anio'] = $anio;
        $datos['hc'] = $hc;
        $datos['reporte'] = "2n";
        
        //CARGA EL MODELO
        $this->load->model('md_indicador');
        $indicador = new MD_indicador(1);
        
        //GUARDA LAS UNIDADES DE MEDIDA QUE SE VAN A CALCULAR
        $unidades_medida = array();
        for($i = 1; $i<= 34;$i++)
            if($i != 26 && $i != 24 && $i != 10 && $i != 18 && $i != 19)
                $unidades_medida[] = $i;
       
        
        //CARGAR LIBRERIAS DE CI
        $this->load->helpers(array('form','html','url'));
        $this->load->library('table'); 
        
        //CICLO PARA CADA UNA DE LAS UNIDADES DE MEDIDA
        foreach($unidades_medida as $row2)
        {
            $sumar = array();
            //echo $row2."<br />";
            
            //TRAE LOS DATOS DE LA UNIDAD DE MEDIDA       
            $uni_med = $indicador->uni_med_calc_2n($row2);
            //$juris = $indicador->um_juris($juris);
            //print_r($uni_med);
            
            //CICLO PARA LA UNIDAD DE MEDIDA
            foreach($uni_med as $ren)
            {
                //$indicador = $ren['indicador'];
                //GUARDA LOS DATOS EN VARIABLES
                $descripcion = $ren['descripcion'];
                $h = $ren['h'];
                $programa = $ren['programa'];
                //VAR GUARDA LAS VARIABES QUE SE VAN A SUMAR DE LAS FUENTES DE INFORMACION
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
            }
            //print_r($var);
            //echo br();
            //CICLO PARA LAS VARIABLES
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
            
            $celda_total = array('data' => '<a href="'.site_url('/principal/metadatos').'/'.$row2.'/2n/'.$mes.'/'.$anio.'/'.$hc.'/1" target="_blank">'.number_format($total).'</a>', 'align' => 'right' );
                                                                    
            $meta_parcial_celda = array('data' => number_format($valores['meta_parcial'],0), 'align'=>'right');
            $meta_logro_parcial_celda = array('data' => number_format($valores['porcentaje_logro_parcial'],2), 'align'=>'right');
            $meta_celda = array('data' => number_format($meta), 'align'=>'right');
            $meta_logro_anual_celda = array('data' => number_format($porcentaje_logro,2), 'align'=>'right');
            
            $this->table->add_row( $h,$programa,$descripcion,$celda_total,$meta_parcial_celda,$meta_logro_parcial_celda,$valores['celda'],$meta_celda,$meta_logro_anual_celda );
            $datos2[] = array('h' => $h,
                              'programa' => $programa,
                              'descripcion' => $descripcion,
                              'total' => number_format($total),
                              'meta_parcial' => number_format($valores['meta_parcial'],0),
                              's_parcial' => $valores['celda']['data'],
                              'meta_logro_p' => number_format($valores['porcentaje_logro_parcial'],2),
                              'meta' => number_format($meta),
                              's' => $celda['data'],
                              'meta_logro' => number_format($porcentaje_logro,2));
            //$this->table->add_row( $h,$programa,$descripcion,number_format($meta),number_format($total),$celda,number_format($porcentaje_logro,2) );    
            //print_r($total);
            //echo "<br />";
        }
        
        $hc_txt = $indicador->um_txt($hc);
        
        $this->load->model('md_fechas');
        $mes_txt = $this->md_fechas->convertir_mes_txt($mes);
        //$mes_txt = $this->convertir_mes_txt($mes);
        
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
        
        //$datos['titulo'] = "REPORTE".br().$titulo." ".$mes_txt;
        $this->table->set_heading('H','PROGRAMA','UNIDAD DE MEDIDA','LOGRO',$celda4,$celda6,$celda5,$celda1,$celda3);
        
        if($hc_txt == "TODOS")
            $texto_titulo = "LOS H.G., H.C. y H. ESPECIALIDAD";
        else
            $texto_titulo = "";
        
        if($mes_txt != 'ENERO')
            $datos['titulo'] = "REPORTE".br().$hc_txt." ".$texto_titulo." <br />ENERO - ".$mes_txt;
        else
            $datos['titulo'] = "REPORTE".br().$hc_txt." ".$texto_titulo." <br />".$mes_txt;
        //$this->table->set_heading('H','PROGRAMA','UNIDAD DE MEDIDA','META','LOGRO','S','% META-LOGRO');
        
        if($mesInformacion == $mes)
            $datos['botonPublicar'] = '<input id="btn_publicar" type="button" class="btn btn-success" onclick="publicar2Nivel('.$mes.','.$anio.')" value="PUBLICAR MES" />';
        else
            $datos['botonPublicar'] = '';
        
        $this->load->model('md_informacion');
        $datos['corte'] = $this->md_informacion->fecha_corte($anio);                
        $this->load->view('vw_encabezado');
        $mnu['selec'] = 'reporte';
        //$this->load->view('vw_menu',$mnu);
        $this->load->model('Md_pagina');
        $datos['pagina'] = new Md_Pagina;
        
        return $datos;
        //$this->load->view("vw_reporta_evaluacion",$datos);
        //$this->load->view('vw_footer');
        
        //$this->table->set_heading('DESCRIPCION','UNIDAD DE MEDIDA','VALOR');
        //echo $this->table->generate();
        
    }
    
    /**
     * Reporte del Administrador de Jurisdiccion
     * @param str mes
     * @param int anio
     * @param str um
     * @return Array
     */
    function administradorJuris($mes,$anio,$juris)
    {
        //CONSULTAR FECHA DE LA INFORMACION
        $this->load->model('md_informacion');
        $mesInformacion = $this->md_informacion->mesActual();
        //echo $mesInformacion;
        
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
        $indicadorNuevo = new MD_indicador(1);
              
        $unidades_medida = array();
        
        //ID DE LOS INDICADORES QUE SE VAN A CALCULAR (OBSOLETO)
        //for($i = 1; $i<= 177;$i++)
//        {
//            if($i != 8 && $i != 9 && $i != 10 && $i != 7 && $i != 6 && $i != 20 && $i != 21 && $i != 22)
//                $unidades_medida[] = $i;
//        }
        
        //ID DE LOS INDICADORES QUE SE VAN A CALCULAR
        //$this->db->where('id','177');
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
            $uni_med = $indicadorNuevo->uni_med_calc($row2);
            
            //$juris = $indicadorNuevo->um_juris($juris);
            //print_r($uni_med);
            
            //GUARDA LAS METAS, NOMBRES DE VARIABLES PARA EL CALCULO, ETC.
            foreach($uni_med as $ren)
            {
                $indicador = $ren['indicador'];
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
                    $sumar[$ren] = $indicadorNuevo->valor_ind($ren,$mes,$anio,$juris,'soloJuris',$acumular,$operacion,$qhc);
                //SI ES DE UN RESPONSABLE DE PROGRAMA
                else
                    $sumar[$ren] = $indicadorNuevo->valor_ind_jur($ren,$mes,$anio,$juris);
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
                $semaforo = "azul";
            else            
                if($porcentaje_logro >= 80)
                    $semaforo = "verde";
                else
                    if( $porcentaje_logro >= 60)
                        $semaforo = "amarillo";
                    else
                        if($porcentaje_logro < 60)
                            $semaforo = "rojo";
            
            //AGREGA EL NOMBRE DE LA IMAGEN PARA SEMAFORO
            $celda = array('data' => '<div class="'.$semaforo.'"></div>',
                            'align' => 'center');
            /*
            $meta_parcial = $meta / 12;
            $meta_parcial = $meta_parcial * $mes;
            
            if($meta_parcial != 0)
                $porcentaje_logro_parcial = ($total * 100) / $meta_parcial;
            else
                $porcentaje_logro_parcial = 0;
            
            if($porcentaje_logro_parcial >= 120)
                $semaforo_parcial = "azul.jpg";
            else            
                if($porcentaje_logro_parcial >= 80)
                    $semaforo_parcial = "verde.jpg";
                else
                    if( $porcentaje_logro_parcial >= 60)
                        $semaforo_parcial = "amarillo.jpg";
                    else
                        if($porcentaje_logro_parcial < 60)
                            $semaforo_parcial = "rojo.jpg";
            
            $celda_parcial = array('data' => img('/img/'.$semaforo_parcial),
                            'align' => 'center');*/
            $valores = $this->meta_parcial($meta,$total,$mes,$acumular);
            $celda_total = array('data' => '<a href="'.site_url('/principal/metadatos').'/'.$row2.'/juris'.'/'.$mes.'/'.$anio.'/'.$juris.'/1" target="_blank">'.number_format($total).'</a>', 'align' => 'right' );                                                        
            $meta_parcial_celda = array('data' => number_format($valores['meta_parcial'],0), 'align'=>'right');
            $meta_logro_parcial_celda = array('data' => number_format($valores['porcentaje_logro_parcial'],2), 'align'=>'right');
            $meta_celda = array('data' => number_format($meta), 'align'=>'right');
            $meta_logro_anual_celda = array('data' => number_format($porcentaje_logro,2), 'align'=>'right');
            
            //PARA LOS TITULOS DEL DEPARTAMENTO
            if($titulosTabla[$unidadMedidaDepartamento[$row2]] != ""){
                $estiloTituloDepartamento = array('data' => $titulosTabla[$unidadMedidaDepartamento[$row2]],
                                                    'bgcolor' => 'lightgreen',
                                                    'colspan' => '3');
                $this->table->add_row($estiloTituloDepartamento);
                $titulosTabla[$unidadMedidaDepartamento[$row2]] = "";
            }
            ///////////////////////////////////
            
            $this->table->add_row( $h,$programa,$indicador,$descripcion,$celda_total,$meta_parcial_celda,$meta_logro_parcial_celda,$valores['celda'],$meta_celda,$meta_logro_anual_celda );    
            $datos2[] = array('h' => $h,
                              'programa' => $programa,
                              'indicador' => $indicador,
                              'descripcion' => $descripcion,
                              'total' => number_format($total),
                              'meta_parcial' => number_format($valores['meta_parcial'],0),
                              's_parcial' => $valores['celda']['data'],
                              'meta_logro_p' => number_format($valores['porcentaje_logro_parcial'],2),
                              'meta' => number_format($meta),
                              's' => $celda['data'],
                              'meta_logro' => number_format($porcentaje_logro,2));
            //print_r($total);
            //echo "<br />";
        }
        
        switch ($juris)
        {
            case "01": $titulo = "ZACATECAS";
                       break;
            case "02": $titulo = "OJOCALIENTE";
                       break;
            case "03": $titulo = "FRESNILLO";
                       break;
            case "04": $titulo = "RIO GRANDE";
                       break;
            case "05": $titulo = "JALPA";
                       break;
            case "06": $titulo = "TLALTENANGO";
                       break;
            case "07": $titulo = "CONCEPCION DEL ORO";
                       break;
            default : $titulo = "ESTATAL";
                        break;
        }
        
        $this->load->model('md_fechas');
        $mes_txt = $this->md_fechas->convertir_mes_txt($mes);
        //$mes_txt = $this->convertir_mes_txt($mes);
        
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
        
        if($mes_txt != 'ENERO')
            $datos['titulo'] = "REPORTE".br().$titulo." ENERO - ".$mes_txt."<br /><h3>(Excluye Resultados de las Acciones de Hospitales Comunitarios)</h3>";
        else
            $datos['titulo'] = "REPORTE".br().$titulo." ".$mes_txt."<br /><h3>(Excluye Resultados de las Acciones de Hospitales Comunitarios)</h3>";
            
        $this->table->set_heading('H','PROGRAMA','DESCRIPCION','UNIDAD DE MEDIDA','LOGRO',$celda4,$celda6,$celda5,$celda1,$celda3);
        
        $this->load->model('md_informacion');
        
        $datos['corte'] = $mes_txt;
                
        $this->load->view('vw_encabezado');
        $mnu['selec'] = 'reporte';
        //$this->load->view('vw_menu',$mnu);
        
        $this->load->model('Md_pagina');
        $datos['pagina'] = new Md_Pagina;
        
        //PARA DESACTIVAR O ACTIVAR BOTON DE PUBLICAR
        if($mesInformacion == $mes)
            $datos['botonPublicar'] = '<input id="btn_publicar" type="button" class="btn btn-success" onclick="publicarJuris('.$mes.','.$anio.')" value="PUBLICAR MES" />';
        else
            $datos['botonPublicar'] = '';
        
        return $datos;
        //$this->load->view("vw_reporta_evaluacion",$datos);
        //$this->load->view('vw_footer');
    }
    
    /**
     * Reporte del Administrador de Jurisdiccion mas Hospitales Comunitarios
     * @param str mes
     * @param int anio
     * @param str um
     * @return Array
     */
    function administradorJurisHC($mes,$anio,$juris)
    {
        //CONSULTAR FECHA DE LA INFORMACION
        $this->load->model('md_informacion');
        $mesInformacion = $this->md_informacion->mesActual();
        
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
        $indicadorNuevo = new MD_indicador(1);
               
        $unidades_medida = array();
        
        //ID DE LOS INDICADORES QUE SE VAN A CALCULAR (OBSOLETO)
        //for($i = 1; $i<= 177;$i++)
//        {
//            if($i != 8 && $i != 9 && $i != 10 && $i != 7 && $i != 6 && $i != 20 && $i != 21 && $i != 22)
//                $unidades_medida[] = $i;
//        }
        
        //ID DE LOS INDICADORES QUE SE VAN A CALCULAR
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
            $uni_med = $indicadorNuevo->uni_med_calc($row2);
            
            //$juris = $indicadorNuevo->um_juris($juris);
            //print_r($uni_med);
            
            //GUARDA LAS METAS, NOMBRES DE VARIABLES PARA EL CALCULO, ETC.
            foreach($uni_med as $ren)
            {
                $indicador = $ren['indicador'];
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
               // $qhc = $ren['qhc'];
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
                    $sumar[$ren] = $indicadorNuevo->valor_ind($ren,$mes,$anio,$juris,'todas',$acumular,$operacion);
                //SI ES DE UN RESPONSABLE DE PROGRAMA
                else
                    $sumar[$ren] = $indicadorNuevo->valor_ind_jur($ren,$mes,$anio,$juris);
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
            $celda_total = array('data' => '<a href="'.site_url('/principal/metadatos').'/'.$row2.'/jurishc'.'/'.$mes.'/'.$anio.'/'.$juris.'/1" target="_blank">'.number_format($total).'</a>', 'align' => 'right' );                                                        
            $meta_parcial_celda = array('data' => number_format($valores['meta_parcial'],0), 'align'=>'right');
            $meta_logro_parcial_celda = array('data' => number_format($valores['porcentaje_logro_parcial'],2), 'align'=>'right');
            $meta_celda = array('data' => number_format($meta), 'align'=>'right');
            $meta_logro_anual_celda = array('data' => number_format($porcentaje_logro,2), 'align'=>'right');
            
            //PARA LOS TITULOS DEL DEPARTAMENTO
            if($titulosTabla[$unidadMedidaDepartamento[$row2]] != ""){
                $estiloTituloDepartamento = array('data' => $titulosTabla[$unidadMedidaDepartamento[$row2]],
                                                    'bgcolor' => 'lightgreen',
                                                    'colspan' => '3');
                $this->table->add_row($estiloTituloDepartamento);
                $titulosTabla[$unidadMedidaDepartamento[$row2]] = "";
            }
            ///////////////////////////////////
            
            $this->table->add_row( $h,$programa,$indicador,$descripcion,$celda_total,$meta_parcial_celda,$meta_logro_parcial_celda,$valores['celda'],$meta_celda,$meta_logro_anual_celda );    
            $datos2[] = array('h' => $h,
                              'programa' => $programa,
                              'indicador' => $indicador,
                              'descripcion' => $descripcion,
                              'total' => number_format($total),
                              'meta_parcial' => number_format($valores['meta_parcial'],0),
                              's_parcial' => $valores['celda']['data'],
                              'meta_logro_p' => number_format($valores['porcentaje_logro_parcial'],2),
                              'meta' => number_format($meta),
                              's' => $celda['data'],
                              'meta_logro' => number_format($porcentaje_logro,2));
            //print_r($total);
            //echo "<br />";
        }
        
        switch ($juris)
        {
            case "01": $titulo = "ZACATECAS";
                       break;
            case "02": $titulo = "OJOCALIENTE";
                       break;
            case "03": $titulo = "FRESNILLO";
                       break;
            case "04": $titulo = "RIO GRANDE";
                       break;
            case "05": $titulo = "JALPA";
                       break;
            case "06": $titulo = "TLALTENANGO";
                       break;
            case "07": $titulo = "CONCEPCION DEL ORO";
                       break;
            default : $titulo = "ESTATAL";
                        break;
        }
        
        $this->load->model('md_fechas');
        $mes_txt = $this->md_fechas->convertir_mes_txt($mes);
        //$mes_txt = $this->convertir_mes_txt($mes);
        
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
        
        if($mes_txt != 'ENERO')
            $datos['titulo'] = "REPORTE".br().$titulo." ENERO - ".$mes_txt."<br /><h3>(Incluye Resultados de las Acciones de Hospitales Comunitarios)</h3>";
        else
            $datos['titulo'] = "REPORTE".br().$titulo." ".$mes_txt."<br /><h3>(Incluye Resultados de las Acciones de Hospitales Comunitarios)</h3>";
            
        $this->table->set_heading('H','PROGRAMA','DESCRIPCION','UNIDAD DE MEDIDA','LOGRO',$celda4,$celda6,$celda5,$celda1,$celda3);
        
        $this->load->model('md_informacion');
        
        $datos['corte'] = $this->md_informacion->fecha_corte($anio);
        
        if($mesInformacion == $mes)
            $datos['botonPublicar'] = '<input id="btn_publicar" type="button" class="btn btn-success" onclick="publicarJurisHc('.$mes.','.$anio.')" value="PUBLICAR MES" />';
        else
            $datos['botonPublicar'] = '';
                
        $this->load->view('vw_encabezado');
        $mnu['selec'] = 'reporte';
        //$this->load->view('vw_menu',$mnu);
        
        $this->load->model('Md_pagina');
        $datos['pagina'] = new Md_Pagina;
        
        return $datos;
        //$this->load->view("vw_reporta_evaluacion",$datos);
        //$this->load->view('vw_footer');
    }
    
    function meta_parcial($meta,$total,$mes,$acumular)
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
            $semaforo_parcial = '<div class="azul"></div>';
        else            
            if($porcentaje_logro_parcial >= 80)
                $semaforo_parcial = '<div class="verde"></div>';
            else
                if( $porcentaje_logro_parcial >= 60)
                    $semaforo_parcial = '<div class="amarillo"></div>';
                else
                    if($porcentaje_logro_parcial < 60)
                        $semaforo_parcial = '<div class="rojo"></div>';
        
        if($meta == 0)
            $semaforo_parcial = '<div class="blanco"></div>';
        
        $res['meta_parcial'] = $meta_parcial;
        $res['porcentaje_logro_parcial'] = $porcentaje_logro_parcial;
        $res['semaforo_parcial'] = $semaforo_parcial;        
        $res['celda'] = array('data' => $semaforo_parcial,
                        'align' => 'center');                                                
        
        return $res;
    }
}
?>