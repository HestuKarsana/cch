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
class App extends CI_Controller {
	
	private $active_user;
	private $pos_office;
	private $active_role;
	private $active_regional; 
	public function __construct()
	{
        parent::__construct();
		if((!$this->session->userdata('ses_login')) && ($this->session->userdata('ses_login') != true ))
		{
			redirect('login',301);
		}

		$this->active_user		= $this->session->userdata('ses_username');
		$this->pos_office 		= $this->session->userdata('pos_office');
		$this->active_role	 	= $this->session->userdata('ses_role');
		if($this->session->userdata('ses_utype') == 'Regional'){
			$regional	= $this->mglobals->get_kantor_pos_in_regional($this->pos_office);
			$query 		= $this->mglobals->get_kantor_pos_regional($regional);
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$view_kantor_pos[]	= $row->code;
				}
			}
			$this->active_regional = $view_kantor_pos;
		}else if($this->session->userdata('ses_utype') == 'KPRK'){
			$this->active_regional = array($this->pos_office);
		}else{
			$this->active_regional = array($this->pos_office);
		}
	}

	public function check()
	{
		echo '<pre>';
		print_r($this->session->all_userdata());
		echo '</pre>';
	}

	public function wsdl()
	{
		$params[]	= array('userId'=>'CHATBOTMCC');
		$params[]	= array('password'=>'CHATBOTMCC@419');
		$params[]	= array('barcode'=>'17382018392');
		//$params[]	= array('userId'=>'CHATBOTMCC');

		$wsdl = 'http://178.128.55.194/storage/PosWebServices.xml';
        $options = array(
                'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
                'style'=>SOAP_RPC,
                'use'=>SOAP_ENCODED,
                'soap_version'=>SOAP_1_1,
                'cache_wsdl'=>WSDL_CACHE_NONE,
                'connection_timeout'=>15,
                'trace'=>true,
                'encoding'=>'UTF-8',
                'exceptions'=>true,
            );
			$soap = new SoapClient($wsdl, $options);
			print_r($soap->getTrackAndTracePiol($params));
            try {
				//$data = $soap->SI_SOReq($params);
				
				
        	}
        catch(Exception $e) {
            die($e->getMessage());
		}
		
	}
	public function get_events()
	{
		//if (!$this->input->is_ajax_request()) {
		//	exit('No direct script access allowed');
		//}

		$start 		= $this->input->get('start');
		$end 		= $this->input->get('end');

		$data 		= array();
		$query 		= $this->mglobals->get_events_calendar($start, $end);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$row->allDay	= true;
				array_push($data, $row);
			}
		}

		echo json_encode($data);
	}

	public function show_notification()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$text 	= array();
		$query 	= $this->mglobals->get_notification();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$text[]	= $row->title.' <a href="'.site_url('notif/detail/'.$row->id).'">detail</a>';
			}
		}

		$full_text 	= (!empty($text)) ? implode(' | ', $text) : '';

		$result 	= array('status'=>true, 'info'=>$full_text);
		echo json_encode($result);
	}

	public function check_ticket()
	{
		$this->load->library('Posapp');
		
		$result['rows']	= array();
		$no_resi		= $this->input->post('resi');
		$jenis_kiriman 	= $this->input->post('jenis_kiriman');
		$no_ticket 		= $this->mglobals->check_ticket($no_resi);
		if($no_ticket['history'] == '')
		{
			$result['new_ticket']	= true;
			// $ticket
			// buat ticket baru
			$query 		= $this->mglobals->check_xray($no_resi);
			if($query->num_rows() > 0)
			{
				
				$row 	= $query->row();
				$res = "";
				$res .= "GAGAL XRAY \n";
				$res .= "Kantor Penerbangan :".$row->kantor_aduan."\n";
				$res .= "Tanggal :".$row->tgl_input."\n";
				$res .= "Kantor Asal :".$row->kantor_asal."\n";
				$res .= "Kantor Tujuan :".$row->kantor_tujuan."\n";
				$res .= "ID Kiriman :".$row->id_kiriman."\n";
				$res .= "Kantong Lama :".$row->kantong_lama."\n";
				$res .= "Kantong Baru :".$row->kantong_baru."\n";
				$res .= "Isi Kiriman :".$row->isi_kiriman."\n";
				$res .= "Berat :".$row->berat."Kg\n";
				$res .= "Keterangan :".$row->keterangan."\n";
				
				$result['xray_ticket']		= true;
				$result['xray_ticket_info']	= $res;

				//$result['ticket_id']		= $no_ticket['tid'];
				$result['ticket_recreate']	= true;
			}else{
				$result['xray_ticket']		= false;
				$data_tracking 	= "";
				if($jenis_kiriman == 'keuangan')
				{
					$params['payload']		= $no_resi;
					$json 				= json_encode($params);
					$var 				= $this->posapp->getJasKeu($json);	

					if(isset($var->DATAS))
					{
						$xoutput 	= json_decode(json_encode($var->DATAS), true); 
						$data_tracking .= implode(', ', array_map(
							function ($v, $k) {
								if(is_array($v)){
									return $k.'[]='.implode('&'.$k.'[]=', $v);
								}else{
									return $k." : ".$v."\n";
								}
							}, 
							$xoutput, 
							array_keys($xoutput)
						));
					}
				}else{
					
					$params['barcode']		= $no_resi;
					$json 				= json_encode($params);

					$trackDetail 		= $this->posapp->getTrackingDetail($json);
					if($trackDetail->response)
					{
						if($trackDetail->response->data){
						
							$grep_txt 		= end($trackDetail->response->data);
							if(isset($grep_txt)){
								
								$ext_txt		= explode('~~',$grep_txt->description);
								$produk_txt 	= explode(':', $ext_txt[10]);
								
								$layanan 		= $this->mglobals->get_layanan_code(trim(end($produk_txt)));
								$result['jenis_layanan_txt']		= end($produk_txt);
								$result['jenis_layanan']			= $layanan;
							}
							
							
						}
						
						$var 				= $this->posapp->getTracking($json);
						
						if(isset($var->response)){
							
							//echo $total 			= count($data);
							if(is_array($var->response->data)){
								$data 			= $var->response->data;
								
								#echo '<pre>';
								#print_r($data);
								#echo '</pre>';
	
								$total 	= count($data);
								for($i = 0; $i < $total; $i++)
								{
									if( ($i == 0) && ($data[0]->description != '')){
											//echo $data[0]->description;
											/*
											LAYANAN : POS KILAT KHUSUS;
											PENGIRIM : BANK JATIM TULUNGAGUNG;
											ALAMAT PENGIRIM : JL I GUSTI NGURAH RAI 1 66211;
												PENERIMA : MULYA PERKASA CV;
												ALAMAT PENERIMA : DS PULOSARI 001 008 PULOSARI 66211
											*/
											
											$text 			= explode(";", $data[0]->description);
											$kdpos 			= $text[count($text) - 2];
											
											$params['receiverzipcode']		= $kdpos;
											$json 				= json_encode($params);
											$dpos = $this->posapp->getKantorTujuan2($json);
											if($dpos)
											{
												if($dpos->response->respcode == 00)
												{
													$result['kantor_tujuan_kirim']	= $dpos->response->destoffice;
													$result['kantor_tujuan_kirim_name']	= $dpos->response->descdestoffice;
												}
					
												$data_tracking	.= $text[1]."\n";
												$data_tracking	.= $text[3]."\n";
												$data_tracking .= "-------------------------------------------\n";
											}
											
									}
									
									$data_tracking .= "No Barcode : ".$data[$i]->barcode."\n";
									$data_tracking .= "Tgl / Aktivitas : ".$data[$i]->eventDate." / ".$data[$i]->eventName."\n";
									#$data_tracking .= "Kantor : ".$data[$i]->office." ".$data[$i]->officeCode."\n";
									$data_tracking .= "Kantor : ".$data[$i]->officeName." ".$data[$i]->officeCode."\n";
									$data_tracking .= "Deskripsi : ".$data[$i]->description."\n";
									$data_tracking .= "-------------------------------------------\n";
									
									
									$row	= array('barcode'=>$data[$i]->barcode,
													'officeCode'=>$data[$i]->officeCode,
													'office'=>$data[$i]->officeName,
													'eventName'=>$data[$i]->eventName,
													'eventDate'=>$data[$i]->eventDate,
													'description'=>$data[$i]->description);
									array_push($result['rows'], $row);
								}
							}else{
								$data	= $var->response->data;
								$text 	= explode(";", $data->description);
								$kdpos 	= $text[count($text) - 2];
								
								
								$params['receiverzipcode']		= $kdpos;
								$json 				= json_encode($params);
								$dpos = $this->posapp->getKantorTujuan($json);
								if($dpos)
								{
									if($dpos->response->respcode == 00)
									{
										$result['kantor_tujuan_kirim']	= $dpos->response->destoffice;
										$result['kantor_tujuan_kirim_name']	= $dpos->response->descdestoffice;
									}
	
									$data_tracking	.= $text[1]."\n";
									$data_tracking	.= $text[3]."\n";
									$data_tracking .= "-------------------------------------------\n";
										
									$data_tracking .= "No Barcode : ".$data[$i]->barcode."\n";
									$data_tracking .= "Tgl / Aktivitas : ".$data[$i]->eventDate." / ".$data[$i]->eventName."\n";
									$data_tracking .= "Kantor : ".$data[$i]->office." ".$data[$i]->officeCode."\n";
									$data_tracking .= "Deskripsi : ".$data[$i]->description."\n";
									$data_tracking .= "-------------------------------------------\n";
								}
							}
						}
					}
					
				}
				
				
				
				
			
				$result['tracking_ticket']	= $data_tracking;
			}
		}else{
			$result['new_ticket']		= false;
			$result['addons']			= true;
			$result['addons_value']		= $no_ticket['addon'];
			$result['ticket_info']		= $no_ticket['history'];
			$result['ticket_id']		= $no_ticket['tid'];
			$result['ticket_recreate']	= $no_ticket['recreate'];
		}

		
		echo json_encode($result);
	}

	// API POS
	public function getJasKeu()
	{
		$this->load->library('Posapp');

		$params['payload']		= 'xxx';
		$json 				= json_encode($params);
		$var 				= $this->posapp->getJasKeu($json);	
		$output 		= "";
		if(isset($var->DATAS))
		{ 
			$xoutput 	= json_decode(json_encode($var->DATAS), true); 
			$output 	.= implode(', ', array_map(
				function ($v, $k) {
					if(is_array($v)){
						return $k.'[]='.implode('&'.$k.'[]=', $v);
					}else{
						return $k." : ".$v."\n";
					}
				}, 
				$xoutput, 
				array_keys($xoutput)
			));
			//echo implode("\n", $output);
			
		}
		echo '<pre>';
		print_r($output);
		echo '</pre>';
	}
	public function getReceiverOffice(){
		$this->load->library('Posapp');
		
		$params['receiverzipcode']		= "30226";
		$json 				= json_encode($params);
		$var = $this->posapp->getKantorTujuan($json);
		echo '<pre>';
		print_r($var->response);
		echo '</pre>';
	}
	public function tracking_detail()
	{
		$no_resi 		= $this->input->post('resi');

		$this->load->library('Posapp');
		$params['resi']		= $no_resi;
		$json 				= json_encode($params);
		
		$var 	= $this->posapp->getTrackingDetail($json);
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}

	public function trackandtrace()
	{
		$this->load->library('Posapp');

		$no_resi 		= $this->input->post('resi');

		$params['barcode']		= $no_resi;
		$json 				= json_encode($params);
		
		//$result 	= (object)[];
		$result 	= array();
		$var 	= $this->posapp->getTrackingDetail($json);
		if(isset($var->response->data[0]))
		{
			$resData 	= $var->response->data;
			
			$lastDate	= "";
			$lastInfo	= "";
			for($i = 0; $i < count($resData); $i++)
			{
				$getDate 	= explode(' ', $resData[$i]->eventDate);
				
				if($lastInfo != $resData[$i]->eventDate)
				{
					
					#echo '<pre>';
					#print_r($resData[$i]);
					#echo '</pre>';
					$lastInfo 	= $resData[$i]->eventDate;

					$mresult['date']	= $getDate[0];
					$mresult['time']	= $getDate[1];
					$mresult['office']	= $resData[$i]->officeName."-".$resData[$i]->officeCode;
					$mresult['status']	= $resData[$i]->eventName;
					$mresult['info']	= str_replace("~~","<br>",$resData[$i]->description);
					$result[]			= $mresult;
				}
				
				

				/*
				if($lastDate != $getDate[0])
				{
					$lastDate = $getDate[0];
					//$result->date[]	= $lastDate;
					//$result['date'][][$lastDate][]	= array('time'=>$getDate[1], 'office'=>$resData[$i]->officeName."-".$resData[$i]->officeCode, 'status'=>$resData[$i]->eventName, 'info'=>$resData[$i]->description);
					
					//$mresult['date']	= array($lastDate, array('time'=>$getDate[1], 'office'=>$resData[$i]->officeName."-".$resData[$i]->officeCode, 'status'=>$resData[$i]->eventName, 'info'=>$resData[$i]->description));
					$lastInfo			= $resData[$i]->eventDate;
					$mresult['date']	= $lastDate;
					$mresult['time']	= $getDate[1];
					$mresult['office']	= $resData[$i]->officeName."-".$resData[$i]->officeCode;
					$mresult['status']	= $resData[$i]->eventName;
					$mresult['info']	= $resData[$i]->description;
					
				}else{
					//echo $lastInfo;
					
						$mresult['date']	= $lastDate;
						$mresult['time']	= $getDate[1];
						$mresult['office']	= $resData[$i]->officeName."-".$resData[$i]->officeCode;
						$mresult['status']	= $resData[$i]->eventName;
						$mresult['info']	= $resData[$i]->description;
						
					
						
					
					

					//if(in_array($lastDate, $mresult['date'])){
						//echo '<pre>';
						//print_r($mresult);
						//echo '</pre>';
						//echo $key 	= array_search($lastDate, $mresult['date']);
						//echo '<br>';
						//$mresult['date'][$key][$lastDate]	= array('time'=>$getDate[1], 'office'=>$resData[$i]->officeName."-".$resData[$i]->officeCode, 'status'=>$resData[$i]->eventName, 'info'=>$resData[$i]->description);
						//$mresult['date'][]	= $lastDate;
						//$mresult['date'][$lastDate]	= array('time'=>$getDate[1], 'office'=>$resData[$i]->officeName."-".$resData[$i]->officeCode, 'status'=>$resData[$i]->eventName, 'info'=>$resData[$i]->description);
					//}
					//array_push($result['date'])
					//$result->date$lastDate;
					//array_push($result['date'][$lastDate], array('time'=>$getDate[1], 'office'=>$resData[$i]->officeName."-".$resData[$i]->officeCode, 'status'=>$resData[$i]->eventName, 'info'=>$resData[$i]->description));
					//$result[$lastDate]	= array('time'=>$getDate[1]);
					//$result[$lastDate]['time'][]	= $getDate[1];
					//$result[$lastDate][]	= array('time'=>$getDate[1]);
					//if( $result['date'][][$lastDate]
					//$result['date'][$lastDate][]	= array('time'=>$getDate[1], 'office'=>$resData[$i]->officeName."-".$resData[$i]->officeCode, 'status'=>$resData[$i]->eventName, 'info'=>$resData[$i]->description);
				}
				//echo $lastInfo.'===='.$resData[$i]->eventDate;
				
				if( $lastInfo === $resData[$i]->eventDate){
					
				}
				*/
				//$result['date']	= array('date'=>$getDate[0],
				//					'time'=>$getDate[1]);
			}
		}
		
		echo json_encode($result);
	}
	public function tracking()
	{
		$no_resi 		= $this->input->post('resi');

		$this->load->library('Posapp');

		$value['rows']	= array();
		$value['xray']	= array();
		
		
		$xdata 	= $this->mglobals->check_xray($no_resi);
		if($xdata->num_rows() > 0)
		{
			$row 				= $xdata->row();
			#$row['xray']		= array('id_kiriman'=>$row->id_kiriman,
			#							'kantor_penerbangan'=>$row->kantor_aduan.' - '.$row->kode_kantor_aduan,
			#							'asal_kiriman'=>$row->kantor_asal.' - '.$row->kode_kantor_asal,
			#							'tujuan_kiriman'=>$row->kantor_tujuan.' - '.$row->kode_kantor_tujuan,
			#							'isi'=>$row->isi,
			#							'tgl'=>$row->tgl_input,
			#							'keterangan'=>$row->keterangan);
			$row	= array('barcode'=>$row->id_kiriman,
							'officeCode'=>$row->kantor_aduan,
							'office'=>$row->kode_kantor_aduan,
							'eventName'=>'GAGAL XRAY',
							'eventDate'=>$row->tgl_input,
							'description'=>"Kantor Asal : ".$row->kantor_asal." - ".$row->kode_kantor_asal."<br>Kantor Tujuan : ".$row->kantor_tujuan." - ".$row->kode_kantor_tujuan."<br>ISI : ".$row->isi_kiriman."<br>Keterangan : ".$row->keterangan);
			array_push($value['rows'], $row);
		}else{
			$params['resi']		= $no_resi;
			$json 				= json_encode($params);
			
			$var 	= $this->posapp->getTracking($json);
			
			if(isset($var->serviceCode)){
				echo 'GAGAL';
			}
			
			$data 			= $var->rs_tnt->r_tnt;
			$total 			= count($data);
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
			
		}
		
		
		echo json_encode($value);
	}
	
	public function tracking_only()
	{
		$no_resi 		= $this->input->post('resi');

		$this->load->library('Posapp');

		$value['rows']	= array();
		$value['xray']	= array();
		
		
		$params['resi']		= $no_resi;
		$json 				= json_encode($params);
		
		$var 	= $this->posapp->getTracking($json);
		if(isset($var->serviceCode)){
			echo 'GAGAL';
		}
		
		$data 			= $var->rs_tnt->r_tnt;
		$total 			= count($data);
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
		echo json_encode($value);
	}
	
	public function last_tracking()
	{
		$no_resi 	= $this->input->post('no_resi');
		
		$this->load->library('Posapp');
		
		$params['resi']		= $no_resi;
		$json 				= json_encode($params);
		
		$var 	= $this->posapp->getLastStatusAwb($json);
		if(isset($var->serviceCode)){
			echo 'GAGAL';
		}
		
		$data 			= $var->responses->response;
		$row			= array('barcode'=>$data->barcode,
								'eventDate'=>$data->eventDate,
								'office'=>$data->office,
								'eventName'=>$data->eventName,
								'description'=>$data->description,
								'senderName'=>$data->senderName,
								'senderCity'=>$data->senderCity,
								'receiverName'=>$data->receiverName,
								'receiverCity'=>$data->receiverCity);
		
		echo json_encode($row);
	}

	public function load_province()
	{
		$this->load->library('Posapp');

		$key 	= $this->input->post('q');

		$params['provinceName']		= $key;
		$json 	= json_encode($params);
		
		
		$value 			= array();
		//if(isset($var->serviceCode)){
		$var 	= $this->posapp->getPropinsi($json);
		if(!isset($var->responses->response[0])){
			$value[] 	= array('id'=>00, 'text'=>'Tidak ditemukan');	
		}else{
			$data 			= $var->responses->response;
			$total_data 	= count($data);
			for($i = 0; $i < $total_data; $i++)
			{
				$value[] 	= array('id'=>$data[$i]->provinceCode, 'text'=>$data[$i]->provinceName);	
			}
		}
		echo json_encode($value);
		
	}

	public function load_city()
	{
		$this->load->library('Posapp');

		$key 		= $this->input->post('search');
		$province 	= $this->input->post('province');

		#$params['provinceCode']		= $province;
		#$params['cityName']			= $key;
		$params['payload']			= $key;
		$json 	= json_encode($params);
		$var 	= $this->posapp->getKota($json);

		$value 			= array();
		if(!isset($var->responses->response[0])){
			$value[] 	= array('id'=>00, 'text'=>'Tidak ditemukan');	
		}else{
			$data 			= $var->responses->response;
			$total_data 	= count($data);
			for($i = 0; $i < $total_data; $i++)
			{
				$value[] 	= array('id'=>$data[$i]->cityCode, 'text'=>$data[$i]->cityType.' '.$data[$i]->cityName);	
			}
		}
		echo json_encode($value);
	}

	public function load_kecamatan()
	{
		$this->load->library('Posapp');

		$key 		= $this->input->post('search');
		$province 	= $this->input->post('parent');

		$params['cityCode']					= $province;
		$params['subDistrictName']			= $key;
		$json 	= json_encode($params);
		$var 	= $this->posapp->getKecamatan($json);
		echo '<pre>';
		print_r($var);
		echo '</pre>';
		exit;
		$value['rows'] 		= array();
		
		$data 	 	= $var->responses->response;
		$propinsi 	= $data->provinceName;
		$kota 		= $data->data->city->cityType.' '.$data->data->city->cityName;
		$kecamatan 	= $data->data->city->data->subdistrict;
		
		$total_data 	= count($kecamatan);
		for($i = 0; $i < $total_data; $i++)
		{
			if(isset($kecamatan[$i]->data->subsubdistrict)){
				$kelurahan			= $kecamatan[$i]->data->subsubdistrict;

				#print_r($kelurahan);
				$total_kelurahan 	= count($kelurahan);
				for($z= 0; $z<$total_kelurahan; $z++)
				{
					
					$data_row 	= array('propinsi'=>$propinsi, 
								'kota'=>$kota,
								'kecamatan'=>$kecamatan[$i]->subDistrictType.' '.$kecamatan[$i]->subDistrictName,
								'kelurahan'=>$kelurahan[$z]->subSubDistrictType.' '.$kelurahan[$z]->subSubDistrictName,
								'postalCode'=>$kelurahan[$z]->postalCode);	
					
								array_push($value['rows'], $data_row);
					//$value[]	= $data_row;	
				}		
			}
		}
		
		echo json_encode($value);
	}

	public function load_kodepos()
	{
		$this->load->library('Posapp');

		$key 		= $this->input->post('search');
		$explode 	= explode(' ', $key, 2);
		$tkey 		= count($explode);

		if($tkey > 1){
			$city 	= $explode[0];
			$address= $explode[1];
		}else{
			$city 	= $key;
			$address= $key;
		}
		
		$params['city']			= $city;
		$params['address ']		= $address;
		#$params['provinceCode']		= $province;
		#$params['cityName']			= $key;
		//$params['payload']			= $key;
		$json 	= json_encode($params);
		$value 			= array();
		$var 	= $this->posapp->getKodePos($json);
		
		if(!isset($var->rs_postcode->r_postcode[0])){
			$value[] 	= array('id'=>00, 'text'=>'Tidak ditemukan');	
		}else{
			$data 			= $var->rs_postcode->r_postcode;
			$total_data 	= count($data);
			for($i = 0; $i < $total_data; $i++)
			{
				if($data[$i]->city == 'NEGARA'){
					$value[] 	= array('id'=>$data[$i]->posCode, 'text'=>$data[$i]->city.' - '.$data[$i]->address.' - '.$data[$i]->posCode);	
				}else{
					$value[] 	= array('id'=>$data[$i]->posCode, 'text'=>$data[$i]->city.''.$data[$i]->address.' - '.$data[$i]->posCode);	
				}
				
			}
		}
		echo json_encode($value);
	}

	public function ongkir()
	{
		$this->load->library('Posapp');

		$params['customerid']		= "";
		$params['desttypeid']		= 1;
		$params['itemtypeid']		= $this->input->post('item_type');
		$params['shipperzipcode']	= $this->input->post('kodepos_asal');
		$params['receiverzipcode']	= $this->input->post('kodepos_tujuan');
		$params['length']			= $this->input->post('panjang');
		$params['width']			= $this->input->post('lebar');
		$params['height']			= $this->input->post('tinggi');
		$params['weight']			= $this->input->post('weight');
		$params['valuegoods ']		= $this->input->post('harga');

		$json 	= json_encode($params);

		$var 	= $this->posapp->getFee($json);

		if(isset($var->serviceCode)){
			$value['api_result']	= false;
			$value['api_response']	= "Terjadi kesalahan.";
		}else{
			$jenis_tujuan_kirim	= $params['desttypeid'] == 1 ? 'Domestik' : 'International';
			$jenis_kiriman		= $params['itemtypeid'] == 1 ? 'Paket' : 'Surat';

			$text 	= "";
			$text	.= "Kode Pelanggan : ".$params['customerid']."\n";
			$text	.= "Jenis Tujuan Kiriman : ".$jenis_tujuan_kirim."\n";
			$text	.= "Jenis Kiriman : ".$jenis_kiriman."\n";
			$text	.= "Kode Pos Asal Kiriman : ".$params['shipperzipcode']."\n";
			$text	.= "Kode Pos Tujuan Kiriman : ".$params['receiverzipcode']."\n";
			$text	.= "Dimensi : ".$params['length']." x ".$params['width']." x ".$params['height']."\n";
			$text	.= "Berat : ".$params['weight']." (gram)\n";
			$text	.= "Nilai Barang Kiriman : ".$params['weight']." (gram)\n";
			$text	.= "----------------------------\n";
			
			$value['api_result']	= false;
			$value['rows']			= array();
			$data 			= $var->rs_fee->r_fee;
			$total_data 	= count($data);
			for($i = 0; $i < $total_data; $i++)
			{
				$rows 	 	= array('name'=>$data[$i]->serviceName,
									'ongkir'=>$data[$i]->fee,
									'ongkir_tax'=>$data[$i]->feeTax,
									'insurance'=>$data[$i]->insurance,
									'insuranceTax'=>$data[$i]->insuranceTax,
									'totalFee'=>$data[$i]->totalFee,
									'harga'=>$data[$i]->itemValue);
				array_push($value['rows'], $rows);
				$ongkir 	= $data[$i]->fee + $data[$i]->feeTax;
				$asuransi 	= $data[$i]->insurance + $data[$i]->insuranceTax;
				$total 		= $data[$i]->totalFee;

				$text	.= "Layanan : ".$data[$i]->serviceName." ".$data[$i]->serviceCode;
				$text	.= "Ongkir : ".$ongkir;
				$text	.= "Asuransi : ".$asuransi;
				$text	.= "Total : ".$total;
				$text	.= "Harga Barang : ".$data[$i]->itemValue;
				$text	.= "Catatan : ".$data[$i]->notes;
				$text	.= "----------------------------\n";
			}

			$value['text']		 	= $text;
			
			#echo $total_option 	= count($var->rs_fee->r_fee);
			

		}
		echo json_encode($value);
	}

	public function kantor_pos()
	{
		$this->load->library('Posapp');

		$value['json']	= array();
		$value['rows']	= array();

		$params['city']			= $this->input->post('kota');
		$params['address ']		= $this->input->post('area');

		$json 	= json_encode($params);

		$var 	= $this->posapp->getKantorPos($json);
		if(!isset($var->responses->response[0]))
		{
			$value['json'][] 	= array('id'=>00, 'text'=>'Tidak ditemukan');	
		}else{
			$data			= $var->responses->response;
			for($i = 0; $i < count($data); $i++)
			{
				$value['json'][] 	= array('id'=>$data[$i]->office_id, 'text'=>$data[$i]->office_name.' '.$data[$i]->address.' - '.$data[$i]->schedule,'info'=>$data[$i]->schedule);	
				
				$rows 	 	= array('office_id'=>$data[$i]->office_id,
									'office_name'=>$data[$i]->office_name,
									'type'=>$data[$i]->type,
									'address'=>$data[$i]->address.' '.$data[$i]->city.' '.$data[$i]->sub_district.' '.$data[$i]->sub_sub_district.' '.$data[$i]->zipcode,
									'phone'=>$data[$i]->phone,
									'schedule'=>$data[$i]->schedule);
				array_push($value['rows'], $rows);
			}
		}
		
		
		
		#echo '<pre>';
		#print_r($data);
		#echo '</pre>';
		
		
		/*
		$value['rows']	= array();
		$data 			= $var->rs_fee->r_fee;
		$total_data 	= count($data); 
		 
		*/
		#echo $total_option 	= count($var->rs_fee->r_fee);
		echo json_encode($value);
		

	}

	public function get_kantor_pos()
	{
		$search 	= $this->input->post('city');

		$value		= array();
		$query 		= $this->mglobals->get_kantor_pos($search);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value, $row);
			}
		}

		echo json_encode($value);
	}

	public function get_kantor_pos_tujuan()
	{
		$search 	= $this->input->post('city');

		$value		= array();
		$query 		= $this->mglobals->get_kantor_pos_tujuan($search);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value, $row);
			}
		}

		echo json_encode($value);
	}

	public function get_negara()
	{
		$search 	= $this->input->post('city');

		$value		= array();
		$query 		= $this->mglobals->get_negara($search);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				array_push($value, $row);
			}
		}

		echo json_encode($value);
	}

	public function kantor_posdd()
	{
		$this->load->library('Posapp');

		$params['city']			= $this->input->post('city');
		$params['address ']		= $this->input->post('address');
		
		$json 	= json_encode($params);

		$var 	= $this->posapp->getKantorPos($json);
	
		if(isset($var->serviceCode)){
			echo 'GAGAL';
		}

		
		$value['rows']	= array();
		$data			= $var->responses->response;
		$total 			= count($data);

		for($i = 0; $i < $total; $i++)
		{
			$rows 	 	= array('id'=>$data[$i]->office_id,
								'text'=>$data[$i]->office_name.' - '.$data[$i]->office_id);
			array_push($value['rows'], $rows);
		}
		echo json_encode($value['rows']);
		

	}
	
	public function kantor_posdd_new()
	{
		$this->load->library('Posapp');

		$params['city']			= $this->input->post('city');
		$params['address ']		= $this->input->post('address');
		
		$json 	= json_encode($params);

		$var 	= $this->posapp->getKantorPos($json);
	
		if(isset($var->serviceCode)){
			echo 'GAGAL';
		}

		
		$value['rows']	= array();
		$data			= $var->responses->response;
		$total 			= count($data);

		for($i = 0; $i < $total; $i++)
		{
			$rows 	 	= array('id'=>$data[$i]->office_id."|".$data[$i]->office_name,
								'text'=>$data[$i]->office_name.' - '.$data[$i]->office_id);
			array_push($value['rows'], $rows);
		}
		echo json_encode($value['rows']);
		

	}

	public function testapi()
	{
		$no_resi 		= $this->input->post('resi');

		$this->load->library('Posapp');

		/*
		$params['city']		= "Bandung";
		$params['address']	= "Bandung";
		$json 				= json_encode($params);
		$var = $this->posapp->getKantorPos($json);
		*/

		$params['desttypeid']	= "1";
		$params['itemtypeid']	= "1";
		$params['shipperzipcode']	= "40351";
		$params['receiverzipcode']	= "40352";
		$params['weight']	= 1000;
		$params['length']	= 0;
		$params['width']	= 0;
		$params['height']	= 0;
		$params['diameter']	= 0;
		$params['valuegoods']	= 5000;

		

		$json 				= json_encode($params);

		echo $json;
		$var = $this->posapp->getFee($json);
		echo '<h4>via Utility</h4>';
		echo '<pre>';
		print_r($var);
		echo '</pre>';

		$var = $this->posapp->getFee2($json);
		echo '<h4>via Utilitas</h4>';
		echo '<pre>';
		print_r($var);
		echo '</pre>';
		/*
		$params['resi']		= 17401433608;
		$json 				= json_encode($params);
		#$var 	= $this->posapp->getTrackingDetail($json);
		$var 	= $this->posapp->getTracking($json);
		*/

		/*
		$params['receiverzipcode']		= "40226";
		$json 				= json_encode($params);
		$var 	= $this->posapp->getKantorTujuan($json);
		*/
	}

	public function testgetCity()
	{
		$this->load->library('Posapp');

		$key 		= $this->uri->segment(3);
		
		//$province 	= $this->input->post('province');

		#$params['provinceCode']		= $province;
		#$params['cityName']			= $key;
		$params['payload']			= $key;
		$json 	= json_encode($params);
		$var 	= $this->posapp->getKota($json);
		print_r($var);
	}

	public function getKantorPos()
	{
		$this->load->library('Posapp');

		$params['city']			= $this->uri->segment(3);
		$params['address ']		= $this->uri->segment(4);
		
		$json 	= json_encode($params);

		$var 	= $this->posapp->getKantorPos($json);
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}

	public function getKodePos()
	{
		$this->load->library('Posapp');

		$params['city']			= $this->uri->segment(3);
		$params['address ']		= $this->uri->segment(4);
		$json 	= json_encode($params);
		$var 	= $this->posapp->getKodePos($json);
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}

	public function testapiOffice()
	{
		$no_resi 		= $this->input->post('resi');

		$this->load->library('Posapp');

		/*
		$params['city']		= "Bandung";
		$params['address']	= "Bandung";
		$json 				= json_encode($params);
		$var = $this->posapp->getKantorPos($json);
		*/
		/*
		$params['resi']		= 17401433608;
		$json 				= json_encode($params);
		#$var 	= $this->posapp->getTrackingDetail($json);
		$var 	= $this->posapp->getTracking($json);
		*/

		
		$params['receiverzipcode']	= "40226";
		$json 						= json_encode($params);
		echo $json;
		$var 						= $this->posapp->getKantorTujuan($json);
		echo '<h4>via Utility</h4>';
		echo '<pre>';
		print_r($var);
		echo '</pre>';

		$var 						= $this->posapp->getKantorTujuan2($json);
		echo '<h4>via Utilitas</h4>';
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}	


	public function testapiTrackTrace()
	{
		$no_resi 		= $this->input->post('resi');

		$this->load->library('Posapp');

		$params['barcode']		= 172700854321;
		$json 				= json_encode($params);
		echo $json;
		$var 	= $this->posapp->getTracking($json);
		

		echo '<h4>via Utility</h4>';
		echo '<pre>';
		print_r($var);
		echo '</pre>';

		$var 	= $this->posapp->getTracking2($json);
		echo '<h4>via Utilitas</h4>';
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}	

	// END API POS
	
	public function index()
	{
		show_404();
	}
	
	public function contacts()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$id			= $this->input->post('cid');
		$value		= array();
		$this->db->select('id, name, phone_number, status, email, address', false);
		$this->db->where('id', $id);
		$query	= $this->db->get('contacts');
		#echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= array('id'=>$row->id, 'name'=>$row->name, 'phone'=>$row->phone_number, 'status'=>$row->status, 'email'=>$row->email, 'address'=>$row->address);
		}
		
		echo json_encode($value);
		
	}
	
	public function ticket()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$id			= $this->input->post('cid');
		$value		= array();
		$this->db->select('id, phone_number, subject, category, complaint, status', false);
		$this->db->where('id', $id);
		$query	= $this->db->get('ticket');
		#echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= array('id'=>$row->id, 'subject'=>$row->subject, 'phone'=>$row->phone_number, 'status'=>$row->status, 'category'=>$row->category, 'complaint'=>$row->complaint);
		}
		
		echo json_encode($value);
	}
	
	public function ticketDetail()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$this->load->helper('text');
		$id			= $this->input->post('cid');
		
		$value['data']		= array();
		$value['media']	= array();
		$query		= $this->mglobals->ticketDetail($id);
		if($query->num_rows() > 0)
		{
			$row			= $query->row();
			
			$tujuan 				= explode(',', $row->assignee);
			$assignee 				= $this->mglobals->get_kantor_name_code($tujuan);
			$row->tujuan_pengaduan 	= $assignee;
			$row->asal_pengaduan 	= $this->mglobals->get_kantor_name_code($row->complaint_origin);
			//$row->assignee_val	= ($assignee != "") ? $assignee : "-";
			//$row->assignee_text = str_replace('|',' - ',$row->assignee_val);
			//$row->assignee_opt	= $row->assignee_val.'_'.str_replace('|',' - ',$row->assignee_val);
			$row->date_ago 		= time_elapsed_string($row->date);
			$row->new_date 		= date("d/m/y", strtotime($row->date));
			
			if(in_array($this->pos_office, $tujuan)){
				$allow 			= 'destination';
			}else if($this->pos_office == $row->complaint_origin){
				$allow 			= 'origin';
			}else{
				$allow 			= 'guest';
			}
			$row->user_type = $allow;

			$row->complaint 	= nl2br($row->complaint);
			
			$row->avatar		= generateInitialName($row->user_cch);
			$row->class_first 	= ( ($row->status == 99) && ($row->date == $row->last_update)) ? 'bg-success-trans' : 'bg-warning-trans';
			if($row->channel == 1){
				$info_channel 	= $row->channel_name.' - '.$row->phone;
			}else if($row->channel == 2){
				$info_channel 	= $row->channel_name.' - '.$row->instagram;
			}else if($row->channel == 3){
				$info_channel 	= $row->channel_name.' - '.$row->twitter;
			}else if($row->channel == 4){
				$info_channel 	= $row->channel_name.' - '.$row->facebook;
			}else if($row->channel == 5){
				$info_channel 	= $row->channel_name.' - '.$row->email;
			}else{
				$info_channel 	= $row->channel_name.' - '.$row->phone;
			}
			$row->info_channel 	= $info_channel;
			
			$value['data']	= $row;

			$value['history']	= array();
			if($row->awb != "")
			{
				$subquery 		= $this->mglobals->get_awb_history($row->awb, $row->no_ticket, $row->date);
				if($subquery->num_rows() > 0)
				{
					foreach($subquery->result() as $rows)
					{
						$rows->url_ticket 	= base_url('ticket/d/'.$rows->id);
						array_push($value['history'], $rows);
					}
				}
			}
		}

		
		$query 		= $this->mglobals->get_ticket_media($id, null);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($row->file_ext == 'pdf'){
					$icon 	= 'fa-file-pdf-o';
				}else if( ($row->file_ext == 'xls') || ($row->file_ext == 'xlsx')){
					$icon 	= 'fa-file-excel-o';
				}else if( ($row->file_ext == 'doc') || ($row->file_ext == 'docx')){
					$icon 	= 'fa-file-word-o';
				}else if( ($row->file_ext == 'jpg') || ($row->file_ext == 'png') || ($row->file_ext == 'jpeg') || ($row->file_ext == 'gif')){
					$icon 	= 'fa-file-image';
				}else {
					$icon 	= 'fa-file-o';
				}
				#$row->file_name = ellipsize($row->file_name, 32, .5);
				$row->icon 	= $icon;

				array_push($value['media'], $row);
			}
		}


		

		
		
		echo json_encode($value);
	}

	public function get_ticket_history()
	{
		$ticket 	= $this->input->post('tid') ? $this->input->post('tid') : $this->uri->segment(3);
		$data['ticket']		= $this->mglobals->ticketDetail($ticket);
		$data['response']	= $this->mglobals->ticketResponse($ticket);
		echo $this->load->view('ticket_history', $data, true);
	}
	
	// get User Data
	public function getUser()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$value	= array();
		$uid	= $this->input->post('cid', true);
		$data	= array();
		$query	= $this->mglobals->getUserData($uid);
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$data	= array('status'=>'OK',
							'fullname'=>$row->title,
							'username'=>$row->username,
							'is_admin'=>$row->is_admin,
							'status'=>$row->status,
							'email'=>$row->email,
							'role'=>$row->role_id,
							'id'=>$row->id);
		}
		echo json_encode($data);
	}
	
	public function getUserRole()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$value	= array();
		$query	= $this->mglobals->getUserRole();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
			
		}
		echo json_encode($value);
	}
	
	public function getRoleData()
	{
		if (!$this->input->is_ajax_request()){
			exit('No direct script access allowed');
		}
		
		$value	= array();
		$query	= $this->mglobals->getRoleData($this->input->post('cid'));
		if($query->num_rows() > 0)
		{
			$row	= $query->row();
			$value	= array('name'=>$row->name,
							'menuaccess'=>$row->menuaccess,
							'status'=>$row->status,
							'id'=>$row->id);
			
		}
		echo json_encode($value);
	}
	
	public function ticketCategory()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$value		= array();
		$this->db->select('id, name', false);
		$this->db->where('status', 1);
		$this->db->where('ticket_type','basic');
		$query	= $this->db->get('ticket_category');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		
		echo json_encode($value);
	}
	
	public function do_upload_tmp()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		/*
		$config['upload_path']          = 'storage/ticket/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|doc|docx|xls|xlsx|pdf';
		$config['max_size']             = 4092; // 4Mb
		$config['file_ext_tolower']     = true;
		$config['encrypt_name']     	= true;
		

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		#$upload_dir		= '';
		#$file			= $_FILES['files'];
		#$result         = array();
		for($i = 0; $i < count($file['name']); $i++)
		{
			$_FILES['mfile']['name']		= $file['name'][$i];
			$_FILES['mfile']['type']		= $file['type'][$i];
			$_FILES['mfile']['tmp_name']	= $file['tmp_name'][$i];
			$_FILES['mfile']['size']		= $file['size'][$i];
			$_FILES['mfile']['error']		= $file['error'][$i];
			
			
			if($this->upload->do_upload('mfile'))
			{
				$fileData = $this->upload->data();

				
			}
		}
		*/
		
		$upload_dir		= 'storage/ticket/';
		$file			= $_FILES['files'];
		$result         = array();
		for($i = 0; $i < count($file['name']); $i++)
		{
			$fname		= $file['name'][$i];
			$ftype		= $file['type'][$i];
			$ftmp		= $file['tmp_name'][$i];
			$fsize		= $file['size'][$i];
			$ferror		= $file['error'][$i];
			
			$res_upload	= 0;
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
					$data 	= array('file_name'=>$filename,
									'file_path'=>$upload_dir.$filename,
									'file_type'=>$ftype,
									'file_size'=>$fsize,
									'file_ext'=>$extension,
									'ticket_id'=>'',
									'response_id'=>'',
									'tmp_status'=>1,
									'user_cch'=>$this->active_user);
					$res_upload 	= $this->mglobals->media_file_tmp($data);
					
				}
			}	
		}

		if($res_upload > 0){
			$status = array('status'=>true, "msg"=>"upload berhasil");
		}else{
			$status = array('status'=>false, "msg"=>"upload gagal");
		}
        
        echo json_encode($status);
	}

	public function do_upload_response_tmp()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		/*
		$config['upload_path']          = 'storage/ticket/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|doc|docx|xls|xlsx|pdf';
		$config['max_size']             = 4092; // 4Mb
		$config['file_ext_tolower']     = true;
		$config['encrypt_name']     	= true;
		

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		#$upload_dir		= '';
		#$file			= $_FILES['files'];
		#$result         = array();
		for($i = 0; $i < count($file['name']); $i++)
		{
			$_FILES['mfile']['name']		= $file['name'][$i];
			$_FILES['mfile']['type']		= $file['type'][$i];
			$_FILES['mfile']['tmp_name']	= $file['tmp_name'][$i];
			$_FILES['mfile']['size']		= $file['size'][$i];
			$_FILES['mfile']['error']		= $file['error'][$i];
			
			
			if($this->upload->do_upload('mfile'))
			{
				$fileData = $this->upload->data();

				
			}
		}
		*/

		
		$upload_dir		= 'storage/ticket/';
		$file			= $_FILES['files'];
		$ticket_id 		= $this->input->post('ticket_id');
		$result         = array();
		for($i = 0; $i < count($file['name']); $i++)
		{
			$fname		= $file['name'][$i];
			$ftype		= $file['type'][$i];
			$ftmp		= $file['tmp_name'][$i];
			$fsize		= $file['size'][$i];
			$ferror		= $file['error'][$i];
			
			$res_upload	= 0;
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
					$data 	= array('file_name'=>$filename,
									'file_path'=>$upload_dir.$filename,
									'file_type'=>$ftype,
									'file_size'=>$fsize,
									'file_ext'=>$extension,
									'ticket_id'=>$ticket_id,
									'response_id'=>'',
									'tmp_status'=>1,
									'user_cch'=>$this->active_user);
					$res_upload 	= $this->mglobals->media_file_tmp($data);
					
				}
			}	
		}

		if($res_upload > 0){
			$status = array('status'=>true, "msg"=>"upload berhasil");
		}else{
			$status = array('status'=>false, "msg"=>"upload gagal");
		}
        
        echo json_encode($status);
	}

	public function load_media_uploaded()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$this->load->helper('text');
		$result 	= array();
		$query 	= $this->mglobals->load_media_uploaded($this->active_user);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($row->file_ext == 'pdf'){
					$icon 	= 'fa-file-pdf-o';
				}else if( ($row->file_ext == 'xls') || ($row->file_ext == 'xlsx')){
					$icon 	= 'fa-file-excel-o';
				}else if( ($row->file_ext == 'doc') || ($row->file_ext == 'docx')){
					$icon 	= 'fa-file-word-o';
				}else if( ($row->file_ext == 'jpg') || ($row->file_ext == 'png') || ($row->file_ext == 'jpeg') || ($row->file_ext == 'gif')){
					$icon 	= 'fa-file-images-o';
				}else {
					$icon 	= 'fa-file-o';
				}
				$row->file_name = ellipsize($row->file_name, 32, .5);
				$row->icon 	= $icon;
				array_push($result, $row);
			}
		}
		
		echo json_encode($result);
	}

	public function load_media_response_uploaded()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$this->load->helper('text');
		$ticket_id 	= $this->input->post('ticket_id');
		$result 	= array();
		$query 	= $this->mglobals->load_media_response_uploaded($ticket_id, $this->active_user);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if($row->file_ext == 'pdf'){
					$icon 	= 'fa-file-pdf-o';
				}else if( ($row->file_ext == 'xls') || ($row->file_ext == 'xlsx')){
					$icon 	= 'fa-file-excel-o';
				}else if( ($row->file_ext == 'doc') || ($row->file_ext == 'docx')){
					$icon 	= 'fa-file-word-o';
				}else if( ($row->file_ext == 'jpg') || ($row->file_ext == 'png') || ($row->file_ext == 'jpeg') || ($row->file_ext == 'gif')){
					$icon 	= 'fa-file-images-o';
				}else {
					$icon 	= 'fa-file-o';
				}
				$row->file_name = ellipsize($row->file_name, 32, .5);
				$row->icon 	= $icon;
				array_push($result, $row);
			}
		}
		
		echo json_encode($result);
	}

	public function remove_media()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$id 	= $this->input->post('mid');

		$result = $this->mglobals->remove_media($id);
		if($result > 0){
			$value 	= array('status'=>true, 'message'=>'Hapus berhasil');
		}else{
			$value 	= array('status'=>false, 'message'=>'Hapus gagal');
		}

		echo json_encode($value);
	}

	public function download_media()
	{
		$this->load->helper('download');

		$id 		= $this->input->post('mid');

		$query 		= $this->mglobals->download_media($id);
		if($query->num_rows() > 0)
		{
			$row 	= $query->row();
			$value 	= $row->file_path;
			if(file_exists($row->file_path)){
				$result 	= array('status'=>true, 'path'=>base_url($row->file_path), 'name'=>$row->file_name);
				//force_download($row->file_path, null);
			}else{
				$result 	= array('status'=>false, 'message'=>'File tidak ditemukan');
			}
		}else{
			$result 	= array('status'=>false, 'message'=>'File tidak ditemukan');
		}

		echo json_encode($result);
	}
	
	public function totalticketCategory()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$value['category']			= array();
		$value['most_complaint']	= array();
		$query		= $this->mglobals->getTotalTicketCategory($this->active_regional);
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value['category'][]	= $row;
			}
		}
		
		
		#$query->next_result();
		#		$query->free_result();
		$query		= $this->mglobals->getMostComplaintTicket();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				
				$value['most_complaint'][]	= array('product'=>$row->category_name, 'total'=>$row->total);
			}
		}
		
		/*
		$query		= $this->mglobals->getMostComplaintTicket();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value['most_complaint'][]	= array('product'=>$row->product_name, 'total'=>$row->total);
			}
		}
		*/
		echo json_encode($value);
	}
	
	public function ticket_list()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$id		= $this->input->post('cid', true);
		$value	= array();
		$query	= $this->mglobals->getTicketListCategory($id);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$row->url	= site_url('ticket/d/'.$row->id);
				$value[]	= $row;
			}
		}
		echo stripslashes(json_encode($value));
	}
	
	public function ticket_history()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		
		$id		= $this->input->post('cid', true);
		$value	= array();
		$query	= $this->mglobals->getTicketListHistory($id);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				//$value[]	= $row;
				$val		= array('id'=>$row->id,
									'no_ticket'=>$row->no_ticket,
									'date'=>$row->date,
									'date_ago'=>time_elapsed_string($row->date),
									'awb'=>$row->awb
									);
				$value[]	= $val;
			}
		}
		echo json_encode($value);
	}
	
	public function getticketStatus()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$value	= array();
		$query	= $this->mglobals->getTicketStatus();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		echo json_encode($value);
	}
	
	
	public function accountType($system_id = null)
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$value		= array();
		$this->db->select('id, name', false);
		$this->db->where('system_id', $system_id);
		$query	= $this->db->get('accounts_type');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$value[]	= $row;
			}
		}
		
		echo json_encode($value);
	}
	
	public function industryType($system_id = null)
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$value		= array();
		//$query				= $this->maccounts->datalist($search['value']);
		$this->db->select('id, name', false);
		$this->db->where('system_id', $system_id);
		$query	= $this->db->get('accounts_industry');
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