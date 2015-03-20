<?php

class MD_usuarios extends CI_Model 
{
    function responsable ($resp,$nivel)
    {
        $this->db->distinct();
        //echo $resp;
        $this->db->where('propietario',$resp);
        if($nivel == 'juris')
            $consulta = $this->db->get('eval_responsables_fteinf');
        if($nivel == '1er')
            $consulta = $this->db->get('eval_responsables_fteinf_1er');
        if($nivel == '2do')
            $consulta = $this->db->get('eval_responsables_fteinf_2do');
        return $consulta->result_array();
    }
    function login($username, $password)
    {
       // Esta función recibe como parámetros el nombre de usuario y password
        //$this -> db -> select('usuario, id'); //Indicamos los campos que usaremos del resultado de la consulta
        //$this -> db -> from('usuarios'); // indicamos la tabla a usar
        $this -> db -> where('usuario', $username ); // Indicamos que va a buscar el nombre de usuario
        $this -> db -> where('pass', MD5($password) ); // Indicamos que va a buscar el password con MD5
        $this -> db -> limit(1);
               // Solo deberá de existir un usuario

        $query = $this -> db -> get('usuarios');
               // Obtenemos los resultados del query

        if($query -> num_rows() == 1)
        {
            /*$visitas = $this -> db -> get('contador');
            foreach($visitas->result() as $row)
            {
                $cuenta = $row->visitas;
                $cuenta++;
                $data = array(
                               'visitas' => $cuenta
                            );
                $this->db->update('contador', $data);   
            }*/
            return $query->result();
                       // Existen nombre de usuario y contraseña.
        }
        else
        {
            return false;
                      // No existe nombre de usuario o contraseña.
        }

    }
    function bitacora($datos)
    {
        $this->db->insert('bitacora',$datos);
    }
}

?>