<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

/**
 * Md_ind_hosp
 * 
 * @package Indicadores
 * @author Cristhian Narvaez
 * @copyright 2014
 * @access public
 */
Class Md_ind_hosp extends CI_Model
{
    //private $id;
    //private $nombre;
    private $um = 0;
    private $mes = 0;
    private $anio = 0;
    public $dias_pac_men = 0;
    public $dias_pac_acu = 0;
    public $egresos_men = 0;
    public $egresos_acu = 0;
    public $defun_men = 0;
    public $defun_acu = 0;
    public $defun_48hrs_men = 0;
    public $defun_48hrs_acu = 0;
    
    public $med_int_dias_pac_men = 0;
    public $med_int_dias_pac_acu = 0;
    public $med_int_egresos_men = 0;
    public $med_int_egresos_acu = 0;
    public $med_int_defun_men = 0;
    public $med_int_defun_acu = 0;
    public $med_int_defun_48hrs_men = 0;
    public $med_int_defun_48hrs_acu = 0;
    
    public $pediatria_dias_pac_men = 0;
    public $pediatria_dias_pac_acu = 0;
    public $pediatria_egresos_men = 0;
    public $pediatria_egresos_acu = 0;
    public $pediatria_defun_men = 0;
    public $pediatria_defun_acu = 0;
    public $pediatria_defun_48hrs_men = 0;
    public $pediatria_defun_48hrs_acu = 0;
    
    public $ginecologia_dias_pac_men = 0;
    public $ginecologia_dias_pac_acu = 0;
    public $ginecologia_egresos_men = 0;
    public $ginecologia_egresos_acu = 0;
    public $ginecologia_defun_men = 0;
    public $ginecologia_defun_acu = 0;
    public $ginecologia_defun_48hrs_men = 0;
    public $ginecologia_defun_48hrs_acu = 0;
    
    public $trauma_dias_pac_men = 0;
    public $trauma_dias_pac_acu = 0;
    public $trauma_egresos_men = 0;
    public $trauma_egresos_acu = 0;
    public $trauma_defun_men = 0;
    public $trauma_defun_acu = 0;
    public $trauma_defun_48hrs_men = 0;
    public $trauma_defun_48hrs_acu = 0;
    
    /**
     * Md_ind_hosp::__construct()
     * 
     * @param mixed $ind
     * @return void
     */
    public function __construct($um = NULL, $mes = NULL, $anio = NULL)
    {
        if($um != NULL)
        {
            //ESTABLECE LOS VALORES
            //$this->id = $ind;            
            $this->um = $um;
            $this->mes = $mes;
            $this->anio = $anio;
            $acu = 0;
            $hc = 0;
            if($um == 'H.C.' OR $um == 'H.G.')
                $hc = $um;
            
            //CONSULTA LA BD BUSCANDO EL INDICADOR
            //$this->db->where('id',$this->id);
            //$consulta = $this->db->get('saeh_ind');
            
            //GUARDA EL NOMBRE DEL INDICADOR
            //foreach($consulta->result() as $ren)
            //    $this->nombre = $ren->saeh_ind_nombre;
            
            //VARIABLES PARA LOS ID'S DE LOS INDICADORES
            $dias_pac = 1;
            $egresos = 6;
            $def = 11;
            $def_48 = 16;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,1,0,$hc);
            $this->calcular($dias_pac,1,1,$hc);        
            $this->calcular($egresos,1,0,$hc);
            $this->calcular($egresos,1,1,$hc);
            $this->calcular($def,1,0,$hc);
            $this->calcular($def,1,1,$hc);
            $this->calcular($def_48,1,0,$hc);
            $this->calcular($def_48,1,1,$hc);
            
            $dias_pac++;
            $egresos++;
            $def++;
            $def_48++;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,2,0,$hc);
            $this->calcular($dias_pac,2,1,$hc);        
            $this->calcular($egresos,2,0,$hc);
            $this->calcular($egresos,2,1,$hc);
            $this->calcular($def,2,0,$hc);
            $this->calcular($def,2,1,$hc);
            $this->calcular($def_48,2,0,$hc);
            $this->calcular($def_48,2,1,$hc);
            
            $dias_pac++;
            $egresos++;
            $def++;
            $def_48++;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,3,0,$hc);
            $this->calcular($dias_pac,3,1,$hc);        
            $this->calcular($egresos,3,0,$hc);
            $this->calcular($egresos,3,1,$hc);
            $this->calcular($def,3,0,$hc);
            $this->calcular($def,3,1,$hc);
            $this->calcular($def_48,3,0,$hc);
            $this->calcular($def_48,3,1,$hc);
            
            $dias_pac++;
            $egresos++;
            $def++;
            $def_48++;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,4,0,$hc);
            $this->calcular($dias_pac,4,1,$hc);        
            $this->calcular($egresos,4,0,$hc);
            $this->calcular($egresos,4,1,$hc);
            $this->calcular($def,4,0,$hc);
            $this->calcular($def,4,1,$hc);
            $this->calcular($def_48,4,0,$hc);
            $this->calcular($def_48,4,1,$hc);
            
            $dias_pac++;
            $egresos++;
            $def++;
            $def_48++;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,5,0,$hc);
            $this->calcular($dias_pac,5,1,$hc);        
            $this->calcular($egresos,5,0,$hc);
            $this->calcular($egresos,5,1,$hc);
            $this->calcular($def,5,0,$hc);
            $this->calcular($def,5,1,$hc);
            $this->calcular($def_48,5,0,$hc);
            $this->calcular($def_48,5,1,$hc);
          
          if($this->um == 223){  
            $dias_pac = 44;
            $egresos = 43;
            
            $this->calcular($dias_pac,6,0,$hc);
            $this->calcular($dias_pac,6,1,$hc);        
            $this->calcular($egresos,6,0,$hc);
            $this->calcular($egresos,6,1,$hc);
          }
            
        }
    }
    
    public function agregar($um = NULL, $mes = NULL, $anio = NULL)
    {
        if($um != NULL)
        {
            //ESTABLECE LOS VALORES
            //$this->id = $ind;            
            $this->um = $um;
            $this->mes = $mes;
            $this->anio = $anio;
            $acu = 0;
            $hc = 0;
            if($um == 'H.C.' OR $um == 'H.G.')
                $hc = $um;
            
            //CONSULTA LA BD BUSCANDO EL INDICADOR
            //$this->db->where('id',$this->id);
            //$consulta = $this->db->get('saeh_ind');
            
            //GUARDA EL NOMBRE DEL INDICADOR
            //foreach($consulta->result() as $ren)
            //    $this->nombre = $ren->saeh_ind_nombre;
            
            //VARIABLES PARA LOS ID'S DE LOS INDICADORES
            $dias_pac = 1;
            $egresos = 6;
            $def = 11;
            $def_48 = 16;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,1,0,$hc);
            $this->calcular($dias_pac,1,1,$hc);        
            $this->calcular($egresos,1,0,$hc);
            $this->calcular($egresos,1,1,$hc);
            $this->calcular($def,1,0,$hc);
            $this->calcular($def,1,1,$hc);
            $this->calcular($def_48,1,0,$hc);
            $this->calcular($def_48,1,1,$hc);
            
            $dias_pac++;
            $egresos++;
            $def++;
            $def_48++;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,2,0,$hc);
            $this->calcular($dias_pac,2,1,$hc);        
            $this->calcular($egresos,2,0,$hc);
            $this->calcular($egresos,2,1,$hc);
            $this->calcular($def,2,0,$hc);
            $this->calcular($def,2,1,$hc);
            $this->calcular($def_48,2,0,$hc);
            $this->calcular($def_48,2,1,$hc);
            
            $dias_pac++;
            $egresos++;
            $def++;
            $def_48++;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,3,0,$hc);
            $this->calcular($dias_pac,3,1,$hc);        
            $this->calcular($egresos,3,0,$hc);
            $this->calcular($egresos,3,1,$hc);
            $this->calcular($def,3,0,$hc);
            $this->calcular($def,3,1,$hc);
            $this->calcular($def_48,3,0,$hc);
            $this->calcular($def_48,3,1,$hc);
            
            $dias_pac++;
            $egresos++;
            $def++;
            $def_48++;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,4,0,$hc);
            $this->calcular($dias_pac,4,1,$hc);        
            $this->calcular($egresos,4,0,$hc);
            $this->calcular($egresos,4,1,$hc);
            $this->calcular($def,4,0,$hc);
            $this->calcular($def,4,1,$hc);
            $this->calcular($def_48,4,0,$hc);
            $this->calcular($def_48,4,1,$hc);
            
            $dias_pac++;
            $egresos++;
            $def++;
            $def_48++;
            
            //MANDA HACER LOS CALCULOS
            $this->calcular($dias_pac,5,0,$hc);
            $this->calcular($dias_pac,5,1,$hc);        
            $this->calcular($egresos,5,0,$hc);
            $this->calcular($egresos,5,1,$hc);
            $this->calcular($def,5,0,$hc);
            $this->calcular($def,5,1,$hc);
            $this->calcular($def_48,5,0,$hc);
            $this->calcular($def_48,5,1,$hc);
        }
    }
    /**
     * Md_ind_hosp::calcular()
     * 
     * @param mixed $unidad
     * @param mixed $mes
     * @param mixed $anio
     * @param integer $acu
     * @return void
     */
    public function calcular($id,$servicio,$acu = 0,$hc = 0)
    {
        //echo $id."<br />";
        //echo "servicio".$servicio;
        //CONVIERTE A ENTERO EL MES PORQUE DESPUES NO LO QUIERE LA CONSULTA A LA BD
        $mes = (int) $this->mes;
                        
       // $this->output->enable_profiler();
        
        //SI NO SE PIDE EL ACUMULADO
        if($acu === 0)
        {
            $this->db->where('mes',$this->mes);
        }
        //SI SE PIDE EL ACUMULADO
        else
        {
            $this->db->select_sum('dato');
            $this->db->where('mes <=', $mes);            
        }
        //SI SE PIDEN LOS HC
        if($hc != 0)
        {
            if($acu === 0)
                $this->db->select_sum('dato');
            $this->db->where('tipologia','H.C.');
        }
        //SI SE PIDE UNA UNIDAD EN ESPECIFICO
        else
            $this->db->where('id_um',$this->um);
        
        //SE REALIZA LA CONSULTA
        $this->db->where('anio',$this->anio);
        $this->db->where('id_ind',$id);
        $consulta = $this->db->get('vw_ind_hosp');
        $resultado = $consulta->result();
        if(! isset($resultado[0]))
            return FALSE;

        //SE GUARDA EL DATO
        foreach($consulta->result() as $ren)
        {
            //echo $ren->dato."<br />";
            if($acu == 0 && $hc == 0)
                if($servicio == 1)
                    switch ($id)
                    {
                        case 1: $this->dias_pac_men += $ren->dato;
                                break;
                        case 6: $this->egresos_men += $ren->dato;
                                break;
                        case 11: $this->defun_men += $ren->dato;
                                break;
                        case 16: $this->defun_48hrs_men += $ren->dato;
                                break;
                    }
                else if($servicio == 2)
                        switch ($id)
                        {
                            case 2: $this->med_int_dias_pac_men += $ren->dato;
                                    break;
                            case 7: $this->med_int_egresos_men += $ren->dato;
                                    break;
                            case 12: $this->med_int_defun_men += $ren->dato;
                                    break;
                            case 17: $this->med_int_defun_48hrs_men += $ren->dato;
                                    break;
                        }
                    else if($servicio == 3)
                        switch ($id)
                        {
                            case 3: $this->pediatria_dias_pac_men += $ren->dato;
                                    break;
                            case 8: $this->pediatria_egresos_men += $ren->dato;
                                    break;
                            case 13: $this->pediatria_defun_men += $ren->dato;
                                    break;
                            case 18: $this->pediatria_defun_48hrs_men += $ren->dato;
                                    break;
                        }
                    else if($servicio == 4)
                        switch ($id)
                        {
                            case 4: $this->ginecologia_dias_pac_men += $ren->dato;
                                    break;
                            case 9: $this->ginecologia_egresos_men += $ren->dato;
                                    break;
                            case 14: $this->ginecologia_defun_men += $ren->dato;
                                    break;
                            case 19: $this->ginecologia_defun_48hrs_men += $ren->dato;
                                    break;
                        }
                    else if($servicio == 5)
                        switch ($id)
                        {
                            case 5: $this->trauma_dias_pac_men += $ren->dato;
                                    break;
                            case 10: $this->trauma_egresos_men += $ren->dato;
                                    break;
                            case 15: $this->trauma_defun_men += $ren->dato;
                                    break;
                            case 20: $this->trauma_defun_48hrs_men += $ren->dato;
                                    break;
                        }
                    else if($servicio == 6)
                        switch ($id)
                        {
                            case 44: $this->trauma_dias_pac_men += $ren->dato;
                                    break;
                            case 43: $this->trauma_egresos_men += $ren->dato;
                                    break;
                        }
            if($acu == 1 && $hc == 0)
                if($servicio == 1)
                    switch ($id)
                    {
                        case 1: $this->dias_pac_acu += $ren->dato;
                                break;
                        case 6: $this->egresos_acu += $ren->dato;
                                break;
                        case 11: $this->defun_acu += $ren->dato;
                                break;
                        case 16: $this->defun_48hrs_acu += $ren->dato;
                                break;
                    }
                else if($servicio == 2)
                        switch ($id)
                        {
                            case 2: $this->med_int_dias_pac_acu += $ren->dato;
                                    break;
                            case 7: $this->med_int_egresos_acu += $ren->dato;
                                    break;
                            case 12: $this->med_int_defun_acu += $ren->dato;
                                    break;
                            case 17: $this->med_int_defun_48hrs_acu += $ren->dato;
                                    break;
                        }
                    else if($servicio == 3)
                        switch ($id)
                        {
                            case 3: $this->pediatria_dias_pac_acu += $ren->dato;
                                    break;
                            case 8: $this->pediatria_egresos_acu += $ren->dato;
                                    break;
                            case 13: $this->pediatria_defun_acu += $ren->dato;
                                    break;
                            case 18: $this->pediatria_defun_48hrs_acu += $ren->dato;
                                    break;
                        }
                    else if($servicio == 4)
                        switch ($id)
                        {
                            case 4: $this->ginecologia_dias_pac_acu += $ren->dato;
                                    break;
                            case 9: $this->ginecologia_egresos_acu += $ren->dato;
                                    break;
                            case 14: $this->ginecologia_defun_acu += $ren->dato;
                                    break;
                            case 19: $this->ginecologia_defun_48hrs_acu += $ren->dato;
                                    break;
                        }
                    else if($servicio == 5)
                        switch ($id)
                        {
                            case 5: $this->trauma_dias_pac_acu += $ren->dato;
                                    break;
                            case 10: $this->trauma_egresos_acu += $ren->dato;
                                    break;
                            case 15: $this->trauma_defun_acu += $ren->dato;
                                    break;
                            case 20: $this->trauma_defun_48hrs_acu += $ren->dato;
                                    break;
                        }
                    else if($servicio == 6)
                        switch ($id)
                        {
                            case 44: $this->trauma_dias_pac_acu += $ren->dato;
                                    break;
                            case 43: $this->trauma_egresos_acu += $ren->dato;
                                    break;
                        }
        }
    }
    
    public function meses_capturados($anio)
    {
        $this->db->select('mes');
        $this->db->distinct();
        $this->db->where('anio',$anio);
        $consulta = $this->db->get('saeh_reporte_ant');
        foreach($consulta->result() as $ren)
        {
            switch ($ren->mes)
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
            $datos[$ren->mes] = $mes_txt;
        }
        return $datos;
    }
    /**
 * public function calcular_egresos($acu = 0)
 *     {
 *         //CONVIERTE A ENTERO EL MES PORQUE DESPUES NO LO QUIERE LA CONSULTA A LA BD
 *         $mes = (int) $this->mes;
 *         
 *         
 *     }
 */
}

?>