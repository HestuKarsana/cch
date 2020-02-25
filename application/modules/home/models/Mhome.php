<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  
 *
 * @package		Home Dashboard Models
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Mhome extends CI_Model{
	
	private $ticket			= 'ticket';
	private $ticket_status	= 'ticket_status';
	private $product 		= 'pos_product';
	
	public function dashboard_kprk_in($start = '', $end = "", $kprk = "")
	{	
		return $this->db->query('call sp_dashboard_kprk_in("'.$start.'", "'.$end.'", "'.$kprk.'")');
	}

	public function dashboard_kprk_out($start = '', $end = "", $kprk = "")
	{	
		return $this->db->query('call sp_dashboard_kprk_out("'.$start.'", "'.$end.'", "'.$kprk.'")');
	}

	public function dashboard_produk_kprk_in($start = '', $end = "", $kprk = "")
	{	
		return $this->db->query('call sp_dashboard_produk_kprk_in("'.$start.'", "'.$end.'", "'.$kprk.'")');
	}

	public function dashboard_produk_kprk_out($start = '', $end = "", $kprk = "")
	{	
		return $this->db->query('call sp_dashboard_produk_kprk_out("'.$start.'", "'.$end.'", "'.$kprk.'")');
	}

	public function dashboard_category_kprk_in($start = '', $end = "", $kprk = "")
	{	
		return $this->db->query('call sp_dashboard_category_kprk_in("'.$start.'", "'.$end.'", "'.$kprk.'")');
	}

	public function dashboard_category_kprk_out($start = '', $end = "", $kprk = "")
	{	
		return $this->db->query('call sp_dashboard_category_kprk_out("'.$start.'", "'.$end.'", "'.$kprk.'")');
	}

	

	public function dashboard_kprk_stat_in_out($start = "", $end = "", $kprk = "")
	{	
		return $this->db->query('call sp_dashboard_kprk_stat_in("'.$start.'", "'.$end.'", "'.$kprk.'")');
	}

	public function ticket_service_monthly($date = '')
	{
		$date 	= ($date != '')  ? $date : date('Y-m-d');

		$this->db->select('count(a.id) as total, a.service_type as label', false);
		$this->db->where('a.service_type !=',"");
		$this->db->where('year(a.date) = year("'.$date.'") and month(a.date) = month("'.$date.'")', null, false);
		$this->db->group_by('a.service_type');
		return $this->db->get($this->ticket.' a');
	}

	public function ticket_product_monthly($date = '')
	{
		$date 	= ($date != '')  ? $date : date('Y-m-d');
		$this->db->select('count(a.id) as total, b.name as label', false);
		$this->db->where('a.jenis_layanan !=',"");
		$this->db->where('year(a.date) = year("'.$date.'") and month(a.date) = month("'.$date.'")', null, false);
		$this->db->group_by('a.jenis_layanan');
		$this->db->join($this->product.' b','a.jenis_layanan = b.code');
		return $this->db->get($this->ticket.' a');
	}

	public function ticket_status_monthly($date = '')
	{
		$date 	= ($date != '')  ? $date : date('Y-m-d');
		$this->db->select('a.name as label, count(b.id) as total', false);
		$this->db->where('b.status !=',"");
		$this->db->where('year(b.date) = year("'.$date.'") and month(b.date) = month("'.$date.'")', null, false);
		$this->db->group_by('a.id');
		$this->db->where('a.status',1);
		$this->db->join($this->ticket.' b','a.id = b.status');
		
		return $this->db->get($this->ticket_status.' a');
	}

	public function average_close_ticket_monthly($date = '', $kprk = ''){

		$date 	= ($date != '')  ? $date : date('Y-m-d');
		$this->db->select('SEC_TO_TIME(COALESCE(AVG(TIMESTAMPDIFF(second, a.date,  DATE_ADD(a.date, interval TOTAL_WEEKDAYS_2(date(a.date), date(a.last_update)) day)) + TIME_TO_SEC(timediff(time(a.last_update), time(a.date)))),0))  as avg_time', false);
		$this->db->where('a.status',99);
		$this->db->where('year(a.date) = year("'.$date.'") and month(a.date) = month("'.$date.'")', null, false);
		$query 	= $this->db->get($this->ticket.' a');
		$row 	= $query->row();
		return $row->avg_time;
	}
	
	public function getTicketMonthly($date, $status)
	{
		$dexplode		= explode("-",$date);
		
		$select		= "COALESCE(DATE_FORMAT(a.`date`,'%m/%Y'),'".$dexplode[1]."/".$dexplode[0]."') AS periode, COUNT(IF(a.status = 4, 1, NULL)) AS `solved`, COUNT(IF(a.status = 7, 1, NULL)) AS `closed`"; 
		$this->db->select($select, false);
		$this->db->where('year(a.date)', $dexplode[0]);
		$this->db->where('month(a.date)', $dexplode[1]);
		$this->db->join($this->ticket_status.' b','a.status = b.id','LEFT');
		return $this->db->get($this->ticket.' a');
	}
	
	public function getTicketRepeat($date)
	{
		$dexplode		= explode("-",$date);
		return $this->db->query('call sp_repeated_complaint('.$dexplode[0].','.$dexplode[1].')');
	}
	
	public function getTicketQA($date)
	{
		$dexplode		= explode("-",$date);
		return $this->db->query('call sp_total_ticket_qa('.$dexplode[0].','.$dexplode[1].')');
	}
	
	public function getTicketOrder($date)
	{
		$dexplode		= explode("-",$date);
		return $this->db->query('call sp_total_ticket_order('.$dexplode[0].','.$dexplode[1].')');
	}
	
	public function getAverageCloseTicket()
	{
		return $this->db->query('call sp_average_time_close()');
	}
	
	public function getTicketResponse()
	{
		return $this->db->query('call sp_closed_by_system_percent()');
	}
	
	public function getTicketAll()
	{
		return $this->db->query('CALL sp_ticket_report()');
	}
	
	public function getTicketMUMAll()
	{
		return $this->db->query('CALL sp_ticket_mum_report()');
	}
	
	public function getTicketMUMAllNetto($date)
	{
		$dexplode		= explode("-",$date);
		return $this->db->query('call sp_sla_order_mum('.$dexplode[0].','.$dexplode[1].')');
	}
	
	public function getSolvedToClosed()
	{
		return $this->db->query('CALL sp_solved_closed("'.date('Y').'","'.date('m').'")');
		 
	}
	
	
}