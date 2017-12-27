<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media_poll_model extends CI_Model {
	
	function cast_ballot_watched_it($media_fk) {
		if($this->_is_allowed_to_vote($media_fk,BALLOT_WATCHED_IT) === false) {
			return false;
		}
		
		$media_poll_user_fk = $this->get_or_create_poll_user($media_fk);
		
		$this->cast_ballot(BALLOT_WATCHED_IT,$media_fk,$media_poll_user_fk);
		
		$this->_set_cookie(BALLOT_WATCHED_IT,$media_fk);
	}
	
	function cast_ballot_want_to_watch_it($media_fk) {
		if($this->_is_allowed_to_vote($media_fk,BALLOT_WANT_TO_WATCH_IT) === false) {
			return false;
		}
		
		$media_poll_user_fk = $this->get_or_create_poll_user($media_fk);
		
		$this->cast_ballot(BALLOT_WANT_TO_WATCH_IT,$media_fk,$media_poll_user_fk);

		$this->_set_cookie(BALLOT_WANT_TO_WATCH_IT,$media_fk);
	}
	
	function cast_ballot_rating($media_fk,$rating_value) {
		if($this->_is_allowed_to_vote($media_fk,BALLOT_RATING) === false) {
			return false;
		}
		
		$media_poll_user_fk = $this->get_or_create_poll_user($media_fk);
		
		$this->cast_ballot(BALLOT_RATING,$media_fk,$media_poll_user_fk,$rating_value);

		$this->_set_cookie(BALLOT_RATING,$media_fk);
	}
	
	private function _lookup_max_vote($type) {
		switch ($type) {
			case BALLOT_WATCHED_IT:
				return MAX_VOTE_WATCHED_IT;
			case BALLOT_WANT_TO_WATCH_IT:
				return MAX_VOTE_WANT_TO_WATCH_IT;
			case BALLOT_RATING:
				return MAX_VOTE_RATING;
			default:
				return 15;
		}
	}
	
	private function _lookup_cookie_name($type) {
		switch ($type) {
			case BALLOT_WATCHED_IT:
				$cookie_name = 'ctoken_bwi';
				break;
			case BALLOT_WANT_TO_WATCH_IT:
				$cookie_name = 'ctoken_bwtwi';
				break;
			case BALLOT_RATING:
				$cookie_name = 'ctoken_br';
				break;
		}
		
		return $cookie_name;
	}
	
	private function _lookup_max_limit_column_name($type) {
		switch ($type) {
			case BALLOT_RATING:
				$column_name = 'is_max_rating_count_reached';
				break;
			case BALLOT_WATCHED_IT:
				$column_name = 'is_max_watched_it_count_reached';
				break;
			case BALLOT_WANT_TO_WATCH_IT:
				$column_name = 'is_max_want_to_watch_it_count_reached';
				break;
		}
		
		return $column_name;
	}
	
	private function _set_cookie($type,$media_fk) {
		
		$cookie_name = $this->_lookup_cookie_name($type);
		
		$current_cookie = $this->_get_cookie($type);
		
		$value = '';
		if($current_cookie) {
			$value = json_decode($current_cookie,TRUE);
		}
		
		if(!is_array($value)) {
			$value = '';
		}
		
		$value[] = $media_fk;
		
		setcookie($cookie_name,json_encode($value),time()+60*60*24*10000,'/',$this->config->item('cookie_url'),false,true); //10k days!
	}
	
	private function _get_cookie($type) {
		return $this->input->cookie($this->_lookup_cookie_name($type),TRUE);
	}
	
	private function _is_allowed_to_vote($media_fk,$type) {
		//check two things: cookie exists? not a banned ip?

		$current_cookie = $this->_get_cookie($type);
		if($current_cookie) {
			$value = json_decode($current_cookie,TRUE);
		
			if(in_array($media_fk,$value)) {
				return false;
			}
		}
		
		//check if allowed to vote, did they reach their max allowed?
		if($this->db->query("SELECT 1
							 FROM media_poll_user
							 WHERE media_fk = ?
							 AND ip_address = ?
							 AND ".$this->_lookup_max_limit_column_name($type)." = 1",
				array(
					$media_fk,
					$this->input->ip_address()
				)
			)->num_rows() > 0
		) {
			return false;
		}
		
		//check if they are at max
		if($this->db->query("select * 
							from media_poll_user_ballot mpub
							join media_poll_user mpu 
							on mpu.media_poll_user_pk = mpub.media_poll_user_fk
							where mpu.ip_address = ? 
							and mpu.media_fk = ?
							and ballot_type = ?",
				array(
					$this->input->ip_address(),
					$media_fk,
					$type
				)
			)->num_rows() >= $this->_lookup_max_vote($type)
		) {
			return false;
		}
		
		return true;

	}
	
	
	private function cast_ballot($type,$media_fk,$media_poll_user_fk,$value = 1) {
		$sql = "INSERT INTO media_poll_user_ballot (media_fk,media_poll_user_fk,ballot_type,date_created,value) VALUES (?,?,?,NOW(),?)";
		$this->db->query($sql,array($media_fk,$media_poll_user_fk,$type,$value));
	}
	
	private function get_or_create_poll_user($media_fk) {
		$ip_address = $this->input->ip_address();
		
		$sql = "INSERT IGNORE INTO media_poll_user (media_fk,ip_address) VALUES (?,?);";
		$this->db->query($sql,array($media_fk,$ip_address));
		
		$media_poll_user_pk = $this->db->insert_id();
		
		if(!($media_poll_user_pk > 0)) {
			$media_poll_user_pk = $this->db->query("SELECT media_poll_user_pk
													FROM media_poll_user
													WHERE media_fk = ? AND ip_address = ?",
													array($media_fk,$ip_address))->row()->media_poll_user_pk;
		}
		
		return $media_poll_user_pk;
	}
	
	function get_rating_scheme() {
		return $this->db->query("SELECT * FROM rating_system ORDER BY value ASC")->result_array();
	}
	
	public function get_criticowl_rating($media_fk) {
		$row = $this->db->query('SELECT criticowl_rating FROM media_stats WHERE media_fk = ?',array($media_fk))->row_array();
		
		if(empty($row['criticowl_rating'])) {
			return 0;
		} else {
			return $row['criticowl_rating'];
		}
	}
	
	function update_criticowl_rating($media_fk,$rating) {
		$rating = (float)$rating;
		
		$sql = "INSERT INTO media_stats
					(media_fk, criticowl_rating, last_updated)
				VALUES
					(?,?,NOW())
				ON DUPLICATE KEY UPDATE
					criticowl_rating = ?,
					last_updated = NOW()";
				
		$this->db->query($sql,array($media_fk,$rating,$rating));

		//need to update all other stats - only criticowl vote!
		$this->db->query("	update media_stats 
							set average_overall_rating = criticowl_rating 
							where (average_user_rating is null or average_user_rating = 0)
							and media_fk = ?", array($media_fk));

		//need to update all other stats - rerated media by criticowl
		$this->db->query("	update media_stats 
							set average_overall_rating = ((criticowl_rating+average_user_rating)/2) 
							where average_user_rating > 0 and media_fk = ?", array($media_fk));


	}

	// function get_vote_summary_today() {
	// 	return $this->db->query("	select m.name, m.type, mpub.ballot_type, mpub.value
	// 								from media_poll_user_ballot mpub
	// 								join media m
	// 								on m.media_pk = mpub.media_fk
	// 								where mpub.date_created >= CURDATE()
	// 								order by name, ballot_type;")->result_array();
	// }
	
	function get_media_stats_by_name($name) {
		//will return a row, if not will return false
		
		$sql = "SELECT ms.average_overall_rating, ms.criticowl_rating, CONCAT('review/',a.uri_segment) as uri, m.media_pk
				FROM media m 
				JOIN media_stats ms 
				ON m.media_pk = ms.media_fk 
				JOIN article a
				ON m.media_pk = a.media_fk AND a.is_live = 1 AND a.article_type = 'REVIEW'
				WHERE m.name = ?
				AND ms.criticowl_rating IS NOT NULL 
				LIMIT 1;";
		
		$out = $this->db->query($sql,array($name))->row_array();
		
		if(empty($out)) {
			return FALSE;
		} else {
			return $out;
		}
	}
}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
