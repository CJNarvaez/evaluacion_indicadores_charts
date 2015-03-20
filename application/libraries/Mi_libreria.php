<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Mi_libreria {
    public function __construct()
    {       
    }
    public function mes_a_txt($mes,$abreviado = 0)
    {
        if($abreviado == 0){
            switch ($mes)
            {
                case '01': return 'ENERO';
                case '02': return 'FEBRERO';
                case '03': return 'MARZO';
                case '04': return 'ABRIL';
                case '05': return 'MAYO';
                case '06': return 'JUNIO';
                case '07': return 'JULIO';
                case '08': return 'AGOSTO';
                case '09': return 'SEPTIEMBRE';
                case '10': return 'OCTUBRE';
                case '11': return 'NOVIEMBRE';
                case '12': return 'DICIEMBRE';
                default : return 'NO APLICA'; 
            }
        }
        
        if($abreviado == 1){
            switch ($mes)
            {
                case '01': return 'ENE';
                case '02': return 'FEB';
                case '03': return 'MAR';
                case '04': return 'ABR';
                case '05': return 'MAY';
                case '06': return 'JUN';
                case '07': return 'JUL';
                case '08': return 'AGO';
                case '09': return 'SEP';
                case '10': return 'OCT';
                case '11': return 'NOV';
                case '12': return 'DIC';
                default : return 'NO APLICA';
            }
        }
    }
    public function mes_a_num($mes,$abreviado = 0)
    {
        $mes = strtoupper($mes);
        if($abreviado == 0){
            switch ($mes)
            {
                case 'ENERO':       return '01';
                case 'FEBRERO':     return '02';
                case 'MARZO':       return '03';
                case 'ABRIL':       return '04';
                case 'MAYO':        return '05';
                case 'JUNIO':       return '06';
                case 'JULIO':       return '07';
                case 'AGOSTO':      return '08';
                case 'SEPTIEMBRE':  return '09';
                case 'OCTUBRE':     return '10';
                case 'NOVIEMBRE':   return '11';
                case 'DICIEMBRE':   return '12';
                default : return 'NO APLICA';
            }
        }
        
        if($abreviado == 1){
            switch ($mes)
            {
                case 'ENE': return '01';
                case 'FEB': return '02';
                case 'MAR': return '03';
                case 'ABR': return '04';
                case 'MAY': return '05';
                case 'JUN': return '06';
                case 'JUL': return '07';
                case 'AGO': return '08';
                case 'SEP': return '09';
                case 'OCT': return '10';
                case 'NOV': return '11';
                case 'DIC': return '12';
                default : return 'NO APLICA';
            }
        }
    }
}

/* End of file Someclass.php */
?>