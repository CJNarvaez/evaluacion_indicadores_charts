<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 * 
 * Esta Clase es para consultar los Años y meses reportados en la BD para llenar los combos
 * de la pagina reporte
 */

class Md_reporte extends CI_Model
{
    public $anios,$meses;
    
    public function __construct($administrador = 0)
    {
        $this->db->select('mes, anio');
        $this->db->distinct();
        $this->db->order_by('mes');
        if($administrador)
            $consulta = $this->db->get('vw_evaluacion_juris');
        else
            $consulta = $this->db->get('vw_reportejuris');
        
        $anio_inicial = 0;
        foreach($consulta->result() as $ren)
        {
            if($ren->anio != $anio_inicial)
                $this->anios[$ren->anio] = $ren->anio;
            
            switch($ren->mes)
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
            
            $this->meses[$ren->mes] = $mes_txt;
            $anio_inicial = $ren->anio;
        } 
    }
    function regresar_datos()
    {
        $datos = array(
                        'meses' => $this->meses,
                        'anios' => $this->anios
                        );
        return $datos;
    }
}
?>