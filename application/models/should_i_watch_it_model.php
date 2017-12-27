<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Should_i_watch_it_model extends CI_Model {
	
	function search_omdbapi($movie_name) {
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://www.omdbapi.com/?t='.urlencode($movie_name).'&tomatoes=true',
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		
		return $resp;
	}

	function search_imdb_api($movie_name) {
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://deanclatworthy.com/imdb/?q='.urlencode($movie_name),
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);

		return $resp;
	}

	function search_siwi_lookup($movie_name) {
		$movie_name = $this->db->escape_str($movie_name);
		
		$sql = "SELECT * FROM `siwi_lookup` WHERE `title` LIKE '%{$movie_name}%' ORDER BY year desc LIMIT 10;";

		return $this->db->query($sql,array($movie_name))->result_array();
	}

	function insert_update_siwi_lookup($media_fk="",$title,$year,$rating) {
		$sql = "INSERT IGNORE INTO `siwi_lookup`
					(`media_fk`,`title`,`year`,`rating`,`date_modified`)
				VALUES
					(?,?,?,?,NOW());";

		$this->db->query($sql,array($media_fk,$title,$year,$rating));
	}

	function update_siwi_lookup($media_fk,$title,$year,$rating) {
		$sql = "UPDATE `siwi_lookup`
				SET `media_fk` = ?, `rating` = ?, `date_modified` = NOW()
				WHERE `title` = ? AND `year` = ?;";

		$this->db->query($sql,array($media_fk,$rating,$title,$year));
	}

	function get_siwi_card_data($media_fk) {
		$cache_id = __FUNCTION__.md5("{$media_fk}");
		
		if($out = get_cached_item($cache_id)) {
			//return $out;
		}
		
		$sql = "select m.name,m.release_date,ms.criticowl_rating as rating,m.uri_segment as media_uri,a.uri_segment as article_uri
				from media m
				join article a on m.media_pk = a.media_fk and a.is_live = 1 and a.article_type = '".POST_REVIEW."'
				join media_stats ms on m.media_pk = ms.media_fk
				where m.media_pk = ?";

		$out = $this->db->query($sql,array($media_fk))->row_array();

		set_cached_item($cache_id,$out);
		
		return $out;
	}

}
	

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
