<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo_model extends CI_Model {
	
	function get_uris() {
		$sql = "SELECT * FROM sitemap_url";
		return $this->db->query($sql)->result_array();
	}
	
	function update_uri($uri,$priority) {
		$sql = "INSERT INTO sitemap_url (uri,priority,date_updated) VALUES (?,?,NOW())
				ON DUPLICATE KEY UPDATE priority=?, date_updated = NOW()";
		$this->db->query($sql,array($uri,$priority,$priority));		
	}
	
}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
