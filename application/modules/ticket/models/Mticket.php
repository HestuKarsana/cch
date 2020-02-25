<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  
 *
 * @package		Authentication Models
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Mticket extends CI_Model{
	
	private $contacts	= 'ccare';
	private $reason		= 'reason';
	private $user		= 'sys_user';
	private $role		= 'sys_role';
	private $ticket		= 'ticket';
	private $categories = 'category';
	private $ticket_new = 'ticket_new';
	private $status 	= 'ticket_status';
	private $ticket_response 	= 'ticket_response';
	
	public function getTicketData($search = '', $where, $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.no_ticket, a.subject, a.priority, a.complaint, a.category, a.date, a.status, a.files, a.phone_number, b.name as category_name, c.name_requester as contact_name, c.id as cid, d.name as status_name, d.param';
		$this->db->select($select, false);		
		#( ($search != '') ? $this->db->like('a.subject', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.no_ticket', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('c.name_requester', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		#$this->db->where('date(a.date) between "'.$date[0].'" and "'.$date[1].'"');
		$this->db->where($where);
		$this->db->join($this->categories.' b','a.category = b.auto_id','LEFT');
		$this->db->join($this->contacts.' c','a.contact_id = c.id','LEFT');
		$this->db->join($this->status.' d','a.status = d.id');
		(($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.no_ticket', 'desc'));
		return $this->db->get($this->ticket.' a');
	}
	
	public function getTicketDataTotal($search = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->like('a.subject', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.no_ticket', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('c.name_requester', $search, 'both') : '');
		#$this->db->where('date(a.date) between "'.$date[0].'" and "'.$date[1].'"');
		$this->db->where($date);
		$this->db->join($this->categories.' b','a.category = b.auto_id','LEFT');
		$this->db->join($this->contacts.' c','a.contact_id = c.id');
		$this->db->join($this->status.' d','a.status = d.id');
		$query	= $this->db->get($this->ticket.' a');
		$row 	= $query->row();
		return $row->total;
	}
	
	public function get_ticket_status($status = '')
	{
		$this->db->select('id, name as text, param, status', false);
		$this->db->where('id >', 0);
		$this->db->where('status', 1);
		if($status == 'destination'){
			$this->db->where('destination', 1);
		}else if($status == 'origin'){
			$this->db->where('origin', 1);
		}else{
			$this->db->where('response', 1);
		}
		
		//$this->db->where('new_ticket', 1);
		return $this->db->get($this->status);
	}
	
	public function checkContact($phone)
	{
		$value				= array();
		$value['status']	= 0;
		$this->db->select('id', false);
		$this->db->where('phone_number', $phone);
		$this->db->group_by('id');
		$query		= $this->db->get($this->contacts);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value['status'] = $query->num_rows();
			$value['cid']	 = $row->id;
		}
		return $value;
	}
	
	public function updateContact($data, $cid)
	{
		if($cid != '')
		{
			$this->db->where('id', $cid);
			return $this->db->update($this->contacts, $data);
		}else{
			$this->db->set('id',random_string('sha1'));
		return $this->db->insert($this->contacts, $data);
		}
		
	}
	
	public function getTicketLastID($id)
	{
		$select 	= 'a.*, b.name as category_name, b.code, b.email, c.email AS email_user, d.email AS email_assignee, c.name AS contact_name, c.address AS contact_address, c.phone_number AS contact_phone, c.email AS contact_email, e.product_name, e.expired_date, e.netto, e.sample, e.sample_amount, e.production_code, e.detail_complaint, e.no_po, e.detail_complaint, c.name as customer_name, c.delivery_address';
		$this->db->select($select, false);
		$this->db->where('a.last_id', $id);
		$this->db->join($this->categories.' b','a.category = b.id');
		$this->db->join($this->contacts.' c','a.contact_id = c.id');
		$this->db->join($this->user.' d','a.assignee = d.username','LEFT');
		$this->db->join($this->ticket_new.' e','a.last_id = e.ticket_id','LEFT');
		return $this->db->get($this->ticket.' a');
	}
	
	public function updateTicketInfo($subject, $detail, $id)
	{
		$this->db->set('subject', $subject);
		$this->db->set('complaint', $detail);
		$this->db->where('last_id', $id);
		return $this->db->update($this->ticket);
	}
	
	public function datalist($search)
	{
		$select	= 'a.id, a.subject, a.complaint, a.category, a.date, a.status, a.files, a.phone_number, b.name as category_name, c.name as contact_name, c.id as cid, d.name as status_name, d.param';
		$this->db->select($select, false);		
		(($search != '') ? $this->db->like('a.subject', $search, 'both') : '');
		$this->db->join($this->categories.' b','a.category = b.id');
		$this->db->join($this->contacts.' c','a.phone_number = c.phone_number');
		$this->db->join($this->status.' d','a.status = d.id');
		return $this->db->get($this->ticket.' a');
	}
	
	public function saveData($data, $key = '')
	{
		if($key != ''){
			$this->db->where('id', $key);
			return $this->db->update($this->ticket, $data);
		}else{
			$no_ticket	= $this->getNewNoTicket();
			$this->db->set('no_ticket', $no_ticket);
			$this->db->set('id', random_string('sha1'));
			#$this->db->set('status', 1);
			$this->db->set('date','NOW()',false);
			return $this->db->insert($this->ticket, $data);
		}
	}
	
	public function getContactByAutoID($autoid)
	{
		$this->db->select('id', false);
		$this->db->where('auto_id', $autoid);
		$query	= $this->db->get($this->contacts);
		$row	= $query->row();
		return $row->id;
	}
	
	public function saveTicketNew($data, $ticket_id)
	{
		$this->db->set('ticket_id', $ticket_id);
		return $this->db->insert($this->ticket_new, $data);
	}
	
	public function getNewNoTicket()
	{
		$this->db->select('max(no_ticket) as no_ticket', false);
		$this->db->limit(1);
		$query	= $this->db->get($this->ticket);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$return = $row->no_ticket + 1;
		}else{
			$return = 1;
		}
		return $return;
	}
	
	public function getDetailTicket($tid)
	{
		$select = 'a.id, a.no_ticket, a.date, a.subject, a.complaint, a.phone_number, a.status, a.priority, b.name as category_name, c.name as status_name, d.id as uid, d.name as contact_name, e.title as assignee_name, e.email as assignee_email';
		$this->db->select($select, false);
		$this->db->where('a.id', $tid);
		$this->db->join($this->categories.' b','a.category = b.auto_id','LEFT');
		$this->db->join($this->status.' c','a.status = c.id','LEFT');
		$this->db->join($this->contacts.' d','a.contact_id = d.id','LEFT');
		$this->db->join($this->user.' e','a.assignee = e.username','LEFT');
		return $this->db->get($this->ticket.' a');	
	}
	
	public function getTicketResponse($tid)
	{
		$select = 'a.id, a.ticket_id, a.response, a.username, a.date, b.title as staff_name';
		
		$this->db->select($select, false);
		$this->db->where('a.ticket_id', $tid);
		$this->db->order_by('a.date','desc');
		$this->db->join($this->user.' b','a.username = b.username');
		return $this->db->get($this->ticket_response.' a');	
	}
	
	public function saveResponse($data)
	{
		$response_id 	= random_string('sha1');

		$this->update_media_response_id($data['ticket_id'], $data['username'], $response_id);

		$this->db->set('id',$response_id);
		$this->db->set('date','NOW()', false);
		return $this->db->insert($this->ticket_response, $data);
	}

	private $media	= 'ticket_media';
	public function update_media_response_id($ticket_id, $user, $response_id)
	{
		$this->db->set('response_id', $response_id);
		$this->db->set('tmp_status', 0);
		$this->db->where('ticket_id', $ticket_id);
		$this->db->where('user_cch', $user);
		$this->db->update($this->media.' a');
		return $this->db->affected_rows();
	}
	
	public function update_ticket($data, $ticket_id)
	{
		$this->db->where('id', $ticket_id);
		$this->db->update($this->ticket, $data);
		return $this->db->affected_rows();
	}
	
	public function getContactData($key, $type_customer = '')
	{
		$select 	= 'concat(name," (",COALESCE(phone_number,"-"),")") as name, id as id';
		$this->db->select($select, false);
		$this->db->like('phone_number', $key, 'BOTH');
		$this->db->or_like('name', $key, 'BOTH');
		$this->db->where('status',1);
		(($type_customer != '') ? $this->db->where('type_customer',$type_customer) : '');
		return $this->db->get($this->contacts);
		
	}
	
	public function getAgentData($key)
	{
		$select 	= 'concat(a.title," (",a.username,")") as name, a.username as id';
		$this->db->select($select, false);
		$this->db->like('a.username', $key, 'BOTH');
		$this->db->or_like('b.name', $key, 'BOTH');
		$this->db->where('a.status',1);
		$this->db->join($this->role.' b','a.role_id = b.id');
		return $this->db->get($this->user.' a');
		
	}
	
	// show 
	public function showTickets($row_show, $row_start, $where = '', $sort = 'a.date', $order = 'desc')
	{
		$select	= 'a.id, a.no_ticket, a.subject, a.priority, a.complaint, a.category, a.date, a.status, a.files, a.phone_number, b.name as category_name, c.name_requester as contact_name, c.id as cid, d.name as status_name, d.param';
		$this->db->select($select, false);		
		#( ($search != '') ? $this->db->like('a.subject', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.no_ticket', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('c.name', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		(($where != '') ? $this->db->where($where) : '');
		$this->db->join($this->categories.' b','a.category = b.id','LEFT');
		$this->db->join($this->contacts.' c','a.contact_id = c.id','LEFT');
		$this->db->join($this->status.' d','a.status = d.id');
		(($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.no_ticket', 'desc'));
		return $this->db->get($this->ticket.' a');
	}
	
	public function TotalshowTickets($where = '')
	{
		$select	= 'count(a.id) as total';
		$this->db->select($select, false);		
		#( ($search != '') ? $this->db->like('a.subject', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.no_ticket', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('c.name', $search, 'both') : '');
		$this->db->join($this->categories.' b','a.category = b.id','LEFT');
		$this->db->join($this->contacts.' c','a.contact_id = c.id','LEFT');
		$this->db->join($this->status.' d','a.status = d.id');
		(($where != '') ? $this->db->where($where) : '');
		$query	= $this->db->get($this->ticket.' a');
		$row	= $query->row();
		return $row->total;
	}
	
	public function showTicketsCounter($username)
	{
		return $this->db->query('call sp_ticket_info("'.$username.'")');
	}
	
	// unsolved ticket
	public function getUnsolvedTicket($username = '')
	{
		$this->db->select('count(id) as total', false);
		$this->db->where_in('status', array(1,2));
		(($username != '') ? $this->db->where('tujuan_pengaduan', $username) : '');
		$query	= $this->db->get($this->ticket);
		$row	= $query->row();
		return $row->total;
	}
	
	public function getUnassigneedTicket()
	{
		$this->db->select('count(id) as total', false);
		$this->db->where('tujuan_pengaduan', '');
		$query	= $this->db->get($this->ticket);
		$row	= $query->row();
		return $row->total;
	}
	
	public function getStatusTicket($status)
	{
		$this->db->select('count(id) as total', false);
		$this->db->where('status', $status);
		$query	= $this->db->get($this->ticket);
		$row	= $query->row();
		return $row->total;
	}
	
	public function getResponse($ticket_id)
	{
		$select	= 'a.*, b.title, c.param, c.name as status_name, count(d.id) as total_file';
		$this->db->select($select, false);
		$this->db->join($this->user.' b','a.username = b.username');
		$this->db->join($this->status.' c','a.ticket_status = c.id','left');
		$this->db->join($this->media.' d','a.id = d.response_id','left');
		$this->db->where('a.ticket_id', $ticket_id);
		$this->db->group_by('a.id');
		$this->db->order_by('a.date','desc');
		
		return $this->db->get($this->ticket_response.' a');
	}
	
	public function updateTicketStatus($ticket_id, $status)
	{
		$this->db->set('status', $status);
		$this->db->where_in('id', $ticket_id);
		return $this->db->update($this->ticket);
	}
	
	public function getTicketCategory()
	{
		$select	= 'a.id, a.no_ticket, a.subject, a.priority, a.complaint, a.category, a.date, a.status, a.files, a.phone_number, b.name as category_name, c.name as contact_name, c.id as cid, d.name as status_name, d.param';
		$this->db->select($select, false);		
		
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		(($where != '') ? $this->db->where($where) : '');
		$this->db->join($this->categories.' b','a.category = b.id');
		$this->db->join($this->contacts.' c','a.contact_id = c.id');
		$this->db->join($this->status.' d','a.status = d.id');
		(($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.no_ticket', 'desc'));
		return $this->db->get($this->ticket.' a');
	}
	
	public function getTotalTicketCategory()
	{
		
	}
	
	public function getTicketStatus()
	{
		$this->db->select('id, name, param, status', false);
		$this->db->where('id >', 0);
		$this->db->where('status', 1);
		$this->db->where('response', 1);
		//$this->db->where('new_ticket', 1);
		return $this->db->get($this->status);
	}
	
	
	
	/* contact reason */
	public function getContactReasonCategory($category)
	{
		$this->db->select('id, name, form_name', false);
		$this->db->where('ticket_category', $category);
		return $this->db->get($this->reason);
	}
	
	public function saveContacts($data, $key = '')
	{
		if($key != '')
		{
			$this->db->where('id', $key);
			return $this->db->update($this->contact, $data);
		}else{
			$this->db->set('id', random_string('sha1'));
			return $this->db->insert($this->contact, $data);
		}
	}
}