<style type="text/css">
@media only screen and (max-width: <?=VIEWPORT_CHANGE_SIZE?>px) {
	.results-box {
		margin: 10px auto;
		background-color: #FFEECA;
		font-weight: bold;
		color: #6D6D6D;
		box-shadow: 0px 5px 10px #494949;
	}
	.results-box .rating {
		text-align: center;
	}
	.results-box .rating .title {
		background-color: #646464;
		color: #FFFFFF;
		padding: 25px 0px 20px 0px;
		margin: 20px 0px 0px 0px;
		font-size: 30px;
		line-height: 32px;
		font-weight: bold;
	}
	.rating-bar {
		width: 100%;
		margin: 0 auto;
		padding: 0px;
		background-color: #B7B7B7;
	}
	.rating-desc {
		width: 90%;
		padding: 10px;
		margin: 0 auto;
		font-size: 16px;
	}
	.movie-title {
		font-size: 20px;
		font-weight: bold;
		padding: 10px 0px;
		color: #FAFAFA;
	}
	.our-review-bar {
		text-align: center;
		padding: 5px 0px;
	}
	.middle-section {
		background: #646464;
	}
}
@media only screen and (min-width: <?=VIEWPORT_CHANGE_SIZE + 1?>px) {
	.results-box {
		width: 800px;
		margin: 10px auto;
		background-color: #FFEECA;
		font-weight: bold;
		color: #6D6D6D;
		box-shadow: 0px 5px 10px #494949;
	}
	.results-box .rating {
		text-align: center;
	}
	.results-box .rating .title {
		background-color: #646464;
		color: #FFFFFF;
		padding: 25px 0px 20px 0px;
		margin: 20px 0px 0px 0px;
		font-size: 36px;
		font-weight: bold;
	}
	.rating-bar {
		width: 100%;
		margin: 0 auto;
		padding: 0px;
		background-color: #B7B7B7;
	}
	.rating-desc {
		width: 90%;
		padding: 10px;
		margin: 0 auto;
		font-size: 16px;
	}
	.movie-title {
		font-size: 25px;
		font-weight: bold;
		padding: 10px 0px;
		color: #FAFAFA;
	}
	.our-review-bar {
		text-align: center;
		padding: 5px 0px;
	}
	.middle-section {
		background: #646464;
	}
}
</style>
<?
	$rating = process_rating_variants($rating_decimal);
	list($watchit_label,$rating_desc) = get_should_i_watch_it_label($rating['numerator_of_100']);
?>
<div class="results-box">
	<div class='rating'>
		<div class="title"><?=$rating_title?></div>

		<div class="middle-section">
			<div class='movie-title'>CriticOwl Says : <?=$watchit_label?></div>
			<div class='rating-bar'>
				<?=$this->load->view('snippets/rating_bar_snippet',array('rating'=>$rating['numerator_of_10']),TRUE)?>
			</div>
		</div>
		
		<div class='rating-desc'>
			<?=$rating_desc?>
		</div>
	</div>
	<?if(!empty($review_url)):?>
	<div class='our-review-bar'>
		<a href='<?=$review_url?>' class='btn btn-small btn-danger'>Read Our Review</a>
	</div>
	<?endif;?>
</div>