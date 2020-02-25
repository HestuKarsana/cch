<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  
 *
 * @package		Globals Models
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Mgeneral extends CI_Model{
	
	private $categories		= 'categories';
	private $contacts		= 'contacts';
	private $contacts_type	= 'contacts_type';
	private $area			= 'area';
	
	private $user 			= 'sys_user';

	public function get_user($select = "a.title, a.username", $search = '')
	{
		$this->db->select($select, false);
		(($search != '') ? $this->db->like('a.title', $search) : '');
		$this->db->where('a.status', 1);
		return $this->db->get($this->user.' a');
	}

	public function removeCategories($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->categories);
	}
	
	public function updateCategoriesName($value, $id)
	{
		$this->db->set('name', $value);
		$this->db->where('id', $id);
		return $this->db->update($this->categories);
	}
	
	/* contact */
	public function getContactDetail($uid)
	{
		$select	= 'a.id, a.name, a.address, a.delivery_address, a.phone_number, a.email, a.last_update, a.status, a.propinsi, a.kota, a.kecamatan, a.kelurahan, a.type_customer, a.parent_customer, a.patokan, a.propinsi, a.kota, a.kecamatan, a.kelurahan, b.name AS propinsi_name, c.name AS kota_name, d.name AS kecamatan_name, e.name AS kelurahan_name';
		$this->db->select($select, false);
		$this->db->where('a.id', $uid);
		$this->db->join($this->area.' b','a.propinsi = b.idprov and b.level = 1','LEFT');
		$this->db->join($this->area.' c','a.kota = c.idkot AND a.propinsi = c.idprov AND c.level = 2','LEFT');
		$this->db->join($this->area.' d','a.kecamatan = d.idkec AND a.propinsi = d.idprov AND a.kota = d.idkot AND d.level = 3','LEFT');
		$this->db->join($this->area.' e','a.kelurahan = e.idkel AND a.propinsi = e.idprov AND a.kota = e.idkot AND a.kecamatan = e.idkec AND e.level = 4','LEFT');
		
		return $this->db->get($this->contacts.' a');
	}
	
	/* Area */
	public function getArea($level = 0)
	{
		$this->db->select('id, idprov, idkot, idkec, idkel, name, level', false);
		(($level > 0 ) ? $this->db->where('level', $level) : '');
		return $this->db->get($this->area);
	}
	
	public function getProvince()
	{
		$this->db->select('idprov as id, idkot, idkec, idkel, name, level', false);
		$this->db->where('level',1);
		return $this->db->get($this->area);
	}
	
	public function getCity($key = 0)
	{
		$this->db->select('idprov, idkot as id, idkec, idkel, name, level', false);
		$this->db->where('level',2);
		(($key > 0) ? $this->db->where('idprov', $key) : ''); 
		return $this->db->get($this->area);
	}
	
	public function getKecamatan($prov, $city)
	{
		$this->db->select('id, idprov, idkot, idkec as id, idkel, name, level', false);
		$this->db->where('level',3);
		$this->db->where('idprov', $prov);
		$this->db->where('idkot', $city);
		return $this->db->get($this->area);
	}
	
	public function getKelurahan($prov, $city, $kec)
	{
		$this->db->select('idprov, idkot, idkec, idkel as id, name, level', false);
		$this->db->where('level',4);
		$this->db->where('idprov', $prov);
		$this->db->where('idkot', $city);
		$this->db->where('idkec', $kec);
		return $this->db->get($this->area);
	}
}