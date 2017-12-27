<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailer_model extends CI_Model {

	function add_to_queue($tos,$subject,$body, $from_name = "CriticOwl") {
		# Removed functionality
		return true;
		// $system_db = $this->load->database('system', TRUE);
		
		// $sql = "INSERT INTO `mail_queue`
		// 		(`from`,`from_name`,`to`,`subject`,`message`,`date_added`,`application_id`,`environment`)
		// 		VALUES (?,?,?,?,?,NOW(),'CRITICOWL',?)";
								
		// foreach((array)$tos as $to) {
		// 	$system_db->query($sql,array($this->config->item('donotreply_email'),$from_name,$to,$subject,$body,$this->config->item('environment')));			
		// }
	}
	
	function add_system_email_to_queue($tos,$subject,$body) {
		# Removed functionality
		return true;
		// $system_db = $this->load->database('system', TRUE);
		
		// $sql = "INSERT INTO `mail_queue`
		// 		(`from`,`from_name`,`to`,`subject`,`message`,`date_added`,`application_id`,`environment`)
		// 		VALUES (?,?,?,?,?,NOW(),'SYSTEM',?)";
							
		// foreach((array)$tos as $to) {
		// 	$system_db->query($sql,array("ms.prime.server@gmail.com",'MSPrime Server',$to,$subject,$body,$this->config->item('environment')));		
		// }
	}

}
 