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
class Mcontacts extends CI_Model{
	
	private $cust_support 	= 'customer_support';
	private $channel 		= 'channel_info';
	private $cust_category 	= 'category';

	public function get_source_channel()
	{
		$this->db->select('id as id, name as text', false);
		$this->db->where('status', 1);
		return $this->db->get($this->channel);
	}

	public function get_category($close = 0)
	{
		$this->db->select('id, name, type', false);
		$this->db->where('status', 1);
		$this->db->where('on_close', $close);
		return $this->db->get($this->cust_category);
	}


	// OLD 

	private $contacts		= 'ccare';
	private $ccare		= 'ccare';
	private $ccare_history		= 'ccare_history';
	private $ticket		= 'ticket';
	private $contacts_type	= 'contacts_type';
	private $area			= 'area';
	
	public function getContactsData($search = '', $cust = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.created_date, a.name_requester, a.address, a.phone, a.email, a.facebook, a.instagram, a.twitter';
		$this->db->select($select, false);		
		( ($search != '') ? $this->db->like('a.name_requester', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.phone', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		( ($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.name_requester', 'asc'));
		//( ($cust != '') ? $this->db->where('a.type_customer', $cust) : '');
		
		//$this->db->join($this->contacts_type.' f','a.type_customer = f.id','LEFT');
		//$this->db->join($this->contacts.' g','a.parent_customer = g.id', 'left');
		return $this->db->get($this->contacts.' a');
	}

	
	public function getContactsDataTotal($search = '', $cust = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->like('a.name_requester', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.phone', $search, 'both') : '');
		#$this->db->where('date(a.date) between "'.$date[0].'" and "'.$date[1].'"');
		//( ($cust != '') ? $this->db->where('a.type_customer', $cust) : '');
		$query	= $this->db->get($this->contacts.' a');
		$row 	= $query->row();
		return $row->total;
	}
	
	
	
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
		#$this->db->join($this->area.' b','a.propinsi = b.idprov and b.level = 1','LEFT');
		#$this->db->join($this->area.' c','a.kota = c.idkot AND a.propinsi = c.idprov AND c.level = 2','LEFT');
		#$this->db->join($this->area.' d','a.kecamatan = d.idkec AND a.propinsi = d.idprov AND a.kota = d.idkot AND d.level = 3','LEFT');
		#$this->db->join($this->area.' e','a.kelurahan = e.idkel AND a.propinsi = e.idprov AND a.kota = e.idkot AND a.kecamatan = e.idkec AND e.level = 4','LEFT');
		#$this->db->join($this->contacts_type.' f','a.type_customer = f.id','LEFT');
		#$this->db->join($this->contacts.' g','a.parent_customer = g.id', 'left');
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
		/*
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
		*/
		$this->db->select('a.id, a.name_requester, a.address, a.phone, a.email, a.twitter, a.facebook, a.instagram, a.last_update', false);
		$this->db->join($this->ccare_history.' b','a.id = b.ccare_id', 'left');
		$this->db->join($this->ticket.' c','a.id = c.contact_id','left');
		$this->db->where('a.id', $key);
		return $this->db->get($this->ccare.' a');
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