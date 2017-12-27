<?
if(!isset($retention_modal_id)) {
	$retention_modal_id = 'retention_article_page';
}
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
    <h3 id="retentionModal">What did you think?</h3>
  </div>
  <div class="modal-body">


  	<!-- <p>
  		Did you like the article? 
  	</p> -->
  	<p>
  		Be heard, spark a discussion, or ask other readers about the topic!
  	</p>
	<div class="retention_image_container">
		<img src="<?=resource_url()?>img/comments-97860_640.png" />
	</div>

  </div>
  <div class="modal-footer">
    <button class="btn btn-success btn-small" data-dismiss="modal" aria-hidden="true" onclick="leaveAComment()"><i class="icon-comment icon-white"></i> Comment</button>
    <?if(!empty($movie_page_url)):?>
    <button class="btn btn-default btn-small" data-dismiss="modal" aria-hidden="true" onclick="window.location='<?=$movie_page_url?>'"><i class="icon-film"></i> Movie Homepage</button>
    <?endif;?>
    <button class="btn btn-danger btn-small" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
</div>

<script>

	var retention_article_page_modal_disabled = false;
	var timeout_amt = <?=$timeout_amount?>;

	$(document).ready(function(){
		// window.onscroll = function(ev) {

	  	// };

		// window.setTimeout(function(){
			// trigger_retention_popup();
		// }, timeout_amt);

	});
	
	function trigger_retention_popup() {
		//noo don't leave! :(

		if(retention_article_page_modal_disabled) {
			return;
		}
		retention_article_page_modal_disabled = true;

		$('#<?=$retention_modal_id?>_btn').click();

		return false;
	}

	function leaveAComment() {
		scroll_to_id('disqus_thread');
	}

	function retention_modal_article_page_check() {
		if(!retention_article_page_modal_disabled && $('#bottomOfArticle:in-viewport').length > 0) {
			console.log('bottom reached!');
			window.setTimeout(function(){
				trigger_retention_popup();
			}, timeout_amt);
		}
	}

</script>