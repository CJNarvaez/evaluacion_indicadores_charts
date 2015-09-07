<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_manejo extends CI_Controller {

	public function index()
	{
		
	}
	public function borrar_usuario($id)
	{
		$this->load->library('ion_auth');
		if ($this->ion_auth->delete_user($id)) {
			echo "Borrado Correctamente";
		}
		else
			echo "Error al Borrar";
	}

}

/* End of file usuarios_manejo.php */
/* Location: ./application/controllers/usuarios_manejo.php */