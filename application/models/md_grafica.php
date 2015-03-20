<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_grafica extends CI_Controller
{
    public $titulo, $subtitulo,$categorias,$logros,$metas;
    
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Establece el titulo
     *     @recibe STR cadena del titulo
     */
    public function titulo($titulo)
    {
        $this->titulo = $titulo;
    }
    
    /**
     * Establece el subtitulo
     *     @recibe STR cadena del subtitulo
     */
    public function subtitulo($subtitulo)
    {
        $this->subtitulo = $subtitulo;
    }
    
    /**
      * Agrega un dato a la grafica
      *     @categoria STR
      *     @logro FLOAT
      *     @meta FLOAT
      */ 
    public function agregar_dato($categoria,$logro,$meta)
    {
        //echo $categoria;
        $this->categorias[] = $categoria;
        $this->logros[] = (float) number_format($logro, 2, '.', ''); 
        $this->metas[] = (float) number_format($meta, 2, '.', ''); 
    }
}

?>