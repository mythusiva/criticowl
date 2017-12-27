<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"> 
<?$criticowl_version = $this->config->item('criticowl_version');?>

<link rel="shortcut icon" type="image/x-icon" href="<?=resource_url()?>img/favicon_logo_red.ico?v=<?=$criticowl_version?>">
<link rel="apple-touch-icon" href="<?=resource_url()?>img/apple-touch-icon.png"/>
<link rel="apple-touch-icon-precomposed" href="<?=resource_url()?>img/apple-touch-icon.png"/>

<link 	type="text/css" 	href="<?=base_url()?>css/fonts.css?v=<?=$criticowl_version?>" 		rel="Stylesheet" />

<?
$desktop_combined_css = get_combined_css('desktop_compressed.css',array(
	resource_url().'/css/button_styles.css',
	resource_url().'/css/bootstrap.css',
	resource_url().'/css/global.css',
	resource_url().'/css/preview_review.css',
	resource_url().'/css/preview_article.css',
	resource_url().'/js/owl-carousel/owl.carousel.css',
	resource_url().'/js/owl-carousel/owl.theme.css',
	resource_url().'/css/datepicker.css',
	resource_url().'/css/homepage.css',
	resource_url().'/css/page_article.css',
	resource_url().'/css/page_review.css',
	resource_url().'/css/landing_page.css',
));
$mobile_combined_css = get_combined_css('mobile_compressed.css',array(
	resource_url().'/css/button_styles.css?v='.$criticowl_version,
	resource_url().'/css/bootstrap.css?v='.$criticowl_version,
	resource_url().'/css/global_mobile.css?v='.$criticowl_version,
	resource_url().'/css/preview_review_mobile.css?v='.$criticowl_version,
	resource_url().'/css/preview_article_mobile.css?v='.$criticowl_version,
	resource_url().'/js/owl-carousel/owl.carousel.css?v='.$criticowl_version,
	resource_url().'/js/owl-carousel/owl.theme.css?v='.$criticowl_version,
	resource_url().'/css/datepicker.css?v='.$criticowl_version,
	resource_url().'/css/homepage_mobile.css?v='.$criticowl_version,
	resource_url().'/css/page_article_mobile.css?v='.$criticowl_version,
	resource_url().'/css/page_review_mobile.css?v='.$criticowl_version,
	resource_url().'/css/landing_page_mobile.css?v='.$criticowl_version,
));
$combined_js = get_combined_js('compressed.js',array(
	'http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js',
	'http://cdnjs.cloudflare.com/ajax/libs/pace/0.4.17/pace.js',
	'http://cdn.jsdelivr.net/jquery.marquee/1.3.1/jquery.marquee.min.js',
	// 'https://cdnjs.cloudflare.com/ajax/libs/Snowstorm/20131208/snowstorm-min.js',
	resource_url().'/js/bootstrap.min.js?v='.$criticowl_version,
	resource_url().'/js/bootstrap-datepicker.js?v='.$criticowl_version,
	resource_url().'/js/raty/jquery.raty.min.js?v='.$criticowl_version,
	resource_url().'/js/owl-carousel/owl.carousel.js?v='.$criticowl_version,
	resource_url().'/js/global.js?v='.$criticowl_version,
	resource_url().'/js/pagination.js?v='.$criticowl_version,
	resource_url().'/js/jquery.viewport.js?v='.$criticowl_version,
	'https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0-beta.11/angular.js'
));
?>

<link type="text/css" href="<?=rtrim(resource_url(),'/').$desktop_combined_css?>?v=<?=$criticowl_version?>" media="only screen and (min-width: <?=VIEWPORT_CHANGE_SIZE + 1?>px)"  rel="stylesheet">
<link type="text/css" href="<?=rtrim(resource_url(),'/').$mobile_combined_css?>?v=<?=$criticowl_version?>" media="only screen and (max-width: <?=VIEWPORT_CHANGE_SIZE?>px)"  rel="stylesheet">
<script type="text/javascript"  src="<?=rtrim(resource_url(),'/').$combined_js?>?v=<?=$criticowl_version?>"></script>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5234d0e04d6d9a7b" async="async"></script>

<?
$today = (int)date('z');
if($today > 340 || $today < 3):?>
<!-- SEASONAL CODE: Snow!!! :D -->
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/Snowstorm/20131208/snowstorm-min.js"></script>
<script type="text/javascript">
	snowStorm.snowColor = '#efefef';  
	snowStorm.flakesMaxActive = 25;    // show more snow on screen at once
	snowStorm.followMouse = false; 
	snowStorm.vMaxX = 1;
	snowStorm.vMaxY = 2;
	snowStorm.snowCharacter = '<span style="text-shadow: 2px 2px 5px #646464;">•</span>';
</script>
<!-- ===== -->
<?endif;?>

<!-- admin loads -->
<?if($this->config->item('is_admin')):?>
	<script type="text/javascript"  src="<?=resource_url()?>js/ckeditor/ckeditor.js?v=<?=$criticowl_version?>"></script>
<?endif;?>

<?if($this->config->item('is_admin') === FALSE):?>
	<link rel="alternate" href="/rss.xml" title="CriticOwl Updates" type="application/rss+xml" />
	<?=$this->load->view('snippets/google_analytics_snippet',TRUE);?>
<?endif;?>