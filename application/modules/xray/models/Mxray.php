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
class Mxray extends CI_Model{
	
	private $xray 			= 'xray';

	public function save_data($data)
	{
		$this->db->set('id', random_string('sha1'));
		$this->db->set('tgl_input', 'NOW()', false);
		$this->db->insert($this->xray, $data);	
		return $this->db->affected_rows();
	}

	public function get_xray_data($search = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id_kiriman, a.kode_kantor_aduan, a.kantor_aduan, a.kode_kantor_asal, a.kantor_asal, a.kode_kantor_tujuan, a.kantor_tujuan, a.isi_kiriman, a.keterangan, a.tgl_input, a.kantong_lama, a.kantong_baru, a.berat, a.user_cch, date_format(a.tgl_input, "%d/%m/%Y") as tgl_input_id';
		$this->db->select($select, false);
		( ($search != '') ? $this->db->where('a.id_kiriman like "%'.$search.'%" or a.kantong_lama like "%'.$search.'%" or a.kantong_baru like "%'.$search.'%"', null, false) : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		( ($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.tgl_input', 'desc'));
		#$this->db->join($this->channel.' b','a.channel = b.auto_id','left');
		#$this->db->join($this->cust_category.' c','a.type_request = c.auto_id','left');
		return $this->db->get($this->xray.' a');
	}

	
	public function get_xray_total_data($search = '', $cust = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		#( ($search != '') ? $this->db->like('a.name', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.phone_number', $search, 'both') : '');
		( ($search != '') ? $this->db->where('a.id_kiriman like "%'.$search.'%" or a.kantong_lama like "%'.$search.'%" or a.kantong_baru like "%'.$search.'%"', null, false) : '');
		$query	= $this->db->get($this->xray.' a');
		$row 	= $query->row();
		return $row->total;
	}
	
	// check xray 
	public function checkXrayData($idkirim, $kta, $kttn, $kantong_baru)
	{
		$value 	= 0;
		if($kantong_baru != "-")
		{
			$this->db->select('auto_id', false);
			$this->db->where('id_kiriman', $idkirim);
			$this->db->where('kantor_asal', $kta);
			$this->db->where('kantor_tujuan', $kttn);
			//$this->db->where('kantong_baru', $kantong_baru);
			$query 	= $this->db->get($this->xray);
			if($query->num_rows() > 0)
			{
				$row 	= $query->row();
				$value 	= $row->auto_id;
			}
		}	
		return $value;
	}
}