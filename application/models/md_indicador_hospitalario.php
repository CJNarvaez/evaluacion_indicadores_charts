<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

Class Md_indicador_hospitalario extends CI_Model
{
    public $id,$clave,$descripcion,$programa,$calculo,$apartadoSIS,$zacatecas,
            $fresnillo,$jerez,$loreto,$calera,$trancoso,$ojocaliente,$sombrerete,$valparaiso,
            $juan_aldama,$jalpa,$juchipila,$tabasco,$nochistlan,$villa_de_cos,$mujer,$psiquiatrico,
            $acumular,$j1,$j2,$j3,$j4,$j5,$j6,$j7,$qhc,$publicar;
            
 /**
 * CONSTRUCTOR
 * @recibe INT id del indicador
 * @recibe STR tipo de reporte "juris","hc","2n"
 */
    function __construct($id = NULL,$reporte = NULL,$publicar=0)
    {
        //echo $reporte;
        $this->publicar = $publicar;
        
        //$this->output->enable_profiler();
        if($id != NULL)
        {
            $this->db->where('id',$id);
            if($reporte == 'juris' || $reporte == 'jurishc')
                $consulta = $this->db->get('eval_unidad_medida');            
            elseif($reporte == 'hc')
                $consulta = $this->db->get('eval_unidad_medida_1er');
            elseif($reporte == '2n')
                $consulta = $this->db->get('eval_unidad_medida_2do');
            
            foreach($consulta->result() as $ren)
            {
                $this->id = $ren->id;
                $this->clave = $ren->clave;
                $this->descripcion = $ren->descripcion;
                $this->programa = $this->programa($ren->programa,$reporte);
                $this->calculo = $ren->calculo;
                $this->apartadoSIS = $ren->apartado_sis;
                
                echo $this->juan_aldama;
                
                if($reporte != 'juris' && $reporte != 'jurishc'){
                    $this->jalpa = (int) $ren->jalpa;
                    $this->juan_aldama = (int) $ren->juan_aldama;
                    $this->juchipila = (int) $ren->juchipila;
                    $this->nochistlan = (int) $ren->nochistlan;
                    $this->ojocaliente = (int) $ren->ojocaliente;
                    $this->sombrerete = (int) $ren->sombrerete;
                    $this->tabasco = (int) $ren->tabasco;
                    $this->valparaiso = (int) $ren->valparaiso;
                    $this->trancoso = (int) $ren->trancoso;
                    $this->calera = (int) $ren->calera;
                    $this->villa_de_cos = (int) $ren->villa_de_cos;
                    
                    if($reporte == '2n')
                    {
                        $this->zacatecas = (int) $ren->zacatecas;
                        $this->fresnillo = (int) $ren->fresnillo;
                        $this->jerez = (int) $ren->jerez;
                        $this->loreto = (int) $ren->loreto;
                        $this->mujer = (int) $ren->mujer;
                        $this->psiquiatrico = (int) $ren->psiquiatrico;
                    }
                }
                else
                {
                    if($reporte == 'jurishc')
                    {
                        $this->j1 = $ren->J1;
                        $this->j2 = $ren->J2;
                        $this->j3 = $ren->J3;
                        $this->j4 = $ren->J4;
                        $this->j5 = $ren->J5;
                        $this->j6 = $ren->J6;
                        $this->j7 = $ren->J7;
                    }
                    else
                    {
                        $this->j1 = $ren->juris1;
                        $this->j2 = $ren->juris2;
                        $this->j3 = $ren->juris3;
                        $this->j4 = $ren->juris4;
                        $this->j5 = $ren->juris5;
                        $this->j6 = $ren->juris6;
                        $this->j7 = $ren->juris7;
                        $this->qhc = $ren->qhc;
                    }
                    
                }

                $this->acumular = $ren->acumular;
                
            }
        }
    }
    /**
     * CONSULTA UN DATO EN UN MES
     * @return INT Dato
     */
    function reporta_mes($mes,$anio,$hc,$reporte,$acumular = 1,$qhc = 0)
    {
        $var = explode('+',$this->calculo);
        $this->load->model('md_indicador');
        $indicador = new MD_indicador($this->publicar);
        //print_r($var);
        
        //CONSULTA LOS VALORES DE LA VARIABLE UNO POR UNO PARA SUMARLOS DESPUES
        foreach ($var as $ren){
           // print_r($ren);
           // echo br();
           
           $operacion = strstr($ren,'/');
                //echo strlen($operacion)."<br />";
                if(strlen($operacion) != 0)
                    $ren = substr($ren,0,strlen($ren)-2);
                //echo br();
           $operacion = substr($operacion,1,1);
           
           $cadena = substr($ren,0,4);
           //print_r($cadena);
           //echo br();
            if($cadena != "RESP")
            {
                if($cadena != "SAEH")
                {
                    //echo $qhc;
                    //echo $mes;
                    //echo $anio;
                    //echo $hc;
                    //echo $acumular;
                    if($reporte == 'juris')
                        $sumar[$ren] = $indicador->valor_ind($ren,$mes,$anio,$hc,'soloJuris',$acumular,$operacion,$qhc);
                    elseif($reporte == 'jurishc')
                        $sumar[$ren] = $indicador->valor_ind($ren,$mes,$anio,$hc,'todas',$acumular,$operacion,$qhc);                                            
                    elseif($reporte == 'hc')
                        $sumar[$ren] = $indicador->valor_ind_hc($ren,$mes,$anio,$hc,'H.C.',$acumular,$operacion);
                    elseif($reporte == '2n')
                        $sumar[$ren] = $indicador->valor_ind_2n($ren,$mes,$anio,$hc,array('H.C.','H.E.','H.G.'),$acumular);
                }
                else
                    $sumar[$ren] = $indicador->valor_ind_2n_saeh($ren,$mes,$anio,$hc,array('H.C.','H.E.','H.G.'),$acumular);
            }
            else{
                //PRUEBA PARA CONSULTAR LOS RESPONSABLES DE PROGRAMA 18/09/2014
                if($reporte == "juris")
                    $sumar[$ren] = $indicador->valor_ind_jur($ren,$mes,$anio,$hc);
                elseif($reporte == 'hc')
                {
                    //PARCHE PORQUE EN ESTA CLASE SE USA EL CLUES EN LA VARIABLE $hc Y MI FUNCION DE
                    //md_indicador RECIBE UN ID EN ENTERO
                    $this->db->where('clues',$hc);
                    $consulta2 = $this->db->get('um');
                    foreach($consulta2->result() as $ren2){
                        //print_r($ren);
                        $hc_id = $ren2->id;
                    }
                    //print_r($hc_id);
                    ////////////////////////////////////////////////////////////////////////////////
                    
                    $sumar[$ren] = $indicador->valor_ind_hc_resp($ren,$mes,$anio,$hc_id);
                }
                elseif($reporte == '2n'){
                    //PARCHE PORQUE EN ESTA CLASE SE USA EL CLUES EN LA VARIABLE $hc Y MI FUNCION DE
                    //md_indicador RECIBE UN ID EN ENTERO
                    $this->db->where('clues',$hc);
                    $consulta2 = $this->db->get('um');
                    foreach($consulta2->result() as $ren2){
                        //print_r($ren);
                        $hc_id = $ren2->id;
                    }
                    //print_r($hc_id);
                    ////////////////////////////////////////////////////////////////////////////////
                    
                    $sumar[$ren] = $indicador->valor_ind_2do_resp($ren,$mes,$anio,$hc_id);
                }
            }
          // print_r($sumar[$ren]);
          //echo $juris."<br />";
        }
        $total = 0;
            //print_r($sumar['SAEH6']);
        foreach($sumar as $ren)
            foreach($ren as $row)
                // print_r($row['logro']);
                $total = $total + $row['logro'];
        return $total;
    }
    
    /**
     * Regresa la meta de la unidad pedida
     *     @recibe STR
     *     @regresa INT
     */
    public function meta($clues)
    {
        switch ($clues)
        {
            case 'ZSSSA000572': return $this->jalpa;
            case 'ZSSSA000695': return $this->juan_aldama;
            case 'ZSSSA000700': return $this->juchipila;
            case 'ZSSSA000922': return $this->nochistlan;
            case 'ZSSSA001016': return $this->ojocaliente;
            case 'ZSSSA001313': return $this->sombrerete;
            case 'ZSSSA001395': return $this->tabasco;
            case 'ZSSSA001506': return $this->valparaiso;
            case 'ZSSSA001861': return $this->trancoso;
            case 'ZSSSA002136': return $this->calera;
            case 'ZSSSA002141': return $this->villa_de_cos;
            case 'ZSSSA000152': return $this->fresnillo;
            case 'ZSSSA000613': return $this->jerez;
            case 'ZSSSA012853': return $this->loreto;
            case 'ZSSSA013143': return $this->zacatecas;
            case 'ZSSSA013172': return 0;
            case 'ZSSSA012450': return $this->mujer;
            case 'ZSSSA012771': return $this->psiquiatrico;
            case '01': return $this->j1;
            case '02': return $this->j2;
            case '03': return $this->j3;
            case '04': return $this->j4;
            case '05': return $this->j5;
            case '06': return $this->j6;
            case '07': return $this->j7;
        }
    }
    
    /**
     * REGRESA EL PROGRAMA DE UN INDICADOR
     * @recibe INT id del programa
     * @recibe STR Nombre del reporte
     * @return STR Nombre del programa
     */
    private function programa($id_programa,$reporte)
    {
        $this->db->where('id',$id_programa);
        
        if($reporte == 'juris' || $reporte == 'jurishc')
            $consulta = $this->db->get('eval_programa');
        if($reporte == 'hc')
            $consulta = $this->db->get('eval_programa_1er');
        if($reporte == '2n')
            $consulta = $this->db->get('eval_programa_2do');
        foreach($consulta->result() as $ren)
        {
            $descripcion = $ren->descripcion;
        }
        return $descripcion;
    }
}

?>