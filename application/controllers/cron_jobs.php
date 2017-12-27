<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron_jobs extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		if(!$this->input->is_cli_request()) {
			echo "Forbidden!";
			die();
		}
		
		$this->load->model('system_job_model');
		
		$this->current_cmd = '';
		$this->is_work_to_do = TRUE;
		$this->log_messages = '';
		$this->jobs = array();
		
		$this->_init_jobs_table();
		$this->_init_semaphors();
		
		if(!$this->system_job_model->increment_instance_counter('job_manager')) {
			log_message('INFO','job_manager is locked, probably already running ...');
			die();
		}
	}
	
	public function __destruct() {
		$this->system_job_model->decrement_instance_counter('job_manager');

		if($this->is_work_to_do && !empty($this->log_messages)) {			
			//send email here
			$tos = array('cron@criticowl.com');
			$this->emailer_model->add_system_email_to_queue("cron@criticowl.com","CriticOwl - Cron Run: {$this->current_cmd}",$this->log_messages);
		}
	}
		
	private function _init_semaphors() {
		
		$current_semaphores = $this->db->query('SELECT * FROM system.semaphore WHERE `environment` = ? AND `application_id` = ?',
																				array($this->config->item('environment'),
																							$this->config->item('application_id')
																				)
																		)->result_array();
		
		//add new semaphores here
		$other_semaphores = array(
			'job_manager',
		);
		
		$this->jobs = array_merge($this->jobs,$other_semaphores);
		
		if(count($this->jobs) !== count($current_semaphores)) {
			foreach($this->jobs as $semaphore_id) {
				$ins_data = array(
					$semaphore_id,
					$this->config->item('environment'),
					$this->config->item('application_id'),
				);
				$this->db->query("INSERT IGNORE INTO `system`.`semaphore`
									(`identifier`,`environment`,`application_id`,`date_last_updated`)
								  VALUES
									(?,?,?,NOW())",$ins_data);
				$insert_id = $this->db->insert_id();
				
				//update code here for overriding default max num instances
				// 
				//
			}
		}
		
	}
	
	private function _job_manager_instances_clearance() {
		
	}
	
	
	private function _get_available_jobs() {
		$funcs = (get_class_methods('Cron_jobs'));
		$jobs = array();
		
		foreach($funcs as $f) {
			if(strpos($f,'job_') === 0) {
				$jobs[] = $f;
			}
		}
		
		$this->jobs = $jobs;
		
		return $jobs;
	}
	
	private function _init_jobs_table() {
		//get all functions that are crons and update the job table
		
		$jobs = $this->_get_available_jobs();
		$current_jobs = $this->db->query('SELECT * FROM system.job WHERE `environment` = ? AND `application_id` = ?',
																				array($this->config->item('environment'),
																							$this->config->item('application_id')
																				)
																		)->result_array();
		
		if(count($jobs) !== count($current_jobs)) {
			foreach($jobs as $j) {
				$ins_data = array(
					$j,
					$j,
					$this->config->item('environment'),
					$this->config->item('application_id'),
				);
				$this->db->query("INSERT IGNORE INTO `system`.`job`
									(`task_name`,`function_name`,`environment`,`application_id`)
								  VALUES
									(?,?,?,?)",$ins_data);			
			}
		}
		
	}
	
	//=========================================================================
	
	public function run_jobs($env) {
		$this->_job_start(__FUNCTION__);
		
		$sql = 	"		SELECT *
								FROM system.job
								WHERE environment = ?
								AND run_next = 1
								AND application_id = 'CRITICOWL'";
				
		$jobs_to_run = $this->db->query($sql,array($env))->result_array();
		
		if(empty($jobs_to_run)) {
			$this->is_work_to_do = false;
			return; //nothing to run, don't waste resources here after.
		}
		
		foreach($jobs_to_run as $job) {
			echo "\n\n>>>>>> Running Job PK {$job['job_pk']} : {$job['task_name']} \n";
			try {
				$incremented = FALSE;
				
				if(!$this->system_job_model->is_at_max_allowed_instance($job['task_name']) &&
						$this->system_job_model->increment_instance_counter($job['task_name'])) {
					
					$incremented = TRUE;
					
					$this->db->query("UPDATE system.job SET run_next = 0, date_last_run = NOW() WHERE job_pk = ? AND is_persist = 0",array($job['job_pk']));
					$func_name = $job['function_name'];
					$this->{$func_name}();
				}

			} catch (Exception $e) {
				$error_message = $e->getMessage();
				log_message("ERROR","Cron job <<".$job['task_name'].">> failed: {$error_message}");
				$this->db->query("UPDATE system.job SET run_next = 0, status = ?, date_last_run = NOW() WHERE job_pk = ?",array($error_message,$job['job_pk']));
			}
				
			if($incremented) {
				$this->system_job_model->decrement_instance_counter($job['task_name']);
			}
		}
		
		$this->_job_complete();
		
	}
	
	//=========================================================================
	
	//-------Generate Uris
	public function job_update_media_uris() {
		$this->_job_start(__FUNCTION__);
		
		$this->load->model('media_model');
		
		$this->db->trans_start();
		$this->media_model->update_media_uris();
		$this->db->trans_complete();
		
		$this->_job_complete();
	}
	
	//=========================================================================
	
	public function job_update_article_uris() {
		$this->_job_start(__FUNCTION__);
		
		$this->load->model('article_model');
		$this->db->trans_start();
		$this->article_model->update_article_uris();
		$this->db->trans_complete();

		$this->_job_complete();
	}
	
	//=========================================================================
	
	//-------Update top rated slides
	public function job_update_top_rated_slideshow() {
		$this->_job_start(__FUNCTION__);
		
		$this->load->model('media_model');
		
		$top_rated = $this->media_model->get_top_rated(25);
		$slideshow_fk = $this->db->query("	SELECT slideshow_pk
																				FROM slideshow
																				WHERE unique_identifier = 'top_rated_media'")->row()->slideshow_pk;
		
		$this->db->trans_start();
		
		//delete existing slides
		$this->db->query("delete from slide where slideshow_fk = ?",array($slideshow_fk));
		
		$order = 1;
		foreach($top_rated as $new_slide) {
			$this->db->query("INSERT INTO slide
												(slideshow_fk,title,`label`,image_url,read_more_link,`order`)
												VALUES
												(?,?,?,?,?,?)",
											array(
												$slideshow_fk,
												$new_slide['title'],
												$new_slide['label'],
												$new_slide['image_url'],
												$new_slide['read_more_link'],
												$order
											));
			
			$order++;
		}
		
		$this->db->trans_complete();
		
				
		if ($this->db->trans_status() === FALSE)
		{
			echo "ERROR, unable to commit the transaction!";
		}
		
		$this->_job_complete();
		clear_all_cache();

	}
	
	//=========================================================================
	
	//-------Update top 5 movies this year slides
	public function job_update_top_5_movies_slideshow() {
		$this->_job_start(__FUNCTION__);
		
		$this->load->model('media_model');
		
		$current_year = date('Y');
		
		$top5 = $this->media_model->get_top_media_for_year($current_year,MEDIA_MOVIE,5);
		$slideshow_uid = 'top_5_movies_this_year';
		if(count($top5) < 5) {
			$top5 = $this->media_model->get_top_media_for_year($current_year-1,MEDIA_MOVIE,5);
			$slideshow_uid = 'top_5_movies_last_year';
		}
		
		if(count($top5) === 5) {
	
			$slideshow_fk = $this->db->query("	SELECT slideshow_pk
												FROM slideshow
												WHERE unique_identifier = ?",array($slideshow_uid))->row()->slideshow_pk;
	
			$this->db->trans_start();
			
			//delete existing slides
			$this->db->query("delete from slide where slideshow_fk = ?",array($slideshow_fk));
			
			$order = 1;
			foreach($top5 as $new_slide) {
				$standing = count($top5) - ($order - 1);
				
				$this->db->query("INSERT INTO slide
										(slideshow_fk,title,`label`,image_url,read_more_link,`order`)
										VALUES
										(?,?,?,?,?,?)",
									array(
										$slideshow_fk,
										"{$standing}. ".$new_slide['title'],
										$new_slide['label'],
										$new_slide['image_url'],
										$new_slide['read_more_link'],
										$order
									));
				
				$order++;
			}
			
			$this->db->trans_complete();
			
					
			if ($this->db->trans_status() === FALSE)
			{
				echo "ERROR, unable to commit the transaction!";
			}

		}
		
		$this->_job_complete();
		clear_all_cache();

	}
	
	//=========================================================================
	
	//-------Update Search Tables
	public function job_update_search_tables() {
		$this->_job_start(__FUNCTION__);
		
		$this->load->model('search_model');

		$this->db->trans_start();
		
		$this->search_model->populate_search_articles();
		$this->search_model->populate_search_media();
		$this->search_model->populate_search_tags();
		$this->search_model->remove_disabled_search_items();
		
		$this->db->trans_complete();
		
		$this->_job_complete();
	}
	
	//=========================================================================
	
	//------Update media_poll_user Table
	public function job_update_media_stats_tables() {
		$this->_job_start(__FUNCTION__);
		
		//will update all stats tables in serial order
		
		$this->db->trans_start();
		
		$this->update_media_poll_users();
		$this->update_media_stats();
		
		$this->db->trans_complete();
		
		$this->_job_complete();
	}
	
	private function update_media_poll_users() {
		$sql_update_user_count_watched_it = "	UPDATE media_poll_user mpu
												INNER JOIN 
													(select media_poll_user_fk,SUM(value) as count_watched_it, count(1) as times_voted
													FROM media_poll_user_ballot
													WHERE ballot_type = 'WATCHED_IT'
													GROUP BY media_poll_user_fk) mpub_wi
												ON mpub_wi.media_poll_user_fk = mpu.media_poll_user_pk
												SET mpu.count_watched_it = mpub_wi.count_watched_it,
													mpu.is_max_watched_it_count_reached = IF(mpub_wi.times_voted > ".MAX_VOTE_WATCHED_IT.",1,0)";
		$sql_update_user_count_want_to_watch_it = " UPDATE media_poll_user mpu
													INNER JOIN 
														(select media_poll_user_fk,SUM(value) as count_want_to_watch_it, count(1) as times_voted
														FROM media_poll_user_ballot
														WHERE ballot_type = 'WANT_TO_WATCH_IT'
														GROUP BY media_poll_user_fk) mpub_wtwi
													ON mpub_wtwi.media_poll_user_fk = mpu.media_poll_user_pk
													SET mpu.count_want_to_watch_it = mpub_wtwi.count_want_to_watch_it,
														mpu.is_max_want_to_watch_it_count_reached = IF(mpub_wtwi.times_voted > ".MAX_VOTE_WANT_TO_WATCH_IT.",1,0)";
		$sql_update_user_avg_rating = "	UPDATE media_poll_user mpu
										INNER JOIN 
											(select media_poll_user_fk,SUM(value)/count(value) as average_user_rating, count(1) as times_voted
											FROM media_poll_user_ballot
											WHERE ballot_type = 'RATING'
											GROUP BY media_poll_user_fk) mpub_r
										ON mpub_r.media_poll_user_fk = mpu.media_poll_user_pk
										SET mpu.average_user_rating = mpub_r.average_user_rating,
											mpu.is_max_rating_count_reached = IF(mpub_r.times_voted > ".MAX_VOTE_RATING.",1,0)";
							
		echo "Updating users : count watched it ... ";			
		$this->db->query($sql_update_user_count_watched_it);
		echo "Done! \n";
		echo "Updating users : count want to watch it ... ";			
		$this->db->query($sql_update_user_count_want_to_watch_it);
		echo "Done! \n";
		echo "Updating users average ratings ... ";			
		$this->db->query($sql_update_user_avg_rating);
		echo "Done! \n";
	}
	
	private function update_media_stats() {
		$sql_update_media_stats = "	INSERT INTO media_stats (   media_fk,
																count_watched_it,
																count_want_to_watch_it,
																average_user_rating,
																last_updated)
										SELECT  mpu.media_fk, 
												SUM(mpu.count_watched_it) as count_watched_it,
												SUM(mpu.count_want_to_watch_it) as count_want_to_watch_it,
												SUM(mpu.average_user_rating)/COUNT(CASE WHEN mpu.average_user_rating > 0 THEN mpu.average_user_rating ELSE NULL END) as average_user_rating, 
												NOW() as last_updated
										FROM media_poll_user mpu
										GROUP BY mpu.media_fk
									ON DUPLICATE KEY UPDATE 
										count_watched_it = VALUES(count_watched_it),
										count_want_to_watch_it = VALUES(count_want_to_watch_it),
										average_user_rating = VALUES(average_user_rating), 
										last_updated = NOW()";
		$sql_calculate_ratings = "	UPDATE media_stats
									SET average_overall_rating = IF(average_user_rating > 0,
																	IF(criticowl_rating > 0,(average_user_rating + criticowl_rating)/2,average_user_rating),
																	IF(criticowl_rating > 0,criticowl_rating,0)
																 );";
		
		echo "Updating all media stats ... ";			
		$this->db->query($sql_update_media_stats);
		echo "Done! \n";
		
		echo "Calculating all ratings ... ";			
		$this->db->query($sql_calculate_ratings);
		echo "Done! \n";
	}
	
	//=========================================================================
	
	//URIs sanity check
	public function job_sanity_check_uris() {
		$this->_job_start(__FUNCTION__);
		
		$media_uris = "	select * 
										from media
										group by uri_segment
										having count(uri_segment) > 1";
		$article_uris = "	select * 
											from article
											group by uri_segment
											having count(uri_segment) > 1";
		
		$media_uris_found = $this->db->query($media_uris)->result_array();
		$article_uris_found = $this->db->query($article_uris)->result_array();
		
		if(count($media_uris_found) > 0) {
			echo "Media uri duplicates found! \n";
			foreach($media_uris_found as $uri) {
				echo "media_pk: {$uri['media_pk']} -> uri: {$uri['uri_segment']} \n";
			}
		}
		if(count($article_uris_found) > 0) {
			echo "Article uri duplicates found! \n";
			foreach($article_uris_found as $uri) {
				echo "article_pk: {$uri['article_pk']} -> uri: {$uri['uri_segment']} \n";
			}
		}
		
		$this->_job_complete();
	}
	
	//=========================================================================
	
	//subscriptions
	public function job_send_email_to_article_subscribers() {
		$this->_job_start(__FUNCTION__);
		
		$this->load->model(array('subscription_model'));
		
		$articles =  $this->subscription_model->get_new_articles($no_tweets = TRUE);
		
		$media_subscribers = array();
		
		foreach($articles as $a) {
			
			if(!isset($media_subscribers[$a['media_fk']])) {
				$media_subscribers[$a['media_fk']] = $this->subscription_model->get_media_subscribers($a['media_fk']);
			}
			
			//notify admins
			foreach($this->config->item('new_article_notification') as $admin_email) {
				$recently_added_page_url = base_url().strtolower($a['article_type'])."/".$a['uri_segment'];
				$stortened_url = get_short_url($recently_added_page_url);
				$admin_subject = "New content posted! - {$a['media_title']} {$a['title']}";
				$admin_email_content = "
					Hello Admin, <br>
					Recently added page: <a href='{$recently_added_page_url}'>click here</a><br/>
					Short Url: <a href='{$stortened_url}'>{$stortened_url}</a><br/>
					<br/>
				";
				
				$this->emailer_model->add_to_queue($admin_email,$admin_subject,$admin_email_content);
			}
			
			
			$subject_data = array(
				'media_name' => $a['media_title'],
				'article_title' => $a['title'],
			);
			
			$subject = $this->load->view('email_templates/media_article_subscription_email_subject',$subject_data,TRUE);

			foreach($media_subscribers[$a['media_fk']] as $user) {
				
				$email_data = array(
					'preview_data' => $a,
					'email_address' => $user['email_address'],
					'unsubscribe_token' => $user['unsubscribe_token'],
					'article_type' => $a['article_type'],
				);
				
				$content = $this->load->view('email_templates/media_article_subscription_email',$email_data,TRUE);
				
				$this->emailer_model->add_to_queue($user['email_address'],$subject,$content);
				
			}
					
			//update the article
			$this->db->query("UPDATE article SET date_notified = NOW() WHERE article_pk = ?",array($a['article_pk']));
		
		
		}

		$this->_job_complete();
	}
	
	//=========================================================================
	
	//-------Update worth watching movies slideshow
	public function job_update_movies_worth_watching_slideshow() {
		$this->_job_start(__FUNCTION__);
		
		$this->load->model('media_model');
		
		$current_year = date('Y');
		
		$list = $this->media_model->get_movies_worth_watching_list(25);
		$slideshow_uid = 'top_movies_to_watch';
		
		if(count($list) > 0) {
	
			$slideshow_fk = $this->db->query("	SELECT slideshow_pk
																					FROM slideshow
																					WHERE unique_identifier = ?",array($slideshow_uid))->row()->slideshow_pk;
	
			$this->db->trans_start();
			
			//delete existing slides
			$this->db->query("delete from slide where slideshow_fk = ?",array($slideshow_fk));
			
			$order = 1;
			foreach($list as $new_slide) {
				$this->db->query("INSERT INTO slide
													(slideshow_fk,title,`label`,image_url,read_more_link,`order`)
													VALUES
													(?,?,?,?,?,?)",
									array(
										$slideshow_fk,
										$new_slide['title'],
										$new_slide['label'],
										$new_slide['image_url'],
										$new_slide['read_more_link'],
										$order
									));
				
				$order++;
			}
			
			$this->db->trans_complete();
			echo "SUCCESS, the movies slideshow has been updated";
			clear_all_cache();
			
					
			if ($this->db->trans_status() === FALSE)
			{
				echo "ERROR, unable to commit the transaction!";
			}

		}
		
		$this->_job_complete();
	}
	
	//=========================================================================
	
	//------- Votes summary email
	// public function job_send_vote_summary_email() {
	// 	//this is scheduled through cron tab! However it can be run manually also.
		
	// 	$this->_job_start(__FUNCTION__);
		
	// 	$this->load->model('media_poll_model');
		
	// 	$vote_summary = $this->media_poll_model->get_vote_summary_today();
		
	// 	$msg = '';
		
	// 	if(count($vote_summary) > 0) {
	// 		foreach($vote_summary as $row) {
	// 			$msg .= "{$row['name']} ({$row['type']}) - {$row['ballot_type']} : {$row['value']} <br />";
	// 		}

	// 		$to = $this->config->item('reporting_notification');
	// 		$subject = 'CriticOwl Reports - Vote summary for today';
	// 		$body = $msg;

	// 		$this->emailer_model->add_to_queue($to,$subject,$body);
	// 	}
		
	// 	$this->_job_complete();
	// }
	
	//=========================================================================

	//-------- Reporting Emails
	private function _get_columns_and_data($sql,$args=array()) {
		$rows = $this->db->query($sql,(array)$args)->result_array();

		$columns = $data = array();
		if(count($rows) > 0) {
			$columns = array_keys($rows[0]);
			$data = $rows;
		}

		return array($columns,$data);
	}

	private function _report_vote_summary($days_ago = 1) {
		$days_ago = (int)$days_ago;

		$sql = "SELECT m.name, m.type, mpub.ballot_type, mpub.value
				FROM media_poll_user_ballot mpub
				JOIN media m
				ON m.media_pk = mpub.media_fk
				WHERE DATEDIFF(mpub.date_created,CURDATE()) = -{$days_ago}
				ORDER BY name, ballot_type;";

		return $this->_get_columns_and_data($sql);
	}

	private function _report_search_terms($days_ago = 1) {
		$days_ago = (int)$days_ago;

		$sql = "SELECT search_text, COUNT(text_hash) as times_searched, date_created as date_searched
				FROM search_log
				WHERE DATEDIFF(date_created,CURDATE()) = -{$days_ago}
				GROUP BY text_hash
				ORDER BY times_searched desc;";

		return $this->_get_columns_and_data($sql);
	}

	private function _report_errors_summary($days_ago = 1) {
		$days_ago = (int)$days_ago;
		$application_id = "criticowl_".$this->config->item('environment');

		$sql = "select *
				from archive.raw_log
				where DATEDIFF(log_date,CURDATE()) = -{$days_ago}
				and log_level = \"ERROR\"
				and application_id = ?
				LIMIT 5000;";

		return $this->_get_columns_and_data($sql,array($application_id));
	}

	private function _report_editor_monthly_completed_articles() {

		$sql = "SELECT article_pk,title,article_type,date_created FROM article 
				WHERE is_live = 1 AND is_approved = 1
				AND MONTH(date_created) = IF(MONTH(NOW())-1 = 0,12,MONTH(NOW())-1)";

		return $this->_get_columns_and_data($sql);
	}

	public function job_send_daily_reporting_emails() {
		$this->_job_start(__FUNCTION__);
		
		$this->_send_reporting_emails("DAILY");
		
		$this->_job_complete();
	}

	public function job_send_monthy_reporting_emails() {
		$this->_job_start(__FUNCTION__);
		
		$this->_send_reporting_emails("MONTHLY");
		
		$this->_job_complete();
	}

	private function _send_reporting_emails($frequency_setting) {

		if($frequency_setting === "DAILY") {

			$reports_to_run = array(
				"_report_search_terms" => array(
					'report_name' => "Yesterday's Searched Terms",
					'arguments' => array(1),
					'recipients' => $this->config->item('reporting_notification'),
				),
				"_report_vote_summary" => array(
					'report_name' => "Yesterday's Vote Summary",
					'arguments' => array(1),
					'recipients' => $this->config->item('reporting_notification'),
				),
				"_report_errors_summary" => array(
					'report_name' => "Yesterday's Errors Summary",
					'arguments' => array(1),
					'recipients' => "mythu@criticowl.com",
				),
			);

		} elseif ($frequency_setting === "MONTHLY") {
			
			$reports_to_run = array(
				"_report_editor_monthly_completed_articles" => array(
					'report_name' => "Articles Completed Last Month",
					'arguments' => array(),
					'recipients' => "mythu@criticowl.com",
				),
			);

		} else {
			//nothing to do
			return;
		}
		
		foreach ($reports_to_run as $function_name => $settings) {
			list($headings,$data) = call_user_func_array(array($this, $function_name), $settings['arguments']); 
			if(empty($headings)) {
				continue;
			}
			$msg = $this->load->view('email_templates/admin_reporting_table_summary',array('headings'=>$headings,'data'=>$data),TRUE);
			$to = $settings['recipients'];
			$subject = 'CriticOwl Reports - '.$settings['report_name'];
			$body = $msg;

			$this->emailer_model->add_to_queue($to,$subject,$body);

		}
	}

	//=========================================================================

	//-------- DVD Bluray missing dates email
	public function job_send_missing_dvd_bluray_date_email() {
		$this->_job_start(__FUNCTION__);

		$this->load->model('media_model');
		$list = $this->media_model->get_missing_dvd_bluray_release_dates();
		$email_content = $this->load->view('snippets/dvd_bluray_media_checklist_snippet',array('list'=>$list),TRUE);
		
		$to = $this->config->item('reporting_notification');
		$subject = 'CriticOwl Reports - Weekly DVD/Blu-ray missing dates list';
		$body = "Hi Admins,<br />
		Here is a list of movies that are older than a threshold of ".RELEASE_DATE_BLURAY_THRESHOLD." days that may have DVD/Blu-ray dates available. The list below will only show an entry if either or both of the dates are missing.
		<br /><br />";
		$body .= $email_content;
		
		$body .= "<br /><br />";
		$body .= base_url()."admin/edit_media";

		$this->emailer_model->add_to_queue($to,$subject,$body);

		$this->_job_complete();
	}


	//=========================================================================

	public function job_populate_web_rating_tweet_analysis() {
		$this->_job_start(__FUNCTION__);

		$ci = &get_instance();
		$ci->load->library('twitter_api');
		$ci->load->model('web_rating_tweet_analysis_model');
   		
		$enabled_movies = $this->db->query("select * from media where is_enabled = 1")->result_array();
		foreach ($enabled_movies as $media_row) {
	   		$media_fk = $media_row['media_pk'];
	   		$max_tweet_id = $ci->web_rating_tweet_analysis_model->get_latest_tweet_for_media_fk($media_fk);
	   		
	   		$tweets_data = $ci->twitter_api->search_tweets($media_row['hashtag'],1000,$max_tweet_id);
	   		$tweets_data = reset($tweets_data);

	   		foreach ($tweets_data as $single_tweet) {
	   			if(!isset($single_tweet['id'])) {
	   				//twitter not behaving...
	   				log_message("INFO","Twitter isn't behaving, here is what twitter sent back: ".print_r($tweets_data,TRUE));
	   				continue;
	   			}
	   			$id = $single_tweet['id'];
	   			$tweet_text = $single_tweet['text'];
	   			$ci->web_rating_tweet_analysis_model->insert_new_web_rating_tweet($media_fk,$tweet_text,$id);
	   		}
	   		unset($tweets_data);
		}

		$this->_job_complete();
	}

	public function job_analyze_and_update_web_rating_tweets() {
		$this->_job_start(__FUNCTION__);

		$ci = &get_instance();
		$ci->load->model('web_rating_tweet_analysis_model');
		$ci->web_rating_tweet_analysis_model->classify_and_delete_tweets(1000);

		$this->_job_complete();
	}


	//=========================================================================
	
	private function _job_start($job_function_name) {
		$this->current_cmd = $job_function_name;
		ob_start();
	}
	
	private function _job_complete() {
		
		$logged = ob_get_contents();
		
		if(!empty($logged)) {
			$logged .= ">>> COMPLETED {$this->current_cmd} \n\n";
			
			$this->log_messages .= '<pre>'.$logged.'</pre>';
			ob_end_clean();
		} else {
			log_message("INFO","nothing to log for job: {$this->current_cmd}");
		}

	}
	
}