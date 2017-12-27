<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagination_model extends CI_Model {
	
	function count_total_articles() {
		$sql = "select count(*) as count
						from article a
						join media m
						on a.media_fk = m.media_pk
						where a.is_live = 1";
		return $this->db->query($sql)->row()->count;
	}
	
	function count_articles_by_type($type,$filters = array()) {
		$sql = "select count(*) as count
						from article a
						join media m
						on a.media_fk = m.media_pk
						where a.media_type = ? and m.type = a.media_type and a.is_live = 1";
						
		if(count($filters) > 0) {
			$filter_list = "'".implode("','",$filters)."'";
			$sql = $sql." AND a.article_type IN ($filter_list)";
		}
						
		return $this->db->query($sql,array($type))->row()->count;
	}
	
	function count_articles_by_media_fk($media_fk) {
		$sql = "select count(*) as count
						from article a
						join media m
						on a.media_fk = m.media_pk and m.media_pk = ? and a.is_live = 1";
						
		return $this->db->query($sql,array($media_fk))->row()->count;
	}

}

