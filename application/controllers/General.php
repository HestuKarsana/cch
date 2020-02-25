<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Global App Controller
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class General extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mgeneral');
	}
	
	public function index(){
		show_404();
	}

	public function get_assignee_user()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$value 	= array();
		$query 	= $this->mgeneral->get_user();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value, $row);
			}
		}
		echo json_encode($value);
	}
	
	public function remove_categories()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$id			= $this->input->post('id', true);
		$query		= $this->mgeneral->removeCategories($id);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error');
		}
		
		echo json_encode($result);
	}
	
	public function update_categories()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$pk		= $this->input->post('pk', true);
		$value	= $this->input->post('value', true);
		$name	= $this->input->post('name', true);
		
		$query	= $this->mgeneral->updateCategoriesName($value, $pk);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error');
		}
		
		echo json_encode($result);
	}
	
	/* generate form ticket */
	public function generateForm()
	{
		$tpl		= $this->input->post('tpl');
		
		echo $this->load->view('ticket/'.$tpl,'',true);
		
	}
	
	/* get contact detail */
	public function getContactDetail()
	{
		$uid		= $this->input->post('uid', true);
		$value		= array();
		$query		= $this->mgeneral->getContactDetail($uid);
		if($query->num_rows() > 0)
		{
			$row		= $query->row();
			$value[]	= $row;
		}
		
		echo json_encode($value);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */