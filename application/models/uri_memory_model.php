<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uri_memory_model extends CI_Model {
	
	function insert_into_uri_memory_model($fk,$complete_uri) {
		$sql = "
			INSERT INTO `uri_memory_bank` (`fk`,`uri`) VALUES (?,?)
			ON DUPLICATE KEY UPDATE `fk` = ?;
		";
		$this->db->query($sql,array($fk,$complete_uri,$fk));
	}

	function get_fk_from_full_uri($complete_uri) {
		$sql = "SELECT * FROM `uri_memory_bank` WHERE `uri` = ?";

		$q = $this->db->query($sql,array($complete_uri));

		if($q->num_rows() > 0) {
			$out = $q->row()->fk;
		} else {
			$out = FALSE;
		}

		return $out;
	}

	function get_correct_article_uri($fk,$article_type) {
		//find article
		$sql = "SELECT * FROM article WHERE article_pk = ?";

		$q = $this->db->query($sql,array($fk));

		if($q->num_rows() > 0) {

			$out = "/".get_uri_prefix_by_article_type($article_type).$q->row()->uri_segment;

		} else {
			$out = FALSE;
		}

		return $out;
	}

	function get_correct_media_uri($fk,$media_type = MEDIA_MOVIE) {
		//find article
		$sql = "SELECT * FROM media WHERE media_pk = ?";

		$q = $this->db->query($sql,array($fk));

		if($q->num_rows() > 0) {

			$out = "/".get_uri_prefix_by_media_type($media_type).$q->row()->uri_segment;

		} else {
			$out = FALSE;
		}

		return $out;
	}
	
}

