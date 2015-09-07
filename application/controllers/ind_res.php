<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ind_res extends CI_Controller {

	public function index()
	{
		
	}
	public function nac_xCesarea($mes,$anio)
	{
		$this->load->model('Md_46ind');
		$nac_x_cesarea = new nac_x_cesarea();
		$nac_x_cesarea->anio = $anio;
		$nac_x_cesarea->mes = $mes;
		$nac_x_cesarea->reporteEstatal();
		$datos['reporte'] = $nac_x_cesarea;
		$this->load->view('vw_IndResUM', $datos);
	}
	public function consPren($mes,$anio)
	{
		$this->load->model('md_46ind');
        $prom_cons_emb = new prom_cons_pre_x_emb();
        $prom_cons_emb->mes = $mes;
        $prom_cons_emb->anio = $anio;
        $prom_cons_emb->reporteEstatal();
		$datos['reporte'] = $prom_cons_emb;
		$this->load->view('vw_IndResJuris', $datos);
	}
	public function usuAct($mes,$anio)
	{
		$this->load->model('md_46ind');
        $usuarias_act = new usuarias_act_pf();
        $usuarias_act->mes = $mes;
        $usuarias_act->anio = $anio;
        $usuarias_act->reporteEstatal();
		$datos['reporte'] = $usuarias_act;
		$this->load->view('vw_IndResJuris', $datos);
	}
	public function promConsMed($mes,$anio)
	{
		$this->load->model('md_46ind');
        $promConsMed = new Prom_diario_consulta_x_medico();
        $promConsMed->mes = $mes;
        $promConsMed->anio = $anio;
        $promConsMed->reporteEstatal();
		$datos['reporte'] = $promConsMed;
		$this->load->view('vw_IndResJuris', $datos);
	}
	public function porcOcuHosp($mes,$anio)
	{
		$this->load->model('Md_46ind');
		$porcOcuHosp = new Porc_ocupacion_hosp();
		$porcOcuHosp->anio = $anio;
		$porcOcuHosp->mes = $mes;
		$porcOcuHosp->reporteEstatal();
		$datos['reporte'] = $porcOcuHosp;
		$datos['completo'] = 1;
		$this->load->view('vw_IndResUM', $datos);
	}
	public function promDiasEst($mes,$anio)
	{
		$this->load->model('Md_46ind');
		$promDiasEst = new prom_dias_est();
		$promDiasEst->anio = $anio;
		$promDiasEst->mes = $mes;
		$promDiasEst->reporteEstatal();
		$datos['reporte'] = $promDiasEst;
		$datos['completo'] = 1;
		$this->load->view('vw_IndResUM', $datos);
	}
	public function interQuir($mes,$anio)
	{
		$this->load->model('Md_46ind');
		$interQuir = new inter_quir_x_quir();
		$interQuir->anio = $anio;
		$interQuir->mes = $mes;
		$interQuir->reporteEstatal();
		$datos['reporte'] = $interQuir;
		$datos['completo'] = 1;
		$this->load->view('vw_IndResUM', $datos);
	}
	public function morbTbPulmonar($mes,$anio)
	{
		$this->load->model('Md_46ind');
		$morbTbPulmonar = new morbTbPulmonar();
		$morbTbPulmonar->anio = $anio;
		$morbTbPulmonar->mes = $mes;
		$morbTbPulmonar->reporteEstatal();
		$datos['reporte'] = $morbTbPulmonar;
		$datos['completo'] = 1;
		$this->load->view('vw_IndResJuris', $datos);
	}
	public function nuevosTbTaesTerm($mes,$anio)
	{
		$this->load->model('Md_46ind');
		$nuevosTbTaesTerm = new nuevosTbTaesTerm();
		$nuevosTbTaesTerm->anio = $anio;
		$nuevosTbTaesTerm->mes = $mes;
		$nuevosTbTaesTerm->reporteEstatal();
		$datos['reporte'] = $nuevosTbTaesTerm;
		$datos['completo'] = 1;
		$this->load->view('vw_IndResJuris', $datos);
	}
}

/* End of file ind_res.php */
/* Location: ./application/controllers/ind_res.php */