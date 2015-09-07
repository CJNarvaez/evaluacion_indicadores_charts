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
            $this->load->model('md_reporte');
            $datos = $this->md_reporte->administradorJuris($this->input->post('mes'),$this->input->post('anio'),$this->input->post('juris'));

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
            $this->load->model('md_reporte');
            $datos = $this->md_reporte->administradorJurisHC($this->input->post('mes'),$this->input->post('anio'),$this->input->post('juris'));

            $this->load->view("vw_reporta_evaluacion",$datos);
            //$this->load->view('vw_footer');
        }
    }
    
    function reporte_hc()
    {        
        $this->load->model('md_reporte');
        
        $datos = $this->md_reporte->administradorHC($this->input->post('mes'),$this->input->post('anio'),$this->input->post('hc'));
        
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
            
            $this->load->model('md_reporte');
            $datos = $this->md_reporte->administrador2n($this->input->post('mes'),$this->input->post('anio'),$this->input->post('hghehc'));
            
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
        $this->load->model('md_graficaIndHosp');
                            
        $id_um = $this->input->post('um');
            
        $mes = $this->input->post('mes');
        $anio = $this->input->post('anio');
        
        $this->load->model('md_unidad_medica');
        
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
        $datos2['datos'] = $this->md_graficaIndHosp->ind_hosp($um,$mes,$anio);
        $mnu['selec'] = 'ind_hosp';
        $this->load->view('vw_menu',$mnu);
        $this->load->view('vw_reporte_ind_hosp',$datos2);
        //$this->load->view('vw_footer');
    }
    function graficar_ind_hosp($id_um,$mes,$anio,$indicador,$porc_ocup_men,$med_int_porc_ocup_men,$pediatria_porc_ocup_men,$ginecologia_porc_ocup_men,$trauma_porc_ocup_men,$titulo)
    {
        $this->load->model('md_unidad_medica');
        $um = new Md_unidad_medica($id_um);
        
        $this->load->model('md_graficaIndHosp');
        
        $data['porc_ocup_men'] = $porc_ocup_men;
        $data['med_int_porc_ocup_men'] = $med_int_porc_ocup_men;
        $data['pediatria_porc_ocup_men'] = $pediatria_porc_ocup_men;
        $data['ginecologia_porc_ocup_men'] = $ginecologia_porc_ocup_men;
        $data['trauma_porc_ocup_men'] = $trauma_porc_ocup_men;
        //echo $titulo;
        $indicador = $this->md_graficaIndHosp->tituloNumATxt($titulo);

        $data['datos'] = $this->md_graficaIndHosp->datos_ind_hosp_xum($um,'total',$anio,$indicador['corto']);
        //print_r($data['datos']); 
        
        $data['titulo'] = $indicador['largo'];
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
        $this->load->model('md_unidad_medica');
        $um = new Md_unidad_medica($id_um);
        
        $this->load->model('md_graficaIndHosp');
        
        $data['datos'] = $this->md_graficaIndHosp->datos_ind_hosp_xum($um,$servicio,$anio,$indicador); 
        $data['titulo'] = $titulo;
        $data['servicio'] = $servicio2;  
        $data['meses'] = array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
        
        $this->load->model('md_ind_hosp');
        $indicador = new Md_ind_hosp();

        $data['mes'] = sizeof($indicador->meses_capturados('2014'));

        $this->load->view('prueba3',$data);  
    }        
    function prueba_46ind($um,$mes,$anio)
    {
        $this->output->enable_profiler();
        /*
        $this->load->model('Md_46ind');
        $nac_x_cesarea = new nac_x_cesarea();
        $nac_x_cesarea->anio = $anio;
        $nac_x_cesarea->mes = $mes;
        $nac_x_cesarea->hospital = $um;
        $nac_x_cesarea->reporte();
        echo "numerador : ".$nac_x_cesarea->numerador." denominador: ".$nac_x_cesarea->denominador."<br/>";
        echo $nac_x_cesarea->nombre.": ".$nac_x_cesarea->total;
        //print_r($nac_x_cesarea);
        */

        /*
        //  las juris se tienen que definir de modo 01, 02, 03, etc.
        $this->load->model('md_46ind');
        $prom_cons_emb = new prom_cons_pre_x_emb();
        $prom_cons_emb->juris = $um;
        $prom_cons_emb->mes = $mes;
        $prom_cons_emb->anio = $anio;
        $prom_cons_emb->reporte();
        echo $prom_cons_emb->total;
        */

        /*
        $this->load->model('md_46ind');
        $prom_cons_emb = new usuarias_act_pf();
        $prom_cons_emb->juris = $um;
        $prom_cons_emb->mes = $mes;
        $prom_cons_emb->anio = $anio;
        $prom_cons_emb->reporte();
        echo "numerador : ".$prom_cons_emb->numerador." denominador: ".$prom_cons_emb->denominador."<br/>";
        echo $prom_cons_emb->total;
         */
        
        /*
        $this->load->model('md_46ind');
        $prom_cons_med = new Prom_diario_consulta_x_medico();
        $prom_cons_med->juris = $um;
        $prom_cons_med->mes = $mes;
        $prom_cons_med->anio = $anio;
        $prom_cons_med->reporte();
        echo $prom_cons_med->nombre."<br />";
        echo "numerador : ".$prom_cons_med->numerador." denominador: ".$prom_cons_med->denominador."<br/>";
        echo $prom_cons_med->total;
        */
        
        /*
        $this->load->model('md_46ind');
        $porc_ocup_hosp = new Porc_ocupacion_hosp();
        $porc_ocup_hosp->juris = $um;
        $porc_ocup_hosp->mes = $mes;
        $porc_ocup_hosp->anio = $anio;
        $porc_ocup_hosp->reporte();
        echo $porc_ocup_hosp->nombre."<br />";
        echo "numerador : ".$porc_ocup_hosp->numerador." denominador: ".$porc_ocup_hosp->denominador."<br/>";
        echo $porc_ocup_hosp->total;
        */
        
        /*
        $this->load->model('md_46ind');
        $prom_dias_est = new prom_dias_est();
        $prom_dias_est->juris = $um;
        $prom_dias_est->mes = $mes;
        $prom_dias_est->anio = $anio;
        $prom_dias_est->reporte();
        echo $prom_dias_est->nombre."<br />";
        echo "numerador : ".$prom_dias_est->numerador." denominador: ".$prom_dias_est->denominador."<br/>";
        echo $prom_dias_est->total;
        */
       /*
        $this->load->model('md_46ind');
        $inter_quir_x_quir = new inter_quir_x_quir();
        $inter_quir_x_quir->juris = $um;
        $inter_quir_x_quir->mes = $mes;
        $inter_quir_x_quir->anio = $anio;
        $inter_quir_x_quir->reporte();
        echo $inter_quir_x_quir->nombre."<br />";
        echo "numerador : ".$inter_quir_x_quir->numerador." denominador: ".$inter_quir_x_quir->denominador."<br/>";
        echo $inter_quir_x_quir->total;
        */
        //$this->load->model('md_indicador_nuevo');
        //$indicador = new Md_indicador_1er(11);
        //print_r($indicador);
        //$indicador->reportar_mes($mes,$anio,$um);
        /*
        $this->load->model('md_46ind');
        $morbTbPulmonar = new morbTbPulmonar();
        $morbTbPulmonar->juris = $um;
        $morbTbPulmonar->mes = $mes;
        $morbTbPulmonar->anio = $anio;
        $morbTbPulmonar->reporte();
        echo $morbTbPulmonar->nombre."<br />";
        echo "numerador : ".$morbTbPulmonar->numerador." denominador: ".$morbTbPulmonar->denominador."<br/>";
        echo $morbTbPulmonar->total;
        */
        $this->load->model('md_46ind');
        $nuevosTbTaesTerm = new nuevosTbTaesTerm();
        $nuevosTbTaesTerm->juris = $um;
        $nuevosTbTaesTerm->mes = $mes;
        $nuevosTbTaesTerm->anio = $anio;
        $nuevosTbTaesTerm->reporte();
        echo $nuevosTbTaesTerm->nombre."<br />";
        echo "numerador : ".$nuevosTbTaesTerm->numerador." denominador: ".$nuevosTbTaesTerm->denominador."<br/>";
        echo $nuevosTbTaesTerm->total;
    }
    function indRes()
    {
        $this->load->view('vw_indRes');
    }
}

?>