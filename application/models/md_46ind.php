<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_46ind extends CI_Model
{
    public $nombre,$total,$mes,$anio,$num_vars,$den_vars,$var_nom,$numerador,$denominador,$juris;
    public function __construct()
    {
        parent ::__construct();
    }
    
    /**
     * AGREGA UN INDICADOR DEL 'SIS' AL CALCULO
     * @param STR ej. numerador||"denominador 
     */
    public function agregar($num_den,$variable)
    {
        if( strlen($variable) < 4 )
        {
            $variable_completa = $this->var_nom.$variable;
        }
        else
        {
            $this->var_nom = substr($variable,0,3);
            $variable_completa = $variable; 
        }
        if($num_den == 'numerador')
            $this->num_vars[] = $variable_completa;
        else
            $this->den_vars[] = $variable_completa;
    }
    
    /**
     * CONSULTA LOS VALORES EN LA BD
     * @param STR ej. num || den
     */
    public function calcular_num ($num_den)
    {
        $this->db->select_sum('logro','logro');
        if($num_den == 'num')
            $this->db->where_in('nombre',$this->num_vars);
        else
            $this->db->where_in('nombre',$this->den_vars);
        $this->db->where('mes <=',$this->mes);
        $this->db->where('anio',$this->anio);
        $this->db->where('cve_jur',$this->juris);
        $this->db->group_by('anio');
        $consulta = $this->db->get('vw_sis');
        //print_r($consulta->result_array());
        foreach($consulta->result() as $ren)
        {
            if($num_den == 'num')
                $this->numerador = $ren->logro;
            else
                $this->denominador = $ren->logro;
        } 
    }
}

class nac_x_cesarea extends md_46ind
{
    public $hospital,$cesareas,$nacimientos;
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "NACIMIENTOS POR CESAREA";
    }
    
    public function reporte()
    {
        //  $this->output->enable_profiler();
        
        //  formula para el calculo "cesareas/nacimientos x 100"
        //  el indicador 24 es partos distocicos
        $this->db->select_sum('dato');
        $this->db->group_by('id_ind');
        $this->db->where('id_ind',24);
        $this->db->where('mes <=',$this->mes);
        $this->db->where('anio',$this->anio);
        $this->db->where('id_um',$this->hospital);
        $consulta = $this->db->get('saeh_reporte');
        
        foreach($consulta->result() as $ren)
            $this->numerador = $ren->dato;
        
        //  el indicador 22 es partos
        $this->db->select_sum('dato');
        $this->db->group_by('id_ind');
        $this->db->where('id_ind',22);
        $this->db->where('mes <=',$this->mes);
        $this->db->where('anio',$this->anio);
        $this->db->where('id_um',$this->hospital);
        $consulta = $this->db->get('saeh_reporte');
        
        foreach($consulta->result() as $ren)
            $this->denominador = $ren->dato;
        
        if( $this->denominador != 0)
            $this->total = $this->numerador / $this->denominador * 100;
        else
            $this->total = 0;
    }
}

class prom_cons_pre_x_emb extends Md_46ind
{    
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "PROMEDIO DE CONSULTA PRENATAL POR EMBARAZADA";
        
        //PARA VER EL TOTAL DE CONSULTAS
        $this->db->where('id',1);
        $consulta = $this->db->get('46ind_ind');
        foreach($consulta->result() as $ren)
        {
            $variable = explode(',',$ren->calculo);
            foreach($variable as $var)
                $this->agregar('numerador',$var);
        }
        
        //PARA VER CONSULTAS 1ERA VEZ POR EMBARAZADA
        $this->db->where('id',2);
        $consulta = $this->db->get('46ind_ind');
        foreach($consulta->result() as $ren)
        {
            $variable = explode(',',$ren->calculo);
            foreach($variable as $var)
                $this->agregar('denominador',$var);
        }
    }
    public function __destruct()
    {
        echo "destruyendo";
    }
    public function reporte()
    {
        //$this->output->enable_profiler();        
        //PARA CALCULAR TOTAL DE CONSULTAS POR EMBARAZADA
        $this->calcular_num('num');
        
        //PARA CALCULAR CONSULTAS POR EMBARAZADA 1ERA VEZ
        $this->calcular_num('den');
            
        $this->total = $this->numerador / $this->denominador;
    }
}
class usuarias_act_pf extends Md_46ind
{
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "USUARIAS ACTIVAS DE P.F. X 100 M.E.F.U.";
        
        //PARA USUARIAS ACTIVAS
        $this->db->where('id',3);
        $consulta = $this->db->get('46ind_ind');
        foreach($consulta->result() as $ren)
        {
            $variable = explode(',',$ren->calculo);
            foreach($variable as $var)
                $this->agregar('numerador',$var);
        }
    }
    public function reporte()
    {
        //$this->output->enable_profiler();        
        //PARA CALCULAR TOTAL DE CONSULTAS POR EMBARAZADA
        $this->calcular_num('num');
        
        //PARA M.E.F.
        $this->db->where('anio',$this->anio);
        $this->db->where('juris',$this->juris);
        $consulta = $this->db->get('46ind_mef');
        foreach($consulta->result() as $ren)
            $this->denominador = $ren->dato;

        if( $this->denominador != 0)
            $this->total = $this->numerador / $this->denominador * 100;
        else
            $this->total = 0;
    }
}
class Prom_diario_consulta_x_medico extends Md_46ind
{
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "PROMEDIO DIARIO DE CONSULTA POR MEDICO";
        
        //PARA TOTAL DE CONSULTAS
        $this->db->where('id',4);
        $consulta = $this->db->get('46ind_ind');
        foreach($consulta->result() as $ren)
        {
            $variable = explode(',',$ren->calculo);
            foreach($variable as $var)
                $this->agregar('numerador',$var);
        }
    }
    public function reporte()
    {
        //$this->output->enable_profiler();        
        //PARA CALCULAR TOTAL DE CONSULTAS POR EMBARAZADA
        $this->calcular_num('num');
        
        //PARA M.E.F.
        $this->db->where('anio',$this->anio);
        $this->db->where('juris',$this->juris);
        $consulta = $this->db->get('46ind_mef');
        foreach($consulta->result() as $ren)
            $this->denominador = $ren->dato;

        if( $this->denominador != 0)
            $this->total = $this->numerador / $this->denominador * 100;
        else
            $this->total = 0;
    }
}
?>