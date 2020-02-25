<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Authentication
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Home extends CI_Controller {

	private $pos_office;
	private $active_user; 
	private $active_role;
	private $active_regional; 
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mhome');
		$this->pos_office 		= $this->session->userdata('pos_office');
		$this->active_user 		= $this->session->userdata('ses_username');
		$this->active_role	 	= $this->session->userdata('ses_role');

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
		if($this->active_role == '0007'){
			$data['content']	= $this->load->view('marketplace','',true);
		}else{
			$data['content']		= $this->load->view('blank','',true);
		}
		$this->load->view('page', $data);
	}
	
	public function getTotalTicketWeekly()
	{
		$value	= array();
		$start 	= date('Y-m-d');

		$active = implode(',', $this->active_regional);
		for($i=-7; $i<=0; $i++)
		{
			$date	= date("Y-m-d", strtotime($i.' days', strtotime($start)));
			
			$query 	= $this->mglobals->getTotalTicketWeekly($date, $active);
			
			if($query->num_rows() > 0)
			{
				$row 	= $query->row();
				$value[]	= array('day'=>$date, 'v'=>(int) $row->total_aduan, 'x'=>(int) $row->total_info);
			}else{
				$value[]	= array('day'=>$date, 'v'=>0, 'x'=>0);
			}
			$query->next_result();
			$query->free_result();
		}
		echo json_encode($value);
		
	}
	
	public function getTicketMonthly()
	{
		$result	= array();

		$start 		= $this->input->post('start') ? $this->input->post('start') : date('2019-07-01');
		$end 		= $this->input->post('end') ? $this->input->post('end') : date('Y-m-t');
		$kprk 		= $this->input->post('kprk') ? $this->input->post('kprk') : $this->active_regional;

		$im_kprk 	= "%".implode('%\" or a.tujuan_pengaduan like \"%', $kprk)."%";

		$data['datasets']	= array();
		$data['labels']	= array();
		$query 	= $this->mhome->dashboard_kprk_in($start, $end, $im_kprk);
		
		if($query->num_rows() > 0)
		{
			$row 		= $query->row();
			$grade_a 	= ($row->total > 0) ? ($row->grade_a / $row->total) * 100 : 0;
			$grade_b 	= ($row->total > 0) ? 100 - $grade_a : 0;

			$label	= array('24 Jam','> 24 Jam');
			$value	= array((float) $grade_a, (float) $grade_b);
			$info 	= array((int) $row->grade_a, (int) $row->grade_b);

			$data['datasets']	= array(array('data'=>$value,'info'=>$info, 'backgroundColor'=>array('rgba(239,179,45,1)','rgba(28,122,201,1)')));
			$data['labels']		= $label;
		}
		$query->next_result();
		$query->free_result();



		$product['datasets']	= array();
		$product['labels']		= array();		
		$query 	= $this->mhome->dashboard_produk_kprk_in($start, $end, $im_kprk);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$pvalue[]	= (int) $row->total;
				$plabel[]	= $row->label;
			}
			$product['datasets']	= array(array('data'=>$pvalue,'backgroundColor'=>array('rgba(122,122,201,1)','rgba(21,100,12,1)','rgba(255,10,10,1)','rgba(239,179,45,1)','rgba(28,122,201,1)')));
			$product['labels']		= $plabel;
		}
		$query->next_result();
		$query->free_result();


		$status['datasets']		= array();
		$status['labels']		= array();
		$query 	= $this->mhome->dashboard_category_kprk_in($start, $end, $im_kprk);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$svalue[]	= (int) $row->total;
				$slabel[]	= $row->label;
			}
			$status['datasets']	= array(array('data'=>$svalue,'backgroundColor'=>array('#EC971F','#31B0D5','#286090','#C9302C')));
			$status['labels']		= $slabel;
		}
		$query->next_result();
		$query->free_result();

		//
		$data_out['datasets']	= array();
		$data_out['labels']	= array();
		$query 	= $this->mhome->dashboard_kprk_out($start, $end, $im_kprk);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			#foreach($query->result() as $row)
			#{
			#	$value[]	= (int) $row->total;
			#	$label[]	= $row->label;
			#}

			$grade_a 	= ($row->total > 0) ? ($row->grade_a / $row->total) * 100 : 0;
			$grade_b 	= ($row->total > 0) ? 100 - $grade_a : 0;

			$label	= array('24 Jam','> 24 Jam');
			$value	= array((float) $grade_a, (float) $grade_b);
			$info 	= array((int) $row->grade_a, (int) $row->grade_b);

			$data_out['datasets']	= array(array('data'=>$value,'info'=>$info, 'backgroundColor'=>array('rgba(239,179,45,1)','rgba(28,122,201,1)')));
			$data_out['labels']		= $label;
		}
		$query->next_result();
		$query->free_result();



		$product_out['datasets']	= array();
		$product_out['labels']		= array();		
		$query 	= $this->mhome->dashboard_produk_kprk_out($start, $end, $im_kprk);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$pvalues[]	= (int) $row->total;
				$plabels[]	= $row->label;
			}
			$product_out['datasets']	= array(array('data'=>$pvalues,'backgroundColor'=>array('rgba(122,122,201,1)','rgba(21,100,12,1)','rgba(255,10,10,1)','rgba(239,179,45,1)','rgba(28,122,201,1)')));
			$product_out['labels']		= $plabels;
		}
		$query->next_result();
		$query->free_result();


		$status_out['datasets']		= array();
		$status_out['labels']		= array();
		$query 	= $this->mhome->dashboard_category_kprk_out($start, $end, $im_kprk);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$svalues[]	= (int) $row->total;
				$slabels[]	= $row->label;
			}
			$status_out['datasets']	= array(array('data'=>$svalues,'backgroundColor'=>array('#EC971F','#31B0D5','#286090','#C9302C')));
			$status_out['labels']		= $slabels;
		}
		$query->next_result();
		$query->free_result();

		$result['avg_time']	= $this->mhome->average_close_ticket_monthly();
		
		$result['kiriman']	= $data;
		$result['product']	= $product;
		$result['status']	= $status;

		$result['kiriman_out']	= $data_out;
		$result['product_out']	= $product_out;
		$result['status_out']	= $status_out;
		echo stripslashes(json_encode($result));
		
	}
	
	public function getTicketAll()
	{
		$value['ticket']			= array();
		$value['ticket_mum']		= array();
		$query			= $this->mhome->getTicketAll();
		
		$field_name		= $query->list_fields();
		$total_field	= count($field_name);
		$data 			= array();
		#print_r($field_name[1]);
		
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				#echo $field_name;
				#$data	= array();
				#print_r($row);
				for($i=0; $i<$total_field; $i++){
					if( $field_name[$i] == 'name'){
						$data['name']	= $row->name;
					}else if($field_name[$i] == 'Info_Product'){
						$data['Info_Product']	= $row->Info_Product;
					}else if($field_name[$i] == 'Complaint'){
						$data['Complaint']	= $row->Complaint;
					}else if($field_name[$i] == 'Order'){
						$data['Order']	= $row->Order;
					}else if($field_name[$i] == 'Uncategories'){
						$data['Uncategories']	= $row->Uncategories;
					}
					#$data[$field_name[$i]]	= $row->$field_name[$i];
					//echo $field_name[$i];
				}
				#print_r($data);
				#for($i=0; $i<$total_field; $i++){
				#	echo $row->$field_name[$i];
				#}
				#array_push($data, $row);
				$value['ticket'][]	= $data;
			}
		}
		$query->next_result();
		$query->free_result();
		
		
		
		
		echo json_encode($value);
	}
	
	public function getDashboardStat()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$start 	= $this->input->post('start') ? $this->input->post('start') : date('2019-07-01');
		$end 	= $this->input->post('end') ? $this->input->post('end') : date('Y-m-t');
		$kprk 	= $this->input->post('kprk') ? $this->input->post('kprk') : $this->active_regional;
		
		$im_kprk 	= "%".implode('%\" or a.tujuan_pengaduan like \"%', $kprk)."%";

		$query 	= $this->mhome->dashboard_kprk_stat_in_out($start, $end, $im_kprk);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($row->pos =="in"){
					$data['all_ticket_in']			= $row->all_ticket;
					$data['all_ticket_solved_in']	= $row->all_ticket_solved;
					$data['all_ticket_unsolved_in']	= $row->all_ticket_unsolved;
					$data['avg_ticket_in']				= floor($row->avg_ticket);
				} else if($row->pos == "out"){
					$data['all_ticket_out']			= $row->all_ticket;
					$data['all_ticket_solved_out']	= $row->all_ticket_solved;
					$data['all_ticket_unsolved_out']	= $row->all_ticket_unsolved;
					$data['avg_ticket_out']				= floor($row->avg_ticket);
				}
			}
		}else{
			$data['all_ticket_in']			= 0;
			$data['all_ticket_solved_in']	= 0;
			$data['all_ticket_unsolved_in']	= 0;
			$data['avg_ticket_in']				= 0;

			$data['all_ticket_out']			= 0;
			$data['all_ticket_solved_out']	= 0;
			$data['all_ticket_unsolved_out']	= 0;
			$data['avg_ticket_out']				= 0;
		}
		$result['stat']	= $data;
		/*
		$query				= $this->mhome->getAverageCloseTicket();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($row->id == 1){
					$text	= "info";
				}else if($row->id == 2){
					$text	= "complaint";
				}else if($row->id == 5){
					$text	= "order";
				}else if($row->id == 6){
					$text	= "register";
				}
				
				$data[$text]	= number_format((float)$row->avg_hour, 2, '.', '');
			}
		}
		$query->next_result();
		$query->free_result();
		
		
		$query	= $this->mhome->getTicketResponse();			
		if($query->num_rows() > 0)
		{
			$row					= $query->row();
			$dClosebySystem			= $row->system_closed;
			$dClosebyAgent			= 100 - $dClosebySystem;
			
			$response[]				= array('label'=>'Closed By System', 'value'=>number_format((float)$dClosebySystem, 2, '.', ''));
			$response[]				= array('label'=>'Closed By Agent', 'value'=>number_format((float)$dClosebyAgent, 2, '.', ''));
			$data['response']		= $response;
			$query->next_result();
			$query->free_result();
		}
		
		$query	= $this->mhome->getSolvedToClosed();			
		if($query->num_rows() > 0)
		{
			$row					= $query->row();
			$data['solved_to_close']= number_format((float)$row->total, 0, '.', '')."%";
			$query->next_result();
			$query->free_result();
		}
		*/
		echo json_encode($result);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */