<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Customer Care
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Kprk extends CI_Controller {
	
	var $client_id;
	var $active_user;
	var $pos_office;
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mkprk');

		$this->client_id 	= $this->session->userdata('ses_cid');
		$this->active_user 	= $this->session->userdata('ses_username');
		$this->pos_office 	= $this->session->userdata('pos_office');
	}
	
	public function index()
	{
		$data['content']	= $this->load->view('index','',true);
		$this->load->view('page',$data);
	}
	
	public function get_data_list()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);

		$start 			= $this->input->post('start') ? $this->input->post('start') : date('Y-m-01');
		$end 			= $this->input->post('end') ? $this->input->post('end') : date('Y-m-t');
		
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		$product 		= $this->input->post('product') ? $this->input->post('product') : '';
		
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mkprk->get_total_data($search, $product);
		$query 			= $this->mkprk->get_data($search, $product, $row_show, $row_start, $sort, $order);
		
		if($query->num_rows() > 0)
		{
			$rows['total'] 	= $total;
			foreach($query->result() as $row)
			{
				$subquery 	= $this->mkprk->get_ticket_inout($row->code, $start, $end);
				$mrows 		= $subquery->row();
				
				$row->total_ticket_keluar	= $mrows->ticket_keluar;
				$row->total_ticket_masuk 	= $mrows->ticket_masuk;

				array_push($rows['rows'],$row);
			}
		}
		echo json_encode($rows);
	}

	public function load_detail_info()
	{
		
		$uid 			= $this->input->post('uid');
		
		$query			= $this->mkprk->get_kantor_pos($uid);
		$data['row']	= $query->row();
		
		echo $this->load->view('index_side', $data, true);
	}

	public function load_sidebar()
	{
		$code 		= $this->input->post('code');
		$start 		= $this->input->post('start');
		$end 		= $this->input->post('end');

		$in_out['datasets']		= array();
		$in_out['labels']		= array();
		$query 	= $this->mkprk->get_ticket_inout($code, $start, $end);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			#foreach($query->result() as $row)
			#{
			#	$prod_value[]	= (int) $row->total;
			#	$prod_label[]	= $row->name.' ('.$row->total.')';
			#}
			$val = array((int) $row->ticket_masuk, (int) $row->ticket_keluar);
			$lab = array('Masuk', 'Keluar');
			$in_out['datasets']		= array(array('data'=>$val,'backgroundColor'=>$this->config->item('color')));
			$in_out['labels']		= $lab;
		}
		
		$result['in_out']		= $in_out;

		echo json_encode($result);
	}

	public function load_grid_data()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$value['date_end']			= date('Y-m-t');
		$value['date_start']		= date('Y-m-01');

		echo json_encode($value);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */