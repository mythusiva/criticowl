<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_model extends CI_Model {
	
	function get_vague_article_search($search_terms,$ignore_media_fk = NULL) {
		$cache_id = __FUNCTION__.md5("{$search_terms}").$ignore_media_fk;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "SELECT *
				FROM search_article sa
				JOIN article a ON sa.article_fk = a.article_pk AND a.is_live = 1
				WHERE 
				(sa.title like ('%{$search_terms}%') 
						OR sa.content like ('%{$search_terms}%'))
				";

		if(!empty($ignore_media_fk)) {
			$ignore_media_fk = (int)$ignore_media_fk;
			$sql .= " AND a.media_fk <> {$ignore_media_fk} ";
		}

		$sql .= " ORDER BY a.date_created DESC";

		$out = $this->db->query($sql)->result_array();

		set_cached_item($cache_id,$out);
		
		return $out;
	}

	function search_tags_get_list($search_terms) {
		$search_terms = $this->db->escape_like_str($search_terms);

		$cache_id = __FUNCTION__.md5("{$search_terms}");
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "SELECT group_concat(tag_fk) as tag_fks
				FROM search_tag
				WHERE name like '%{$search_terms}%'";

		$out = $this->db->query($sql,[])->row()->tag_fks;
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}

	function get_articles_by_tag_fks($in_tag_fks) {
		$cache_id = __FUNCTION__.md5("{$in_tag_fks}");
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		$sql = "SELECT * 
				FROM tag_article ta
				JOIN article a on ta.article_fk = a.article_pk
				WHERE ta.tag_fk IN ({$in_tag_fks})
				LIMIT 25";

		$out = $this->db->query($sql)->result_array();

		set_cached_item($cache_id,$out);
		
		return $out;
	}
	
	//populate from articles
	function populate_search_articles() {
		$this->db->query("	INSERT INTO search_article
							SELECT article_pk as article_fk,title,content,article_type,media_type 
							FROM article a
							WHERE a.article_type <> 'NEWS'
							AND a.is_live = 1
							ON DUPLICATE KEY UPDATE title = a.title, content = a.content");
	}

	function remove_disabled_search_items() {
		$deleted_items_sql = "SELECT a.*,m.*
							 FROM article a 
							 JOIN media m 
							 ON a.media_fk = m.media_pk
							 WHERE m.is_enabled = 0 OR a.is_live = 0;";
		$deleted_items = $this->db->query($deleted_items_sql)->result_array();

		foreach ($deleted_items as $row) {
			$this->db->query("DELETE FROM search_article WHERE article_fk = ?",array($row['article_pk']));
			$this->db->query("DELETE FROM search_media WHERE media_fk = ?",array($row['media_pk']));
		}
	}
	
	function populate_search_media() {
		$this->db->query("	INSERT INTO search_media
							SELECT media_pk as media_fk,name,type as media_type
							FROM media m
							WHERE m.is_enabled = 1
							ON DUPLICATE KEY UPDATE name = m.name");
	}

	function populate_search_tags() {
		$this->db->query("	INSERT INTO search_tag
							SELECT t.tag_pk as tag_fk,t.name
							FROM tag t
							ON DUPLICATE KEY UPDATE name = t.name");
	}

	function get_movie_fk_if_exists($search_terms) {
		$cache_id = __FUNCTION__.md5("{$search_terms}");
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}

		$search_terms = $this->db->escape_str($search_terms); 
		$boolean_search_terms = $this->_get_boolean_terms($search_terms);

		$sql = "SELECT * 
				FROM search_media
				WHERE name LIKE '%{$search_terms}%'";
		$q = $this->db->query($sql, []);

		if($q->num_rows() > 0) {
			$out = $q->row()->media_fk;

			$out = ($this->is_media_enabled($out)) ? $out : NULL;
		} else {
			$out = NULL;
		}

		set_cached_item($cache_id,$out);
		
		return $out;
	}

	private function _get_boolean_terms($search_terms) {
		//replace whitespace with ' +'

		$patterns[] = "/\s/";

		$replacements[] = " +";


		return preg_replace($patterns,$replacements,$search_terms);
	}

	function is_media_enabled($media_fk) {
		$cache_id = __FUNCTION__.md5("{$media_fk}");
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}

		$sql = "SELECT 1 FROM media WHERE media_pk = ? AND is_enabled = 1;";

		$q = $this->db->query($sql,array($media_fk));

		$out = $q->num_rows() > 0; 

		set_cached_item($cache_id,$out);
		
		return $out;
	}

	function get_top_searched() {
		$cache_id = __CLASS__.__FUNCTION__;

		return [];

		if($out = get_cached_item($cache_id)) {
			return $out;
		}

		$sql = "SELECT search_text
				FROM search_log
				GROUP BY text_hash
				ORDER BY 1 DESC
				LIMIT 25;";

		$out = $this->db->query($sql)->result_array();

		set_cached_item($cache_id,$out,CACHE_TTL_SHORT);
		
		return $out;
	}

	function insert_into_search_log($search_term) {
		$this->db->query("INSERT INTO search_log (search_text,text_hash,date_created) VALUES (?,MD5(?),NOW());",array($search_term,$search_term));
	}
	
}

