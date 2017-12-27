<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_job_model extends CI_Model {
	
	public function update_media_uris() {
		$this->trigger_job('job_update_media_uris');
	}
	public function update_article_uris() {
		$this->trigger_job('job_update_article_uris');
	}
	public function update_top_rated_slideshow() {
		$this->trigger_job('job_update_top_rated_slideshow');
	}
	public function update_search_tables() {
		$this->trigger_job('job_update_search_tables');
	}
	public function update_media_stats_tables() {
		$this->trigger_job('job_update_media_stats_tables');
	}
	public function sanity_check_uris() {
		$this->trigger_job('job_sanity_check_uris');
	}
	public function update_top_5_movies_slideshow() {
		$this->trigger_job('job_update_top_5_movies_slideshow');
	}
	
	public function update_movies_worth_watching_slideshow() {
		$this->trigger_job('job_update_movies_worth_watching_slideshow');
	}

	public function queue_subscription_emails() {
		$this->trigger_job('job_send_email_to_article_subscribers');
	}
	
	public function trigger_job($task_name) {
		$this->_queue_job($task_name);
	}
	
	
	private function _queue_job($task_name) {
		# Removed functionality ...
		return true;
		// $this->db->query('UPDATE system.job SET run_next = 1 WHERE task_name = ? AND `environment` = ? AND `application_id` = ?',array(
		// 																					$task_name,
		// 																					$this->config->item('environment'),
		// 																					$this->config->item('application_id')));
	}
	
	
	public function is_persist($job_name) {
		# Removed functionality ...
		return true;
		// $result = $this->db->query('SELECT * FROM system.job WHERE `task_name` AND `environment` = ? AND `application_id` = ?',
		// 																		array(
		// 																					$job_name,
		// 																					$this->config->item('environment'),
		// 																					$this->config->item('application_id')
		// 																		)
		// 																)->row_array();
		
		// if(isset($result['is_persist']) && (int)$result['is_persist'] === 1) {
		// 	return TRUE;
		// } else {
		// 	return FALSE;
		// }
	}
	
	public function increment_instance_counter($semaphore_id) {
		# Removed functionality ...
		return true;
		// $this->db->query("UPDATE system.semaphore SET current_number_instances = current_number_instances + 1 WHERE current_number_instances <> max_number_instances
		// 									AND identifier = ? AND `environment` = ? AND `application_id` = ?",array(
		// 									$semaphore_id,
		// 									$this->config->item('environment'),
		// 									$this->config->item('application_id'),
		// 								));
		
		// if($this->db->affected_rows() > 0) {
		// 	return TRUE;
		// } else {
		// 	return FALSE;
		// }
	}
	
	public function decrement_instance_counter($semaphore_id) {
		# Removed functionality ...
		return true;
		// $this->db->query("UPDATE system.semaphore SET current_number_instances = current_number_instances - 1 WHERE current_number_instances > 0
		// 									AND identifier = ? AND `environment` = ? AND `application_id` = ?",array(
		// 									 $semaphore_id,
		// 									 $this->config->item('environment'),
		// 									 $this->config->item('application_id'),
		// 								 ));
		
		// if($this->db->affected_rows() > 0) {
		// 	return TRUE;
		// } else {
		// 	return FALSE;
		// }
	}
	
	public function is_at_max_allowed_instance($semaphore_id) {
		# Removed functionality ...
		return true;
		// $q = $this->db->query('SELECT 1 FROM system.semaphore WHERE identifier = ? AND `max_number_instances` = `current_number_instances` AND `environment` = ? AND `application_id` = ?',array(
		// 															$semaphore_id,
		// 															$this->config->item('environment'),
		// 															$this->config->item('application_id'),
		// 														));
		
		// if($q->num_rows() > 0) {
		// 	return TRUE;
		// } else {
		// 	return FALSE; 
		// }
	}
	
	public function get_current_number_of_instances($semaphore_id) {
		# Removed functionality ...
		return 1;
		// $result = $this->db->query('SELECT current_number_instances FROM system.semaphore WHERE identifier = ? AND `environment` = ? AND `application_id` = ?',array(
		// 															$semaphore_id,
		// 															$this->config->item('environment'),
		// 															$this->config->item('application_id'),
		// 														))->row_array();
		
		// if(isset($result['current_number_instances'])) {
		// 	return (int)$result['current_number_instances'];
		// } else {
		// 	log_message("ERROR","Something went wrong, bad semaphore_id {$semaphore_id} in ".__FUNCTION__);
		// 	return FALSE; 
		// }
	}
	
}

