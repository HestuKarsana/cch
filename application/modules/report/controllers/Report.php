<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Report
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Report extends CI_Controller {
	
	private $pos_office;
	private $active_user;
	private $active_role;
	private $active_regional; 
	private $active_utype;
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mreport');

		$this->pos_office 		= $this->session->userdata('pos_office');
		$this->active_user 		= $this->session->userdata('ses_username');
		$this->active_utype		= $this->session->userdata('ses_utype');

		if($this->session->userdata('ses_utype') == 'Regional'){
			$regional	= $this->mglobals->get_kantor_pos_in_regional($this->pos_office);
			$query 		= $this->mglobals->get_kantor_pos_regional($regional);
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$view_kantor_pos[]	= $row->code;
				}
			}
			$this->active_regional = $view_kantor_pos;
		}else{
			$this->active_regional = array($this->pos_office);
		}
	}
	
	public function index()
	{
		show_404();
	}

	public function layanan()
	{
		$data['content']	= $this->load->view('layanan','',true);
		$this->load->view('page',$data);
	}

	public function load_rekap_layanan()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$start 			= $this->input->post('start') ? $this->input->post('start') : date('Y-m-01');
		$end 			= $this->input->post('end') ? $this->input->post('end') : date('Y-m-t');
		$kprk 			= $this->input->post('kprk') ? $this->input->post('kprk') : $this->active_regional;

		$im_kprk 		= "%".implode('%\" or a.tujuan_pengaduan like \"%', $kprk)."%";

		$myrows		= array();
		$query 		= $this->mreport->get_rekap_layanan_info($start, $end, $im_kprk);
		$rows['info_head'] 	= $query->field_data();
		
		for($i = 0; $i<count($rows['info_head']); $i++)
		{
			$rows['dinfo_head'][]	= array('field'=>$rows['info_head'][$i]->name, 'title'=>$rows['info_head'][$i]->name, 'width'=>20);
		}

		$rows['info']		= array();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($rows['info'], $row);
			}

			foreach($query->result_array() as $row)
			{
				$mdata 	= array();
				for($i = 0; $i<count($rows['info_head']); $i++)
				{
					//$mdata[]	= array($rows['info_head'][$i]->name => $row[$rows['info_head'][$i]->name]);
					$mdata[$rows['info_head'][$i]->name] = $row[$rows['info_head'][$i]->name];
				}
				$rows['dinfo_body'][]	= $mdata;
			}

		}
		$query->next_result(); 
		$query->free_result();
		

		$query		= $this->mreport->get_rekap_layanan_pengaduan($start, $end, $im_kprk);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			
			$selesai		= ($row->total > 0) ? ($row->total_close / $row->total) * 100 : 0;
			$terbuka		= ($row->total > 0) ? ($row->total_open / $row->total) * 100 : 0;

			$grade_a		= ($row->total_close > 0) ? ($row->total_grade_a / $row->total_close) * 100 : 0;
			$grade_b		= ($row->total_close > 0) ? ($row->total_grade_b / $row->total_close) * 100 : 0;

			$new_row 		= array('total'=>$row->total, 
									'selesai'=>$row->total_close, 
									'selesai_percent'=>$selesai,
									'terbuka'=>$row->total_open, 
									'terbuka_percent'=>$terbuka,
									'grade_a'=>$row->total_grade_a,
									'grade_a_percent'=>$grade_a,
									'grade_b'=>$row->total_grade_b,
									'grade_b_percent'=>$grade_b
								);
			$rows['aduan']	= $new_row;
			


		}
		$query->next_result(); 
		$query->free_result();


		
		//$rows['masalah_head']	= array();
		$query		= $this->mreport->get_rekap_layanan_aduan_masalah_produk($start, $end, $im_kprk);
		$rows['masalah_head'] 	= $query->field_data();
		for($i = 0; $i<count($rows['masalah_head']); $i++)
		{
			$rows['dmasalah_head'][]	= array('field'=>$rows['masalah_head'][$i]->name, 'title'=>$rows['masalah_head'][$i]->name, 'width'=>20);
		}
		$rows['masalah']		= array();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($rows['masalah'], $row);
			}
		}
		$query->next_result(); 
		$query->free_result();


		echo json_encode($rows); 
	}

	public function incoming()
	{
		if($this->active_utype == 'KPRK'){
			//$data['content']	= $this->load->view('incoming_kprk','',true);
			//$this->incoming_kprk($this->pos_office);
			redirect('report/incoming_kprk/'.$this->pos_office, 301);
		}else if($this->active_utype == 'Regional'){
			$regional	= $this->mglobals->get_kantor_pos_in_regional_id($this->pos_office);
			redirect('report/incoming_regional/'.$regional, 301);
		}else if($this->active_utype == 'Pusat'){
			$data['content']	= $this->load->view('incoming','',true);
			$this->load->view('page',$data);	
		}
		
		
	}

	public function get_incoming_ticket()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true) ? $this->input->post('order') : ' desc ';
		$sort			= $this->input->post('sort', true) ? $this->input->post('sort') : ' a.date ';
		
		$date_start		= $this->input->post('start', true) ? $this->input->post('start', true): date('Y-m-d', strtotime('-1 months'));
		$date_end		= $this->input->post('end', true) ? $this->input->post('end', true) : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		$status			= $this->input->post('status', true) ? $this->input->post('status', true) : '';
		
		$export			= $this->input->post('export', true) ? $this->input->post('export', true) : '';
		if($export != ''){
			$row_show	= '';
			$row_start	= '';
		}
		
		$date			= array($date_start, $date_end);
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		//$rows['footer'] = array();

		$total_ticket 	= 0;
		$tot_selesai 	= 0;
		$tot_terbuka 	= 0;
		//$total 			= $this->mreport->get_total_incoming_ticket($search, $this->pos_office, $date);
		$query 			= $this->mreport->get_incoming_ticket($search, $this->pos_office, $date, $row_show, $row_start, $sort, $order);
		#echo $this->db->last_query();
		if($export == 'xls')
		{
			$result 	= $this->createExcelIncoming($query);
			echo json_encode($result);
		}else{
			if($query->num_rows() > 0)
			{
				$rows['total'] 	= $query->num_rows();
				$data_row		= array();
				foreach($query->result() as $row)
				{
					$total_ticket	+= $row->total_ticket;
					$tot_selesai 	+= $row->total_selesai;
					$tot_terbuka 	+= $row->total_terbuka;
				}

				foreach($query->result() as $row)
				{
					//$row->percent_selesai	= ($row->total_ticket > 0 ) ? number_format(($row->total_selesai * 100) / $row->total_ticket,1) : 0.0;
					$row->percent_selesai	= ($row->total_ticket > 0 ) ? number_format(($row->total_selesai * 100) / $tot_selesai,1) : 0.0;
					$row->percent_terbuka	= ($row->total_ticket > 0 ) ? number_format(($row->total_terbuka * 100) / $tot_terbuka,1) : 0.0;
					$row->isFooter	= false;
					

					$data_row[]	= array('id'=>$row->id, 'city'=>$row->city, 'regional'=>$row->regional, 'total_ticket'=>$row->total_ticket, 'total_selesai'=>$row->total_selesai,'percent_selesai'=>$row->percent_selesai, 'total_terbuka'=>$row->total_terbuka, 'percent_terbuka'=>$row->percent_terbuka);
					//array_push($rows['rows'],$row);
				}
				$rows['footer'] = array(array('regional'=>'TOTAL','city'=>'','total_ticket'=>$total_ticket, 'total_selesai'=>$tot_selesai, 'total_terbuka'=>$tot_terbuka, 'isFooter'=>true))	;
				$rows['rows']	= $data_row;
			}
			echo json_encode($rows);
		}
	}

	// REGIONAL REPORT
	public function incoming_regional($regional)
	{
		//$regional 			= $this->uri->segment(3);
		$status 			= $this->uri->segment(4);

		$query 				= $this->mreport->get_regional($regional);
		if($query->num_rows() > 0)
		{
			if($status == 'terbuka'){
				$content['status']	= "[ Terbuka ]";
			}else if($status == "selesai"){
				$content['status']	= "[ Selesai ]";
			}else{
				$content['status']	= "";
			}
			$content['reg']		= $query->row();
			$data['content']	= $this->load->view('incoming_regional',$content,true);
			$this->load->view('page',$data);
		}else{
			echo 'ERROR 404';
		}

		
	}


	public function get_incoming_regional_ticket()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$regid 			= $this->input->post('regid');
		$filter 		= $this->input->post('status');
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true) ? $this->input->post('order') : ' desc ';
		$sort			= $this->input->post('sort', true) ? $this->input->post('sort') : ' a.date ';
		
		$date_start		= $this->input->post('start', true) ? $this->input->post('start', true)  : date('Y-m-d', strtotime('-1 months'));
		$date_end		= $this->input->post('end', true) ? $this->input->post('end', true)  : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		//$status			= $this->input->post('status', true) ? $this->input->post('status', true) : '';
		
		$export			= $this->input->post('export', true) ? $this->input->post('export', true) : '';
		if($export != ''){
			$row_show	= '';
			$row_start	= '';
		}

		$reg 				= $this->mreport->get_regional($regid);
		$reg_row	 		= $reg->row();


		$total_ticket 	= 0;
		$tot_selesai 	= 0;
		$tot_terbuka 	= 0;
		
		$date			= array($date_start, $date_end);
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$rows['footer'] 	= array();
		$query 			= $this->mreport->get_incoming_regional_ticket($reg_row->regional, $date);
		if($export == 'xls')
		{
			$result 	= $this->createExcelIncomingRegional($query);
			echo json_encode($result);
		}else{
			if($query->num_rows() > 0)
			{
				$rows['total'] 	= $query->num_rows();
				foreach($query->result() as $row)
				{
					$row->percent_selesai	= ($row->total_ticket > 0 ) ? number_format(($row->total_selesai * 100) / $row->total_ticket,1) : 0.0;
					$row->percent_terbuka	= ($row->total_ticket > 0 ) ? number_format(($row->total_terbuka * 100) / $row->total_ticket,1) : 0.0;
					$row->isFooter			= false;

					$total_ticket 	+= $row->total_ticket;
					$tot_selesai 	+= $row->total_selesai;
					$tot_terbuka 	+= $row->total_terbuka;
					//$tot_sele 	+= $row->total_selesai;
					//$tot_terbuka 	+= $row->total_terbuka;

					array_push($rows['rows'],$row);
				}
				$rows['footer'] = array(array('fullname'=>'TOTAL','city'=>'','total_ticket'=>$total_ticket, 'total_selesai'=>$tot_selesai, 'total_terbuka'=>$tot_terbuka, 'isFooter'=>true))	;
			}
			echo json_encode($rows);
		}
	}

	public function incoming_kprk()
	{
		$kprk 				= $this->uri->segment(3);

		$query 				= $this->mreport->get_kprk($kprk);
		if($query->num_rows() > 0)
		{
			$content['kprk']	= $query->row();
			$data['content']	= $this->load->view('incoming_kprk',$content,true);
			$this->load->view('page',$data);
		}else{
			echo 'ERROR 404';
		}

		
	}

	public function get_incoming_kprk_ticket()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$kprk 			= $this->input->post('kprk');
		$filter 		= $this->input->post('filter');
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true) ? $this->input->post('order') : ' desc ';
		$sort			= $this->input->post('sort', true) ? $this->input->post('sort') : ' a.date ';
		
		$date_start		= $this->input->post('start', true) ? $this->input->post('start', true) : date('Y-m-d', strtotime('-1 months'));
		$date_end		= $this->input->post('end', true) ? $this->input->post('end', true) : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		//$status			= $this->input->post('status', true) ? $this->input->post('status', true) : '';
		
		$export			= $this->input->post('export', true) ? $this->input->post('export', true) : '';
		if($export != ''){
			$row_show	= '';
			$row_start	= '';
		}

		//$reg 				= $this->mreport->get_regional($regid);
		//$reg_row	 		= $reg->row();


		$total_ticket 		= 0;
		$tot_selesai 		= 0;
		$tot_terbuka 		= 0;
		
		$date				= array($date_start, $date_end);
		$rows['total'] 		= 0;
		$rows['rows'] 		= array();
		$rows['footer'] 	= array();
		$tquery 			= $this->mreport->get_incoming_kprk_ticket($kprk, $date, $filter, 0, 0);
		//$total_data 		= $tquery->num_rows();
		$rows['total'] 	= $tquery->num_rows();
		$tquery->next_result(); 
		$tquery->free_result(); 
		$query 			= $this->mreport->get_incoming_kprk_ticket($kprk, $date, $filter, $row_start, $row_show);
		if($export == 'xls')
		{
			$result = $this->createExcelIncomingKprk($query);
			echo json_encode($result);
		}else{
			if($query->num_rows() > 0)
			{
				
				foreach($query->result() as $row)
				{
					array_push($rows['rows'],$row);
				}
			}
			echo json_encode($rows);
		}
	}

	// OUTGOING
	public function outgoing()
	{
		if($this->active_utype == 'KPRK'){
			redirect('report/outgoing_kprk/'.$this->pos_office, 301);
		}else if($this->active_utype == 'Regional'){
			$regional	= $this->mglobals->get_kantor_pos_in_regional_id($this->pos_office);
			redirect('report/outgoing_regional/'.$regional, 301);
		}else if($this->active_utype == 'Pusat'){
			$data['content']	= $this->load->view('outgoing','',true);
		$this->load->view('page',$data);
		}
		
	}

	public function get_outgoing_ticket()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true) ? $this->input->post('order') : ' desc ';
		$sort			= $this->input->post('sort', true) ? $this->input->post('sort') : ' a.date ';
		
		//$date_start		= $this->input->post('start', true) ? SetDateFormatFromID($this->input->post('start', true),'Y-m-d') : date('Y-m-d', strtotime('-1 months'));
		$date_start		= $this->input->post('start', true) ? $this->input->post('start', true) : date('Y-m-d', strtotime('-1 months'));
		//$date_end		= $this->input->post('end', true) ? SetDateFormatFromID($this->input->post('end', true),'Y-m-d') : date('Y-m-d');
		$date_end		= $this->input->post('end', true) ? $this->input->post('end', true) : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		$status			= $this->input->post('status', true) ? $this->input->post('status', true) : '';
		
		$export			= $this->input->post('export', true) ? $this->input->post('export', true) : '';
		if($export != ''){
			$row_show	= '';
			$row_start	= '';
		}

		$total_ticket 	= 0;
		$tot_selesai 	= 0;
		$tot_terbuka 	= 0;
		
		$date			= array($date_start, $date_end);
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$query 			= $this->mreport->get_outgoing_ticket($this->pos_office, $date);
		//echo $this->db->last_query();
		if($export == 'xls')
		{
			$result 	= $this->createExcelIncoming($query, 'Laporan Tiket Keluar Nasional');
			echo json_encode($result);
		}else{
			if($query->num_rows() > 0)
			{
				$rows['total'] 	= $query->num_rows();
				$data_row		= array();
				foreach($query->result() as $row)
				{
					$total_ticket	+= $row->total_ticket;
					$tot_selesai 	+= $row->total_selesai;
					$tot_terbuka 	+= $row->total_terbuka;
				}

				foreach($query->result() as $row)
				{
					//$row->percent_selesai	= ($row->total_ticket > 0 ) ? number_format(($row->total_selesai * 100) / $row->total_ticket,1) : 0.0;
					$row->percent_selesai	= ($row->total_ticket > 0 ) ? number_format(($row->total_selesai * 100) / $tot_selesai,1) : 0.0;
					$row->percent_terbuka	= ($row->total_ticket > 0 ) ? number_format(($row->total_terbuka * 100) / $tot_terbuka,1) : 0.0;
					$row->isFooter	= false;
					

					$data_row[]	= array('id'=>$row->id, 'city'=>$row->city, 'regional'=>$row->regional, 'total_ticket'=>$row->total_ticket, 'total_selesai'=>$row->total_selesai,'percent_selesai'=>$row->percent_selesai, 'total_terbuka'=>$row->total_terbuka, 'percent_terbuka'=>$row->percent_terbuka);
					//array_push($rows['rows'],$row);
				}
				$rows['footer'] = array(array('regional'=>'TOTAL','city'=>'','total_ticket'=>$total_ticket, 'total_selesai'=>$tot_selesai, 'total_terbuka'=>$tot_terbuka, 'isFooter'=>true))	;
				$rows['rows']	= $data_row;
			}
			echo json_encode($rows);
		}
	}

	public function outgoing_regional()
	{
		$regional 			= $this->uri->segment(3);
		$status 			= $this->uri->segment(4);

		

		$query 				= $this->mreport->get_regional($regional);
		if($query->num_rows() > 0)
		{
			if($status == 'terbuka'){
				$content['status']	= "[ Terbuka ]";
			}else if($status == "selesai"){
				$content['status']	= "[ Selesai ]";
			}else{
				$content['status']	= "";
			}
			$content['reg']		= $query->row();
			$data['content']	= $this->load->view('outgoing_regional',$content,true);
			$this->load->view('page',$data);
		}else{
			echo 'ERROR 404';
		}
	}

	public function get_outgoing_regional_ticket()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$regid 			= $this->input->post('regid');
		$filter 		= $this->input->post('status');
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true) ? $this->input->post('order') : ' desc ';
		$sort			= $this->input->post('sort', true) ? $this->input->post('sort') : ' a.date ';
		
		$date_start		= $this->input->post('start', true) ? $this->input->post('start', true) : date('Y-m-d', strtotime('-1 months'));
		$date_end		= $this->input->post('end', true) ? $this->input->post('end', true) : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		//$status			= $this->input->post('status', true) ? $this->input->post('status', true) : '';
		
		$export			= $this->input->post('export', true) ? $this->input->post('export', true) : '';
		if($export != ''){
			$row_show	= '';
			$row_start	= '';
		}

		$reg 				= $this->mreport->get_regional($regid);
		$reg_row	 		= $reg->row();


		$total_ticket 	= 0;
		$tot_selesai 	= 0;
		$tot_terbuka 	= 0;
		
		$date			= array($date_start, $date_end);
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$rows['footer'] 	= array();
		$query 			= $this->mreport->get_outgoing_regional_ticket($reg_row->regional, $date);
		if($export == 'xls')
		{
			$result 	= $this->createExcelIncomingRegional($query, 'Laporan Tiket Keluar Regional');
			echo json_encode($result);
		}else{
			if($query->num_rows() > 0)
			{
				$rows['total'] 	= $query->num_rows();
				foreach($query->result() as $row)
				{
					$row->percent_selesai	= ($row->total_ticket > 0 ) ? number_format(($row->total_selesai * 100) / $row->total_ticket,1) : 0.0;
					$row->percent_terbuka	= ($row->total_ticket > 0 ) ? number_format(($row->total_terbuka * 100) / $row->total_ticket,1) : 0.0;
					$row->isFooter			= false;

					$total_ticket 	+= $row->total_ticket;
					$tot_selesai 	+= $row->total_selesai;
					$tot_terbuka 	+= $row->total_terbuka;
					//$tot_sele 	+= $row->total_selesai;
					//$tot_terbuka 	+= $row->total_terbuka;

					array_push($rows['rows'],$row);
				}
				$rows['footer'] = array(array('fullname'=>'TOTAL','city'=>'','total_ticket'=>$total_ticket, 'total_selesai'=>$tot_selesai, 'total_terbuka'=>$tot_terbuka, 'isFooter'=>true))	;
			}
			echo json_encode($rows);
		}
	}

	public function outgoing_kprk()
	{
		$kprk 				= $this->uri->segment(3);

		$query 				= $this->mreport->get_kprk($kprk);
		if($query->num_rows() > 0)
		{
			$content['kprk']	= $query->row();
			$data['content']	= $this->load->view('outgoing_kprk',$content,true);
			$this->load->view('page',$data);
		}else{
			echo 'ERROR 404';
		}

		
	}

	public function get_outgoing_kprk_ticket()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$kprk 			= $this->input->post('kprk');
		$filter 		= $this->input->post('filter');
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true) ? $this->input->post('order') : ' desc ';
		$sort			= $this->input->post('sort', true) ? $this->input->post('sort') : ' a.date ';
		
		$date_start		= $this->input->post('start', true) ? $this->input->post('start', true) : date('Y-m-d', strtotime('-1 months'));
		$date_end		= $this->input->post('end', true) ? $this->input->post('end', true) : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		//$status			= $this->input->post('status', true) ? $this->input->post('status', true) : '';
		
		$export			= $this->input->post('export', true) ? $this->input->post('export', true) : '';
		if($export != ''){
			$row_show	= '';
			$row_start	= '';
		}

		//$reg 				= $this->mreport->get_regional($regid);
		//$reg_row	 		= $reg->row();


		$total_ticket 		= 0;
		$tot_selesai 		= 0;
		$tot_terbuka 		= 0;
		
		$date				= array($date_start, $date_end);
		$rows['total'] 		= 0;
		$rows['rows'] 		= array();
		$rows['footer'] 	= array();
		$tquery 			= $this->mreport->get_outgoing_kprk_ticket($kprk, $date, $filter, 0, 0);
		//$total_data 		= $tquery->num_rows();
		$rows['total'] 	= $tquery->num_rows();
		$tquery->next_result(); 
		$tquery->free_result(); 
		$query 			= $this->mreport->get_outgoing_kprk_ticket($kprk, $date, $filter, $row_start, $row_show);
		if($export == 'xls')
		{
			$result 	= $this->createExcelIncomingKprk($query, "Laporan Tiket Keluar KPRK");
			echo json_encode($result);
		}else{
			if($query->num_rows() > 0)
			{
				
				foreach($query->result() as $row)
				{
					array_push($rows['rows'],$row);
				}
				//$rows['footer'] = array(array('fullname'=>'TOTAL','city'=>'','total_ticket'=>$total_ticket, 'total_selesai'=>$tot_selesai, 'total_terbuka'=>$tot_terbuka, 'isFooter'=>true))	;
			}
			echo json_encode($rows);
		}
	}


	// PRODUCT 
	public function product()
	{
		$data['content']	= $this->load->view('product','',true);
		$this->load->view('page',$data);
	}

	
	public function get_product_ticket()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$regional 		= $this->input->post('regional');
		$kprk 			= $this->input->post('kprk');
		$filter 		= $this->input->post('filter');
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true) ? $this->input->post('order') : ' desc ';
		$sort			= $this->input->post('sort', true) ? $this->input->post('sort') : ' a.date ';
		
		$date_start		= $this->input->post('start', true) ? $this->input->post('start', true) : date('Y-m-d', strtotime('-1 months'));
		$date_end		= $this->input->post('end', true) ? $this->input->post('end', true) : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		//$status			= $this->input->post('status', true) ? $this->input->post('status', true) : '';
		
		$export			= $this->input->post('export', true) ? $this->input->post('export', true) : '';
		if($export != ''){
			$row_show	= '';
			$row_start	= '';
		}

		//$reg 				= $this->mreport->get_regional($regid);
		//$reg_row	 		= $reg->row();


		$total_ticket 		= 0;
		$tot_selesai 		= 0;
		$tot_terbuka 		= 0;
		
		$date				= array($date_start, $date_end);
		$rows['total'] 		= 0;
		$rows['rows'] 		= array();
		$rows['footer'] 	= array();
		$tquery 			= $this->mreport->get_product_ticket($date, $regional, $kprk);
		$rows['total'] 	= $tquery->num_rows();
		$tquery->next_result(); 
		$tquery->free_result(); 
		$query 			= $this->mreport->get_product_ticket($date, $regional, $kprk);

		$rows['chart_product']		= array();
		$product['datasets']		= array();
		$product['labels']			= array();

		if($export == 'xls')
		{
			$result 	= $this->createExcelProduct($query);
			echo json_encode($result);
		}else{
			if($query->num_rows() > 0)
			{
				
				foreach($query->result() as $row)
				{

					if($row->total_ticket > 0)
					{
						$prod_value[]	= (int) $row->total_ticket;
						$prod_label[]	= $row->name.' ('.$row->total_ticket.')';
					}
					

					array_push($rows['rows'],$row);
				}

				$product['datasets']		= array(array('data'=>$prod_value,'backgroundColor'=>$this->config->item('color')));
				$product['labels']		= $prod_label;
				//$rows['footer'] = array(array('fullname'=>'TOTAL','city'=>'','total_ticket'=>$total_ticket, 'total_selesai'=>$tot_selesai, 'total_terbuka'=>$tot_terbuka, 'isFooter'=>true))	;
			}

			$rows['chart_product']	= $product;
			echo json_encode($rows);
		}
	}

	public function load_detail_info_product()
	{
		$uid 			= $this->input->post('uid');
		
		$query			= $this->mreport->get_product_info($uid);
		$data['row']	= $query->row();
		
		echo $this->load->view('product_side', $data, true);
	}

	public function load_sidebar_product()
	{
		$product 		= $this->input->post('code');
		$start 			= $this->input->post('start');
		$end 			= $this->input->post('end');

		

		$in_out['datasets']		= array();
		$in_out['labels']		= array();
		$query 		= $this->mreport->get_product_detail_by_regional($start, $end, $product);
		if($query->num_rows() > 0)
		{
			#$row 	= $query->row();
			foreach($query->result() as $row)
			{
				$prod_value[]	= (int) $row->total_ticket;
				$prod_label[]	= $row->name.' ('.$row->total_ticket.')';
			}
			#$val = array((int) $row->ticket_masuk, (int) $row->ticket_keluar);
			#$lab = array('Masuk', 'Keluar');
			$in_out['datasets']		= array(array('data'=>$prod_value,'backgroundColor'=>$this->config->item('color')));
			$in_out['labels']		= $prod_label;
		}

		$query->next_result(); 
		$query->free_result(); 

		$kprk['datasets']		= array();
		$kprk['labels']		= array();
		$query 	= $this->mreport->get_product_detail_in_kprk($start, $end, $product);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			foreach($query->result() as $row)
			{
				$kprk_value[]	= (int) $row->total_ticket;
				$kprk_label[]	= $row->name.' ('.$row->total_ticket.')';
			}
			#$val = array((int) $row->ticket_masuk, (int) $row->ticket_keluar);
			#$lab = array('Masuk', 'Keluar');
			$kprk['datasets']		= array(array('data'=>$kprk_value,'backgroundColor'=>$this->config->item('color')));
			$kprk['labels']		= $kprk_label;
		}
		
		$result['regional']		= $in_out;
		$result['kprk']		= $kprk;

		echo json_encode($result);
	}

	
	public function ticket()
	{
		$data['content']	= $this->load->view('index','',true);
		$this->load->view('page',$data);
	}
	
	public function getTicketData()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true) ? $this->input->post('order') : ' desc ';
		$sort			= $this->input->post('sort', true) ? $this->input->post('sort') : ' a.date ';
		
		$date_start		= $this->input->post('start', true) ? $this->input->post('start', true) : date('Y-m-d', strtotime('-1 months'));
		$date_end		= $this->input->post('end', true) ? $this->input->post('end', true) : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		$status			= $this->input->post('status', true) ? $this->input->post('status', true) : '';
		
		$export			= $this->input->post('export', true) ? $this->input->post('export', true) : '';
		if($export != ''){
			$row_show	= '';
			$row_start	= '';
		}
		
		$date			= array($date_start, $date_end);
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mreport->getTicketDataTotal($search, $status, $date);
		$query 			= $this->mreport->getTicketData($search, $status, $date, $row_show, $row_start, $sort, $order);
		#echo $this->db->last_query();
		if($export == 'xls')
		{
			$result 	= $this->createExcelReport($query);
			echo json_encode($result);

		}else{
			if($query->num_rows() > 0)
			{
				$rows['total'] 	= $total;
				foreach($query->result() as $row)
				{
					$tujuan 	= explode(',', $row->tujuan_pengaduan);
					$row->kantor_tujuan_name 	= $this->mglobals->get_kantor_name_code($tujuan);

					$sender 	= explode(',', $row->sender);
					$row->sender 	= $this->mglobals->get_kantor_name_code($sender);

					$receiver 	= explode(',', $row->receiver);
					$row->receiver 	= $this->mglobals->get_kantor_name_code($receiver);

					$time 		= explode(':', $row->tt);
					$hari 		= floor($time[0]/24);
					$jam 		= $time[0] - ($hari * 24);
					$new_time 	= ($hari > 0) ? $hari.' Hari '.$jam.':'.$time[1].':'.$time[2] : $jam.':'.$time[1].':'.$time[2];

					$row->duration_id = $new_time;
					
					array_push($rows['rows'],$row);
				}
			}
			echo json_encode($rows);
		}
		
		
	}

	public function load_grid_data()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$value['status']	= array();

		$query 	= $this->mglobals->getTicketStatus();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value['status'], $row);
			}
		}

		$value['date_end']			= date('Y-m-d');
		$value['date_start']		= date('Y-m-d', strtotime('-1 months'));

		echo json_encode($value);
	}
	
	//
	public function dashboard()
	{
		$data['content']	= $this->load->view('dashboard','',true);
		$this->load->view('page',$data);
	}

	public function load_chart()
	{
	
		$month 		= $this->input->post('month');
		$year 		= $this->input->post('year');

		$date 		= '2019-08-01';
		if($month != ''){
			$date	= $year.'-'.$month.'-01';
		}

		$result 	= array();

		$report['datasets']		= array();
		$report['labels']		= array();
		$pencapaian	= array();

		$query 	= $this->mreport->get_cch_kpi($date);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			
			$grade_b 	= $row->total_ticket - $row->grade_a;
			$label	= array('1 Hari','1 Hari Lebih');
			$value	= array((int) $row->grade_a, (int) $grade_b);

			$pencapaian[]		= array('name'=>'24 Jam', 'y'=>(int) $row->grade_a);
			$pencapaian[]		= array('name'=>'> 24 Jam', 'y'=>(int) $grade_b);

			$report['datasets']	= array(array('data'=>$value,'backgroundColor'=>array('rgba(239,179,45,1)','rgba(28,122,201,1)')));
			$report['labels']		= $label;
		}
		$query->next_result(); 
		$query->free_result();


		$asal['datasets']	= array();
		$asal['labels']		= array();
		$asal_aduan			= array();
		$query 	= $this->mreport->get_kantor_asal($date);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$asal_value[]	= (int) $row->_total;
				$asal_label[]	= $row->_name;
				$asal_aduan[]	= array('name'=>$row->_name, 'y'=>(int)$row->_total, 'id'=>$row->_name);
			}
			$asal['datasets']	= array(array('data'=>$asal_value,'backgroundColor'=>$this->config->item('color')));
			$asal['labels']		= $asal_label;
		}
		$query->next_result(); 
		$query->free_result();

		$tujuan['datasets']		= array();
		$tujuan['labels']		= array();
		$tujuan_aduan			= array();
		$query 	= $this->mreport->get_kantor_tujuan($date);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$tujuan_value[]	= (int) $row->_total;
				$tujuan_label[]	= $row->_name;
				$tujuan_aduan[]	= array('name'=>$row->_name, 'y'=>(int) $row->_total, 'id'=>$row->_name);
			}
			$tujuan['datasets']	= array(array('data'=>$tujuan_value,'backgroundColor'=>$this->config->item('color')));
			$tujuan['labels']		= $tujuan_label;
		}
		$query->next_result(); 
		$query->free_result();

		$product['datasets']	= array();
		$product['labels']		= array();
		$query 	= $this->mreport->get_jenis_produk($date);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$prod_value[]	= (int) $row->_total;
				$prod_label[]	= $row->_name.' ('.$row->_total.')';
			}
			$product['datasets']	= array(array('data'=>$prod_value,'backgroundColor'=>$this->config->item('color')));
			$product['labels']		= $prod_label;
		}
		$query->next_result(); 
		$query->free_result();

		$masalah	= array();
		$query 	= $this->mreport->get_masalah_ticket($date);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$masalah[]	= array('id'=>$row->id, 'name'=>$row->name, 'value'=>(int)$row->total);
				if($row->collecting > 0){
					$masalah[]	= array('parent'=>$row->id, 'name'=>'Collecting', 'value'=>(int)$row->collecting);
				}
				if($row->processing > 0){
					$masalah[]	= array('parent'=>$row->id, 'name'=>'Processing', 'value'=>(int)$row->processing);
				}
				if($row->transporting > 0){
					$masalah[]	= array('parent'=>$row->id, 'name'=>'Transporting', 'value'=>(int)$row->transporting);
				}
				if($row->delivery > 0){
					$masalah[]	= array('parent'=>$row->id, 'name'=>'Delivery', 'value'=>(int)$row->delivery);
				}
				if($row->reporting > 0){
					$masalah[]	= array('parent'=>$row->id, 'name'=>'Reporting', 'value'=>(int)$row->reporting);
				}
			}
		}
	

		$result['pencapaian']		= $pencapaian;
		$result['asal_aduan']		= $asal_aduan;
		$result['tujuan_aduan']		= $tujuan_aduan;
		$result['masalah']			= $masalah;

		$result['report']		= $report;
		$result['asal']			= $asal;
		$result['tujuan']		= $tujuan;
		$result['product']		= $product;

		echo json_encode($result);
	}

	public function reload_dashboard()
	{
		$date 			= $this->input->post('date') ? $this->input->post('date') : date('Y-m-d');
		$pencapaian 	= $this->input->post('pencapaian');
		$reg_asal 		= $this->input->post('regional_asal');
		$reg_tujuan 	= $this->input->post('regional_tujuan');

		$asal_aduan			= array();
		$query 	= $this->mreport->get_kantor_asal($date, $pencapaian);
		#echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$asal_aduan[]	= array('name'=>$row->_name, 'y'=>(int)$row->_total, 'id'=>$row->_name);
			}
		}
		$query->next_result(); 
		$query->free_result();

		$tujuan_aduan			= array();
		$query 	= $this->mreport->get_kantor_tujuan($date, $pencapaian, $reg_asal);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$tujuan_aduan[]	= array('name'=>$row->_name, 'y'=>(int) $row->_total, 'id'=>$row->_name);
			}
		}
		$query->next_result(); 
		$query->free_result();

		$product['datasets']	= array();
		$product['labels']		= array();
		$query 	= $this->mreport->get_jenis_produk($date, $pencapaian, $reg_asal, $reg_tujuan);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$prod_value[]	= (int) $row->_total;
				$prod_label[]	= $row->_name.' ('.$row->_total.')';
			}
			$product['datasets']	= array(array('data'=>$prod_value,'backgroundColor'=>$this->config->item('color')));
			$product['labels']		= $prod_label;
		}
		$query->next_result(); 
		$query->free_result();

		$result['asal_aduan']		= $asal_aduan;
		$result['tujuan_aduan']		= $tujuan_aduan;
		$result['product']		= $product;
		#$result['masalah']			= $masalah;
		echo json_encode($result);
	}

	public function update_chart()
	{
		$month 		= $this->input->post('month');
		$year 		= $this->input->post('year');

		$kpi 			= $this->input->post('kpi');
		$kntr_asal 		= $this->input->post('asal');
		//$date 		= $this->input->post('date') ? $this->input->post('date') : date('Y-m-d');
		$date 		= '';
		if($month != '')
		{
			$date = $year.'-'.$month.'-01';
		}
		$where	= array();
		if($kpi == '1 HARI'){
			$where[]	= ' ADDTIME(SEC_TO_TIME(TIMESTAMPDIFF(second, a.date, DATE_ADD(a.date, interval TOTAL_WEEKDAYS_2(date(a.date), date(a.last_update)) day))), timediff(time(a.last_update), time(a.date))) <= "24:00:00" ';
		}else if($kpi == "1 HARI LEBIH"){
			$where[]	= ' ADDTIME(SEC_TO_TIME(TIMESTAMPDIFF(second, a.date, DATE_ADD(a.date, interval TOTAL_WEEKDAYS_2(date(a.date), date(a.last_update)) day))), timediff(time(a.last_update), time(a.date))) > "24:00:00" ';
		}

		
		
		$asal['datasets']		= array();
		$asal['labels']			= array();
		$query 	= $this->mreport->get_kantor_asal($date, $where);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$asal_value[]	= (int) $row->total;
				$asal_label[]	= $row->regional;
			}
			$asal['datasets']	= array(array('data'=>$asal_value,'backgroundColor'=>$this->config->item('color')));
			$asal['labels']		= $asal_label;
		}

		if(!empty($kntr_asal)){
			$where[]	= ' c.regional = "'.$kntr_asal.'" ';
		}
		
		$tujuan['datasets']		= array();
		$tujuan['labels']		= array();
		$query 	= $this->mreport->get_kantor_tujuan($date, $where);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$tujuan_value[]	= (int) $row->total;
				$tujuan_label[]	= $row->regional;
			}
			$tujuan['datasets']	= array(array('data'=>$tujuan_value,'backgroundColor'=>$this->config->item('color')));
			$tujuan['labels']		= $tujuan_label;
		}

		$product['datasets']	= array();
		$product['labels']		= array();
		$query 	= $this->mreport->get_jenis_produk($date, $where);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$prod_value[]	= (int) $row->total;
				$prod_label[]	= $row->name.' ('.$row->total.')';
			}
			$product['datasets']	= array(array('data'=>$prod_value,'backgroundColor'=>$this->config->item('color')));
			$product['labels']		= $prod_label;
		}
		
		$result['tujuan']		= $tujuan;
		$result['asal']			= $asal;
		$result['product']		= $product;
		echo json_encode($result);
	}

	// XRAY Report 
	public function xray()
	{
		$data['content']	= $this->load->view('xray','',true);
		$this->load->view('page',$data);
	}

	public function load_xray_chart()
	{
		$month 		= $this->input->post('month') ? $this->input->post('month') : date('m');
		$year 		= $this->input->post('year');

		$regional_asal 		= $this->input->post('regional_asal');
		$regional_tujuan 	= $this->input->post('regional_tujuan');

		$kantor_asal 		= $this->input->post('kantor_asal');
		$kantor_terbangan 	= $this->input->post('kantor_terbangan');
		$kantor_tujuan 		= $this->input->post('kantor_tujuan');

		$date 		= '2019-07-01';
		if($month != ''){
			$date	= $year.'-'.$month.'-01';
		}

		$result 	= array();

		$xray_regional_asal_kirim		= array();
		$query 	= $this->mreport->get_xray_regional_asal_kirim($date);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$xray_regional_asal_kirim[]	= array('name'=>$row->_name, 'y'=>(int) $row->_total);
			}
		}
		$query->next_result(); 
		$query->free_result(); 
		
		$xray_regional_tujuan_kirim		= array();
		$query 	= $this->mreport->get_xray_regional_tujuan_kirim($date, $regional_asal);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$xray_regional_tujuan_kirim[]	= array('name'=>$row->_name, 'y'=>(int) $row->_total);
			}
		}
		$query->next_result(); 
		$query->free_result(); 


		$asal_kirim['datasets']		= array();
		$asal_kirim['labels']		= array();
		$query 	= $this->mreport->get_xray_asal_kirim($date, $regional_asal, $regional_tujuan);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$asal_value[]	= (int) $row->_total;
				$asal_label[]	= $row->_name;
			}
			$asal_kirim['datasets']		= array(array('data'=>$asal_value,'backgroundColor'=>$this->config->item('color')));
			$asal_kirim['labels']		= $asal_label;
			
		}
		$query->next_result(); 
		$query->free_result(); 


		$terbangan['datasets']		= array();
		$terbangan['labels']		= array();
		$terbangan['rows']			= array();
		$query 	= $this->mreport->get_xray_asal_terbangan($date, $regional_asal, $regional_tujuan, $kantor_asal);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				#$asal_value[]	= (int) $row->_total;
				#$asal_label[]	= $row->_name;
				array_push($terbangan['rows'], $row);
			}
			#$asal_kirim['datasets']		= array(array('data'=>$asal_value,'backgroundColor'=>$this->config->item('color')));
			#$asal_kirim['labels']		= $asal_label;
			
		}
		$query->next_result(); 
		$query->free_result(); 

		$tujuan_kirim['datasets']	= array();
		$tujuan_kirim['labels']		= array();
		$query 	= $this->mreport->get_xray_tujuan_kirim($date, $regional_asal, $regional_tujuan, $kantor_asal);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$tujuan_value[]	= (int) $row->_total;
				$tujuan_label[]	= $row->_name;
			}
			$tujuan_kirim['datasets']		= array(array('data'=>$tujuan_value,'backgroundColor'=>$this->config->item('color')));
			$tujuan_kirim['labels']		= $tujuan_label;
			
		}
		$query->next_result(); 
		$query->free_result();


		$tag_cloud['datasets']	= array();
		$tag_cloud['labels']		= array();
		$query 	= $this->mreport->get_xray_item_name($date, $regional_asal, $regional_tujuan, $kantor_asal, $kantor_tujuan);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$tags_total[]	= (int) $row->_total;
				$tags_label[]	= $row->_name;
				$mtags[]		= array('name'=>$row->_name, 'weight'=>(int) $row->_total);
			}
			$tag_cloud['datasets']		= $mtags;
			
		}
		$query->next_result(); 
		$query->free_result(); 


		//$harian['datasets']	= array();
		//$harian['labels']	= array();

		$lastDateOfMonth = date("t", strtotime($date));
		for($i = 0; $i<$lastDateOfMonth; $i++)
		{
			$label = date('Y-m-d', strtotime($i.' days', strtotime($date)));
			$check = date('d/m/y', strtotime($i.' days', strtotime($date)));
			$query 	= $this->mreport->get_xray_harian($label);	
			if($query->num_rows() > 0)
			{
				$row 		= $query->row();

				$mdata[]	= array($label, (int) $row->_total);
			}else{
				$mdata[]	= array($label, null);
			}
			$query->next_result(); 
			$query->free_result(); 
			
		}
		$harian 			= $mdata;
		$result['regional_asal_kirim']		= $xray_regional_asal_kirim;
		$result['regional_tujuan_kirim']	= $xray_regional_tujuan_kirim;
		$result['kantor_asal_kirim']		= $asal_kirim;
		$result['kantor_terbangan']			= $terbangan;
		$result['kantor_tujuan_kirim']		= $tujuan_kirim;
		$result['harian']					= $harian;
		$result['tag']						= $tag_cloud;
		#$result['asal']			= $asal;
		#$result['tujuan']		= $tujuan;
		#$result['product']		= $product;

		echo json_encode($result);	
	}


	// EXCEL
	public function createExcelReport($query)
	{
		$spreadsheet    = new PhpOffice\PhpSpreadsheet\Spreadsheet;
        // HEADER 
        $start_row      = 1;
        $spreadsheet->setActiveSheetIndex(0)
         ->setCellValue('B'.$start_row, 'No Ticket')
         ->setCellValue('C'.$start_row, 'Nama Pelapor')
         ->setCellValue('D'.$start_row, 'Asal Pengaduan')
         ->setCellValue('E'.$start_row, 'Tujuan Pengaduan')
         ->setCellValue('F'.$start_row, 'Asal Kiriman')
         ->setCellValue('G'.$start_row, 'Tujuan Kiriman')
         ->setCellValue('H'.$start_row, 'Produk Pos')
         ->setCellValue('I'.$start_row, 'Tgl Pengaduan')
         ->setCellValue('J'.$start_row, 'No Barcode / AWB')
		 ->setCellValue('K'.$start_row, 'Durasi');
		 
		 $header_row =[
            'font' =>['bold' => true],
            'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ];
		$spreadsheet->getActiveSheet()->getStyle('B'.$start_row.':K'.$start_row)->applyFromArray($header_row);
		

		if($query->num_rows() > 0)
        {
            $i = $start_row + 1;
            foreach($query->result() as $row)
            {
                $date         = explode(' ',$row->date);
				$time         = explode(':', $date[1]);
				
				$tujuan 	= explode(',', $row->tujuan_pengaduan);
				$kantor_tujuan_name 	= $this->mglobals->get_kantor_name_code($tujuan);

				$sender 	= explode(',', $row->sender);
				$sender_val 	= $this->mglobals->get_kantor_name_code($sender);

				$receiver 	= explode(',', $row->receiver);
				$receiver_val 	= $this->mglobals->get_kantor_name_code($receiver);

				$time 		= explode(':', $row->tt);
				$hari 		= floor($time[0]/24);
				$jam 		= $time[0] - ($hari * 24);
				$new_time 	= ($hari > 0) ? $hari.' Hari '.$jam.':'.$time[1].':'.$time[2] : $jam.':'.$time[1].':'.$time[2];

				$tgl_pengaduan = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel( $date[0].' '.$date[1] );
				
				
                //$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i, $row->no_polisi);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $row->no_ticket);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $row->contact_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $row->complaint_origin);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $kantor_tujuan_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $sender_val);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $receiver_val);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $row->product);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $tgl_pengaduan);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $row->awb);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $new_time);
                //$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $row->shop_name);
                
                $i++;
            }
        }
        $last   =   $i - 1;
		$spreadsheet->getActiveSheet()->getStyle('I2:I'.$last)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME);
		//$spreadsheet->getActiveSheet()->getStyle('K2:K'.$last)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_TIME);
        $spreadsheet->getActiveSheet()->setTitle('Laporan Pengaduan');
        
        $writer         = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename       = 'export_pengaduan_'.date('ymd_his').'.xls';
        $fxls           = './storage/'.$filename;
        
        $writer->save($fxls);
        
        if(file_exists($fxls)){
            $result     = array('status'=>true, 'filename'=>$filename,'path'=>base_url('storage/'.$filename));
        }else{
            $result     = array('status'=>false, 'filename'=>$filename);
        }
        return $result;
	}

	private function createExcelIncoming($query, $excel_name = 'Laporan Tiket Masuk Nasional')
	{
		$spreadsheet    = new PhpOffice\PhpSpreadsheet\Spreadsheet;
        // HEADER 
        $start_row      = 1;
        $spreadsheet->setActiveSheetIndex(0)
         ->setCellValue('B'.$start_row, 'Regional')
         ->setCellValue('C'.$start_row, 'Jumlah Pengaduan')
         ->setCellValue('D'.$start_row, 'Jml Selesai')
         ->setCellValue('E'.$start_row, '(%) Selesai')
         ->setCellValue('F'.$start_row, 'Jml Terbuka')
         ->setCellValue('G'.$start_row, '(%) Terbuka');
		 
		 $header_row =[
            'font' =>['bold' => true],
            'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ];
		$spreadsheet->getActiveSheet()->getStyle('B'.$start_row.':G'.$start_row)->applyFromArray($header_row);
		

		$total_ticket	= 0;
		$tot_selesai 	= 0;
		$tot_terbuka 	= 0;
		if($query->num_rows() > 0)
        {
			$i = $start_row + 1;
			foreach($query->result() as $row)
			{
				$total_ticket	+= $row->total_ticket;
				$tot_selesai 	+= $row->total_selesai;
				$tot_terbuka 	+= $row->total_terbuka;
			}
            foreach($query->result() as $row)
            {
			
				$row->percent_selesai	= ($row->total_ticket > 0 ) ? number_format(($row->total_selesai * 100) / $tot_selesai,1) : 0.0;
				$row->percent_terbuka	= ($row->total_ticket > 0 ) ? number_format(($row->total_terbuka * 100) / $tot_terbuka,1) : 0.0;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $row->regional);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $row->total_ticket);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $row->total_selesai);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $row->percent_selesai);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $row->total_terbuka);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $row->percent_terbuka);
                
                
                $i++;
            }
        }
        $last   =   $i - 1;
		$spreadsheet->getActiveSheet()->setTitle($excel_name);
        
        $writer         = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename       = 'export_pengaduan_nasional_'.date('ymd_his').'.xls';
        $fxls           = './storage/'.$filename;
        
        $writer->save($fxls);
        
        if(file_exists($fxls)){
            $result     = array('status'=>true, 'filename'=>$filename,'path'=>base_url('storage/'.$filename));
        }else{
            $result     = array('status'=>false, 'filename'=>$filename);
        }
        return $result;
	}

	private function createExcelIncomingRegional($query, $excel_name = 'Laporan Tiket Masuk Regional')
	{
		$spreadsheet    = new PhpOffice\PhpSpreadsheet\Spreadsheet;
        // HEADER 
        $start_row      = 1;
        $spreadsheet->setActiveSheetIndex(0)
         ->setCellValue('B'.$start_row, 'KPRK / UPT')
         ->setCellValue('C'.$start_row, 'Jumlah Pengaduan')
         ->setCellValue('D'.$start_row, 'Jml Selesai')
         ->setCellValue('E'.$start_row, '(%) Selesai')
         ->setCellValue('F'.$start_row, 'Selesai 24 Jam')
		 ->setCellValue('G'.$start_row, 'Selesai > 24 Jam')
		 ->setCellValue('H'.$start_row, 'Jml Terbuka')
         ->setCellValue('I'.$start_row, '(%) Terbuka')
         ->setCellValue('J'.$start_row, 'Terbuka 24 Jam')
         ->setCellValue('K'.$start_row, 'Terbuka > 24 Jam');
		 
		 $header_row =[
            'font' =>['bold' => true],
            'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ];
		$spreadsheet->getActiveSheet()->getStyle('B'.$start_row.':K'.$start_row)->applyFromArray($header_row);
		

		$total_ticket	= 0;
		$tot_selesai 	= 0;
		$tot_terbuka 	= 0;
		if($query->num_rows() > 0)
        {
			$i = $start_row + 1;
            foreach($query->result() as $row)
            {
			
				$row->percent_selesai	= ($row->total_ticket > 0 ) ? number_format(($row->total_selesai * 100) / $row->total_ticket,1) : 0.0;
				$row->percent_terbuka	= ($row->total_ticket > 0 ) ? number_format(($row->total_terbuka * 100) / $row->total_ticket,1) : 0.0;
				$row->isFooter			= false;

				$total_ticket 	+= $row->total_ticket;
				$tot_selesai 	+= $row->total_selesai;
				$tot_terbuka 	+= $row->total_terbuka;

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $row->fullname);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $row->total_ticket);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $row->total_selesai);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $row->percent_selesai);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $row->selesai_a);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $row->selesai_b);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $row->total_terbuka);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$i, $row->percent_terbuka);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$i, $row->terbuka_a);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$i, $row->terbuka_b);
                
                
                $i++;
            }
        }
        $last   =   $i - 1;
		$spreadsheet->getActiveSheet()->setTitle($excel_name);
        
        $writer         = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename       = 'export_pengaduan_regional_'.date('ymd_his').'.xls';
        $fxls           = './storage/'.$filename;
        
        $writer->save($fxls);
        
        if(file_exists($fxls)){
            $result     = array('status'=>true, 'filename'=>$filename,'path'=>base_url('storage/'.$filename));
        }else{
            $result     = array('status'=>false, 'filename'=>$filename);
        }
        return $result;
	}

	private function createExcelIncomingKprk($query, $excel_name = "Laporan Tiket Masuk KPRK")
	{
		$spreadsheet    = new PhpOffice\PhpSpreadsheet\Spreadsheet;
        // HEADER 
        $start_row      = 1;
        $spreadsheet->setActiveSheetIndex(0)
         ->setCellValue('B'.$start_row, 'No Ticket')
         ->setCellValue('C'.$start_row, 'Produk Pos')
         ->setCellValue('D'.$start_row, 'No Barcode / AWB')
         ->setCellValue('E'.$start_row, 'Status Akhir')
         ->setCellValue('F'.$start_row, 'Kantor Terakhir Update')
         ->setCellValue('G'.$start_row, 'Tgl Terakhir Update')
         ->setCellValue('H'.$start_row, 'Durasi');
		 
		 $header_row =[
            'font' =>['bold' => true],
            'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ];
		$spreadsheet->getActiveSheet()->getStyle('B'.$start_row.':H'.$start_row)->applyFromArray($header_row);
		

		if($query->num_rows() > 0)
        {
            $i = $start_row + 1;
            foreach($query->result() as $row)
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $row->no_ticket);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $row->product_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, $row->awb);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i, $row->status_text);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i, $row->office_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$i, $row->last_response);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$i, $row->duration);
                //$spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$i, $row->shop_name);
                
                $i++;
            }
        }
        $last   =   $i - 1;
		$spreadsheet->getActiveSheet()->getStyle('I2:I'.$last)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME);
		$spreadsheet->getActiveSheet()->setTitle($excel_name);
        
        $writer         = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename       = 'export_pengaduan_kprk_'.date('ymd_his').'.xls';
        $fxls           = './storage/'.$filename;
        
        $writer->save($fxls);
        
        if(file_exists($fxls)){
            $result     = array('status'=>true, 'filename'=>$filename,'path'=>base_url('storage/'.$filename));
        }else{
            $result     = array('status'=>false, 'filename'=>$filename);
        }
        return $result;
	}

	private function createExcelProduct($query, $excel_name = "Laporan Pengaduan Produk POS")
	{
		$spreadsheet    = new PhpOffice\PhpSpreadsheet\Spreadsheet;
        // HEADER 
        $start_row      = 1;
        $spreadsheet->setActiveSheetIndex(0)
         ->setCellValue('B'.$start_row, 'Produk POS')
         ->setCellValue('C'.$start_row, 'Jumlah Pengaduan');
		 
		 $header_row =[
            'font' =>['bold' => true],
            'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ];
		$spreadsheet->getActiveSheet()->getStyle('B'.$start_row.':C'.$start_row)->applyFromArray($header_row);
		
		if($query->num_rows() > 0)
        {
			$i = $start_row + 1;
            foreach($query->result() as $row)
            {
			
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i, $row->name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i, $row->total_ticket);
                
                
                $i++;
            }
        }
        $last   =   $i - 1;
		$spreadsheet->getActiveSheet()->setTitle($excel_name);
        
        $writer         = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename       = 'export_pengaduan_regional_'.date('ymd_his').'.xls';
        $fxls           = './storage/'.$filename;
        
        $writer->save($fxls);
        
        if(file_exists($fxls)){
            $result     = array('status'=>true, 'filename'=>$filename,'path'=>base_url('storage/'.$filename));
        }else{
            $result     = array('status'=>false, 'filename'=>$filename);
        }
        return $result;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */