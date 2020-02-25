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
class Mauth extends CI_Model{
	
	private $t_user	= 'sys_user';
	private $t_role	= 'sys_role';
	
	public function getUserLogin($username,$password)
	{
		$this->db->select('a.title, a.username, a.id as cid, a.is_admin, a.status, a.kantor_pos, a.role_id as role, a.utype',FALSE);
		$this->db->where('a.username',$username);
		$this->db->where('a.password',$password);
		$this->db->where('a.status',1);
		return $this->db->get($this->t_user.' a');
	}
	public function updateOnlineStatus($username, $status)
	{
		$this->db->set('is_online', $status);
		$this->db->where('username', $username);
		$this->db->update($this->t_user);
		return $this->db->affected_rows();
	}
	public function loginAttack($data)
	{
		$this->db->insert('login_attack',$data);
	}
	
}