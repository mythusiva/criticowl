<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$data['title']?> | <?=$this->config->item('window_title')?></title>
    <meta name="description" content="<?=$data['preview_text']?>">
    <meta property="og:image" content="<?=get_compressed_thumbnail_url($data['image_link'])?>" />
    <meta property="og:title" content="<?=$data['title']?>" />
    <meta property="og:description" content="<?=$data['preview_text']?>" />
	<?$article_url = base_url().get_uri_prefix_by_article_type($data['article_type']).$data['uri_segment'];?>
    <meta property="og:url" content="<?=$article_url?>" />
    <meta property="og:site_name" content="<?=$this->config->item('logo_text')?>"/>
    <?include "header_essentials.php"?>
</head>
<body>
	<h1 style="display:none"><?=$data['title']?></h1>
	<?$config['access_level'] = 'USER';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'home'))?>
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
				
				<?
					$data['url'] = "";
				?>
				<?if($data['media_type'] !== MEDIA_NONE):?>
					<?
						$data['url'] = '/'.get_uri_prefix_by_media_type($data['media_type']).$data['media_uri_segment'];
					?>
					<div>
						<a class='btn btn-lg' href="<?=$data['url']?>"> <i class="icon-film"></i> <strong>Movie landing page: <?=$data['media_title']?></strong></a>
					</div>
				<?endif;?>
				<div class="left_content_pane">
					<div id="articleContents">
						<?if($data['article_type'] === POST_ARTICLE):?>
							<?=$this->load->view('snippets/article_content_snippet',$data,TRUE);?>
						<?elseif($data['article_type'] === POST_REVIEW):?>
							<?=$this->load->view('snippets/review_content_snippet',$data,TRUE);?>
						<?endif;?>
					</div>
					<div id="bottomOfArticle">
						<?=$this->load->view('snippets/retention_modal_article_page',array('retention_modal_id'=>'end_article','timeout_amount'=>15000,'movie_page_url'=>$data['url']),TRUE);?>
					</div>
					<?
						$disqus_data = array(
							'disqus_title' => $data['title'],						 
						);
						
						echo $this->load->view('snippets/disqus_comments_snippet',$disqus_data,TRUE);
					?>
				</div>
				<div class="right_content_pane">
					<?foreach ($related_pages as $page_data):?>
						<?if(isset($shown_articles[$page_data['article_pk']])){
							continue;
						}?>
						<div class="related-pages-card">	
							<div class="related-pages-header">
								<a href="/<?=get_uri_prefix_by_article_type($page_data['article_type']).$page_data['uri_segment']?>"><?=$page_data['title']?></a>
							</div>
							<div class="related-pages-image">
								<a href="/<?=get_uri_prefix_by_article_type($page_data['article_type']).$page_data['uri_segment']?>">
									<img src="<?=get_compressed_image_url($page_data['image_link'])?>" width="100%" />
								</a>
							</div>
							<div class="related-pages-body">
								<p><?=$page_data['preview_text']?></p>
							</div>
						</div>
						<?$shown_articles[$page_data['article_pk']] = 1;?>
					<?endforeach;?>
				</div>
				
				<div style="clear: both;"></div>
				
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>

</html>

<script>

</script>
