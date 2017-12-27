<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(array('search_model','media_model'));
	}
	
	public function global_search() {
		$search_terms = $this->input->get('search_terms',TRUE);

		if(empty($search_terms)) {
			return;
		}

		$media_fk = $this->search_model->get_movie_fk_if_exists($search_terms);
		$is_movie = (empty($media_fk)) ? FALSE : TRUE;

		$is_reviewed = $this->media_model->is_movie_rated($media_fk);

		$output['siwi'] = array();
		$output['movie_review'] = array();
		$output['movie_articles'] = array();

		if($is_movie) {
			if($is_reviewed) {
				$output['siwi'] = $this->_get_siwi_result($media_fk);
				$output['movie_review'] = $this->_get_movie_review($media_fk);
			}
			$output['movie_articles'] = $this->_get_movie_articles($media_fk);
			$output['other'] = $this->_get_vague_matches($search_terms,$ignore_movie = $media_fk);
		} else {
			$output['other'] = $this->_get_vague_matches($search_terms);
		}

		$output['tags'] = $this->_get_tag_results($search_terms);

		if(
			empty($output["siwi"]) &&
			empty($output["movie_review"]) &&
			empty($output["movie_articles"]) &&
			empty($output["tags"]) &&
			empty($output["other"])
		) {
			$output = array();
		}

		if(!empty($output)) {
			$this->search_model->insert_into_search_log($search_terms);
		}

		echo $this->load->view('snippets/global_search_bar_results_snippet',array('results' => $output),TRUE);
	}

	private function _is_movie_title($search_terms) {
		$media_fk = $this->search_model->get_movie_fk_if_exists($search_terms);

		if(empty($media_fk)) {
			$out = FALSE;
		} else {
			$out = TRUE;
		}
	}

	private function _get_siwi_result($media_fk) {
		//get movie siwi card if possible

		$this->load->model('should_i_watch_it_model');
		$media_row = $this->should_i_watch_it_model->get_siwi_card_data($media_fk);

		$out['title'] = $media_row['name']." (".DATE("Y",strtotime($media_row['release_date'])).")";
		$out['rating_decimal'] = $media_row['rating'];
		$out['movie_landing_page_url'] = "/".get_uri_prefix_by_media_type(MEDIA_MOVIE).$media_row['media_uri'];
		$out['review_url'] = "/".get_uri_prefix_by_article_type(POST_REVIEW).$media_row['article_uri'];

		return $out;
	}

	private function _get_movie_review($media_fk) {
		//get list of reviews for this movie

		$this->load->model('article_model');
		$review_article = $this->article_model->get_articles_by_type_and_media_fk($media_fk,POST_REVIEW);
		$review_article = reset($review_article);

		$out['title'] = $review_article['title'];
		$out['url'] = "/".get_uri_prefix_by_article_type(POST_REVIEW).$review_article['uri_segment'];
		$out['description'] = $review_article['preview_text'];
	
		return $out;
	}

	private function _get_movie_articles($media_fk) {
		//get list of articles for this movie

		$this->load->model('article_model');
		$articles = $this->article_model->get_articles_by_type_and_media_fk($media_fk,POST_ARTICLE);

		$out = array();
		foreach ($articles as $a) {
			$out[] = array(
				'title' => $a['title'],
				'url' => "/".get_uri_prefix_by_article_type(POST_ARTICLE).$a['uri_segment'],
				'description' => $a['preview_text'],
			);
		}
	
		return $out;
	}

	private function _get_tag_results($search_terms) {
		//get list of articles/reviews with tag

		$tag_fks = $this->search_model->search_tags_get_list($search_terms);
		$articles = array();	
		if(!empty($tag_fks)) {
			$articles = $this->search_model->get_articles_by_tag_fks($tag_fks);
		}

		$out = array();
		foreach ($articles as $a) {
			$out[] = array(
				'title' => $a['title'],
				'url' => "/".get_uri_prefix_by_article_type($a['article_type']).$a['uri_segment'],
				'description' => $a['preview_text'],
			);
		}
	
		return $out;
	}

	private function _get_vague_matches($search_terms,$ignore_movie = NULL) {
		//search content, preview text
		
		$articles = $this->search_model->get_vague_article_search($search_terms,$ignore_movie);

		$out = array();
		foreach ($articles as $a) {
			$out[] = array(
				'title' => $a['title'],
				'url' => "/".get_uri_prefix_by_article_type($a['article_type']).$a['uri_segment'],
				'description' => $a['preview_text'],
			);
		}
	
		return $out;
	}
	
	
	
}