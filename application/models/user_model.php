<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
	
	/*=============== Auth functions ===============*/
	function login_check($email_address,$password) {
		$sql = "SELECT * FROM user u WHERE u.email_address = ? AND u.password = ?;";
		$q = $this->db->query($sql,array($email_address,md5($password)));

		if($q->num_rows() === 1) {
			return true;
		} else {
			return false;
		}
	}
	
	function session_check() {
		log_message("DEBUG","<<session check>>");
		if($this->session->userdata('logged_in') && $this->get_user_pk() !== FALSE) {
			log_message("DEBUG","<<session check>> user is logged in ... ");
		} else {
			log_message("DEBUG","<<session check>> user is not logged in!");
			
			$value = uri_string();
			$value = $this->encrypt->encode($value);
			setcookie("oreo", $value, time()+3600,'/');  /* expire in 1 hour */
			
			$this->session->sess_destroy();
			if($this->uri->segment(2) !== "login") {
				log_message("DEBUG","<<session check>> redirecting to login screen.");
				redirect('/admin/login');
			} 
		}
	}
	
	function logged_in_check() {
		if($this->session->userdata('logged_in')) {
			return true;
		} else {
			$this->session_check();
			return false;
		}
	}
	
	function destroy_session() {
		$this->session->sess_destroy();
	}
	
	function log_login_attempt() {
		$sql = "INSERT INTO blacklist
						(ip_address,user_agent,date_created,date_modified,try_attempts)
						VALUES
						(?,?,NOW(),NOW(),1)
						ON DUPLICATE KEY UPDATE
						try_attempts = try_attempts+1,
						is_blocked = IF(try_attempts >= ?,1,0),
						date_modified = NOW()";
						
		$this->db->query($sql,array($this->input->ip_address(),$this->input->user_agent(),MAX_LOGIN_ATTEMPTS));
	}
	
	function is_blocked_user() {
		$sql = "SELECT 1
						FROM blacklist
						WHERE ip_address = ? AND is_blocked = 1";
						
		$q = $this->db->query($sql,array($this->input->ip_address()));
		
		if($q->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function clear_login_attempts() {
		$sql = "DELETE FROM blacklist
						WHERE ip_address = ?";
						
		$this->db->query($sql,array($this->input->ip_address()));
	}
	
	/*=============== End Auth ===============*/
	
	function is_user_exists($email_address) {
		$sql = "SELECT *
						FROM user
						WHERE email_address = ?";
		
		$q = $this->db->query($sql,array(strtolower($email_address)));
		
		if($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function is_username_exists($username) {
		$sql = "SELECT *
						FROM user
						WHERE username = ?";
		
		$q = $this->db->query($sql,array(strtolower($username)));
		
		if($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_user_pk() {
		log_message("DEBUG","attepting to get user_pk");
		if($this->logged_in_check()) {
			$email_address = $this->session->userdata('email_address');
			
			$sql = "SELECT user_pk
							FROM user
							WHERE email_address = ?";
			$q = $this->db->query($sql,$email_address);
			if($q->num_rows() > 0) {
				return $q->row()->user_pk;
			}
		} 
		log_message("DEBUG","NONE FOUND!");
		return false;
	}
	
	function get_email_address() {
		if($this->logged_in_check()) {
			$email_address = $this->session->userdata('email_address');
			return $email_address;
		} else {
			log_message("DEBUG","NONE FOUND!");
			return false;
		}
	}
	
	function get_pk_by_email($email_address) {
		
		$sql = "SELECT user_pk
						FROM user
						WHERE email_address = ?";
		return $this->db->query($sql,$email_address)->row()->user_pk;
		
	}
	
	function get_permission_level_for_current_user() {
		$user_pk = $this->get_user_pk();
		return $this->db->query("SELECT permissions FROM user WHERE user_pk = ?",array($user_pk))->row()->permissions;
	}
	
	function verify_current_user_pass($password) {
		$password = md5($password);
		
		if($this->logged_in_check()) {
			$email_address = $this->session->userdata('email_address');
			
			$sql = "SELECT 1
							FROM user
							WHERE email_address = ? AND password = ?";
			$q = $this->db->query($sql,array($email_address,$password));
			
			if($q->num_rows() > 0){
				return true;
			} else {
				return false;
			}
		} else {
			log_message("DEBUG","NONE FOUND!");
			return false;
		}
	}
	
	
	//reset_password
	function is_valid_email($email) {
		$sql = "SELECT 1
						FROM user
						WHERE email_address = ?";
		$q = $this->db->query($sql,array($email));
		if($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	function create_email_verification_token($email) {
		$token = md5(time());
		
		log_message("INFO","setting email verification token for email -> $email");
		
		$sql = "UPDATE user
						SET verification_token = ?
						WHERE email_address = ?
						LIMIT 1";
						
		$this->db->query($sql,array($token,$email));
		
		return $token;
	}
	
	function verify_email_token($token,$email) {
		//check if valid token
		$sql = "SELECT 1
						FROM user
						WHERE verification_token = ? AND email_address = ?";
		$q = $this->db->query($sql,array($token,$email));
		
		if($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function is_email_verified($email) {
		$sql = "SELECT email_verified
						FROM user
						WHERE email_address = ?
						LIMIT 1";
		$q = $this->db->query($sql,array($email));
		
		$r = false;
		
		if($q->num_rows() > 0) {
			$is_verified = $q->row()->email_verified;
			if((int)$is_verified === 1) {
				$r = true;
			} 
		}
		
		return $r;
	}
	
	function set_email_verified($token,$email) {
		$sql = "UPDATE user
						SET email_verified = 1
						WHERE verification_token = ? AND email_address = ?";
						
		$this->db->query($sql,array($token,$email));
		
		log_message("INFO","email is not verified for $email -> ".$this->db->last_query());
		
		$sql = "UPDATE user
						SET verification_token = NULL
						WHERE verification_token = ? AND email_address = ?";
						
		$this->db->query($sql,array($token,$email));
	}
	
	function create_password_reset_token($email) {
		$token = md5(time());
		
		log_message("INFO","setting reset token for email -> $email");
		
		$sql = "UPDATE user
						SET reset_token = ?
						WHERE email_address = ?
						LIMIT 1";
						
		$this->db->query($sql,array($token,$email));
		
		return $token;
	}
	
	function verify_reset_token($token,$email) {
		$sql = "SELECT 1
						FROM user
						WHERE reset_token = ? AND email_address = ?";
		$q = $this->db->query($sql,array($token,$email));
		
		if($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function reset_user_password($token,$email) {
		$new_password = time();
		$encryped_pass = md5($new_password);
		
		$sql = "UPDATE user
						SET password = ?
						WHERE reset_token = ? AND email_address = ?";
						
		$this->db->query($sql,array($encryped_pass,$token,$email));
		log_message("INFO","password reset for $email -> ".$this->db->last_query());
		
		$sql = "UPDATE user
						SET reset_token = NULL, prompt_change_pass = 1
						WHERE reset_token = ? AND email_address = ?";
		$this->db->query($sql,array($token,$email));
		
		return $new_password;
	}
	
	function set_new_password($password) {
		if($this->logged_in_check()) {
			$email_address = $this->session->userdata('email_address');
			
			$sql = "UPDATE user
							SET password = ?, prompt_change_pass = 0
							WHERE email_address = ?
							LIMIT 1";
			$this->db->query($sql,array(md5($password),$email_address));
		} 
	}
	
	function check_prompt_change_pass() {
		$r = '';
		if($this->logged_in_check()) {
			$email_address = $this->session->userdata('email_address');
			
			$sql = "SELECT prompt_change_pass
							FROM user
							WHERE email_address = ?
							LIMIT 1";
			$q = $this->db->query($sql,array($email_address));
			if($q->num_rows() > 0) {
				$prompt_flag = $q->row()->prompt_change_pass;
				if((int)$prompt_flag === 1) {
					$r = "You currently are using a temporary password! This is a security vulnerability, please change it <a href='/change_password' style='color:white;'>here</a>.";
				} 
			} 
		}
		return $r;
	}
}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
