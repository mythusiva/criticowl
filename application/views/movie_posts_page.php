<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$media['name']?> | Reviews | Articles | <?=$this->config->item('window_title')?></title>
    <meta name="description" content="<?=$media['name']?> ">
    <?include "header_essentials.php"?>
</head>
<style type="text/css">
@media only screen and (max-width: <?=VIEWPORT_CHANGE_SIZE?>px) {
	.left_pane {
		font-size: 16px;
	}
	.right_pane {
		font-size: 16px;
	}
	.movies_list {
		text-align: center;
		font-size: 13px;
	}
	.updates-placeholder {
		color: #AAAAAA;
		font-size: 22px;
		padding: 35px;
	}
}
@media only screen and (min-width: <?=VIEWPORT_CHANGE_SIZE + 1?>px) {
	.left_pane {
		float: left;
		width: 65%;
		font-size: 16px;
	}
	.right_pane {
		float: right;
		width: 30%;
		font-size: 16px;
	}
	.movies_list {
		text-align: center;
		font-size: 13px;
	}
	.updates-placeholder {
		color: #AAAAAA;
		font-size: 22px;
		padding: 35px;
	}
}
</style>
<?
//pagination config
$id_movie_posts_list = 'movie_posts_list'; 
?>
<body>
	<h1 style="display:none;"><?=$media['name']?></h1>
	<?$config['access_level'] = 'USER';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'movies'))?>
	<div class="main_body">
		<div class="main_content">
			<div id="notification_block">
				<?if(!empty($notification)):?>
				<div id="notifications" class="notification_area alert alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<?=$notification?>
				</div>
				<?endif;?>
			</div>
			<div class="content_wrapper">

				<?=$this->load->view('snippets/trending_links_snippet',array(),TRUE)?>

				<div class="right_pane">
					<div>
						<?=$this->load->view('snippets/movies_list_snippet',array('media_list'=>$media_list))?>			
					</div>
				</div>

				<div class="left_pane">
					<div>
						<?=$this->load->view('snippets/movie_info_box_snippet',$media_info_box,TRUE)?>
					</div>
					<?if(!empty($articles)):?>
					<div id="<?=$id_movie_posts_list?>_container" class='review_block'>
						<?foreach($articles as $a):?>
							<?if($a['article_type'] === POST_ARTICLE):?>
								<?=$this->load->view('snippets/preview_article_content_snippet',$a,TRUE);?>
							<?elseif($a['article_type'] === POST_REVIEW):?>
								<?=$this->load->view('snippets/preview_review_content_snippet',$a,TRUE);?>
							<?endif;?>
						<?endforeach;?>
					</div>
					<?else:?>
					<div class='updates-placeholder'>
						<p>
							Hmmm, it looks like are writers have been distracted by TV, greasy potato chips and fizzy soda again ...
						</p>
						<br />
						<p>
							By clicking the "Interested" button, you can help keep our writers focused.
						</p>
					</div>
					<?endif;?>					
				</div>
				<?=$this->load->view('snippets/retention_modal_movie_landing_page',array('release_date'=>$media_info_box['release_date'],'timeout_amount'=>1000));?>
				
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>

</html>