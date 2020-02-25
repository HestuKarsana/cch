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
class Ccare extends CI_Controller {
	
	var $client_id;
	var $active_user;
	var $pos_office;
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}
		$this->load->model('mcare');

		$this->client_id 	= $this->session->userdata('ses_cid');
		$this->active_user 	= $this->session->userdata('ses_username');
		$this->pos_office 	= $this->session->userdata('pos_office');
	}
	
	public function index()
	{
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
		$product 		= $this->input->post('product') ? $this->input->post('product') : '';
		$start 			= $this->input->post('start') ? $this->input->post('start') : date('Y-m-d', strtotime('-7 days'));
		$end 			= $this->input->post('end') ? $this->input->post('end') : date('Y-m-d');
		
		$date			= ' date(a.date) between "'.$start.'" and "'.$end.'" ';
		$rows['total'] 	= 0;
		$rows['rows'] 	= array();
		$total 			= $this->mcare->get_ccare_total_data($search, $product, $date);
		$query 			= $this->mcare->get_ccare_data($search, $product, $date, $row_show, $row_start, $sort, $order);
		
		if($query->num_rows() > 0)
		{
			$rows['total'] 	= $total;
			foreach($query->result() as $row)
			{
				$source = array(',','|');
				$replace= array(', ',' - ');
				$kantor 	= explode(',', $row->tujuan_pengaduan_name);
				

				#$row->kantor_tujuan_pengaduan 	= str_replace($source, $replace, $row->tujuan_pengaduan_name);
				$row->kantor_tujuan_pengaduan 	= $this->mglobals->get_kantor_name_code($kantor);
				array_push($rows['rows'],$row);
			}
		}
		echo json_encode($rows);
	}

	public function load_grid_data()
	{
		$value['product'] 	= array();
		$query 	= $this->mcare->get_pos_product();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row){
				array_push($value['product'], $row);
			}
		}

		$value['date_end']			= date('Y-m-d');
		$value['date_start']		= date('Y-m-d', strtotime('-7 days'));

		echo json_encode($value);
	}

	public function load_detail_info()
	{
		$this->load->library('Posapp');

		$uid 		= $this->input->post('uid');

		$query 		= $this->mcare->get_ticket_info($uid);
		$data['row']		= $query->row();

		$params['resi']		= $data['row']->awb;
		$json 	= json_encode($params);
		$var 	= $this->posapp->getLastStatusAwb($json);
		
		/*
		$data['tracking']	= "No Resi :".$var->responses->response->barcode
								."\nPengirim : ".$var->responses->response->senderName.' - '.$var->responses->response->senderCity
								."\nPenerima : ".$var->responses->response->receiverName.' - '.$var->responses->response->receiverCity
								."\nLast Update : ".$var->responses->response->office.' '.$var->responses->response->eventDate
								."\nInformasi : ".$var->responses->response->eventName.' '.$var->responses->response->description
								
								;
								*/
								$data['tracking']	= "No Resi :".$var->response->data->barcode
								//."\nPengirim : ".$var->response->data->senderName.' - '.$var->responses->response->senderCity
								//."\nPenerima : ".$var->response->receiverName.' - '.$var->responses->response->receiverCity
								."\nLast Update : ".$var->response->data->office.' '.$var->response->data->officeCode.' - '.$var->response->data->eventDate
								."\nInformasi : ".$var->response->data->eventName.' '.$var->response->data->description
								
								;
		echo $this->load->view('index_side', $data, true);
	}
	
	public function form()
	{
		
		$content['page_title']	= $this->uri->segment(3) == '' ? '[ BARU ]'.$this->mcare->get_no_ticket() : '[ PERUBAHAN ]';
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
		$query				= $this->mcare->get_source_channel();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value['source'], $row);
			}
		}

		//$value['category']	= array();
		//$value['category']['children']	= array();
		$query				= $this->mcare->get_category();
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
		
		echo json_encode($value);
	}
	
	public function customer_lookup()
	{
		$phone 		= $this->input->post('q');
		
		$query 		= $this->mcare->check_customer_phone($phone);
		
		$rows 		= array();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($rows, $row);
			}
		}
		
		echo json_encode($rows);
	}

	public function load_form_helper()
	{
		$type_of_request= $this->input->post('type_id');
		$type_of_request= $this->mcare->get_category_data($type_of_request, 'forms');

		echo $this->load->view($type_of_request,'', true);
	}

	public function load_form_helper_fix()
	{
		$page 		= $this->input->post('page');
		echo $this->load->view($page,'', true);
	}

	public function save_data()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$channel 		= $this->input->post('channel');
		$id_ktp 		= $this->input->post('id_ktp');
		$requester		= $this->input->post('requester');
		$address 		= $this->input->post('address');
		$phone 			= $this->input->post('phone');
		$email 			= $this->input->post('email');
		
		$facebook 			= $this->input->post('facebook');
		$instagram 			= $this->input->post('instagram');
		$twitter 			= $this->input->post('twitter');
		
		$type_of_request	= $this->input->post('type_of_request');
		
		
		$service_type 		= $this->input->post('services_type') ? $this->input->post('services_type') : 'domestik';
		$awb 				= $this->input->post('resi');
		$jenis_layanan 		= $this->input->post('jenis_layanan');
		
		
		$tujuan_aduan	= $this->input->post('tujuan_pengaduan');
		$sender 		= $this->input->post('sender_name');
		$receiver		= $this->input->post('receiver_name');
		$note 			= $this->input->post('note');
		$jenis_pos 		= $this->input->post('jenis_pos');
		$jenis_customer = $this->input->post('jenis_customer');
		$jenis_bisnis 	= $this->input->post('jenis_bisnis');
		$jenis_kiriman 	= $this->input->post('jenis_kiriman');
		$add_note 		= $this->input->post('additionalnotes');
		$uid 			= $this->input->post('uid');
		
		
		$awb_manual 				= $this->input->post('resi_manual');
		$tujuan_pengaduan_manual	= $this->input->post('tujuan_pengaduan_manual');
		$sender_name_manual			= $this->input->post('sender_name_manual');
		$receiver_name_manual		= $this->input->post('receiver_name_manual');
		$jenis_layanan_manual		= $this->input->post('jenis_layanan_intl');
		$recreate					= $this->input->post('recreate');
		
		$ccare_id 					= random_string('sha1');
		
		$channel 					= $this->mcare->get_channel_detail($channel);
		$category_id				= $this->mcare->get_category_detail($type_of_request);
		$group_category 			= $this->mcare->get_category_data($type_of_request, "type");
		$category_name 				= $this->mcare->get_category_data($type_of_request, "name");

		$send_kode_aduan		= array();
		$send_aduan				= array();
		
		//if($group_category == 'INFO'){
		//	$status 				= 99;
		//}else{
		//	$status 				= 1;
		//}
		
		
		$no_ticket 					= $this->mcare->get_no_ticket($this->pos_office);
		
		
		
		if(isset($tujuan_aduan)){
			if(count($tujuan_aduan) > 0){
				for($i = 0; $i < count($tujuan_aduan); $i++){
					$kode_aduan 			= explode('|', $tujuan_aduan[$i]);
					$send_kode_aduan[]		= $kode_aduan[0];
					$send_aduan[]			= $tujuan_aduan[$i];
				}
			}
		}
		
		#print_r(implode(',',$send_kode_aduan));
		#echo $fkode_aduan;
		$kode_sender 				= explode(' ', $sender);
		$kode_receiver 				= explode(' ', $receiver);
		
		$fkode_aduan_fin 				= implode(',',$send_kode_aduan);
		$fkode_sender 				= ((isset($kode_sender[0])) ? $kode_sender[0] : '');
		$fkode_receiver 			= ((isset($kode_receiver[0])) ? $kode_receiver[0] : '');

		$status 					= $group_category == 'INFO' ? 99 : 1;
		$fkode_aduan 				= $group_category == 'INFO' ? $this->pos_office : $fkode_aduan_fin;
		
		
		if($awb != ''){
			$query 			= $this->mglobals->check_xray($awb);
			if($query->num_rows() > 0){
				$status 	= ($recreate == 1) ? 1 : 99;
			}
		}
		
		$final_awb 					= ($service_type == 'domestik') ? $awb : $awb_manual;
		$subject 					= $group_category.' '.$category_name.'['.$final_awb.']';
		// CHECK AWB ON TICKET
		$text		= "";
		$query 		= $this->mcare->get_ticket($final_awb);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$offcode = explode(',',$row->tujuan_pengaduan);
				$tujuan_aduan 	= $this->mglobals->get_kantor_name_code($offcode);
				$text 	.= "------------RIWAYAT SEBELUMNYA-----------\n";
				$text 	.= "No Tiket : ".$row->no_ticket."\n";
				$text 	.= "Tgl Aduan : ".$row->date."\n";
				$text 	.= "Pembuat Aduan : ".$row->kantor_pembuat." - ".$row->user_cch."\n";
				$text 	.= "Tujuan Aduan : ".$tujuan_aduan."\n";
				$text 	.= "Jenis Aduan : ".$row->category_name." - ".$row->category_detail."\n";
				$text 	.= "Tgl Selesai : ".$row->last_update."\n";
				$text 	.= "Informasi : ".$row->complaint."\n";
				$text 	.= "-----------------------\n";

				$subquery 	= $this->mcare->get_ticket_response($row->id);
				if($subquery->num_rows() > 0)
				{
					foreach($subquery->result() as $rows)
					{
						$text 	.= "Tgl Response : ".$rows->date."\n";
						$text 	.= "Kantor Response : ".$rows->kantor_response." - ".$rows->username."\n";
						$text 	.= "Status : ".$rows->status_name."\n";
						$text 	.= "Informasi : ".$rows->response."\n";
						$text 	.= "-----------------------\n";
					}
				}
			}
		}
		
		$data 		= array('name_requester'=>$requester,
							'address'=>$address,
							'phone'=>$phone,
							'email'=>$email,
							'facebook'=>$facebook,
							'instagram'=>$instagram,
							'twitter'=>$twitter,
							'user_ccare'=>$this->active_user,
							'id_ktp'=>$id_ktp,
							'status'=>$status);
		
		
		$dticket 	= array('no_ticket'=>$no_ticket,
							'contact_id'=>(($uid != '') ? $uid : $ccare_id),
							'awb'=>($service_type == 'domestik') ? $awb : $awb_manual,
							'info_aduan'=>$group_category,
							'complaint_origin'=>$this->pos_office,
							'sender'=>($service_type == 'domestik') ? $fkode_sender : $sender_name_manual,
							'sender_name'=>$sender,
							'receiver'=>($service_type == 'domestik') ? $fkode_receiver : $receiver_name_manual,
							'receiver_name'=>$receiver,
							'phone_number'=>$phone,
							'status'=>$status,
							'closed_is'=>"-",
							'category'=>$category_id,
							'subject'=>$subject,
							'complaint'=>$note." ".$text,
							'channel'=>$channel,
							'user_cch'=>$this->active_user,
							'tujuan_pengaduan'=>($service_type == 'domestik') ? $fkode_aduan : $tujuan_pengaduan_manual,
							'tujuan_pengaduan_name'=>implode(',',$send_aduan),
							'service_type'=>$service_type,
							'jenis_pos'=>$jenis_pos,
							'jenis_customer'=>$jenis_customer,
							'jenis_bisnis'=>$jenis_bisnis,
							'jenis_exim'=>$jenis_kiriman,
							'notes'=>$add_note,
							'jenis_layanan'=>($service_type == 'domestik') ? $jenis_layanan : $jenis_layanan_manual
							);

		
		$this->db->trans_begin();

		$error		= array();
		// save customer 
		//$squery 	= $this->mcare->save_customer($dcust);
		//if($squery == 0){
		//	$error[] = 'Error save data';
		//}

		// save ccare
		$squery 	= $this->mcare->save_data($data, $uid, $ccare_id);
		if($squery == 0){
			$error[] = 'Error save data';
		}
		
		// create ticket
		$squery 	= $this->mcare->create_ticket($dticket);
		if($squery == 0){
			$error[] = 'Create ticket error';
		}

		#$squery 	= $this->mcare->update_tmp_media($no_ticket, $this->active_user);

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

	public function tools_ongkir()
	{
		$content['tpl']		= 'ongkir';
		$data['content']	= $this->load->view('tools',$content,true);
		$this->load->view('page',$data);
	}

	public function tools_resi()
	{
		$content['tpl']		= 'resi';
		$data['content']	= $this->load->view('tools',$content,true);
		$this->load->view('page',$data);
	}

	public function tools_kodepos()
	{
		$content['tpl']		= 'kodepos';
		$data['content']	= $this->load->view('tools',$content,true);
		$this->load->view('page',$data);
	}

	public function tools_kantorpos()
	{
		$content['tpl']		= 'kantor_pos';
		$data['content']	= $this->load->view('tools',$content,true);
		$this->load->view('page',$data);
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */