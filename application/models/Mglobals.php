<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  
 *
 * @package		Globals Models
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Mglobals extends CI_Model{
	
	private $menu				= 'sys_menu';
	private $user				= 'sys_user';
	private $role				= 'sys_role';
	
	private $ticket_categories	= 'category';
	private $ticket_status		= 'ticket_status';
	private $ticket				= 'ticket';
	private $ticket_response	= 'ticket_response';
	
	private $contacts			= 'ccare';

	private $kantor_pos			= 'pos_office';
	private $country			= 'country';
	
	private $xray 				= 'xray';
	private $channel			= 'channel_info';
	private $media 				= 'ticket_media';

	private $notification 		= 'notification';
	private $holiday 			= 'holiday';
	private $produk				= 'pos_product';

	public function get_layanan_code($res)
	{
		$value 	= '';
		$this->db->select('a.code', false);
		$this->db->where('a.name', $res);
		$query 	= $this->db->get($this->produk.' a');
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= $row->code;
		}
		return $value;
	}

	public function get_events_calendar($start, $end)
	{
		$this->db->select('a.id, a.date_start as start, a.date_end as end, a.title, a.description', false);
		$this->db->where('a.date_start between "'.$start.'" and "'.$end.'"', null, false);
		return $this->db->get($this->holiday.' a');
	}

	public function get_notification()
	{
		$this->db->select('a.id, a.title, a.description, a.event_date', false);
		$this->db->where('a.event_date', 'curdate()', false);
		return $this->db->get($this->notification.' a');
	}

	public function check_ticket($no_resi)
	{
		$value 	= array();

		$value['history']	= '';
		$value['addon']		= '';
		$this->db->select('a.no_ticket, a.id, b.name as status_name, a.date, a.tujuan_pengaduan, a.complaint, a.notes, a.status');
		$this->db->order_by('a.date','desc');
		$this->db->limit(1);
		$this->db->where('a.awb', $no_resi);
		$this->db->join($this->ticket_status.' b','a.status = b.id');
		$query 	= $this->db->get($this->ticket.' a');
		#echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			
			$value['history'] 		= $this->last_ticket_response($row->id);
			$value['addon']			= $row->notes;
			$value['recreate']		= ($row->status == 99) ? true : false;
			$value['tid']			= $row->id;
			if($value['history'] == ''){
				$res 	 = "";
				$res 	.= "No Ticket : ".$row->no_ticket."\n";
				$res 	.= "Tgl Update : ".$row->date."\n";
				$res 	.= "Kantor Pengaduan : ".$row->tujuan_pengaduan."\n";
				$res 	.= "Status : ".$row->status_name."\n";
				$res 	.= "Informasi : ".$row->complaint."\n";
				$value['history'] = $res;
			}
			#$value 	= $row->no_ticket;
		}

		return $value;
	}
	
	public function last_ticket_response($ticket_id)
	{
		$value 	= '';
		$this->db->select('a.response, a.username, a.ticket_status, a.date, a.update_office, b.no_ticket, c.name as status_name', false);
		$this->db->where('a.ticket_id', $ticket_id);
		$this->db->limit(1);
		$this->db->join($this->ticket.' b','a.ticket_id = b.id');
		$this->db->join($this->ticket_status.' c','a.ticket_status = c.id');
		$this->db->order_by('a.date','desc');
		$query 	= $this->db->get($this->ticket_response.' a');
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= "";
			$value 	.= "No Ticket : ".$row->no_ticket."\n";
			$value 	.= "Tgl Update : ".$row->date."\n";
			$value 	.= "Kantor Update : ".$row->update_office."\n";
			$value 	.= "Status : ".$row->status_name."\n";
			$value 	.= "Respon : ".strip_tags($row->response)."\n";
		}
		
		return $value;
	}

	public function check_xray($no_resi)
	{
		$this->db->select('*', false);
		$this->db->where('id_kiriman', $no_resi);
		return $this->db->get($this->xray);
	}

	public function get_kantor_pos_user($code)
	{
		$this->db->select('fullname as text', false);
		$this->db->where('code', $code);
		$query 	= $this->db->get($this->kantor_pos);
		$row	= $query->row();
		return $row->text;
	}

	public function get_kantor_pos($search = "")
	{
		$this->db->select('code as id, fullname as text', false);
		$this->db->like('fullname', $search, 'BOTH');
		return $this->db->get($this->kantor_pos);
	}

	public function get_kantor_pos_tujuan($search = "")
	{
		$this->db->select('code as id, fullname as text', false);
		$this->db->like('fullname', $search, 'BOTH');
		$this->db->where('tujuan', 1);
		return $this->db->get($this->kantor_pos);
	}

	public function get_negara($search = "")
	{
		$this->db->select('code as id, concat(name," - ",code) as text', false);
		$this->db->like('name', $search, 'BOTH');
		$this->db->or_like('code', $search, 'BOTH');
		return $this->db->get($this->country);
	}

	public function get_kantor_name_code($code)
	{
		$this->db->select('group_concat(fullname SEPARATOR ",") as fullname');
		$this->db->where_in('code', $code);
		$query 	= $this->db->get($this->kantor_pos);
		$row 	= $query->row();
		return $row->fullname;
	}

	public function get_kantor_pos_in_regional($code, $show = 'group')
	{
		$value 	= '';
		$this->db->select('regional',false);
		$this->db->where('code', $code);
		$query 	= $this->db->get($this->kantor_pos);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value	= $row->regional;
		}
		return $value;
	}

	public function get_kantor_pos_in_regional_id($code, $show = 'group')
	{
		$value 	= '';
		$this->db->select('id',false);
		$this->db->where('code', $code);
		$query 	= $this->db->get($this->kantor_pos);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value	= $row->id;
		}
		return $value;
	}


	public function get_kantor_pos_regional($regional)
	{
		$this->db->select('code, fullname', false);
		$this->db->where('regional', $regional);
		return $this->db->get($this->kantor_pos);
	}

	
	
	public function showMainMenu()
	{
		$menuaccess		= $this->getAccessMenu($this->session->userdata('ses_username'));
		
		$this->db->select('a.id, a.parent_id, a.name, a.alias, a.url, a.icon, count(b.id) as total_child', false);
		$this->db->join($this->menu.' b','a.id = b.parent_id','LEFT');
		$this->db->group_by('a.id');
		$this->db->where('a.parent_id', 0);
		$this->db->where('a.status',1);
		$this->db->where_in('a.id', $menuaccess);
		$this->db->order_by('a.order_id','asc');
		return $this->db->get($this->menu.' a');
	}
	
	public function showChildMenu($parent_id)
	{
		$menuaccess		= $this->getAccessMenu($this->session->userdata('ses_username'));
		
		$this->db->select('a.id, a.parent_id, a.name, a.alias, a.url, a.icon', false);
		$this->db->where('a.parent_id', $parent_id);
		$this->db->order_by('a.order_id');
		$this->db->where('a.status',1);
		$this->db->where_in('a.id', $menuaccess);
		$this->db->order_by('a.order_id','asc');
		return $this->db->get($this->menu.' a');
	}
	
	public function getAccessMenu($username)
	{
		$this->db->select('a.menuaccess', false);
		$this->db->where('b.username', $username);
		$this->db->join($this->user.' b', 'a.id = b.role_id','left');
		$query	= $this->db->get($this->role.' a');
		$row	= $query->row();
		return explode(',', $row->menuaccess);
	}
	
	
	
	// Get Ticket information
	public function getTicketList()
	{
		$select = 'a.*';
		$this->db->select($select, false);
		$this->db->where('date(a.date)', 'CURDATE()', false);
		$this->db->where('b.ticket_type','basic');
		$this->db->join($this->ticket_categories.' b','a.category = b.id');
		return $this->db->get($this->ticket.' a');
	}
	
	// get total ticket each category
	public function getTotalTicketCategory($kantor_pos = array())
	{
		$select = 'a.id, a.type as name, count(b.id) as total_ticket';
		$this->db->select($select, false);
		$this->db->where('a.status',1);
		
		
		//((count($kantor_pos) > 0 ) ? $this->db->where_in('b.complaint_origin', $kantor_pos) : '');
		//$this->db->where_in('a.status', array(1));
		$this->db->join($this->ticket.' b','a.auto_id = b.category and date(b.date) = curdate() and b.complaint_origin in('.implode(",", $kantor_pos).')', 'left');
		$this->db->group_by('a.type');
		//$this->db->group_by('b.category');
		//$this->db->where('a.ticket_type', 'basic');
		return $this->db->get($this->ticket_categories.' a');
	}
	
	// ticket list per categories
	public function getTicketListCategory($id)
	{
		$select = 'a.id, a.no_ticket, a.phone_number, a.subject, a.category, a.date, c.name as status_name, c.param';
		$this->db->select($select, false);
		$this->db->where('date(a.date)', '2017-03-07', false);
		$this->db->where('a.category', $id);
		$this->db->join($this->ticket_categories.' b','a.category = b.id');
		$this->db->join($this->ticket_status.' c','a.status = c.id');
		return $this->db->get($this->ticket.' a');
	}
	
	// ticket history list per user
	public function getTicketListHistory($id)
	{
		$select = 'a.id, a.no_ticket, a.awb, a.status, a.date, a.last_update';
		$this->db->select($select, false);
		//$this->db->where('date(a.date)', 'CURDATE()', false);
		$this->db->where('a.contact_id', $id);
		$this->db->order_by('a.date','desc');
		$this->db->limit(6);
		//$this->db->join($this->ticket_categories.' b','a.category = b.id');
		//$this->db->join($this->ticket_status.' c','a.status = c.id');
		//$this->db->join($this->contacts.' d','a.contact_id = d.id');
		return $this->db->get($this->ticket.' a');
	}
	
	// get ticket status
	public function getTicketStatus()
	{
		$select = 'id, name, name as text';
		$this->db->select($select, false);
		$this->db->where('status', 1);
		return $this->db->get($this->ticket_status);
	}
	
	// get user data
	public function getUserData($uid)
	{
		$select = "*";
		$this->db->select($select, false);
		$this->db->where('id', $uid);
		return $this->db->get($this->user);
	}	
	
	// get user role
	public function getUserRole()
	{
		$this->db->select('id, name', false);
		$this->db->where('status', 1);
		return $this->db->get($this->role);
	}
	
	// get total ticket everyday in weekly
	public function getTotalTicketWeekly($date = '', $kprk)
	{
		$date 	= ($date != '') ? $date : date('Y-m-d');
		return $this->db->query('call sp_ticket_daily("'.$date.'", "'.$kprk.'")');
		/*
		return $this->db->query('SELECT a.date, COUNT(z.id) as total
FROM (
    SELECT "2017-03-07" - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS date
    FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
    CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
    CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
) a LEFT JOIN ticket z ON a.date = DATE(z.date)
WHERE a.date BETWEEN DATE_SUB("2017-03-07", INTERVAL 7 DAY) AND "2017-03-07" 
GROUP BY a.date');
		*/

		
	}
	
	public function ticketDetail($id)
	{
		#d.title as assignee_name
		$select = 'a.id, a.subject, a.no_ticket, a.date, date_format(a.date,"%H:%i:%s %d/%m/%Y") as tgl_entry, a.last_update, a.assignee, a.ccs, a.tags, a.user_cch, a.category, a.priority, a.status, a.complaint, b.name as category_name, a.contact_id, c.name_requester as contact_name, c.phone as phone_number, c.address, a.tujuan_pengaduan as assignee, a.tujuan_pengaduan_name as assignee_val, a.complaint_origin, a.awb, a.sender, a.receiver, a.notes, d.name as channel_name, c.phone, c.email, c.facebook, c.twitter, c.instagram, a.channel, sum(if(e.tmp_status = 0 and e.response_id = "", 1,0)) as total_file, f.title as username_title, g.name as produk';
		$this->db->select($select, false);
		$this->db->join($this->ticket_categories.' b','a.category = b.id','LEFT');
		$this->db->join($this->contacts.' c','a.contact_id = c.id','LEFT');
		$this->db->join($this->channel.' d','a.channel = d.auto_id','left');
		$this->db->join($this->media.' e','a.id = e.ticket_id','left');
		$this->db->join($this->user.' f','a.user_cch = f.username');
		$this->db->join($this->produk.' g','a.jenis_layanan = g.code','left');
		$this->db->where('a.id', $id);
		return $this->db->get($this->ticket.' a');
	}

	public function get_awb_history($awb, $no_ticket, $date = '')
	{
		$this->db->select('a.no_ticket, a.complaint_origin, a.date, a.id, COALESCE(b.fullname," ?? ") as officename', false);
		$this->db->where('a.awb', $awb);
		$this->db->where('a.no_ticket != ', $no_ticket);
		(($date != '') ? $this->db->where('a.date <', $date) : '');
		$this->db->order_by('a.date','desc');
		$this->db->join($this->kantor_pos.' b','a.complaint_origin = b.code','left');
		return $this->db->get($this->ticket.' a');
	}

	public function ticketResponse($ticket_id)
	{
		$select	= 'a.*, b.title, c.param, c.name as status_name, count(d.id) as total_file';
		$this->db->select($select, false);
		$this->db->join($this->user.' b','a.username = b.username');
		$this->db->join($this->ticket_status.' c','a.ticket_status = c.id','left');
		$this->db->join($this->media.' d','a.id = d.response_id','left');
		$this->db->where('a.ticket_id', $ticket_id);
		$this->db->group_by('a.id');
		$this->db->order_by('a.date','asc');
		
		return $this->db->get($this->ticket_response.' a');
	}
	
	public function getRoleData($id)
	{
		$this->db->select('id, name, menuaccess, status',false);
		$this->db->where('id', $id);
		return $this->db->get($this->role);
	}
	
	
	/* menu */
	public function NewListMenu($menu,$element='',$value='',$class='menu-list',$disable='')
	{
		
		$input		= '';
		$var		= '';
		$menuaccess = explode(',',$menu);
		$newmenu['checkbox']	= array();
		
		$this->db->select('id,name,url', FALSE);
		$this->db->where('parent_id',0);
		$this->db->where('status',1);
		//$this->db->where_in('id',$menuaccess);
		$query 	= $this->db->get($this->menu);
		
		if($query->num_rows() > 0):
			foreach($query->result() as $row):
		
				$array_value 	= explode(',',$value);
				$set			= $disable != '' ? TRUE : FALSE;
				
				if(in_array($row->id,$array_value)){
					$setVal = TRUE;
				}else{
					$setVal	= FALSE;
				}
				
				$data	= array('parentid'=>"0",
								'value'=>$row->id,
								'disable'=>$set,
								'checked'=>$setVal,
								'class'=>$class,
								'text'=>$row->name,
								'child'=>$this->NewListMenuParent($row->id,$menuaccess,$element,$value,$class,$set)
								);
				array_push($newmenu['checkbox'],$data);
			
			endforeach;
		endif;
		
		return $newmenu;
	}
	
	function NewListMenuParent($parent,$menuaccess,$element='',$value='',$class='',$set =''){
		$input	= '';
		$newmenu['checkbox']	= array();
		$this->db->select('id,name,url');
		$this->db->where('parent_id',$parent);
		$this->db->where('status',1);
		$query 	= $this->db->get($this->menu);
		$var 	= '';
		if($query->num_rows() > 0):
			foreach($query->result() as $row):
				$array_value 	= explode(',',$value);
				
				if(in_array($row->id,$array_value)){
					$setVal = TRUE;
				}else{
					$setVal	= FALSE;
				}
				
				$data	= array('parentid'=>$parent,
								'value'=>$row->id,
								'disable'=>$set,
								'checked'=>$setVal,
								'class'=>$class,
								'text'=>$row->name,
								'child'=>$this->NewListMenuParent($row->id,$menuaccess,$element,$value,$class,$set));
				array_push($newmenu['checkbox'],$data);
			endforeach;
		endif;
		return $newmenu;
		
	}
	
	/* Get User information base on username */
	public function getUserInformation($username)
	{
		$this->db->select('a.id, a.title, a.username, a.email', false);
		$this->db->where('a.username', $username);
		$query	= $this->db->get($this->user.' a');
		return $query->row();
	}
	
	/* get dashboard information */
	public function getDashboardStat()
	{
		
		$query	= $this->db->query('SELECT 
							(SELECT COUNT(id) FROM ticket WHERE STATUS IN (1,12,19,99)) AS all_ticket, 
							(SELECT COUNT(id) FROM ticket WHERE STATUS IN (99)) AS all_ticket_solved,
							(SELECT COUNT(id) FROM ticket WHERE STATUS IN (12,19,1)) AS all_ticket_unsolved,
							(SELECT DATEDIFF(CURDATE(), MIN(DATE)) AS total FROM ticket) AS total_day'
							);
		$row	= $query->row();
		return $row;
	}
	
	/* get ticket categories */
	public function getTicketCategories($select = "*")
	{
		
		$this->db->select($select, false);
		$this->db->where('status',1);
		return $this->db->get($this->ticket_categories);
	}
	
	/* get top complaint product */
	public function getMostComplaintTicket()
	{
		return $this->db->query('call sp_top_complaint_product()');
	}

	// upload
	public function media_file_tmp($data)
	{
		$this->db->set('id', random_string('sha1'));
		$this->db->insert($this->media, $data);
		return $this->db->affected_rows();
	}

	public function load_media_uploaded($user)
	{
		$this->db->select('a.id, a.file_name, a.file_ext, a.file_size', false);
		$this->db->where('a.user_cch', $user);
		$this->db->where('a.tmp_status', 1);
		$this->db->where('a.ticket_id','');
		$this->db->where('a.response_id','');
		return $this->db->get($this->media.' a');
	}

	public function load_media_response_uploaded($ticket_id, $user)
	{
		$this->db->select('a.id, a.file_name, a.file_ext, a.file_size', false);
		$this->db->where('a.user_cch', $user);
		$this->db->where('a.tmp_status', 1);
		$this->db->where('a.ticket_id',$ticket_id);
		$this->db->where('a.response_id','');
		return $this->db->get($this->media.' a');
	}

	public function remove_media($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->media);
		return $this->db->affected_rows();
	}

	public function download_media($id)
	{
		$this->db->select('file_path, file_name', false);
		$this->db->where('id', $id);
		return $this->db->get($this->media);
	}

	public function get_ticket_media($id, $response_id = 'all')
	{
		$this->db->select('file_name, file_path, file_ext, id', false);
		$this->db->where('ticket_id', $id);
		(($response_id != 'all' ) ? $this->db->where('response_id', $response_id) : '');
		$this->db->where('tmp_status', 0);
		return $this->db->get($this->media);
	}
}