<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct() {
			parent::__construct();
	}

	//AJAX get compressed image url
	public function get_internal_image_url() {
		$image_url = $this->input->get('image_url',TRUE);
		$this->load->model('image_model');

		//echo out the updated url
		echo $this->image_model->get_compressed_image($image_url);
	}
}