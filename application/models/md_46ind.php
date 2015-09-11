<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_46ind extends CI_Model
{
    public $nombre,$total,$mes,$mesTxt,$anio,$num_vars,$den_vars,$var_nom,$numerador,$denominador,$juris,$noAcumular,$noJur,$logroEstatal;
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

        // EN LAS USUARIAS ACTIVAS NO SE ACUMULAN LOS DATOS
        if($this->noAcumular)
            $this->db->where('mes',$this->mes);
        else 
            $this->db->where('mes <=',$this->mes);

        $this->db->where('anio',$this->anio);
        
        //  CUANDO NO SE PIDE UNA JURIS
        if ($this->noJur) {
            $this->db->where('id_um',$this->juris);
        }
        else
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

    public function mesNumTxt()
    {
        switch ($this->mes) {
            case '01':
                $this->mesTxt = "Enero";
                break;
            case '02':
                $this->mesTxt = "Febrero";
                break;
            case '03':
                $this->mesTxt = "Marzo";
                break;
            case '04':
                $this->mesTxt = "Abril";
                break;
            case '05':
                $this->mesTxt = "Mayo";
                break;
            case '06':
                $this->mesTxt = "Junio";
                break;
            case '07':
                $this->mesTxt = "Julio";
                break;
            case '08':
                $this->mesTxt = "Agosto";
                break;
            case '09':
                $this->mesTxt = "Septiembre";
                break;
            case '10':
                $this->mesTxt = "Octubre";
                break;
            case '11':
                $this->mesTxt = "Noviembre";
                break;
            case '12':
                $this->mesTxt = "Diciembre";
                break;
            
            default:
                $this->mesTxt = "Vacio";
                break;
        }
    }
}

class nac_x_cesarea extends md_46ind
{
    public $cesareas,$nacimientos;
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "NACIMIENTOS POR CESAREA";
    }
    
    public function reporte($hospital)
    {
        //  $this->output->enable_profiler();
        
        //  formula para el calculo "cesareas/nacimientos x 100"
        //  el indicador 24 es partos distocicos
        $this->db->select_sum('dato');
        $this->db->group_by('id_ind');
        $this->db->where('id_ind',24);
        $this->db->where('mes <=',$this->mes);
        $this->db->where('anio',$this->anio);
        $this->db->where('id_um',$hospital);
        $consulta = $this->db->get('saeh_reporte');
        
        foreach($consulta->result() as $ren)
            $this->numerador = $ren->dato;
        
        //  el indicador 22 es partos
        $this->db->select_sum('dato');
        $this->db->group_by('id_ind');
        $this->db->where('id_ind',22);
        $this->db->where('mes <=',$this->mes);
        $this->db->where('anio',$this->anio);
        $this->db->where('id_um',$hospital);
        $consulta = $this->db->get('saeh_reporte');
        
        foreach($consulta->result() as $ren)
            $this->denominador = $ren->dato;
        
        if( $this->denominador != 0)
            $this->total = $this->numerador / $this->denominador * 100;
        else
            $this->total = 0;
    }

    public function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de los Hospitales Generales y la Mujer
        //  HG FRESNILLO, HG LORETO, HG JEREZ, MUJER
        $idHosp = array(13,231,57,203);

        //  Bucle para capturar cada uno de los logros
        foreach ($idHosp as $id) {
            $this->reporte($id);
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100;
        else
            $this->logroEstatal['estatal'] = 0;
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
        //echo "destruyendo";
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
    public function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
class usuarias_act_pf extends Md_46ind
{
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "USUARIAS ACTIVAS DE P.F. X 100 M.E.F.U.";
        $this->noAcumular = 1;
        
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
    public function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}

/**
 *  MORBILIDAD POR T.B. PULMONAR
 *  Casos Nuevos / Población Total x 100,000
 */
class morbTbPulmonar extends Md_46ind
{
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "MORBILIDAD POR T.B. PULMONAR";
    }

    function reporte()
    {
        //  1. Obten casos Nuevos (Numerador ind. 24)
        //  guarda en $this->numerador
        $this->db->select_sum('logro');
        $this->db->group_by('id_ind');
        $this->db->where('id_ind', 24);
        $this->db->where('jurisdiccion', $this->juris);
        $anioMes = ($this->anio * 100) + (int) $this->mes;
        $this->db->where('aniomes <=', $anioMes);
        $consulta = $this->db->get('46ind_reporte');
        foreach ($consulta->result() as $res)
            $this->numerador = $res->logro;
        
        //  2. Obtener la poblacion total
        //  guarda en $this->denominador
        $this->db->where('jurisdiccion', $this->juris);
        $this->db->where('anio', $this->anio);
        $consulta = $this->db->get('46ind_poblacion');

        foreach ($consulta->result() as $res)
            $this->denominador = $res->total;

        //  Casos Nuevos / Población Total x 100,000
        //  Guardar en $this->total
        $this->total = $this->numerador / $this->denominador * 100000;
    }

    function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100000;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}

/**
 * % DE CASOS NVOS. T.B. CON TAES TERMINADO
 */
class nuevosTbTaesTerm extends Md_46ind
{
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "CASOS NVOS. T.B. CON TAES TERMINADO";
    }

    function reporte()
    {
        $this->denominador = 0;
        //  1. Obtener TAES Terminado (Numerador ind 25)
        //  guardar en $this->numerador
        //  2. Obtener Ingresos (Denominador ind 25)
        //  gardar en $this->denominador
        $this->db->select('num_den');
        $this->db->select_sum('logro');
        $this->db->group_by('num_den');
        $this->db->where('id_ind', 25);
        $anioMes = $this->anio * 100 + (int) $this->mes;
        $this->db->where('anioMes <=', $anioMes);
        $this->db->where('jurisdiccion', $this->juris);
        $consulta = $this->db->get('46ind_reporte');
        foreach ($consulta->result() as $res)
            if ($res->num_den)
                $this->numerador = $res->logro;
            else
                $this->denominador = $res->logro;
            
        //  TAES / Ingresos *100
        //  guardar en $this->total
        if ($this->denominador != 0)
            $this->total = $this->numerador / $this->denominador *100;
        else
            $this->total = 0;
    }
    function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
/**
 * Morbilidad por Gonorrea
 */
class morbGonorrea extends Md_46ind
{
    
    function __construct()
    {
        parent::__construct();
        $this->nombre = "MORBILIDAD POR GONORREA";
    }
    function reporte()
    {
        // 1. Obtener Casos Nuevos (ind 26) Numerador
        $this->db->where('id_ind', 26);
        $this->db->select_sum('logro');
        $anioMes = $this->anio * 100 + (int) $this->mes;
        $this->db->where('anioMes <=', $anioMes);
        $this->db->where('jurisdiccion', $this->juris);
        $consulta = $this->db->get('46ind_reporte');
        foreach ($consulta->result() as $res)
            $this->numerador = $res->logro;

        // 2. Obtener la poblacion Denominador
        $this->db->where('jurisdiccion', $this->juris);
        $this->db->where('anio', $this->anio);
        $consulta = $this->db->get('46ind_poblacion');

        foreach ($consulta->result() as $res)
            $this->denominador = $res->total;

        // 3. Casos Nuevos / Poblacion * 100000
        // 4. Guardar en $this->total
        $this->total = $this->numerador / $this->denominador * 100000;
    }
    function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100000;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
/**
 * MORBILIDAD X SIDA SEGÚN FECHA DE DX
 */
class morbSida extends Md_46ind
{
    
    function __construct()
    {
        parent::__construct();
        $this->nombre = "MORBILIDAD X SIDA SEGÚN FECHA DE DX";
    }
    function reporte()
    {
        // 1. Obtener Casos Nuevos (ind 29) Numerador
        $this->db->where('id_ind', 29);
        $this->db->select_sum('logro');
        $anioMes = $this->anio * 100 + (int) $this->mes;
        $this->db->where('anioMes <=', $anioMes);
        $this->db->where('jurisdiccion', $this->juris);
        $consulta = $this->db->get('46ind_reporte');
        foreach ($consulta->result() as $res)
            $this->numerador = $res->logro;

        // 2. Obtener la poblacion Denominador
        $this->db->where('jurisdiccion', $this->juris);
        $this->db->where('anio', $this->anio);
        $consulta = $this->db->get('46ind_poblacion');

        foreach ($consulta->result() as $res)
            $this->denominador = $res->total;

        // 3. Casos Nuevos / Poblacion * 100000
        // 4. Guardar en $this->total
        $this->total = $this->numerador / $this->denominador * 100000;
    }
    function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100000;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
/**
 * CASOS NUEVOS DE SIFILIS CONGENITA
 */
class casosNuevosSifilis extends Md_46ind
{
    
    function __construct()
    {
        parent::__construct();
        $this->nombre = "CASOS NUEVOS DE SIFILIS CONGENITA";
    }
    function reporte()
    {
        // 1. Obtener Casos Nuevos (ind 27) Numerador
        $this->db->where('id_ind', 27);
        $this->db->select_sum('logro');
        $anioMes = $this->anio * 100 + (int) $this->mes;
        $this->db->where('anioMes <=', $anioMes);
        $this->db->where('jurisdiccion', $this->juris);
        $consulta = $this->db->get('46ind_reporte');
        foreach ($consulta->result() as $res)
            $this->numerador = $res->logro;

        // 2. Obtener la poblacion Denominador
        $this->db->where('jurisdiccion', $this->juris);
        $this->db->where('anio', $this->anio);
        $consulta = $this->db->get('46ind_poblacion');

        foreach ($consulta->result() as $res)
            $this->denominador = $res->total;

        // 3. Casos Nuevos / Poblacion * 100000
        // 4. Guardar en $this->total
        $this->total = $this->numerador / $this->denominador * 100000;
    }
    function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100000;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
/**
 * CASOS NVOS. DE H.A.S. 100,000 POBL. S.S.Z.
 */
class casosNuevosHA extends Md_46ind
{
    
    function __construct()
    {
        parent::__construct();
        $this->nombre = "CASOS NVOS. DE H.A.S. 100,000 POBL S.S.Z.";
    }
    function reporte()
    {
        // 1. Obtener Casos Nuevos (ind 37) Numerador
        $this->db->where('id_ind', 37);
        $this->db->select_sum('logro');
        $anioMes = $this->anio * 100 + (int) $this->mes;
        $this->db->where('anioMes <=', $anioMes);
        $this->db->where('jurisdiccion', $this->juris);
        $consulta = $this->db->get('46ind_reporte');
        foreach ($consulta->result() as $res)
            $this->numerador = $res->logro;

        // 2. Obtener la poblacion Denominador
        $this->db->where('jurisdiccion', $this->juris);
        $this->db->where('anio', $this->anio);
        $consulta = $this->db->get('46ind_poblacion_ssz');

        foreach ($consulta->result() as $res)
            $this->denominador = $res->total;

        // 3. Casos Nuevos / Poblacion * 100000
        // 4. Guardar en $this->total
        $this->total = $this->numerador / $this->denominador * 100000;
    }
    function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100000;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
/**
 * CASOS NVOS. DE D.M. X 100,000 POBL. S.S.Z.
 */
class casosNuevosDM extends Md_46ind
{
    
    function __construct()
    {
        parent::__construct();
        $this->nombre = "CASOS NVOS. DE D.M. X 100,000 POBL S.S.Z.";
    }
    function reporte()
    {
        // 1. Obtener Casos Nuevos (ind 39) Numerador
        $this->db->where('id_ind', 39);
        $this->db->select_sum('logro');
        $anioMes = $this->anio * 100 + (int) $this->mes;
        $this->db->where('anioMes <=', $anioMes);
        $this->db->where('jurisdiccion', $this->juris);
        $consulta = $this->db->get('46ind_reporte');
        foreach ($consulta->result() as $res)
            $this->numerador = $res->logro;

        // 2. Obtener la poblacion Denominador
        $this->db->where('jurisdiccion', $this->juris);
        $this->db->where('anio', $this->anio);
        $consulta = $this->db->get('46ind_poblacion_ssz');

        foreach ($consulta->result() as $res)
            $this->denominador = $res->total;

        // 3. Casos Nuevos / Poblacion * 100000
        // 4. Guardar en $this->total
        $this->total = $this->numerador / $this->denominador * 100000;
    }
    function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100000;
        else
            $this->logroEstatal['estatal'] = 0;
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
        
        //PARA Numero de Medicos
        $this->db->where('anio',$this->anio);
        $this->db->where('juris',$this->juris);
        $consulta = $this->db->get('46ind_nmed');
        foreach($consulta->result() as $ren)
            $this->denominador = $ren->dato;

        //  la formula es NumMed * 21 dias al mes
        $dias = (int) $this->mes * 21;
        $this->denominador = $this->denominador * $dias;

        if( $this->denominador != 0)
            $this->total = $this->numerador / $this->denominador;
        else
            $this->total = 0;
    }
    public function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de las Juris
        $idJur = array('01','02','03','04','05','06','07');

        //  Bucle para capturar cada uno de los logros
        foreach ($idJur as $id) {
            $this->juris = $id;
            $this->reporte();
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
class Porc_ocupacion_hosp extends Md_46ind
{
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "PORCENTAJE DE OCUPACION HOSPITALARIA";
        $this->noJur = 1;
        
        //PARA TOTAL DE CONSULTAS
        $this->db->where('id',5);
        $consulta = $this->db->get('46ind_ind');
        foreach($consulta->result() as $ren)
        {
            $variable = explode(',',$ren->calculo);
            foreach($variable as $var)
                $this->agregar('numerador',$var);
        }
    }
    public function reporte($juris)
    {
        // $this->output->enable_profiler();        
        // PARA CALCULAR TOTAL DE DIAS PACIENTE
        // $this->calcular_num('num'); <-- obsoleto no viene de SIS viene de SAEH
        $this->db->select_sum('dato');
        $this->db->group_by('anio');
        $this->db->where_in('id_ind',array(1,2,3,4,5));
        $this->db->where('id_um', $juris);
        $consulta = $this->db->get('saeh_reporte');
        foreach ($consulta->result() as $ren) {
            $this->numerador = $ren->dato;
        }
        
        //PARA Numero de Camas
        $this->db->where('id',$juris);
        $consulta = $this->db->get('um');
        foreach($consulta->result() as $ren)
            $this->denominador = $ren->camas_total;

        //  la formula es diasPac * 30 dias al mes
        if ($this->mes == "12") 
            $dias = 365;
        else
            $dias = (int) $this->mes * 30;
        $this->denominador = $this->denominador * $dias;

        if( $this->denominador != 0)
            $this->total = $this->numerador / $this->denominador * 100;
        else
            $this->total = 0;
    }
    public function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de los Hospitales Generales y la Mujer
        //  HG FRESNILLO, HG LORETO, HG JEREZ, MUJER, HG ZACATECAS
        $idHosp = array(13,231,57,203,259);

        //  Bucle para capturar cada uno de los logros
        foreach ($idHosp as $id) {
            $this->reporte($id);
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador * 100;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
class prom_dias_est extends Md_46ind
{
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "PROMEDIO DE DIAS ESTANCIA";
        $this->noJur = 1;
        
        //PARA TOTAL DE CONSULTAS
        $this->db->where('id',5);
        $consulta = $this->db->get('46ind_ind');
        foreach($consulta->result() as $ren)
        {
            $variable = explode(',',$ren->calculo);
            foreach($variable as $var)
                $this->agregar('numerador',$var);
        }
    }
    public function reporte($juris)
    {
        // $this->output->enable_profiler();        
        // PARA CALCULAR TOTAL DE "DIAS PACIENTE" SON LO MISMO QUE "DIAS ESTANCIA"
        // $this->calcular_num('num'); <-- obsoleto no viene de SIS viene de SAEH
        $this->db->select_sum('dato');
        $this->db->group_by('anio');
        $this->db->where_in('id_ind',array(1,2,3,4,5,44));
        $this->db->where('id_um', $juris);
        $consulta = $this->db->get('saeh_reporte');
        foreach ($consulta->result() as $ren) {
            $this->numerador = $ren->dato;
        }
        
        //PARA EGRESOS
        $this->db->select_sum('dato');
        $this->db->group_by('anio');
        $this->db->where_in('id_ind',array(6,7,8,9,10,43));
        $this->db->where('id_um', $juris);
        $consulta = $this->db->get('saeh_reporte');
        foreach($consulta->result() as $ren)
            $this->denominador = $ren->dato;

        //  la formula es dias_pac / egresos
        if( $this->denominador != 0)
            $this->total = $this->numerador / $this->denominador;
        else
            $this->total = 0;
    }
    public function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de los Hospitales Generales y la Mujer
        //  HG FRESNILLO, HG LORETO, HG JEREZ, MUJER, HG ZACATECAS
        $idHosp = array(13,231,57,203,259);

        //  Bucle para capturar cada uno de los logros
        foreach ($idHosp as $id) {
            $this->reporte($id);
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
class inter_quir_x_quir extends Md_46ind
{
    public function __construct()
    {
        parent::__construct();
        $this->nombre = "PROMEDIO DIARIO DE INTERVENCIONES QUIRURGICAS POR QUIROFANO";
        $this->noJur = 1;
        
        //PARA TOTAL DE CONSULTAS
        $this->db->where('id',5);
        $consulta = $this->db->get('46ind_ind');
        foreach($consulta->result() as $ren)
        {
            $variable = explode(',',$ren->calculo);
            foreach($variable as $var)
                $this->agregar('numerador',$var);
        }
    }
    public function reporte($juris)
    {
        // $this->output->enable_profiler();        
        // PARA CALCULAR TOTAL DE "INTERVENCIONES QUIRURGICAS"
        // $this->calcular_num('num'); <-- obsoleto no viene de SIS viene de SAEH
        $this->db->select_sum('dato');
        $this->db->group_by('anio');
        $this->db->where('id_ind',41);
        $this->db->where('id_um', $juris);
        $consulta = $this->db->get('saeh_reporte');
        foreach ($consulta->result() as $ren) {
            $this->numerador = $ren->dato;
        }
        
        //PARA N_SALAS_X_DIAS_TRANSCURRIDOS
        switch ($juris) {
            case 259:
                $nSalas = 6;
                break;
            case 13:
                $nSalas = 4;
                break;
            case 231:
                $nSalas = 2;
                break;
            case 57:
                $nSalas = 2;
                break;
            case 203:
                $nSalas = 2;
                break;
        }
        //  la formula es diasPac * 30 dias al mes
        if ($this->mes == "12") 
            $this->denominador = 365;
        else
            $this->denominador = (int) $this->mes * 30;

        $this->denominador = $this->denominador * $nSalas;

        //  la formula es dias_pac / egresos
        if( $this->denominador != 0)
            $this->total = $this->numerador / $this->denominador;
        else
            $this->total = 0;
    }
    public function reporteEstatal()
    {
        $this->mesNumTxt();
        $numerador=0;
        $denominador=0;

        //  IDs de los Hospitales Generales y la Mujer
        //  HG FRESNILLO, HG LORETO, HG JEREZ, MUJER, HG ZACATECAS
        $idHosp = array(13,231,57,203,259);

        //  Bucle para capturar cada uno de los logros
        foreach ($idHosp as $id) {
            $this->reporte($id);
            $this->logroEstatal[$id] = $this->total;

            // guarda sumatoria de numerador y denominador
            $numerador += $this->numerador;
            $denominador += $this->denominador;
        }

        //  calcula logro estatal
        if ($denominador != 0)
            $this->logroEstatal['estatal'] = $numerador / $denominador;
        else
            $this->logroEstatal['estatal'] = 0;
    }
}
?>