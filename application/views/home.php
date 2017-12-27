<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?$site_title_text = $this->config->item('motto_text')." | ".$this->config->item('window_title')." | Movies";?>
    <title><?=$site_title_text?></title>
    <meta name="description" content="<?=$this->config->item('motto_description_text')?>">
    <meta name="keywords" content="available,theaters,locate,dvd,blu-ray,movie,review,trailer,approved,recent,criticowl,should i watch it,playing">
    <meta property="og:image" content="<?=get_compressed_thumbnail_url(base_url().'img/icon_logo_red_512x512.png')?>" />
    <meta property="og:title" content="<?=$site_title_text?>" />
    <meta property="og:description" content="<?=$this->config->item('motto_description_text')?>" />
    <meta property="og:url" content="<?=base_url()?>" />
    <meta property="og:site_name" content="<?=$this->config->item('logo_text')?>"/>
    <?include "header_essentials.php"?>
</head>
<?
//pagination config
$id_recent_posts = 'recent_posts'; 
$label_cell_width = "25%";
$action_cell_width = "25%";
?>
<body>
	<?$config['access_level'] = 'USER';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'home'))?>
	<h1 style="display:none"><?=$site_title_text?></h1>
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
				
				<div class="left_pane">
					<div class="homepage_slideshow">
						<?
							$slideshow_viewlet['identifier'] = 'movies_page_main';
							$slideshow_viewlet['num_slides'] = 4;
						?>
						<?=$this->load->view('snippets/ranking_slideshow_snippet',$slideshow_viewlet,TRUE)?>
					</div>

					<?if(!empty($latest_exclusive_articles)):?>
					<div class="exclusive_articles_main_container" style="margin-bottom: 10px;">
						<div class="title-medium title-box">Top Exclusive Articles</div>
						<div class='review_block'>
							<?foreach($latest_exclusive_articles as $la):?>
								<?=$this->load->view('snippets/preview_article_content_snippet',$la,TRUE)?>
							<?endforeach;?>
						</div>
					</div>
					<?endif;?>
					
					<div class="reviews_and_articles_main_container">
						<div class="title-medium title-box">Recent Reviews & Articles</div>
						<div id="home_reviews_and_articles_container" class='reviews_and_articles_main_container review_block'>
							<?foreach($articles as $a):?>
								<?if($a['article_type'] === POST_ARTICLE):?>
									<?=$this->load->view('snippets/preview_article_content_snippet',$a,TRUE);?>
								<?elseif($a['article_type'] === POST_REVIEW):?>
									<?=$this->load->view('snippets/preview_review_content_snippet',$a,TRUE);?>
								<?endif;?>
							<?endforeach;?>
						</div>
						<div class="show_more_button_container">
							<a class="show_more_btn btn btn-medium auto_load_feed" 
								data-update-selector="#home_reviews_and_articles_container" 
								data-pagination-id="<?=SHOW_MORE_ALL_LIST?>" 
								data-content-type="<?=POST_ALL?>">Show More</a>
						</div>
					</div>
					
				</div>

				<div class="right_pane">
					<div id="search_bar_container_homepage" class="search_bar_container">
						<?=$this->load->view('snippets/search_bar_snippet')?>
					</div>

					<div class="upcoming_releases_list">
						<div class="title-medium title-box">Movies Currently In Theatres</div>
						<div class="content">
							<table class='sub-table' cellpadding=5px>
								<thead>
								</thead>
								<tbody>
									
									<?foreach($upcoming_media['now_playing_list'] as $m):?>
										<?$url = '/'.get_uri_prefix_by_media_type($m['type']).$m['uri_segment'];?>
										<tr style="width: 100%;">
											<td style="width: <?=$label_cell_width?>; text-align: left;">
												<?if(is_worth_watching($m['criticowl_rating'])):?>
													<span class="label label-success" title="Recommended by us."><i class="icon-white icon-thumbs-up"></i> Watch It</span>
												<?else:?>
													<span class="label label-important" title="We don't recommend it."><i class="icon-white icon-thumbs-down"></i> Skip It</span>
												<?endif;?>
											</td>
											<td>
												<a href="<?=$url?>"><?=$m['name']?></a>
												<?unset($url);?>
											</td>
											<td style="width: <?=$action_cell_width?>; text-align: right;">
												<a target="_blank" class="btn btn-small" href="http://www.google.com/movies?view=map&q=<?=urlencode($m['name'])?>"> <i class="icon-map-marker"></i> Find</a>
											</td>
										</tr>
									<?endforeach;?>

								</tbody>
							</table>
						</div>
					</div>

					<div class="upcoming_releases_list">
						<div class="title-medium title-box">Movies Available On DVD/BluRay</div>
						<div class="content">
							<table class='sub-table' cellpadding=5px>
								<thead>
								</thead>
								<tbody>

									<?foreach($upcoming_media['dvd_bluray_list'] as $m):?>
										<?$url = '/'.get_uri_prefix_by_media_type($m['type']).$m['uri_segment'];?>
										<tr style="width: 100%;">
											<td style="width: <?=$label_cell_width?>; text-align: left;">
												<?if(is_worth_watching($m['criticowl_rating'])):?>
													<span class="label label-success" title="Recommended by us."><i class="icon-white icon-thumbs-up"></i> Worth It</span>
												<?else:?>
													<span class="label label-important" title="We don't recommend it."><i class="icon-white icon-thumbs-down"></i> Not Worth It</span>
												<?endif;?>
											</td>
											<td>
												<a href="<?=$url?>"><?=$m['name']?></a>
												<?unset($url);?>
											</td>
											<td style="width: <?=$action_cell_width?>; text-align: right;">
												<?=$this->load->view('snippets/amazon_get_it_modal_snippet',array('title'=>$m['name']));?>
											</td>
										</tr>
									<?endforeach;?>

								</tbody>
							</table>
						</div>
					</div>

					<div class="upcoming_releases_list">
						<div class="title-medium title-box">Coming To A Theatre Near You</div>
						<div class="content">
							<table class='sub-table' cellpadding=5px>
								<thead>
								</thead>
								<tbody>
									

									<?foreach($upcoming_media['upcoming_list'] as $m):?>
										<?$url = '/'.get_uri_prefix_by_media_type($m['type']).$m['uri_segment'];?>
										<tr style="width: 100%;">
											<td style="width: <?=$label_cell_width?>; text-align: left;">
												<span class="label label-inverse"><?=get_relative_days_left((int)$m['days_left'])?></span>
											</td>
											<td>
												<a href="<?=$url?>"><?=$m['name']?></a>
												<?unset($url);?>
											</td>
											<td style="width: <?=$action_cell_width?>; text-align: right;">
												
											</td>
										</tr>
									<?endforeach;?>

								</tbody>
							</table>
						</div>
					</div>
					
					<?if(!empty($twitter_ids)):?>
					<div class="recent_tweets_container">
						<div class="title-medium title-box">Ramblings of the Owl</div>
						<div>
							<?foreach($twitter_ids as $id):?>
							<div>
								<blockquote class="twitter-tweet" lang="en">
									<a href="https://twitter.com/CriticOwl/statuses/<?=$id?>"></a>
								</blockquote>
								<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
							</div>
							<?endforeach;?>
						</div>
					</div>
					<?endif;?>

				</div>

				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>

</html>