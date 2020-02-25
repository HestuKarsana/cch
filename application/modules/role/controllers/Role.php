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
class Role extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mrole');
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
	
	/* generate menu access */
	public function generateMenuAccess()
	{
		if (!$this->input->is_ajax_request()){
			exit('No direct script access allowed');
		}
		$roleMenu 	= $this->mrole->getMenuRole($this->input->post('role_id'));
		$menuAccess	= $this->mrole->getRoleID($this->session->userdata('ses_username'));
		$menu 		= $this->mglobals->NewListMenu($menuAccess,'checkbox',$roleMenu,'menu-list','');
		echo json_encode($menu);
	}
	
	public function dgDataListRole()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		
		$query 			= $this->mrole->DataListRole($search, $row_show, $row_start, $sort, $order);
		
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
		$query			= $this->mrole->doSave($data, $id);
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
		
		$query	= $this->mrole->deleteUser($uid);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error', 'msg'=>'Failed to save data.');
		}	
		echo json_encode($result);
	}
	
	public function changePassword()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$uid		= $this->input->post('uid', true);
		$old		= $this->input->post('old_password', true);
		$new		= $this->input->post('new_password', true);
		
		if($this->muser->checkUserExists($uid, md5($old))){
			$query	= $this->muser->changePassword($uid, md5($new));
			if($query){
				$result	= array('status'=>'OK');
			}else{
				$result	= array('status'=>'Error', 'msg'=>'Failed to save data.');
			}	
		}else{
			$result	= array('status'=>'Error', 'msg'=>'Password not match with account');
		}
		
		echo json_encode($result);
	}
	
	public function do_login()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$username	= $this->input->post('username', true);
		$password	= $this->input->post('password', true);
				
		$result		= array();
		
		$query		= $this->mauth->getUserLogin($username,$password);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			
			$data	= array('ses_username'=>$row->username,
							'ses_fullname'=>$row->title,
							'ses_login'=>TRUE);
			$this->session->set_userdata($data);
			$result		= array('status'=>'OK');
		}else{
			$data	= array('username'=>$username, 
							'password'=>$password, 
							'ip_address'=>$this->input->ip_address(),
							'user_agent'=>$this->input->user_agent()
							);
			$this->mauth->loginAttack($data);
			$result = array('status'=>'error', 'msg'=>'username or password not registered');
		}
		
		echo json_encode($result);
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(site_url());
	}
	
	public function getSessionLive()
	{
		$value	= array();
		if( ($this->session->userdata('ses_username') == "") or (!$this->session->userdata('ses_login')) )
		{
			$value	= array('session'=>FALSE);
		}else{
			$value	= array('session'=>TRUE);
		}
		echo json_encode($value);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */