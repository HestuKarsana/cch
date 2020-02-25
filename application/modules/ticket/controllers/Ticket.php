<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Contacts
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Ticket extends CI_Controller {
	
	private $pos_office;
	private $active_user;
	private $active_regional;
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mticket');
		$this->pos_office = $this->session->userdata('pos_office');
		$this->active_user = $this->session->userdata('ses_username');
		
	}
	
	public function index()
	{
		$data['content']	= $this->load->view('index','',true);
		$this->load->view('page',$data);
	}
	
	public function datalist()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$date_start		= $this->input->post('start', true) ? SetDateFormatFromID($this->input->post('start', true),'Y-m-d') : date('Y-m-d');
		$date_end		= $this->input->post('end', true) ? SetDateFormatFromID($this->input->post('end', true),'Y-m-d') : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		
		$view			= strtolower(str_replace('#/','',$this->input->post('view',true)));
		
		switch ($view) {
			case "your_unsolved_tickets":
				$where			= ' a.status not in (99) and a.tujuan_pengaduan like "%'.$this->pos_office.'%" ';
				break;
			case "outgoing_ticket":
				$where			= ' a.status not in (99) and a.complaint_origin = "'.$this->pos_office.'" ';
				break;
			case "unassigned_tickets":
				$where			= 'a.tujuan_pengaduan = ""';
				break;
			case "all_unsolved_tickets":
				$where			= 'a.status in (1,2)';
				break;
			case "recently_updated_tickets":
				$where			= 'DATE(a.last_update) = CURDATE() and ( a.complaint_origin = "'.$this->pos_office.'" or a.tujuan_pengaduan like "%'.$this->pos_office.'%")';
				break;
			case "on_progress_tickets":
				$where			= 'a.status in(7,8,9,10,11,12,13,14,15,16,17,18,19,20)';
				break;
			case "recently_solved_tickets":
				$where			= 'DATE(a.last_update) = CURDATE() and a.status = 4';
				break;
			case "suspended_tickets":
				$where			= 'a.status = 5';
				break;
			case "deleted_tickets":
				$where			= 'a.status = 6';
				break;
			case "close_tickets":
				$where			= 'a.status = 99';
				break;
			case "request_close":
				$where			= 'a.request_close = 1';
				break;
			case "all_incoming":
				$where			= 'a.tujuan_pengaduan like "%'.$this->pos_office.'%"';
				break;
			case "all_outgoing":
				$where			= 'a.complaint_origin = "'.$this->pos_office.'"';
				break;
			default:

				$where			= 'a.status not in (99) and a.tujuan_pengaduan like "%'.$this->pos_office.'%"';
				break;
		}
		
		
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mticket->getTicketDataTotal($search, $where);
		
		$query 			= $this->mticket->getTicketData($search, $where, $row_show, $row_start, $sort, $order);
		
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
	
	public function dtable_list()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$search				= $this->input->post('search');
		
		$value['data']		= array();
		$query				= $this->mticket->datalist($search['value']);
		if($query->num_rows() > 0)
		{
			$value['recordsTotal']	= $query->num_rows();
			foreach($query->result() as $row)
			{
				$row	= array($row->phone_number, $row->subject, $row->category_name, $row->complaint, $row->status, $row->files, $row->date, $row->id);
				array_push($value['data'], $row);
			}
		}
		
		echo json_encode($value);
	}
	
	public function create()
	{
		$data['content']	= $this->load->view('form_create_ticket','',true);
		$this->load->view('page',$data);
	}
	
	public function checkContact()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$phone		= $this->input->post('phone', true);
		$contact	= $this->mticket->checkContact($phone);
		if($contact['status'] > 0){
			$result	= array('status'=>'OK', 'form'=>'old', 'msg'=>'Existing Contact', 'cid'=>$contact['cid']);
		}else{
			$result	= array('status'=>'OK', 'form'=>'new', 'msg'=>'New Contact');
		}
		echo json_encode($result);
		
	}
	
	public function form()
	{
		$data['content']	= $this->load->view('form2','',true);
		$this->load->view('page',$data);
	}
	
	public function edit(){
		$this->create();
	}
	
	public function detail(){
		$data['content']	= $this->load->view('form_response','',true);
		$this->load->view('page',$data);
	}
	
	public function form_response()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$id			= $this->input->post('cid');
		
		$value['data']		= array();
		$query		= $this->mglobals->ticketDetail($id);
		if($query->num_rows() > 0)
		{
			$row				= $query->row();
			
			$destination 		= explode(',', $row->assignee);

			$fullname 			= $this->mglobals->get_kantor_name_code($destination);

			if($this->pos_office == $row->complaint_origin){
				$type 			= 'origin';
			}else if(in_array($this->pos_office, $destination)){
				$type 			= 'destination';
			}else{
				$type 			= 'guest';
			}
			$row->user_type 	= $type;
			
			//$assignee = $this->mglobals->get_kantor_name_code($row->assignee);
			$row->assignee_val	= ($fullname != "") ? $fullname : "-";
			$row->assignee_text = str_replace('|',' - ',$row->assignee_val);
			$row->assignee_opt	= $row->assignee_val.'_'.str_replace('|',' - ',$row->assignee_val);
			$row->date_ago 		= time_elapsed_string($row->date);
			$row->new_date 		= date("d/m/y", strtotime($row->date));
			//$row->body 	   	= nl2br($row->complaint);
			$row->avatar		= generateInitialName($row->user_cch);
			
			if($row->channel == 1){
				$info_channel 	= $row->channel_name.' - '.$row->phone;
			}else if($row->channel == 2){
				$info_channel 	= $row->channel_name.' - '.$row->instagram;
			}else if($row->channel == 3){
				$info_channel 	= $row->channel_name.' - '.$row->twitter;
			}else if($row->channel == 4){
				$info_channel 	= $row->channel_name.' - '.$row->facebook;
			}else if($row->channel == 5){
				$info_channel 	= $row->channel_name.' - '.$row->email;
			}else{
				$info_channel 	= $row->channel_name.' - '.$row->phone;
			}
			$row->info_channel 	= $info_channel;
			$value['data']		= $row;
		}
		
		$value['ticket_status'] 	= array();
		$query 	= $this->mticket->get_ticket_status($value['data']->user_type);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value['ticket_status'], $row);
			}
		}
		
		echo json_encode($value);
	}
	
	public function ticketResponse()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$tid		= $this->input->post('tid', true);
		$val		= array();
		$val['media']		= array();
		$val['ticket']		= array();
		$query		= $this->mticket->getResponse($tid);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $mrow)
			{
				/*
				$val['ticket'][]		= array('id'=>$row->id,
												'response'=>nl2br($row->response),
												'username'=>$row->username,
												'realname'=>$row->title,
												'avatar'=>generateInitialName($row->title),
												'date' => date("d/m/y", strtotime($row->date)),
												'date_ago' => time_elapsed_string($row->date),
												'status'=>$row->ticket_status,
												'status_name'=>$row->status_name,
												'color'=>'bg-'.$row->param.'-trans',
												'total_file'=>$row->total_file);
				*/

				$tmedia 		= array();
				$xquery 		= $this->mglobals->get_ticket_media($tid, $mrow->id);
				if($xquery->num_rows() > 0)
				{
					foreach($xquery->result() as $row)
					{
						if($row->file_ext == 'pdf'){
							$icon 	= 'fa-file-pdf-o';
						}else if( ($row->file_ext == 'xls') || ($row->file_ext == 'xlsx')){
							$icon 	= 'fa-file-excel-o';
						}else if( ($row->file_ext == 'doc') || ($row->file_ext == 'docx')){
							$icon 	= 'fa-file-word-o';
						}else if( ($row->file_ext == 'jpg') || ($row->file_ext == 'png') || ($row->file_ext == 'jpeg') || ($row->file_ext == 'gif')){
							$icon 	= 'fa-file-image';
						}else {
							$icon 	= 'fa-file-o';
						}
						#$row->file_name = ellipsize($row->file_name, 32, .5);
						$row->icon 	= $icon;

						array_push($tmedia, $row);
					}
				}

				$dticket 	= array('id'=>$mrow->id,
									'response'=>nl2br($mrow->response),
									'username'=>$mrow->username,
									'realname'=>$mrow->title,
									'avatar'=>generateInitialName($mrow->title),
									'date' => date("d/m/y", strtotime($mrow->date)),
									'date_ago' => time_elapsed_string($mrow->date),
									'status'=>$mrow->ticket_status,
									'status_name'=>$mrow->status_name,
									'color'=>'bg-'.$mrow->param.'-trans',
									'total_file'=>$mrow->total_file,
									'media'=>$tmedia);
				
				array_push($val['ticket'], $dticket);
			}
		}

		
		echo json_encode($val,JSON_UNESCAPED_SLASHES);
	}
	
	
	
	public function delete()
	{
		$id 		= $this->input->post('id', false);
		
		$query		= $this->mticket->deleteData($id);
		$result	= array();
		$this->db->trans_begin();
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Remove data failed');
		}else{
			$this->db->trans_commit();
			$result	= array('status'=>'OK', 'msg'=>'Remove data success');
		}
		echo json_encode($result);
	}
	
	public function getContactData()
	{
		$key		= $this->input->post('q');
		$type_cust	= $this->input->post('type_customer');
		$value		= array();
		$query		= $this->mticket->getContactData($key, $type_cust);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= array('id'=>$row->id, 'name'=>$row->name);
			}
		}
		echo json_encode($value);
	}
	
	public function getAgentData()
	{
		$key		= $this->input->post('q');
		$value		= array();
		$query		= $this->mticket->getAgentData($key);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= array('id'=>$row->id, 'name'=>$row->name);
			}
		}
		
		echo json_encode($value);
	}
	/*
	public function saveOLD()
	{
		$this->load->helper('string');
		
		$cid					= $this->input->post('ticket_id',true);
		
		if($this->input->post('requester', true)){
			$data['contact_id']		= $this->input->post('requester', true);
		}
		
		if($this->input->post('assignee', true)){
			$data['assignee']	= $this->input->post('assignee', true);
		}
		
		if($this->input->post('ccs', true)){
			$data['ccs']	= $this->input->post('ccs', true);
		}
		
		if($this->input->post('tags', true)){
			$data['tags']	= $this->input->post('tags', true);
		}
		
		if($this->input->post('category', true)){
			$data['category']	= $this->input->post('category', true);
		}
		if($this->input->post('priority', true)){
			$data['priority']		= $this->input->post('priority', true);
		}
		
		if($this->input->post('subject', true)){
			$data['subject']	= $this->input->post('subject', true);
		}
		
		if($this->input->post('complaint', true)){
			$data['complaint']	= $this->input->post('complaint', true);
		}
		
		if($this->input->post('status', true)){
			$data['status']		= $this->input->post('status', true);
			
		}
		
		if($this->input->post('contact_reason', true)){
			$data['contact_reason']		= $this->input->post('contact_reason', true);
		}
		
		// response 
		if($this->input->post('response', true)){
			$response['response']		= $this->input->post('response', true);
			$response['ticket_status']	= $this->input->post('status', true);
			$response['username']		= $this->session->userdata('ses_username');
			$response['ticket_id']		= $cid;
			$response['type_response']	= $this->input->post('type_response',true)? $this->input->post('type_response',true) :1; 
		}
		
		
		$this->db->trans_begin();
		
		$result	= array();
		$this->mticket->saveData($data, $cid);
		
		$ticket_last_id	= $this->db->insert_id();
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Saving data failed');
		}else{
			$this->db->trans_commit();
			
			if($cid == ''){
				$row 	= array();
				$query	= $this->mticket->getTicketLastID($ticket_last_id);
				#echo $this->db->last_query();
				if($query->num_rows() > 0)
				{
					$row	= $query->row();
					$this->sendEmailNotification($row);
				}
				$result	= array('status'=>'OK', 'msg'=>'Saving data success', 'ticket_info'=>$row->code.'-'.$row->no_ticket);
			}else{
				$this->mticket->saveResponse($response);
				$result	= array('status'=>'OK', 'msg'=>'Saving data success');
			}
		}
		echo json_encode($result);
		
	}
	*/
	
	
	
	public function saveResponse()
	{
		$this->load->helper('string');
		
		$response				= $this->input->post('response', true);
		$tid					= $this->input->post('uid', true);	
		$username				= $this->session->userdata('ses_username');
		$category_final			= $this->input->post('category_final');
		$category_detail 		= $this->input->post('category_detail');
		$status					= $this->input->post('status', true);
		
		$data	= array('response'=>trim($response), 'ticket_id'=>$tid, 'username'=>$username, 'ticket_status'=>$status);

		$tdata 	= array('category'=>$category_final, 'category_detail'=>$category_detail);
		$this->mticket->update_ticket($tdata, $tid);
		$this->mticket->saveResponse($data); 
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Saving data failed');
		}else{
			$this->db->trans_commit();
			$result	= array('status'=>'OK', 'msg'=>'Saving data success');
		}
		
		echo json_encode($result);
	}
	
	// show
	public function showTickets()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : 10;
		$row_start	 	= $this->input->post('page') ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		
		$search			= $this->input->post('search', true);
		$btnSpam		= true;
		$btnInbox		= false;
		$btnDelete		= true;
		$view			= strtolower(str_replace('/','',$this->input->post('view',true)));
		switch ($view) {
			
			case "your_unsolved_tickets":
				$where			= 'a.status not in (99) and a.tujuan_pengaduan like "%'.$this->pos_office.'%"';
				#$where			= 'a.status in (1,2) and a.assignee where "'.$this->session->ses_username.'"';
				break;
			case "outgoing_ticket":
				$where			= 'a.complaint_origin = "'.$this->pos_office.'" and a.status not in (99) ';
				break;
			case "unassigned_tickets":
				$where			= 'a.tujuan_pengaduan = ""';
				break;
			case "all_unsolved_tickets":
				$where			= 'a.status in (1,2)';
				break;
			case "recently_updated_tickets":
			$where			= 'DATE(a.last_update) = CURDATE() and ( a.complaint_origin = "'.$this->pos_office.'" or a.tujuan_pengaduan like "%'.$this->pos_office.'%")';
				break;
			case "on_progress_tickets":
				$where			= 'a.status = 3';
				break;
			case "recently_solved_tickets":
				$where			= 'DATE(a.last_update) = CURDATE() and a.status = 4';
				break;
			
			case "suspended_tickets":
				$where			= 'a.status = 5';
				$btnSpam		= false;
				$btnInbox		= true;
				$btnDelete		= true;
				break;
			case "deleted_tickets":
				$where			= 'a.status = 6';
				$btnSpam		= true;
				$btnInbox		= true;
				$btnDelete		= false;
				break;
			case "close_tickets":
				$where			= 'a.status = 7';
				$btnSpam		= false;
				$btnInbox		= false;
				$btnDelete		= false;
				break;
				
			default:
				$where			= 'a.status not in (99) and a.tujuan_pengaduan = "'.$this->pos_office.'"';
				
				break;
		}
		
		
		
		$value			= array();
		$total			= $this->mticket->TotalshowTickets($where);
		$query			= $this->mticket->showTickets($row_show, $row_start, $where);
		
		
		
		$end			= (($row_start + $row_show) < $total) ? ($row_start + $row_show) : $total;
		$start			= ($total > 0) ? $row_start + 1 : 0;
		
		$value['info']	= array('row_show'=>$row_show, 'row_start'=>$start, 'row_end'=>$end,'total_rows'=>$total, 'last_page'=>ceil($total/$row_show), 'btn_spam'=>$btnSpam, 'btnInbox'=>$btnInbox, 'btnDelete'=>$btnDelete);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value['data'][]	= array('id'=>$row->id,
									'no_ticket'=>$row->no_ticket,
									'selected'=>false,
									'subject'=>$row->subject,
									'priority'=>getPriorityText($row->priority),
									'category_name'=>$row->category_name,
									'status'=>$row->status,
									'status_name'=>substr($row->status_name,0,1),
									'param'=>$row->param,
									'contact_id'=>$row->cid,
									'contact_name'=>$row->contact_name,
									'date_ago'=>time_elapsed_string($row->date)
									);
			}
		}
		
		echo json_encode((object) $value);
		
	}
	
	public function showTicketsCounter()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$value	= array();
		
		$query	= $this->mticket->showTicketsCounter($this->pos_office);
		$value	= $query->row();
		
		echo json_encode($value);
	}
	
	public function moveToSpam()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$ticket_id 		= $this->input->post('ticket_id', true);
		$total			= count($ticket_id);
		$this->db->trans_begin();
		
		
		
		$this->mticket->updateTicketStatus($ticket_id, 5);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Process Failed');
		}else{
			$this->db->trans_commit();
			$result	= array('status'=>'OK', 'msg'=>$total.' tickets move to spam');
			
		}
		
		echo json_encode($result);
	}
	
	public function moveToTrash()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$ticket_id 		= $this->input->post('ticket_id', true);
		$total			= count($ticket_id);
		$this->db->trans_begin();
		
		$this->mticket->updateTicketStatus($ticket_id, 6);
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Process Failed');
		}else{
			$this->db->trans_commit();
			$result	= array('status'=>'OK', 'msg'=>$total.' tickets move to trash');
			
		}
		
		echo json_encode($result);
	}
	
	public function moveToInbox()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$ticket_id 		= $this->input->post('ticket_id', true);
		$total			= count($ticket_id);
		$this->db->trans_begin();
		
		$this->mticket->updateTicketStatus($ticket_id, 2);
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Process Failed');
		}else{
			$this->db->trans_commit();
			$result	= array('status'=>'OK', 'msg'=>$total.' tickets move to trash');
			
		}
		
		echo json_encode($result);
	}
	
	public function category(){
		$content['page_title']	= 'Ticket Category '.$this->uri->segment(3);
		$data['content']	= $this->load->view('category',$content,true);
		$this->load->view('page',$data);
	}
	
	public function ticketListCategory()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$date_start		= $this->input->post('start', true) ? SetDateFormatFromID($this->input->post('start', true),'Y-m-d') : date('Y-m-d');
		$date_end		= $this->input->post('end', true) ? SetDateFormatFromID($this->input->post('end', true),'Y-m-d') : date('Y-m-d');
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		
		
		$where 			= '';
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mticket->getTotalTicketCategory($search, $where);
		$query 			= $this->mticket->getTicketCategory($search, $where, $row_show, $row_start, $sort, $order);
		
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
	
	public function takeit()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		if($this->session->userdata('ses_username') != '')
		{
			$user 	= $this->mglobals->getUserInformation($this->session->userdata('ses_username'));
			$data	= array('id'=>$user->username, 'text'=>$user->title.' ('.$user->username.')');
			
			echo json_encode($data);
		}
	}
	
	public function getTicketStatus()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$ticket_type	= $this->input->post('type');
		$value	= array();
		$query	= $this->mticket->getTicketStatus($ticket_type);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		
		echo json_encode($value);
	}
	
	public function uploader()
	{
		$this->load->library('upload');
		$this->load->model('muploader');
		$pathinfo		= $this->config->item('path_media');
		$config['upload_path'] 		= $pathinfo;
		$config['allowed_types'] 	= 'gif|jpg|png';
		$config['encrypt_name'] 	= true;
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('file'))
		{
				$error = $this->upload->display_errors();
				$result	= array('status'=>'Error', 'msg'=>$error);
	
		}else{
				$upload	= $this->upload->data();
				$media	= array('orig_name'=>$upload['orig_name'],
								'file_name'=>$upload['file_name'],
								'file_type'=>$upload['file_type'],
								'file_path'=>$upload['file_path'],
								'file_size'=>$upload['file_size'],
								'file_ext'=>strtolower($upload['file_ext']),
								'module'=>$this->router->fetch_class(),
								'is_image'=>$upload['is_image']);
				$this->muploader->saveMedia($media);
				$result	= array('status'=>'OK', 'url'=>base_url($pathinfo).$upload['file_name']);
		}
		
		echo json_encode($result);
	}
	
	
	/* contacts reason */
	public function getContactReason()
	{
		$cid		= $this->input->post('cid', true);
		
		$value		= array();
		$query		= $this->mticket->getContactReasonCategory($cid);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		echo json_encode($value);
	}
	
	public function saveResponses()
	{
	
		$cid						= $this->input->post('cid',true);
		$tiket_id					= $this->input->post('ticket_id', true);
		$response					= $this->input->post('response');
		$request 					= $this->input->post('request');
		$status						= $this->input->post('status', true);
		$category					= $this->input->post('category');
		
		$assignee					= $this->input->post('assignee');
		$priority 					= $this->input->post('priority');
		$tags 						= $this->input->post('tags');
		
		$this->db->trans_begin();
		
		//$status 	= 
		
		
		$ticket_data['request_close']	= 0;
		$response	= array('ticket_id'=>$tiket_id,
							'response'=>$response,
							'username'=>$this->active_user,
							'ticket_status'=>$status,
							'type_response'=>$category,
							'update_office'=>$this->pos_office);
		if($request == 0){
			$response['ticket_status']		= 12;
		}else if($request == 1){
			$response['ticket_status']		= 12;	
			$ticket_data['request_close']	= 1;
		}else if($request == 2){
			$response['ticket_status']		= 17;	
			$ticket_data['request_close']	= 0;
		}else if($request == 3){
			$response['ticket_status']		= 99;	
			$ticket_data['request_close']	= 0;
		}			
		
		$this->mticket->update_ticket($ticket_data, $tiket_id);
		
		$this->mticket->saveResponse($response);
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>false, 'message'=>'Proses simpan gagal');
		}else{
			$this->db->trans_commit();
			//$query	= $this->mticket->getDetailTicket($tiket_id);
			//if($query->num_rows() > 0)
			//{
			//	$qrow	= $query->row();
				//$this->sendEmailResponse($qrow, $response);
			//}
			$result	= array('status'=>true, 'message'=>'Proses simpan berhasil');
		}
		echo json_encode($result);
		//$this->mticket->saveContacts($contact, $cid);
	}
	
	public function save()
	{
		// contact 
		$this->load->helper('string');
		
		//contact id
		$cid					= $this->input->post('cid',true);
		
		$contact_id					= $this->input->post('contact_id',true);
		
		if($this->input->post('requester', true)){
			$contact['name']	= $this->input->post('requester', true);
		}
		
		if($this->input->post('phone_number', true)){
			$contact['phone_number']	= $this->input->post('phone_number', true);
		}
		if($this->input->post('email', true)){
			$contact['email']	= $this->input->post('email', true);
		}

		if($this->input->post('address', true)){
			$contact['address']	= $this->input->post('address', true);
		}
		if($this->input->post('delivery_address', true)){
			$contact['delivery_address']	= $this->input->post('delivery_address', true);
		}
		if($this->input->post('propinsi', true)){
			$contact['propinsi']	= $this->input->post('propinsi', true);
		}
		if($this->input->post('kota', true)){
			$contact['kota']	= $this->input->post('kota', true);
		}
		if($this->input->post('kecamatan', true)){
			$contact['kecamatan']	= $this->input->post('kecamatan', true);
		}
		
		if($this->input->post('kelurahan', true)){
			$contact['kelurahan']	= $this->input->post('kelurahan', true);
		}
		
		if($this->input->post('patokan', true)){
			$contact['patokan']	= $this->input->post('patokan', true);
		}
		
		// ticket
		/*
		if($this->input->post('contact_id', true)){
			$ticket['contact_id']	= $this->input->post('contact_id', true);
		}
		*/
		$this->db->trans_begin();
		
		if($contact_id == '')
		{
			$this->mticket->updateContact($contact, $contact_id);
			$last_contact_id		= $this->db->insert_id();
			#$this->mticket->getContactByAutoID($last_contact_id);
			$ticket['contact_id']	= $this->mticket->getContactByAutoID($last_contact_id);
		}else{
			$ticket['contact_id']	= $this->input->post('contact_id', true);
		}
		if($this->input->post('assignee', true)){
			$ticket['assignee']	= $this->input->post('assignee', true);
		}
		if($this->input->post('subject', true)){
			$ticket['subject']	= $this->input->post('subject', true);
		}
		if($this->input->post('complaint', true)){
			$ticket['complaint']	= $this->input->post('complaint', true);
		}
		if($this->input->post('category', true)){
			$ticket['category']	= $this->input->post('category', true);
		}
		if($this->input->post('priority', true)){
			$ticket['priority']	= $this->input->post('priority', true);
		}
		if($this->input->post('contact_reason', true)){
			$ticket['contact_reason']	= $this->input->post('contact_reason', true);
		}
		if($this->input->post('tags', true)){
			$ticket['tags']	= $this->input->post('tags', true);
		}
		if($this->input->post('nama_produk', true)){
			$ext['product_name']	= $this->input->post('nama_produk', true);
		}
		if($this->input->post('kode_produksi', true)){
			$ext['production_code']	= $this->input->post('kode_produksi', true);
		}
		if($this->input->post('expired_date', true)){
			$ext['expired_date']	= $this->input->post('expired_date', true);
		}
		if($this->input->post('sample_product', true)){
			$ext['sample']	= $this->input->post('sample_product', true);
		}
		if($this->input->post('netto', true)){
			$ext['netto']	= $this->input->post('netto', true);
		}
		if($this->input->post('jumlah_sample', true)){
			$ext['sample_amount']	= $this->input->post('jumlah_sample', true);
		}
		if($this->input->post('detail_complaint', true)){
			$ext['detail_complaint']	= $this->input->post('detail_complaint', true);
		}
		if($this->input->post('no_po', true)){
			$ext['no_po']	= $this->input->post('no_po', true);
		}
		
		
		#print_r($ticket);
		
		
		
		// update contact
		
		
		$result	= array();
		$this->mticket->saveData($ticket);
		$ticket_last_id	= $this->db->insert_id();
		
		// save new ticket
		if(isset($ext)){
			$this->mticket->saveTicketNew($ext, $ticket_last_id);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Saving data failed');
		}else{
			$this->db->trans_commit();
			
			if($cid == ''){
				$row 	= array();
				$query	= $this->mticket->getTicketLastID($ticket_last_id);
				if($query->num_rows() > 0)
				{
					$row	= $query->row();
					if($row->contact_reason != 'info')
					{
						$tmp_subject		= $row->category_name.' '.$row->product_name.$row->no_po;
						$tmp_complaint		= $this->load->view('mail/'.$row->contact_reason, $row, true);
						$this->mticket->updateTicketInfo($tmp_subject, $tmp_complaint, $ticket_last_id);
					}
					
					//if($row->contact_reason
					#$this->sendEmailNotification($row);
				}
				$result	= array('status'=>'OK', 'msg'=>'Saving data success', 'ticket_info'=>$row->code.'-'.$row->no_ticket);
			}else{
				$this->mticket->saveResponse($response);
				$result	= array('status'=>'OK', 'msg'=>'Saving data success');
			}
		}
		echo json_encode($result);
		//$this->mticket->saveContacts($contact, $cid);
	}
	
	public function sendEmailNotification($data = '')
	{
		$subject	= "[TICKET ID #".$data->no_ticket."]".$data->subject;
		$priority	= $data->priority;
		$email_to	= $data->email_assignee;
		
		
		
		$this->load->library('email');
		
		$config['protocol'] 	= $this->config->item('protocol');
		$config['charset'] 		= $this->config->item('protocol');
		$config['smtp_host'] 	= $this->config->item('smtp_host');
		$config['smtp_user'] 	= $this->config->item('smtp_user');
		$config['smtp_pass'] 	= $this->config->item('smtp_pass');
		$config['smtp_port'] 	= $this->config->item('smtp_port');
		$config['mailtype'] 	= $this->config->item('mailtype');
		$config['priority'] 	= $priority;
		$config['wordwrap'] 	= TRUE;
		$this->email->initialize($config);
		
		if($data->contact_reason == 'info')
		{
			//$tmp				= $this->load->view('mail/'.$data->contact_reason, $data, true);
			$tmp				= $data->subject."<br>".$data->complaint;
			$tmp_customer		= $this->load->view('mail/customer_'.$data->contact_reason, $data, true);
		}else{
			$tmp				= $this->load->view('mail/'.$data->contact_reason, $data, true);
			$tmp_customer		= $this->load->view('mail/customer_complaint', $data, true);
		}
		
		if(valid_email($data->email_assignee))
		{
			$this->email->clear();
			$this->email->from($this->config->item('smtp_user'), $this->config->item('system_name'));
			$this->email->to($email_to);
			$this->email->subject($subject);
			$this->email->message($tmp);

			$this->email->send();
		}
		
		if(valid_email($data->contact_email))
		{
			$this->email->clear();
			$this->email->from($this->config->item('smtp_user'), $this->config->item('system_name'));
			$this->email->to($data->email_user);
			$this->email->subject($subject);
			$this->email->message($tmp_customer);
			$this->email->send();
		}
		
		#$this->email->print_debugger(array('headers'));
		
		
	}
	
	public function sendEmailResponse($data = '', $response)
	{
		$subject	= "UPDATE:[TICKET ID #".$data->no_ticket."]".$data->subject;
		$priority	= $data->priority;
		$email_to	= $data->assignee_email;
		
		
		
		$this->load->library('email');
		
		$config['protocol'] 	= $this->config->item('protocol');
		$config['charset'] 		= $this->config->item('protocol');
		$config['smtp_host'] 	= $this->config->item('smtp_host');
		$config['smtp_user'] 	= $this->config->item('smtp_user');
		$config['smtp_pass'] 	= $this->config->item('smtp_pass');
		$config['smtp_port'] 	= $this->config->item('smtp_port');
		$config['mailtype'] 	= $this->config->item('mailtype');
		$config['priority'] 	= $priority;
		$config['wordwrap'] 	= TRUE;
		$this->email->initialize($config);
		
		
		$this->email->from($this->config->item('smtp_user'), $this->config->item('system_name'));
		$this->email->to($email_to);
		$this->email->subject($subject);
		$this->email->message($response);
		$this->email->send(false);
		$this->email->clear();
		$this->email->print_debugger(array('headers'));
		
	}
	
	public function generateTmp()
	{
		$tpl		= $this->input->post('tpl');
		echo $this->load->view('ticket/response_'.$tpl,'',true);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */