<?php 
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends FPDF {
        public $fechaCorte = 'prueba';
        public function __construct($fechaCorte='') {
            parent::__construct();
            $this->fechaCorte = $fechaCorte;
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image('img/encabezado.jpg',0,0);
           // $this->SetFont('Arial','B',13);
           // $this->Cell(30);
           // $this->Cell(120,10,'ESCUELA X',0,0,'C');
           // $this->Ln('5');
           // $this->SetFont('Arial','B',8);
           // $this->Cell(30);
           // $this->Cell(120,10,'INFORMACION DE CONTACTO',0,0,'C');
            $this->Ln(20);
       }
       // El pie del pdf
       public function Footer(){
           //$CI =& get_instance();
           //$CI->load->model('md_informacion');        
           //$corte = $CI->md_informacion->fecha_corte(2014);
           
           $this->SetY(-15);
           $this->SetFont('Arial','I',8);
           $this->Cell(0,10,$this->fechaCorte.' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
      }
    }
?>;