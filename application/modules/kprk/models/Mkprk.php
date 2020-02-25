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
class Mkprk extends CI_Model{
	
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
	private $kantor_pos 	= 'pos_office';


	// KPRK
	public function get_data($search = '', $product = '', $row_show, $row_start, $sort, $order)
	{
		$select 	= "a.id, a.code, a.name, a.regional, count(b.id) as total_ticket_keluar, count(c.id) as total_ticket_masuk";
		
		$this->db->select($select, false);
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		( ($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.auto_id', 'asc'));
		( ($search != '') ? $this->db->where('a.code like "%'.$search.'%" or a.name like "%'.$search.'%" or a.regional like "%'.$search.'%"', null, false) : '');
		//( ($product != '') ? $this->db->where('a.jenis_layanan', $product) : '');
		$this->db->join($this->ticket.' b', 'a.code = b.complaint_origin','left');
		$this->db->join($this->ticket.' c', 'a.code = c.tujuan_pengaduan','left');
		$this->db->group_by('a.code');
		return $this->db->get($this->kantor_pos.' a');
	}

	
	public function get_total_data($search = '', $product = '')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->where('a.code like "%'.$search.'%" or a.name like "%'.$search.'%" or a.regional like "%'.$search.'%"', null, false) : '');
		$this->db->join($this->ticket.' b', 'a.code = b.complaint_origin','left');
		$this->db->join($this->ticket.' c', 'a.code = c.tujuan_pengaduan','left');
		$query	= $this->db->get($this->kantor_pos.' a');
		$row 	= $query->row();
		return $row->total;
	}

	public function get_kantor_pos($uid)
	{
		$this->db->select('code, name, fullname, regional, id', false);
		$this->db->where('code', $uid);
		return $this->db->get($this->kantor_pos);
	}


	public function get_ticket_inout($code, $start = '', $end = '')
	{
		$sql = 'count( case when a.complaint_origin = "'.$code.'" then a.id end) as ticket_keluar,
		count( case when a.tujuan_pengaduan = "'.$code.'" then a.id end) as ticket_masuk';
		$this->db->select($sql, false);
		(($end != '') ? $this->db->where('date(a.date) between "'.$start.'" and "'.$end.'" ',null, false) : '');
		return $this->db->get($this->ticket.' a');
		
	}
	// END KPRK

}