<?php

class MD_indicador extends CI_Model 
{
    //PARA SABER CUANDO SE ESTA PIDIENDO PUBLICAR
    private $publicar;
    
    function __construct($publicar=0)
    {
        $this->publicar = $publicar;
    }
    function eval_indicador ($id,$nivel,$nivel_aten)
    {
        //$this->output->enable_profiler();
        //$this->db->select("h_descrip,clave,id_programa,descripcion_programa");
        //$this->db->distinct();
        if($nivel == "programa")
            $this->db->where('id_medida',$id);
        if($nivel == "indicador")
            $this->db->where('id_programa',$id);
        if($nivel == "medida")
            if($nivel_aten == '2do')
                $this->db->where('id_programa',$id);
            else
                $this->db->where('id_ind',$id);
        if($nivel_aten == 'juris')
            $consulta = $this->db->get('vw_eval_estruct');
        if($nivel_aten == '1er')
            $consulta = $this->db->get('vw_eval_estruct_1er');
        if($nivel_aten == '2do')
            $consulta = $this->db->get('vw_eval_estruct_2do');
        return $consulta->result_array();
    }
    function jurisdicciones ($nivel)
    {
        if($nivel == 'juris')
            $consulta = $this->db->get('jurisdiccion');
        if($nivel == '1er')
        {
            $this->db->where('tipologia','H.C.');
            $consulta = $this->db->get('um');
        }
        if($nivel == '2do')
        {
            $this->db->where_in('tipologia',array('H.C.','H.G.','H.E.'));
            $consulta = $this->db->get('um');
        }
        return $consulta->result_array();
    }
    function eval_guardar_juris($datos,$nivel)
    {
        if($nivel == 'juris')
            return $this->db->insert_batch('eval_reporte', $datos);
        if($nivel == '1er')
            return $this->db->insert_batch('eval_reporte_1er', $datos);
        if($nivel == '2do')
            return $this->db->insert_batch('eval_reporte_2do', $datos);
    }
    function uni_med_calc($id)
    {
        $this->db->where('id',$id);
        $uni_med = $this->db->get('vw_reporte_juris2');
        
        return $uni_med->result_array();
    }
    function uni_med_calc_hc($id)
    {
        $this->db->where('id',$id);
        $uni_med = $this->db->get('vw_reporte_hc');
        
        return $uni_med->result_array();
    }
    function uni_med_calc_2n($id)
    {
        $this->db->where('id',$id);
        $uni_med = $this->db->get('vw_reporte_2n');
        
        return $uni_med->result_array();
    }
    function valor_ind($nombre,$mes,$anio,$juris,$nivel,$acumular,$dividir,$qhc = 0)
    {
       // $this->output->enable_profiler();
       // print_r($nivel);
       // echo br();
       // print_r($juris);
        //echo br();
        
        $this->db->where('nombre',$nombre);
        $acc = $this->db->get('indicadores');

        //echo sizeof($acc->result_array());
        
        foreach($acc->result_array() as $ren)
        {
            //print_r($ren);
            $this->db->select('mes,anio');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_acc',$ren['id']);
            if($acumular != 'NO')
                $this->db->where('mes <=', (integer) $mes);
            else
                $this->db->where('mes',(integer) $mes);
            $this->db->where('anio',$anio);
            if($nivel == 'soloJuris')
            {
                if($qhc == 1)
                    $this->db->where_not_in('tipologia',array('H.C.','H.E.','H.G.'));
            }
            elseif($nivel != 'todas')
                    $this->db->where('tipologia',$nivel);
                else
                    $this->db->where_not_in('tipologia',array('H.G.','H.E.'));
                
            if($juris != "todas")
                $this->db->where("cve_jur",$juris);
           /* if($qhc == 1){
                $hospitales = array('H.C.', 'H.G.', 'H.E.');
                $this->db->where_not_in("tipologia",$hospitales);
            }*/
           // $this->db->where_in('id_unidad',$juris);
           if($this->publicar)
                $valor = $this->db->get('vw_evaluacion_juris');
           else{
                //echo "CONSULTA A LOS ANT. PUBLICAR = ".$this->publicar."<br />";
                $valor = $this->db->get('vw_evaluacion_juris_ant');
           }
            //$valor = $this->db->get('reporte');
            //print_r($valor->result_array());
        }
        if(isset($valor))
        {            
            $dato = array();
            $contador = 0;                        
            foreach($valor->result() as $ren)
            {                
                if($dividir != '' && $dividir != 0)
                {                                    
                    $dato = array(array('mes' => $mes,
                          'logro' => $ren->logro/$dividir ,
                          'anio' => $anio));
                }
                else
                    $dato = array(array('mes' => $mes,
                          'logro' => $ren->logro,
                          'anio' => $anio));
                $contador++;
                //if($dividir != '' && $dividir != 0)                                                          
                //echo 'VALOR ANTES: '.$ren->logro.' VALOR DESPUES: '.$ren->logro / $dividir .' CONTADOR: '.$contador.'<BR />';                                                                    
                            
            }       
            return $dato;
        }
        else
            return array(array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio));
    }
    function um($tipo)
    {
        $this->db->where_in('tipologia',$tipo);
        $consulta = $this->db->get('um');
        
        $res['todas'] = "ESTATAL";
        
        foreach($consulta->result_array() as $row)
        {
            $res[$row['clues']] = $row['nombre'];
        }
        return $res;
    }
    function um_evaluacion($tipo)
    {
        $this->db->where_in('tipologia',$tipo);
        $consulta = $this->db->get('um');
        
        //$res['todas'] = "ESTATAL";
        
        foreach($consulta->result_array() as $row)
        {
            $res[] = $row['clues'];
        }
        return $res;
    }
    function um_txt($um)
    {
        if($um != 'todas')
        {
            $this->db->where('clues',$um);
            $consulta = $this->db->get('um');
            foreach($consulta->result_array() as $row)
                $res = $row['nombre'];
        }
        else
            $res = "TODOS";
        return $res;
    }
    function valor_ind_hc($nombre,$mes,$anio,$hc,$nivel,$acumular,$dividir)
    {
       // $this->output->enable_profiler();
       // print_r($nombre);
       // echo br();
        
        $this->db->where('nombre',$nombre);
        $acc = $this->db->get('indicadores');

        //echo sizeof($acc->result_array());
        
        foreach($acc->result_array() as $ren)
        {
            //print_r($ren);
            $this->db->select('mes,anio,nombre');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_acc',$ren['id']);
            if($acumular != 'NO')
                $this->db->where('mes <=', (integer) $mes);
            else
                $this->db->where('mes',$mes);
            $this->db->where('anio',$anio);
            if($nivel != 'todas')
                $this->db->where('tipologia',$nivel);
            if($hc != "todas")
                $this->db->where("clues",$hc);
           // $this->db->where_in('id_unidad',$juris);
            if($this->publicar)
                $valor = $this->db->get('vw_evaluacion_juris');
            else
                $valor = $this->db->get('vw_evaluacion_juris_ant');
            //$valor = $this->db->get('reporte');
            //print_r($valor->result_array());
        }
        if(isset($valor))
        {
            $dato = array();
            $contador = 0;                        
            foreach($valor->result() as $ren)
            {                
                if($dividir != '' && $dividir != 0)
                {                                    
                    $dato = array(array('mes' => $mes,
                          'logro' => $ren->logro/$dividir ,
                          'anio' => $anio));
                }
                else
                    $dato = array(array('mes' => $mes,
                          'logro' => $ren->logro,
                          'anio' => $anio));
                $contador++;
               // if($dividir != '' && $dividir != 0)                                                          
               // echo 'VALOR ANTES: '.$ren->logro.' VALOR DESPUES: '.$ren->logro / $dividir .' CONTADOR: '.$contador.'<BR />';                                                                    
                            
            }       
            return $dato;
        }
        else
            return array(array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio));
    }
    function valor_ind_2n($nombre,$mes,$anio,$hc,$nivel,$acumular)
    {
        //$this->output->enable_profiler();
        //print_r($nombre);
        
        $this->db->where('nombre',$nombre);
        $acc = $this->db->get('indicadores');

        //echo sizeof($acc->result_array());
        
        foreach($acc->result_array() as $ren)
        {
            //print_r($ren);
            $this->db->select('mes,anio');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_acc',$ren['id']);
            if($acumular != 'NO')
                $this->db->where('mes <=', (integer) $mes);
            else
                $this->db->where('mes', (integer) $mes);
            $this->db->where('anio',$anio);
            if($nivel != 'todas')
                $this->db->where_in('tipologia',$nivel);
            if($hc != "todas")
                $this->db->where_in("clues",$hc);
           // $this->db->where_in('id_unidad',$juris);
            if($this->publicar)
                $valor = $this->db->get('vw_evaluacion_juris');
            else
                $valor = $this->db->get('vw_evaluacion_juris_ant');
            //$valor = $this->db->get('reporte');
            //print_r($valor->result_array());
        }
        if(isset($valor))
            return $valor->result_array();
        else
            return array(array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio));
    }
    function valor_ind_2n_saeh($nombre,$mes,$anio,$hc,$nivel,$acumular)
    {
        //$this->output->enable_profiler();
        //print_r($nombre);
        
        switch($mes)
        {
            case 1 : $mes = "01";
                    break;
            case 2 : $mes = "02";
                    break;
            case 3 : $mes = "03";
                    break;
            case 4 : $mes = "04";
                    break;
            case 5 : $mes = "05";
                    break;
            case 6 : $mes = "06";
                    break;
            case 7 : $mes = "07";
                    break;
            case 8 : $mes = "08";
                    break;
            case 9 : $mes = "09";
                    break;
        }
        
        if($hc != "todas")
        {
            $this->db->where('clues',$hc);
            $consulta = $this->db->get('um');
            foreach($consulta->result() as $ren)
                $id_um = $ren->id;
        }
        
        
        $ind = (int) substr($nombre,4,strlen($nombre));
        //echo $ind."<br />";
        
        $this->db->where('id_ind',$ind);        
        if($acumular != 'NO')
                $this->db->where('mes <=', $mes);
            else
                $this->db->where('mes', $mes);
        $this->db->where('anio',$anio);
        if($hc != "todas")
            $this->db->where('id_um',$id_um);
        //else
            $this->db->select_sum('dato');
        
        if($this->publicar)
            $consulta = $this->db->get('saeh_reporte');
        else
            $consulta = $this->db->get('saeh_reporte_ant');

        //echo sizeof($acc->result_array());
        foreach($consulta->result() as $ren)
        {
            //print_r($ren);
            $resultado = array( array(
                            'mes' => $mes,
                            'logro' => $ren->dato,
                            'anio' => $anio
                        ));
        }
        
        if(isset($resultado))
            return $resultado;
        else
            return array(array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio));
    }
    function valor_ind_jur($nombre,$mes,$anio,$juris)
    {
        //$this->output->enable_profiler();
        //print_r($nombre);
        
        $this->db->like('calculo',$nombre);
        $acc = $this->db->get('eval_unidad_medida');

        //echo sizeof($acc->result_array());
        
        foreach($acc->result_array() as $ren)
        {
            //print_r($ren);
            $this->db->select('mes,anio');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_unidad_medida',$ren['id']);
            $this->db->where('mes <=', (integer) $mes);
            $this->db->where('anio',$anio);
            if($juris != 'todas')
                $this->db->where('no_Jur',$juris);
           // $this->db->where_in('id_unidad',$juris);
            if($this->publicar)
                $valor = $this->db->get('vw_evaluacion_juris_resp');
            else
                $valor = $this->db->get('vw_evaluacion_juris_resp_ant');
            //$valor = $this->db->get('eval_reporte');
            //print_r($valor->result_array());
        }
        if(isset($valor))
            return $valor->result_array();
        else
            return array(array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio));
    }
    function valor_ind_hc_resp($nombre,$mes,$anio,$juris)
    {
        //$this->output->enable_profiler();
        //echo strlen($juris)."<br />";
        //print_r($nombre);
        
        //
        //PARCHE PARA QUE FUNCIONE LOS RESPONSABLES EN REPORTE 1ER NIVEL//
        if(strlen($juris) > 3 && $juris != 'todas')
        {
            $this->load->model("md_unidad_medica");
            $juris = $this->md_unidad_medica->cluesID($juris);
        }
        //////////////////////////////////////////////////////////////////
        
        $this->db->like('calculo',$nombre);
        $acc = $this->db->get('eval_unidad_medida_1er');

        //echo sizeof($acc->result_array());
        
        foreach($acc->result_array() as $ren)
        {
            //print_r($ren);
            $this->db->select('mes,anio');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_unidad_medida',$ren['id']);
            $this->db->where('mes <=', (integer) $mes);
            $this->db->where('anio',$anio);
            if($juris != 'todas')
                $this->db->where('id',$juris);
           // $this->db->where_in('id_unidad',$juris);
            if($this->publicar)
                $valor = $this->db->get('vw_evaluacion_1er_resp');
            else
                $valor = $this->db->get('vw_evaluacion_1er_resp_ant');
            //$valor = $this->db->get('eval_reporte');
            //print_r($valor->result_array());
        }
        if(isset($valor))
            return $valor->result_array();
        else
            return array(array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio));
    }
    function valor_ind_2do_resp($nombre,$mes,$anio,$juris)
    {
        //$this->output->enable_profiler();
        //print_r($nombre);
        
        $this->db->like('calculo',$nombre);
        $acc = $this->db->get('eval_unidad_medida_2do');

        //echo sizeof($acc->result_array());
        
        foreach($acc->result_array() as $ren)
        {
            //print_r($ren);
            $this->db->select('mes,anio');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_unidad_medida',$ren['id']);
            $this->db->where('mes <=', (integer) $mes);
            $this->db->where('anio',$anio);
            if($juris != 'todas')
                $this->db->where('id',$juris);
           // $this->db->where_in('id_unidad',$juris);
            if($this->publicar)
                $valor = $this->db->get('vw_evaluacion_2do_resp');
            else
                $valor = $this->db->get('vw_evaluacion_2do_resp_ant');
            //$valor = $this->db->get('eval_reporte');
            //print_r($valor->result_array());
        }
        if(isset($valor))
            return $valor->result_array();
        else
            return array(array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio));
    }
    function um_juris ($juris)
    {
        $this->db->where('id',$juris);
        $juris = $this->db->get('jurisdiccion');
        
        foreach($juris->result_array() as $ren){
            $this->db->where('cve_jur',$ren['no_Jur']);
            $consulta = $this->db->get('um');
        }
        
        foreach($consulta->result_array() as $ren)
            $dato[] = $ren['id']; 
        
        return $dato;
    }
}
?>