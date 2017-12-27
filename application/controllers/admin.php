<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->is_primary_server();
		$this->load->model(array('user_model'));
		$CI = get_instance();
		$this->user_model->session_check();
		$this->user_fk = $this->user_model->get_user_pk();
		$this->config->set_item('is_admin',TRUE);
	}

	private function is_primary_server() {
            $system_name = gethostname();
            if($system_name !== 'ms-prime') {
                    $this->session->set_flashdata('notification',

             'Sorry, primary server is currently offline! Please try again later ...');
                    redirect('/');

            }
    }


	public function login() {
		if($this->user_model->is_blocked_user()) {
			$this->session->set_flashdata('notification',
																				'Sorry, we are unable to find that link!');
			redirect('/');
		}
		
		$data = array();
		$data['notification'] = $this->session->flashdata('notification');
		$this->session->sess_destroy();
		$this->load->view('login_page',$data);
	}
	
	public function logout() {
		$this->session->set_flashdata('notification','Successfully logged out.');
		redirect('/admin/login');
	}
	
	public function index()	{
		$this->load->model('admin_model');
		
		$data = array();		
		$data['notification'] = $this->session->flashdata('notification');
		$data['list'] = $this->admin_model->get_latest_edited_list();
		
		$this->load->view('admin_home',$data);
	}
	
	
	//=========================================

	// public function affiliates_amazon() {
	// 	$this->load->model('admin_model');
		
	// 	$data = array();		
	// 	$data['notification'] = $this->session->flashdata('notification');
	// 	$data['list'] = $this->admin_model->get_missing_amazon_links();
		
	// 	$this->load->view('admin_affiliates_amazon_links',$data);
	// }

	public function update_amazon_affiliates_links() {
		$media_fk = (int)$this->input->post('media_fk');
		$dvd_link = $this->input->post('amazon_dvd_link',TRUE);
		$bluray_link = $this->input->post('amazon_bluray_link',TRUE);
		
		$this->load->model('admin_model');
		
		$this->admin_model->update_amazon_links_dvd($media_fk,$dvd_link);
		$this->admin_model->update_amazon_links_bluray($media_fk,$bluray_link);
	}

	//=========================================
	
	
	public function edit_media() {
		$data = array();
		$data['validation'] = $this->session->flashdata('validation');
		$data['notification'] = $this->session->flashdata('notification');
		$data['user_data'] = $this->session->flashdata('post_data');
		
		$this->load->model('media_model');
		
		$dd_lookup_pair = $this->media_model->get_dropdown_lookup_pair();
		$data['autocomplete_list'] = json_encode($dd_lookup_pair['list']);
		$data['autocomplete_lookup'] = json_encode($dd_lookup_pair['lookup']);
		
		$this->load->view('admin_edit_media_page',$data);
	}
	
	public function save_edited_media() {
		$media_fk = (int)$this->input->post('media_item_field');
		$media_title = $this->input->post('media_title_field',true);
		$media_release_date = $this->input->post('media_release_date_field',true);
		$dvd_release_date = $this->input->post('dvd_release_date_field',true);
		$bluray_release_date = $this->input->post('bluray_release_date_field',true);
		$epoch_dvd_release_date = strtotime($dvd_release_date);
		$is_enabled = ($this->input->post('is_enabled',true) === "1") ? "1" : "0";

		if(empty($dvd_release_date)) {
			$epoch_dvd_release_date = NULL;
		}
		$epoch_bluray_release_date = strtotime($bluray_release_date);
		if(empty($bluray_release_date)) {
			$epoch_bluray_release_date = NULL;
		}
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('media_title_field', '`Offical Title`', 'required|max_length[128]');
		$this->form_validation->set_rules('media_release_date_field', '`Offical Release Date`', 'required');
		
		if ($this->form_validation->run() === FALSE) {
			$form_error_areas = array();
			if(form_error('media_title_field')) {
							$form_error_areas['media_title_field'] = 1;
			}
			if(form_error('media_release_date_field')) {
							$form_error_areas['media_release_date_field'] = 1;
			}
			
			$post_data['media_title'] = $media_title;
			$post_data['media_release_date'] = $media_release_date;

			$this->session->set_flashdata('validation',$form_error_areas);
			$this->session->set_flashdata('notification',validation_errors('<div class="error">', '</div>'));
			$this->session->set_flashdata('post_data',$post_data);
			
		} else {
			$this->load->model('media_model');

			if($this->media_model->save_edited_media($media_fk, $media_title,strtotime($media_release_date),$epoch_dvd_release_date,$epoch_bluray_release_date,$is_enabled)) {
				$this->session->set_flashdata('notification','Successfully completed!');	
			} else {
				$this->session->set_flashdata('notification','Unable to save edited media!');
			}
			
			$this->load->model('system_job_model');
			$this->system_job_model->update_media_uris();
			$this->system_job_model->update_search_tables();
			$this->system_job_model->sanity_check_uris();

		}

		redirect('/admin/edit_media');

	}
	
	//=========================================
	
	
	public function add_movie() {
		$data = array();
		$data['validation'] = $this->session->flashdata('validation');
		$data['notification'] = $this->session->flashdata('notification');
		$data['user_data'] = $this->session->flashdata('post_data');
		
		$this->load->view('admin_add_movie_page',$data);
	}
	
	public function save_movie() {
		$media_title = $this->input->post('media_title_field',true);
		$media_release_date = $this->input->post('media_release_date_field',true);
		$dvd_release_date = $this->input->post('dvd_release_date_field',true);
		$bluray_release_date = $this->input->post('bluray_release_date_field',true);
		$epoch_dvd_release_date = strtotime($dvd_release_date);
		$is_enabled = ($this->input->post('is_enabled',true) === "1") ? "1" : "0";


		if(empty($dvd_release_date)) {
			$epoch_dvd_release_date = NULL;
		}
		$epoch_bluray_release_date = strtotime($bluray_release_date);
		if(empty($bluray_release_date)) {
			$epoch_bluray_release_date = NULL;
		}
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('media_title_field', '`Offical Title`', 'required|max_length[128]');
		$this->form_validation->set_rules('media_release_date_field', '`Offical Release Date`', 'required');
		
		if ($this->form_validation->run() === FALSE) {
			$form_error_areas = array();
			if(form_error('media_title_field')) {
							$form_error_areas['media_title_field'] = 1;
			}
			if(form_error('media_release_date_field')) {
							$form_error_areas['media_release_date_field'] = 1;
			}
			
			$post_data['media_title'] = $media_title;
			$post_data['media_release_date'] = $media_release_date;
			$post_data['dvd_release_date'] = $dvd_release_date;
			$post_data['bluray_release_date'] = $bluray_release_date;
			$post_data['is_enabled'] = $is_enabled;

			$this->session->set_flashdata('validation',$form_error_areas);
			$this->session->set_flashdata('notification',validation_errors('<div class="error">', '</div>'));
			$this->session->set_flashdata('post_data',$post_data);
			
			redirect('/admin/add_movie');
		} else {
			$this->load->model('media_model');

			if($this->media_model->add_movie($media_title,strtotime($media_release_date),$epoch_dvd_release_date,$epoch_bluray_release_date,$is_enabled)) {
				$this->session->set_flashdata('notification','Successfully completed!');	
			} else {
				$this->session->set_flashdata('notification','Movie already exists!');
			}
			
			$this->load->model('system_job_model');
			$this->system_job_model->update_media_uris();
			$this->system_job_model->update_search_tables();
			$this->system_job_model->sanity_check_uris();

			redirect('/admin/add_movie');
		}
	}
	
	
	//=========================================
	
	public function add_quote() {
		$data = array();
		$data['validation'] = $this->session->flashdata('validation');
		$data['notification'] = $this->session->flashdata('notification');
		$data['user_data'] = $this->session->flashdata('post_data');
		
		$this->load->model('media_model');
		$dd_lookup_pair = $this->media_model->get_dropdown_lookup_pair();
		$data['autocomplete_list'] = json_encode($dd_lookup_pair['list']);
		$data['autocomplete_lookup'] = json_encode($dd_lookup_pair['lookup']);
		
		$this->load->view('admin_add_quote_page',$data);
	}
	
	public function save_quote() {
		//if below is > 0  this is from an edit page!
		$quotation_fk = $this->input->post('quotation_fk',true);
		if((int)$quotation_fk > 0) {
			$type = 'edit';
			$redirect = '/admin/edit_quote/'.$quotation_fk;
		} else {
			$quotation_fk = null; //null it out
			$type = 'add';
			$redirect = '/admin/add_quote';
		}
		
		
		$quotation_text = $this->input->post('quotation_text_field',true);
		$author = $this->input->post('author_field',true);
		$media_item = $this->input->post('media_item_field',true);
		$is_deleted = $this->input->post('is_deleted',true);

		$this->load->library('form_validation');

		$this->form_validation->set_rules('quotation_text_field', '`Quotation`', 'required');
		$this->form_validation->set_rules('author_field', '`Author`', 'required');
		$this->form_validation->set_rules('media_item_field', '`Media Item`', 'required');
		
		$post_data['author'] = $author;
		$post_data['media_item'] = $media_item;
		$post_data['quotation_text'] = $quotation_text;
		$post_data['is_deleted'] = (in_array($is_deleted,array('0','1'))) ? $is_deleted : '0';
				
		if ($this->form_validation->run() === FALSE) {
			$form_error_areas = array();
			if(form_error('quotation_text_field')) {
							$form_error_areas['quotation_text_field'] = 1;
			}
			if(form_error('author_field')) {
							$form_error_areas['author_field'] = 1;
			}
			if(form_error('media_item_field')) {
							$form_error_areas['media_item_field'] = 1;
			}

			$this->session->set_flashdata('validation',$form_error_areas);
			$this->session->set_flashdata('notification',validation_errors('<div class="error">', '</div>'));
			$this->session->set_flashdata('post_data',$post_data);
			
			redirect($redirect);
		} else {
			$this->load->model('quotation_model');

			if($quotation_fk > 0) {
				$this->quotation_model->save_quote($quotation_fk,$quotation_text,$author,$media_item,$is_deleted);
			} else {
				$this->quotation_model->add_quote($quotation_text,$author,$media_item);				
			}

			$this->session->set_flashdata('notification','Successfully saved!');
			
			redirect('/admin');
		}
	}
	
	function edit_quote($quotation_fk = null) {
		if($quotation_fk === null) {
			$this->edit_quotes_list();
			return;
		}
		
		
		$this->load->model('quotation_model');
		
		$quotation_row = $this->quotation_model->get_quotation_by_pk($quotation_fk);
		
		if(empty($quotation_row)) {
			$this->session->set_flashdata('notification','We\'re sorry, that page could not be found ...');
			redirect('/admin');
		}
		
		$post_data['author'] = $quotation_row['author'];
		$post_data['media_item'] = $quotation_row['media_fk'];
		$post_data['quotation_text'] = $quotation_row['quote'];
		$post_data['is_deleted'] = $quotation_row['is_deleted'];

		$data['user_data'] = $post_data;
		$data['quotation_fk'] = $quotation_fk;
		
		$this->load->model('media_model');
		
		$dd_lookup_pair = $this->media_model->get_dropdown_lookup_pair();
		$data['autocomplete_list'] = json_encode($dd_lookup_pair['list']);
		$data['autocomplete_lookup'] = json_encode($dd_lookup_pair['lookup']);
		
		$this->load->view('admin_edit_quote_page',$data);
	}
	
	//=========================================
	
	
	public function add_article() {
		$data = array();
		$data['validation'] = $this->session->flashdata('validation');
		$data['notification'] = $this->session->flashdata('notification');
		$data['user_data'] = $this->session->flashdata('post_data');
		
		$this->load->model('media_model');
		
		$dd_lookup_pair = $this->media_model->get_dropdown_lookup_pair();
		$data['autocomplete_list'] = json_encode($dd_lookup_pair['list']);
		$data['autocomplete_lookup'] = json_encode($dd_lookup_pair['lookup']);
		
		$this->load->view('admin_add_article_page',$data);
	}
	
	function edit_article($article_fk = null) {
		if($article_fk === null) {
			$this->edit_list(POST_ARTICLE);
			return;
		}
		
		$this->load->model(array('article_model','tag_model'));
		
		$article_row = $this->article_model->get_article_by_pk($article_fk);
		
		if(empty($article_row)) {
			$this->session->set_flashdata('notification','We\'re sorry, that page could not be found ...');
			redirect('/admin');
		}
		
		$tags = $this->tag_model->get_list_of_tags_for_article($article_row['article_pk']);
		$tags = implode(',', $tags);
		
		$post_data['media_item'] = $article_row['media_fk'];
		$post_data['article_title'] = $article_row['title'];
		$post_data['article_preview_text'] = $article_row['preview_text'];
		$post_data['article_img_link'] = $article_row['image_link'];
		$post_data['article_content'] = $article_row['content'];
		$post_data['is_live'] = $article_row['is_live'];
		$post_data['is_approved'] = $article_row['is_approved'];
		$post_data['sources'] = $article_row['sources'];
		$post_data['article_expiry_date'] = $article_row['expiry_date'];
		$post_data['tags'] = $tags;
	
		$data['user_data'] = $post_data;
		$data['article_fk'] = $article_fk;
		
		$this->load->model('media_model');
		$data['movie_list'] = $this->media_model->get_movie_list();
		
		$dd_lookup_pair = $this->media_model->get_dropdown_lookup_pair();
		$data['autocomplete_list'] = json_encode($dd_lookup_pair['list']);
		$data['autocomplete_lookup'] = json_encode($dd_lookup_pair['lookup']);
		
		$this->load->view('admin_edit_article_page',$data);
	}
	
	public function save_article() {
		//if below is > 0  this is from an edit page!
		$article_fk = $this->input->post('article_fk',true);
		if((int)$article_fk > 0) {
			$type = 'edit';
			$redirect = '/admin/edit_article/'.$article_fk;
		} else {
			$article_fk = null; //null it out
			$type = 'add';
			$redirect = '/admin/add_article';
		}
		
		
		$post_data['media_item'] = $media_item = $this->input->post('media_item_field',true);
		$post_data['article_title'] = $article_title = $this->input->post('article_title_field',true);
		$post_data['article_preview_text'] = $article_preview_text = $this->input->post('article_preview_text_field',true);
		$post_data['article_img_link'] = $article_img_link = $this->input->post('image_link_field',true);
		$post_data['article_content'] = $article_content = $this->input->post('article_content_field');
		$article_expiry_date = $this->input->post('expiry_date_field',true);
		$post_data['article_expiry_date'] = ($article_expiry_date) ? $article_expiry_date : '';
		$is_live = $this->input->post('is_live',true);
		$post_data['is_live'] = (in_array($is_live,array('0','1'))) ? $is_live : '0';
		$is_approved = $this->input->post('is_approved',true);
		$post_data['is_approved'] = (in_array($is_approved,array('0','1'))) ? $is_approved : '0';
		$post_data['sources'] = $sources = $this->input->post('sources_field',TRUE);
		$post_data['tags'] = $tags = $this->input->post('tags_field',true);
		$tags_array = explode(',',$tags);

		$this->load->library('form_validation');

		$this->form_validation->set_rules('media_item_field', '`Category`', 'required');
		$this->form_validation->set_rules('article_title_field', '`Article Title`', 'required|max_length[128]');
		$this->form_validation->set_rules('article_preview_text_field', '`Preview Text`', 'required|max_length[1024]');
		$this->form_validation->set_rules('image_link_field', '`Image link`', 'required|max_length[256]');
		$this->form_validation->set_rules('article_content_field', '`Article Content`', 'required');
				
		if ($this->form_validation->run() === FALSE) {
			$form_error_areas = array();
			if(form_error('media_item_field')) {
							$form_error_areas['media_item_field'] = 1;
			}
			if(form_error('article_title_field')) {
							$form_error_areas['article_title_field'] = 1;
			}
			if(form_error('article_preview_text_field')) {
							$form_error_areas['article_preview_text_field'] = 1;
			}
			if(form_error('image_link_field')) {
							$form_error_areas['image_link_field'] = 1;
			}
			if(form_error('article_content_field')) {
							$form_error_areas['article_content_field'] = 1;
			}

			$this->session->set_flashdata('validation',$form_error_areas);
			$this->session->set_flashdata('notification',validation_errors('<div class="error">', '</div>'));
			$this->session->set_flashdata('post_data',$post_data);
			
			redirect($redirect);
		} else {
			$this->load->model(array('media_model','article_model','tag_model'));

			$media_row = $this->media_model->get_media_by_pk($media_item);
			
			if(empty($media_row)) {
				$this->session->set_flashdata('post_data',$post_data);
				
				$this->session->set_flashdata('notification','Error occurred, please try again.');
				redirect($redirect);
				return;
			}
			
			$article_pk = $this->article_model->save_article($this->user_fk,$article_title,$article_content,$article_preview_text,
																				$media_row['type'],$media_row['media_pk'],$article_img_link,$is_live,
																				$is_approved,$article_fk,$sources,$article_expiry_date);
			$this->tag_model->save_list_of_tags_for_article($article_pk,$tags_array);
			
			$this->load->model('system_job_model');
			$this->system_job_model->update_article_uris();
			$this->system_job_model->update_search_tables();
			$this->system_job_model->sanity_check_uris();
			$this->system_job_model->queue_subscription_emails();
			
			$this->session->set_flashdata('notification','Successfully completed!');
			redirect('/admin');
		}
	}
	
	//=========================================
	
	
	public function add_review() {
		$data = array();
		$data['validation'] = $this->session->flashdata('validation');
		$data['notification'] = $this->session->flashdata('notification');
		$data['user_data'] = $this->session->flashdata('post_data');
		
		$this->load->model('media_model');
		
		$dd_lookup_pair = $this->media_model->get_dropdown_lookup_pair();
		$data['autocomplete_list'] = json_encode($dd_lookup_pair['list']);
		$data['autocomplete_lookup'] = json_encode($dd_lookup_pair['lookup']);
		
		$this->load->view('admin_add_review_page',$data);
	}
	
	function edit_review($review_fk = null) {
		if($review_fk === null) {
			$this->edit_list(POST_REVIEW);
			return;
		}
		
		$this->load->model(array(
			'article_model',
			'media_poll_model',
			'tag_model',
		));
		
		$article_row = $this->article_model->get_article_by_pk($review_fk);
		
		if(empty($article_row)) {
			$this->session->set_flashdata('notification','We\'re sorry, that page could not be found ...');
			redirect('/admin');
		}

		$tags = $this->tag_model->get_list_of_tags_for_article($article_row['article_pk']);
		$tags = implode(',', $tags);
		
		$post_data['media_item'] = $article_row['media_fk'];
		$post_data['review_title'] = $article_row['title'];
		$post_data['review_preview_text'] = $article_row['preview_text'];
		$post_data['review_img_link'] = $article_row['image_link'];
		$post_data['review_content'] = $article_row['content'];
		$post_data['is_live'] = $article_row['is_live'];
		$post_data['is_approved'] = $article_row['is_approved'];
		$post_data['rating_amnt'] = $this->media_poll_model->get_criticowl_rating($article_row['media_fk']);
		$post_data['sources'] = $article_row['sources'];
		$post_data['tags'] = $tags;

		$data['user_data'] = $post_data;
		$data['article_fk'] = $review_fk;
		
		$this->load->model('media_model');
		
		$dd_lookup_pair = $this->media_model->get_dropdown_lookup_pair();
		$data['autocomplete_list'] = json_encode($dd_lookup_pair['list']);
		$data['autocomplete_lookup'] = json_encode($dd_lookup_pair['lookup']);
		
		$this->load->view('admin_edit_review_page',$data);
	}
	
	public function save_review() {
		//if below is > 0  this is from an edit page!
		$review_fk = $this->input->post('review_fk',true);
		if((int)$review_fk > 0) {
			$type = 'edit';
			$redirect = '/admin/edit_review/'.$review_fk;
		} else {
			$review_fk = null; //null it out
			$type = 'add';
			$redirect = '/admin/add_review';
		}
		
		$post_data['media_item'] = $media_item = $this->input->post('media_item_field',true);
		$post_data['review_title'] = $review_title = $this->input->post('review_title_field',true);
		$post_data['review_preview_text'] = $review_preview_text = $this->input->post('review_preview_text_field',true);
		$post_data['review_img_link'] = $review_img_link = $this->input->post('image_link_field',true);
		$post_data['rating_amnt'] = $rating_amnt = (float)$this->input->post('rating_amnt_field');
		$post_data['review_content'] = $review_content = $this->input->post('review_content_field');
		$is_live = $this->input->post('is_live',true);
		$post_data['is_live'] = (in_array($is_live,array('0','1'))) ? $is_live : '0';
		$is_approved = $this->input->post('is_approved',true);
		$post_data['is_approved'] = (in_array($is_approved,array('0','1'))) ? $is_approved : '0';
		$post_data['sources'] = $sources = $this->input->post('sources_field',true);
		$post_data['tags'] = $tags = $this->input->post('tags_field',true);
		$tags_array = explode(',',$tags);

		$this->load->library('form_validation');

		$this->form_validation->set_rules('media_item_field', '`Category`', 'required');
		$this->form_validation->set_rules('review_title_field', '`Review Title`', 'required|max_length[128]');
		$this->form_validation->set_rules('review_preview_text_field', '`Preview Text`', 'required|max_length[1024]');
		$this->form_validation->set_rules('image_link_field', '`Image link`', 'required|max_length[256]');
		$this->form_validation->set_rules('review_content_field', '`Review Content`', 'required');
	
		if ($this->form_validation->run() === FALSE) {
			$form_error_areas = array();
			if(form_error('media_item_field')) {
							$form_error_areas['media_item_field'] = 1;
			}
			if(form_error('review_title_field')) {
							$form_error_areas['review_title_field'] = 1;
			}
			if(form_error('review_preview_text_field')) {
							$form_error_areas['review_preview_text_field'] = 1;
			}
			if(form_error('image_link_field')) {
							$form_error_areas['image_link_field'] = 1;
			}
			if(form_error('review_content_field')) {
							$form_error_areas['review_content_field'] = 1;
			}
			
			$this->session->set_flashdata('validation',$form_error_areas);
			$this->session->set_flashdata('notification',validation_errors('<div class="error">', '</div>'));
			$this->session->set_flashdata('post_data',$post_data);
			
			redirect($redirect);
		} else {
			$this->load->model(array(
				'media_model',
				'article_model',
				'media_poll_model',
				'tag_model',
			));

			$media_row = $this->media_model->get_media_by_pk($media_item);
			
			if(empty($media_row)) {
				
				$this->session->set_flashdata('post_data',$post_data);
				
				$this->session->set_flashdata('notification','Error occurred, please try again.');
				redirect($redirect);
				return;
			}
			
			$article_pk = $this->article_model->save_review($this->user_fk,$review_title,$review_content,$review_preview_text,
																				$media_row['type'],$media_row['media_pk'],$review_img_link,$is_live,$is_approved,$review_fk,$sources);
			$this->tag_model->save_list_of_tags_for_article($article_pk,$tags_array);

			if($rating_amnt > 0) {
				$this->media_poll_model->update_criticowl_rating($media_row['media_pk'],$rating_amnt);				
			}
			
			$this->load->model('system_job_model');
			$this->system_job_model->update_article_uris();
			$this->system_job_model->update_search_tables();
			$this->system_job_model->update_top_rated_slideshow();
			$this->system_job_model->sanity_check_uris();

			$this->system_job_model->update_movies_worth_watching_slideshow();
			$this->system_job_model->queue_subscription_emails();
			
			$this->session->set_flashdata('notification','Successfully completed!');
			redirect('/admin');
		}
	}
	
	//=========================================
	
	
	function edit_quotes_list() {
		$this->load->model('quotation_model');
		
		$data['list'] = $this->quotation_model->get_quotation_list();
		
		$this->load->view('admin_edit_quote_list_page',$data);
	}
	
	function edit_list($type) {
		$this->load->model('admin_model');
		
		if($type === POST_NEWS) {
			$data['list_title'] = "Edit News";
			$data['list_type'] = POST_NEWS;
			$data['list'] = $this->admin_model->get_edit_list(POST_NEWS);
			
			$this->load->view('admin_edit_list_page',$data);
		} else if($type === POST_ARTICLE) {
			$data['list_title'] = "Edit Article";
			$data['list_type'] = POST_ARTICLE;
			$data['list'] = $this->admin_model->get_edit_list(POST_ARTICLE);
			
			$this->load->view('admin_edit_list_page',$data);
		} else if($type === POST_REVIEW) {
			$data['list_title'] = "Edit Review";
			$data['list_type'] = POST_REVIEW;
			$data['list'] = $this->admin_model->get_edit_list(POST_REVIEW);
			
			$this->load->view('admin_edit_list_page',$data);
		}
	}
	
	//==============================================================

	function tool_image_linker() {
		$data = array();
		$data['notification'] = '';
		
		$this->load->view('admin_tool_image_linker',$data);
	}

	//==============================================================
	
	//AJAX lookup
	public function get_media_information() {
		$media_fk = (int)$this->input->get('media_fk');
		
		$this->load->model('media_model');
		
		$media_data = $this->media_model->get_media_by_pk($media_fk);
		
		if(empty($media_data)) {
			echo 0;
			return;
		}
		
		$tmp = explode(' ',$media_data['release_date']);
		$media_data['release_date'] = $tmp[0];

		if(!empty($media_data['dvd_release_date'])) {
			$tmp = explode(' ',$media_data['dvd_release_date']);
			$media_data['dvd_release_date'] = $tmp[0];
		}
		if(!empty($media_data['bluray_release_date'])) {
			$tmp = explode(' ',$media_data['bluray_release_date']);
			$media_data['bluray_release_date'] = $tmp[0];
		}
		
		echo json_encode($media_data);
	}

	//AJAX get compressed image url
	// MOVED TO API
	// public function get_internal_image_url() {
	// 	$image_url = $this->input->get('image_url',TRUE);
	// 	$this->load->model('image_model');

	// 	//echo out the updated url
	// 	echo $this->image_model->get_compressed_image($image_url);
	// }
}