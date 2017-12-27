<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>About Us | <?=$this->config->item('window_title')?></title>
    <meta name="description" content="<?=$this->config->item('motto_description_text')?>">
    <meta name="keywords" content="about,us,should,watch,movie,decide,rating">
    <meta property="og:image" content="<?=get_compressed_thumbnail_url(base_url().'img/icon_logo_red_512x512.png')?>" />
    <meta property="og:title" content="About Us | <?=$this->config->item('window_title')?>" />
    <meta property="og:description" content="<?=$this->config->item('motto_description_text')?>" />
    <meta property="og:url" content="<?=base_url()?>" />
    <meta property="og:site_name" content="<?=$this->config->item('logo_text')?>"/>
    <?include "header_essentials.php"?>
</head>
<style type="text/css">
.left_pane {
	float: left;
	width: 65%;
	font-size: 16px;
}
.right_pane {
	float: right;
	width: 32%;
	font-size: 16px;
}
.movies_list {
	text-align: right;
}
.review_block {
	display: block;
	position: relative;
}
.review {

}

.review-right {
	padding: 0px 5px;
}
.review-left {
	min-width: 200px;
}

.sub-table {
	text-align: center;
	font-size: 14px;
}

.sub-table tr:nth-of-type(odd) {
  background-color:#F4EDE8;
}
.sub-table tr:nth-of-type(even) {
  background-color:#FFEFE5;
}
</style>
<?
//pagination config
$id_recent_posts = 'recent_posts'; 
?>
<body>
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
				
				<div>
					<h1>About Us</h1>
					<br/>
					<p>
						CriticOwl aspires to be the go-to website when you’re deciding on which movie to watch.
					</p>
					<br/>
					<p>
						Let’s face it: some movies leave you thinking about how much time you’ve wasted. With a simple rating system, CriticOwl turns meaningless numbers to plain English. We also pride ourselves in having reviews that are to-the-point. Simplicity is our virtue, as we value your time.
					</p>
					<br/>
					<p>
						CriticOwl was built on a special foundation. It all started with a group of friends, where one friend always showed passion for cinema and the movie industry. We&#8212;the friends&#8212;came to realize that his opinions would be useful to others. So, early in 2013, we decided to make ourselves public by launching this movie review site. 
					</p>
					<br/>
					<p>
						As the co-founders of CriticOwl.com, our reviews are genuine and not influenced by any sponsors. We are real people who go to the movies just like you. We compile the good and the bad for each and every movie in the simplest way we can. From action to romance, comedy to horror, indie to mainstream, we cover them all.
					</p>
					<br/>
					<p>
						Whether you’re a hardcore cinephile or a casual movie-goer, you’ve come to the right place.<br/><br/>
						<strong>Should you watch it? We'll let you know.</strong>
					</p>
					<br/><br/><br/>
				</div>
				
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>
</html>
