<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_46ind extends CI_Model
{
    public $nombre, $total;
    public function __construct()
    {
        parent ::__construct();
    } 
}

class nac_x_cesarea extends md_46ind
{
    public $hospital,$cesareas,$nacimientos,$mes,$anio;
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "NACIMIENTOS POR CESAREA";
    }
    public function reporte()
    {
        $this->db->where('id_ind',43);
        $this->db->where('mes',$this->mes);
        $this->db->where('anio',$anio->anio);
        $consulta = $this->db->get('saeh_reporte');
        
        foreach($consulta->result() as $ren)
            $this->db->cesareas = $ren->dato;
        
        
        $this->db->where('id_ind',22);
        $this->db->where('mes',$this->mes);
        $this->db->where('anio',$anio->anio);
        $consulta = $this->db->get('saeh_reporte');
        
        foreach($consulta->result() as $ren)
            $this->db->nacimientos = $ren->dato;
        
        $this->total = $this->cesareas / $this->nacimientos * 100;
    }
}

?>