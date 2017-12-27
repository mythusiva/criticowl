<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Terms & Conditions | <?=$this->config->item('window_title')?></title>
    <meta name="description" content="CriticOwl's terms and conditions.">
    <meta name="keywords" content="terms,conditions,legal,contract,usage">
    <meta property="og:image" content="<?=get_compressed_thumbnail_url(base_url().'img/icon_logo_red_512x512.png')?>" />
    <meta property="og:title" content="Terms & Conditions | <?=$this->config->item('window_title')?>" />
    <meta property="og:description" content="CriticOwl's terms and conditions." />
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
					<h1>Terms and Conditions</h1>
					<p>
						<strong>Acceptable Content</strong><br/><br/>
						Commenting under any published media by CriticOwl is for the sole purpose of audience-writer or audience-audience interaction. Any offensive, racial or defamatory terms used by the commenter are not to be posted and will be flagged/removed.
					</p>
					<p>
						<strong>Acceptable Use</strong><br/><br/>
						All visitors are encouraged to engage in discussion on pages via the communication tools provided on CriticOwl.com. However, any interactions which include harassing other commenters, promoting content which violates CriticOwl policies, or the exchange of illegal material via our website are strictly prohibited and will not be tolerated. 
					</p>
					<p>
						Copyrights & Trademarks. The trademarks, names, logos and service marks (collectively “trademarks”) displayed on this website are registered and unregistered trademarks of the website owner. Nothing contained on this website should be construed as granting any license or right to use any trademark without the prior written permission of the website owner. The written content displayed on this website is owned by its respective author and may not be reproduced in whole, or in part, without the express written permission of the author.
					</p>
					<p>
						Modification. CriticOwl reserves the right to revise the terms and conditions of this Agreement at any time. Any such revision will be binding and effective immediately upon posting of the revised Agreement on our web site. Your continued use of our site constitutes agreement to any revision of the terms and conditions of this Agreement.
					</p>
					<p>
						All images, posters, cover arts, etc., are registered trademarks and copyrights which belong to their respective holder. Images are used to identify products which are discussed through critical analysis and commentary. All images are of low-resolution and are scaled down. 
					</p>
				</div>
				
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>
</html>
