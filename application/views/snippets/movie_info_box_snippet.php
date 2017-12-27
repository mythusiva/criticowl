<style type="text/css">
@media only screen and (max-width: <?=VIEWPORT_CHANGE_SIZE?>px) {
	.media_info_box {
		/*padding: 5px;*/
	}

	.media-info-box-left {
		display: inline-block;
		width: 100%;
		padding: 10px 0px;
		border-bottom: 1px solid #cfcfcf;
		margin-bottom: 5px;
	}
	.media-info-box-right {
		display: inline-block;
		width: 100%;
		padding: 10px 0px;
		vertical-align: middle;
		text-align: center;
	}

	table {
		width: 100%;
	}

	.rating_result_box {
	}

	.subscribe_section {
		display: none;
	}
}
@media only screen and (min-width: <?=VIEWPORT_CHANGE_SIZE + 1?>px) {
	.media_info_box {
		/*padding: 5px;*/
	}
	
	.watch_stats_count_container {
	}
	
	.watch_stats_count_container td {
	}

	.watch_stats_btn_container {
	}
	.media-info-box-left {
		display: inline-block;
		width: 50%;
		vertical-align: middle;
		padding-top: 10px;
		float: left;
	}
	.media-info-box-right {
		display: inline-block;
		width: 45%;
		vertical-align: middle;
		text-align: right;
		padding-top: 20px;
		float: right;
	}

	table {		
		width: 100%;
	}

	.rating_result_box {
	}
}
</style>
<?
	$rating_overall = process_rating_variants($average_overall_rating);
	$rating_user = process_rating_variants($average_user_rating);
	$rating_criticowl = process_rating_variants($criticowl_rating);
	$status = get_date_status($release_date);
	$release_date = format_date($release_date,'F d, Y');
?>
<div class="media_info_box">
	<div class='title-large title-box'><?=$name?></div>
	<?if($status === DATE_POSTRELEASE):?>

		<div class="media-info-box-left">
				<div class="watch_stats_container" style="width: 100%; text-align: center;">
					<div class="stats_block" style="display: inline-block;
margin: 0px 5px;
width: 45%;
">
						<div class="status_block_number" style="border: 1px solid #E9E9E9;
padding: 15px 0px;
margin-bottom: 3px;
background: #FEFEFE;
font-size: 20px;"><?=($count_watched_it > 0) ? $count_watched_it : 0;?></div>
						<div><button id="watched_it_btn" class="btn btn-small" style="width:100%"><i class='icon-plus'></i> Watched it</button></div>
					</div>
					<div class="stats_block" style="display: inline-block;
margin: 0px 5px;
width: 45%;
">
						<div class="status_block_number" style="border: 1px solid #E9E9E9;
padding: 15px 0px;
margin-bottom: 3px;
background: #FEFEFE;
font-size: 20px;"><?=($count_want_to_watch_it > 0) ? $count_want_to_watch_it : 0;?></div>
						<div><button id="want_to_watch_it_btn" class="btn btn-small" style="width:100%"><i class='icon-plus'></i> Interested</button></div>
					</div>
				</div>
			</p>
			<p class="subscribe_section">
				For instant updates about <i><?=$name?></i>, make sure you subscribe. We will only send you an email only when there is a new update for this movie.
			</p>
			<p class="subscribe_section">
				<?=$this->load->view('snippets/subscribe_modal_snippet',array('media_fk'=>$media_pk,'media_title'=>$name));?>
			</p>
		</div>
		
		<div class="media-info-box-right">
			<div>
				<strong>Overall Rating</strong> - <?=get_rating_label($rating_overall['decimal'],MEDIA_MOVIE)?><br />
				<?=$this->load->view('snippets/rating_bar_snippet',array('rating'=>$rating_overall['numerator_of_10']),TRUE)?>
			</div>
			<div>
				<strong>CriticOwl Rating</strong> - <?=get_rating_label($rating_criticowl['decimal'],MEDIA_MOVIE)?><br />
				<?=$this->load->view('snippets/rating_bar_snippet',array('rating'=>$rating_criticowl['numerator_of_10']),TRUE)?>
			</div>
			<div>
				<strong>User Rating</strong> - <?=get_rating_label($rating_user['decimal'],MEDIA_MOVIE)?><br />
				<?=$this->load->view('snippets/rating_bar_snippet',array('rating'=>$rating_user['numerator_of_10']),TRUE)?>
			</div>
			<p>
				<?=$this->load->view('snippets/rating_modal_snippet',array('media_fk' => $media_pk,'media_type' => MEDIA_MOVIE),TRUE)?>
			</p>
		</div>
		<div style="clear:both;"></div>

	<?else:?>

		<div class="media-info-box-left">
			<table>
				<tr>
					<td style="width: 30%;">
							<div class="watch_stats_container" style="width: 100%; text-align: center;">
							<div class="stats_block" style="display: inline-block;
		">
								<div class="status_block_number" style="border: 1px solid #E9E9E9;
padding: 15px 0px;
margin-bottom: 3px;
background: #FEFEFE;
font-size: 20px;"><?=($count_want_to_watch_it > 0) ? $count_want_to_watch_it : 0;?></div>
								<div><button id="want_to_watch_it_btn" class="btn btn-small" style="width:100%"><i class='icon-plus'></i> Interested</button></div>
							</div>
						</div>
					</td>
					<td style="padding-left: 10px;">
						<?if($status === DATE_UNAVAILABLE):?>
							The release date of <i><?=$name?></i> is currently unknown or unofficial at the moment.
						<?else:?>
							<i><?=$name?></i> is set to be officially released in North America on <?=$release_date?>.
						<?endif;?>
					</td>
					
				</tr>
				
			</table>
		</div>

		<div class="media-info-box-right">
			<p>
				For instant updates about <i><?=$name?></i>, make sure you subscribe.
			</p>
			<p>
				<?=$this->load->view('snippets/subscribe_modal_snippet',array('media_fk'=>$media_pk,'media_title'=>$name));?>
			</p>
		</div>
		<div style="clear:both;"></div>

	<?endif;?>
</div>
<script type="application/x-javascript">
	$(document).ready(function() {
		<?if($status === DATE_POSTRELEASE):?>
		$('#watched_it_btn').click(function() {
			vote_watched_it();
		});
		<?endif;?>
		$('#want_to_watch_it_btn').click(function() {
			vote_want_to_watch_it();
		});
	});
	<?if($status === DATE_POSTRELEASE):?>
	function vote_watched_it() {
		$.ajax({
			type: "POST",
			url: '/vote/vote_watched_it',
			data: {media_fk:'<?=$media_pk?>'},
			success: function(data) {
				global_show_notification('Thanks for voting! All votes are compiled and validated every few minutes.');
			}
		});
	}
	function vote_rating(rating_amt) {
		$.ajax({
			type: "POST",
			url: '/vote/vote_rating',
			data: {media_fk:'<?=$media_pk?>',rating:rating_amt},
			success: function(data) {
				global_show_notification('Thanks for voting! All votes are compiled and validated every few minutes.');
			}
		});
	}
	<?endif?>
	function vote_want_to_watch_it() {
		$.ajax({
			type: "POST",
			url: '/vote/vote_want_to_watch_it',
			data: {media_fk:'<?=$media_pk?>'},
			success: function(data) {
				global_show_notification('Thanks for voting! All votes are compiled and validated every few minutes.');
			}
		});
	}
</script>