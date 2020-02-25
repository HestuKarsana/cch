<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  
 *
 * @package		Customer Care Models
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Mcare extends CI_Model{
	
	private $ccare 			= 'ccare';
	private $cust_support 	= 'customer_support';
	private $channel 		= 'channel_info';
	private $cust_category 	= 'category';
	private $ticket 		= 'ticket';
	private $ticket_resp 	= 'ticket_response';
	private $ticket_status 	= 'ticket_status';
	private $customer 		= 'customer';
	private $media			= 'ticket_media';
	private $product 		= 'pos_product';
	private $pos_office 	= 'pos_office';

	public function get_pos_product()
	{
		$this->db->select('code as id, concat(name,"-",layanan) as text', false);
		$this->db->where('status', 1);
		return $this->db->get($this->product);
	}
	public function get_source_channel()
	{
		$this->db->select('id as id, name as text', false);
		$this->db->where('status', 1);
		return $this->db->get($this->channel);
	}
	
	public function get_channel_detail($id)
	{
		$value 	= 0;
		$this->db->select('auto_id, name', false);
		$this->db->where('id', $id);
		$query 	= $this->db->get($this->channel);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= $row->auto_id;
		}
		return $value;
	}

	public function get_category($on_close = 0)
	{
		$this->db->select('id, auto_id, name, type', false);
		$this->db->where('status', 1);
		$this->db->where('on_close', 1);
		return $this->db->get($this->cust_category);
	}
	
	public function get_category_detail($id)
	{
		$value 	= 0;
		$this->db->select('auto_id, name', false);
		$this->db->where('id', $id);
		$query 	= $this->db->get($this->cust_category);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= $row->auto_id;
		}
		return $value;
	}

	public function get_category_data($id, $select = 'auto_id')
	{
		$value 	= '';
		$this->db->select($select.' as data', false);
		$this->db->where('id', $id);
		$query 	= $this->db->get($this->cust_category);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= $row->data;
		}
		return $value;
	}

	public function check_customer($data)
	{
		$value 	= 0;
		$this->db->select('auto_id', false);
		if($data['phone']  != ''){
			$this->db->where('phone', $data['phone']);
		}
		
		if($data['email']	!= ''){
			$this->db->where('email', $data['email']);
		}
		
		$query 	= $this->db->get($this->ccare);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= $row->auto_id;
		}
		return $value;
	}
	
	public function check_customer_phone($phone)
	{
		$this->db->select('phone as id, id as uid, concat(phone," - ",name_requester) as text, address, email, name_requester as name', false);
		$this->db->like('phone',$phone,'BOTH');
		return $this->db->get($this->ccare);
	}

	public function get_ticket($awb)
	{
		$this->db->select('a.id, a.no_ticket, a.complaint, a.date, a.last_update, a.user_cch, a.tujuan_pengaduan, a.complaint_origin, b.fullname as kantor_pembuat, c.name as category_name, a.category_detail', false);
		$this->db->where('a.awb', $awb);
		$this->db->join($this->pos_office.' b','a.complaint_origin = b.code','LEFT');
		$this->db->join($this->cust_category.' c','a.category = c.auto_id');
		$this->db->order_by('a.auto_id','desc');
		return $this->db->get($this->ticket.' a');
	}

	public function get_ticket_response($ticket_id)
	{
		$this->db->select('a.response, a.username, a.ticket_status, a.date, a.update_office, b.name as status_name, c.fullname as kantor_response', false);
		$this->db->where('a.ticket_id', $ticket_id);
		$this->db->join($this->ticket_status.' b','a.ticket_status = b.id');
		$this->db->join($this->pos_office.' c','a.update_office = c.code','LEFT');
		$this->db->order_by('a.date','asc');
		return $this->db->get($this->ticket_resp.' a');
	}
	
	public function save_data($data, $old_id, $new_id)
	{
		if($old_id == ""){
			$this->db->set('id', $new_id);
			$this->db->set('created_date','NOW()', false);
			$this->db->insert($this->ccare, $data);
		}else{
			$this->db->where('id', $old_id);
			$this->db->update($this->ccare, $data);	
		}
		return $this->db->affected_rows();
	}

	public function create_ticket($data)
	{
		$ticket_id 	= random_string('sha1');

		$this->update_media_tmp($ticket_id, $data['user_cch']);

		$this->db->set('id', $ticket_id);
		$this->db->set('date', 'NOW()', false);
		$this->db->insert($this->ticket, $data);
		return $this->db->affected_rows();
	}

	public function update_media_tmp($ticket_id, $user)
	{
		$this->db->set('ticket_id', $ticket_id);
		$this->db->set('tmp_status', 0);
		$this->db->where('user_cch', $user);
		$this->db->where('tmp_status', 1);
		$this->db->update($this->media);
	}

	public function get_ccare_data($search = '', $product = '', $date = '', $row_show, $row_start, $sort, $order)
	{
		$select 	= "a.id, a.no_ticket, a.contact_id, a.phone_number, a.assignee, a.category, a.priority, a.date, a.last_update, a.status, a.subject, a.complaint, a.closed_is, a.awb, a.complaint_origin, a.sender, a.sender_name, a.receiver, a.receiver_name, a.channel, a.user_cch, a.tujuan_pengaduan, a.tujuan_pengaduan_name, a.service_type, b.name as channel_name, c.name as ticket_status, c.id as ticket_val, a.jenis_layanan, e.name as jenis_layanan_name";
		
		$this->db->select($select, false);
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		( ($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.date', 'desc'));
		( ($search != '') ? $this->db->where('a.no_ticket like "%'.$search.'%" or a.awb like "%'.$search.'%" or a.user_cch like "%'.$search.'%" or a.awb like "%'.$search.'%"', null, false) : '');
		( ($product != '') ? $this->db->where('a.jenis_layanan', $product) : '');
		$this->db->where($date, null, false);
		$this->db->join($this->channel.' b','a.channel = b.auto_id');
		$this->db->join($this->ticket_status.' c','a.status = c.id');
		$this->db->join($this->ccare.' d','a.contact_id = d.id');
		$this->db->join($this->product.' e','a.jenis_layanan = e.code','left');
		return $this->db->get($this->ticket.' a');
	}

	
	public function get_ccare_total_data($search = '', $product = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->where('a.no_ticket like "%'.$search.'%" or a.awb like "%'.$search.'%"', null, false) : '');
		( ($product != '') ? $this->db->where('a.jenis_layanan', $product) : '');
		$this->db->where($date, null, false);
		$this->db->join($this->channel.' b','a.channel = b.auto_id');
		$this->db->join($this->ticket_status.' c','a.status = c.id');
		$this->db->join($this->ccare.' d','a.contact_id = d.id');
		$this->db->join($this->product.' e','a.jenis_layanan = e.code','left');
		$query	= $this->db->get($this->ticket.' a');
		$row 	= $query->row();
		return $row->total;
	}

	public function get_no_ticket($code_prefix = "")
	{
		
		$prefix 		= "";
		if($code_prefix != '')
		{
			$prefix 	= substr($code_prefix, 0, 3);
		}
		
		$this->db->select('no_ticket', false);
		$this->db->like('no_ticket', $prefix.date('ym'),'AFTER');
		$this->db->order_by('no_ticket','desc');
		$this->db->limit(1);
		$query = $this->db->get($this->ticket);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= $row->no_ticket + 1;
		}else{
			$value 	= $prefix.date('ym').sprintf("%04d", 1);
		}
		return $value;
	}

	public function get_ticket_info($uid)
	{
		$this->db->select('a.no_ticket, a.last_update, a.subject, a.complaint, b.name as status_name, a.date,  COALESCE(a.awb,"-") as awb, a.id as ticket_id', false);
		$this->db->where('a.id', $uid);
		$this->db->join($this->ticket_status.' b','a.status = b.id');
		return $this->db->get($this->ticket.' a');
	}

	// OLD 

	private $contacts		= 'contacts';
	private $contacts_type	= 'contacts_type';
	private $area			= 'area';
	
	
	
	public function getContactsDataMUM($search = '', $cust = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.name, a.address, a.delivery_address, a.phone_number, a.email, a.last_update, a.status, a.type_customer, a.parent_customer, a.patokan, b.name AS propinsi, c.name AS kota, d.name AS kecamatan, e.name AS kelurahan, f.name as type_cust_name, g.name as parent_name';
		$this->db->select($select, false);		
		( ($search != '') ? $this->db->like('a.name', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.phone_number', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		( ($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.name', 'desc'));
		( ($cust != '') ? $this->db->where('a.type_customer', $cust) :  $this->db->where('a.type_customer >= ', 1));
		$this->db->join($this->area.' b','a.propinsi = b.idprov and b.level = 1','LEFT');
		$this->db->join($this->area.' c','a.kota = c.idkot AND a.propinsi = c.idprov AND c.level = 2','LEFT');
		$this->db->join($this->area.' d','a.kecamatan = d.idkec AND a.propinsi = d.idprov AND a.kota = d.idkot AND d.level = 3','LEFT');
		$this->db->join($this->area.' e','a.kelurahan = e.idkel AND a.propinsi = e.idprov AND a.kota = e.idkot AND a.kecamatan = e.idkec AND e.level = 4','LEFT');
		
		$this->db->join($this->contacts_type.' f','a.type_customer = f.id','LEFT');
		$this->db->join($this->contacts.' g','a.parent_customer = g.id', 'left');
		return $this->db->get($this->contacts.' a');
	}

	
	public function getContactsDataTotalMUM($search = '', $cust = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->like('a.name', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.phone_number', $search, 'both') : '');
		#$this->db->where('date(a.date) between "'.$date[0].'" and "'.$date[1].'"');
		( ($cust != '') ? $this->db->where('a.type_customer', $cust) :  $this->db->where('a.type_customer >= ', 1));
		$query	= $this->db->get($this->contacts.' a');
		$row 	= $query->row();
		return $row->total;
	}
	
	
	public function datalist($search)
	{
		$select	= '*';
		$this->db->select($select, false);		
		(($search != '') ? $this->db->like('name', $search, 'both') : '');
		return $this->db->get($this->contacts);
	}
	
	public function saveData($data, $key = '')
	{
		if($key != ''){
			$this->db->where('id', $key);
			return $this->db->update($this->contacts, $data);
		}else{
			$this->db->set('id', random_string('sha1'));
			return $this->db->insert($this->contacts, $data);
		}
	}
	
	public function getContactByAutoID($id){
		$value		= array();
		$select 	= '*';
		$this->db->select($select, false);
		$this->db->where('auto_id',$id);
		$query		= $this->db->get($this->contacts);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= $row;
		}
		return $value;
	}
	
	public function getContact($id)
	{
		$this->db->select($select, false);
		$this->db->where('id', $id);
		return $this->db->get($this->contacts);
	}
	
	public function deleteData($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->contacts);
	}
	
	public function insertFromCSV($result)
	{
		$total				= count($result);
		$data['duplicate']	= 0;
		$data['success']	= 0;
		$data['failed']		= 0;
		$data['all']		= $total;
		//print_r($result);
		
		for($i=1; $i<=$total; $i++)
		{
			$duplicate	= $this->checkingDuplicatContacs($result[$i]['Email'], $result[$i]['Phone_Number']);
			if($duplicate == 0){
				// insert new contacs
				$this->db->set('id', random_string('sha1'));
				$this->db->set('name',$result[$i]['Name']);
				$this->db->set('phone_number',$result[$i]['Phone_Number']);
				$this->db->set('email',$result[$i]['Email']);
				$this->db->set('address',$result[$i]['Address']);
				
				$this->db->set('post_date','NOW()', false);
				$this->db->set('status',1);
				$query	= $this->db->insert($this->contacts);
				if($query){
					$data['success'] += 1;
				}else{
					$data['failed'] += 1;
				}
			}else{
				$data['duplicate'] += 1;
			}
		}	
		return $data;
		
	}
	
	private function checkingDuplicatContacs($email, $phone)
	{
		$this->db->select('count(*) as total', false);
		(($email != '') ? $this->db->where('email', $email) : '');
		(($phone != '') ? $this->db->or_where('phone_number', $phone) : '');
		$query		= $this->db->get($this->contacts);
		$row		= $query->row();
		return $row->total;
	}
	
	public function getDetailContact($key)
	{
		$select	= 'a.id, a.name, a.address, a.delivery_address, a.phone_number, a.email, a.propinsi, a.kota, a.kecamatan, a.kelurahan, a.last_update, a.status, a.type_customer, a.parent_customer, a.patokan, b.name AS propinsi_name, c.name AS kota_name, d.name AS kecamatan_name, e.name AS kelurahan_name, f.name as type_cust_name, g.name as parent_name, a.code_mum, a.type_customer, g.name as parent_customer_name, a.verifikasi_mum';
		
		$this->db->select($select, false);
		$this->db->where('a.id', $key);
		
		$this->db->join($this->area.' b','a.propinsi = b.idprov and b.level = 1','LEFT');
		$this->db->join($this->area.' c','a.kota = c.idkot AND a.propinsi = c.idprov AND c.level = 2','LEFT');
		$this->db->join($this->area.' d','a.kecamatan = d.idkec AND a.propinsi = d.idprov AND a.kota = d.idkot AND d.level = 3','LEFT');
		$this->db->join($this->area.' e','a.kelurahan = e.idkel AND a.propinsi = e.idprov AND a.kota = e.idkot AND a.kecamatan = e.idkec AND e.level = 4','LEFT');
		$this->db->join($this->contacts_type.' f','a.type_customer = f.id','LEFT');
		$this->db->join($this->contacts.' g','a.parent_customer = g.id', 'left');
		
		return $this->db->get($this->contacts.' a');
	}
	
	/* contacts type */
	public function getContactType()
	{
		$select		= "a.id, a.name";
		$this->db->select($select, false);
		$this->db->where('a.status', 1);
		return $this->db->get($this->contacts_type.' a');
	}
	
	public function checkContacts($field, $value)
	{
		$this->db->select('id', false);
		$this->db->where($field, $value);
		$query	= $this->db->get($this->contacts);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= $row->id;
		}else{
			$value	= '';
		}
	
		return $value;
	}
}