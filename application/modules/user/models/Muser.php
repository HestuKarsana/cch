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
class Muser extends CI_Model{
	
	private $user			= 'sys_user';
	private $role			= 'sys_role';
	private $pos_office 	= 'pos_office';

	public function DataListUser($search = '', $regional = '', $role = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.title, a.username, a.user_hash, a.email, a.system_id, a.is_admin, a.role_id, a.phone, a.status, b.name as role_name, c.regional, c.fullname as kantor_pos';
		$this->db->select($select, false);		
		#( ($search != '') ? $this->db->like('a.title', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.username', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');

		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		(($search !=  '') ? $this->db->where('a.title like "%'.$search.'%" or a.username like "%'.$search.'%" or a.email like "%'.$search.'%"') : '');
		(($regional != '') ? $this->db->where('c.regional', $regional) : '');
		(($role != '') ? $this->db->where('a.role_id', $role) : '');
		(($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.title', 'asc'));
		$this->db->where('a.status >=',0);
		$this->db->join($this->role.' b','a.role_id = b.id');
		$this->db->join($this->pos_office.' c','a.kantor_pos = c.code','left');
		return $this->db->get($this->user.' a');
	}

	public function DataListUserActive($search = '', $regional = '', $role = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.title, a.username, a.user_hash, a.email, a.system_id, a.is_admin, a.role_id, a.phone, a.status, b.name as role_name, c.regional, c.fullname as kantor_pos';
		$this->db->select($select, false);		
		#( ($search != '') ? $this->db->like('a.title', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.username', $search, 'both') : '');
		#( ($search != '') ? $this->db->or_like('a.email', $search, 'both') : '');
		$this->db->where('a.is_online', 1);
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		(($search !=  '') ? $this->db->where('a.title like "%'.$search.'%" or a.username like "%'.$search.'%" or a.email like "%'.$search.'%"') : '');
		(($regional != '') ? $this->db->where('c.regional', $regional) : '');
		(($role != '') ? $this->db->where('a.role_id', $role) : '');
		(($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.title', 'asc'));
		$this->db->where('a.status >=',0);
		$this->db->join($this->role.' b','a.role_id = b.id');
		$this->db->join($this->pos_office.' c','a.kantor_pos = c.code','left');
		return $this->db->get($this->user.' a');
	}

	
	
	public function checkUser($field, $value)
	{
		$this->db->select('id', false);
		$this->db->where($field, $value);
		$query	= $this->db->get($this->user);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= $row->id;
		}else{
			$value	= '';
		}
	
		return $value;
	}
	
	public function doSave($data)
	{
		$this->db->set('id', random_string('sha1'));
		return $this->db->insert($this->user, $data);
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
		return $this->db->update($this->user);
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