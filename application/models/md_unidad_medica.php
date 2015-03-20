<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_unidad_medica extends CI_Model
{
    public $id;
    public $clues;
    public $nombre;
    public $tipologia;
    public $cve_ent;
    public $nom_ent;
    public $cve_jur;
    public $nom_jur;
    public $cve_mun;
    public $nom_mun;
    public $cve_loc;
    public $nom_loc;
    public $camas_cirugia;
    public $camas_med_interna;
    public $camas_pediatria;
    public $camas_ginecologia;
    public $camas_trauma;
    public $camas_otros;
    public $camas_total;   
    
    public function __construct($id = NULL)
    {
       // $this->output->enable_profiler();
        if($id != NULL)
        {
            if($id == 'HC')
                $id = "H.C.";
                elseif($id == 'HG')
                        $id = "H.G.";
                                    
            $this->id = $id;                                 
            
            if($id != 'H.G.' && $id != 'H.C.')
                $this->db->where('id',$id);
            else
            {
                $this->db->select('clues,nombre,tipologia,cve_ent,nom_ent,cve_jur,nom_jur,cve_mun,nom_mun,cve_loc,nom_loc');
                $this->db->select_sum('camas_cirugia');
                $this->db->select_sum('camas_med_interna');
                $this->db->select_sum('camas_pediatria');
                $this->db->select_sum('camas_ginecologia');
                $this->db->select_sum('camas_trauma');
                $this->db->select_sum('camas_otros');
                $this->db->select_sum('camas_total');
                $this->db->where('tipologia',$id);
            }
            $consulta = $this->db->get('um');
            
            foreach($consulta->result() as $um)
            {
                $this->clues = $um->clues;
                $this->nombre = $um->nombre;
                $this->tipologia = $um->tipologia;
                $this->cve_ent = $um->cve_ent;
                $this->nom_ent = $um->nom_ent;
                $this->cve_jur = $um->cve_jur;
                $this->nom_jur = $um->nom_jur;
                $this->cve_mun = $um->cve_mun;
                $this->nom_mun = $um->nom_mun;
                $this->cve_loc = $um->cve_loc;
                $this->nom_loc = $um->nom_loc;
                $this->camas_cirugia = $um->camas_cirugia;
                $this->camas_med_interna = $um->camas_med_interna;
                $this->camas_pediatria = $um->camas_pediatria;
                $this->camas_ginecologia = $um->camas_ginecologia;
                $this->camas_trauma = $um->camas_trauma;
                $this->camas_otros = $um->camas_otros;
                $this->camas_total =$um->camas_total;                                        
            }
        }
    }
    public function menu_desplegable($condicion = NULL)
    {
        $this->db->where_in('tipologia',$condicion);
        //$this->db->where('nombre <>','UNEME DE URGENCIAS ZACATECAS');
        $consulta = $this->db->get('um');
        $resultado = array();
        foreach($consulta->result() as $ren)
        {
            $resultado[$ren->id] = $ren->nombre;
        }
        return $resultado;
    }
    public function cluesNombre($clues)
    {
        $this->db->where('clues',$clues);
        $consulta = $this->db->get('um');
        foreach($consulta->result() as $ren)
            $nombre = $ren->nombre;
        return $nombre;
    }
}

?>