<?
	$id = 'rating_modal_'.$media_fk.'_'.time().'_';
	$this->load->model('media_model');
	$rating_data = $this->media_model->get_rating_system_for_media($media_type);
	
	$star_count = count($rating_data);
	
	$rating_labels = $rating_values = array();
	
	foreach($rating_data as $row) {
		$numerator = $row['value'] * 10;
		$denominator = 10;
		$rating_labels[] = "<strong><sup>{$numerator}</sup>&frasl;<sub>{$denominator}</sub></strong> {$row['label']}";
		$rating_values[] = $row['value'];
	}
?>
<style type="text/css">
.rating {
	text-align: center;
}
#<?=$id?>rating_label {
	padding: 0px 10px 20px;
	font-size: 24px;
	height: 10px;
}

</style>
<!-- Button to trigger modal -->
<a href="#<?=$id?>rating_modal" role="button" class="btn btn-small ratingModalBtn" data-toggle="modal"><i class='icon-tasks'></i> Add Your Rating</a>
 
<!-- Modal -->
<div id="<?=$id?>rating_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="<?=$id?>rating_modalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="<?=$id?>rating_modalLabel">What's your Rating ?</h3>
  </div>
  <div class="modal-body">
    <p>
			<div class="rating_holder">
				<div class="rating">
					<div id="<?=$id?>rating_label"></div>
					<div id="<?=$id?>star"></div>
				</div>
				
			</div>
		</p>
  </div>
  <div class="modal-footer">
	<input id="<?=$id?>rating_modalScore" value="" type="hidden" />
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button id="<?=$id?>rating_modalSave" class="btn btn-primary">Save changes</button>
  </div>
</div>
<script type="application/x-javascript">
	$(document).ready(function() {
		var score_lookup = <?=json_encode($rating_values)?>;
		var score_hints = <?=json_encode($rating_labels)?>;
		$('#<?=$id?>star').raty({
			hints: score_hints,
			starOff: '/js/raty/img/criticowl_star-off.png',
			starOn : '/js/raty/img/criticowl_star-on.png',
			width: 'auto',
			target: '#<?=$id?>rating_label',
			number: <?=$star_count?>,
			targetKeep: true,
			click: function(score, evt) {
				$('#<?=$id?>rating_modalScore').val(score_lookup[score-1]);
			}
		});
		$('#<?=$id?>rating_modalSave').click(function() {
			var rating_amt = $("#<?=$id?>rating_modalScore").val();
			vote_rating(rating_amt);
			$('#<?=$id?>rating_modal').modal('hide');
			window.location = "#";
		});
		$('#<?=$id?>star img').each(function() {
			var text_val = $(this).attr('title');
			$(this).attr('title',$(text_val).text());
		});
	});
	
</script>