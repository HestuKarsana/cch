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
class Contacts extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mcontacts');
	}
	
	public function index()
	{
		$data['content']	= $this->load->view('index','',true);
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
		$cust 			= $this->input->post('cust') ? $this->input->post('cust') : '';
		
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mcontacts->getContactsDataTotal($search, $cust, $date);
		$query 			= $this->mcontacts->getContactsData($search, $cust, $row_show, $row_start, $sort, $order);
		
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
	
	public function form()
	{
		$content['page_title']	= $this->uri->segment(3) == '' ? '[ BARU ]' : '[ PERUBAHAN ]';
		$data['content']	= $this->load->view('form',$content,true);
		$this->load->view('page',$data);
	}

	public function form_data()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$cid 				= $this->input->post('cid');
		$value				= array();
		
		$value['source']	= array();
		$query				= $this->mcontacts->get_source_channel();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value['source'], $row);
			}
		}

		//$value['category']	= array();
		//$value['category']['children']	= array();
		$query				= $this->mcontacts->get_category();
		if($query->num_rows() > 0)
		{
			$parent 		= array();
			foreach($query->result() as $row)
			{
				if(in_array($row->type, $parent)){
					//$var[$row->type][]	= array('id'=>$row->id, 'text'=>$row->name);
					$child[$row->type][]	= array('id'=>$row->id, 'text'=>$row->name);

					//array_push($value['category']['children'], $child);
				}else{
					$parent[]					= $row->type;
					//$child						= array('id'=>$row->id, 'text'=>$row->name);
					$child[$row->type][]		= array('id'=>$row->id, 'text'=>$row->name);
					$value['category'][]		= array('text'=>$row->type);
				}
			}

			for($i = 0; $i<count($parent); $i++)
			{
				$key 	= $parent[$i];
				$value['category'][$i]['children']	= $child[$key];
				//array_push($value['category'][$i]['children'], $child[$key]);

				$value['basic'][$key]	= $child[$key];
			}
			
		}
		
		if($cid != '')
		{
			$query 		= $this->mcontacts->getDetailContact($cid);
			if($query->num_rows() > 0)
			{
				$row 	= $query->row();
				$value['data']		= $row;	
			}
		}
		
		
		
		echo json_encode($value);
	}
	
	public function edit()
	{
		$this->create();
	}
	
	public function upload_csv()
	{
		$data['content']	= $this->load->view('form_upload_csv','',true);
		$this->load->view('page',$data);
	}
	
	public function templateDownload()
	{
		$this->load->helper('download');
		
		$data = 'storage/template/template.csv';
		$name = 'contacts_template.csv';
		force_download($data, null);
	}
	
	public function do_upload_csv()
	{
		$this->load->helper('string');
		$this->load->library('readercsv');
        
		$config['upload_path']          = './storage/';
		$config['allowed_types']        = 'csv';
		
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('csv'))
		{
				//$error = $this->upload->display_errors();
				$data['status']	= 'Error';
				$data['error']	= strip_tags($this->upload->display_errors());
		}else{
				$data['status']	= 'OK';
				$files			= $this->upload->data();
				$result 		= $this->readercsv->parse_file($files['full_path']);
				$data['total']	= $this->mcontacts->insertFromCSV($result);
				$data['files']	= $files;
		}
		
		echo json_encode($data);
	}
	
	
	
	public function save()
	{
		$this->load->helper('string');
		
		$cid					= $this->input->post('uid',true);
		
		if($this->input->post('customer_name', true)){
			$data['name']	= $this->input->post('customer_name', true);
		}
		
		if($this->input->post('no_handphone', true)){
			$data['phone_number']	= $this->input->post('no_handphone', true);
		}
		if($this->input->post('email', true)){
			$data['email']	= $this->input->post('email', true);
		}
		
		
		if($this->input->post('address', true)){
			$data['address']	= $this->input->post('address', true);
		}
		if($this->input->post('delivery_address', true)){
			$data['delivery_address']	= $this->input->post('delivery_address', true);
		}
		if($this->input->post('province', true)){
			$data['propinsi']	= $this->input->post('province', true);
		}
		if($this->input->post('city', true)){
			$data['kota']	= $this->input->post('city', true);
		}
		if($this->input->post('kecamatan', true)){
			$data['kecamatan']	= $this->input->post('kecamatan', true);
		}
		
		if($this->input->post('kelurahan', true)){
			$data['kelurahan']	= $this->input->post('kelurahan', true);
		}
		
		if($this->input->post('patokan', true)){
			$data['patokan']	= $this->input->post('patokan', true);
		}
		if($this->input->post('type_customer', true)){
			$data['type_customer']	= $this->input->post('type_customer', true);
		}
		if($this->input->post('code_mum', true)){
			$data['code_mum']	= $this->input->post('code_mum', true);
		}
		if($this->input->post('parent_customer', true)){
			$data['parent_customer']	= $this->input->post('parent_customer', true);
		}
		if($this->input->post('verifikasi_mum', true)){
			$data['verifikasi_mum']	= $this->input->post('verifikasi_mum', true);
		}
		
		
		if($this->input->post('status', true)){
			$data['status']	= $this->input->post('status', true);
		}
		
		
		
		$this->db->trans_begin();
		
		$result	= array();
		$query		= $this->mcontacts->saveData($data, $cid);
		
		#echo $this->db->last_query();
		
		$last_id	= ($cid != '') ? $cid : $this->db->insert_id();
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result	= array('status'=>'Error', 'msg'=>'Saving data failed');
		}else{
			$this->db->trans_commit();
			$result	= array('status'=>'OK', 'msg'=>'Saving data success', 'contacts'=>$this->mcontacts->getContactByAutoID($last_id));
		}
		
		echo json_encode($result);
	}
	

	public function delete()
	{
		$id 		= $this->input->post('id', false);
		
		$query		= $this->mcontacts->deleteData($id);
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
	
	public function detail()
	{
		$uid				= $this->uri->segment(3);
		$query				= $this->mcontacts->getDetailContact($uid);
		if($query->num_rows() > 0)
		{
			$row			= $query->row();
			$content		= array('page_title'=>'Contact Detail', 'id'=>$row->id);
			$data['content']	= $this->load->view('detail',$content,true);
			$this->load->view('page',$data);
		}else{
			show_404();
		}
	}
	
	public function formModal(){
		echo $this->load->view('form_contact','', true);
	}
	
	public function getContactsDetail()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$value		= array();
		$cid		= $this->input->post('cid', true);
		
		$query		= $this->mcontacts->getDetailContact($cid);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= array('status'=>'OK', 'data'=>$row);
		}else{
			$value	= array('status'=>'Error');
		}
		echo json_encode($value);
	}
	
	public function contact_type()
	{
		if (!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}
		
		$value		= array();
		$cid		= $this->input->post('cid', true);
		
		$query		= $this->mcontacts->getContactType($cid);
		if($query->num_rows() > 0)
		{
			//$row	= $query->row();
			//$value	= array('status'=>'OK', 'row'=>$row);
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		echo json_encode($value);
	}
	
	public function check_email()
	{
		$email		= $this->input->post('email', true);
		$uid		= $this->input->post('uid', true);
		
		$user_id	= $this->mcontacts->checkContacts('email', $email);
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