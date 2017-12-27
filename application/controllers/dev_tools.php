<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dev_tools extends CI_Controller {

	public function __construct() {
			parent::__construct();
			$this->load->model(array('user_model','media_model','article_model'));
			$this->user_model->session_check();
			$this->user_fk = $this->user_model->get_user_pk();
	}

	public function generate_all_images() {
		$image_links = $this->db->query('select image_link from article where media_type = "MOVIE" and is_live = "1" and image_link is not null and image_link <> "" and article_type = "REVIEW"')->result_array();

		foreach ($image_links as $row) {
			echo get_compressed_image_url($row['image_link']);
		}
	}

	public function update_search_tables() {
		echo "STARTING >>> ";

		$ci = &get_instance();
		$ci->load->model('search_model');

		$this->db->trans_start();
		
		$ci->search_model->populate_search_articles();
		$ci->search_model->populate_search_media();
		$ci->search_model->populate_search_tags();
		$ci->search_model->remove_disabled_search_items();
		
		$this->db->trans_complete();
		
		echo "DONE.";
	}

	public function format_all_article_content() {
		$articles = $this->db->query("select * from article")->result_array();

		foreach($articles as $a) {
				$formatted_content = $this->article_model->format_article_content($a['content']);
				$this->db->query("update article set formatted_content = ? where article_pk = ?",array($formatted_content,$a['article_pk']));
				echo "Updated: {$a['title']} <br />";
		}
		
	}

	public function format_content() {
		$content = $this->db->query("select * from article where article_pk = 18;")->row()->content;
		$formatted = $this->article_model->format_article_content($content);

		echo $formatted;
	}

	public function outstanding_dvd_list() {
		$list = $this->media_model->get_missing_dvd_bluray_release_dates();

		$this->load->view('snippets/dvd_bluray_media_checklist_snippet',array('list'=>$list));
	}
	
	public function generate_optimized_image() {
		echo get_compressed_image_url('http://development.criticowl.com/img/criticowl_BG.png');
	}

	public function siwi_lookup() {
		$this->load->model('should_i_watch_it_model');		

		$search_title = "knight";

		var_dump($this->should_i_watch_it_model->search_siwi_lookup($search_title));
	}
	
	public function test_twitter() {
		echo "Tweet Testing <br /><br />";

		$this->load->library('twitter_api');
		$stuff = $this->twitter_api->get_latest_tweet_ids();
		//show the data
 		var_dump($stuff);
	}
	
	public function get_omdbapi_movie() {
		
		$movie_name = "walking dead (3000)";
		
		// Get cURL resource
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
		
		$rating = $percentage = array();
		if(!empty($resp)) {
			$resp = json_decode($resp,TRUE);
			
			if(isset($resp['Error'])) {
				echo 'nothing';
				return;
			}
			
			if(isset($resp['imdbRating'])
			   && isset($resp['Type'])
			   && $resp['Type'] === 'movie'
			   && $resp['imdbRating'] > 0) {
				$rating['imdb'] = $resp['imdbRating'];
				$percentage['imdb'] = (float)$resp['imdbRating'] / 10;
			}
			
			if(isset($resp['tomatoRating'])
			   && isset($resp['Type'])
			   && $resp['Type'] === 'movie'
			   && $resp['tomatoRating'] > 0) {
				$rating['rottentomatoes'] = $resp['tomatoRating'];
				$percentage['rottentomatoes'] = (float)$resp['tomatoRating'] / 10;
			}
			
			//get criticowl rating if exists
			$this->load->model('media_poll_model');
			$criticowl_row = $this->media_poll_model->get_media_stats_by_name($movie_name);
			
			if($criticowl_row && count($criticowl_row) > 0) {
				//if a row returned, get the rating for movie here.
				$rating['criticowl'] = $criticowl_row['criticowl_rating'] * 10;
				$percentage['criticowl'] = $criticowl_row['criticowl_rating'];
			}
			
		}
		
		
		echo "<pre>";
		var_dump($percentage);
		echo "</pre>";
		
	}
	
	
	function clear_cache() {
		echo "Clearing Cache ... ";
		clear_all_cache();
		echo "Done.";
	}
	
	function cast_vote_test() {
		$this->load->model('media_poll_model');
		
		$this->media_poll_model->cast_ballot_watched_it(5);
	}
	
	function modal_test() {
		$this->load->view('snippets/rating_modal_snippet');
	}
	
	//-------Populate media hashtags
	public function update_media_hashtags() {
		$media = $this->db->query("SELECT * FROM media")->result_array();
		
		foreach($media as $m) {
			$this->db->query("UPDATE media SET hashtag = ? WHERE media_pk = ?", array(convert_to_hashtag($m['name']),$m['media_pk']));
		}
	}
	
	
	
	//-------Slideshow upload sheets
	public function upload_slideshow_sheet() {
		echo "<h1>Slideshow sheet uploader</h1>";
		
		$filename = "new_slideshow.csv";
		
		$mapping = array(
			'order' => 0,
			'title' => 1,
			'label' => 2,
			'image_url' => 3,
			'read_more_link' => 4,
		);
		
		//skip the very first row since it will hold all the column names
		$row_num = 1;
		$this->imported_slides = array();
		if(($handle = @fopen("/tmp/slideshow_uploads/".$filename,"r")) !== FALSE) {
			while (($data = fgetcsv($handle,10000,",")) !== FALSE) {
				if($row_num === 1) {
					$row_num++;
					continue;
				}elseif($row_num === 2) {
					$slideshow_title = $data[5];
					$slideshow_unique_identifier = $data[6];
				} else {
					$this->imported_slides[] = $this->_generate_row_data_array($mapping,$data);					
				}
				
				$row_num++;
			}
			fclose($handle);
		} else {
			echo "Nothing to import ..."; return;
		}
		
		$this->load->model('slideshow_model');
		$this->slideshow_model->import_slideshow($slideshow_title,$slideshow_unique_identifier,$this->imported_slides);
		
		@mkdir('/tmp/archive');
		if (copy("/tmp/slideshow_uploads/$filename","/tmp/slideshow_uploads/archive/".$this->config->item('environment')."_".time()."_{$filename}")) {
			echo "moved file to archive! <br />";
			unlink("/tmp/slideshow_uploads/$filename");
		} else {
			echo "unable to move file to archive! <br />";				  
		}
	}
	
	private function _generate_row_data_array($mapping,$data_row) {
		$output_arr = array();
		foreach($mapping as $col_name => $col_number) {
			$output_arr[$col_name] = $data_row[$col_number];
		}
		return $output_arr;
	}
	
	private function _insert_media($release_date,$title,$type) {
		$sql = "INSERT IGNORE INTO media
						(`name`,`release_date`,`type`,`date_created`,`date_modified`)
						VALUES
						(?,?,?,NOW(),NOW())";
		$this->db->query($sql,array($title,$release_date,$type));
		
		echo "Processed $title ... <br/>";
	}

	//-------Media upload sheets
	public function upload_media_sheet() {
		
		$filename = "media_list_update.csv";
		
		$mapping = array(
			'release_date' => 0,
			'title' => 1,
			'type' => 2,
		);
		
		//skip the very first row since it will hold all the column names
		$first_row = true;
		
		if(($handle = fopen("/tmp/media_uploads/".$filename,"r")) !== FALSE) {
			while (($data = fgetcsv($handle,10000,",")) !== FALSE) {
				if($first_row) {
					$first_row = false;
					continue;
				}
				
				$this->_process_media_row($data,$mapping);
				
			}
			fclose($handle);
		}
		@mkdir('/tmp/archive');
		if (copy("/tmp/media_uploads/$filename","/tmp/media_uploads/archive/".$this->config->item('environment')."_".time()."_{$filename}")) {
			echo "moved file to archive! <br />";
			unlink("/tmp/media_uploads/$filename");
		} else {
			echo "unable to move file to archive! <br />";				  
		}
	}
	
	private function _process_media_row($data_row,$mapping) {
		$release = $data_row[$mapping['release_date']];
		$title = $data_row[$mapping['title']];
		$type = strtoupper($data_row[$mapping['type']]);
		
		if(empty($title)) {
			echo "Skipped empty row ... <br />";
			return;
		}
		
		$release_epoch = strtotime($release);
		$release = sql_datetime($release_epoch);
		
		switch($type) {
			case MEDIA_MOVIE:
				$type = MEDIA_MOVIE;
				break;
			default:
				$type = MEDIA_NONE;
				break;
		}
		
		$this->_insert_media($release,$title,$type);
	}
	
	//--------


	function datum_api_test() {
		$ci = &get_instance();
		$ci->load->library('DatumboxAPI');
		$ci->load->model('web_rating_tweet_analysis_model');

		$tweets = $ci->web_rating_tweet_analysis_model->get_tweets_to_analyze(15);
		$tweets_to_delete = array();

		foreach ($tweets as $t) {
			echo $t['tweet'];
			echo " => ";
			$sentiment = $ci->datumboxapi->SentimentAnalysis($t['tweet']);
			echo $sentiment;
			echo " ___ ";
			$classification = $ci->datumboxapi->TopicClassification($t['tweet']);
			echo $classification;

			$subjectivity = $ci->datumboxapi->SubjectivityAnalysis($t['tweet']);
			$readability = $ci->datumboxapi->ReadabilityAssessment($t['tweet']);

			echo " ___ {$readability}";

			if($classification === "Arts" && $subjectivity === "subjective") {
				if($sentiment === "positive") {
					$int_sentiment = 1;
				} elseif($sentiment === "negative") {
					$int_sentiment = -1;
				} elseif($sentiment === "neutral") {
					$int_sentiment = 0;
				} 

				if(in_array($int_sentiment, array(0,-1,1))) {
					$ci->web_rating_tweet_analysis_model->update_tweet_sentiment($t['web_rating_tweet_analysis_pk'],$int_sentiment);
					echo "updated@ {$t['web_rating_tweet_analysis_pk']},{$int_sentiment}";
				}
			} else {
				echo " >>> deletable tweet";
				$tweets_to_delete[] = $t['web_rating_tweet_analysis_pk'];
			}

			echo "<br/>";
		}

		if(!empty($tweets_to_delete)) {
			$ci->web_rating_tweet_analysis_model->delete_tweets($tweets_to_delete);
		}

	}

	function datum_get_tweets() {
		$ci = &get_instance();
		echo "<br/><h1>Tweets</h1>";
		$ci->load->library('twitter_api');
		$ci->load->model('web_rating_tweet_analysis_model');
   		
   		$media_fk = 16;
   		$max_tweet_id = $ci->web_rating_tweet_analysis_model->get_latest_tweet_for_media_fk($media_fk);
   		
   		$tweets_data = $ci->twitter_api->search_tweets("#JackReacher",1000,$max_tweet_id);

   			// echo "<pre>";
   			// print_r($tweets_data); 
   			// echo "</pre>";

   		$tweets_data = reset($tweets_data);

   		echo "new tweets found: ".count($tweets_data)."<br/>";

   		foreach ($tweets_data as $single_tweet) {
   			$id = $single_tweet['id'];
   			$tweet_text = $single_tweet['text'];

   			// echo "INSERTING NEW ROW : ({$media_fk},{$id},{$tweet_text}) </br>";

   			// echo "<pre>";
   			// print_r($single_tweet); 
   			// echo "</pre>";

   			$ci->web_rating_tweet_analysis_model->insert_new_web_rating_tweet($media_fk,$tweet_text,$id);
   		}
	}
}