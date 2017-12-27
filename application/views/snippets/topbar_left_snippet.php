<style type="text/css">
</style>
<?
//get essentials
$CI = &get_instance();
$CI->load->model('user_model');
$topbar_user_data = $CI->user_model->get_user_data();
?>
<span class="topbar-name">
	<?=format_display_name($topbar_user_data['preferred_name'],
												 $topbar_user_data['first_name'],
												 $topbar_user_data['last_name'])?>
</span>
