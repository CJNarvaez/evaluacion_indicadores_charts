<?php
class Md_informacion extends CI_Model{
    function fecha_corte($anio)
    {
       // $this->output->enable_profiler();
        $this->db->select('mes');
        $this->db->distinct();
        $this->db->where('anio',$anio);
        $this->db->order_by('mes');
        $consulta = $this->db->get('reporte_ant');
        foreach($consulta->result_array() as $mes)
            $corte = $mes['mes'];
            
        switch($corte)
        {
            case '1': return 'ENERO';
            case '2': return 'FEBRERO';
            case '3': return 'MARZO';
            case '4': return 'ABRIL';
            case '5': return 'MAYO';
            case '6': return 'JUNIO';
            case '7': return 'JULIO';
            case '8': return 'AGOSTO';
            case '9': return 'SEPTIEMBRE';
            case '10': return 'OCTUBRE';
            case '11': return 'NOVIEMBRE';
            case '12': return 'DICIEMBRE';
        }
    }
    public function mesActual()
    {
        $this->db->select_max('anio');
        $consulta = $this->db->get('reporte');
        foreach($consulta->result() as $ren)
            $anio = $ren->anio;
        
        $this->db->select_max('mes');
        $this->db->where('anio',$anio);
        $consulta = $this->db->get('reporte');
        foreach($consulta->result() as $ren)
            $mesActual = $ren->mes;
        
        //$mesActual = substr($mesActual,4,2);
        
        return $mesActual;
        /*switch($mesActual)
        {
            case 1: return "ENERO";
                    break;
            case 2: return "FEBRERO";
                    break;
            case 3: return "MARZO";
                    break;
            case 4: return "ABRIL";
                    break;
            case 5: return "MAYO";
                    break;
            case 6: return "JUNIO";
                    break;
            case 7: return "JULIO";
                    break;
            case 8: return "AGOSTO";
                    break;
            case 9: return "SEPTIEMBRE";
                    break;
            case 10: return "OCTUBRE";
                    break;
            case 11: return "NOVIEMBRE";
                    break;
            case 12: return "DICIEMBRE";
                    break;
        }*/
    }
    public function mesActualPublicado($nivel)
    {
       // $this->output->enable_profiler();
        $this->db->select_max('anioMes');
        if($nivel == 'juris')
            $consulta = $this->db->get('evaluacionvalidadajuris');
        if($nivel == 'hc')
            $consulta = $this->db->get('evaluacionvalidadaprimernivel');
        if($nivel == 'jurishc')
            $consulta = $this->db->get('evaluacionvalidadajurishc');
        if($nivel == '2nivel' || $nivel == '2n')
            $consulta = $this->db->get('evaluacionvalidadasegundonivel');
        foreach($consulta->result() as $ren)
            $mesActual = substr($ren->anioMes,4) ;
        
        //$mesActual = substr($mesActual,4,2);
        
        return $mesActual;
        
    }
}
?>