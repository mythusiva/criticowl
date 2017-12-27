<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slideshow_model extends CI_Model {
	
	function is_identifier_exists($identifier) {
		$sql = "SELECT 1
				FROM slideshow
				WHERE unique_identifier = ?";
				
		$q = $this->db->query($sql,array($identifier));
		
		if($q->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function get_slideshow_by_identifier($identifier) {
		$cache_id = __FUNCTION__.$identifier;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "select ss.title as slideshow_title, s.*
				from slideshow ss
				join slide s on ss.slideshow_pk = s.slideshow_fk
				where unique_identifier = ?
				order by `order` asc";
		$out = $this->db->query($sql,array($identifier))->result_array();
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	function get_slideshow_by_location($location_id) {
		$cache_id = __FUNCTION__.$location_id;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "select ss.title as slideshow_title, s.*, sl.*
				from slideshow_location sl
				join slideshow ss on sl.slideshow_fk = ss.slideshow_pk
				join slide s on ss.slideshow_pk = s.slideshow_fk
				where sl.location = ?
				order by `order` asc";
		$out = $this->db->query($sql,array($location_id))->result_array();
		
		set_cached_item($cache_id,$out,CACHE_TTL_SHORT);
		
		return $out;
	}
	
	function import_slideshow($slideshow_title,$slideshow_identifier,$slides) {
		/* slides need to be an array in the format:
		
		$slides = array('0' =>
							array(
									'order' => 0,
									'title' => 'first place',
									'label' => 'some desc text here',
									'image_url' => 'http://thispointssomewhere.com/jpg.png',
									'read_more_link' => '/section/50',
								),
						'1' => ...
						);
		 
		 */
		
		//sanity checks
		if(empty($slides)) {
			log_message("DEBUG","slides array is empty!");
			return;
		}
		
		if(!$this->is_identifier_exists($slideshow_identifier)) {
			
			$insert_slideshow_sql = "INSERT INTO `slideshow`
										(`title`,`date_created`,`date_modified`,`unique_identifier`)
									 VALUES
										(?,NOW(),NOW(),?);";
			$this->db->query($insert_slideshow_sql,array($slideshow_title,$slideshow_identifier));
			$slideshow_fk = $this->db->insert_id();
			
			$insert_slide_sql = "INSERT INTO `slide`
									(
									`slideshow_fk`,`title`,`label`,`image_url`,`read_more_link`,`order`)
								VALUES
									(?,?,?,?,?,?)";
			
			foreach($slides as $slide) {
				$this->db->query($insert_slide_sql,array($slideshow_fk,$slide['title'],$slide['label'],$slide['image_url'],$slide['read_more_link'],$slide['order']));
				log_message("DEBUG","Slide inserted ...");
			}
			
			
		} else {
			log_message("DEBUG","Slideshow already exists!");
			return;
		}
		
	}

	
}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
