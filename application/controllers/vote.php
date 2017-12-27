<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vote extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('media_poll_model');
	}
	
	public function vote_watched_it() {
		$media_fk = (int)$this->input->post('media_fk');
		
		if(!$this->_is_valid_vote(array('media_fk_check','pre-release'),array('media_fk'=>$media_fk))) {
			echo 'hmmm ...';
			die();
		}
		
		$this->media_poll_model->cast_ballot_watched_it($media_fk);
		
		$this->load->model('system_job_model');
		$this->system_job_model->update_media_stats_tables();
			
		echo 'done';
	}
	
	public function vote_want_to_watch_it() {
		$media_fk = (int)$this->input->post('media_fk');
		
		if(!$this->_is_valid_vote(array('media_fk_check'),array('media_fk'=>$media_fk))) {
			echo 'hmmm ...';
			die();
		}
		
		$this->media_poll_model->cast_ballot_want_to_watch_it($media_fk);
		
		$this->load->model('system_job_model');
		$this->system_job_model->update_media_stats_tables();
		
		echo 'done';
	}
	
	public function vote_rating() {
		
		$media_fk = (int)$this->input->post('media_fk');
		$rating_amt = (float)$this->input->post('rating');
		
		if(!$this->_is_valid_vote(array('media_fk_check','pre-release'),array('media_fk'=>$media_fk))) {
			echo 'hmmm ...';
			die();
		}
		
		
		if($rating_amt < 0.1) {
			$rating_amt = 0.1; //0 will be ingored so 1% will be the lowest acceptable
		}
		
		if((float)$rating_amt > 0.0 && (float)$rating_amt <= 1.0) {
			$this->media_poll_model->cast_ballot_rating($media_fk,$rating_amt);
			
			$this->load->model('system_job_model');
			$this->system_job_model->update_media_stats_tables();
			$this->system_job_model->update_movies_worth_watching_slideshow();
		}
			
		echo 'done';
	}
	
	private function _is_valid_vote($invalid_terms,$params) {
		if(in_array('media_fk_check',$invalid_terms)) {
			if(!($params['media_fk'] > 0)) {
				return FALSE;
			}
		} else if(in_array('pre-release',$invalid_terms)) {
			if($this->_before_release_date($params['media_fk'])){
				return FALSE;
			}
		} 
		
		return TRUE;
	}
	
	private function _before_release_date($media_fk) {
		$this->load->model('media_model');
		
		$media_data = $this->media_model->get_media_by_pk($media_fk);
		$release_date = $media_data['release_date'];
		unset($media_data);
		
		if(time() > strtotime($release_date)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
}