<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_Log extends CI_Log  {

	function __construct()
	{
			parent::__construct();
	}

	public function write_log($level = 'error', $msg, $php_error = FALSE) {
		
		
		$level = strtoupper($level);
		
		if($level === 'ERROR') {
			$ci = &get_instance();

			$to = array('errors@criticowl.com','tony@criticowl.com','yothe@criticowl.com');
			$subject = 'CriticOwl Severity: '.$level;
			$body = 'Severity: '.$level.'  --> '.$msg. ' '.$php_error.' <br /><br />'.json_encode($_SERVER);
			$ci->load->model('emailer_model');
			$ci->emailer_model->add_system_email_to_queue($to,$subject,$body);

		}
			
		parent::write_log($level, $msg, $php_error);
	}

		
}

