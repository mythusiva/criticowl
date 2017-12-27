<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Should_i_watch_it extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('should_i_watch_it_model');

		$this->session->set_flashdata('notification','Sorry this page is no longer available because you can now use this feature in our global search. Give it a try!');
		redirect('/');
	}

}