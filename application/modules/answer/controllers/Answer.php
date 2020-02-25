<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Contacts
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Answer extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('manswer');
	}
	
	public function index()
	{
		$data['content']	= $this->load->view('index','',true);
		$this->load->view('page',$data);
	}
	
	
	
	
	public function getFAQ()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$key			= $this->input->post('q', true);
		$categories		= $this->input->post('category', true);
		$value['total']	= array();
		$value['rows']	= array();
		$query	= $this->manswer->getFAQData($key, $categories);
		if($query->num_rows() > 0)
		{
			$value['total']	= $query->num_rows();
			foreach($query->result() as $row)
			{
				$value['rows'][]	= array('answer'=>$row->answer, 'question'=>nl2br($row->question), 'last_update'=>$row->last_update, 'faq_id'=>$row->uniq_id);
			}
		}else{
			$value['msg']	= 'Not have result for F.A.Q';
		}
		
		echo json_encode($value);
	}
	
	public function getFAQDetail()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$faq_id		= $this->input->post('faqid', true);
		$query		= $this->manswer->getDetailFAQ($faq_id);
		if($query->num_rows() > 0)
		{
			$row		= $query->row();
		}else{
			$row		= array('question'=>'',
								'answer'=>'',
								'categories'=>'',
								'status'=>'');
		}
		echo json_encode($row);
	}
	
	public function remove_categories()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$id			= $this->input->post('id', true);
		$query		= $this->manswer->removeCategories($id);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error');
		}
		
		echo json_encode($result);
	}
	
	public function create_Categories()
	{
		$id			= $this->input->post('id', true);
		$name		= $this->input->post('name', true);
		$status		= $this->input->post('status', true);
		
		$data		= array('name'=>$name, 'status'=>1, 'module'=>'FAQ');
		
		$query		= $this->manswer->saveCategories($data, $id);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error');
		}
		
		echo json_encode($result);
	}
	
	/* Admin Access */
	public function datalist()
	{
		$data['content']	= $this->load->view('datalist','',true);
		$this->load->view('page',$data);
	}
	
	public function getDataList()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->manswer->getTotalListFAQ($search);
		$query 			= $this->manswer->getListFAQ($search, $row_show, $row_start, $sort, $order);
		if($query->num_rows() > 0)
		{
			$rows['total'] 	= $total;
			foreach($query->result() as $row)
			{
				array_push($rows['rows'],$row);
			}
		}
		echo json_encode($rows);
	}
	
	public function edit()
	{
		$content['page_title']	= $this->uri->segment(3) == '' ? 'Create F.A.Q' : 'Edit F.A.Q';
		$id						= $this->uri->segment(3);
		$query					= $this->manswer->getDetailFAQ($id);
		if($query->num_rows() > 0)
		{
			$content['row']		= $query->row();
			$data['content']	= $this->load->view('form',$content,true);
			$this->load->view('page',$data);
		}else{
			redirect('answer/create',301);
		}
		
	}
	
	public function create()
	{
		$content['page_title']	= $this->uri->segment(3) == '' ? 'Create F.A.Q' : 'Edit F.A.Q';
		
		$content['row'] 	= (object) array('question' => '', 'answer' => '', 'uniq_id'=>'');
		
		$data['content']	= $this->load->view('form',$content,true);
		$this->load->view('page',$data);
	}
	
	public function uploader()
	{
		$this->load->library('upload');
		
		$pathinfo		= $this->config->item('path_faq');
		$config['upload_path'] 		= $pathinfo;
		$config['allowed_types'] 	= 'gif|jpg|png';
		$config['encrypt_name'] 	= true;
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('file'))
		{
				$error = $this->upload->display_errors();
				$result	= array('status'=>'Error', 'msg'=>$error);
	
		}else{
				$upload	= $this->upload->data();
				$media	= array('orig_name'=>$upload['orig_name'],
								'file_name'=>$upload['file_name'],
								'file_type'=>$upload['file_type'],
								'file_path'=>$upload['file_path'],
								'file_size'=>$upload['file_size'],
								'file_ext'=>strtolower($upload['file_ext']),
								'is_image'=>$upload['is_image']);
				$this->manswer->saveMedia($media);
				$result	= array('status'=>'OK', 'url'=>base_url($pathinfo).$upload['file_name']);
		}
		
		echo json_encode($result);
	}
	
	
	
	
	public function save()
	{
		$this->load->helper('string');
		
		$cid					= $this->input->post('uid',true);
		
		if($this->input->post('question', true)){
			$data['question']	= $this->input->post('question', true);
		}
		
		if($this->input->post('answer', true)){
			$data['answer']	= $this->input->post('answer', true);
		}
		
		if($this->input->post('categories', true)){
			$data['categories']	= $this->input->post('categories', true);
		}
		
		if($this->input->post('status', true)){
			$data['status']	= $this->input->post('status', true);
		}
		
		$this->db->trans_begin();
		
		$result	= array();
		$query		= $this->manswer->saveData($data, $cid);
		$last_id	= ($cid != '') ? $cid : $this->db->insert_id();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Saving data failed');
		}else{
			$this->db->trans_commit();
			$result	= array('status'=>'OK', 'msg'=>'Saving data success');
		}
		
		echo json_encode($result);
		
	}
	
	public function detail()
	{
		$id		= $this->uri->segment(3);
		$query	= $this->manswer->getDetailFAQ($id);
		
			
		if (!$this->input->is_ajax_request())
		{
			if($query->num_rows() > 0)
			{
				$content['row']		= $query->row();
				$data['content']	= $this->load->view('detail',$content,true);
				$this->load->view('page',$data);
			}
		}else{
			$content['row']			= $query->row();
			$this->load->view('detail_ajax', $content);
		}
		/*
		
		if($query->num_rows() > 0)
		{	
			$content['row']	= $query->row();
			
			if (!$this->input->is_ajax_request())
			{
				$this->load->view('detail_ajax', $content);
			}else{
				#$content['page_title']	= $this->uri->segment(3) == '' ? 'Create F.A.Q' : 'Edit F.A.Q';
				$data['content']	= $this->load->view('detail',$content,true);
				$this->load->view('page',$data);
			}
		}else{
			show_404();
		}
		*/
	}

	public function delete()
	{
		$id 		= $this->input->post('id', false);
		
		$query		= $this->manswer->deleteData($id);
		$result	= array();
		$this->db->trans_begin();
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Saving data failed');
		}else{
			$this->db->trans_commit();
			$result	= array('status'=>'OK', 'msg'=>'Saving data success');
		}
		echo json_encode($result);
	}
	
	public function getFAQCategories()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$value	= array();
		$query	= $this->manswer->getCategories('faq');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		echo json_encode($value);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */