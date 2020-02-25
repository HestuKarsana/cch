<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Authentication
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Auth extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->load->model('mauth');
	}
	
	public function index()
	{
		//$this->login();
		
	}
	
	public function login()
	{
		$this->load->view('login');
	}
	
	public function do_login()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$username	= $this->input->post('username', true);
		$password	= $this->input->post('password', true);
				
		$result		= array();
		
		$query		= $this->mauth->getUserLogin($username,md5($password));
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			
			$data	= array('ses_username'=>$row->username,
							'ses_role'=>$row->role,
							'ses_cid'=>$row->cid,
							'ses_fullname'=>$row->title,
							'ses_utype'=>$row->utype,
							'is_admin'=>$row->is_admin,
							'pos_office'=>$row->kantor_pos,
							'ses_login'=>TRUE);
			$this->session->set_userdata($data);
			$this->mauth->updateOnlineStatus($this->session->userdata('ses_username'), 1);
			$result		= array('status'=>true);
		}else{
			$data	= array('username'=>$username, 
							'password'=>$password, 
							'ip_address'=>$this->input->ip_address(),
							'user_agent'=>$this->input->user_agent()
							);
			$this->mauth->loginAttack($data);
			$result = array('status'=>false, 'message'=>'username or password not registered');
		}
		
		echo json_encode($result);
	}
	
	public function logout()
	{
		$this->mauth->updateOnlineStatus($this->session->userdata('ses_username'), 0);

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