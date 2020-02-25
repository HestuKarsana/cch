<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  
 *
 * @package		holiday Models
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Mholiday extends CI_Model{
	
	private $holiday				= 'holiday';
	private $notification 			= 'notification';
	
	public function get_event_on_date($id)
	{
		$select = 'a.id as uid, a.title, a.description as detail, date(a.date_end) as date_end, date(a.date_start) as date_start';
		$this->db->select($select, false);
		$this->db->where('a.id', $id);
		return $this->db->get($this->holiday.' a');
	}
	public function get_holiday_data($id)
	{
		$select = 'a.id as uid, a.title, a.description as detail, date(a.date_end) as date_end, date(a.date_start) as date_start';
		$this->db->select($select, false);
		$this->db->where('a.id', $id);
		return $this->db->get($this->holiday.' a');
	}
	
	public function getData($search = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.title, a.description, a.date_start as start, a.date_end as end, date(a.date_start) as dstart, date(a.date_end) as dend';
		$this->db->select($select, false);		
		( ($search != '') ? $this->db->like('a.description', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.description', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		(($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.description', 'desc'));
		#$this->db->join($this->ticket_categories.' b', 'a.categories = b.id','left');
		return $this->db->get($this->holiday.' a');
	}
	
	public function getDataTotal($search = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->like('a.description', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.holiday_content', $search, 'both') : '');
		#$this->db->join($this->ticket_categories.' b', 'a.categories = b.id','left');
		#$this->db->group_by('a.id');
		$query	= $this->db->get($this->holiday.' a');
		$row 	= $query->row();
		return $row->total;
	}

	public function save_data($data, $key = '', $id = '')
	{
		if($key != ''){
			$this->db->where('id', $key);
			return $this->db->update($this->holiday, $data);
		}else{
			$this->db->set('id', $id);
			//$this->db->set('create_date', 'NOW()', false);
			return $this->db->insert($this->holiday, $data);
		}
	}
	
	public function delete_data($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->holiday);
		return $this->db->affected_rows();
	}
	
	public function holidayData($search = '')
	{
		$select = 'a.id, a.name, a.holiday_content as tpl_name, a.categories, a.username, a.create_date, a.status';
		$this->db->select($select, false);
		$this->db->where('a.status', 1);
		return $this->db->get($this->holiday.' a');
	}

	public function save_notification($data, $id)
	{
		$this->db->set('id', $id);
		$this->db->insert($this->notification, $data);
		return $this->db->affected_rows();
	}
}