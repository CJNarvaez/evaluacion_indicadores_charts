<?php

class Indicador extends CI_Controller 
{
    function listado_indicadores($prog,$nivel)
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            if($nivel == '2do')
                $this->unidad_medida($prog,$nivel);
            else{
                //$this->output->enable_profiler();
                //$this->load->helpers(array('html'));
                $this->load->model('md_indicador');
                $this->load->library(array('table'));
                
                //CONSULTA EL LISTADO DE INDICADORES
                $datos['ind'] = $this->md_indicador->eval_indicador($prog,'indicador',$nivel);
                $datos['nivel'] = $nivel;
                //print_r($datos);
                
                //$mnu['selec'] = 'captura';
                //$this->load->view('vw_encabezado');
                //$this->load->view('vw_menu',$mnu);
                $this->load->model('Md_pagina');
                $datos['pagina'] = new Md_Pagina;
                $this->load->view("vw_indicador",$datos);
            }
        }
    }
    function unidad_medida($ind,$nivel)
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
            $this->load->model('md_indicador');
            $this->load->library(array('table'));
            
            //CONSULTA LAS UNIDADES DE MEDIDA QUE PERDENECEN A LA SESION
            $datos['ind'] = $this->md_indicador->eval_indicador($ind,'medida',$nivel);
            $datos['nivel'] = $nivel;
            //print_r($datos);
            
            //print_r($datos['ind']);
            //$datos['ind'] = $ind;
            
            //$mnu['selec'] = 'captura';
            //$this->load->view('vw_encabezado');
            //$this->load->view('vw_menu',$mnu);
            $this->load->model('Md_pagina');
            $datos['pagina'] = new Md_Pagina;
            $this->load->view("vw_medida",$datos);
        }
    }
    function eval_reporta_jur ($unidad_medida,$indicador,$nivel)
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
            //print_r($nivel);
            $this->load->model('md_indicador');
            $this->load->library(array('table'));
            $this->load->helpers(array('form'));
            $datos['unidad_medida'] = $unidad_medida;
            $datos['indicador'] = $indicador;
            $datos['jurisdicciones'] = $this->md_indicador->jurisdicciones($nivel);
            
            $this->db->where('id_medida',$unidad_medida);
            if($nivel != '2do')
                $consulta = $this->db->get('vw_eval_estruct_1er');
            else
                $consulta = $this->db->get('vw_eval_estruct_2do');
            foreach($consulta->result() as $ren)
            {
                $datos['prog'] = $ren->descripcion_programa;
                if($nivel != '2do')
                    $datos['ind'] = $ren->descripcion_ind;
                $datos['uni_med'] = $ren->descripcion_medida;
            }
            
            $datos['nivel'] = $nivel;
            //print_r($datos);
            //$mnu['selec'] = 'captura';
            //$this->load->view('vw_encabezado');
            //$this->load->view('vw_menu',$mnu);
            $this->load->model('Md_pagina');
            $datos['pagina'] = new Md_Pagina;
            $this->load->view('vw_formulario_eval_juris',$datos);
        }     
    }
    function eval_guarda_juris($nivel)
    {
       // $this->output->enable_profiler();
        $this->load->model('md_indicador');
        //echo $this->input->post('mes');
        //echo $this->input->post('anio');
        //echo $this->input->post('unidad_medida');
        $datos['jurisdicciones'] = $this->md_indicador->jurisdicciones($nivel);
        foreach ( $datos['jurisdicciones'] as $ren)
        {
            //echo $this->input->post('jur_'.$ren['ID']);
            if($nivel == '2do')
                $guardar[] = array(
                            'id_unidad' => $ren['id'],
                            'logro' => $this->input->post('jur_'.$ren['id']),
                            'mes' => $this->input->post('mes'),
                            'anio' => $this->input->post('anio'),
                            'id_unidad_medida' => $this->input->post('unidad_medida')
                            );
            else
                $guardar[] = array(
                            'id_unidad' => $ren['id'],
                            'id_acc' => $this->input->post('indicador'),
                            'logro' => $this->input->post('jur_'.$ren['id']),
                            'mes' => $this->input->post('mes'),
                            'anio' => $this->input->post('anio'),
                            'id_unidad_medida' => $this->input->post('unidad_medida')
                            );
        }
       // print_r($guardar);
        if($this->md_indicador->eval_guardar_juris($guardar,$nivel) == $this->db->_error_number(1062))
            exit('NO SE GUARDARON LOS DATOS');
        else
            echo('GUARDADO CORRECTAMENTE');
    }
}

?>