<style type="text/css">
.search_result_siwi_card {
	text-align: center;
	background-color: #FFF6E2;
}
.search_result_siwi_card .siwi_card_title {
	font-weight: bold;
	padding-bottom: 5px;
}
.search_result_siwi_card .middle-section .rating-label {

}
</style>
<?
	$rating = process_rating_variants($rating_decimal);
	list($watchit_label,$rating_desc) = get_should_i_watch_it_label($rating['numerator_of_100']);
?>
<div class="search_result_card search_result_siwi_card">
	<div class="siwi_card_title"><a href='<?=$movie_landing_page_url?>'><?=$title?></a></div>
	<div class="middle-section">
		<div class='rating-label'><small><strong>CriticOwl says:</strong> &ldquo;<?=$watchit_label?>&rdquo;</small></div>
		<div class='rating-bar'>
			<?=$this->load->view('snippets/rating_bar_snippet',array('rating'=>$rating['numerator_of_10']),TRUE)?>
		</div>
	</div>
	<div class='review-link'>
		<a href='<?=$review_url?>' class='btn btn-small btn-danger'>Read Our Review</a>
	</div>
</div>
<script type="application/x-javascript">
</script>