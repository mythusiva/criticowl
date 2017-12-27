<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscription_model extends CI_Model {
	
	function add_update_media_subscription($media_fk,$email_address) {
		
		$unsubscribe_token = md5($media_fk."::".$email_address);
		
		$sql = "INSERT INTO subscription_media (media_fk,email_address,unsubscribe_token,date_modified)
						VALUES (?,?,?,NOW())
						ON DUPLICATE KEY UPDATE unsubscribe_token = ?, is_active = 1";
						
		$this->db->query($sql,array($media_fk,$email_address,$unsubscribe_token,$unsubscribe_token));
		
	}
	
	function get_media_subscribers($media_fk) {
		$sql = "SELECT *
				FROM subscription_media
				WHERE media_fk = ? and is_active = 1";
				
		return $this->db->query($sql, array($media_fk))->result_array();
	}

	function get_new_articles($no_tweets = FALSE) {
		$sql = "SELECT 	a.article_pk, a.title,a.content,a.date_created as date_posted,a.image_link,a.article_type, a.media_fk,
										m.name as media_title,m.release_date, a.preview_text, a.media_fk, a.is_live, a.uri_segment, a.media_type,
										m.uri_segment as media_uri_segment, m.hashtag
						FROM article a
						JOIN media m ON m.media_pk = a.media_fk
						WHERE a.date_notified IS NULL
						AND a.is_live = 1";
						
		if($no_tweets) {
			$sql .= " AND a.is_tweet = 0";
		}
		
		return $this->db->query($sql)->result_array();
	}
	
	function unsubscribe_media_subscription($token,$hashed_email) {
		$token_matches = $this->db->query("SELECT * FROM `subscription_media` WHERE `unsubscribe_token` = ?",array($token))->result_array();
		
		foreach($token_matches as $match) {
			if($hashed_email === md5($match['email_address'])) {
				$this->db->query("UPDATE `subscription_media` SET `is_active` = 0 WHERE `subscription_media_pk` = ?",array($match['subscription_media_pk']));
			}
		}
		
	}
	
}
