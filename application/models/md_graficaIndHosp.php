<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_graficaIndHosp extends CI_Controller
{
    function tituloNumATxt($titulo_num)
    {
        switch ($titulo_num)
        {
            case 1: $titulo = array('corto' => 'ocup_men', 'largo' => 'Porcentaje Ocupacion Mensual');
                    return $titulo; 
            case 2: $titulo = array('corto' => 'ocup_acu', 'largo' => 'Porcentaje Ocupacion Acumulado');
                    return $titulo; 
            case 3: $titulo = array('corto' => 'ind_rot_men', 'largo' => 'Indice de Rotacion Mensual');
                    return $titulo;
            case 4: $titulo = array('corto' => 'ind_rot_acu', 'largo' => 'Indice de Rotacion Acumulado');
                    return $titulo;
            case 5: $titulo = array('corto' => 'int_sust_men', 'largo' => 'Intervalo de Sustitucion Mensual');
                    return $titulo;  
            case 6: $titulo = array('corto' => 'int_sust_acu', 'largo' => 'Intervalo de Sustitucion Acumulado');
                    return $titulo;
            case 7: $titulo = array('corto' => 'tasa_mort_bruta', 'largo' => 'Tasas de Mortalidad Bruta');
                    return $titulo;
            case 8: $titulo = array('corto' => 'tasa_mort_ajus', 'largo' => 'Tasas de Mortalidad Ajustada');
                    return $titulo; 
            case 9: $titulo = array('corto' => 'dias_est_men', 'largo' => 'Dias Estancia Mensual');
                    return $titulo; 
            case 10: $titulo = array('corto' => 'dias_est_acu', 'largo' => 'Dias Estancia Acumulado');
                    return $titulo;        
        }
    }
    function datos_ind_hosp_xum($um,$servicio,$anio,$indicador)
    {
        $meses = array('01','02','03','04','05','06','07','08','09','10','11','12');
        
        foreach($meses as $mes)
        {
            $dato = $this->ind_hosp($um,$mes,$anio);
            if($dato != FALSE)
                $datos[] = $dato;
        }
            
        //print_r($datos);
        //echo $indicador;
        $bar_values = array();
        foreach($datos as $dato)
        {
            if($indicador == 'ocup_men' OR $indicador == 'ocup_acu')
                $indicador = 'porc_'.$indicador;
            
            if($servicio == 'cir')
                    $bar_values[] = $dato[$indicador];
                elseif($servicio == 'med_int')
                        $bar_values[] = $dato['med_int_'.$indicador];
                    elseif($servicio == 'pediatria')
                            $bar_values[] = $dato['pediatria_'.$indicador];
                        elseif($servicio == 'ginecologia')
                                $bar_values[] = $dato['ginecologia_'.$indicador];
                            elseif($servicio == 'trauma')
                                    $bar_values[] = $dato['trauma_'.$indicador];
                                elseif($servicio == 'total')
                                        $bar_values[] = $dato['total_'.$indicador];
        }
        return $bar_values;
    }
    
    function ind_hosp($um,$mes,$anio)
    {
        if($um->id == 223)
            $um->camas_trauma = $um->camas_otros;
        
        //print_r($um);
        $this->load->model('md_ind_hosp');
        $ind_hosp = new Md_ind_hosp($um->id,$mes,$anio);
        
        $datos['tipologia'] = $um->tipologia;
        
        if($um->id == 'HC')
                $um->id = "H.C.";
                elseif($um->id == 'HG')
                        $um->id = "H.G.";      
                
        if($um->id == 'H.C.' OR $um->id == 'H.G.')
        {
            $this->db->where('tipologia',$um->id);
            $consulta = $this->db->get('um');
            foreach($consulta->result() as $ren)
            {
                $ind_hosp->agregar($ren->id,$mes,$anio);
            }
        }
        
        if(! isset($ind_hosp->dias_pac_men))
            return FALSE;
        else
        {
        //echo "<BR />";
        //$ind_hosp->calcular(180,$mes,$anio);
        $this->load->library('table');
        
        $tmpl = array (
                    'table_open'          => '<table border="1" cellpadding="4" cellspacing="0">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );

        $this->table->set_template($tmpl);
        
        $dias_cama_men = $um->camas_cirugia * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;            
            $dias_cama_acu = $mes_num * 30 * $um->camas_cirugia;
        }
        else
            $dias_cama_acu = $um->camas_cirugia * 365;
        
        if($um->camas_cirugia != 0)
        {
            $porc_ocup_men = $ind_hosp->dias_pac_men / $dias_cama_men * 100;
            $porc_ocup_acu = $ind_hosp->dias_pac_acu / $dias_cama_acu * 100;
            $ind_rot_men = $ind_hosp->egresos_men / $um->camas_cirugia;
            $ind_rot_acu = $ind_hosp->egresos_acu / $um->camas_cirugia;
        }
        else
        {
            $porc_ocup_men = 0;
            $porc_ocup_acu = 0;
            $ind_rot_men = 0;
            $ind_rot_acu = 0;
        }
        if($ind_hosp->egresos_men != 0)
        {
            $int_sust_men = ($dias_cama_men - $ind_hosp->dias_pac_men) / $ind_hosp->egresos_men;
            $int_sust_acu = ($dias_cama_acu - $ind_hosp->dias_pac_acu) / $ind_hosp->egresos_acu;
            $tasa_mort_bruta = $ind_hosp->defun_men / $ind_hosp->egresos_men * 1000;
            $tasa_mort_ajus = $ind_hosp->defun_48hrs_men / $ind_hosp->egresos_men * 1000;
            $dias_est_men = $ind_hosp->dias_pac_men / $ind_hosp->egresos_men;
            $dias_est_acu = $ind_hosp->dias_pac_acu / $ind_hosp->egresos_acu;
        }
        else
        {
            $int_sust_men = 0;
            $int_sust_acu = 0;
            $tasa_mort_bruta = 0;
            $tasa_mort_ajus = 0;
            $dias_est_men = 0;
            $dias_est_acu = 0;
        }
        //////////////////////////////////////////////////////////////////////////////////////
        $dias_cama_men = $um->camas_med_interna * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * 30 * $um->camas_med_interna;
            //echo $dias_cama_acu;
        }
        
        if($um->camas_med_interna != 0)
        {
            $med_int_porc_ocup_men = $ind_hosp->med_int_dias_pac_men / $dias_cama_men * 100;
            $med_int_porc_ocup_acu = $ind_hosp->med_int_dias_pac_acu / $dias_cama_acu * 100;
            $med_int_ind_rot_men = $ind_hosp->med_int_egresos_men / $um->camas_med_interna;
            $med_int_ind_rot_acu = $ind_hosp->med_int_egresos_acu / $um->camas_med_interna;
        }
        else
        {
            $med_int_porc_ocup_men = 0;
            $med_int_porc_ocup_acu = 0;
            $med_int_ind_rot_men = 0;
            $med_int_ind_rot_acu = 0;
        }    
                
        if($ind_hosp->med_int_egresos_men != 0)
        {
            $med_int_int_sust_men = ($dias_cama_men - $ind_hosp->med_int_dias_pac_men) / $ind_hosp->med_int_egresos_men;
            $med_int_int_sust_acu = ($dias_cama_acu - $ind_hosp->med_int_dias_pac_acu) / $ind_hosp->med_int_egresos_acu;
            $med_int_tasa_mort_bruta = $ind_hosp->med_int_defun_men / $ind_hosp->med_int_egresos_men * 1000;
            $med_int_tasa_mort_ajus = $ind_hosp->med_int_defun_48hrs_men / $ind_hosp->med_int_egresos_men * 1000;
            $med_int_dias_est_men = $ind_hosp->med_int_dias_pac_men / $ind_hosp->med_int_egresos_men;
            $med_int_dias_est_acu = $ind_hosp->med_int_dias_pac_acu / $ind_hosp->med_int_egresos_acu;
                    
        }        
        else        
        {
            $med_int_int_sust_men = 0;
            $med_int_int_sust_acu = 0;
            $med_int_tasa_mort_bruta = 0;
            $med_int_tasa_mort_ajus = 0;
            $med_int_dias_est_men = 0;
            $med_int_dias_est_acu = 0;
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $dias_cama_men = $um->camas_pediatria * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * 30 * $um->camas_pediatria;
            //echo $dias_cama_acu;
        }
        
        if($um->camas_pediatria != 0)
        {
            $pediatria_porc_ocup_men = $ind_hosp->pediatria_dias_pac_men / $dias_cama_men * 100;
            $pediatria_porc_ocup_acu = $ind_hosp->pediatria_dias_pac_acu / $dias_cama_acu * 100;
            $pediatria_ind_rot_men = $ind_hosp->pediatria_egresos_men / $um->camas_pediatria;
            $pediatria_ind_rot_acu = $ind_hosp->pediatria_egresos_acu / $um->camas_pediatria;
        }
        else
        {
            $pediatria_porc_ocup_men = 0;
            $pediatria_porc_ocup_acu = 0;
            $pediatria_ind_rot_men = 0;
            $pediatria_ind_rot_acu = 0;
        }
        if($ind_hosp->pediatria_egresos_men != 0)
        {
            $pediatria_int_sust_men = ($dias_cama_men - $ind_hosp->pediatria_dias_pac_men) / $ind_hosp->pediatria_egresos_men;
            $pediatria_int_sust_acu = ($dias_cama_acu - $ind_hosp->pediatria_dias_pac_acu) / $ind_hosp->pediatria_egresos_acu;
            $pediatria_tasa_mort_bruta = $ind_hosp->pediatria_defun_men / $ind_hosp->pediatria_egresos_men * 1000;
            $pediatria_tasa_mort_ajus = $ind_hosp->pediatria_defun_48hrs_men / $ind_hosp->pediatria_egresos_men * 1000;
            $pediatria_dias_est_men = $ind_hosp->pediatria_dias_pac_men / $ind_hosp->pediatria_egresos_men;
            $pediatria_dias_est_acu = $ind_hosp->pediatria_dias_pac_acu / $ind_hosp->pediatria_egresos_acu;
        }
        else
        {
            $pediatria_int_sust_men = 0;
            $pediatria_int_sust_acu = 0;
            $pediatria_tasa_mort_bruta = 0;
            $pediatria_tasa_mort_ajus = 0;
            $pediatria_dias_est_men = 0;
            $pediatria_dias_est_acu = 0;
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $dias_cama_men = $um->camas_ginecologia * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * 30 * $um->camas_ginecologia;
            //echo $dias_cama_acu;
        }
        else
            $dias_cama_acu = 365 * $um->camas_ginecologia;
        
        if($um->camas_ginecologia != 0)
        {
            $ginecologia_porc_ocup_men = $ind_hosp->ginecologia_dias_pac_men / $dias_cama_men * 100;
            $ginecologia_porc_ocup_acu = $ind_hosp->ginecologia_dias_pac_acu / $dias_cama_acu * 100;
            
            $ginecologia_ind_rot_men = $ind_hosp->ginecologia_egresos_men / $um->camas_ginecologia;
            $ginecologia_ind_rot_acu = $ind_hosp->ginecologia_egresos_acu / $um->camas_ginecologia;
        }
        else
        {
            $ginecologia_porc_ocup_men = 0;
            $ginecologia_porc_ocup_acu = 0;
            $ginecologia_ind_rot_men = 0;
            $ginecologia_ind_rot_acu = 0;
                    
        }                
        
        if($ind_hosp->ginecologia_egresos_men != 0)                
        {
            $ginecologia_int_sust_men = ($dias_cama_men - $ind_hosp->ginecologia_dias_pac_men) / $ind_hosp->ginecologia_egresos_men;
            $ginecologia_int_sust_acu = ($dias_cama_acu - $ind_hosp->ginecologia_dias_pac_acu) / $ind_hosp->ginecologia_egresos_acu;
            $ginecologia_tasa_mort_bruta = $ind_hosp->ginecologia_defun_men / $ind_hosp->ginecologia_egresos_men * 1000;
            $ginecologia_tasa_mort_ajus = $ind_hosp->ginecologia_defun_48hrs_men / $ind_hosp->ginecologia_egresos_men * 1000;
            $ginecologia_dias_est_men = $ind_hosp->ginecologia_dias_pac_men / $ind_hosp->ginecologia_egresos_men;
            $ginecologia_dias_est_acu = $ind_hosp->ginecologia_dias_pac_acu / $ind_hosp->ginecologia_egresos_acu;
        }
        else
        {
            $ginecologia_int_sust_men = 0;
            $ginecologia_int_sust_acu = 0;
            $ginecologia_tasa_mort_bruta = 0;
            $ginecologia_tasa_mort_ajus = 0;
            $ginecologia_dias_est_men = 0;
            $ginecologia_dias_est_acu = 0;
        }
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $dias_cama_men = $um->camas_trauma * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * 30 * $um->camas_trauma;
            //echo $dias_cama_acu;
        }
        else
            $dias_cama_acu = 365 * $um->camas_trauma;

        if($um->camas_trauma != 0)
        {
            $trauma_porc_ocup_men = $ind_hosp->trauma_dias_pac_men / $dias_cama_men * 100;
            $trauma_porc_ocup_acu = $ind_hosp->trauma_dias_pac_acu / $dias_cama_acu * 100;
            $trauma_ind_rot_men = $ind_hosp->trauma_egresos_men / $um->camas_trauma;
            $trauma_ind_rot_acu = $ind_hosp->trauma_egresos_acu / $um->camas_trauma;
        }
        else
        {
            $trauma_porc_ocup_men = 0;
            $trauma_porc_ocup_acu = 0;
            $trauma_ind_rot_men = 0;
            $trauma_ind_rot_acu = 0;
        }
        if($ind_hosp->trauma_egresos_men != 0)
        {
            $trauma_int_sust_men = ($dias_cama_men - $ind_hosp->trauma_dias_pac_men) / $ind_hosp->trauma_egresos_men;
            $trauma_int_sust_acu = ($dias_cama_acu - $ind_hosp->trauma_dias_pac_acu) / $ind_hosp->trauma_egresos_acu;
            $trauma_tasa_mort_bruta = $ind_hosp->trauma_defun_men / $ind_hosp->trauma_egresos_men * 1000;
            $trauma_tasa_mort_ajus = $ind_hosp->trauma_defun_48hrs_men / $ind_hosp->trauma_egresos_men * 1000;
            $trauma_dias_est_men = $ind_hosp->trauma_dias_pac_men / $ind_hosp->trauma_egresos_men;
            $trauma_dias_est_acu = $ind_hosp->trauma_dias_pac_acu / $ind_hosp->trauma_egresos_acu;
        }
        else
        {
            $trauma_int_sust_men = 0;
            $trauma_int_sust_acu = 0;
            $trauma_tasa_mort_bruta = 0;
            $trauma_tasa_mort_ajus = 0;
            $trauma_dias_est_men = 0;
            $trauma_dias_est_acu = 0;
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $dias_cama_men = ($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia) * 30;
        
        //TODOS LOS MESES SE MULTIPLICAN POR 30 MENOS CUANDO ES DICIEMBRE
        if($mes != 12)
        {
            $mes_num = (int) $mes;
            $dias_cama_acu = $mes_num * $dias_cama_men;
            //echo $dias_cama_acu;
        }
        else
            $dias_cama_acu = ($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia) * 365;
        
        if($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia != 0)
        {
            $total_porc_ocup_men = ($ind_hosp->trauma_dias_pac_men + $ind_hosp->ginecologia_dias_pac_men + $ind_hosp->pediatria_dias_pac_men + $ind_hosp->med_int_dias_pac_men + $ind_hosp->dias_pac_men) / $dias_cama_men * 100;
            $total_porc_ocup_acu = ($ind_hosp->trauma_dias_pac_acu + $ind_hosp->ginecologia_dias_pac_acu + $ind_hosp->pediatria_dias_pac_acu + $ind_hosp->med_int_dias_pac_acu + $ind_hosp->dias_pac_acu) / $dias_cama_acu * 100;
            $total_ind_rot_men = ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->dias_pac_men) / ($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia);
            $total_ind_rot_acu = ($ind_hosp->trauma_egresos_acu + $ind_hosp->ginecologia_egresos_acu + $ind_hosp->pediatria_egresos_acu + $ind_hosp->med_int_egresos_acu + $ind_hosp->dias_pac_acu) / ($um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia);
        }
        else
        {
            $total_porc_ocup_men = 0;
            $total_porc_ocup_acu = 0;
            $total_ind_rot_men = 0;
            $total_ind_rot_acu = 0;
        }
        if($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->dias_pac_men != 0)
        {
            $total_int_sust_men = ($dias_cama_men - $ind_hosp->trauma_dias_pac_men + $ind_hosp->ginecologia_dias_pac_men + $ind_hosp->pediatria_dias_pac_men + $ind_hosp->med_int_dias_pac_men + $ind_hosp->dias_pac_men) / ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->dias_pac_men);
            $total_int_sust_acu = ($dias_cama_acu - $ind_hosp->trauma_dias_pac_acu + $ind_hosp->ginecologia_dias_pac_acu + $ind_hosp->pediatria_dias_pac_acu + $ind_hosp->med_int_dias_pac_acu + $ind_hosp->dias_pac_acu) / ($ind_hosp->trauma_egresos_acu + $ind_hosp->ginecologia_egresos_acu + $ind_hosp->pediatria_egresos_acu + $ind_hosp->med_int_egresos_acu + $ind_hosp->dias_pac_acu);
            $total_tasa_mort_bruta = ($ind_hosp->trauma_defun_men + $ind_hosp->ginecologia_defun_men + $ind_hosp->pediatria_defun_men + $ind_hosp->med_int_defun_men + $ind_hosp->defun_men) / ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->egresos_men) * 1000;
            $total_tasa_mort_ajus = ($ind_hosp->trauma_defun_48hrs_men + $ind_hosp->ginecologia_defun_48hrs_men + $ind_hosp->pediatria_defun_48hrs_men + $ind_hosp->med_int_defun_48hrs_men + $ind_hosp->defun_48hrs_men) / ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->egresos_men) * 1000;
            $total_dias_est_men = ($ind_hosp->trauma_dias_pac_men + $ind_hosp->ginecologia_dias_pac_men + $ind_hosp->pediatria_dias_pac_men + $ind_hosp->med_int_dias_pac_men + $ind_hosp->dias_pac_men) / ($ind_hosp->trauma_egresos_men + $ind_hosp->ginecologia_egresos_men + $ind_hosp->pediatria_egresos_men + $ind_hosp->med_int_egresos_men + $ind_hosp->egresos_men);
            $total_dias_est_acu = ($ind_hosp->trauma_dias_pac_acu + $ind_hosp->ginecologia_dias_pac_acu + $ind_hosp->pediatria_dias_pac_acu + $ind_hosp->med_int_dias_pac_acu + $ind_hosp->dias_pac_acu) / ($ind_hosp->trauma_egresos_acu + $ind_hosp->ginecologia_egresos_acu + $ind_hosp->pediatria_egresos_acu + $ind_hosp->med_int_egresos_acu + $ind_hosp->dias_pac_acu);
        }
        else
        {
            $total_int_sust_men = 0;
            $total_int_sust_acu = 0;
            $total_tasa_mort_bruta = 0;
            $total_tasa_mort_ajus = 0;
            $total_dias_est_men = 0;
            $total_dias_est_acu = 0;
        }
        
        $total_camas = $um->camas_trauma + $um->camas_ginecologia + $um->camas_pediatria + $um->camas_med_interna + $um->camas_cirugia;
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $titulo_ocu_men = array('data' => '<center>% OCUPACION MENSUAL</center>', 'id' => 'mensual', 'class' => 'boton');
        $titulo_ocu_acu = array('data' => '<center>% OCUPACION ACUMULADO</center>', 'id' => 'acumulado', 'class' => 'boton', 'align' => 'center');
        $titulo_ind_rot_men = array('data' => '<center>INDICE DE ROTACION MENSUAL</center>', 'id' => 'ind_rot_men', 'class' => 'boton', 'align' => 'center');
        $titulo_ind_rot_acu = array('data' => '<center>INDICE DE ROTACION ACUMULADO</center>', 'id' => 'ind_rot_acu', 'class' => 'boton', 'align' => 'center');
        $titulo_int_sust_men = array('data' => '<center>INTERVALO DE SUSTITUCION MENSUAL</center>', 'id' => 'int_sust_men', 'class' => 'boton', 'align' => 'center');
        $titulo_int_sust_acu = array('data' => '<center>INTERVALO DE SUSTITUCION ACUMULADO</center>', 'id' => 'int_sust_acu', 'class' => 'boton', 'align' => 'center');
        $titulo_tasa_mort_bruta = array('data' => '<center>TASA DE MORTALIDAD BRUTA</center>', 'id' => 'tasa_mort_bruta', 'class' => 'boton', 'align' => 'center');
        $titulo_tasa_mort_ajus = array('data' => '<center>TASA DE MORTALIDAD AJUSTADA</center>', 'id' => 'tasa_mort_ajus', 'class' => 'boton', 'align' => 'center');
        $titulo_dias_est_men = array('data' => '<center>DIAS ESTANCIA MENSUAL</center>', 'id' => 'dias_est_men', 'class' => 'boton', 'align' => 'center');
        $titulo_dias_est_acu = array('data' => '<center>DIAS ESTANCIA ACUMULADO</center>', 'id' => 'dias_est_acu', 'class' => 'boton', 'align' => 'center');
        
        #PARA PORCENTAJE DE OCUPACION MENSUAL         
        $porc_ocup_men_celda = array('data' => number_format($porc_ocup_men,2), 'id' => 'cir_ocup_men', 'class' => 'boton', 'align' => 'right');
        $med_int_porc_ocup_men_celda = array('data' => number_format($med_int_porc_ocup_men,2), 'id' => 'med_int_ocup_men', 'class' => 'boton', 'align' => 'right');
        $pediatria_porc_ocup_men_celda = array('data' => number_format($pediatria_porc_ocup_men,2), 'id' => 'pediatria_ocup_men', 'class' => 'boton', 'align' => 'right');
        $ginecologia_porc_ocup_men_celda = array('data' => number_format($ginecologia_porc_ocup_men,2), 'id' => 'ginecologia_ocup_men', 'class' => 'boton', 'align' => 'right');
        $trauma_porc_ocup_men_celda = array('data' => number_format($trauma_porc_ocup_men,2), 'id' => 'trauma_ocup_men', 'class' => 'boton', 'align' => 'right');
        $total_porc_ocup_men_celda = array('data' => number_format($total_porc_ocup_men,2), 'id' => 'total_ocup_men', 'class' => 'boton', 'align' => 'right');
        
        #PARA PORCENTAJE DE OCUPACION ACUMULADO         
        $porc_ocup_acu_celda = array('data' => number_format($porc_ocup_acu,2), 'id' => 'cir_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $med_int_porc_ocup_acu_celda = array('data' => number_format($med_int_porc_ocup_acu,2), 'id' => 'med_int_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $pediatria_porc_ocup_acu_celda = array('data' => number_format($pediatria_porc_ocup_acu,2), 'id' => 'pediatria_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $ginecologia_porc_ocup_acu_celda = array('data' => number_format($ginecologia_porc_ocup_acu,2), 'id' => 'ginecologia_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $trauma_porc_ocup_acu_celda = array('data' => number_format($trauma_porc_ocup_acu,2), 'id' => 'trauma_ocup_acu', 'class' => 'boton', 'align' => 'right');
        $total_porc_ocup_acu_celda = array('data' => number_format($total_porc_ocup_acu,2), 'id' => 'total_ocup_acu', 'class' => 'boton', 'align' => 'right');
        
        #PARA INDICE DE ROTACION MENSUAL     
        $ind_rot_men_celda = array('data' => number_format($ind_rot_men,2), 'id' => 'cir_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $med_int_ind_rot_men_celda = array('data' => number_format($med_int_ind_rot_men,2), 'id' => 'med_int_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $pediatria_ind_rot_men_celda = array('data' => number_format($pediatria_ind_rot_men,2), 'id' => 'pediatria_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $ginecologia_ind_rot_men_celda = array('data' => number_format($ginecologia_ind_rot_men,2), 'id' => 'ginecologia_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $trauma_ind_rot_men_celda = array('data' => number_format($trauma_ind_rot_men,2), 'id' => 'trauma_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        $total_ind_rot_men_celda = array('data' => number_format($total_ind_rot_men,2), 'id' => 'total_ind_rot_men', 'class' => 'boton', 'align' => 'right');
        
        #PARA INDICE DE ROTACION ACUMULADO     
        $ind_rot_acu_celda = array('data' => number_format($ind_rot_acu,2), 'id' => 'cir_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $med_int_ind_rot_acu_celda = array('data' => number_format($med_int_ind_rot_acu,2), 'id' => 'med_int_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $pediatria_ind_rot_acu_celda = array('data' => number_format($pediatria_ind_rot_acu,2), 'id' => 'pediatria_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $ginecologia_ind_rot_acu_celda = array('data' => number_format($ginecologia_ind_rot_acu,2), 'id' => 'ginecologia_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $trauma_ind_rot_acu_celda = array('data' => number_format($trauma_ind_rot_acu,2), 'id' => 'trauma_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        $total_ind_rot_acu_celda = array('data' => number_format($total_ind_rot_acu,2), 'id' => 'total_ind_rot_acu', 'class' => 'boton', 'align' => 'right');
        
        #PARA INTERVALO DE SUSTITUCION MENSUAL
        $int_sust_men_celda = array('data' => number_format($int_sust_men,2), 'id' => 'cir_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $med_int_int_sust_men_celda = array('data' => number_format($med_int_int_sust_men,2), 'id' => 'med_int_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $pediatria_int_sust_men_celda = array('data' => number_format($pediatria_int_sust_men,2), 'id' => 'pediatria_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $ginecologia_int_sust_men_celda = array('data' => number_format($ginecologia_int_sust_men,2), 'id' => 'ginecologia_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $trauma_int_sust_men_celda = array('data' => number_format($trauma_int_sust_men,2), 'id' => 'trauma_int_sust_men', 'class' => 'boton', 'align' => 'right');
        $total_int_sust_men_celda = array('data' => number_format($total_int_sust_men,2), 'id' => 'total_int_sust_men', 'class' => 'boton', 'align' => 'right');
        
        #PARA INTERVALO DE SUSTITUCION ACUMULADO
        $int_sust_acu_celda = array('data' => number_format($int_sust_acu,2), 'id' => 'cir_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $med_int_int_sust_acu_celda = array('data' => number_format($med_int_int_sust_acu,2), 'id' => 'med_int_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $pediatria_int_sust_acu_celda = array('data' => number_format($pediatria_int_sust_acu,2), 'id' => 'pediatria_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $ginecologia_int_sust_acu_celda = array('data' => number_format($ginecologia_int_sust_acu,2), 'id' => 'ginecologia_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $trauma_int_sust_acu_celda = array('data' => number_format($trauma_int_sust_acu,2), 'id' => 'trauma_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        $total_int_sust_acu_celda = array('data' => number_format($total_int_sust_acu,2), 'id' => 'total_int_sust_acu', 'class' => 'boton', 'align' => 'right');
        
        #PARA TASA DE MORTALIDAD BRUTA
        $tasa_mort_bruta_celda = array('data' => number_format($tasa_mort_bruta,2), 'id' => 'cir_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $med_int_tasa_mort_bruta_celda = array('data' => number_format($med_int_tasa_mort_bruta,2), 'id' => 'med_int_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $pediatria_tasa_mort_bruta_celda = array('data' => number_format($pediatria_tasa_mort_bruta,2), 'id' => 'pediatria_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $ginecologia_tasa_mort_bruta_celda = array('data' => number_format($ginecologia_tasa_mort_bruta,2), 'id' => 'ginecologia_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $trauma_tasa_mort_bruta_celda = array('data' => number_format($trauma_tasa_mort_bruta,2), 'id' => 'trauma_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        $total_tasa_mort_bruta_celda = array('data' => number_format($total_tasa_mort_bruta,2), 'id' => 'total_tasa_mort_bruta', 'class' => 'boton', 'align' => 'right');
        
        #PARA TASA DE MORTALIDAD AJUSTADA
        $tasa_mort_ajus_celda = array('data' => number_format($tasa_mort_ajus,2), 'id' => 'cir_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $med_int_tasa_mort_ajus_celda = array('data' => number_format($med_int_tasa_mort_ajus,2), 'id' => 'med_int_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $pediatria_tasa_mort_ajus_celda = array('data' => number_format($pediatria_tasa_mort_ajus,2), 'id' => 'pediatria_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $ginecologia_tasa_mort_ajus_celda = array('data' => number_format($ginecologia_tasa_mort_ajus,2), 'id' => 'ginecologia_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $trauma_tasa_mort_ajus_celda = array('data' => number_format($trauma_tasa_mort_ajus,2), 'id' => 'trauma_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        $total_tasa_mort_ajus_celda = array('data' => number_format($total_tasa_mort_ajus,2), 'id' => 'total_tasa_mort_ajus', 'class' => 'boton', 'align' => 'right');
        
        #PARA DIAS ESTANCIA MENSUAL
        $dias_est_men_celda = array('data' => number_format($dias_est_men,2), 'id' => 'cir_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $med_int_dias_est_men_celda = array('data' => number_format($med_int_dias_est_men,2), 'id' => 'med_int_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $pediatria_dias_est_men_celda = array('data' => number_format($pediatria_dias_est_men,2), 'id' => 'pediatria_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $ginecologia_dias_est_men_celda = array('data' => number_format($ginecologia_dias_est_men,2), 'id' => 'ginecologia_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $trauma_dias_est_men_celda = array('data' => number_format($trauma_dias_est_men,2), 'id' => 'trauma_dias_est_men', 'class' => 'boton', 'align' => 'right');
        $total_dias_est_men_celda = array('data' => number_format($total_dias_est_men,2), 'id' => 'total_dias_est_men', 'class' => 'boton', 'align' => 'right');
        
        #PARA DIAS ESTANCIA ACUMULADO
        $dias_est_acu_celda = array('data' => number_format($dias_est_acu,2), 'id' => 'cir_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $med_int_dias_est_acu_celda = array('data' => number_format($med_int_dias_est_acu,2), 'id' => 'med_int_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $pediatria_dias_est_acu_celda = array('data' => number_format($pediatria_dias_est_acu,2), 'id' => 'pediatria_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $ginecologia_dias_est_acu_celda = array('data' => number_format($ginecologia_dias_est_acu,2), 'id' => 'ginecologia_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $trauma_dias_est_acu_celda = array('data' => number_format($trauma_dias_est_acu,2), 'id' => 'trauma_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        $total_dias_est_acu_celda = array('data' => number_format($total_dias_est_acu,2), 'id' => 'total_dias_est_acu', 'class' => 'boton', 'align' => 'right');
        
        $this->table->set_heading('','CAMAS CENSABLES',$titulo_ocu_men,$titulo_ocu_acu,$titulo_ind_rot_men,$titulo_ind_rot_acu,$titulo_int_sust_men,$titulo_int_sust_acu,$titulo_tasa_mort_bruta,$titulo_tasa_mort_ajus,$titulo_dias_est_men,$titulo_dias_est_acu);
        $this->table->add_row('CIRUGIA',$um->camas_cirugia,$porc_ocup_men_celda,$porc_ocup_acu_celda,$ind_rot_men_celda,$ind_rot_acu_celda,$int_sust_men_celda,$int_sust_acu_celda,$tasa_mort_bruta_celda,$tasa_mort_ajus_celda,$dias_est_men_celda,$dias_est_acu_celda);
        $this->table->add_row('MEDICINA INTERNA',$um->camas_med_interna,$med_int_porc_ocup_men_celda,$med_int_porc_ocup_acu_celda,$med_int_ind_rot_men_celda,$med_int_ind_rot_acu_celda,$med_int_int_sust_men_celda,$med_int_int_sust_acu_celda,$med_int_tasa_mort_bruta_celda,$med_int_tasa_mort_ajus_celda,$med_int_dias_est_men_celda,$med_int_dias_est_acu_celda);
        $this->table->add_row('PEDIATRIA',$um->camas_pediatria,$pediatria_porc_ocup_men_celda,$pediatria_porc_ocup_acu_celda,$pediatria_ind_rot_men_celda,$pediatria_ind_rot_acu_celda,$pediatria_int_sust_men_celda,$pediatria_int_sust_acu_celda,$pediatria_tasa_mort_bruta_celda,$pediatria_tasa_mort_ajus_celda,$pediatria_dias_est_men_celda,$pediatria_dias_est_acu_celda);
        $this->table->add_row('GINECOLOGIA',$um->camas_ginecologia,$ginecologia_porc_ocup_men_celda,$ginecologia_porc_ocup_acu_celda,$ginecologia_ind_rot_men_celda,$ginecologia_ind_rot_acu_celda,$ginecologia_int_sust_men_celda,$ginecologia_int_sust_acu_celda,$ginecologia_tasa_mort_bruta_celda,$ginecologia_tasa_mort_ajus_celda,$ginecologia_dias_est_men_celda,$ginecologia_dias_est_acu_celda);
        if($um->id == 223)
            $this->table->add_row('PSIQUIATRIA',$um->camas_trauma,$trauma_porc_ocup_men_celda,$trauma_porc_ocup_acu_celda,$trauma_ind_rot_men_celda,$trauma_ind_rot_acu_celda,$trauma_int_sust_men_celda,$trauma_int_sust_acu_celda,$trauma_tasa_mort_bruta_celda,$trauma_tasa_mort_ajus_celda,$trauma_dias_est_men_celda,$trauma_dias_est_acu_celda);
        else
            $this->table->add_row('TRAUMATOLOGIA',$um->camas_trauma,$trauma_porc_ocup_men_celda,$trauma_porc_ocup_acu_celda,$trauma_ind_rot_men_celda,$trauma_ind_rot_acu_celda,$trauma_int_sust_men_celda,$trauma_int_sust_acu_celda,$trauma_tasa_mort_bruta_celda,$trauma_tasa_mort_ajus_celda,$trauma_dias_est_men_celda,$trauma_dias_est_acu_celda);
        $this->table->add_row('TOTAL',$total_camas,$total_porc_ocup_men_celda,$total_porc_ocup_acu_celda,$total_ind_rot_men_celda,$total_ind_rot_acu_celda,$total_int_sust_men_celda,$total_int_sust_acu_celda,$total_tasa_mort_bruta_celda,$total_tasa_mort_ajus_celda,$total_dias_est_men_celda,$total_dias_est_acu_celda);
        
        $datos['porc_ocup_men'] = (float) number_format($porc_ocup_men,2);
        $datos['med_int_porc_ocup_men'] = (float) number_format($med_int_porc_ocup_men,2);
        $datos['pediatria_porc_ocup_men'] = (float) number_format($pediatria_porc_ocup_men,2);
        $datos['ginecologia_porc_ocup_men'] = (float) number_format($ginecologia_porc_ocup_men,2);
        $datos['trauma_porc_ocup_men'] = (float) number_format($trauma_porc_ocup_men,2);
        $datos['total_porc_ocup_men'] = (float) number_format($total_porc_ocup_men,2);
        
        $datos['porc_ocup_acu'] = (float) number_format($porc_ocup_acu,2);
        $datos['med_int_porc_ocup_acu'] = (float) number_format($med_int_porc_ocup_acu,2);
        $datos['pediatria_porc_ocup_acu'] = (float) number_format($pediatria_porc_ocup_acu,2);
        $datos['ginecologia_porc_ocup_acu'] = (float) number_format($ginecologia_porc_ocup_acu,2);
        $datos['trauma_porc_ocup_acu'] = (float) number_format($trauma_porc_ocup_acu,2);
        $datos['total_porc_ocup_acu'] = (float) number_format($total_porc_ocup_acu,2);
        
        $datos['ind_rot_men'] = (float) number_format($ind_rot_men,2);
        $datos['med_int_ind_rot_men'] = (float) number_format($med_int_ind_rot_men,2);
        $datos['pediatria_ind_rot_men'] = (float) number_format($pediatria_ind_rot_men,2);
        $datos['ginecologia_ind_rot_men'] = (float) number_format($ginecologia_ind_rot_men,2);
        $datos['trauma_ind_rot_men'] = (float) number_format($trauma_ind_rot_men,2);
        $datos['total_ind_rot_men'] = (float) number_format($total_ind_rot_men,2);
        
        $datos['ind_rot_acu'] = (float) number_format($ind_rot_acu,2);
        $datos['med_int_ind_rot_acu'] = (float) number_format($med_int_ind_rot_acu,2);
        $datos['pediatria_ind_rot_acu'] = (float) number_format($pediatria_ind_rot_acu,2);
        $datos['ginecologia_ind_rot_acu'] = (float) number_format($ginecologia_ind_rot_acu,2);
        $datos['trauma_ind_rot_acu'] = (float) number_format($trauma_ind_rot_acu,2);
        $datos['total_ind_rot_acu'] = (float) number_format($total_ind_rot_acu,2);
        
        $datos['int_sust_men'] = (float) number_format($int_sust_men,2);
        $datos['med_int_int_sust_men'] = (float) number_format($med_int_int_sust_men,2);
        $datos['pediatria_int_sust_men'] = (float) number_format($pediatria_int_sust_men,2);
        $datos['ginecologia_int_sust_men'] = (float) number_format($ginecologia_int_sust_men,2);
        $datos['trauma_int_sust_men'] = (float) number_format($trauma_int_sust_men,2);
        $datos['total_int_sust_men'] = (float) number_format($total_int_sust_men,2);
        
        $datos['int_sust_acu'] = (float) number_format($int_sust_acu,2);
        $datos['med_int_int_sust_acu'] = (float) number_format($med_int_int_sust_acu,2);
        $datos['pediatria_int_sust_acu'] = (float) number_format($pediatria_int_sust_acu,2);
        $datos['ginecologia_int_sust_acu'] = (float) number_format($ginecologia_int_sust_acu,2);
        $datos['trauma_int_sust_acu'] = (float) number_format($trauma_int_sust_acu,2);
        $datos['total_int_sust_acu'] = (float) number_format($total_int_sust_acu,2);
        
        $datos['tasa_mort_bruta'] = (float) number_format($tasa_mort_bruta,2);
        $datos['med_int_tasa_mort_bruta'] = (float) number_format($med_int_tasa_mort_bruta,2);
        $datos['pediatria_tasa_mort_bruta'] = (float) number_format($pediatria_tasa_mort_bruta,2);
        $datos['ginecologia_tasa_mort_bruta'] = (float) number_format($ginecologia_tasa_mort_bruta,2);
        $datos['trauma_tasa_mort_bruta'] = (float) number_format($trauma_tasa_mort_bruta,2);
        $datos['total_tasa_mort_bruta'] = (float) number_format($total_tasa_mort_bruta,2);
        
        $datos['tasa_mort_ajus'] = (float) number_format($tasa_mort_ajus,2);
        $datos['med_int_tasa_mort_ajus'] = (float) number_format($med_int_tasa_mort_ajus,2);
        $datos['pediatria_tasa_mort_ajus'] = (float) number_format($pediatria_tasa_mort_ajus,2);
        $datos['ginecologia_tasa_mort_ajus'] = (float) number_format($ginecologia_tasa_mort_ajus,2);
        $datos['trauma_tasa_mort_ajus'] = (float) number_format($trauma_tasa_mort_ajus,2);
        $datos['total_tasa_mort_ajus'] = (float) number_format($total_tasa_mort_ajus,2);
        
        $datos['dias_est_men'] = (float) number_format($dias_est_men,2);
        $datos['med_int_dias_est_men'] = (float) number_format($med_int_dias_est_men,2);
        $datos['pediatria_dias_est_men'] = (float) number_format($pediatria_dias_est_men,2);
        $datos['ginecologia_dias_est_men'] = (float) number_format($ginecologia_dias_est_men,2);
        $datos['trauma_dias_est_men'] = (float) number_format($trauma_dias_est_men,2);
        $datos['total_dias_est_men'] = (float) number_format($total_dias_est_men,2);
        
        $datos['dias_est_acu'] = (float) number_format($dias_est_acu,2);
        $datos['med_int_dias_est_acu'] = (float) number_format($med_int_dias_est_acu,2);
        $datos['pediatria_dias_est_acu'] = (float) number_format($pediatria_dias_est_acu,2);
        $datos['ginecologia_dias_est_acu'] = (float) number_format($ginecologia_dias_est_acu,2);
        $datos['trauma_dias_est_acu'] = (float) number_format($trauma_dias_est_acu,2);
        $datos['total_dias_est_acu'] = (float) number_format($total_dias_est_acu,2);
        
        return $datos;
        }
    }
}

?>