<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media_model extends CI_Model {

	function is_movie_rated($media_fk) {
		$sql = "select 1
				from article
				where media_fk = ?
				and article_type = ?
				and is_live = 1";

		$q = $this->db->query($sql,array($media_fk,POST_REVIEW));

		if($q->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function get_missing_dvd_bluray_release_dates($number_of_days = RELEASE_DATE_BLURAY_THRESHOLD) {
		$sql = "select 
				    *
				from
				    media
				where
				    DATEDIFF(release_date, date('now')) <= -".$number_of_days."
				and
					(dvd_release_date IS null or bluray_release_date is null)
				order by release_date desc;";

		return $this->db->query($sql)->result_array();
	}	

	function add_movie($name,$release_date_epoch,$dvd_release_date_epoch=NULL,$bluray_release_date_epoch=NULL,$is_enabled = 1) {
		return $this->add_media($name,$release_date_epoch,MEDIA_MOVIE,$dvd_release_date_epoch,$bluray_release_date_epoch,$is_enabled);
	}

	function add_media($name,$release_date_epoch,$type,$dvd_release_date_epoch=NULL,$bluray_release_date_epoch=NULL,$is_enabled = 1) {
		
		$sql = "INSERT IGNORE INTO `media`
					(name,release_date,date_created,date_modified,type,uri_segment,hashtag,dvd_release_date,bluray_release_date,is_enabled)
				VALUES
					(?,?,date('now'),date('now'),?,?,?,?,?,?)";
						
		$this->db->query($sql,array($name,sql_format_release_date($release_date_epoch),$type,NULL,convert_to_hashtag($name),sql_format_release_date($dvd_release_date_epoch),sql_format_release_date($bluray_release_date_epoch),$is_enabled));
		
		$media_pk = $this->db->insert_id();
		$this->_update_uri_segment($media_pk,$name,$release_date_epoch);
		
		if($this->db->affected_rows() > 0) {
			clear_all_cache();
			return true;
		} else {
			return false;
		}
	}
	
	function save_edited_media( $media_fk, $media_title,$media_release_epoch,
								$dvd_release_date_epoch=NULL,$bluray_release_date_epoch=NULL,$is_enabled = 1) {
		$sql = "UPDATE `media`
				SET `name` = ?, `release_date` = FROM_UNIXTIME(?), `dvd_release_date` = FROM_UNIXTIME(?), `bluray_release_date` = FROM_UNIXTIME(?), `is_enabled` = ?
				WHERE `media_pk` = ?";
		
		$this->db->query($sql,array($media_title,$media_release_epoch,$dvd_release_date_epoch,$bluray_release_date_epoch,$is_enabled,$media_fk));

		$this->_update_uri_segment($media_fk,$media_title,$media_release_epoch);
		
		clear_all_cache();

		return TRUE;
	}
	
	function get_media_by_pk($media_pk) {
		$cache_id = __FUNCTION__.$media_pk;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "SELECT *
				FROM media
				WHERE media_pk = ?";
		$out = $this->db->query($sql,array($media_pk))->row_array();
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	function get_media_info_box_data($media_pk,$media_type) {
		$sql = "SELECT * 
				FROM media m
				LEFT JOIN media_stats ms ON ms.media_fk = m.media_pk
				WHERE m.media_pk = ?";
		$media_info_data = $this->db->query($sql,array($media_pk))->row_array();
		
		$media_info_data['average_overall_rating_label'] = $this->get_rating_system_label($media_info_data['average_overall_rating'],$media_type);
		$media_info_data['average_user_rating_label'] = $this->get_rating_system_label($media_info_data['average_user_rating'],$media_type);
		$media_info_data['criticowl_rating_label'] = $this->get_rating_system_label($media_info_data['criticowl_rating'],$media_type);
		
		return $media_info_data;
	}
	
	function get_rating_system_for_media($media_type) {
		$cache_id = __FUNCTION__.'_rating_system_'.$media_type;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		
		$sql = "SELECT *
				FROM rating_system
				WHERE media_type = ?
				ORDER BY value ASC";
		$out = $this->db->query($sql,array($media_type))->result_array();
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	function get_rating_system_label($rating_decimal,$media_type) {
		$cache_id = __FUNCTION__.md5("{$rating_decimal}-{$media_type}");
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		if($rating_decimal <= 1 && $rating_decimal > 0) {
			$rounded = round($rating_decimal,1,PHP_ROUND_HALF_UP);

			$sql = "SELECT label FROM rating_system WHERE value = ? and media_type = ?";
			$out = $this->db->query($sql,array($rounded,$media_type))->row()->label;
		} else {
			$out = 'Not yet available.';
		}
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	function get_media_list($type) {
		$cache_id = __FUNCTION__.$type;

		return [];
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "SELECT * 
						FROM article a
						JOIN media m ON a.media_fk = m.media_pk AND m.is_enabled = 1
						WHERE m.type = ?
						AND a.is_live = 1
						GROUP BY m.media_pk
						ORDER BY name ASC";
						
		$out = $this->db->query($sql,array($type))->result_array();
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	function get_none_type() {
		return $this->get_media_list(MEDIA_NONE);
	}
	function get_movie_list() {
		$movies = $this->get_media_list(MEDIA_MOVIE);

		foreach ($movies as &$m) {
			$char = substr($m['name'], 0, 1);

			if(ctype_alpha($char)) {
				$heading_letter = strtoupper($char);
			} else {
				$heading_letter = "#";
			}

			$m['heading_letter'] = $heading_letter;
		}

		return $movies;
	}
	
	function get_dropdown_lookup_pair() {
		
		$dd_data = $this->_get_complete_dropdown_list();
		
		$lookup_arr = array();
		$dropdown_list = array();
		
		foreach($dd_data as $row) {
			$lookup_arr[$row['label']] = $row['media_pk'];
			$dropdown_list[] = $row['label'];
		}
		
		return array('list' => $dropdown_list, 'lookup' => $lookup_arr);
	}
	
	private function _get_complete_dropdown_list() {
		$cache_id = __FUNCTION__;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		// title (release-date - TYPE) ie. Django Unchained (2013-01-04 - MOVIE)
		$sql = "SELECT
							media_pk,
							CONCAT(name,' (',DATE_FORMAT(release_date, '%Y-%m-%d'),' - ',type,')') as label,
							md5(CONCAT(name,' (',DATE_FORMAT(release_date, '%Y-%m-%d'),' - ',type,')')) as identifier
						FROM media;";
						
		$out = $this->db->query($sql)->result_array();
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	public function get_pk_by_uri_segment($uri_segment) {
		$cache_id = __FUNCTION__.md5($uri_segment);
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$q = $this->db->query("SELECT media_pk FROM media WHERE uri_segment = ? AND is_enabled = 1",array($uri_segment));
		
		if($q->num_rows() > 0) {
			$out = $q->row()->media_pk;
		} else {
			$out = 0;
		}
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	public function update_media_uris() {
		$media = $this->db->query("SELECT media_pk,name,release_date FROM media")->result_array();
		
		foreach($media as $m) {
			
			$uri_segment = $this->_generate_media_uri($m['media_pk'],$m['name'],strtotime($m['release_date']));
			
			$this->db->query('UPDATE media SET uri_segment = ? WHERE media_pk = ?',array($uri_segment,$m['media_pk']));
		}
		
	}
	
	private function _generate_media_uri($media_pk,$title,$epoch_release_date) {
		
		$year = date('Y',$epoch_release_date);
		if(!($year > 0) || empty($epoch_release_date)) {
			return $media_pk;
		}
		
		$str = trim($title);
		$str = strtolower($str);
		$str = str_replace("$","s",$str);
		$str = preg_replace("/[^A-Za-z0-9- ]/",'', $str);
		$str = preg_replace("/\s+/", ' ', $str);
		$str = str_replace(' ','-',$str);
		$str = preg_replace("/-+/", '-', $str);
		
		return "{$year}/{$str}";
	}

	// private function _sort_new_release_list($list) {
	// 	//containers: now playing items, dvd/bluray releases, movies upcoming soon 
	// 	$output_list['dvd_bluray_list'] = array();
	// 	$output_list['now_playing_list'] = array();
	// 	$output_list['upcoming_list'] = array();

	// 	foreach ($list as $l) {
	// 		if(
	// 			!empty($l['dvd_release_date']) || !empty($l['bluray_release_date']) &&
	// 			(strtotime($l['dvd_release_date']) <= time() || strtotime($l['bluray_release_date']) <= time())
	// 		) {
	// 			if($l['has_review'] == '0') {
	// 				continue;
	// 			}
	// 			//if the dvd bluray release date exists and release date has passed
	// 			$output_list['dvd_bluray_list'][] = $l;
	// 		} elseif(
	// 			(int)$l['days_left'] <= 0
	// 		) {
	// 			if($l['has_review'] == '0') {
	// 				continue;
	// 			}
	// 			$output_list['now_playing_list'][] = $l;
	// 		} else {
	// 			$output_list['upcoming_list'][] = $l;
	// 		}
	// 	}

	// 	return $output_list;
	// }

	function get_latest_dvd_bluray_releases($limit = 20) {
		$cache_id = __FUNCTION__.$limit;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}

		$sql = "SELECT 	m.*, (m.release_date - date()) as days_left, 
						1 as has_review, ms.criticowl_rating, am.*,
						a.image_link
				FROM media m
				LEFT JOIN affiliate_amazon am
				ON m.media_pk = am.media_fk 
				JOIN article a
					ON m.media_pk = a.media_fk AND a.article_type = '".POST_REVIEW."' AND a.is_live = 1
				JOIN media_stats ms
					ON m.media_pk = ms.media_fk AND ms.criticowl_rating IS NOT NULL
				WHERE
					m.is_enabled = 1 AND
					(
						(m.dvd_release_date - date()) <= 0 OR 	
						(m.bluray_release_date - date()) <= 0
					)
				ORDER BY m.release_date DESC 
				LIMIT {$limit};";

		$out = $this->db->query($sql)->result_array();

		set_cached_item($cache_id,$out);
		
		return $out;
	}

	function get_latest_now_playing_releases($limit = 25) {
		$cache_id = __FUNCTION__.$limit;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}

		$sql = "SELECT 	m.*, (m.release_date-date()) as days_left, 
						1 as has_review, ms.criticowl_rating,
						a.image_link
				FROM media m
				JOIN article a
					ON m.media_pk = a.media_fk AND a.article_type = '".POST_REVIEW."' AND a.is_live = 1
				JOIN media_stats ms
					ON m.media_pk = ms.media_fk AND ms.criticowl_rating IS NOT NULL
				WHERE 
					(m.release_date-date()) <= 0 AND (m.release_date-date()) > -60 AND
					((m.dvd_release_date-date()) > 0 OR m.dvd_release_date IS NULL) AND 
					((m.bluray_release_date-date()) > 0 OR m.bluray_release_date IS NULL) 
					AND m.is_enabled = 1
				ORDER BY m.release_date DESC LIMIT {$limit};";
		$out = $this->db->query($sql)->result_array();

		set_cached_item($cache_id,$out);
		
		return $out;
	}

	function get_latest_future_releases($limit = 15) {
		$cache_id = __FUNCTION__.$limit;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}

		$sql = "SELECT 	m.*, (m.release_date-date()) as days_left, 
						0 as has_review, NULL as criticowl_rating
				FROM media m 
				WHERE 
					(m.release_date-date()) > 0
					AND m.is_enabled = 1
				ORDER BY m.release_date ASC LIMIT {$limit};";
		$out = $this->db->query($sql)->result_array();

		set_cached_item($cache_id,$out);
		
		return $out;
	}

	function get_upcoming_releases() {
		$output_list['dvd_bluray_list'] = $this->get_latest_dvd_bluray_releases();
		$output_list['now_playing_list'] = $this->get_latest_now_playing_releases();
		$output_list['upcoming_list'] = $this->get_latest_future_releases();

		return $output_list;
	}
	
	function get_top_rated($limit = 15) {
		$cache_id = __FUNCTION__.$limit;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "select m.name as title, a.preview_text as `label`, a.image_link as image_url, CONCAT('/movies/',m.uri_segment) as read_more_link
				from media_stats ms
				join media m on ms.media_fk = m.media_pk and ms.criticowl_rating >= 0.8 AND m.is_enabled = 1
				join article a on ms.media_fk = a.media_fk and a.is_live = 1 and a.article_type = ?
				order by m.release_date desc
				limit {$limit};";
		$out = $this->db->query($sql,array(POST_REVIEW))->result_array();
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	function get_top_media_for_year($year,$media_type,$limit = 5) {
		$limit = (int)$limit;
		
		$sql = "select m.name as title, a.preview_text as `label`, a.image_link as image_url, CONCAT('/movies/',m.uri_segment) as read_more_link
						from media_stats ms
						join media m on ms.media_fk = m.media_pk and ms.criticowl_rating >= 0.8 and m.type = ? AND m.is_enabled = 1
						join article a on ms.media_fk = a.media_fk and a.is_live = 1 and a.article_type = ?
						where YEAR(release_date) = ?
						order by ms.criticowl_rating asc
						limit {$limit}";
		$out = $this->db->query($sql,array($media_type,POST_REVIEW,$year))->result_array();
		
		return $out;
	}
	
	function get_movies_worth_watching_list($limit = 10) {
		$limit = (int)$limit;
		
		$sql = "select m.name as title, a.preview_text as `label`, a.image_link as image_url, CONCAT('/movies/',m.uri_segment) as read_more_link
						from media_stats ms
						join media m on ms.media_fk = m.media_pk and ms.average_overall_rating >= 0.6 and m.type = ? AND m.is_enabled = 1
						join article a on ms.media_fk = a.media_fk and a.is_live = 1 and a.article_type = ?
						order by m.release_date desc
						limit {$limit}";
		$out = $this->db->query($sql,array(MEDIA_MOVIE,POST_REVIEW))->result_array();
		
		return $out;
	}
		
	function get_if_valid_media($media_type,$hashtag) {
		$sql = "SELECT *
						FROM media
						WHERE type = ?
						AND hashtag = ?";
		
		$media_row = $this->db->query($sql,array($media_type,"#{$hashtag}"))->row_array();
					
		if(!empty($media_row['media_pk'])) {
			return $media_row['media_pk'];
		} else {
			return FALSE;
		}
	}
	
	function get_unspecified_media_fk() {
		return $this->db->query('select media_pk from media where type = ?',array(MEDIA_NONE))->row()->media_pk;
	}
	
	private function _update_uri_segment($media_pk,$media_title,$epoch_release_date) {
		$uri_segment = $this->_generate_media_uri($media_pk,$media_title,$epoch_release_date);

		$this->db->query("UPDATE media SET uri_segment = ? WHERE media_pk = ?",
										 array(
			$uri_segment,
			$media_pk
		));

		$ci = &get_instance();
		$ci->uri_memory_model->insert_into_uri_memory_model($media_pk,"/".get_uri_prefix_by_media_type(MEDIA_MOVIE)."{$uri_segment}");
	}
	
}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
