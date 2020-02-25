<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Uploader 
 * @subpackage	Models
 * @author		wkecil@gmail.com
 * @license		http://webkecil.com/doc-license
 * @link		http://webkecil.com
 * @since		Version 1
 * 
 * 
 */
class Muploader extends CI_Model {
	
	private $media	= 'media';
	
	public function saveMedia($data)
	{
		return $this->db->insert($this->media, $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */