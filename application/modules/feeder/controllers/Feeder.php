<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Customer Care
 * @subpackage	Controller
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Feeder extends CI_Controller {
	
	var $client_id;
	var $active_user;
	var $pos_office;
	var $active_name;
	var $total_row;
	var $baris_ke;

	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mfeeder');

		$this->client_id 	= $this->session->userdata('ses_cid');
		$this->active_user 	= $this->session->userdata('ses_username');
		$this->active_name	= $this->session->userdata('ses_fullname');
		$this->pos_office 	= $this->session->userdata('pos_office');
		$this->total_row 	= 0;
		$this->baris_ke 	= 0;
	}
	
	public function index()
	{
		file_put_contents(FCPATH.'progress_mp.json', json_encode(array('percentComplete'=>0)));
		
		$data['content']	= $this->load->view('index','',true);
		$this->load->view('page',$data);
	}
	
	public function get_data_list()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$row_show		= $this->input->post('rows', true) ? $this->input->post('rows', true) : $this->config->item('show_perQuery');
		$row_start	 	= $this->input->post('page', true) ? ( $this->input->post('page', true) - 1) * $row_show : 0;
		$order			= $this->input->post('order', true);
		$sort			= $this->input->post('sort', true);
		
		$search			= $this->input->post('search', true) ? $this->input->post('search', true) : '';
		$search 		= explode(' ',$search);


		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mfeeder->get_total_data($search, $date);
		$query 			= $this->mfeeder->get_data($search, $row_show, $row_start, $sort, $order);
		
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

	// upload
	public function do_upload()
    {
        file_put_contents('./progress_mp.json', json_encode(array('percentComplete'=>0)));
		$upload_dir		= 'storage/xray/';
		$file			= $_FILES['files'];
		$result         = array();
		for($i = 0; $i < count($file['name']); $i++)
		{
			$fname		= $file['name'][$i];
			$ftype		= $file['type'][$i];
			$ftmp		= $file['tmp_name'][$i];
			$fsize		= $file['size'][$i];
			$ferror		= $file['error'][$i];
			
			
			if($ferror == 0)
			{	
				$uploaded_file	 = explode('.',$fname); // explode
				$extension	     = end($uploaded_file); // get extension
				$basename	     = basename($fname,$extension); // get orginal name
				
				$filename	= date('Ymd_His').'_'.url_title($basename).".".$extension; // uniq file name 
				
				if(!move_uploaded_file($ftmp, $upload_dir.$filename))
				{
					$result[]	= array('code'=>$ferror, 'msg'=>"Upload failed", 'status'=>'ERROR','orginal_name'=>$fname);
				}else{
					
					
					$result[]	= array('status'=>'OK',
                                        'orginal_name'=>$fname,
                                        'server_name'=>$filename,
                                        'server_path'=>$upload_dir.$filename,
                                        'file_size'=>$fsize,
                                        'file_type'=>$ftype, 
                                        'extension'=>$extension);
				}
			}	
		}
        
        echo json_encode($result);
	
    }
	
	public function do_read_file($file = "")
    {
        $file             = "./".$this->input->post('file', true);
        $inputFileType    = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
        
		$reader           = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		
		$reader->setReadDataOnly(true);
		$spreadsheet      = $reader->load($file);		
		
		$worksheetData 	  = $reader->listWorksheetInfo($file);
		$numRows		  = ($worksheetData[0]['totalRows']) - 1;
		
		$sheetData 		  = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        
		$columns          = array_fill_keys(['A','B','C','D','E','F','G','H','I','J','K'], true);
        
		array_walk($sheetData, function(&$row) use ($columns, $numRows) { 
			$xyz = array_intersect_key($row, $columns);
			
			if($this->baris_ke > 0){
                
				if( ($xyz['A'] != null) && ($xyz['B'] != null) ){
					$this->insertData($xyz);
				}
                
			}
            file_put_contents('./progress_mp.json', json_encode(array('percentComplete'=>$this->baris_ke/$numRows)), LOCK_EX);
            
			$this->baris_ke += 1;
			
		});
        
        $total_read             = $this->baris_ke - 1;
        $result              = array('status'=>true, 'msg'=>'Read '.$total_read.' rows excel file');
        
        echo json_encode($result);
    }
	
	
	private function insertData($data)
    {
		$rest   = array('tgl_upload'=>date('Y-m-d H:i:s'),
						'marketplace'=>$this->active_name,
						'user_upload'=>$this->active_user,
						'no_pesanan'=>$data['A'],
                        'awb'=>$data['B'],
						'pengirim'=>$data['C'],
						'pengirim_alamat'=>$data['D'],
						'pengirim_telp'=>$data['E'],
						'penerima'=>$data['F'],
						'pengirim_alamat'=>$data['G'],
						'pengirim_telp'=>$data['H'],
						'mp_status'=>$data['I'],
						'tgl_pickup'=>$data['J'],
						'kota'=>$data['K']
                        );
        
        $do_id  = $this->mfeeder->checkFeederData($data['B']);
        if($do_id == 0){
			return $this->mfeeder->save_data($rest);
        }
	}
	
	// REPORT
	public function report()
	{
		$data['content']	= $this->load->view('report_dashboard','',true);
		$this->load->view('page',$data);
	}

	public function load_dashboard()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$start 				= $this->input->post('start', true) ? $this->input->post('start') : date('Y-07-01');
		$end 				= $this->input->post('end', true) ? $this->input->post('end') :  date('Y-m-t');
		$totalDays			= totalDays($start, $end);
		
		$mp_name			= $this->input->post('marketplace');
		$regional_asal 		= $this->input->post('regional_asal');
		$regional_tujuan 	= $this->input->post('regional_tujuan');

		$kantor_asal 		= $this->input->post('kantor_asal');
		$kantor_terbangan 	= $this->input->post('kantor_terbangan');
		$kantor_tujuan 		= $this->input->post('kantor_tujuan');

		$result 			= array();


		$marketplace 	= array();
		$query 			= $this->mfeeder->get_marketplace_upload($start, $end);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$marketplace[]	= array('name'=>$row->_name, 'y'=>(int) $row->_total);
			}
		}
		$query->next_result(); 
		$query->free_result(); 


		for($i = 0; $i<$totalDays; $i++)
		{
			$label = date('Y-m-d', strtotime($i.' days', strtotime($start)));
			$check = date('d/m/y', strtotime($i.' days', strtotime($start)));
			$query 	= $this->mfeeder->get_marketplace_upload_daily($label, $mp_name);	
			if($query->num_rows() > 0)
			{
				$row 		= $query->row();

				$mdata[]	= array($label, (int) $row->_total);
			}else{
				$mdata[]	= array($label, null);
			}
			$query->next_result(); 
			$query->free_result(); 
			
		}
		$daily 			= $mdata;

		/*
		$xray_regional_asal_kirim		= array();
		$query 	= $this->mreport->get_xray_regional_asal_kirim($date);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$xray_regional_asal_kirim[]	= array('name'=>$row->_name, 'y'=>(int) $row->_total);
			}
		}
		$query->next_result(); 
		$query->free_result(); 
		
		$xray_regional_tujuan_kirim		= array();
		$query 	= $this->mreport->get_xray_regional_tujuan_kirim($date, $regional_asal);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$xray_regional_tujuan_kirim[]	= array('name'=>$row->_name, 'y'=>(int) $row->_total);
			}
		}
		$query->next_result(); 
		$query->free_result(); 


		$asal_kirim['datasets']		= array();
		$asal_kirim['labels']		= array();
		$query 	= $this->mreport->get_xray_asal_kirim($date, $regional_asal, $regional_tujuan);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$asal_value[]	= (int) $row->_total;
				$asal_label[]	= $row->_name;
			}
			$asal_kirim['datasets']		= array(array('data'=>$asal_value,'backgroundColor'=>$this->config->item('color')));
			$asal_kirim['labels']		= $asal_label;
			
		}
		$query->next_result(); 
		$query->free_result(); 


		$terbangan['datasets']		= array();
		$terbangan['labels']		= array();
		$terbangan['rows']			= array();
		$query 	= $this->mreport->get_xray_asal_terbangan($date, $regional_asal, $regional_tujuan, $kantor_asal);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				#$asal_value[]	= (int) $row->_total;
				#$asal_label[]	= $row->_name;
				array_push($terbangan['rows'], $row);
			}
			#$asal_kirim['datasets']		= array(array('data'=>$asal_value,'backgroundColor'=>$this->config->item('color')));
			#$asal_kirim['labels']		= $asal_label;
			
		}
		$query->next_result(); 
		$query->free_result(); 

		$tujuan_kirim['datasets']	= array();
		$tujuan_kirim['labels']		= array();
		$query 	= $this->mreport->get_xray_tujuan_kirim($date, $regional_asal, $regional_tujuan, $kantor_asal);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$tujuan_value[]	= (int) $row->_total;
				$tujuan_label[]	= $row->_name;
			}
			$tujuan_kirim['datasets']		= array(array('data'=>$tujuan_value,'backgroundColor'=>$this->config->item('color')));
			$tujuan_kirim['labels']		= $tujuan_label;
			
		}
		$query->next_result(); 
		$query->free_result();


		$tag_cloud['datasets']	= array();
		$tag_cloud['labels']		= array();
		$query 	= $this->mreport->get_xray_item_name($date, $regional_asal, $regional_tujuan, $kantor_asal, $kantor_tujuan);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$tags_total[]	= (int) $row->_total;
				$tags_label[]	= $row->_name;
				$mtags[]		= array('name'=>$row->_name, 'weight'=>(int) $row->_total);
			}
			$tag_cloud['datasets']		= $mtags;
			
		}
		$query->next_result(); 
		$query->free_result(); 


		//$harian['datasets']	= array();
		//$harian['labels']	= array();

		$lastDateOfMonth = date("t", strtotime($date));
		for($i = 0; $i<$lastDateOfMonth; $i++)
		{
			$label = date('Y-m-d', strtotime($i.' days', strtotime($date)));
			$check = date('d/m/y', strtotime($i.' days', strtotime($date)));
			$query 	= $this->mreport->get_xray_harian($label);	
			if($query->num_rows() > 0)
			{
				$row 		= $query->row();

				$mdata[]	= array($label, (int) $row->_total);
			}else{
				$mdata[]	= array($label, null);
			}
			$query->next_result(); 
			$query->free_result(); 
			
		}
		$harian 			= $mdata;
		$result['regional_asal_kirim']		= $xray_regional_asal_kirim;
		$result['regional_tujuan_kirim']	= $xray_regional_tujuan_kirim;
		$result['kantor_asal_kirim']		= $asal_kirim;
		$result['kantor_terbangan']			= $terbangan;
		$result['kantor_tujuan_kirim']		= $tujuan_kirim;
		
		$result['tag']						= $tag_cloud;
		#$result['asal']			= $asal;
		#$result['tujuan']		= $tujuan;
		#$result['product']		= $product;
		*/
		$result['marketplace']	= $marketplace;
		$result['daily']					= $daily;
		echo json_encode($result);	
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */