<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Updates and Interesting Topics | Trailer Talks | Articles | <?=$this->config->item('window_title')?></title>
    <meta name="description" content="Interesting articles from our independant writers. Predictions and thoughts about current movie trends.">
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
}
@media only screen and (min-width: <?=VIEWPORT_CHANGE_SIZE + 1?>px) {
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
}
</style>
<body>
	<h1 style="display:none">Movie Updates and Interesting Topics</h1>
	<?$config['access_level'] = 'USER';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'articles'))?>
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
						<?=$this->load->view('snippets/search_bar_snippet')?>
					</div>
					<div>
						<?=$this->load->view('snippets/movies_list_snippet',array('media_list'=>$media_list))?>
					</div>
				</div>

				<div class="left_pane">
					<div>
						<?
							//$slideshow_viewlet['identifier'] = 'movies_page_main';
						?>
						<?//=$this->load->view('snippets/ranking_slideshow_snippet',$slideshow_viewlet,TRUE)?>
					</div>
					<div id="articles_list_container">
						<div class="title-medium title-box">Articles</div>
						<div class="content">
							<?foreach($articles as $a):?>
								<?=$this->load->view('snippets/preview_article_content_snippet',$a,TRUE)?>
							<?endforeach;?>
						</div>
					</div>
					<div class="show_more_button_container">
						<a class="show_more_btn btn btn-medium auto_load_feed" 
							data-update-selector="#articles_list_container .content" 
							data-pagination-id="<?=SHOW_MORE_ARTICLES_LIST?>" 
							data-content-type="<?=POST_ARTICLE?>">Show More</a>
					</div>
				</div>
				
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>
</html>

<script type="text/javascript">
	
</script>
