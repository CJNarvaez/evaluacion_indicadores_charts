<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Evaluacion extends CI_Controller 
{
    function publicarJuris($mes,$anio)
    {
        $this->load->model('md_evaluacion');
        for($i=1; $i<=7; $i++)
            $this->md_evaluacion->publicarJuris($mes,$anio,$i);
        $this->md_evaluacion->publicarJuris($mes,$anio,"todas");
        $this->load->view('vw_publicado');
    }
    function publicarJurisHc($mes,$anio)
    {
        $this->load->model('md_evaluacion');
        for($i=1; $i<=7; $i++)
            $this->md_evaluacion->publicarJurisHc($mes,$anio,$i);
        $this->md_evaluacion->publicarJurisHc($mes,$anio,"todas");
        $this->load->view('vw_publicado');
    }
    function publicarHc($mes,$anio)
    {
        //$this->output->enable_profiler();
        $this->load->model('md_evaluacion');
        
        $this->load->model("md_indicador");
        $hc = $this->md_indicador->um_evaluacion('H.C.');
        
        foreach($hc as $ren)
            $this->md_evaluacion->publicarHc($mes,$anio,$ren);
        $this->md_evaluacion->publicarHc($mes,$anio,"todas");
        $this->load->view('vw_publicado');
    }
    function publicar2Nivel($mes,$anio)
    {
        //$this->output->enable_profiler();
        $this->load->model('md_evaluacion');
        
        $this->load->model("md_indicador");
        $hc = $this->md_indicador->um_evaluacion(array('H.C.','H.G.','H.E.'));
        $hc[] = 'ZSSSA013172';
        //print_r($hc);
        foreach($hc as $ren)
            $this->md_evaluacion->publicar2Nivel($mes,$anio,$ren);
        $this->md_evaluacion->publicar2Nivel($mes,$anio,"todas");
        $this->load->view('vw_publicado');
    }
    function reporteJuris()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
            {
            $mes = $this->input->post('mes');
            $anio = $this->input->post('anio');
            $juris = $this->input->post('juris');
            
            $this->load->model("md_evaluacion");
            $datos['reporteJuris'] = $this->md_evaluacion->reporteJuris($mes,$anio,$juris);
            $this->load->model('md_pagina');
            
            $mes_txt = $this->convertir_mes_txt($mes);
            
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
            
            if($mes_txt != 'ENERO')
                $datos['titulo'] = heading("REPORTE JURISDICCIONAL",1,'class="text-center"').heading($titulo,2,'class="text-center"').heading("ENERO - ".$mes_txt,3,'class="text-center"').heading('<small class="text-center">(Excluye Resultados de las Acciones de Hospitales Comunitarios)</small>',4,'class="text-center"');
            else
                $datos['titulo'] = "REPORTE JURISDICCIONAL".br().$titulo.br().$mes_txt.br()."<h3 class='text-center'>(Excluye Resultados de las Acciones de Hospitales Comunitarios)</h3>";
            
            $datos['pagina'] = new Md_Pagina;
            $datos['reporte'] = "juris";
            $datos['agregarHC'] = 0;
            $datos['mes'] = $mes;
            $datos['anio'] = $anio;
            $datos['juris'] = $juris;
            $datos['botonPdf'] = '<center><a class="btn btn-success" href="'.site_url('evaluacion/reporte_juris_pdf/'.$mes.'/'.$anio.'/'.$juris).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
            $this->load->model('md_informacion');
            
            $datos['corte'] = $mes_txt;
            
            $this->load->view("vw_reporteJuris",$datos);
        }
    }
    function reporteJurisHc()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
            {
            $mes = $this->input->post('mes');
            $anio = $this->input->post('anio');
            $juris = $this->input->post('juris');
            
            $this->load->model("md_evaluacion");
            $datos['reporteJuris'] = $this->md_evaluacion->reporteJurisHc($mes,$anio,$juris);
            $this->load->model('md_pagina');
            
            $mes_txt = $this->convertir_mes_txt($mes);
            
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
            
            if($mes_txt != 'ENERO')
                $datos['titulo'] = heading("REPORTE JURISDICCIONAL Y H.C.",1,'class="text-center"').heading($titulo,2,'class="text-center"').heading("ENERO - ".$mes_txt,3,'class="text-center"').heading("<small>(Incluye Resultados de las Acciones de Hospitales Comunitarios)</small>",4,'class="text-center"');
            else
                $datos['titulo'] = "REPORTE".br().$titulo." ".$mes_txt."<br /><h3 class='text-center'>(Incluye Resultados de las Acciones de Hospitales Comunitarios)</h3>";
            
            $datos['pagina'] = new Md_Pagina;
            $datos['reporte'] = "juris";
            $datos['agregarHC'] = 1;
            $datos['mes'] = $mes;
            $datos['anio'] = $anio;
            $datos['juris'] = $juris;
            $datos['botonPdf'] = '<center><a class="btn btn-success" href="'.site_url('evaluacion/reporte_jurishc_pdf/'.$mes.'/'.$anio.'/'.$juris).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
            $this->load->model('md_informacion');
            
            $datos['corte'] = $mes_txt;
            
            $this->load->view("vw_reporteJuris",$datos);
        }
    }
    function reporteHc()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            $mes = $this->input->post('mes');
            $anio = $this->input->post('anio');
            $hc = $this->input->post('hc');
            
            $this->load->model("md_evaluacion");
            $datos['reporteJuris'] = $this->md_evaluacion->reporteHc($mes,$anio,$hc);
            $this->load->model('md_pagina');
            
            $mes_txt = $this->convertir_mes_txt($mes);
            
            $this->load->model('md_unidad_medica');
            
            if($hc != 'todas')
                $titulo = $this->md_unidad_medica->cluesNombre($hc);
            else
                $titulo = "ESTATAL";
            
            if($mes_txt != 'ENERO')
                $datos['titulo'] = heading("REPORTE PRIMER NIVEL H.C.",1,'class="text-center"').heading($titulo,2,'class="text-center"').heading("ENERO - ".$mes_txt,3,'class="text-center"');
            else
                $datos['titulo'] = "REPORTE".br().$titulo." ".$mes_txt;
            
            $datos['pagina'] = new Md_Pagina;
            $datos['reporte'] = "hc";
            $datos['agregarHC'] = 0;
            $datos['mes'] = $mes;
            $datos['anio'] = $anio;
            $datos['hc'] = $hc;
            $datos['botonPdf'] = '<center><a class="btn btn-success" href="'.site_url('evaluacion/reporte_hc_pdf/'.$mes.'/'.$anio.'/'.$hc).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
            $this->load->model('md_informacion');
            
            $datos['corte'] = $mes_txt;
            
            $this->load->view("vw_reporteJuris",$datos);
        }
    }
    function reporte2Nivel()
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
            $mes = $this->input->post('mes');
            $anio = $this->input->post('anio');
            $hc = $this->input->post('hghehc');
            
            $this->load->model("md_evaluacion");
            $datos['reporteJuris'] = $this->md_evaluacion->reporte2Nivel($mes,$anio,$hc);
            $this->load->model('md_pagina');
            
            $mes_txt = $this->convertir_mes_txt($mes);
            
            $this->load->model('md_unidad_medica');
            
            if($hc != 'todas')
                $titulo = $this->md_unidad_medica->cluesNombre($hc);
            else
                $titulo = "ESTATAL";
            
            if($mes_txt != 'ENERO')
                $datos['titulo'] = heading("REPORTE 2DO NIVEL",1,'class="text-center"').heading($titulo,2,'class="text-center"').heading(" ENERO - ".$mes_txt,3,'class="text-center"');
            else
                $datos['titulo'] = "REPORTE".br().$titulo." ".$mes_txt;
            
            $datos['pagina'] = new Md_Pagina;
            $datos['reporte'] = "2n";
            $datos['agregarHC'] = 0;
            $datos['mes'] = $mes;
            $datos['anio'] = $anio;
            $datos['hc'] = $hc;
            $datos['botonPdf'] = '<center><a class="btn btn-success" href="'.site_url('evaluacion/reporte_2nivel_pdf/'.$mes.'/'.$anio.'/'.$hc).'" target="_blank"><span class="glyphicon glyphicon-cloud-download"></span> VERSION PDF</a></center>';
            $this->load->model('md_informacion');
            
            $datos['corte'] = $mes_txt;
            
            $this->load->view("vw_reporteJuris",$datos);
        }
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
    function reporte_juris_pdf($mes, $anio, $juris)
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
            {
            $this->load->model("md_evaluacion");
            $datos = $this->md_evaluacion->reporteJuris_pdf($mes,$anio,$juris);
            $this->load->model('md_pagina');
            
            $mes_txt = $this->convertir_mes_txt($mes);
            
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
            
            //PARA LOS TITULOS DE LA TABLA
            $titulosTabla[1] = "PROMOCION DE LA SALUD";
            $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
            $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
            $titulosTabla[4] = "REGULACION SANITARIA";
            $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
            //////////////////////////////////                
            
            $this->pdf($datos['datos'],$titulo,$mes_txt,"juris",$titulosTabla,$datos['unidadMedidaDepartamento']);
        }
    }
    function reporte_jurishc_pdf($mes, $anio, $juris)
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            $this->load->model("md_evaluacion");
            $datos = $this->md_evaluacion->reporteJurisHc_pdf($mes,$anio,$juris);
            $this->load->model('md_pagina');
            
            $mes_txt = $this->convertir_mes_txt($mes);
            
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
            
            //PARA LOS TITULOS DE LA TABLA
            $titulosTabla[1] = "PROMOCION DE LA SALUD";
            $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
            $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
            $titulosTabla[4] = "REGULACION SANITARIA";
            $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
            //////////////////////////////////                
            
            $this->pdf($datos['datos'],$titulo,$mes_txt,"juris",$titulosTabla,$datos['unidadMedidaDepartamento'],1);
        }
    }    
    function reporte_hc_pdf($mes, $anio, $hc)
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            $this->load->model("md_evaluacion");
            $datos = $this->md_evaluacion->reporteHc_pdf($mes,$anio,$hc);
            $this->load->model('md_pagina');
            
            $mes_txt = $this->convertir_mes_txt($mes);
            
            $this->load->model('md_indicador');
            //$titulo = $this->md_indicador->um_txt($hc);
            
            //PARA LOS TITULOS DE LA TABLA
            $titulosTabla[1] = "PROMOCION DE LA SALUD";
            $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
            $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
            $titulosTabla[4] = "REGULACION SANITARIA";
            $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
            //////////////////////////////////                
            
            $this->pdf($datos['datos'],$hc,$mes_txt,"hc",$titulosTabla,$datos['unidadMedidaDepartamento']);
        }
    }
    function reporte_2nivel_pdf($mes, $anio, $hc)
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
            {
            $this->load->model("md_evaluacion");
            $datos = $this->md_evaluacion->reporte2Nivel_pdf($mes,$anio,$hc);
            $this->load->model('md_pagina');
            
            $mes_txt = $this->convertir_mes_txt($mes);
            
            $this->load->model('md_indicador');
            //$titulo = $this->md_indicador->um_txt($hc);
            
            //PARA LOS TITULOS DE LA TABLA
            $titulosTabla[1] = "PROMOCION DE LA SALUD";
            $titulosTabla[2] = "PLANIFICACION FAMILIAR Y SALUD DE LA MUJER";
            $titulosTabla[3] = "VIGILANCIA EPIDEMIOLOGICA, PREVENCION Y CONTROL DE ENFERMEDADES";
            $titulosTabla[4] = "REGULACION SANITARIA";
            $titulosTabla[5] = "ATENCION MEDICA DE PRIMER NIVEL";
            //////////////////////////////////                
            
            $this->pdf($datos['datos'],$hc,$mes_txt,"2n",$titulosTabla,$datos['unidadMedidaDepartamento']);
        }
    }
    function pdf($datos,$hc,$mes,$reporte,$titulosProgramas = 0,$unidadMedidaDepartamento = 0,$incluyeHc = 0)
    {
        if($reporte != '2n' && $reporte != 'hc')
            echo "";
        else
            $hc_txt = $this->md_indicador->um_txt($hc);
        
        //$mes_txt = $this->convertir_mes_txt($mes);
        
        // Se carga la libreria fpdf
        $this->load->library('pdf');
 
        // Creacion del PDF
 
        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new Pdf($mes);
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
 
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("HOSPITALES");
        $this->pdf->SetLeftMargin(10);
        $this->pdf->SetRightMargin(10);
        $this->pdf->SetFillColor(200,200,200);
      
        $this->pdf->SetFont('Arial', 'B', 20);
        // Movernos a la derecha
        $this->pdf->Cell(80);
        // Título
        if($reporte == "hc")
        {
            if($hc_txt == 'TODOS')
                if($mes != 'ENERO')
                {
                    $this->pdf->Cell(100,10,"REPORTE PRIMER NIVEL",0,1,'C');
                    $this->pdf->Cell(260,10,$hc_txt." LOS H.C.",0,1,'C');
                    $this->pdf->Cell(260,10,"ENERO - ".$mes,0,0,'C');
                }
                else
                    $this->pdf->Cell(100,10,"REPORTE PRIMER NIVEL".$hc_txt." LOS H.C. ".$mes,0,0,'C');
            else
                if($mes != 'ENERO')
                {
                    $this->pdf->Cell(100,10,"REPORTE PRIMER NIVEL",0,1,'C');
                    $this->pdf->Cell(260,10,$hc_txt,0,1,'C');
                    $this->pdf->Cell(260,10,"ENERO - ".$mes,0,0,'C');
                }
                else
                    $this->pdf->Cell(100,10,"REPORTE PRIMER NIVEL".$hc_txt." ".$mes,0,0,'C');
        }
        if($reporte == "2n")
            if($mes != 'ENERO')
            {
                $this->pdf->Cell(100,10,"REPORTE SEGUNDO NIVEL ",0,1,'C');
                $this->pdf->Cell(260,10,$hc_txt,0,1,'C');
                $this->pdf->Cell(260,10,"ENERO - ".$mes,0,0,'C');
            }
            else
                $this->pdf->Cell(100,10,"REPORTE SEGUNDO NIVEL ".$hc_txt." ".$mes,0,0,'C');
        if($reporte == "juris")
            if($mes != 'ENERO'){
                $this->pdf->Cell(100,10,"REPORTE JURISDICCIONAL",0,1,'C');
                $this->pdf->Cell(260,10,$hc,0,1,'C');
                $this->pdf->Cell(260,10," ENERO - ".$mes,0,0,'C');
            }
            else
                $this->pdf->Cell(100,10,"REPORTE ".$hc." ".$mes,0,0,'C');
        
        if($titulosProgramas != 0){
            
            if($reporte != '2n' && $reporte != 'hc')
            {
                $this->pdf->Ln(10);
                $this->pdf->SetFont('Arial', 'B', 8);
                if($incluyeHc)
                    $this->pdf->Cell(258,10,"(Incluye Resultados de las Acciones de Hospitales Comunitarios)",0,0,'C');
                else
                    $this->pdf->Cell(258,10,"(Excluye Resultados de las Acciones de Hospitales Comunitarios)",0,0,'C');
            }
        }
        
        // Salto de línea
        $this->pdf->Ln(10);
        
        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 8);
        
        /*
         * TITULOS DE COLUMNAS
         *
         * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
         */
 
        if($reporte == "2n")
            $this->pdf->Cell(20,7,'','',0,'C');
        $this->pdf->Cell(8,7,'H','TBL',0,'C','1');
        $this->pdf->Cell(60,7,'PROGRAMA','TBL',0,'C','1');
        if($reporte != '2n')
            $this->pdf->Cell(50,7,'DESCRIPCION','TBL',0,'C','1');
        $this->pdf->Cell(25,7,'UNIDAD MEDIDA','TBL',0,'C','1');
        $this->pdf->Cell(15,7,'LOGRO','TBL',0,'C','1');
        $this->pdf->Cell(20,7,'META MES','TBL',0,'C','1');
        $this->pdf->Cell(25,7,'% META-LOGRO','TBLR',0,'C','1');
        $this->pdf->Cell(5,7,'S','TBL',0,'C','1');
        $this->pdf->Cell(25,7,'META ANUAL','TBL',0,'C','1');
       // $this->pdf->Cell(5,7,'S','TBL',0,'C','1');
        $this->pdf->Cell(25,7,'% META-LOGRO','TBLR',0,'C','1');
        $this->pdf->Ln(7);
        
        foreach($datos as $ren)
        {
            if($titulosProgramas != 0)
            {
                //echo "titulo: ".$titulosProgramas[$unidadMedidaDepartamento[$ren['idInd']]]."<br />";
                //PARA LOS TITULOS DEL DEPARTAMENTO
                if($titulosProgramas[$unidadMedidaDepartamento[$ren['idInd']]] != ""){
                    $this->pdf->SetFillColor(0,255,0);
                    $this->pdf->Cell(130,5,$titulosProgramas[$unidadMedidaDepartamento[$ren['idInd']]],'TBL',0,'L',1);
                    
                    $titulosProgramas[$unidadMedidaDepartamento[$ren['idInd']]] = "";
                    $this->pdf->Ln(5);
                    $this->pdf->SetFillColor(200,200,200);
                }
            }
            
            $ren['programa'] = substr($ren['programa'],0,20);
            $ren['indicador'] = substr($ren['indicador'],0,20);
            $ren['descripcion'] = substr($ren['descripcion'],0,10);

            if($reporte == "2n")
                $this->pdf->Cell(20,7,'','',0,'C');
                
            $this->pdf->Cell(8,5,$ren['h'],'TBL',0,'C','0');
            $this->pdf->Cell(60,5,$ren['programa'],'TBL',0,'C','0');
            if($reporte != '2n')
            $this->pdf->Cell(50,5,$ren['indicador'],'TBL',0,'C','0');            
            $this->pdf->Cell(25,5,$ren['descripcion'],'TBL',0,'C','0');
            $this->pdf->Cell(15,5,$ren['total'],'TBL',0,'R','0');
            $this->pdf->Cell(20,5,$ren['meta_parcial'],'TBL',0,'R','0');
            
            $this->pdf->Cell(25,5,$ren['meta_logro_p'],'TBLR',0,'R','0');
            
            
            if(strstr($ren['s_parcial'],'rojo'))
                $this->pdf->SetFillColor(255,0,0);
            else
                if(strstr($ren['s_parcial'],'verde'))
                    $this->pdf->SetFillColor(0,255,0);
                else
                    if(strstr($ren['s_parcial'],'amarillo'))
                        $this->pdf->SetFillColor(255,216,0);
                    else
                        if(strstr($ren['s_parcial'],'blanco'))
                            $this->pdf->SetFillColor(255,255,255);
                        else
                            $this->pdf->SetFillColor(0,0,255);
            
                    
            $this->pdf->Cell(5,5,'','TBL',0,'C','1');
            $this->pdf->SetFillColor(200,200,200);
            
            $this->pdf->Cell(25,5,$ren['meta'],'TBL',0,'R','0');
            /*
            if(strstr($ren['s'],'rojo'))
                $this->pdf->SetFillColor(255,0,0);
            else
                if(strstr($ren['s'],'verde'))
                    $this->pdf->SetFillColor(0,255,0);
                else
                    if(strstr($ren['s'],'amarillo'))
                        $this->pdf->SetFillColor(255,216,0);
                    else
                        $this->pdf->SetFillColor(0,0,255);
            $this->pdf->Cell(5,5,'','TBL',0,'R','1');
            
            $this->pdf->SetFillColor(200,200,200);*/
            
            $this->pdf->Cell(25,5,$ren['meta_logro'],'TBLR',0,'R','0');

            $this->pdf->Ln(5);
        }
        //$this->pdf->Ln(7);
        
        /*
         * Se manda el pdf al navegador
         *
         * $this->pdf->Output(nombredelarchivo, destino);
         *
         * I = Muestra el pdf en el navegador
         * D = Envia el pdf para descarga
         *
         */
        $this->pdf->Output();
    }
}

?>