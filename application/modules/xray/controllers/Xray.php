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
class ChunkReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
		{
			private $startRow = 0;
			private $endRow   = 0;

			/**  Set the list of rows that we want to read  */
			public function setRows($startRow, $chunkSize) {
				$this->startRow = $startRow;
				$this->endRow   = $startRow + $chunkSize;
			}

			public function readCell($column, $row, $worksheetName = '') {
				//  Only read the heading row, and the configured rows
				if (($row == 1) || ($row >= $this->startRow && $row < $this->endRow)) {
					return true;
				}
				return false;
			}
		}

class Xray extends CI_Controller {
	
	var $client_id;
	var $active_user;
	var $total_row;
	var $baris_ke;

	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mxray');

		$this->client_id 	= $this->session->userdata('ses_cid');
		$this->active_user 	= $this->session->userdata('ses_username');
		$this->total_row 	= 0;
		$this->baris_ke 	= 0;
	}
	
	public function index()
	{
		file_put_contents('./progress.json', json_encode(array('percentComplete'=>0)));
		
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
		
		$date			= '';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mxray->get_xray_total_data($search, $date);
		$query 			= $this->mxray->get_xray_data($search, $row_show, $row_start, $sort, $order);
		
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

	public function load_detail_info()
	{
		$this->load->library('Posapp');

		$uid 		= $this->input->post('uid');

		$query 		= $this->mxray->get_ticket_info($uid);
		$data['row']		= $query->row();
		
		
		
		

		$params['resi']		= $data['row']->awb;
		$json 	= json_encode($params);
		$var 	= $this->posapp->getLastStatusAwb($json);

		$data['tracking']	= "No Resi :".$var->responses->response->barcode
								."\nPengirim : ".$var->responses->response->senderName.' - '.$var->responses->response->senderCity
								."\nPenerima : ".$var->responses->response->receiverName.' - '.$var->responses->response->receiverCity
								."\nLast Update : ".$var->responses->response->office.' '.$var->responses->response->eventDate
								."\nInformasi : ".$var->responses->response->eventName.' '.$var->responses->response->description
								
								;
		#if(isset($var->serviceCode)){
		#	echo 'GAGAL';
		#}
		#$value['rows']	= array();
		#$data 			= $var->rs_tnt->r_tnt;
		#$total 			= count($data);
		/*
		for($i = 0; $i < $total; $i++)
		{
			$row	= array('barcode'=>$data[$i]->barcode,
							'officeCode'=>$data[$i]->officeCode,
							'office'=>$data[$i]->office,
							'eventName'=>$data[$i]->eventName,
							'eventDate'=>$data[$i]->eventDate,
							'description'=>$data[$i]->description);
			array_push($value['rows'], $row);
		}
		*/
		#echo json_encode($value);
		
		echo $this->load->view('index_side', $data, true);
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
		
		$value		= array();
		
		$value['source']	= array();
		$query				= $this->mxray->get_source_channel();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value['source'], $row);
			}
		}

		//$value['category']	= array();
		//$value['category']['children']	= array();
		$query				= $this->mxray->get_category();
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
					//$child					= array('id'=>$row->id, 'text'=>$row->name);
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
		
		
		echo json_encode($value);
	}

	public function save_data()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$aduan 		= $this->input->post('kantor_penerbangan');
		$asal 		= $this->input->post('kantor_asal');
		$tujuan 	= $this->input->post('kantor_tujuan');
		$id_kiriman	= $this->input->post('id_kiriman');
		$isi_kiriman= $this->input->post('isi_kiriman');
		$keterangan = $this->input->post('keterangan');
		
		$ktr_aduan 	= explode('|', $aduan);
		$ktr_asal 	= explode('|', $asal);
		$ktr_tujuan = explode('|', $tujuan);
		
		$data 	= array('kode_kantor_aduan'=>$ktr_aduan[0],
						'kantor_aduan'=>$ktr_aduan[1],
						'kode_kantor_asal'=>$ktr_asal[0],
						'kantor_asal'=>$ktr_asal[1],
						'kode_kantor_tujuan'=>$ktr_tujuan[0],
						'kantor_tujuan'=>$ktr_tujuan[1],
						'id_kiriman'=>$id_kiriman,
						'isi_kiriman'=>$isi_kiriman,
						'keterangan'=>$keterangan,
						'user_cch'=>$this->active_user);
		
		$this->db->trans_begin();

		$error		= array();
		// save gagal xray
		$squery 	= $this->mxray->save_data($data);
		if($squery == 0){
			$error[] = 'Error save data';
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$result 	= array('status'=>false, 'message'=>'Gagal menyimpan data');
		}else{
			$this->db->trans_commit();
			$result 	= array('status'=>true, 'message'=>'Berhasil menyimpan data');
		}

		echo json_encode($result);
	}

	// upload
	public function do_upload()
    {
        file_put_contents('./progress.json', json_encode(array('percentComplete'=>0)));
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
		

		$client = new Memcache();
		$client->connect('localhost', 11211);
		$pool = new \Cache\Adapter\Memcache\MemcacheCachePool($client);
		//$simpleCache = new \Cache\Bridge\SimpleCache\SimpleCacheBridge($pool);
		//\PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);

		//$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

		/**  Define how many rows we want to read for each "chunk"  **/
		


        #$file             = './storage/20180308_131331_Delivery-ALC-Feb-18.XLSX';
        $file             = "./".$this->input->post('file', true);
		$inputFileType    = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
		$reader           = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		
		/*
		$chunkSize 		  = 1000;
		$chunkFilter 	  = new ChunkReadFilter();

		$reader->setReadFilter($chunkFilter);
		$reader->setReadDataOnly(true);
		#print_r($reader);
		#exit;
		//$startRow	= 4;
		
		//echo pathinfo($file, PATHINFO_BASENAME) . ' using IOFactory with a defined reader type of ' . $inputFileType;

		$worksheetData 	  = $reader->listWorksheetInfo($file);
		$numRows		  = ($worksheetData[0]['totalRows']) - 1;
		for ($startRow = 4; $startRow <= $numRows; $startRow += $chunkSize) {
			$chunkFilter->setRows($startRow,$chunkSize);

			#echo 'Loading WorkSheet using configurable filter for headings row 1 and for rows ' . $startRow . ' to ' . ($startRow + $chunkSize - 1);
			
			//print_r($)
			//$spreadsheet = $reader->load($file);
			
			#$numRows		  = $spreadsheet->getActiveSheet()->getHighestRow() - 1;
			//$sheetData 		  = $spreadsheet->getActiveSheet()->toArray();
			//$sheetData 		  = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			#$columns          = array_fill_keys(['B','C','D','E','F','G','H','I'], true);
			
			//    Do some processing here
		}
		
		#$client->set('excel_xray', $reader, MEMCACHE_COMPRESSED, 50);
		#reader 			= $client->get('excel_xray');
		#print_r();

		//print_r($client->get('excel_xray'));
		
		*/
		
		/**  Load $inputFileName to a Spreadsheet Object  **/
		
		$reader->setReadDataOnly(true);
		// old
		$spreadsheet      = $reader->load($file);		
		//print_r($spreadsheet);
		//exit;
		#$client->set('excel_sheet', $spreadsheet->getActiveSheet()->getHighestRow(), MEMCACHE_COMPRESSED, 300);
		#echo $spreadsheet      = $client->get('excel_sheet') - 1;
		#exit;
		// cache
		//$spreadsheet      = $reader->load($file);		
		
		//$spreadsheet->setActiveSheetIndexByName('Sheet1');
		//$numRows          = $spreadsheet->getActiveSheet()->getHighestRow() - 1;
		//$sheetData 		  = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        #
        //  $spreadsheet->getActiveSheet()->getStyle('C:D')->getNumberFormat()->setFormatCode('YYYY-MM-DD');
        #$date_1     = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($data[B]));
		//$worksheetData 	  = $reader->listWorksheetInfo($file);
		$numRows		  = $spreadsheet->getActiveSheet()->getHighestRow() - 1;
		$sheetData 		  = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		
        
		$columns          = array_fill_keys(['B','C','D','E','F','G','H','I'], true);
		
		array_walk($sheetData, function(&$row) use ($columns, $numRows) { 
			$xyz = array_intersect_key($row, $columns);
			
			if($this->baris_ke > 4){
                
				if( ($xyz['B'] != null) && ($xyz['C'] != null) && ($xyz['D'] != null) && ($xyz['E'] != null) && ($xyz['F'] != null) && ($xyz['G'] != null) && ($xyz['H'] != null)){
//                    echo '<pre>'; print_r($xyz);echo '</pre>';
					$this->insertData($xyz);
				}
                
			}
            file_put_contents('./progress.json', json_encode(array('percentComplete'=>$this->baris_ke/$numRows)), LOCK_EX);
            
			$this->baris_ke += 1;
			
		});
        
        //if(($this->baris_ke - 1) == $numRows){
        $total_read             = $this->baris_ke - 1;
            $result              = array('status'=>'OK', 'msg'=>'Read '.$total_read.' rows excel file');
        //}
        echo json_encode($result);
    }
	
	
	private function insertData($data)
    {
        #$date   = DateTime::createFromFormat('d/m/y', $data['C']);
        #$date_1 = ($data['C'] - 25569) * 86400;
        #$date_2 = ($data['D'] - 25569) * 86400;
		$get_user_pos	= $this->mglobals->get_kantor_pos_user($this->session->userdata('pos_office'));
		$keterangan 	= ($data['I'] != "") ? $data['I'] : "diteruskan via laut/darat";
        #gmdate("Y-m-d", $date_1)
        $rest   = array('kantor_aduan'=>$get_user_pos,
                        //,'tgl_input'=>date('Y-m-d'),
                        'kantor_asal'=>$data['B'],
                        'kantor_tujuan'=>$data['C'],
                        'id_kiriman'=>$data['D'],
                        'kantong_lama'=>$data['E'],
                        'kantong_baru'=>$data['F'],
                        'isi_kiriman'=>$data['G'],
                        'berat'=>$data['H'],
                        'keterangan'=>$keterangan
                       );
        
        $do_id  = $this->mxray->checkXrayData($data['D'], $data['B'], $data['C'], $data['F']);
        if($do_id == 0){
            return $this->mxray->save_data($rest);
        }
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */