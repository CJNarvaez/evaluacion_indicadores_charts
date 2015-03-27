<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2015
 */

/**
 * TODAS LAS OPERACIONES CON FECHAS
 */
class Md_fechas extends CI_Model
{
    function __construct(){
        parent::__construct();
    }
    
    /**
     * CONVIERTE UN MES NUMERICO A TEXTO
     * @param INT mes en entero
     * @return STR mes en string
     */
    function convertir_mes_txt($mes)
    {
        switch($mes)
        {
            case '1': $mes_txt = 'ENERO';
                        break;
            case '2': $mes_txt = 'FEBRERO';
                        break;
            case '3': $mes_txt = 'MARZO';
                        break;
            case '4': $mes_txt = 'ABRIL';
                        break;
            case '5': $mes_txt = 'MAYO';
                        break;
            case '6': $mes_txt = 'JUNIO';
                        break;
            case '7': $mes_txt = 'JULIO';
                        break;
            case '8': $mes_txt = 'AGOSTO';
                        break;
            case '9': $mes_txt = 'SEPTIEMBRE';
                        break;
            case '10': $mes_txt = 'OCTUBRE';
                        break;
            case '11': $mes_txt = 'NOVIEMBRE';
                        break;
            case '12': $mes_txt = 'DICIEMBRE';
                        break;
        }
        return $mes_txt;
    }
}
?>