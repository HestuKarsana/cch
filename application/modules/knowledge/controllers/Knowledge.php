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
class Knowledge extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mknowledge');
	}
	
	public function index()
	{
		$data['content']	= $this->load->view('index','',true);
		$this->load->view('page',$data);
	}
	
	public function index_kb()
	{
		$data['content']	= $this->load->view('index_kb','',true);
		$this->load->view('page',$data);
	}
	
	public function datalist()
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
		$total 			= $this->mknowledge->getKnowledgeTotal($search, $date);
		$query 			= $this->mknowledge->getKnowledge($search, $row_show, $row_start, $sort, $order);
		
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
	
	
	public function create()
	{
		$content['page_title']	= $this->uri->segment(3) == '' ? 'Create Product Knowledge' : 'Edit Product Knowledge';
		
		$data['content']	= $this->load->view('form',$content,true);
		$this->load->view('page',$data);
	}
	
	public function edit()
	{
		$this->form();
	}
	
	public function form()
	{
		$content['page_title']	= $this->uri->segment(3) == '' ? 'Create Product Knowledge' : 'Edit Product Knowledge';
		
		$data['content']	= $this->load->view('form',$content,true);
		$this->load->view('page',$data);
	}
	
	public function getKnowledgeDetail()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$id		= $this->input->post('uid', true);
		
		$value	= array();
		$query	= $this->mknowledge->getDetail($id);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= $row;
		}
		
		echo json_encode($value);
	}
	
	public function getCategories()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$value	= array();
		$query	= $this->mknowledge->getCategories('kbase');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		echo json_encode($value);
	}
	
	
	
	public function create_Categories()
	{
		$id			= $this->input->post('id', true);
		$name		= $this->input->post('name', true);
		$status		= $this->input->post('status', true);
		
		$data		= array('name'=>$name, 'status'=>1, 'module'=>'KBASE');
		
		$query		= $this->mknowledge->saveCategories($data, $id);
		if($query){
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error');
		}
		
		echo json_encode($result);
	}
	
	public function getKnowledgeCategory()
	{
		$value['data']		= array();
		$value['status']	= "error";
		$value['msg']		= "Query Failed.";
		$query	= $this->mknowledge->getKnowledgeCategory();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value['msg']		= '';
				$value['status']	= "OK";
				$subquery	= $this->mknowledge->getKnowledgeCategory($row->id);
				if($subquery->num_rows() > 0)
				{
					$value['data'][$row->name]	= array();
					foreach($subquery->result() as $subrow)
					{
						
						$value['data'][$row->name][]	= $subrow;
					}
				}else{
					$value['data'][$row->name]	= array();
				}
			}
		}else{
			$value['msg']		= "Empty data.";
		}
		
		echo json_encode($value);
	}
	
	public function save()
	{
		$title		= $this->input->post('title', true);
		$detail		= $this->input->post('detail', true);
		$categories	= $this->input->post('categories', true);
		$tags		= $this->input->post('tags', true);
		$username	= $this->session->userdata('ses_username');
		$status		= $this->input->post('status', true);
		$id			= $this->input->post('uid', true);
		
		$data		= array('title'=>$title,
							'detail'=>$detail,
							'categories'=>$categories,
							'tags'=>$tags,
							'username'=>$username,
							'status'=>$status);
		
		$query		= $this->mknowledge->saveKbase($data, $id);
		if($query)
		{
			$result	= array('status'=>'OK');
		}else{
			$result	= array('status'=>'Error', 'msg'=>'Failed save to database');
		}
		
		echo json_encode($result);
	}
	
	public function user()
	{
		/*
		$content['page_title']	= 'Product Knowledge';
		
		$data['content']	= $this->load->view('u_index',$content,true);
		$this->load->view('page',$data);
		*/
		$data['content']	= $this->load->view('u_index','',true);
		$this->load->view('page',$data);
	}
	
	public function getKnowledgeIndex()
	{
		$value		= array();
		$parent_id	= $this->input->post('parent_id', true);
		
		$query		= $this->mknowledge->getKnowledgeIndex();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		
		echo json_encode($value);
	}
	
	public function getKbaseByCategories()
	{
		$categories	= $this->input->post('categories', true);
		$value		= array();
		
		$query		= $this->mknowledge->getKbaseByCategories($categories);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$row->url	= site_url('knowledge/show/'.$row->uniq_id);
				$value[]	= $row;
			}
		}
		
		echo json_encode($value);
	}
	
	public function show()
	{
		$uniq_id		= $this->uri->segment(3);
		$query			= $this->mknowledge->getDetail($uniq_id);
		if($query->num_rows() > 0)
		{
			
			
			$row		= $query->row();
			$content	= $row;
			
			$content->recent	= $this->mknowledge->getRecentPost(5, array($row->uniq_id));
			
			$data['content']	= $this->load->view('u_show',$content,true);
			$this->load->view('page',$data);
		}else{
			show_404();
		}
	}
	
	public function detail()
	{
		
	}
	
	public function uploader()
	{
		$this->load->library('upload');
		$this->load->model('muploader');
		$pathinfo		= $this->config->item('path_media');
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
								'module'=>$this->router->fetch_class(),
								'is_image'=>$upload['is_image']);
				$this->muploader->saveMedia($media);
				$result	= array('status'=>'OK', 'url'=>base_url($pathinfo).$upload['file_name']);
		}
		
		echo json_encode($result);
	}
	
	public function delete()
	{
		$uniq_id		= $this->input->post('id', true);
		
		$query			= $this->mknowledge->deleteData($uniq_id);
		if($query){
			$result		= array('status'=>'OK', 'msg'=>'Success remove data');
		}else{
			$result		= array('status'=>'Error', 'msg'=>'Failed to remove data');
		}
		
		echo json_encode($result);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */