<?php

/**
 * @author Cristhian Narvaez
 * @copyright 2014
 */

class Md_Pagina extends CI_Model
{
    public $titulo_pagina, $encabezado_fondo,$menu,$menu2,$menu_fin,$menu_fin2,$menu_opciones,$jquery,$bootstrap,$pie,$html_head;
    public $tablaTemplate = array (
                    'table_open'          => '<table class="table table-bordered" align="center">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr class="odd">',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );
    
    /**
     * CONSTRUCTOR DE LA CLASE
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('ion_auth');
        
        $this->jquery = '<script type="text/javascript" src="'.base_url('js/jquery-2.0.3.js').'"></script>';
        $this->bootstrap = '<!-- Bootstrap core CSS -->
                            <link href="'.base_url('/css/bootstrap.css').'" rel="stylesheet" />
                            <script src="'.base_url('js/bootstrap.min.js').'"></script>';
        $this->titulo_pagina = 'SISTEMA DE EVALUACION';
        $this->menu_agregar_link('principal/principal','Menú Principal','list',1);
       // $this->menu_agregar_link('principal/captura','Captura','pencil',1);
        $this->menu_agregar_link('principal/reporte','Reportes','list-alt',1);
        $this->menu_agregar_link('principal/reporte_ind_hosp_form','Ind. Hospitalarios','stats',1);
        if($this->ion_auth->in_group('admin'))
            $this->menu_agregar_link('principal/reporte_administrador','Administrar Evaluacion','stats',1);
    }
    /**
    * CREA EL HEAD DE LA PAGINA HTML
    *     @param bool Bootstrap
    *     @param bool jQuery
    *     @param bool FontAwesome
    */ 
    public function html_head ($bootstrap = 0, $jQuery = 0, $fontAwesome = 0)
    {
        //Para que las páginas se muestren correctamente y el zoom funcione bien en los dispositivos móviles, es importante que añadas la siguiente etiqueta dentro de la cabecera <head> de las páginas:
        $this->html_head = '<meta name="viewport" content="width=device-width, initial-scale=1" />';
        
        //Agrega Jquery
        if($jQuery)
            $this->html_head = $this->html_head.'<script type="text/javascript" src="'.base_url('js/jquery-2.1.1.min.js').'"></script>';
        
        //<!-- Bootstrap core CSS -->
        if($bootstrap)
            $this->html_head = $this->html_head.'<link href="'.base_url('/css/bootstrap.css').'" rel="stylesheet" />
                                                <script src="'.base_url('js/bootstrap.min.js').'"></script>';
        
        //Agrega FontAwesome
        if($fontAwesome)
            $this->html_head = $this->html_head.'<link href="'.base_url('css/font-awesome.css').'" rel="stylesheet" type="text/css" />';
        
        echo $this->html_head;
    }
    /**
     * ESTABLECE EL ENCABEZADO DE LA PAGINA
     * @param str Titulo
     * @param str Subtitulo
     */
    public function encabezado($titulo, $subtitulo)
    {
        $encabezado = '<!-- Jumbotron -->
                      <div class="jumbotron">
                        <h1>'.$titulo.'</h1>
                        <p class="lead">'.$subtitulo.'</p>
                      </div>';
        echo $encabezado;
    }
    /**
     * Agrega un vinculo a la barra de menu
     * @param str Vinculo
     * @param str Nombre del vinculo
     * @param str Icono a mostrar
     * @param str Base_url = 0 || site_url = 1
     */
    public function menu_agregar_link($url,$nombre,$icono,$base_site = 0)
    {
        if($base_site == 0)
            $this->menu_opciones[] = array('url' => base_url($url),
                                            'nombre' => $nombre,
                                            'icono' => $icono);
        else
            $this->menu_opciones[] = array('url' => site_url($url),
                                            'nombre' => $nombre,
                                            'icono' => $icono);
    }
    /**
     * Crea la barra de Menu
     * @param str Titulo str titulo del menu     
     * @param str Activo str vinculo seleccionado para resaltar de la barra de menu
     * @param str Tipo str opciones "default" || "justificado" || "estatico"
     */
    public function menu_genera($titulo,$activo, $tipo = 'default')
    {
        $estatico = "";
        if($tipo == 'estatico')
            $estatico = "navbar-fixed-top";
        $this->menu = '<!-- Static navbar -->
                      <div class="navbar-inverse '.$estatico.'" role="navigation">
                        <div class="container-fluid">
                          <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                              <span class="sr-only">Toggle navigation</span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">'.$titulo.'</a>
                          </div>
                          <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">';
        $this->menu_fin = '</ul>
                                    <ul class="nav navbar-nav navbar-right">
                                      <li><a href="'.site_url('auth/logout').'"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
                                    </ul>
                                  </div><!--/.nav-collapse -->
                                </div><!--/.container-fluid -->
                              </div>';
        $this->menu2 = '<div class="masthead">
                        <h3 class="text-muted">'.$titulo.'</h3>
                        <ul class="nav nav-justified">';
        $this->menu_fin2 = '</ul></div>';
                                                            
        foreach($this->menu_opciones as $opcion)
        {
            $seleccionar = "";
            if($opcion['nombre'] == $activo)
                $seleccionar = 'class="active"';
            if($tipo == 'default' OR $tipo == 'estatico')
                $this->menu = $this->menu.'<li '.$seleccionar.'><a href="'.$opcion['url'].'"><span class="glyphicon glyphicon-'.$opcion['icono'].'"></span> '.$opcion['nombre'].'</a></li>';
            if($tipo == 'justificado')
                $this->menu2 = $this->menu2.'<li '.$seleccionar.'><a href="'.$opcion['url'].'"><span class="glyphicon glyphicon-'.$opcion['icono'].'"></span> '.$opcion['nombre'].'</a></li>';
        }
        if($tipo == 'default' OR $tipo == 'estatico')
        {
            $this->menu = $this->menu.$this->menu_fin;
            echo $this->menu;
        }
        if($tipo == 'justificado'){
            $this->menu2 = $this->menu2.$this->menu_fin2;
            echo $this->menu2;
        }
        
    }
    /**
     * GENERA EL PIE DE PAGINA
     * @param str Titulo mensaje a mostrar en el pie de pagina
     */
    public function pie($titulo)
    {
        $this->pie = '<!-- Site footer -->
                      <div class="footer">
                        <p>&copy; '.$titulo.'</p>
                      </div>';
        echo $this->pie;
    }
}

?>