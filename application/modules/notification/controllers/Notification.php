<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Notification
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Notification extends CI_Controller {
	
	private $active_user;
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mnotif');
		$this->active_user 	= $this->session->userdata('ses_username');
	}
	
	public function index()
	{
		$data['content']	= $this->load->view('index','',true);
		$this->load->view('page',$data);
	}
	
	public function get_datalist()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mnotif->getDataTotal($search, $date);
		$query 			= $this->mnotif->getData($search, $row_show, $row_start, $sort, $order);
		
		if($query->num_rows() > 0)
		{
			$rows['total'] 	= $total;
			foreach($query->result() as $row)
			{
				array_push($rows['rows'],$row);
			}
		}
		echo json_encode($rows);
	}

	public function form_load()
	{
		$uid 		= $this->input->post('uid');

		$data['form'] 		= array();
		$query 				= $this->mnotif->get_notification_data($uid);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$time_ex 		= explode('-', $row->event_date);
			$row->start 	= $row->event_date;#$time_ex[1].'/'.$time_ex[2].'/'.$time_ex[0];
			$row->end 		= $row->event_date;
			$data['form']	= $row;
		}

		echo json_encode($data);
	}
	
	public function form()
	{
		$content['page_title']	= $this->uri->segment(3) == '' ? 'Create Contact' : 'Edit Contact';
		$data['content']	= $this->load->view('form',$content,true);
		$this->load->view('page',$data);
	}
	
	public function edit()
	{
		$this->create();
	}

	public function save()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$uid 			= $this->input->post('uid');
		$title			= $this->input->post('title', true);
		$description	= $this->input->post('detail', true);
		$event_date		= $this->input->post('start', true);
		$end			= $this->input->post('end', true) ? $this->input->post('end') : $event_date;
		$username		= $this->active_user;
		
		$batch			= array();
		if($uid == "")
		{
			for($i=0; $i<totalDays($event_date, $end) + 1; $i++)
			{
				$date 	= date('Y-m-d', strtotime($i.' days', strtotime($event_date)));
				$batch[]= array('title'=>$title,
								'description'=>$description,
								'event_date'=>$date,
								'username'=>$username,
								'id'=>random_string('sha1'),
								'create_date'=>date('Y-m-d H:i:s'));
			}
			$data 		= $batch;
		}else{
			$data	= array('title'=>$title,
							'description'=>$description,
							'event_date'=>$event_date,
							'username'=>$username);
		}
		
		//
		$query		= $this->mnotif->save_data($data, $uid);
		if($query){
			$result	= array('status'=>true);
		}else{
			$result	= array('status'=>false, 'message'=>'Terjadi kesalahan pada server silahkan ulangi beberapa saat lagi.');
		}
		echo json_encode($result);
	}
	
	public function delete()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$uid		= $this->input->post('uid', true);
		
		$query		= $this->mnotif->delete_data($uid);
		if($query > 0){
			$result	= array('status'=>true,'message'=>'Berhasil.');
		}else{
			$result	= array('status'=>false,'message'=>'Terjadi kessalahan pada server silahkan ulangi beberapa saat lagi.');
		}
		echo json_encode($result);
	}
	
	// AJAX 
	public function getCategories()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$value	= array();
		$select	= 'id, code, concat(name," - (",ticket_type,")") as name, param, email';
		$query	= $this->mglobals->getTicketCategories($select);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		
		echo  json_encode($value);
	}
	
	public function getMacroDetail()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$value		= array();
		$uid		= $this->input->post('uid', true);
		$query 		= $this->mnotif->getDetail($uid);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= $row;
		}
		
		echo json_encode($value);
	}
	
	
	
	public function macroData()
	{
		
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$search		= $this->input->post('search', true);
		$value		= array();
		$query		= $this->mnotif->macroData();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		
		echo json_encode($value);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */