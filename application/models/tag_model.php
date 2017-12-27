<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_model extends CI_Model {
	
	function get_tag_rows_for_article_fk($article_fk) {
		$sql = "select * 
				from tag_article ta
				join tag t on ta.tag_fk = t.tag_pk
				where ta.article_fk = ?";

		return $this->db->query($sql,array($article_fk))->result_array();
	}

	function get_list_of_tags_for_article($article_fk) {
		$rows = $this->get_tag_rows_for_article_fk($article_fk);
		
		$tags_list = array();
		foreach ($rows as $r) {
			$tags_list[] = $r['name'];
		}

		return $tags_list;
	}

	function save_list_of_tags_for_article($article_fk,$tags_array) {
		$this->_purge_tags_from_article($article_fk);
		foreach ($tags_array as $tag_name) {
			$tag_pk = $this->add_new_tag($tag_name);
			if($tag_pk > 0) {
				$this->associate_tag_to_article($tag_pk,$article_fk);
			}
		}
	}	

	private function _purge_tags_from_article($article_fk) {
		$this->db->query('DELETE FROM tag_article where article_fk = ?',array($article_fk));
	}

	function get_pk_from_tag_name($tag_name) {
		$q = $this->db->query("SELECT tag_pk FROM tag WHERE name = ?",array($tag_name))->row_array();

		return (isset($q['tag_pk'])) ? $q['tag_pk'] : '';
	}

	function add_new_tag($tag_name) {
		$tag_name = trim($tag_name);
		if(empty($tag_name)) {
			return '';
		}

		$this->db->query("INSERT IGNORE INTO tag (name,date_created) VALUES (?,NOW());",array($tag_name));
		$tag_pk = $this->db->insert_id();

		if($tag_pk > 0) {
			return $tag_pk;
		} else {
			return $this->get_pk_from_tag_name($tag_name);
		}
	}

	function associate_tag_to_article($tag_fk,$article_fk) {
		$this->db->query("INSERT IGNORE INTO tag_article (tag_fk,article_fk,date_created) VALUES (?,?,NOW());",array($tag_fk,$article_fk));
	}

}