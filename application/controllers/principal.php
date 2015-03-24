<?php

class Principal extends CI_Controller 
{
    function portada()
    {
        $this->load->view('vw_portada');
    }
    function index_2()
	{
    	$this->load->helpers(array('form','html','url'));
        $this->load->library('ion_auth');
        $this->load->library('table');
        $this->load->view('vw_encabezado');
        $this->load->view('vw_login');
        $this->load->view('vw_footer');
	}
    function metadatos($id,$reporte,$mes,$anio,$juris=0,$publicar=0)
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            //$this->output->enable_profiler();
            //echo "METADATOS PUBLICAR: ".$publicar."<br />";
            $this->load->model('Md_informacion');
                            
            $this->load->library('table');
            $this->load->helpers(array('html'));
            $datos = array();
            $tmpl = array (
                        'table_open'          => '<table border="1" cellpadding="4" cellspacing="0" align="center">',
    
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
    
            $this->table->set_template($tmpl);
            //$this->load->model('md_indicador');
            
            $datos['reporte'] = $reporte;
            
            $datos['mes'] = $mes;
            
            $this->load->model('Md_grafica');
            
            //CREA GRAFICA
            $grafica = new Md_grafica();
            
            //CONSULTA HOSPITALES
            switch($reporte)
            {
                case '2n': $this->db->where_in('tipologia',array('H.C.','H.G.','H.E.'));
                            break;
                case 'hc': $this->db->where_in('tipologia','H.C.');
                            break;
                default: break;
            }
            
            if($reporte != 'juris' && $reporte != 'jurishc')
                $hosp_txt[] = "HC";
            else
            {
                $hosp_txt[] = "JURIS";
                $hosp_txt[] = "J1";
                $hosp_txt[] = "J2";
                $hosp_txt[] = "J3";
                $hosp_txt[] = "J4";
                $hosp_txt[] = "J5";
                $hosp_txt[] = "J6";
                $hosp_txt[] = "J7";
            }
            
            //PARA DECIDIR DONDE CONSULTAR LOS MESES
            if($publicar)
                $mesActual = (int) $this->Md_informacion->mesActual();
            
            else
                $mesActual = (int) $this->Md_informacion->mesActualPublicado($reporte);
    
            for($i=1;$i<=$mesActual;$i++)
            {
                switch($i)
                {
                    case 1: $meses[] = array('valor' => '01', 'nombre' => 'ENE');
                                        break;
                    case 2: $meses[] = array('valor' => '02', 'nombre' => 'FEB');
                                        break;
                    case 3: $meses[] = array('valor' => '03', 'nombre' => 'MAR');
                                        break;
                    case 4: $meses[] = array('valor' => '04', 'nombre' => 'ABR');
                                        break;
                    case 5: $meses[] = array('valor' => '05', 'nombre' => 'MAY');
                                        break;
                    case 6: $meses[] = array('valor' => '06', 'nombre' => 'JUN');
                                        break;
                    case 7: $meses[] = array('valor' => '07', 'nombre' => 'JUL');
                                        break;
                    case 8: $meses[] = array('valor' => '08', 'nombre' => 'AGO');
                                        break;
                    case 9: $meses[] = array('valor' => '09', 'nombre' => 'SEP');
                                        break;
                    case 10: $meses[] = array('valor' => '10', 'nombre' => 'OCT');
                                        break;
                    case 11: $meses[] = array('valor' => '11', 'nombre' => 'NOV');
                                        break;
                    case 12: $meses[] = array('valor' => '12', 'nombre' => 'DIC');
                                        break;
                }
            }
            
            //CUANDO EL REPORTE QUE SE PIDE NO ES EL DE LAS JURISDICCIONES
            if($reporte != 'juris' && $reporte != 'jurishc')
            {
                if($juris == 'todas'){
                    $consulta = $this->db->get('um');  
                    foreach($consulta->result_array() as $row)
                    {
                        $hospitales[] = array('clues' => $row['clues'],
                                                'nombre' => $row['alias']);
                        $hosp_txt[] = $row['alias'];
                    }
                }
                else
                {
                    $hospitales[0] = array('clues' => $juris,
                                                'nombre' => $juris);
                /*    $meses[0] = array('valor' => '01', 'nombre' => 'ENE');
                    $meses[1] = array('valor' => '02', 'nombre' => 'FEB');
                    $meses[2] = array('valor' => '03', 'nombre' => 'MAR');
                    $meses[3] = array('valor' => '04', 'nombre' => 'ABR');
                    $meses[4] = array('valor' => '05', 'nombre' => 'MAY');
                    $meses[5] = array('valor' => '06', 'nombre' => 'JUN');
                    $meses[6] = array('valor' => '07', 'nombre' => 'JUL');
                    $meses[7] = array('valor' => '08', 'nombre' => 'AGO');*/
                }
            }
            //CUANDO EL REPORTE QUE SE PIDE ES EL DE LAS JURISDICCIONES
            else
            {
                if($juris == 'todas')
                {
                    $hospitales[0] = array('clues' => '01',
                                                'nombre' => 'J1');
                    $hospitales[1] = array('clues' => '02',
                                                'nombre' => 'J2');
                    $hospitales[2] = array('clues' => '03',
                                                'nombre' => 'J3');
                    $hospitales[3] = array('clues' => '04',
                                                'nombre' => 'J4');
                    $hospitales[4] = array('clues' => '05',
                                                'nombre' => 'J5');
                    $hospitales[5] = array('clues' => '06',
                                                'nombre' => 'J6');
                    $hospitales[6] = array('clues' => '07',
                                                'nombre' => 'J7');
                }
                else
                {
                    $hospitales[0] = array('clues' => $juris,
                                                'nombre' => $juris);
                  /*  $meses[0] = array('valor' => '01', 'nombre' => 'ENE');
                    $meses[1] = array('valor' => '02', 'nombre' => 'FEB');
                    $meses[2] = array('valor' => '03', 'nombre' => 'MAR');
                    $meses[3] = array('valor' => '04', 'nombre' => 'ABR');
                    $meses[4] = array('valor' => '05', 'nombre' => 'MAY');
                    $meses[5] = array('valor' => '06', 'nombre' => 'JUN');
                    $meses[6] = array('valor' => '07', 'nombre' => 'JUL');
                    $meses[7] = array('valor' => '08', 'nombre' => 'AGO');*/
                }
            }
            //print_r($hospitales);        
                    
            $this->load->model('md_indicador_hospitalario');        
            $ind_2do_nivel = new Md_indicador_hospitalario($id,$reporte,$publicar);
            //echo $ind_2do_nivel->juan_aldama;
            
            $multiplicador = (int) $mes;
            $meta_tabla[] = "<strong>META ANUAL</strong>";
            $meta_tabla_acu[] = "<strong>META MES</strong>";
            
            foreach($hospitales as $ren)
            {
                //echo $ren['nombre']." = ".$ren['clues'];
                //echo $ind_2do_nivel->reporta_mes($mes,$anio,$ren['clues']);
                // echo "<br />";
                $meta_tabla[] = $ind_2do_nivel->meta($ren['clues']);
                $meta_tabla_acu[] = number_format($ind_2do_nivel->meta($ren['clues']) / 12 * $multiplicador ,2);
                
                //echo $ind_2do_nivel->qhc;
                if($juris != 'todas')
                {
                   // echo "acumular: ".$ind_2do_nivel->acumular;
                    foreach($meses as $mes_sel)
                    {
                        //echo $ind_2do_nivel->meta($ren['clues']);
                        if($ind_2do_nivel->acumular == 'NO')
                            $grafica->agregar_dato($mes_sel['nombre'],$ind_2do_nivel->reporta_mes($mes_sel['valor'],$anio,$ren['clues'],$reporte,$ind_2do_nivel->acumular),$ind_2do_nivel->meta($ren['clues']));
                        else
                            $grafica->agregar_dato($mes_sel['nombre'],$ind_2do_nivel->reporta_mes($mes_sel['valor'],$anio,$ren['clues'],$reporte,1),($ind_2do_nivel->meta($ren['clues']) / 12) * (int) $mes_sel['valor']);
                    }
                }
                else
                {
                    //echo "acumular: ".$ind_2do_nivel->acumular;
                    if($ind_2do_nivel->acumular == 'NO')
                        $grafica->agregar_dato($ren['nombre'],$ind_2do_nivel->reporta_mes($mes,$anio,$ren['clues'],$reporte,$ind_2do_nivel->acumular,$ind_2do_nivel->qhc),$ind_2do_nivel->meta($ren['clues']));
                    else
                        $grafica->agregar_dato($ren['nombre'],$ind_2do_nivel->reporta_mes($mes,$anio,$ren['clues'],$reporte,1,$ind_2do_nivel->qhc),$ind_2do_nivel->meta($ren['clues']) / 12 * $multiplicador);
                
                }                
            }
            
            $datos['titulo_rep'] = $ind_2do_nivel->programa;
            $datos['titulo'] = $ind_2do_nivel->descripcion;        
            $datos['categorias'] = $grafica->categorias;
            $datos['logros'] = $grafica->logros;
            $datos['metas'] = $grafica->metas;
            $datos['indicador'] = $ind_2do_nivel;
            //print_r($datos);
            
            //TABLA INDICADOR
            $this->table->add_row('<strong>APARTADO SIS</strong>',$ind_2do_nivel->apartadoSIS);
            $this->table->add_row( '<strong>CALCULO</strong>',$ind_2do_nivel->calculo);
            if($ind_2do_nivel->acumular == 'NO')
                $this->table->add_row( '<strong>ACUMULAR</strong>',$ind_2do_nivel->acumular);
            else
                $this->table->add_row( '<strong>ACUMULAR</strong>','SI');
            $this->table->add_row( '<strong>UNIDAD MEDIDA</strong>',$ind_2do_nivel->descripcion);
            $datos['tabla_ind'] = $this->table->generate();
            
            //TABLA METAS
            $this->table->set_heading($hosp_txt);        
            if($ind_2do_nivel->acumular != 'NO')
                $this->table->add_row($meta_tabla_acu);
            $this->table->add_row($meta_tabla);
            $datos['tabla_metas'] = $this->table->generate();
            
            $this->load->view('vw_encabezado');        
            $this->load->view('vw_metadatos',$datos);
        }
        
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
    function reporte()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
            {
            //$this->output->enable_profiler();
            $this->load->model("md_reporte");
            $datos['meses_jur'] = $this->md_reporte->regresar_datos();
            
            $datos['administrador'] = 0;
            
            $this->load->library('table');
            $this->load->helpers(array('form'));
            $this->load->model("md_indicador");
            
            //OBSOLETO, PORQUE QUEREMOS OTRO ORDEN
            //$datos['hc'] = $this->md_indicador->um('H.C.');
            //$datos['hghehc'] = $this->md_indicador->um(array('H.C.','H.G.','H.E.'));
            //$datos['hghehc']['ZSSSA013172'] = 'URGENCIAS';
            
            //print_r($datos['hghehc']);
            //$mnu['selec'] = 'reporte';
            //$this->load->view('vw_encabezado');
            //$this->load->view('vw_menu',$mnu);
            $this->load->model('md_pagina');
            $datos['pagina'] = new Md_Pagina;
            $this->load->view('vw_formulario_reportes',$datos);
            //$this->load->view('vw_footer');
        }
    }
    function reporte_administrador()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            $this->load->model("md_reporte");
            $reporte = new Md_reporte(1);
            $datos['meses_jur'] = $reporte->regresar_datos();
            
            $datos['administrador'] = 1;
            $datos['reporteAdmin'] = 1;
            
            $this->load->library('table');
            $this->load->helpers(array('form'));
            $this->load->model("md_indicador");
            $datos['hc'] = $this->md_indicador->um('H.C.');
            $datos['hghehc'] = $this->md_indicador->um(array('H.C.','H.G.','H.E.'));
            $datos['hghehc']['ZSSSA013172'] = 'URGENCIAS';
            //print_r($datos['hghehc']);
            //$mnu['selec'] = 'reporte';
            //$this->load->view('vw_encabezado');
            //$this->load->view('vw_menu',$mnu);
            $this->load->model('md_pagina');
            $datos['pagina'] = new Md_Pagina;
            $this->load->view('vw_formulario_reportes',$datos);
            //$this->load->view('vw_footer');
        }
    }
    function index()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            date_default_timezone_set('America/Monterrey');
            $this->load->model('md_usuarios');
    
            
            $user = $this->ion_auth->user()->row();
    		//print_r($user->id) ;
            
            $sess_array = array();
            $tiempo = date("d-m-Y g:i a");
            $sess_array = array(
                'id' => $user -> id,
                'username' => strtoupper($user -> username) ,
                'fecha' => $tiempo,
                'accion' => 'inicio sesion',
                //'ip' => $user -> ip_address
                'ip' => $this->session->userdata('ip_address')
            );
            //print_r($sess_array);
            $this->md_usuarios->bitacora($sess_array);
            
            $this -> session -> set_userdata($sess_array);
            $this->load->model('Md_pagina');
            $datos['pagina'] = new Md_Pagina;
                    
            $this->load->library('table');
            //$this->load->view('vw_encabezado');
            //$mnu['selec'] = 'principal';
            //$this->load->view('vw_menu',$mnu);      
            $this->load->view("vw_principal_nuevo2",$datos);
            //$this->load->view('vw_footer');
        }
    }
    function captura()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            //$this->output->enable_profiler();
            $this->load->model('md_usuarios');
            $this->load->model('md_indicador');
            $this->load->library(array('table'));
            //$this->load->helpers(array('html'));
            
            //CONSULTA LOS PROGRAMAS QUE PERDENECEN A LA SESION
            $datos['juris'] = $this->md_usuarios->responsable($this->session->userdata('username'),"juris");
            $datos['1er'] = $this->md_usuarios->responsable($this->session->userdata('username'),"1er");
            $datos['2do'] = $this->md_usuarios->responsable($this->session->userdata('username'),"2do");        
            
            //print_r($datos);
            
            //LLENA UN ARREGLO CON TODOS LOS PROGRAMAS Y SUS DATOS
            foreach ( $datos['juris'] as $ren )
                $datos['ind'][] = $this->md_indicador->eval_indicador($ren['id'],"programa",'juris');
            
            foreach ( $datos['1er'] as $ren )
                $datos['ind_1er'][] = $this->md_indicador->eval_indicador($ren['id'],"programa",'1er');
            
            foreach ( $datos['2do'] as $ren )
                $datos['ind_2do'][] = $this->md_indicador->eval_indicador($ren['id'],"programa",'2do');

            $this->load->model('Md_pagina');
            $datos['pagina'] = new Md_Pagina;
            $this->load->view("vw_principal",$datos);
            $this->load->view('vw_footer');
        }
    }
    function reporte_juris()
    {
        //$this->output->enable_profiler();
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
            {
            //CONSULTAR FECHA DE LA INFORMACION
            $this->load->model('md_informacion');
            $mesInformacion = $this->md_informacion->mesActual();
            //echo $mesInformacion;
            
            //TRAE LOS VALORES DE LAS VARIABLES
            $mes = $this->input->post('mes');
            $anio = $this->input->post('anio');
            $juris = $this->input->post('juris');
            
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
            
            $this->load->view("vw_reporta_evaluacion",$datos);
            //$this->load->view('vw_footer');
        }
    }
    function reporte_juris_hc()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
            {
            //CONSULTAR FECHA DE LA INFORMACION
            $this->load->model('md_informacion');
            $mesInformacion = $this->md_informacion->mesActual();
            
            //TRAE LOS VALORES DE LAS VARIABLES
            $mes = $this->input->post('mes');
            $anio = $this->input->post('anio');
            $juris = $this->input->post('juris');
            
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
            
            $this->load->view("vw_reporta_evaluacion",$datos);
            //$this->load->view('vw_footer');
        }
    }
    
    function reporte_hc()
    {
        //CONSULTAR FECHA DE LA INFORMACION
        $this->load->model('md_informacion');
        $mesInformacion = $this->md_informacion->mesActual();
        
       // $this->output->enable_profiler();
        $mes = $this->input->post('mes');
        $anio = $this->input->post('anio');
        $hc = $this->input->post('hc');
        
        $datos['mes'] = $mes;
        $datos['anio'] = $anio;
        $datos['hc'] = $hc;
        $datos['reporte'] = "hc";
        //print_r($hc);
        
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
        
        $this->load->view("vw_reporta_evaluacion",$datos);
        //$this->load->view('vw_footer');
        
        //$this->table->set_heading('DESCRIPCION','UNIDAD DE MEDIDA','VALOR');
        //echo $this->table->generate();
    }
    function reporte_2n()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
            {
            //$this->output->enable_profiler();
            //CONSULTAR FECHA DE LA INFORMACION
            $this->load->model('md_informacion');
            $mesInformacion = $this->md_informacion->mesActual();
            
            //$this->output->enable_profiler();
            //TRAE LAS VARIABLES
            $mes = $this->input->post('mes');
            $anio = $this->input->post('anio');
            $hc = $this->input->post('hghehc');
            
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
            $this->load->view("vw_reporta_evaluacion",$datos);
            //$this->load->view('vw_footer');
            
            //$this->table->set_heading('DESCRIPCION','UNIDAD DE MEDIDA','VALOR');
            //echo $this->table->generate();
        }
    }
    function exportar()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            //$this->output->enable_profiler(TRUE);
            //ini_set("memory_limit","300M");
            //$this->load->library('table');
            $this->load->helpers(array('html','url','file'));
            $this->load->model('md_exporta');
            $datos['exporta'] = $this->md_exporta->Exporta('juris');
            $datos['exporta_1er'] = $this->md_exporta->Exporta('1er');
            $datos['exporta_2do'] = $this->md_exporta->Exporta('2do');
            $this->load->view('vw_encabezado');
            $this->load->view('vw_exportar',$datos);
        }
    }
    function descarga()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            //ini_set("memory_limit","300M");
            $this->load->helpers(array('download'));
            $data = file_get_contents("C:\wamp\www\\evaluacion_indicadores\descargas\\reporte.csv"); // Read the file's contents
           // $data = file_get_contents("/opt/lampp/htdocs/poa/descargas/reporte_".$responsable.".csv"); // Read the file's contents
            $name = 'Reporte.csv';
            
            force_download($name, $data);
        }
    }
    function ind_hosp($id_um,$mes,$anio)
    {
        //$this->output->enable_profiler();
        $this->load->model('md_unidad_medica');
        $um = new Md_unidad_medica($id_um);
        
        $this->load->model('md_ind_hosp');
        $ind_hosp = new Md_ind_hosp($um->id,$mes,$anio);
        
        $datos['tipologia'] = $um->tipologia;
        
        if($id_um == 'HC')
                $id_um = "H.C.";
                elseif($id_um == 'HG')
                        $id_um = "H.G.";        
                
        if($id_um == 'H.C.' OR $id_um == 'H.G.')
        {
            $this->db->where('tipologia',$id_um);
            $consulta = $this->db->get('um');
            foreach($consulta->result() as $ren)
            {
                $ind_hosp->agregar($ren->id,$mes,$anio);
            }
        }
        
        if(! isset($ind_hosp->dias_pac_men))
            return FALSE;
        else
        {
        //echo "<BR />";
        //$ind_hosp->calcular(180,$mes,$anio);
        $this->load->library('table');
        
        $tmpl = array (
                    'table_open'          => '<table border="1" cellpadding="4" cellspacing="0">',

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

        $this->table->set_template($tmpl);
        
        $dias_cama_men = $um->camas_cirugia * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;            
            $dias_cama_acu = $mes_num * 30 * $um->camas_cirugia;
        }
        else
            $dias_cama_acu = $um->camas_cirugia * 365;
        
        if($um->camas_cirugia != 0)
        {
            $porc_ocup_men = $ind_hosp->dias_pac_men / $dias_cama_men * 100;
            $porc_ocup_acu = $ind_hosp->dias_pac_acu / $dias_cama_acu * 100;
            $ind_rot_men = $ind_hosp->egresos_men / $um->camas_cirugia;
            $ind_rot_acu = $ind_hosp->egresos_acu / $um->camas_cirugia;
        }
        else
        {
            $porc_ocup_men = 0;
            $porc_ocup_acu = 0;
            $ind_rot_men = 0;
            $ind_rot_acu = 0;
        }
        if($ind_hosp->egresos_men != 0)
        {
            $int_sust_men = ($dias_cama_men - $ind_hosp->dias_pac_men) / $ind_hosp->egresos_men;
            $int_sust_acu = ($dias_cama_acu - $ind_hosp->dias_pac_acu) / $ind_hosp->egresos_acu;
            $tasa_mort_bruta = $ind_hosp->defun_men / $ind_hosp->egresos_men * 1000;
            $tasa_mort_ajus = $ind_hosp->defun_48hrs_men / $ind_hosp->egresos_men * 1000;
            $dias_est_men = $ind_hosp->dias_pac_men / $ind_hosp->egresos_men;
            $dias_est_acu = $ind_hosp->dias_pac_acu / $ind_hosp->egresos_acu;
        }
        else
        {
            $int_sust_men = 0;
            $int_sust_acu = 0;
            $tasa_mort_bruta = 0;
            $tasa_mort_ajus = 0;
            $dias_est_men = 0;
            $dias_est_acu = 0;
        }
        //////////////////////////////////////////////////////////////////////////////////////
        $dias_cama_men = $um->camas_med_interna * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * 30 * $um->camas_med_interna;
            //echo $dias_cama_acu;
        }
        
        if($um->camas_med_interna != 0)
        {
            $med_int_porc_ocup_men = $ind_hosp->med_int_dias_pac_men / $dias_cama_men * 100;
            $med_int_porc_ocup_acu = $ind_hosp->med_int_dias_pac_acu / $dias_cama_acu * 100;
            $med_int_ind_rot_men = $ind_hosp->med_int_egresos_men / $um->camas_med_interna;
            $med_int_ind_rot_acu = $ind_hosp->med_int_egresos_acu / $um->camas_med_interna;
        }
        else
        {
            $med_int_porc_ocup_men = 0;
            $med_int_porc_ocup_acu = 0;
            $med_int_ind_rot_men = 0;
            $med_int_ind_rot_acu = 0;
        }    
                
        if($ind_hosp->med_int_egresos_men != 0)
        {
            $med_int_int_sust_men = ($dias_cama_men - $ind_hosp->med_int_dias_pac_men) / $ind_hosp->med_int_egresos_men;
            $med_int_int_sust_acu = ($dias_cama_acu - $ind_hosp->med_int_dias_pac_acu) / $ind_hosp->med_int_egresos_acu;
            $med_int_tasa_mort_bruta = $ind_hosp->med_int_defun_men / $ind_hosp->med_int_egresos_men * 1000;
            $med_int_tasa_mort_ajus = $ind_hosp->med_int_defun_48hrs_men / $ind_hosp->med_int_egresos_men * 1000;
            $med_int_dias_est_men = $ind_hosp->med_int_dias_pac_men / $ind_hosp->med_int_egresos_men;
            $med_int_dias_est_acu = $ind_hosp->med_int_dias_pac_acu / $ind_hosp->med_int_egresos_acu;
                    
        }        
        else        
        {
            $med_int_int_sust_men = 0;
            $med_int_int_sust_acu = 0;
            $med_int_tasa_mort_bruta = 0;
            $med_int_tasa_mort_ajus = 0;
            $med_int_dias_est_men = 0;
            $med_int_dias_est_acu = 0;
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $dias_cama_men = $um->camas_pediatria * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * 30 * $um->camas_pediatria;
            //echo $dias_cama_acu;
        }
        
        if($um->camas_pediatria != 0)
        {
            $pediatria_porc_ocup_men = $ind_hosp->pediatria_dias_pac_men / $dias_cama_men * 100;
            $pediatria_porc_ocup_acu = $ind_hosp->pediatria_dias_pac_acu / $dias_cama_acu * 100;
            $pediatria_ind_rot_men = $ind_hosp->pediatria_egresos_men / $um->camas_pediatria;
            $pediatria_ind_rot_acu = $ind_hosp->pediatria_egresos_acu / $um->camas_pediatria;
        }
        else
        {
            $pediatria_porc_ocup_men = 0;
            $pediatria_porc_ocup_acu = 0;
            $pediatria_ind_rot_men = 0;
            $pediatria_ind_rot_acu = 0;
        }
        if($ind_hosp->pediatria_egresos_men != 0)
        {
            $pediatria_int_sust_men = ($dias_cama_men - $ind_hosp->pediatria_dias_pac_men) / $ind_hosp->pediatria_egresos_men;
            $pediatria_int_sust_acu = ($dias_cama_acu - $ind_hosp->pediatria_dias_pac_acu) / $ind_hosp->pediatria_egresos_acu;
            $pediatria_tasa_mort_bruta = $ind_hosp->pediatria_defun_men / $ind_hosp->pediatria_egresos_men * 1000;
            $pediatria_tasa_mort_ajus = $ind_hosp->pediatria_defun_48hrs_men / $ind_hosp->pediatria_egresos_men * 1000;
            $pediatria_dias_est_men = $ind_hosp->pediatria_dias_pac_men / $ind_hosp->pediatria_egresos_men;
            $pediatria_dias_est_acu = $ind_hosp->pediatria_dias_pac_acu / $ind_hosp->pediatria_egresos_acu;
        }
        else
        {
            $pediatria_int_sust_men = 0;
            $pediatria_int_sust_acu = 0;
            $pediatria_tasa_mort_bruta = 0;
            $pediatria_tasa_mort_ajus = 0;
            $pediatria_dias_est_men = 0;
            $pediatria_dias_est_acu = 0;
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $dias_cama_men = $um->camas_ginecologia * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * 30 * $um->camas_ginecologia;
            //echo $dias_cama_acu;
        }
        else
            $dias_cama_acu = 365 * $um->camas_ginecologia;
        
        if($um->camas_ginecologia != 0)
        {
            $ginecologia_porc_ocup_men = $ind_hosp->ginecologia_dias_pac_men / $dias_cama_men * 100;
            $ginecologia_porc_ocup_acu = $ind_hosp->ginecologia_dias_pac_acu / $dias_cama_acu * 100;
            
            $ginecologia_ind_rot_men = $ind_hosp->ginecologia_egresos_men / $um->camas_ginecologia;
            $ginecologia_ind_rot_acu = $ind_hosp->ginecologia_egresos_acu / $um->camas_ginecologia;
        }
        else
        {
            $ginecologia_porc_ocup_men = 0;
            $ginecologia_porc_ocup_acu = 0;
            $ginecologia_ind_rot_men = 0;
            $ginecologia_ind_rot_acu = 0;
                    
        }                
        
        if($ind_hosp->ginecologia_egresos_men != 0)                
        {
            $ginecologia_int_sust_men = ($dias_cama_men - $ind_hosp->ginecologia_dias_pac_men) / $ind_hosp->ginecologia_egresos_men;
            $ginecologia_int_sust_acu = ($dias_cama_acu - $ind_hosp->ginecologia_dias_pac_acu) / $ind_hosp->ginecologia_egresos_acu;
            $ginecologia_tasa_mort_bruta = $ind_hosp->ginecologia_defun_men / $ind_hosp->ginecologia_egresos_men * 1000;
            $ginecologia_tasa_mort_ajus = $ind_hosp->ginecologia_defun_48hrs_men / $ind_hosp->ginecologia_egresos_men * 1000;
            $ginecologia_dias_est_men = $ind_hosp->ginecologia_dias_pac_men / $ind_hosp->ginecologia_egresos_men;
            $ginecologia_dias_est_acu = $ind_hosp->ginecologia_dias_pac_acu / $ind_hosp->ginecologia_egresos_acu;
        }
        else
        {
            $ginecologia_int_sust_men = 0;
            $ginecologia_int_sust_acu = 0;
            $ginecologia_tasa_mort_bruta = 0;
            $ginecologia_tasa_mort_ajus = 0;
            $ginecologia_dias_est_men = 0;
            $ginecologia_dias_est_acu = 0;
        }
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $dias_cama_men = $um->camas_trauma * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * 30 * $um->camas_trauma;
            //echo $dias_cama_acu;
        }
        
        if($um->camas_trauma != 0)
        {
            $trauma_porc_ocup_men = $ind_hosp->trauma_dias_pac_men / $dias_cama_men * 100;
            $trauma_porc_ocup_acu = $ind_hosp->trauma_dias_pac_acu / $dias_cama_acu * 100;
            $trauma_ind_rot_men = $ind_hosp->trauma_egresos_men / $um->camas_trauma;
            $trauma_ind_rot_acu = $ind_hosp->trauma_egresos_acu / $um->camas_trauma;
        }
        else
        {
            $trauma_porc_ocup_men = 0;
            $trauma_porc_ocup_acu = 0;
            $trauma_ind_rot_men = 0;
            $trauma_ind_rot_acu = 0;
        }
        if($ind_hosp->trauma_egresos_men != 0)
        {
            $trauma_int_sust_men = ($dias_cama_men - $ind_hosp->trauma_dias_pac_men) / $ind_hosp->trauma_egresos_men;
            $trauma_int_sust_acu = ($dias_cama_acu - $ind_hosp->trauma_dias_pac_acu) / $ind_hosp->trauma_egresos_acu;
            $trauma_tasa_mort_bruta = $ind_hosp->trauma_defun_men / $ind_hosp->trauma_egresos_men * 1000;
            $trauma_tasa_mort_ajus = $ind_hosp->trauma_defun_48hrs_men / $ind_hosp->trauma_egresos_men * 1000;
            $trauma_dias_est_men = $ind_hosp->trauma_dias_pac_men / $ind_hosp->trauma_egresos_men;
            $trauma_dias_est_acu = $ind_hosp->trauma_dias_pac_acu / $ind_hosp->trauma_egresos_acu;
        }
        else
        {
            $trauma_int_sust_men = 0;
            $trauma_int_sust_acu = 0;
            $trauma_tasa_mort_bruta = 0;
            $trauma_tasa_mort_ajus = 0;
            $trauma_dias_est_men = 0;
            $trauma_dias_est_acu = 0;
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $dias_cama_men = ($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia) * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * $dias_cama_men;
            //echo $dias_cama_acu;
        }
        
        if($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia != 0)
        {
            $total_porc_ocup_men = ($ind_hosp->trauma_dias_pac_men + $ind_hosp->ginecologia_dias_pac_men + $ind_hosp->pediatria_dias_pac_men + $ind_hosp->med_int_dias_pac_men + $ind_hosp->dias_pac_men) / $dias_cama_men * 100;
            $total_porc_ocup_acu = ($ind_hosp->trauma_dias_pac_acu + $ind_hosp->ginecologia_dias_pac_acu + $ind_hosp->pediatria_dias_pac_acu + $ind_hosp->med_int_dias_pac_acu + $ind_hosp->dias_pac_acu) / $dias_cama_acu * 100;
            $total_ind_rot_men = ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->dias_pac_men) / ($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia);
            $total_ind_rot_acu = ($ind_hosp->trauma_egresos_acu + $ind_hosp->ginecologia_egresos_acu + $ind_hosp->pediatria_egresos_acu + $ind_hosp->med_int_egresos_acu + $ind_hosp->dias_pac_acu) / ($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia);
        }
        else
        {
            $total_porc_ocup_men = 0;
            $total_porc_ocup_acu = 0;
            $total_ind_rot_men = 0;
            $total_ind_rot_acu = 0;
        }
        if($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->dias_pac_men != 0)
        {
            $total_int_sust_men = ($dias_cama_men - $ind_hosp->trauma_dias_pac_men + $ind_hosp->ginecologia_dias_pac_men + $ind_hosp->pediatria_dias_pac_men + $ind_hosp->med_int_dias_pac_men + $ind_hosp->dias_pac_men) / ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->dias_pac_men);
            $total_int_sust_acu = ($dias_cama_acu - $ind_hosp->trauma_dias_pac_acu + $ind_hosp->ginecologia_dias_pac_acu + $ind_hosp->pediatria_dias_pac_acu + $ind_hosp->med_int_dias_pac_acu + $ind_hosp->dias_pac_acu) / ($ind_hosp->trauma_egresos_acu + $ind_hosp->ginecologia_egresos_acu + $ind_hosp->pediatria_egresos_acu + $ind_hosp->med_int_egresos_acu + $ind_hosp->dias_pac_acu);
            $total_tasa_mort_bruta = ($ind_hosp->trauma_defun_men + $ind_hosp->ginecologia_defun_men + $ind_hosp->pediatria_defun_men + $ind_hosp->med_int_defun_men + $ind_hosp->defun_men) / ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->egresos_men) * 1000;
            $total_tasa_mort_ajus = ($ind_hosp->trauma_defun_48hrs_men + $ind_hosp->ginecologia_defun_48hrs_men + $ind_hosp->pediatria_defun_48hrs_men + $ind_hosp->med_int_defun_48hrs_men + $ind_hosp->defun_48hrs_men) / ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->egresos_men) * 1000;
            $total_dias_est_men = ($ind_hosp->trauma_dias_pac_men + $ind_hosp->ginecologia_dias_pac_men + $ind_hosp->pediatria_dias_pac_men + $ind_hosp->med_int_dias_pac_men + $ind_hosp->dias_pac_men) / ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->egresos_men);
            $total_dias_est_acu = ($ind_hosp->trauma_dias_pac_acu + $ind_hosp->ginecologia_dias_pac_acu + $ind_hosp->pediatria_dias_pac_acu + $ind_hosp->med_int_dias_pac_acu + $ind_hosp->dias_pac_acu) / ($ind_hosp->trauma_egresos_acu + $ind_hosp->ginecologia_egresos_acu + $ind_hosp->pediatria_egresos_acu + $ind_hosp->med_int_egresos_acu + $ind_hosp->dias_pac_acu);
        }
        else
        {
            $total_int_sust_men = 0;
            $total_int_sust_acu = 0;
            $total_tasa_mort_bruta = 0;
            $total_tasa_mort_ajus = 0;
            $total_dias_est_men = 0;
            $total_dias_est_acu = 0;
        }
        
        $total_camas = $um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia;
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $titulo_ocu_men = array('data' => '<center>% OCUPACION MENSUAL</center>', 'id' => 'mensual', 'class' => 'boton');
        $titulo_ocu_acu = array('data' => '<center>% OCUPACION ACUMULADO</center>', 'id' => 'acumulado', 'class' => 'boton', 'align' => 'center');
        $titulo_ind_rot_men = array('data' => '<center>INDICE DE ROTACION MENSUAL</center>', 'id' => 'ind_rot_men', 'class' => 'boton', 'align' => 'center');
        $titulo_ind_rot_acu = array('data' => '<center>INDICE DE ROTACION ACUMULADO</center>', 'id' => 'ind_rot_acu', 'class' => 'boton', 'align' => 'center');
        $titulo_int_sust_men = array('data' => '<center>INTERVALO DE SUSTITUCION MENSUAL</center>', 'id' => 'int_sust_men', 'class' => 'boton', 'align' => 'center');
        $titulo_int_sust_acu = array('data' => '<center>INTERVALO DE SUSTITUCION ACUMULADO</center>', 'id' => 'int_sust_acu', 'class' => 'boton', 'align' => 'center');
        $titulo_tasa_mort_bruta = array('data' => '<center>TASA DE MORTALIDAD BRUTA</center>', 'id' => 'tasa_mort_bruta', 'class' => 'boton', 'align' => 'center');
        $titulo_tasa_mort_ajus = array('data' => '<center>TASA DE MORTALIDAD AJUSTADA</center>', 'id' => 'tasa_mort_ajus', 'class' => 'boton', 'align' => 'center');
        $titulo_dias_est_men = array('data' => '<center>DIAS ESTANCIA MENSUAL</center>', 'id' => 'dias_est_men', 'class' => 'boton', 'align' => 'center');
        $titulo_dias_est_acu = array('data' => '<center>DIAS ESTANCIA ACUMULADO</center>', 'id' => 'dias_est_acu', 'class' => 'boton', 'align' => 'center');
        
        #PARA PORCENTAJE DE OCUPACION MENSUAL         
        $porc_ocup_men_celda = array('data' => number_format($porc_ocup_men,2), 'id' => 'cir_ocup_men', 'class' => 'boton', 'align' => 'right');
        $med_int_porc_ocup_men_celda = array('data' => number_format($med_int_porc_ocup_men,2), 'id' => 'med_int_ocup_men', 'class' => 'boton', 'align' => 'right');
        $pediatria_porc_ocup_men_celda = array('data' => number_format($pediatria_porc_ocup_men,2), 'id' => 'pediatria_ocup_men', 'class' => 'boton', 'align' => 'right');
        $ginecologia_porc_ocup_men_celda = array('data' => number_format($ginecologia_porc_ocup_men,2), 'id' => 'ginecologia_ocup_men', 'class' => 'boton', 'align' => 'right');
        $trauma_porc_ocup_men_celda = array('data' => number_format($trauma_porc_ocup_men,2), 'id' => 'trauma_ocup_men', 'class' => 'boton', 'align' => 'right');
        $total_porc_ocup_men_celda = array('data' => number_format($total_porc_ocup_men,2), 'id' => 'total_ocup_men', 'class' => 'boton', 'align' => 'right');
        
        #PARA PORCENTAJE DE OCUPACION ACUMULADO         
        $porc_ocup_acu_celda = array('data' => number_format($porc_ocup_acu,2), 'id' => 'cir_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $med_int_porc_ocup_acu_celda = array('data' => number_format($med_int_porc_ocup_acu,2), 'id' => 'med_int_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $pediatria_porc_ocup_acu_celda = array('data' => number_format($pediatria_porc_ocup_acu,2), 'id' => 'pediatria_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $ginecologia_porc_ocup_acu_celda = array('data' => number_format($ginecologia_porc_ocup_acu,2), 'id' => 'ginecologia_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $trauma_porc_ocup_acu_celda = array('data' => number_format($trauma_porc_ocup_acu,2), 'id' => 'trauma_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $total_porc_ocup_acu_celda = array('data' => number_format($total_porc_ocup_acu,2), 'id' => 'total_ocup_acu', 'class' => 'boton', 'align' => 'right');
        
        #PARA INDICE DE ROTACION MENSUAL     
        $ind_rot_men_celda = array('data' => number_format($ind_rot_men,2), 'id' => 'cir_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $med_int_ind_rot_men_celda = array('data' => number_format($med_int_ind_rot_men,2), 'id' => 'med_int_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $pediatria_ind_rot_men_celda = array('data' => number_format($pediatria_ind_rot_men,2), 'id' => 'pediatria_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $ginecologia_ind_rot_men_celda = array('data' => number_format($ginecologia_ind_rot_men,2), 'id' => 'ginecologia_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $trauma_ind_rot_men_celda = array('data' => number_format($trauma_ind_rot_men,2), 'id' => 'trauma_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $total_ind_rot_men_celda = array('data' => number_format($total_ind_rot_men,2), 'id' => 'total_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        
        #PARA INDICE DE ROTACION ACUMULADO     
        $ind_rot_acu_celda = array('data' => number_format($ind_rot_acu,2), 'id' => 'cir_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $med_int_ind_rot_acu_celda = array('data' => number_format($med_int_ind_rot_acu,2), 'id' => 'med_int_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $pediatria_ind_rot_acu_celda = array('data' => number_format($pediatria_ind_rot_acu,2), 'id' => 'pediatria_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $ginecologia_ind_rot_acu_celda = array('data' => number_format($ginecologia_ind_rot_acu,2), 'id' => 'ginecologia_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $trauma_ind_rot_acu_celda = array('data' => number_format($trauma_ind_rot_acu,2), 'id' => 'trauma_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $total_ind_rot_acu_celda = array('data' => number_format($total_ind_rot_acu,2), 'id' => 'total_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        
        #PARA INTERVALO DE SUSTITUCION MENSUAL
        $int_sust_men_celda = array('data' => number_format($int_sust_men,2), 'id' => 'cir_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $med_int_int_sust_men_celda = array('data' => number_format($med_int_int_sust_men,2), 'id' => 'med_int_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $pediatria_int_sust_men_celda = array('data' => number_format($pediatria_int_sust_men,2), 'id' => 'pediatria_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $ginecologia_int_sust_men_celda = array('data' => number_format($ginecologia_int_sust_men,2), 'id' => 'ginecologia_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $trauma_int_sust_men_celda = array('data' => number_format($trauma_int_sust_men,2), 'id' => 'trauma_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $total_int_sust_men_celda = array('data' => number_format($total_int_sust_men,2), 'id' => 'total_int_sust_men', 'class' => 'boton', 'align' => 'right');
        
        #PARA INTERVALO DE SUSTITUCION ACUMULADO
        $int_sust_acu_celda = array('data' => number_format($int_sust_acu,2), 'id' => 'cir_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $med_int_int_sust_acu_celda = array('data' => number_format($med_int_int_sust_acu,2), 'id' => 'med_int_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $pediatria_int_sust_acu_celda = array('data' => number_format($pediatria_int_sust_acu,2), 'id' => 'pediatria_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $ginecologia_int_sust_acu_celda = array('data' => number_format($ginecologia_int_sust_acu,2), 'id' => 'ginecologia_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $trauma_int_sust_acu_celda = array('data' => number_format($trauma_int_sust_acu,2), 'id' => 'trauma_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $total_int_sust_acu_celda = array('data' => number_format($total_int_sust_acu,2), 'id' => 'total_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        
        #PARA TASA DE MORTALIDAD BRUTA
        $tasa_mort_bruta_celda = array('data' => number_format($tasa_mort_bruta,2), 'id' => 'cir_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $med_int_tasa_mort_bruta_celda = array('data' => number_format($med_int_tasa_mort_bruta,2), 'id' => 'med_int_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $pediatria_tasa_mort_bruta_celda = array('data' => number_format($pediatria_tasa_mort_bruta,2), 'id' => 'pediatria_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $ginecologia_tasa_mort_bruta_celda = array('data' => number_format($ginecologia_tasa_mort_bruta,2), 'id' => 'ginecologia_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $trauma_tasa_mort_bruta_celda = array('data' => number_format($trauma_tasa_mort_bruta,2), 'id' => 'trauma_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $total_tasa_mort_bruta_celda = array('data' => number_format($total_tasa_mort_bruta,2), 'id' => 'total_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        
        #PARA TASA DE MORTALIDAD AJUSTADA
        $tasa_mort_ajus_celda = array('data' => number_format($tasa_mort_ajus,2), 'id' => 'cir_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $med_int_tasa_mort_ajus_celda = array('data' => number_format($med_int_tasa_mort_ajus,2), 'id' => 'med_int_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $pediatria_tasa_mort_ajus_celda = array('data' => number_format($pediatria_tasa_mort_ajus,2), 'id' => 'pediatria_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $ginecologia_tasa_mort_ajus_celda = array('data' => number_format($ginecologia_tasa_mort_ajus,2), 'id' => 'ginecologia_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $trauma_tasa_mort_ajus_celda = array('data' => number_format($trauma_tasa_mort_ajus,2), 'id' => 'trauma_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $total_tasa_mort_ajus_celda = array('data' => number_format($total_tasa_mort_ajus,2), 'id' => 'total_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        
        #PARA DIAS ESTANCIA MENSUAL
        $dias_est_men_celda = array('data' => number_format($dias_est_men,2), 'id' => 'cir_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $med_int_dias_est_men_celda = array('data' => number_format($med_int_dias_est_men,2), 'id' => 'med_int_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $pediatria_dias_est_men_celda = array('data' => number_format($pediatria_dias_est_men,2), 'id' => 'pediatria_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $ginecologia_dias_est_men_celda = array('data' => number_format($ginecologia_dias_est_men,2), 'id' => 'ginecologia_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $trauma_dias_est_men_celda = array('data' => number_format($trauma_dias_est_men,2), 'id' => 'trauma_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $total_dias_est_men_celda = array('data' => number_format($total_dias_est_men,2), 'id' => 'total_dias_est_men', 'class' => 'boton', 'align' => 'right');
        
        #PARA DIAS ESTANCIA ACUMULADO
        $dias_est_acu_celda = array('data' => number_format($dias_est_acu,2), 'id' => 'cir_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $med_int_dias_est_acu_celda = array('data' => number_format($med_int_dias_est_acu,2), 'id' => 'med_int_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $pediatria_dias_est_acu_celda = array('data' => number_format($pediatria_dias_est_acu,2), 'id' => 'pediatria_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $ginecologia_dias_est_acu_celda = array('data' => number_format($ginecologia_dias_est_acu,2), 'id' => 'ginecologia_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $trauma_dias_est_acu_celda = array('data' => number_format($trauma_dias_est_acu,2), 'id' => 'trauma_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $total_dias_est_acu_celda = array('data' => number_format($total_dias_est_acu,2), 'id' => 'total_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        
        $this->table->set_heading('','CAMAS CENSABLES',$titulo_ocu_men,$titulo_ocu_acu,$titulo_ind_rot_men,$titulo_ind_rot_acu,$titulo_int_sust_men,$titulo_int_sust_acu,$titulo_tasa_mort_bruta,$titulo_tasa_mort_ajus,$titulo_dias_est_men,$titulo_dias_est_acu);
        $this->table->add_row('CIRUGIA',$um->camas_cirugia,$porc_ocup_men_celda,$porc_ocup_acu_celda,$ind_rot_men_celda,$ind_rot_acu_celda,$int_sust_men_celda,$int_sust_acu_celda,$tasa_mort_bruta_celda,$tasa_mort_ajus_celda,$dias_est_men_celda,$dias_est_acu_celda);
        $this->table->add_row('MEDICINA INTERNA',$um->camas_med_interna,$med_int_porc_ocup_men_celda,$med_int_porc_ocup_acu_celda,$med_int_ind_rot_men_celda,$med_int_ind_rot_acu_celda,$med_int_int_sust_men_celda,$med_int_int_sust_acu_celda,$med_int_tasa_mort_bruta_celda,$med_int_tasa_mort_ajus_celda,$med_int_dias_est_men_celda,$med_int_dias_est_acu_celda);
        $this->table->add_row('PEDIATRIA',$um->camas_pediatria,$pediatria_porc_ocup_men_celda,$pediatria_porc_ocup_acu_celda,$pediatria_ind_rot_men_celda,$pediatria_ind_rot_acu_celda,$pediatria_int_sust_men_celda,$pediatria_int_sust_acu_celda,$pediatria_tasa_mort_bruta_celda,$pediatria_tasa_mort_ajus_celda,$pediatria_dias_est_men_celda,$pediatria_dias_est_acu_celda);
        $this->table->add_row('GINECOLOGIA',$um->camas_ginecologia,$ginecologia_porc_ocup_men_celda,$ginecologia_porc_ocup_acu_celda,$ginecologia_ind_rot_men_celda,$ginecologia_ind_rot_acu_celda,$ginecologia_int_sust_men_celda,$ginecologia_int_sust_acu_celda,$ginecologia_tasa_mort_bruta_celda,$ginecologia_tasa_mort_ajus_celda,$ginecologia_dias_est_men_celda,$ginecologia_dias_est_acu_celda);
        $this->table->add_row('TRAUMATOLOGIA',$um->camas_trauma,$trauma_porc_ocup_men_celda,$trauma_porc_ocup_acu_celda,$trauma_ind_rot_men_celda,$trauma_ind_rot_acu_celda,$trauma_int_sust_men_celda,$trauma_int_sust_acu_celda,$trauma_tasa_mort_bruta_celda,$trauma_tasa_mort_ajus_celda,$trauma_dias_est_men_celda,$trauma_dias_est_acu_celda);
        $this->table->add_row('TOTAL',$total_camas,$total_porc_ocup_men_celda,$total_porc_ocup_acu_celda,$total_ind_rot_men_celda,$total_ind_rot_acu_celda,$total_int_sust_men_celda,$total_int_sust_acu_celda,$total_tasa_mort_bruta_celda,$total_tasa_mort_ajus_celda,$total_dias_est_men_celda,$total_dias_est_acu_celda);
        
        $datos['porc_ocup_men'] = (float) number_format($porc_ocup_men,2);
        $datos['med_int_porc_ocup_men'] = (float) number_format($med_int_porc_ocup_men,2);
        $datos['pediatria_porc_ocup_men'] = (float) number_format($pediatria_porc_ocup_men,2);
        $datos['ginecologia_porc_ocup_men'] = (float) number_format($ginecologia_porc_ocup_men,2);
        $datos['trauma_porc_ocup_men'] = (float) number_format($trauma_porc_ocup_men,2);
        $datos['total_porc_ocup_men'] = (float) number_format($total_porc_ocup_men,2);
        
        $datos['porc_ocup_acu'] = (float) number_format($porc_ocup_acu,2);
        $datos['med_int_porc_ocup_acu'] = (float) number_format($med_int_porc_ocup_acu,2);
        $datos['pediatria_porc_ocup_acu'] = (float) number_format($pediatria_porc_ocup_acu,2);
        $datos['ginecologia_porc_ocup_acu'] = (float) number_format($ginecologia_porc_ocup_acu,2);
        $datos['trauma_porc_ocup_acu'] = (float) number_format($trauma_porc_ocup_acu,2);
        $datos['total_porc_ocup_acu'] = (float) number_format($total_porc_ocup_acu,2);
        
        $datos['ind_rot_men'] = (float) number_format($ind_rot_men,2);
        $datos['med_int_ind_rot_men'] = (float) number_format($med_int_ind_rot_men,2);
        $datos['pediatria_ind_rot_men'] = (float) number_format($pediatria_ind_rot_men,2);
        $datos['ginecologia_ind_rot_men'] = (float) number_format($ginecologia_ind_rot_men,2);
        $datos['trauma_ind_rot_men'] = (float) number_format($trauma_ind_rot_men,2);
        $datos['total_ind_rot_men'] = (float) number_format($total_ind_rot_men,2);
        
        $datos['ind_rot_acu'] = (float) number_format($ind_rot_acu,2);
        $datos['med_int_ind_rot_acu'] = (float) number_format($med_int_ind_rot_acu,2);
        $datos['pediatria_ind_rot_acu'] = (float) number_format($pediatria_ind_rot_acu,2);
        $datos['ginecologia_ind_rot_acu'] = (float) number_format($ginecologia_ind_rot_acu,2);
        $datos['trauma_ind_rot_acu'] = (float) number_format($trauma_ind_rot_acu,2);
        $datos['total_ind_rot_acu'] = (float) number_format($total_ind_rot_acu,2);
        
        $datos['int_sust_men'] = (float) number_format($int_sust_men,2);
        $datos['med_int_int_sust_men'] = (float) number_format($med_int_int_sust_men,2);
        $datos['pediatria_int_sust_men'] = (float) number_format($pediatria_int_sust_men,2);
        $datos['ginecologia_int_sust_men'] = (float) number_format($ginecologia_int_sust_men,2);
        $datos['trauma_int_sust_men'] = (float) number_format($trauma_int_sust_men,2);
        $datos['total_int_sust_men'] = (float) number_format($total_int_sust_men,2);
        
        $datos['int_sust_acu'] = (float) number_format($int_sust_acu,2);
        $datos['med_int_int_sust_acu'] = (float) number_format($med_int_int_sust_acu,2);
        $datos['pediatria_int_sust_acu'] = (float) number_format($pediatria_int_sust_acu,2);
        $datos['ginecologia_int_sust_acu'] = (float) number_format($ginecologia_int_sust_acu,2);
        $datos['trauma_int_sust_acu'] = (float) number_format($trauma_int_sust_acu,2);
        $datos['total_int_sust_acu'] = (float) number_format($total_int_sust_acu,2);
        
        $datos['tasa_mort_bruta'] = (float) number_format($tasa_mort_bruta,2);
        $datos['med_int_tasa_mort_bruta'] = (float) number_format($med_int_tasa_mort_bruta,2);
        $datos['pediatria_tasa_mort_bruta'] = (float) number_format($pediatria_tasa_mort_bruta,2);
        $datos['ginecologia_tasa_mort_bruta'] = (float) number_format($ginecologia_tasa_mort_bruta,2);
        $datos['trauma_tasa_mort_bruta'] = (float) number_format($trauma_tasa_mort_bruta,2);
        $datos['total_tasa_mort_bruta'] = (float) number_format($total_tasa_mort_bruta,2);
        
        $datos['tasa_mort_ajus'] = (float) number_format($tasa_mort_ajus,2);
        $datos['med_int_tasa_mort_ajus'] = (float) number_format($med_int_tasa_mort_ajus,2);
        $datos['pediatria_tasa_mort_ajus'] = (float) number_format($pediatria_tasa_mort_ajus,2);
        $datos['ginecologia_tasa_mort_ajus'] = (float) number_format($ginecologia_tasa_mort_ajus,2);
        $datos['trauma_tasa_mort_ajus'] = (float) number_format($trauma_tasa_mort_ajus,2);
        $datos['total_tasa_mort_ajus'] = (float) number_format($total_tasa_mort_ajus,2);
        
        $datos['dias_est_men'] = (float) number_format($dias_est_men,2);
        $datos['med_int_dias_est_men'] = (float) number_format($med_int_dias_est_men,2);
        $datos['pediatria_dias_est_men'] = (float) number_format($pediatria_dias_est_men,2);
        $datos['ginecologia_dias_est_men'] = (float) number_format($ginecologia_dias_est_men,2);
        $datos['trauma_dias_est_men'] = (float) number_format($trauma_dias_est_men,2);
        $datos['total_dias_est_men'] = (float) number_format($total_dias_est_men,2);
        
        $datos['dias_est_acu'] = (float) number_format($dias_est_acu,2);
        $datos['med_int_dias_est_acu'] = (float) number_format($med_int_dias_est_acu,2);
        $datos['pediatria_dias_est_acu'] = (float) number_format($pediatria_dias_est_acu,2);
        $datos['ginecologia_dias_est_acu'] = (float) number_format($ginecologia_dias_est_acu,2);
        $datos['trauma_dias_est_acu'] = (float) number_format($trauma_dias_est_acu,2);
        $datos['total_dias_est_acu'] = (float) number_format($total_dias_est_acu,2);
        
        return $datos;
        }
    }
    function reporte_ind_hosp_form()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            //$this->output->enable_profiler();
            $this->load->helpers(array('form'));
            $this->load->library('table');
            $this->load->model('md_unidad_medica');
            $this->load->model('md_ind_hosp');
            $indicador = new Md_ind_hosp();
            $datos['meses'] = $indicador->meses_capturados('2014'); 
            $datos['um'] = $this->md_unidad_medica->menu_desplegable(array('H.C.','H.G.','H.E.'));
            $datos['um']['HG'] = 'ESTATAL H.G.';
            $datos['um']['HC'] = 'ESTATAL H.C.';
            //print_r($datos['um']);
            //$mnu['selec'] = 'ind_hosp';
            //$this->load->view('vw_encabezado');
            //$this->load->view('vw_menu',$mnu);
            $this->load->model('md_pagina');
            $datos['pagina'] = new Md_Pagina;
            $this->load->view('vw_ind_hosp',$datos);
        }
    }
    function reporte_ind_hosp()
    {
        
        /*        
        if($this->input->post('um') == 'HC')
            $id_um = "H.C.";
        elseif($this->input->post('um') == 'HG')
                $id_um = "H.G.";
            else*/ 
        $id_um = $this->input->post('um');
            
        $mes = $this->input->post('mes');
        $anio = $this->input->post('anio');
        
        $this->load->model('md_unidad_medica');
        $this->load->library('Mi_libreria');
        $mes_txt = $this->mi_libreria->mes_a_txt($mes);
        
        $um = new Md_unidad_medica($id_um);
        
        $this->load->helpers(array('html'));
        $this->load->view('vw_encabezado');
        //echo br(4);
        //$datos2['titulo'] = "<center>".heading($um->nombre)." ".$mes_txt." ".$anio."</center>";
        $datos2['titulo'] = "<center>".heading($um->nombre);
        if($this->input->post('um') == 'HC')
            $datos2['titulo'] = "<center>".heading('ESTATAL HOSPITALES COMUNITARIOS');
        elseif($this->input->post('um') == 'HG')
                $datos2['titulo'] = "<center>".heading('ESTATAL HOSPITALES GENERALES');
        $datos2['um'] = $id_um;
        $datos2['mes'] = $mes;
        $datos2['anio'] = $anio;
        $datos2['datos'] = $this->ind_hosp($id_um,$mes,$anio);
        $mnu['selec'] = 'ind_hosp';
        $this->load->view('vw_menu',$mnu);
        $this->load->view('vw_reporte_ind_hosp',$datos2);
        //$this->load->view('vw_footer');
    }
    function graficar_ind_hosp($id_um,$mes,$anio,$indicador,$porc_ocup_men,$med_int_porc_ocup_men,$pediatria_porc_ocup_men,$ginecologia_porc_ocup_men,$trauma_porc_ocup_men,$titulo)
    {
        
        $data['porc_ocup_men'] = $porc_ocup_men;
        $data['med_int_porc_ocup_men'] = $med_int_porc_ocup_men;
        $data['pediatria_porc_ocup_men'] = $pediatria_porc_ocup_men;
        $data['ginecologia_porc_ocup_men'] = $ginecologia_porc_ocup_men;
        $data['trauma_porc_ocup_men'] = $trauma_porc_ocup_men;
        //echo $titulo;
        switch ($titulo)
        {
            case 1: $indicador = 'ocup_men'; 
                    break;
            case 2: $indicador = 'ocup_acu'; 
                    break;
            case 3: $indicador = 'ind_rot_men'; 
                    break;
            case 4: $indicador = 'ind_rot_acu'; 
                    break;
            case 5: $indicador = 'int_sust_men'; 
                    break;
            case 6: $indicador = 'int_sust_acu'; 
                    break;
            case 7: $indicador = 'tasa_mort_bruta'; 
                    break;
            case 8: $indicador = 'tasa_mort_ajus'; 
                    break;
            case 9: $indicador = 'dias_est_men'; 
                    break;
            case 10: $indicador = 'dias_est_acu'; 
                    break;            
        }
        $data['datos'] = $this->datos_ind_hosp_xum($id_um,'total',$anio,$indicador);
        //print_r($data['datos']); 
        
        $data['titulo'] = $titulo;
        $data['anio'] = $anio;
        
        $this->load->model('md_ind_hosp');
        $indicador = new Md_ind_hosp();
        //$datos['mes'] = $indicador->meses_capturados('2014');
        //print_r( sizeof($datos['mes']) );
        $data['mes'] = sizeof($indicador->meses_capturados('2014'));
        
        $data['meses'] = array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
        
        $this->load->model('md_unidad_medica');
        $unidad = new Md_unidad_medica($id_um);
        
        if($unidad->tipologia == 'H.C.')
            $data['semaforo'] = array('rojo1' => 25, 'amarillo' => 35, 'verde' => 50, 'total' => 100);
        else
            $data['semaforo'] = array('rojo1' => 50, 'amarillo' => 80, 'verde' => 90, 'total' => 140);
        
        //$this->load->view('vw_encabezado', $data);
        $this->load->view('prueba2',$data); 
    }
    function graficar_ind_hosp_xum($id_um,$servicio,$anio,$indicador,$titulo,$servicio2)
    {        
        $data['url'] = "principal/datos_ind_hosp_xum/".$id_um.'/'.$servicio.'/'.$anio.'/'.$indicador;
        $data['width'] = 700;
        $data['height'] = 350;
        $data['useswfobject'] = false;
        $data['datos'] = $this->datos_ind_hosp_xum($id_um,$servicio,$anio,$indicador); 
        $data['titulo'] = $titulo;
        $data['servicio'] = $servicio2;  
        $data['meses'] = array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
        
        $this->load->model('md_ind_hosp');
        $indicador = new Md_ind_hosp();
        //$datos['mes'] = $indicador->meses_capturados('2014');
        //print_r( sizeof($datos['mes']) );
        $data['mes'] = sizeof($indicador->meses_capturados('2014'));
             
        //$this->load->view('vw_encabezado', $data);
        $this->load->view('prueba3',$data);  
    }
    function datos_ind_hosp_xum($id_um,$servicio,$anio,$indicador)
    {        
        $this->load->model('md_unidad_medica');
        $um = new Md_unidad_medica($id_um);
        
        $meses = array('01','02','03','04','05','06','07','08','09','10','11','12');
        
        foreach($meses as $mes)
        {
            $dato = $this->ind_hosp($id_um,$mes,$anio);
            if($dato != FALSE)
                $datos[] = $dato;
        }
            
        //print_r($datos);
        //echo $indicador;
        $bar_values = array();
        foreach($datos as $dato)
        {
            if($indicador == 'ocup_men')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['porc_ocup_men'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_porc_ocup_men'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_porc_ocup_men'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_porc_ocup_men'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_porc_ocup_men'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_porc_ocup_men'];
            }
            elseif($indicador == 'ocup_acu')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['porc_ocup_acu'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_porc_ocup_acu'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_porc_ocup_acu'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_porc_ocup_acu'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_porc_ocup_acu'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_porc_ocup_acu'];
            }
            elseif($indicador == 'ind_rot_men')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['ind_rot_men'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_ind_rot_men'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_ind_rot_men'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_ind_rot_men'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_ind_rot_men'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_ind_rot_men'];
            }
            elseif($indicador == 'ind_rot_acu')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['ind_rot_acu'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_ind_rot_acu'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_ind_rot_acu'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_ind_rot_acu'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_ind_rot_acu'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_ind_rot_acu'];
            }
            elseif($indicador == 'int_sust_men')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['int_sust_men'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_int_sust_men'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_int_sust_men'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_int_sust_men'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_int_sust_men'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_int_sust_men'];
            }
            elseif($indicador == 'int_sust_acu')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['int_sust_acu'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_int_sust_acu'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_int_sust_acu'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_int_sust_acu'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_int_sust_acu'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_int_sust_acu'];
            }
            elseif($indicador == 'tasa_mort_bruta')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['tasa_mort_bruta'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_tasa_mort_bruta'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_tasa_mort_bruta'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_tasa_mort_bruta'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_tasa_mort_bruta'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_tasa_mort_bruta'];
            }
            elseif($indicador == 'tasa_mort_ajus')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['tasa_mort_ajus'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_tasa_mort_ajus'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_tasa_mort_ajus'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_tasa_mort_ajus'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_tasa_mort_ajus'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_tasa_mort_ajus'];
            }
            elseif($indicador == 'dias_est_men')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['dias_est_men'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_dias_est_men'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_dias_est_men'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_dias_est_men'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_dias_est_men'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_dias_est_men'];
            }
            elseif($indicador == 'dias_est_acu')
            {
                
                if($servicio == 'cir')
                    $bar_values[] = $dato['dias_est_acu'];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_dias_est_acu'];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_dias_est_acu'];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_dias_est_acu'];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_dias_est_acu'];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_dias_est_acu'];
            }
        }
        
        return $bar_values;
        
    }        
    function prueba_46ind($um,$mes,$anio)
    {
        
        //$this->load->model('Md_46ind');
//        $nac_x_cesarea = new nac_x_cesarea();
//        $nac_x_cesarea->anio = $anio;
//        $nac_x_cesarea->mes = $mes;
//        $nac_x_cesarea->hospital = $um;
//        $nac_x_cesarea->reporte();
//        echo $nac_x_cesarea->nombre.": ".$nac_x_cesarea->total;
        
       
        //$this->load->model('md_46ind');
//        $prom_cons_emb = new prom_cons_pre_x_emb();
//        $prom_cons_emb->juris = $um;
//        $prom_cons_emb->mes = $mes;
//        $prom_cons_emb->anio = $anio;
//        $prom_cons_emb->reporte();
//        echo $prom_cons_emb->total;
        
        /*$this->load->model('md_46ind');
        $prom_cons_emb = new usuarias_act_pf();
        $prom_cons_emb->juris = $um;
        $prom_cons_emb->mes = $mes;
        $prom_cons_emb->anio = $anio;
        $prom_cons_emb->reporte();
        echo $prom_cons_emb->total;*/
        $this->load->model('md_indicador_nuevo');
        $indicador = new Md_indicador_1er(11);
        //print_r($indicador);
        $indicador->reportar_mes($mes,$anio,$um);
    }
}

?>