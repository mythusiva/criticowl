<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
	
	function get_edit_list($article_type) {
				$sql = "select
												a.article_pk as pk,
												a.date_created as date_posted,
												a.date_modified as last_modified,
												a.title as label,
												a.media_type,
												m.name as media_name,
												IF(a.article_type = ?,'/admin/edit_news/',
																IF(a.article_type = ?,'/admin/edit_article/',
																				IF(a.article_type = ?,'/admin/edit_review/','undefined')
																)
												) as edit_link_prefix,
												a.is_live,
												a.is_approved,
												u.*
												from article a
												join media m on a.media_fk = m.media_pk
												left join user u on a.last_edited_user_fk = u.user_pk
								where a.article_type = ?
								order by date_posted desc";
		
		return $this->db->query($sql,array(POST_NEWS,POST_ARTICLE,POST_REVIEW,$article_type))->result_array();
	}
	
	function get_latest_edited_list() {
				$sql = "select
												a.article_pk as pk,
												a.date_created as date_posted,
												a.date_modified as last_modified,
												a.title as label,
												a.media_type,
												m.name as media_name,
												IF(a.article_type = ?,'/admin/edit_news/',
																IF(a.article_type = ?,'/admin/edit_article/',
																				IF(a.article_type = ?,'/admin/edit_review/','undefined')
																)
												) as edit_link_prefix,
												a.is_live,
												a.is_approved,
												u.*
												from article a
												join media m on a.media_fk = m.media_pk
												left join user u on a.last_edited_user_fk = u.user_pk
								order by a.date_modified desc
								limit 25";
		
		return $this->db->query($sql,array(POST_NEWS,POST_ARTICLE,POST_REVIEW))->result_array();
	}

	function get_missing_amazon_links() {
		$sql = "SELECT m.*,am.* 
				FROM media m
				JOIN media_stats ms
				ON m.media_pk = ms.media_fk
				LEFT JOIN affiliate_amazon am
				ON m.media_pk = am.media_fk
				WHERE 
					(
						am.amazon_dvd_link IS NULL OR
						am.amazon_bluray_link IS NULL
					) AND
					ms.criticowl_rating IS NOT NULL AND
					(
						m.dvd_release_date IS NOT NULL OR
						m.bluray_release_date IS NOT NULL
					)
				ORDER BY m.release_date DESC;";
		return $this->db->query($sql)->result_array();
	}

	function update_amazon_links_dvd($media_fk,$amazon_dvd_link) {
		if(empty($amazon_dvd_link)) {
			$amazon_dvd_link = NULL;
		}
		$sql = "INSERT INTO affiliate_amazon 
					(media_fk,amazon_dvd_link,date_created,date_modified)
				VALUES 
					(?,?,NOW(),NOW())
				ON DUPLICATE KEY UPDATE 
					amazon_dvd_link = ?, date_modified = NOW();";
		$this->db->query($sql,array($media_fk,$amazon_dvd_link,$amazon_dvd_link));
	}

	function update_amazon_links_bluray($media_fk,$amazon_bluray_link) {
		if(empty($amazon_bluray_link)) {
			$amazon_bluray_link = NULL;
		}
		$sql = "INSERT INTO affiliate_amazon 
					(media_fk,amazon_bluray_link,date_created,date_modified)
				VALUES 
					(?,?,NOW(),NOW())
				ON DUPLICATE KEY UPDATE 
					amazon_bluray_link = ?, date_modified = NOW();";
		$this->db->query($sql,array($media_fk,$amazon_bluray_link,$amazon_bluray_link));
	}

}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
