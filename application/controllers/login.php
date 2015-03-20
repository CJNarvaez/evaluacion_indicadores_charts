<?php
class Login extends CI_Controller{
    function verificar(){
        //$this->output->enable_profiler(TRUE);
        date_default_timezone_set('America/Monterrey');
        
        $this->load->helpers(array('form','html','url'));
        $this->load->library('form_validation');
        $data = array();
            
        $this->form_validation->set_rules('username','USUARIO','required');
        $this->form_validation->set_rules('password','PASSWORD','required');
        $this->form_validation->set_message('required', 'EL CAMPO %s NO PUEDE SER VACIO');
        
        if($this->form_validation->run() == FALSE)
        { //Si la informacion no fue correctamente enviada
            $this->load->view('vw_login'); //Carga la vista de login
            $this->load->view('vw_footer');
        }
        else
        {
            $username = $this -> input -> post('username');
            $password = $this -> input -> post('password');
            
            
            
            $this->load->model('md_usuarios');
            
            $result = $this -> md_usuarios -> login($username, $password); //Llamamos a la función login dentro del modelo common mandando los argumentos password y username

            if($result)
            { //login exitoso
                $sess_array = array();
                foreach($result as $row)
                {
                    $tiempo = date("d-m-Y g:i a");
                    $sess_array = array(
                        'id' => $row -> id,
                        'username' => $row -> usuario,
                        'fecha' => $tiempo,
                        'accion' => 'inicio sesion'
                    );
                    //print_r($sess_array);
                    echo "LOGIN.PHP";
                    $this->md_usuarios->bitacora($sess_array);

                    $this -> session -> set_userdata($sess_array); //Iniciamos una sesión con los datos obtenidos de la base.
                }
                redirect('principal/inicio', 'refresh');
               //echo $this->session->userdata('username');
               // $this->load->view('vw_principal');
            }
            else
            { // La validación falla
                $data['error'] = 'Nombre de usuario / Password Incorrecto'; //Error que será enviado a la vista en forma de arreglo
                $this -> load -> view('vw_login', $data); //Cargamos el mensaje de error en la vista.
                $this->load->view('vw_footer');
            }
        }
    }
}

?>