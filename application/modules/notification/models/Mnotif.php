<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  
 *
 * @package		Notification Models
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Mnotif extends CI_Model{
	
	private $notification				= 'notification';
	
	public function get_notification_data($id)
	{
		$select = 'a.id as uid, a.title, a.description as detail, a.event_date';
		$this->db->select($select, false);
		$this->db->where('a.id', $id);
		return $this->db->get($this->notification.' a');
	}
	
	public function getData($search = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.title, a.description, a.event_date';
		$this->db->select($select, false);		
		( ($search != '') ? $this->db->like('a.description', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.description', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		(($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.event_date', 'desc'));
		#$this->db->join($this->ticket_categories.' b', 'a.categories = b.id','left');
		return $this->db->get($this->notification.' a');
	}
	
	public function getDataTotal($search = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->like('a.description', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.notification_content', $search, 'both') : '');
		#$this->db->join($this->ticket_categories.' b', 'a.categories = b.id','left');
		#$this->db->group_by('a.id');
		$query	= $this->db->get($this->notification.' a');
		$row 	= $query->row();
		return $row->total;
	}

	public function save_data($data, $key = '')
	{
		if($key != ''){
			$this->db->where('id', $key);
			return $this->db->update($this->notification, $data);
		}else{
			#$this->db->set('id', random_string('sha1'));
			#$this->db->set('create_date', 'NOW()', false);
			$batch_data 	= array($data);
			return $this->db->insert_batch($this->notification, $batch_data);
		}
	}
	
	public function delete_data($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->notification);
		return $this->db->affected_rows();
	}
	
	public function notificationData($search = '')
	{
		$select = 'a.id, a.name, a.notification_content as tpl_name, a.categories, a.username, a.create_date, a.status';
		$this->db->select($select, false);
		$this->db->where('a.status', 1);
		return $this->db->get($this->notification.' a');
	}
}