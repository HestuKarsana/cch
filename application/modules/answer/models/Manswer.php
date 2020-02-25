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
class Manswer extends CI_Model{
	
	private $faq		= 'faq';
	private $media		= 'media';
	private $categories	= 'categories';
	
	
	public function getFAQData($search = '', $categories = 0)
	{
		$this->db->select('question,answer,categories,subcategories,last_update,uniq_id',false);
		(($search != '') ? $this->db->like('question', $search, 'both') : '');
		(($categories > 0) ? $this->db->where('categories', $categories) : '');
		$this->db->where('status', 1);
		return $this->db->get($this->faq);
	}
	
	public function getListFAQ($search = "", $row_show, $row_start, $sort, $order)
	{
		$select	= "a.uniq_id, a.categories, a.subcategories, a.question, a.answer, a.username, a.last_update, b.name as categories_name";
		$this->db->select($select, false);
		(($search != "") ? $this->db->like('a.question', $search, 'both') : '');
		(($search != "") ? $this->db->or_like('a.answer', $search, 'both') : '');	
		$this->db->where('a.status', 1);
		$this->db->join($this->categories.' b','a.categories = b.id', 'left');
		return $this->db->get($this->faq.' a');
	}
	
	public function getTotalListFAQ($search)
	{
		$this->db->select('count(a.id) as total', false);
		$this->db->where('a.status', 1);
		(($search != "") ? $this->db->or_like('a.question', $search, 'both') : '');
		(($search != "") ? $this->db->like('a.answer', $search, 'both') : '');
		$query	= $this->db->get($this->faq.' a');
		$row	= $query->row();
		return $row->total;
	}
	
	public function saveData($data, $key = '')
	{
		if($key != ''){
			$this->db->where('uniq_id', $key);
			return $this->db->update($this->faq, $data);
		}else{
			$this->db->set('uniq_id', random_string('sha1'));
			return $this->db->insert($this->faq, $data);
		}
	}
	
	public function saveMedia($data)
	{
		return $this->db->insert($this->media, $data);
	}
	
	public function deleteData($id)
	{
		$this->db->where('uniq_id', $id);
		return $this->db->delete($this->faq);
	}
	
	public function getDetailFAQ($id)
	{
		$this->db->select('a.id, a.uniq_id, a.categories, a.subcategories, a.question, a.answer, a.last_update, a.status, b.name as categories_name', false);
		$this->db->where('a.uniq_id', $id);
		$this->db->join($this->categories.' b','a.categories = b.id','left');
		return $this->db->get($this->faq.' a');
	}
	
	public function removeCategories($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->categories);
	}
	
	public function saveCategories($data, $id = '')
	{
		if($id != '')
		{
			$this->db->where('id', $id);
			return $this->db->update($this->categories, $data);
		}else{
			return $this->db->insert($this->categories, $data);
		}
		
	}
	public function getCategories($module = '')
	{
		$select = 'a.*, count(b.id) as total_data';
		$this->db->select($select, false);
		(($module != '') ? $this->db->where('a.module', $module) : '');
		$this->db->join($this->faq.' b','a.id = b.categories','LEFT');
		$this->db->where('a.status', 1);
		$this->db->order_by('a.name', 'asc');
		$this->db->group_by('a.id');
		$this->db->group_by('b.categories');
		return $this->db->get($this->categories.' a');
	}
}