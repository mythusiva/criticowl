<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('seo_model');
	}
	
	public function sitemap() {
		$this->_update_general_section_links();
		$this->_update_movies_urls();
		$this->_update_article_uris();
		
		$data['uris'] = $this->seo_model->get_uris();
		
		$this->load->view('sitemap',$data);
	}
	
	private function _update_general_section_links() {
		
		$uris = array(
			'reviews',
			'articles',
			'exclusive_articles',
		);
		
		foreach($uris as $uri) {
			$this->seo_model->update_uri($uri,'0.5');			
		}
		
	}
	
	private function _update_movies_urls() {
		$uri_path = "movies/";
		
		$pks = $this->db->query("SELECT uri_segment FROM media WHERE `type` = ?",array(MEDIA_MOVIE))->result_array();
		
		foreach($pks as $pk) {
			$uri = $uri_path.$pk['uri_segment'];
			$this->seo_model->update_uri($uri,'0.8');
		}
	}
	
	private function _update_article_uris() {
		
		$pks = $this->db->query("SELECT article_type,uri_segment FROM article WHERE is_live = 1")->result_array();
		
		foreach($pks as $pk) {
			if($pk['article_type'] === POST_ARTICLE) {
				$uri_path = 'article/';
			} else if($pk['article_type'] === POST_REVIEW) {
				$uri_path = 'review/';
			} else {
				continue;
			}
			
			$uri = $uri_path.$pk['uri_segment'];
			$this->seo_model->update_uri($uri,'1.0');
		}
	}
	
	private function _get_article_pks($article_type) {
		$sql = "SELECT article_pk FROM article WHERE article_type = ?";
		
		$article_pks = $this->db->query($sql,array($article_type))->result_array();
	}
	
}