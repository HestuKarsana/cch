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
class Mreport extends CI_Model{
	
	private $contacts	= 'ccare';
	private $user		= 'sys_user';
	private $ticket		= 'ticket';
	private $categories = 'category';
	private $status 	= 'ticket_status';
	private $ticket_response 	= 'ticket_response';

	private $pos_office 	= 'pos_office';
	private $product 		= 'pos_product';
	
	public function getTicketData($search = '', $status, $date, $row_show, $row_start, $sort = 'a.date', $order = 'desc')
	{
		$select	= 'a.id, a.no_ticket, a.subject, a.priority, a.complaint, e.fullname as complaint_origin, f.name as product, a.awb, a.category, a.date, a.status, a.files, a.phone_number, TIMESTAMPDIFF(HOUR, a.date, a.last_update) as long_duration, b.name as category_name, c.name_requester as contact_name, c.id as cid, d.name as status_name, d.param, a.tujuan_pengaduan, a.sender, a.receiver,
		ADDTIME(SEC_TO_TIME(TIMESTAMPDIFF(second, a.date,  DATE_ADD(a.date, interval TOTAL_WEEKDAYS_2(date(a.date), date(a.last_update)) day))),timediff(time(a.last_update), time(a.date))) as tt';
		$this->db->select($select, false);		
		( ($search != '') ? $this->db->like('a.subject', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.no_ticket', $search, 'both') : '');
		( ($status != '') ? $this->db->where('a.status', $status) : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		$this->db->where('date(a.date) between "'.$date[0].'" and "'.$date[1].'"');
		$this->db->join($this->categories.' b','a.category = b.auto_id');
		$this->db->join($this->contacts.' c','a.contact_id = c.id');
		$this->db->join($this->status.' d','a.status = d.id');
		$this->db->join($this->pos_office.' e','a.complaint_origin = e.code', 'LEFT');
		$this->db->join($this->product.' f','a.jenis_layanan = f.code','left');
		$this->db->order_by($sort, $order);
		return $this->db->get($this->ticket.' a');
	}
	
	public function getTicketDataTotal($search = '', $status, $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->like('a.subject', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.no_ticket', $search, 'both') : '');
		( ($status != '') ? $this->db->where('a.status', $status) : '');
		$this->db->where('date(a.date) between "'.$date[0].'" and "'.$date[1].'"');
		$query	= $this->db->get($this->ticket.' a');
		$row 	= $query->row();
		return $row->total;
	}

	// Incoming
	public function get_incoming_ticket($search = '', $pos_office = '', $date, $row_show, $row_start, $sort = 'a.date', $order = 'desc')
	{
		/*
		$select	= 'a.id, a.no_ticket, a.subject, a.priority, a.complaint, a.category, a.date, a.status, a.files, a.phone_number, TIMESTAMPDIFF(HOUR, a.date, a.last_update) as long_duration, b.name as category_name, c.name_requester as contact_name, c.id as cid, d.name as status_name, d.param, a.tujuan_pengaduan, a.sender, a.receiver,
		ADDTIME(SEC_TO_TIME(TIMESTAMPDIFF(second, a.date,  DATE_ADD(a.date, interval TOTAL_WEEKDAYS_2(date(a.date), date(a.last_update)) day))),timediff(time(a.last_update), time(a.date))) as tt';
		$this->db->select($select, false);		
		//( ($search != '') ? $this->db->like('a.subject', $search, 'both') : '');
		//( ($search != '') ? $this->db->or_like('a.no_ticket', $search, 'both') : '');
		( ($pos_office != '') ? $this->db->where('a.tujuan_pengaduan like "%'.$pos_office.'%"',null, false) : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		
		
		$this->db->where('date(a.date) between "'.$date[0].'" and "'.$date[1].'"');
		$this->db->join($this->categories.' b','a.category = b.auto_id');
		$this->db->join($this->contacts.' c','a.contact_id = c.id');
		$this->db->join($this->status.' d','a.status = d.id');
		
		$this->db->order_by($sort, $order);
		return $this->db->get($this->ticket.' a');
		*/

		/*
		$this->db->select('a.id, a.regional, a.city, count(b.id) as total_ticket, 
		count( case when b.status = 99 then b.id end) as total_selesai,
		count( case when b.status != 99 then b.id end) as total_terbuka',false);
		$this->db->join($this->ticket.' b','a.code = b.tujuan_pengaduan','left');
		$this->db->where('date(b.date) between "'.$date[0].'" and "'.$date[1].'"');
		$this->db->group_by('a.regional');
		$this->db->order_by('a.auto_id','asc');
		return $this->db->get($this->pos_office.' a');
		*/
		return $this->db->query('call sp_report_incoming("'.$pos_office.'","'.$date[0].'","'.$date[1].'");');
	}

	public function get_regional($regional_id)
	{
		$this->db->select('regional, city', false);
		$this->db->where('id', $regional_id);
		return $this->db->get($this->pos_office);
	}

	public function get_kprk($code)
	{
		$this->db->select('code, name, fullname', false);
		$this->db->where('code', $code);
		return $this->db->get($this->pos_office);
	}

	public function get_incoming_regional_ticket($regional = '', $date, $filter = 'all')
	{
		/*
		$this->db->select('a.id, a.fullname, a.city, count(b.id) as total_ticket, 
		count( case when b.status = 99 then b.id end) as total_selesai,
		count( case when b.status != 99 then b.id end) as total_terbuka',false);

		$this->db->join($this->ticket.' b','a.code = b.tujuan_pengaduan','left');
		$this->db->where('a.regional', $regional);
		$this->db->where('date(b.date) between "'.$date[0].'" and "'.$date[1].'"');
		$this->db->group_by('a.code');
		$this->db->order_by('total_ticket','desc');
		return $this->db->get($this->pos_office.' a');
		*/
		return $this->db->query('call sp_report_incoming_regional("'.$regional.'","'.$date[0].'","'.$date[1].'");');
	}

	public function get_incoming_kprk_ticket($kprk = '', $date, $filter = 'all', $start, $show)
	{
		return $this->db->query('call sp_report_incoming_kprk("'.$kprk.'","'.$date[0].'","'.$date[1].'","'.$filter.'","'.$start.'","'.$show.'");');
	}


	public function get_outgoing_ticket($pos_office = '', $date)
	{
		return $this->db->query('call sp_report_outgoing("'.$pos_office.'","'.$date[0].'","'.$date[1].'");');
	}

	public function get_outgoing_regional_ticket($regional = '', $date)
	{
		return $this->db->query('call sp_report_outgoing_regional("'.$regional.'","'.$date[0].'","'.$date[1].'");');
	}

	public function get_outgoing_kprk_ticket($kprk = '', $date, $filter = 'all', $start, $show)
	{
		return $this->db->query('call sp_report_outgoing_kprk("'.$kprk.'","'.$date[0].'","'.$date[1].'","'.$filter.'","'.$start.'","'.$show.'");');
	}

	public function get_product_ticket($date, $regional = "", $kprk = "")
	{
		return $this->db->query('call sp_report_product("'.$date[0].'","'.$date[1].'","'.$regional.'","'.$kprk.'");');
	}

	public function get_product_info($code)
	{
		$this->db->select('id, code, name, layanan', false);
		$this->db->where('code', $code);
		return $this->db->get($this->product);
	}

	public function get_product_detail_by_regional($start, $end, $product)
	{
		return $this->db->query('call sp_report_product_regional("'.$start.'","'.$end.'","'.$product.'");');
	}

	
	public function get_product_detail_in_kprk($start, $end, $product)
	{
		return $this->db->query('call sp_report_product_kprk("'.$start.'","'.$end.'","'.$product.'");');
	}

	// Contacts
	public function getContactsData($search = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.*, count(b.id) as total_ticket';
		$this->db->select($select, false);		
		( ($search != '') ? $this->db->like('a.name', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.phone_number', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		$this->db->join($this->ticket.' b','a.id = b.contact_id','LEFT');
		#$this->db->join($this->categories.' b','a.category = b.id');
		#$this->db->join($this->contacts.' c','a.phone_number = c.phone_number');
		#$this->db->join($this->status.' d','a.status = d.id');
		$this->db->order_by($sort, $order);
		$this->db->group_by('a.id');
		$this->db->group_by('b.contact_id');
		return $this->db->get($this->contacts.' a');
	}
	
	public function getContactsDataTotal($search = '')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->like('a.name', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.phone_number', $search, 'both') : '');
		$query	= $this->db->get($this->contacts.' a');
		$row 	= $query->row();
		return $row->total;
	}

	// dashboard
	public function get_cch_kpi($date = '')
	{
		//$date = ($date != '') ? $date : date('Y-m-d');
		/*
		$this->db->select('count( case when ADDTIME(SEC_TO_TIME(TIMESTAMPDIFF(second, a.date,  DATE_ADD(a.date, interval TOTAL_WEEKDAYS_2(date(a.date), date(a.last_update)) day))),timediff(time(a.last_update), time(a.date))) <= "24:00:00" then a.id end ) as grade_a, 
		count( case when ADDTIME(SEC_TO_TIME(TIMESTAMPDIFF(second, a.date,  DATE_ADD(a.date, interval TOTAL_WEEKDAYS_2(date(a.date), date(a.last_update)) day))),timediff(time(a.last_update), time(a.date))) > "24:00:00" then a.id end ) as grade_b', false);
		//$this->db->where('a.status', 99);
		$this->db->where('a.info_aduan','PENGADUAN');
		(($date != '') ? $this->db->where('year(a.date) = year("'.$date.'") and month(a.date) = month("'.$date.'")', null, false) : '');
		$this->db->join($this->pos_office.' b','a.sender = b.code');
		$this->db->join($this->pos_office.' c','a.sender = c.code');
		return $this->db->get($this->ticket.' a');
		*/
		return $this->db->query('call sp_report_dash_pencapaian("'.$date.'")');
	}

	public function get_kantor_asal($date = '', $kpi = '') #$where = array()
	{
		//$date = ($date != '') ? $date : date('Y-m-d');
		/*
		$this->db->select('a.sender, b.regional, count(a.id) as total, b.code', false);
		$this->db->where('a.status',99);
		(($date != '') ? $this->db->where('year(a.date) = year("'.$date.'") and month(a.date) = month("'.$date.'")', null, false) : '');
		
		//(($kpi != '') ? $this->db->where($kpi, null, false) : '');
		if(!empty($where)){
			$where_text	= implode(' and ', $where);
			$this->db->where($where_text, null, false);
		}
		
		$this->db->join($this->pos_office.' b','a.sender = b.code');
		$this->db->join($this->pos_office.' c','a.receiver = c.code');
		$this->db->group_by('b.regional');
		return $this->db->get($this->ticket.' a');
		*/
		return $this->db->query('call sp_report_dash_regional_asal("'.$date.'","'.$kpi.'")');
	}

	public function get_kantor_tujuan($date = '', $kpi = "", $reg_asal = "")
	{
		//$date = ($date != '') ? $date : date('Y-m-d');
		/*
		$this->db->select('a.receiver, b.regional, count(a.id) as total', false);
		$this->db->where('a.status',99);
		(($date != '') ? $this->db->where('year(a.date) = year("'.$date.'") and month(a.date) = month("'.$date.'")', null, false) : '');
		#(($kpi != '') ? $this->db->where($kpi, null, false) : '');
		#(($regional_kirim != '') ? $this->db->where($regional_kirim, null, false) : '');
		if(!empty($where)){
			$where_text	= implode(' and ', $where);
			$this->db->where($where_text, null, false);
		}
		$this->db->join($this->pos_office.' b','a.receiver = b.code');
		$this->db->join($this->pos_office.' c','a.sender = c.code');
		$this->db->group_by('b.regional');
		return $this->db->get($this->ticket.' a');
		*/
		return $this->db->query('call sp_report_dash_regional_tujuan("'.$date.'","'.$kpi.'","'.$reg_asal.'")');
	}

	public function get_jenis_produk($date = '', $kpi = "", $reg_asal = "", $reg_tujuan = "")
	{
		//	$date = ($date != '') ? $date : date('Y-m-d');
		/*
		$this->db->select('x.code, x.name, count(a.id) as total', false);
		$this->db->join($this->ticket.' a','x.code = a.jenis_layanan');
		$this->db->group_by('x.code');
		$this->db->order_by('total','desc');
		(($date != '') ? $this->db->where('year(a.date) = year("'.$date.'") and month(a.date) = month("'.$date.'")', null, false) : '');
		$this->db->join($this->pos_office.' b','a.receiver = b.code');
		$this->db->join($this->pos_office.' c','a.sender = c.code');
		if(!empty($where)){
			$where_text	= implode(' and ', $where);
			$this->db->where($where_text, null, false);
		}
		return $this->db->get($this->product.' x');
		*/
		return $this->db->query('call sp_report_dash_product("'.$date.'","'.$kpi.'","'.$reg_asal.'","'.$reg_tujuan.'")');
	}

	public function get_masalah_ticket($date = '', $where = array())
	{
		$this->db->select('count(a.auto_id) as total, i.name, b.auto_id as id,
		count(case when a.category_detail = "C" then a.auto_id end ) as collecting,
		count(case when a.category_detail = "P" then a.auto_id end ) as processing,
		count(case when a.category_detail = "T" then a.auto_id end ) as transporting,
		count(case when a.category_detail = "D" then a.auto_id end ) as delivery,
		count(case when a.category_detail = "R" then a.auto_id end ) as reporting', false);
	
		(($date != '') ? $this->db->where('year(a.date) = year("'.$date.'") and month(a.date) = month("'.$date.'")', null, false) : '');
		$this->db->join($this->pos_office.' b','a.receiver = b.code','left');
		$this->db->join($this->pos_office.' c','a.sender = c.code','left');
		$this->db->join($this->categories.' i','a.category = i.auto_id');
		$this->db->where('a.category between 6 and 16 ', null, false);
		$this->db->where('a.status',99);
		if(!empty($where)){
			$where_text	= implode(' and ', $where);
			$this->db->where($where_text, null, false);
		}

		$this->db->group_by('a.category');
		return $this->db->get($this->ticket.' a');
		

	}

	// Rekap Layanan
	public function get_rekap_layanan_info($start, $end, $kprk)
	{
		return $this->db->query('call sp_report_ticket_info("'.$start.'","'.$end.'","'.$kprk.'")');
	}

	public function get_rekap_layanan_pengaduan($start, $end, $kprk)
	{
		return $this->db->query('call sp_report_ticket_aduan("'.$start.'","'.$end.'","'.$kprk.'")');
	}

	public function get_rekap_layanan_aduan_masalah_produk($start, $end, $kprk)
	{
		return $this->db->query('call sp_report_ticket_masalah_produk("'.$start.'","'.$end.'","'.$kprk.'")');
	}

	// XRAY 
	public function get_xray_regional_asal_kirim($date)
	{
		return $this->db->query('call sp_report_xray_regional_origin("'.$date.'")');
	}
	public function get_xray_regional_tujuan_kirim($date, $regional_asal = '')
	{
		//,"'.$regional_asal.'")
		return $this->db->query('call sp_report_xray_regional_destination("'.$date.'","'.$regional_asal.'")');
	}

	public function get_xray_asal_kirim($date, $regional_asal = '', $regional_tujuan = '')
	{
		return $this->db->query('call sp_report_xray_origin("'.$date.'","'.$regional_asal.'","'.$regional_tujuan.'")');
	}

	public function get_xray_asal_terbangan($date, $regional_asal = '', $regional_tujuan = '', $kantor_asal = '')
	{
		return $this->db->query('call sp_report_xray_terbangan("'.$date.'","'.$regional_asal.'","'.$regional_tujuan.'","'.$kantor_asal.'")');
	}

	public function get_xray_tujuan_kirim($date,$regional_asal = '', $regional_tujuan = '', $kantor_asal = '')
	{
		return $this->db->query('call sp_report_xray_destination("'.$date.'","'.$regional_asal.'","'.$regional_tujuan.'","'.$kantor_asal.'")');
	}

	public function get_xray_harian($date)
	{
		return $this->db->query('call sp_report_xray_daily("'.$date.'")');
	}

	public function get_xray_item_name($date, $regional_asal = '', $regional_tujuan = '', $kantor_asal = "", $kantor_tujuan = "")
	{
		return $this->db->query('call sp_report_xray_item_name("'.$date.'", "'.$regional_asal.'","'.$regional_tujuan.'","'.$kantor_asal.'","'.$kantor_tujuan.'")');
	}
}