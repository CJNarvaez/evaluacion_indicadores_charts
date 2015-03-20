<?php

class MD_exporta extends CI_Model 
{
    function Exporta ($nivel)
    {
        if($nivel == 'juris')
            $consulta = $this->db->get('vw_exporta');
        if($nivel == '1er')
            $consulta = $this->db->get('vw_exporta_1er');
        if($nivel == '2do')
            $consulta = $this->db->get('vw_exporta_2do');
        return $consulta->result_object();
    }
}

?>