<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2015
 */

class Portal extends CI_Controller 
{
    /**
     * PORTADA
     */
    function index()
    {
        $this->load->view('vw_portada');
    }
    /**
     * INICIO DEL SISTEMA, si no hay sesion iniciada
     * manda al login
     */
    function inicio()
    {
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
        else
        {
            echo "entrar";
        }
    }
}

?>