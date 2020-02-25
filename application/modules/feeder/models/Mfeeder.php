<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  
 *
 * @package		Feeder Models
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Mfeeder extends CI_Model{
	
	private $xray 			= 'xray';
	private $ticket 		= 'ticket';
	private $feeder			= 'feeder';

	public function save_data($data)
	{
		$this->db->set('id', random_string('sha1'));
		$this->db->insert($this->feeder, $data);	
		return $this->db->affected_rows();
	}

	public function get_data($search = array(), $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.tgl_upload, a.marketplace, a.awb, a.status_upload, a.user_upload, a.no_pesanan, a.penerima, a.penerima_alamat, a.penerima_telp, a.pengirim, a.pengirim_alamat, a.pengirim_telp, b.isi_kiriman, c.id as ticket_id, c.no_ticket, c.status';
		$this->db->select($select, false);
		//$this->db->where('a.id != ',"");
		//( ($search != '') ? $this->db->where('a.awb like "%'.$search.'%" or a.no_pesanan like "%'.$search.'%"', null, false) : '');
		if(count($search) > 0)
		{
			for($i = 0; $i<count($search); $i++)
			{
				if($i > 0){
					$this->db->or_where('a.awb like "%'.$search[$i].'%" or a.no_pesanan like "%'.$search[$i].'%"', null, false);
				}else{
					$this->db->where('a.awb like "%'.$search[$i].'%" or a.no_pesanan like "%'.$search[$i].'%"', null, false);
				}
				
			}
		}
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		( ($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.auto_id', 'desc'));
		$this->db->join($this->xray.' b','a.awb = b.id_kiriman','left');
		$this->db->join($this->ticket.' c','a.awb = c.awb','left');
		return $this->db->get($this->feeder.' a');
	}

	
	public function get_total_data($search = array(), $cust = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		//$this->db->where('a.id != ',"");
		if(count($search) > 0)
		{
			for($i = 0; $i<count($search); $i++)
			{
				if($i > 0){
					$this->db->or_where('a.awb like "%'.$search[$i].'%" or a.no_pesanan like "%'.$search[$i].'%"', null, false);
				}else{
					$this->db->where('a.awb like "%'.$search[$i].'%" or a.no_pesanan like "%'.$search[$i].'%"', null, false);
				}
			}
		}
		//( ($search != '') ? $this->db->where('a.awb like "%'.$search.'%" or a.no_pesanan like "%'.$search.'%"', null, false) : '');
		$this->db->join($this->xray.' b','a.awb = b.id_kiriman','left');
		$this->db->join($this->ticket.' c','a.awb = c.awb','left');
		$query	= $this->db->get($this->feeder.' a');
		$row 	= $query->row();
		return $row->total;
	}
	
	public function checkFeederData($awb)
	{
		$value 	= 0;
		$this->db->select('a.auto_id', false);
		$this->db->where('a.awb', $awb);
		$query 	= $this->db->get($this->feeder.' a');
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= $row->auto_id;
		}
		return $value;
	}

	// REPORT 
	public function get_marketplace_upload($start, $end, $marketplace = '')
	{
		return $this->db->query('call sp_feeder_dashboard("'.$start.'","'.$end.'","'.$marketplace.'");');
	}

	public function get_marketplace_upload_daily($start, $marketplace = '')
	{
		return $this->db->query('call sp_feeder_upload_daily("'.$start.'","'.$marketplace.'");');
	}
}