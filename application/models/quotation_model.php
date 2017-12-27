<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotation_model extends CI_Model {
	
	function add_quote($quote_text,$author,$media_fk) {
		delete_cached_item(CACHEID_SYSTEM_QUOTATIONS);
		
		$sql = "INSERT INTO quotation
					(quote,author,media_fk,date_created)
				VALUES
					(?,?,?,NOW())";
					
		$this->db->query($sql,array($quote_text,$author,$media_fk));
		
		return $this->db->insert_id();
	}
	
	function save_quote($quotation_pk,$quote_text,$author,$media_fk,$is_deleted=0) {
		delete_cached_item(CACHEID_SYSTEM_QUOTATIONS);

		$sql = "UPDATE quotation
				SET quote = ?,
					author = ?,
					media_fk = ?,
					is_deleted = ?
				WHERE quotation_pk = ?";

		$this->db->query($sql,array($quote_text,$author,$media_fk,$is_deleted,$quotation_pk));
	}
	
	function get_quotation_list() {
		$sql = "SELECT *
						FROM quotation 
						WHERE is_deleted = 0
						ORDER BY author ASC";
				
		return $this->db->query($sql)->result_array();
	}
	
	function get_quotation_by_pk($quotation_pk) {
		$sql = "SELECT *
				FROM quotation 
				WHERE quotation_pk = ? AND is_deleted = 0";
				
		return $this->db->query($sql,array($quotation_pk))->row_array();
	}
	
	function get_quotations() {
		
		if($out = get_cached_item(CACHEID_SYSTEM_QUOTATIONS))  {
			return $out;			
		}
		
		$sql = "SELECT *
				FROM quotation 
				WHERE is_deleted = 0";
				
		$out = $this->db->query($sql)->result_array();
		
		set_cached_item(CACHEID_SYSTEM_QUOTATIONS,$out);
		
		return $out;
	}
	
	
	
}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
