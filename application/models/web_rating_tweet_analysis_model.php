<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Web_rating_tweet_analysis_model extends CI_Model {

	function __contsruct() {
	}
	
	function insert_new_web_rating_tweet($media_fk,$tweet,$tweet_id) {
		$sql = "
			INSERT IGNORE INTO `web_rating_tweet_analysis` (`media_fk`,`tweet`,`tweet_id`,`tweet_hash`) VALUES (?,?,?,?);
		";
		$this->db->query($sql,array($media_fk,$tweet,$tweet_id,md5($tweet)));

		log_message("INFO","Media_fk {$media_fk} Inserting new tweet >>> {$tweet_id}");
	}

	function update_tweet_sentiment($pk,$sentiment) {
		$sql = "
			UPDATE `web_rating_tweet_analysis` SET `sentiment` = ? WHERE `web_rating_tweet_analysis_pk` = ?;
		";
		$this->db->query($sql,array($sentiment,$pk));
		log_message("INFO","Updated sentiment for pk: {$pk}");
	}

	function delete_tweets($pks) {
		$pks_to_in = '"'.implode('","', $pks).'"';

		$this->db->query("DELETE FROM `web_rating_tweet_analysis` WHERE `web_rating_tweet_analysis_pk` IN ({$pks_to_in})");
		log_message("INFO","Deleted tweets from web_rating_tweet_analysis table: {$pks_to_in}");
	}
	

	function get_latest_tweet_for_media_fk($media_fk) {
		$q = $this->db->query("SELECT MAX(tweet_id) AS latest_tweet_id FROM web_rating_tweet_analysis WHERE media_fk = ?;",array($media_fk));

		if($q->num_rows() > 0) { 
			$max_tweet_id = $q->row()->latest_tweet_id;
		} else {
			$max_tweet_id = 0;
		}

		return $max_tweet_id;
	}

	function get_tweets_to_analyze($limit=50) {
		$q = $this->db->query("SELECT * FROM `web_rating_tweet_analysis` WHERE `sentiment` IS NULL LIMIT {$limit};");
		return $q->result_array();
	}


	function classify_and_delete_tweets($rows_to_update = 100) {
		$ci = &get_instance();
		$ci->load->library('DatumboxAPI');

		$tweets = $this->get_tweets_to_analyze($rows_to_update);
		$tweets_to_delete = array();

		$total_tweets = count($tweets);

		foreach ($tweets as $key => $t) {
			$current = $key + 1;
			log_message("INFO","====== {$current} of {$total_tweets} ======");
			log_message("INFO","Current tweet: {$t['tweet']}");
			$classification = $ci->datumboxapi->TopicClassification($t['tweet']);
			// $readability = $ci->datumboxapi->ReadabilityAssessment($t['tweet']);

			if(empty($classification)) { 
				log_message("INFO","!!! Classification is empty, the API might be exhausted for now.");
				continue;
			}

			
			$updated_sentiment = FALSE;
			if($classification === "Arts") {
				$subjectivity = $ci->datumboxapi->SubjectivityAnalysis($t['tweet']);
				if($subjectivity === "subjective") {
					$sentiment = $ci->datumboxapi->TwitterSentimentAnalysis($t['tweet']);
					
					if($sentiment === "positive") {
						$int_sentiment = 1;
					} elseif($sentiment === "negative") {
						$int_sentiment = -1;
					} elseif($sentiment === "neutral") {
						$int_sentiment = 0;
					} 

					if(in_array($int_sentiment, array(0,-1,1))) {
						log_message("INFO","+++ attributes: {$sentiment},{$classification},{$subjectivity}");
						$this->update_tweet_sentiment($t['web_rating_tweet_analysis_pk'],$int_sentiment);
						$updated_sentiment = TRUE;
					} 
				}
			} 

			if(!$updated_sentiment) {
				log_message("INFO","--- irrelavent tweet, deleting.");
				$tweets_to_delete[] = $t['web_rating_tweet_analysis_pk'];
			}
		}

		if(!empty($tweets_to_delete)) {
			$this->delete_tweets($tweets_to_delete);
		}
	}
}

