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
class User extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('muser');
	}
	
	public function index()
	{
		if($this->session->userdata('is_admin') == 1){
			$this->userList();
		}else{
			//echo $this->session->userdata('ses_username');
			redirect('user/profile/'.$this->session->userdata('ses_cid'), 301);
			//redirect(site_url('user/profile/'.$this->session->userdata('ses_username')), 301);
		}
	}

	public function active()
	{
		$data['content']	= $this->load->view('active','',true);
		$this->load->view('page',$data);
	}

	public function activeUser()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$date_start		= $this->input->post('start', true) ? SetDateFormatFromID($this->input->post('start', true),'Y-m-d') : date('Y-m-d');
		$date_end		= $this->input->post('end', true) ? SetDateFormatFromID($this->input->post('end', true),'Y-m-d') : date('Y-m-d');

		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		$regional 		= $this->input->post('regional', true) ? $this->input->post('regional') : '';
		$role 			= $this->input->post('role') ? $this->input->post('role') : '';
		
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		
		$query 			= $this->muser->DataListUserActive($search, $regional, $role, $row_show, $row_start, $sort, $order);
		
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
	
	public function userList()
	{
		$data['content']	= $this->load->view('list','',true);
		$this->load->view('page',$data);
	}
	
	public function edit()
	{
		$data['content']	= $this->load->view('profile','',true);
		$this->load->view('page',$data);
	}
	
	public function dgDataListUser()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$date_start		= $this->input->post('start', true) ? SetDateFormatFromID($this->input->post('start', true),'Y-m-d') : date('Y-m-d');
		$date_end		= $this->input->post('end', true) ? SetDateFormatFromID($this->input->post('end', true),'Y-m-d') : date('Y-m-d');

		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		$regional 		= $this->input->post('regional', true) ? $this->input->post('regional') : '';
		$role 			= $this->input->post('role') ? $this->input->post('role') : '';
		
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		
		$query 			= $this->muser->DataListUser($search, $regional, $role, $row_show, $row_start, $sort, $order);
		
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
	
	public function profile()
	{
		$uri				= $this->uri->segment(3);
		$tid 				= $this->muser->checkUser('id',$uri);
		
		if($tid == "0"){
			
			if($this->session->userdata('is_admin') == 1){
				redirect(site_url('user/create'), 301);
			}else{
				redirect(site_url('user/profile/'.$this->session->userdata('ses_cid')), 301);
			}
		}else{
			$data['content']	= $this->load->view('profile','',true);
			$this->load->view('page',$data);
		}
	}
	
	public function create()
	{
		$data['content']	= $this->load->view('form','',true);
		$this->load->view('page',$data);
	}
	
	public function doSave()
	{	
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$fullname		= $this->input->post('fullname', true);
		$username		= $this->input->post('username', true);
		$password		= $this->input->post('password', true);
		$is_admin		= $this->input->post('is_admin') ? $this->input->post('is_admin') : 0;
		$role			= $this->input->post('role', true);
		$status			= $this->input->post('status', true);
		$email			= $this->input->post('email', true);
		$utype 			= $this->input->post('utype');
		$kantor_pos		= $this->input->post('kantor_pos') ? $this->input->post('kantor_pos') : 00000;
		
		$data			= array('title'=>$fullname,
								'username'=>$username,
								'password'=>md5($password),
								'is_admin'=>$is_admin,
								'email'=>$email,
								'user_hash'=>random_string('sha1'),
								'role_id'=>$role,
								'status'=>$status,
								'kantor_pos'=>$kantor_pos,
								'utype'=>$utype);
		$query			= $this->muser->doSave($data);
		if($query){
			$result	= array('status'=>true, 'message'=>'Berhasil menyimpan data.');
		}else{
			$result	= array('status'=>false, 'message'=>'Gagal menyimpan data.');
		}	
		echo json_encode($result);
	}
	
	public function update()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$uid 			= $this->input->post('uid', true);
		$username		= $this->input->post('username', true);
		$fullname		= $this->input->post('fullname', true);
		$role			= $this->input->post('role', true);
		$is_admin		= ($this->input->post('role', true) == 0) ? 1 : 0;
		$email			= $this->input->post('email', true);
		$status			= $this->input->post('status', true);
		$kantor_pos 	= $this->input->post('kantor_pos');
		$phone 			= $this->input->post('telepon');
		
		


		$data	= array('title'=>$fullname, 'is_admin'=>$is_admin, 'role_id'=>$role, 'status'=>$status, 'username'=>$username, 'email'=>$email, 'kantor_pos'=>$kantor_pos,'phone'=>$phone);
		if($this->input->post('epassword')){
			$data['password']	= md5($this->input->post('epassword'));
		}				
		$query	= $this->muser->saveUser($data, $uid);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error', 'msg'=>'Failed to save data.');
		}	
		echo json_encode($result);
	}
	
	public function delete()
	{
		$uid 	= $this->input->post('id', true);
		$query	= $this->muser->deleteUser($uid);
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
	
	
	public function check_username()
	{
		$username	= $this->input->post('username', true);
		$uid		= $this->input->post('uid', true);
		
		$user_id	= $this->muser->checkUser('username', $username);
		if($user_id != '')
		{
			if( $uid == $user_id ){
				echo 'true';
			}else{
				echo 'false';
			}
		}else{
			echo 'true';
		}
	}
	
	public function check_email()
	{
		$email		= $this->input->post('email', true);
		$uid		= $this->input->post('uid', true);
		
		$user_id	= $this->muser->checkUser('email', $email);
		if($user_id != '')
		{
			if( $uid == $user_id ){
				echo 'true';
			}else{
				echo 'false';
			}
		}else{
			echo 'true';
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */