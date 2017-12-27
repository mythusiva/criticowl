<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscription extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	function subscribe() {
		$media_pk = (int)$this->input->post('media_fk');
		$email_address = $this->input->post('email_address',TRUE);
		
		$this->load->library('form_validation');
		$this->load->model('subscription_model');	
		
		if(!empty($email_address) && $this->form_validation->valid_email($email_address)) {
			
			$this->subscription_model->add_update_media_subscription($media_pk,$email_address);
			
			$output['status'] = 'valid';
		} else {
			$output['status'] = 'invalid';
		}
		
		echo json_encode($output);
	}
	
	function remove($token,$hashed_email) {
		$this->load->model('subscription_model');	
		$this->subscription_model->unsubscribe_media_subscription($token,$hashed_email);

		$this->session->set_flashdata('notification','Your request to unsubscribe has been processed.');
		redirect('/');
	}
}