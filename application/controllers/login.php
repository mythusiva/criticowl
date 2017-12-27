<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->session->sess_destroy();
			 
		$this->load->model('user_model');
	}

	public function index()	{		
		redirect('/');
	}
	
	//ajax call
	public function authenticate($ajax_call = true) {
		$email_address = $this->input->post('email',true);
		$password = $this->input->post('pass',true);
		
		if($this->_is_valid_login_attempt() && $this->user_model->login_check($email_address,$password)) {
			log_message("DEBUG","Login check success");
			$user_data = array( 
				'session_id'    => md5(time()),
			    'ip_address'    => $this->input->ip_address(),
			    'user_agent'    => $this->input->user_agent(),
			    'last_activity' => time(),
				'email_address'=>$email_address,
				'logged_in'=> true,
			);
			$this->_clear_login_attempts();
			$this->session->set_userdata($user_data); 

			log_message("DEBUG","Setting the session for this user");
			if($ajax_call) {
				echo 1;
			} else {
				return true;
			}
		} else {
			log_message("DEBUG","Failed to pass verification of user...");
			if($ajax_call) {
				echo 0;
			} else {
				return false;
			}
		}
	}
	
	public function user_login() {
		
		if($this->user_model->is_blocked_user()) {
			$this->session->set_flashdata('notification',
																				'Sorry, you have been banned from our admin pages.');
			redirect('/');
		}
		
		$result = $this->authenticate($ajax_call = false);
		
		log_message("DEBUG","Login credentials -> ".(int)$result);

		if(!$result) {
			$this->session->set_flashdata('notification',
																				'Failed to verify your credentials, please try again.');
			redirect('/admin/login');
		} else {
			$url = $this->input->cookie('oreo', TRUE);
			redirect('/admin');
		}
	}
	
	private function _is_valid_login_attempt() {
		if(!$this->user_model->is_blocked_user()) {
			$this->user_model->log_login_attempt();
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function _clear_login_attempts() {
		$this->user_model->clear_login_attempts();
	}
	
}
