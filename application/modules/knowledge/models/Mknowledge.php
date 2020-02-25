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
class Mknowledge extends CI_Model{
	
	private $knowledge			= 'kbase';
	private $categories	= 'categories';
	
	public function getKnowledge($search = '', $row_show, $row_start, $sort, $order)
	{
		$select	= 'a.id, a.uniq_id, a.categories, a.subcategories, a.title, a.detail, a.status, a.username, a.last_update, a.tags, b.name as categories_name';
		$this->db->select($select, false);		
		( ($search != '') ? $this->db->like('a.title', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.detail', $search, 'both') : '');
		( ($row_show != '') ? $this->db->limit($row_show,$row_start) : '');
		(($sort != '' ) ? $this->db->order_by($sort, $order) : $this->db->order_by('a.last_update', 'desc'));
		$this->db->join($this->categories.' b','a.categories = b.id','LEFT');
		return $this->db->get($this->knowledge.' a');
	}
	
	public function getKnowledgeTotal($search = '', $date ='')
	{
		$this->db->select('count(a.id) as total', false);
		( ($search != '') ? $this->db->like('a.title', $search, 'both') : '');
		( ($search != '') ? $this->db->or_like('a.detail', $search, 'both') : '');
		$query	= $this->db->get($this->knowledge.' a');
		$row 	= $query->row();
		return $row->total;
	}
	
	public function getDetail($id)
	{
		$this->db->select('id, uniq_id, categories, subcategories, title, detail, status, username, last_update, tags', false);
		$this->db->where('uniq_id', $id);
		return $this->db->get($this->knowledge);
	}
	
	public function getKnowledgeCategory($id = 0)
	{
		$this->db->select('id, parent_id, name, status', false);
		$this->db->where('parent_id',$id);
		return $this->db->get($this->categories);
	}
	
	public function getCategories($module = 'kbase')
	{
		$select = 'a.*, count(b.id) as total_data';
		$this->db->select($select, false);
		(($module != '') ? $this->db->where('a.module', $module) : '');
		$this->db->join($this->knowledge.' b','a.id = b.categories','LEFT');
		$this->db->where('a.status', 1);
		$this->db->order_by('a.name', 'asc');
		$this->db->group_by('a.id');
		$this->db->group_by('b.categories');
		return $this->db->get($this->categories.' a');
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
	
	public function saveKbase($data, $id = '')
	{
		if($id != '')
		{
			$this->db->where('uniq_id', $id);
			return 	$this->db->update($this->knowledge, $data);
		}else{
			$this->db->set('uniq_id', random_string('sha1'));
			return 	$this->db->insert($this->knowledge, $data);
		}
	}
	
	public function getKnowledgeIndex($parent_id = 0)
	{
		$this->db->select('a.id , a.parent_id, a.name, a.status, b.name AS parent_name, COUNT(c.id) AS total', false);
		$this->db->where('a.status', 1);
		$this->db->where('a.module', 'kbase');
		#$this->db->where('a.parent_id >', $parent_id);
		$this->db->join($this->categories.' b','a.parent_id = b.id','LEFT');
		$this->db->join($this->knowledge.' c','a.id = c.categories','LEFT');
		$this->db->group_by('a.id');
		$this->db->group_by('c.categories');
		return $this->db->get($this->categories.' a');
	}
	
	public function getKbaseByCategories($categories)
	{
		$this->db->select('id, uniq_id, categories, subcategories, title, detail, status, username, last_update, tags', false);
		$this->db->where('categories', $categories);
		return $this->db->get($this->knowledge);
	}
	
	public function getRecentPost($limit = 5, $without = '')
	{
		$this->db->select('id, uniq_id, categories, title', false);
		$this->db->order_by('id','desc');
		$this->db->where('status',1);
		$this->db->limit($limit);
		(($without != '') ? $this->db->where_not_in('uniq_id', $without)  : '');
		return $this->db->get($this->knowledge);
	}
	
	public function deleteData($uniq_id)
	{
		$this->db->where('uniq_id', $uniq_id);
		return $this->db->delete($this->knowledge);
	}
}