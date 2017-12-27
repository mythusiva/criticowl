<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends CI_Controller {

	public function index()	{
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');

		$this->load->model(array(
			'media_model',
			'article_model'
		));

		$data['latest_exclusive_articles'] = $this->article_model->get_latest_exclusives();
		$data['articles'] = $this->article_model->get_latest_articles_exclude_active_exclusives(0,10);
		$data['upcoming_media'] = $this->media_model->get_upcoming_releases();

		$this->load->library('twitter_api');
		$data['twitter_ids'] = $this->twitter_api->get_latest_tweet_ids(15);
		
		$this->load->view('home',$data);
	}

	
	public function article_landing_page($uri_segment) {
		
		$this->load->model('article_model');
		
		$item_data = $this->article_model->get_article_by_uri_segment($uri_segment,POST_ARTICLE,TRUE);

		if(empty($item_data)) {
			//check previous versions of URL
			$fk = $this->uri_memory_model->get_fk_from_full_uri("/".get_uri_prefix_by_article_type(POST_ARTICLE).$uri_segment);

			if($fk > 0) {
				$new_uri = $this->uri_memory_model->get_correct_article_uri($fk,POST_ARTICLE);
				if($new_uri) {
					redirect($new_uri);
				}
			} 

			$this->_fallback_to_home();
		}
		
		
		$total_articles = 5;
		$articles_with_similar_tags = $this->article_model->get_articles_with_tags($item_data['article_pk'],(array)$item_data['article_pk'],3);
		$articles_trailer_talks = $this->article_model->get_latest_trailer_talks((array)$item_data['article_pk'],$total_articles-count($articles_with_similar_tags));

		$data['data'] = $item_data;
		$data['related_pages'] = array_merge($articles_with_similar_tags,$articles_trailer_talks);
		$this->load->view('landing_page',$data);
		
	}
	
	public function review_landing_page($uri_segment) {
		
		$this->load->model('article_model');
		
		$item_data = $this->article_model->get_article_by_uri_segment($uri_segment,POST_REVIEW,TRUE);
		
		if(empty($item_data)) {
			//check previous versions of URL
			$fk = $this->uri_memory_model->get_fk_from_full_uri("/".get_uri_prefix_by_article_type(POST_REVIEW).$uri_segment);

			if($fk > 0) {
				$new_uri = $this->uri_memory_model->get_correct_article_uri($fk,POST_REVIEW);
				if($new_uri) {
					redirect($new_uri);
				}
			} 

			$this->_fallback_to_home();
		}

		$total_articles = 5;
		$articles_with_similar_tags = $this->article_model->get_articles_with_tags($item_data['article_pk'],(array)$item_data['article_pk'],3);
		$articles_reviews = $this->article_model->get_latest_reviews((array)$item_data['article_pk'],$total_articles-count($articles_with_similar_tags));

		
		$data['data'] = $item_data;
		$data['related_pages'] = array_merge($articles_with_similar_tags,$articles_reviews);
		$this->load->view('landing_page',$data);
		
	}
	
	public function reviews() {
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');
		
		$this->load->model(array('article_model','media_model'));

		$data['media_list'] = $this->media_model->get_movie_list();
		$data['articles'] = $this->article_model->get_articles_by_type(MEDIA_MOVIE,0,10,array(POST_REVIEW));
		
		$this->load->view('reviews_page',$data);
	}

	public function articles() {
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');
		
		$this->load->model(array('article_model','media_model'));

		$data['media_list'] = $this->media_model->get_movie_list();
		$data['articles'] = $this->article_model->get_articles_by_type(MEDIA_MOVIE,0,10,array(POST_ARTICLE));
		
		$this->load->view('articles_page',$data);
	}

	public function exclusive_articles() {
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');
		
		$this->load->model(array('article_model','media_model'));

		$data['media_list'] = $this->media_model->get_movie_list();
		$data['articles'] = $this->article_model->get_articles_by_type(MEDIA_NONE,0,10,array(POST_ARTICLE));
		
		$this->load->view('exclusive_articles_page',$data);
	}

	public function movies() {
		redirect('/reviews');
	}
	
	public function movie_related_posts_by_uri($release_year,$uri_segment) {
		$this->load->model('media_model');
		$media_fk = $this->media_model->get_pk_by_uri_segment("{$release_year}/{$uri_segment}");

		if($media_fk > 0) {

			$this->movie_related_posts($media_fk);		

		} else {

			//check previous versions of URL
			$fk = $this->uri_memory_model->get_fk_from_full_uri("/".get_uri_prefix_by_media_type(MEDIA_MOVIE)."{$release_year}/{$uri_segment}");
			if($fk > 0) {
				$new_uri = $this->uri_memory_model->get_correct_media_uri($fk);
				if($new_uri) {
					redirect($new_uri);
				}
			} 
			$this->_fallback_to_home();

		}
		
	}
	
	public function movie_related_posts($media_fk) {
		
		$movie_posts = $this->_get_media_related_posts($media_fk,MEDIA_MOVIE);
		
		if($movie_posts === FALSE) {
			$this->session->set_flashdata('notification','Sorry, that link does not exist!');
			redirect('/reviews');
		}
		
		$this->load->model(array('media_model'));
		$data['media_list'] = $this->media_model->get_movie_list();
		$data['media_info_box'] = $this->media_model->get_media_info_box_data($media_fk,MEDIA_MOVIE);
		
		$data = array_merge((array)$movie_posts,$data);
		
		$this->load->view('movie_posts_page',$data);
	}
	
	private function _get_media_related_posts($media_fk,$type) {
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');
		
		$this->load->model(array('article_model','media_model'));
	
		//check if $media_fk is a valid one!
		$media_row = $this->media_model->get_media_by_pk($media_fk);
		if(!isset($media_row['type']) || $media_row['type'] !== $type) {
			return FALSE;
		}
		
		$data['articles'] = $this->article_model->get_articles_by_media_fk($media_fk,0,25);
		$data['media_fk'] = $media_fk;
		$data['media'] = $media_row;
		
		return $data;
	}
	
	public function page_not_found() {
		redirect('/');
	}
	
	public function contact_us() {
		$data = array();
		$data['validation'] = $this->session->flashdata('validation');
		$data['notification'] = $this->session->flashdata('notification');
		$data['user_data'] = $this->session->flashdata('post_data');
		
		$this->load->view('contact_us_page',$data);
	}
	
	public function submit_contact_us() {
		//post data inbound
			
		$post_data['from_field'] = $from_field = $this->input->post('cu_from_address_field',TRUE);
		$post_data['subject_field'] = $subject_field = $this->input->post('cu_subject_line_field',TRUE);
		$post_data['message_field'] = $message_field = $this->input->post('cu_message_field',TRUE);
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('cu_from_address_field', '`From`', 'required|valid_email');
		$this->form_validation->set_rules('cu_subject_line_field', '`Subject`', 'required|max_length[140]');
		$this->form_validation->set_rules('cu_message_field', '`Message`', 'required|max_length[4096]');
	
		if ($this->form_validation->run() === FALSE) {
			$form_error_areas = array();
			if(form_error('cu_from_address_field')) {
				$form_error_areas['cu_from_address_field'] = 1;
			}
			if(form_error('cu_subject_line_field')) {
				$form_error_areas['cu_subject_line_field'] = 1;
			}
			if(form_error('cu_message_field')) {
				$form_error_areas['cu_message_field'] = 1;
			}
			
			$this->session->set_flashdata('validation',$form_error_areas);
			$this->session->set_flashdata('notification',validation_errors('<div class="error">', '</div>'));
			$this->session->set_flashdata('post_data',$post_data);
			
			redirect('/contact_us');
		} else {

			$content = "Reply-to: {$from_field} \n\n{$message_field}";
		
			$this->emailer_model->add_to_queue('team@criticowl.com',$subject_field,$content);
			
			$this->session->set_flashdata('notification',"Successfully submitted your message!");
			redirect('/');
		}
	}
		
	public function privacy_policy() {
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');

		$this->load->view('legal_privacy_policy_page',$data);
	}
	
	public function terms_and_conditions() {
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');

		$this->load->view('legal_terms_and_conditions_page',$data);
	}
	
	public function about_us() {
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');

		$this->load->view('about_us_page',$data);
	}
	
	public function browser_fail() {
		$this->load->view('browser_fail_page');
	}

	public function rss_feed() {
		$this->load->model('article_model');
		$data['feed_results'] = $this->article_model->get_rss_feed();
		$this->load->view('rss_feed_page',$data);
	}


	private function _fallback_to_home() {
		$this->session->set_flashdata('notification','We\'re sorry we were unable to find that page ...');
		redirect('/');
	}
}