<?php

function get_permissions() {
	$ci = &get_instance();
	$ci->load->model('user_model');
	return $ci->user_model->get_permission_level_for_current_user();
}