<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		User
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Categories extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mcategories');
	}
	
	public function index()
	{
		if($this->session->userdata('is_admin') == 1){
			$this->userList();
		}else{
			$this->load->view('access_deny');
		}
	}
	
	public function userList()
	{
		
		$data['content']	= $this->load->view('list','',true);
		$this->load->view('page',$data);
	}
	
	public function edit()
	{
		$data['content']	= $this->load->view('form','',true);
		$this->load->view('page',$data);
	}
	
	
	public function dgDataListCategories()
	{
		//if (!$this->input->is_ajax_request()) {
		//	exit('No direct script access allowed');
		//}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		
		$query 			= $this->mcategories->DataListCategories($search, $row_show, $row_start, $sort, $order);

		if($query->num_rows() > 0)
		{
			$rows['total'] 	= $query->num_rows();
			foreach($query->result() as $row)
			{
				array_push($rows['rows'],$row);
			}
		}
		echo json_encode($rows);
	}
	
	
	public function create()
	{
		$data['content']	= $this->load->view('form','',true);
		$this->load->view('page',$data);
	}
	
	public function doSave()
	{
		$this->load->helper('string');
		
		$id				= $this->input->post('uid', true) ? $this->input->post('uid', true) : 0;
		$name			= $this->input->post('name', true);
		$status			= $this->input->post('status', true);
		$menu			= $this->input->post('menulist', true);
		$menulist		= implode(',',$menu);
		
		$data			= array('name'=>$name, 'status'=>$status, 'menuaccess'=>$menulist);
		$query			= $this->mcategories->doSave($data, $id);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error', 'msg'=>'Failed to save data.');
		}	
		echo json_encode($result);
	}
	/*
	public function update()
	{
		$this->load->helper('string');
		
		$uid 			= $this->input->post('uid', true);
		$username		= $this->input->post('username', true);
		$fullname		= $this->input->post('fullname', true);
		$is_admin		= $this->input->post('is_admin', true) ? $this->input->post('is_admin', true) : 0;
		$status			= $this->input->post('status', true);
		
		$data	= array('title'=>$fullname, 'is_admin'=>$is_admin, 'status'=>$status, 'username'=>$username);
						
		$query	= $this->muser->saveUser($data, $uid);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error', 'msg'=>'Failed to save data.');
		}	
		echo json_encode($result);
	}
	*/
	public function delete()
	{
		$uid 	= $this->input->post('id', true);
		
		$query	= $this->mcategories->deleteUser($uid);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error', 'msg'=>'Failed to save data.');
		}	
		echo json_encode($result);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */