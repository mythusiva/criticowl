<?
if(!isset($retention_modal_id)) {
	$retention_modal_id = 'retention_movie_landing_page';
}

$hide_modal = 'false';
$status = get_date_status($release_date);
$is_movie_released = $status === DATE_POSTRELEASE;
$current_url_id = md5(current_url());

$rentention_modal_history = $this->session->userdata('rentention_modal_history');
if(!$rentention_modal_history) {
	$rentention_modal_history = [];
}
if(isset($rentention_modal_history[$current_url_id])) {
	$hide_modal = 'true';
} else {
	$rentention_modal_history[$current_url_id] = TRUE;
	$this->session->set_userdata('rentention_modal_history', $rentention_modal_history);
}
// var_dump($rentention_modal_history); die();
?>

<style type="text/css">
.retention_image_container {
	text-align: center;
}
.retention_image_container img {
	width: 50%;
}
</style>

<!-- Button to trigger modal -->
<a id="<?=$retention_modal_id?>_btn" href="#<?=$retention_modal_id?>_retention_popup" role="button" class="btn btn-small" data-title="Stay a little while ..." data-toggle="modal" style="display:none;"></a>
 
<!-- Modal -->
<div id="<?=$retention_modal_id?>_retention_popup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="retentionModal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="retentionModal">Interested in this movie?</h3>
  </div>
  <div class="modal-body">


  	<!-- <p>
  		Did you like the article? 
  	</p> -->
  	<p>
  		Let us know if you are interested in this film! Our writers pay close attention to your feedback because we want to 
  		cover what you care about.
  	</p>
	<div class="retention_image_container">
		<!-- <img src="<?=resource_url()?>img/popcorn-52158_640.jpg" /> -->
	</div>

  </div>
  <div class="modal-footer">
    <button class="btn btn-default btn-small" data-dismiss="modal" aria-hidden="true" onclick="triggerInterested()"><i class="icon-ok"></i>  Interested</button>
    <?if($is_movie_released):?>
    <button class="btn btn-success btn-small" data-dismiss="modal" aria-hidden="true" onclick="triggerRating()"> Already Watched It</button>
    <?endif;?>
    <button class="btn btn-danger btn-small" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
</div>

<script>

	retention_movie_landing_page_modal_disabled = <?=$hide_modal?>;

	$(document).ready(function(){
		// window.onscroll = function(ev) {

	  	// };
		var timeout_amt = <?=$timeout_amount?>;

		window.setTimeout(function(){
			trigger_retention_popup();
		}, timeout_amt);

	});
	
	function trigger_retention_popup() {
		//noo don't leave! :(

		// console.log(retention_movie_landing_page_modal_disabled);
		if(retention_movie_landing_page_modal_disabled) {
			// console.log('skipping popup!');
			return;
		}
		retention_movie_landing_page_modal_disabled = "true";

		$('#<?=$retention_modal_id?>_btn').click();

		return false;
	}

	function triggerRating() {
		// scroll_to_id('disqus_thread');
		$('#watched_it_btn').click();
		$('.ratingModalBtn').click();
	}

	function triggerInterested() {
		// scroll_to_id('disqus_thread');
		$('#want_to_watch_it_btn').click();
	}

</script>