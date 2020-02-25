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
class Mcategories extends CI_Model{
	
	private $ticket						= 'ticket';
	private $ticket_categories			= 'ticket_category';
	
	public function DataListCategories($search = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.code, a.name, a.param, a.email, a.status, count(b.id) as total_ticket';
		$this->db->select($select, false);		
		( ($search != '') ? $this->db->like('a.name', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		( ($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.name', 'asc') );
		$this->db->where('a.status >=',0);
		$this->db->join($this->ticket.' b','a.id = b.category','left');
		$this->db->group_by('a.id');
		$this->db->group_by('b.category');
		return $this->db->get($this->ticket_categories.' a');
	}
	
	public function doSave($data, $id)
	{
		if($id > 0)
		{
			$this->db->where('id', $id);
			return $this->db->update($this->role, $data);
		}else{
			return $this->db->insert($this->role, $data);
		}
	}
	
	public function saveUser($data, $key = '')
	{
		if($key != ''){
			$this->db->where('id', $key);
			return $this->db->update($this->user, $data);
		}else{
			$this->db->set('id', random_string('sha1'));
			return $this->db->insert($this->user, $data);
		}
		
	}
	
	public function deleteUser($uid)
	{
		$this->db->set('status',-1);
		$this->db->where('id', $uid);
		return $this->db->update($this->role);
	}
	
	public function datalist($search)
	{
		$select	= '*';
		$this->db->select($select, false);		
		$this->db->where('status >=', 0);
		(($search != '') ? $this->db->like('name', $search, 'both') : '');
		return $this->db->get($this->user);
	}
	
	public function checkUserExists($uid, $password)
	{
		$result	= false;
		$this->db->select('id', false);
		$this->db->where('username', $uid);
		$this->db->where('password', $password);
		$query	= $this->db->get($this->user);
		if($query->num_rows() > 0)
		{
			$result	= true;
		}
		return $result;
	}
	
	public function changePassword($uid, $password)
	{
		$this->db->set('password', $password);
		$this->db->where('username', $uid);
		return $this->db->update($this->user);
	}
	
}