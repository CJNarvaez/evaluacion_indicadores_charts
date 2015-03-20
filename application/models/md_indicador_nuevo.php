<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_indicador_nuevo extends CI_Model
{
    public $id, $clave, $descripcion, $calculo, $calculando, $acumular, $sumar, $logro = 0;
    
    function __construct()
    {
        parent::__construct();
    } 
}

/**
 * INDICADOR JURISDICCIONAL
 * @param INT id indicador
 */
class Md_indicador_jur extends Md_indicador_nuevo
{
    public $j1,$j2,$j3,$j4,$j5,$j6,$j7;
    
    function __construct($id = NULL)
    {
        parent::__construct();
        if($id != NULL)
        {
            $this->db->where('id',$id);
            $consulta = $this->db->get('eval_unidad_medida');
            
            foreach($consulta->result() as $ren)
            {
                $this->id = $ren->id;
                $this->clave = $ren->clave;
                $this->descripcion = $ren->descripcion;
                $this->calculo = $ren->calculo;
                $this->acumular = $ren->acumular;
                $this->j1 = $ren->J1;
                $this->j2 = $ren->J2;
                $this->j3 = $ren->J3;
                $this->j4 = $ren->J4;
                $this->j5 = $ren->J5;
                $this->j6 = $ren->J6;
                $this->j7 = $ren->J7;                
            }
            $this->calculando = explode('+',$this->calculo);
        }
    }
    
    /**
     * REPORTE MENSUAL DEL INDICADOR EN UN MES
     * @param int mes
     * @param int año
     * @param str juris
     */
    function reportar_mes($mes, $anio, $juris)
    {
        //$this->output->enable_profiler();
        //CARGA MODELO PARA CONSULTAR VALORES     ****(OBSOLETO)
        //$this->load->model('md_valores_ind');
        //$logro = new Md_valores_ind;
        
        foreach($this->calculando as $ren)
        {
            //SEPARA EL SIMBOLO DE DIVISION SI ES QUE EXISTE Y LO GUARDA EN $operacion
            $operacion = strstr($ren,'/');
                if(strlen($operacion) != 0)
                    $ren = substr($ren,0,strlen($ren)-2);
            $operacion = substr($operacion,1,1);
            
            //TOMA LAS PRIMERAS 4 LETRAS DEL NOMBRE DE LA VARIABLE
            $cadena = substr($ren,0,4);
            
            //SI NO ES CAPTURADA POR UN RESPONSABLE DE PROGRAMA
            if($cadena != "RESP")
            {
                $this->sumar[$ren] = $this->consulta_ind($ren,$mes,$anio,$juris,$this->acumular,$operacion);
            }
            //CUANDO LO CAPTURA EL RESPONSABLE DE PROGRAMA
            else
                $this->sumar[$ren] = $this->consulta_resp($ren,$mes,$anio,$juris);
                
        }
       // print_r($this->sumar);
        
        //SUMA DE TODOS LOS DATOS CONSULTADOS
        foreach($this->sumar as $sumando)
        {
            $this->logro = $this->logro + $sumando['logro'];
        }
        echo $this->logro;
    }
    
    /**
     * CONSULTA LA BD PARA TRAER EL VALOR REPORTADO
     * @param str nombre
     * @param int mes
     * @param int año
     * @param str juris
     * @return array dato
     */
    private function consulta_resp($nombre, $mes, $anio, $juris)
    {
        //CONSULTA PARA RECUPERAR EL ID DEL INDICADOR RESP
        $this->db->like('calculo',$nombre);
        $acc = $this->db->get('eval_unidad_medida');
        
        //CONSULTA LOS VALORES REPORTADOS
        foreach($acc->result_array() as $ren)
        {
            $this->db->select('mes,anio');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_unidad_medida',$ren['id']);
            $this->db->where('mes <=', (integer) $mes);
            $this->db->where('anio',$anio);
            if($juris != 'todas')
                $this->db->where('no_Jur',$juris);
            $valor = $this->db->get('vw_evaluacion_juris_resp');
        }
        
        //SI EXISTE EL RESULTADO
        if(isset($valor))
        
        //SI EL RESULTADO TIENE DATOS
        if(sizeof($valor->result()) > 0)
        {
            foreach($valor->result() as $ren)
            {                
                $dato = array('mes' => $mes,
                          'logro' => $ren->logro,
                          'anio' => $anio);             
            } 
            
            return $dato;
        }
        else
            return array('mes' => $mes,
                              'logro' => 0,
                              'anio' => $anio);
    }
    
    /**
     * CONSULTA EL VALOR DE UNA VARIABLE DEL SIS
     * @param str nombre
     * @param int mes
     * @param int anio
     * @param int juris
     * @param str acumular eg. "NO"
     * @param bool dividir
     */
    private function consulta_ind($nombre,$mes,$anio,$juris,$acumular,$dividir)
    {        
        $this->db->where('nombre',$nombre);
        $acc = $this->db->get('indicadores');
        
        foreach($acc->result_array() as $ren)
        {
            $this->db->select('mes,anio');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_acc',$ren['id']);
            if($acumular != 'NO')
                $this->db->where('mes <=', (integer) $mes);
            else
                $this->db->where('mes',(integer) $mes);
            $this->db->where('anio',$anio);
            if($juris != "todas")
                $this->db->where("cve_jur",$juris);

            $valor = $this->db->get('vw_evaluacion_juris');
        }
        
        //SI EXISTE EL RESULTADO
        if(isset($valor))
        
        //SI EL RESULTADO TIENE DATOS
        if(sizeof($valor->result()) > 0)
        {            
            $dato = array();
            $contador = 0;                        
            foreach($valor->result() as $ren)
            {                
                if($dividir != '' && $dividir != 0)
                {                                    
                    $dato = array('mes' => $mes,
                          'logro' => $ren->logro/$dividir ,
                          'anio' => $anio);
                }
                else
                    $dato = array('mes' => $mes,
                          'logro' => $ren->logro,
                          'anio' => $anio);
                $contador++;              
            }       
            return $dato;
        }
        else
            return array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio);
    }
}

/**
 * INDICADOR PRIMER NIVEL
 * @param INT id indicador
 */
class Md_indicador_1er extends Md_indicador_nuevo
{
    public $calera,$trancoso,$ojocaliente,$sombrerete,$valparaiso,$juan_aldama,$jalpa,$juchipila,
            $tabasco,$nochistlan,$villa_de_cos;
    
    function __construct($id = NULL)
    {
        parent::__construct();
        if($id != NULL)
        {
            $this->db->where('id',$id);
            $consulta = $this->db->get('eval_unidad_medida_1er');
            
            foreach($consulta->result() as $ren)
            {
                $this->id = $ren->id;
                $this->clave = $ren->clave;
                $this->descripcion = $ren->descripcion;
                $this->calculo = $ren->calculo;
                $this->acumular = $ren->acumular;
                
                $this->calera = $ren->calera;
                $this->trancoso = $ren->trancoso;
                $this->ojocaliente = $ren->ojocaliente;
                $this->sombrerete = $ren->sombrerete;
                $this->valparaiso = $ren->valparaiso;
                $this->juan_aldama = $ren->juan_aldama;
                $this->jalpa = $ren->jalpa;
                $this->juchipila = $ren->juchipila;
                $this->tabasco = $ren->tabasco;
                $this->nochistlan = $ren->nochistlan;
                $this->villa_de_cos = $ren->villa_de_cos;
            }
            $this->calculando = explode('+',$this->calculo);
        }
    }
    
    /**
     * REPORTE MENSUAL DEL INDICADOR EN UN MES
     * @param int mes
     * @param int año
     * @param str hc
     */
    function reportar_mes($mes, $anio, $hc)
    {
        //$this->output->enable_profiler();
        //CARGA MODELO PARA CONSULTAR VALORES     ****(OBSOLETO)
        //$this->load->model('md_valores_ind');
        //$logro = new Md_valores_ind;
        
        foreach($this->calculando as $ren)
        {
            //SEPARA EL SIMBOLO DE DIVISION SI ES QUE EXISTE Y LO GUARDA EN $operacion
            $operacion = strstr($ren,'/');
                if(strlen($operacion) != 0)
                    $ren = substr($ren,0,strlen($ren)-2);
            $operacion = substr($operacion,1,1);
            
            //TOMA LAS PRIMERAS 4 LETRAS DEL NOMBRE DE LA VARIABLE
            $cadena = substr($ren,0,4);
            
            //SI NO ES CAPTURADA POR UN RESPONSABLE DE PROGRAMA
            if($cadena != "RESP")
            {
                $this->sumar[$ren] = $this->consulta_ind($ren,$mes,$anio,$hc,$this->acumular,$operacion);
            }
            //CUANDO LO CAPTURA EL RESPONSABLE DE PROGRAMA
            else
                $this->sumar[$ren] = $this->consulta_resp($mes,$anio,$hc);
                
        }
       // print_r($this->sumar);
        
        //SUMA DE TODOS LOS DATOS CONSULTADOS
        foreach($this->sumar as $sumando)
        {
            $this->logro = $this->logro + $sumando['logro'];
        }
        echo $this->logro;
    }
    
    /**
     * CONSULTA LA BD PARA TRAER EL VALOR REPORTADO
     * @param str nombre
     * @param int mes
     * @param int año
     * @param str juris
     * @return array dato
     */
    private function consulta_resp($mes, $anio, $hc)
    {
        $this->output->enable_profiler();
        //CONSULTA PARA RECUPERAR EL ID DEL INDICADOR RESP
     //   $this->db->like('calculo',$nombre);
     //   $acc = $this->db->get('eval_unidad_medida_1er');
        
        //CONSULTA LOS VALORES REPORTADOS
       // foreach($acc->result_array() as $ren)
       // {
            $this->db->select('mes,anio');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_unidad_medida',$this->id);
            $this->db->where('mes <=', (integer) $mes);
            $this->db->where('anio',$anio);
            if($hc != 'todas')
                $this->db->where('id_unidad',$hc);
            $valor = $this->db->get('vw_evaluacion_1er_resp');
       // }
        print_r($valor->result());
        //SI EXISTE EL RESULTADO
        if(isset($valor))
        
        //SI EL RESULTADO TIENE DATOS
        if(sizeof($valor->result()) > 0)
        {
            foreach($valor->result() as $ren)
            {                
                $dato = array('mes' => $mes,
                          'logro' => $ren->logro,
                          'anio' => $anio);             
            } 
            
            return $dato;
        }
        else
            return array('mes' => $mes,
                              'logro' => 0,
                              'anio' => $anio);
    }
    
    /**
     * CONSULTA EL VALOR DE UNA VARIABLE DEL SIS
     * @param str nombre
     * @param int mes
     * @param int anio
     * @param int juris
     * @param str acumular eg. "NO"
     * @param bool dividir
     */
    private function consulta_ind($nombre,$mes,$anio,$hc,$acumular,$dividir)
    {
        //$this->output->enable_profiler();
        $this->db->where('nombre',$nombre);
        $acc = $this->db->get('indicadores');
        
        foreach($acc->result_array() as $ren)
        {
            $this->db->select('mes,anio,nombre');
            $this->db->select_sum('logro','logro');
            $this->db->group_by('anio');
            $this->db->where('id_acc',$ren['id']);
            if($acumular != 'NO')
                $this->db->where('mes <=', (integer) $mes);
            else
                $this->db->where('mes',$mes);
            $this->db->where('anio',$anio);
            $this->db->where('tipologia',"H.C.");
            if($hc != "todas")
                $this->db->where("clues",$hc);
           // $this->db->where_in('id_unidad',$juris);
            $valor = $this->db->get('vw_evaluacion_juris');
        }
       // print_r($valor->result());
        //SI EXISTE EL RESULTADO
        if(isset($valor))
        
        //SI EL RESULTADO TIENE DATOS
        if(sizeof($valor->result()) > 0)
        {            
            $dato = array();
            $contador = 0;                        
            foreach($valor->result() as $ren)
            {                
                if($dividir != '' && $dividir != 0)
                {                                    
                    $dato = array('mes' => $mes,
                          'logro' => $ren->logro/$dividir ,
                          'anio' => $anio);
                }
                else
                    $dato = array('mes' => $mes,
                          'logro' => $ren->logro,
                          'anio' => $anio);
                $contador++;              
            }       
            return $dato;
        }
        else
            return array('mes' => $mes,
                          'logro' => 0,
                          'anio' => $anio);
    }
}

?>