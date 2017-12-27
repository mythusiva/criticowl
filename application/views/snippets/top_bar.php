<style type="text/css">
.logo_icon {
	position: absolute;
	z-index: 10;
	top: 5px;
	left: 5px;		
	width: 100px;
}
.topbar-menu-links-area {
	display: inline-block;
	line-height: 30px;
}

.page_top_panel {
	/*background: url("/img/logo_wide.png") no-repeat top;
	min-height: 300px;*/
}

.fixed_menu {
	position: fixed;
	top: 0;	
	left: 0;
	right: 0;
	background-color: #232323;
	z-index: 10;
	padding: 10px 10px 5px 10px;
	font-size: 20px;
	border-bottom: 3px solid #A33D47;
}

</style>
<?
//format for config
/*
$config['access_level'] = 'USER'; //or ADMIN
$selected = a string in $config
*/
?>
<div class="top_panel_bkg">
	<div id="top_bar" class="topbar">
		<div onclick="window.location='/';" class="pointer topbar-left">
			<div style="display: inline-block;">
				<img src="<?=resource_url()?>img/icon_logo_red_64x64.png" width='64' class="main_logo_image" style="" />
			</div>
			<div style="display: inline-block;vertical-align: bottom;padding: 0px 5px;">
				<div class="text_logo_main"  style="">CriticOwl</div>
				<div class="text_logo_motto">&ldquo;Should you watch it? We'll let you know.&rdquo;</div>
			</div>
		</div>
		<div class="topbar-right">
			<div class='text_logo'>
				<?=get_criticowl_phrase();?>
			</div>
			<div id="mobile_menu_icon">
				<span class="btn"><i class="icon-th"></i></span>
			</div>
		</div>
		<div style="clear: both;"></div>

		<div id="menu_start_marker"></div>
		<div id="desktop_top_menu_sticky" class="top_menu_links fixed_menu" style="display:none;"></div>
		<div id="desktop_top_menu_non_sticky" class="top_menu_links"></div>
		
		<div id="desktop_top_menu_prototype" style="display:none;">
			<div class="topbar-menu-links-area">
				<?if($config['access_level'] === 'USER'):?>
					<a href="/" id="home_btn" class="<?=($selected === 'home')?'selected-topbar-button':''?> topbar-button">Home</a>

					<a href="/reviews" id="reviews_btn" class="<?=($selected === 'reviews')?'selected-topbar-button':''?> topbar-button">Reviews</a>

					<a href="/articles" id="articles_btn" class="<?=($selected === 'articles')?'selected-topbar-button':''?> topbar-button">Articles</a>

					<a href="/exclusive_articles" id="exclusive_articles_btn" class="<?=($selected === 'exclusive_articles')?'selected-topbar-button':''?> topbar-button">Exclusives</a>

					<a class="topbar-button" target="_blank" href="http://feedburner.google.com/fb/a/mailverify?uri=Criticowl&amp;loc=en_US">Subscribe</a>

				<?endif;?>
				
				<?if($config['access_level'] === 'ADMIN'):?>
					<a href="/admin" id="admin_home_btn" class="<?=($selected === 'admin_home')?'selected-topbar-button':''?> topbar-button">Admin Home</a>

					<span class="dropdown">
						<a href="#" id="admin_add_article_btn" role="button"
							 data-target="#"
							 class="<?=($selected === 'admin_add_article')?'selected-topbar-button':''?> topbar-button dropdown-toggle"
							 data-toggle="dropdown" >Article</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a tabindex="-1" href="/admin/add_article">Add new article</a></li>
							<li><a tabindex="-1" href="/admin/edit_article">Edit existing article</a></li>
						</ul>
					</span>
				
					<span class="dropdown">
						<a href="#" id="admin_add_review_btn" role="button"
							 data-target="#"
							 class="<?=($selected === 'admin_add_review')?'selected-topbar-button':''?> topbar-button dropdown-toggle"
							 data-toggle="dropdown" >Review</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a tabindex="-1" href="/admin/add_review">Add new review</a></li>
							<li><a tabindex="-1" href="/admin/edit_review">Edit existing review</a></li>
						</ul>
					</span>
				
					<span class="dropdown">
						<a href="#" id="admin_add_media_btn" role="button"
							 data-target="#"
							 class="<?=($selected === 'admin_add_media' || $selected === 'admin_edit_media')?'selected-topbar-button':''?> topbar-button dropdown-toggle"
							 data-toggle="dropdown" >Movies</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a tabindex="-1" href="/admin/add_movie">Add movie</a></li>
							<li><a tabindex="-1" href="/admin/edit_media">Edit movie</a></li>
						</ul>
					</span>
				
					<span class="dropdown">
						<a href="#" id="admin_quote_management_btn" role="button"
							 data-target="#"
							 class="<?=($selected === 'admin_quote_management')?'selected-topbar-button':''?> topbar-button dropdown-toggle"
							 data-toggle="dropdown" >Quotes</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a tabindex="-1" href="/admin/add_quote">Add Quote</a></li>
							<li><a tabindex="-1" href="/admin/edit_quote">Edit Quote</a></li>
						</ul>
					</span>

					<span class="dropdown">
						<a href="#" id="admin_tools_image_linker_btn" role="button"
							 data-target="#"
							 class="<?=($selected === 'admin_tools_image_linker')?'selected-topbar-button':''?> topbar-button dropdown-toggle"
							 data-toggle="dropdown" >Tools</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a tabindex="-1" href="/admin/tool_image_linker">Image Linker</a></li>
						</ul>
					</span>
				
					<a href="/admin/logout" id="admin_logout_btn" class="<?=($selected === 'admin_logout')?'selected-topbar-button':''?> topbar-button">Logout</a>
				<?endif;?>
			</div>
			<?if($config['access_level'] === 'USER'):?>
				<div class='topbar-social-button-area'>
					<a href='https://www.facebook.com/wearecriticowl' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/facebook.png"?>' /></a>
					<a href='https://plus.google.com/+CriticOwl' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/googleplus.png"?>' /></a>
					<a href='https://twitter.com/CriticOwl' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/twitter.png"?>' /></a>
					<a href='https://pinterest.com/CriticOwl' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/pinterest.png"?>' /></a>
					<a href='/rss.xml' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/rss.png"?>' /></a>
				</div>
			<?endif;?>
		</div>

		<div id="mobile_top_menu" class="top_menu_links">
			<a href="/" id="mobile_home_btn" class="<?=($selected === 'home')?'selected-topbar-button':''?> topbar-button">Home</a>

			<a href="/reviews" id="mobile_reviews_btn" class="<?=($selected === 'reviews')?'selected-topbar-button':''?> topbar-button">Reviews</a>

			<a href="/articles" id="mobile_articles_btn" class="<?=($selected === 'articles')?'selected-topbar-button':''?> topbar-button">Articles</a>

			<a href="/exclusive_articles" id="mobile_exclusive_articles_btn" class="<?=($selected === 'exclusive_articles')?'selected-topbar-button':''?> topbar-button">Exclusives</a>

			<a class="topbar-button" target="_blank" href="http://feedburner.google.com/fb/a/mailverify?uri=Criticowl&amp;loc=en_US">Subscribe</a>

			<div class='topbar-social-button-area'>
				<a href='https://www.facebook.com/wearecriticowl' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/facebook.png"?>' /></a>
				<a href='https://plus.google.com/+CriticOwl' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/googleplus.png"?>' /></a>
				<a href='https://twitter.com/CriticOwl' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/twitter.png"?>' /></a>
				<a href='https://pinterest.com/CriticOwl' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/pinterest.png"?>' /></a>
				<a href='/rss.xml' target="_blank" class=''><img width="32" src='<?=resource_url()."img/social_media_icons/rss.png"?>' /></a>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	init_menu();

	$('.dropdown-toggle').dropdown();
	
	$('.logo').click(function() {
			window.location = "/";
	});


	if (window.addEventListener) {
		window.addEventListener('scroll', on_scroll_events, false);
	} else if (window.attachEvent) {
		window.attachEvent('onscroll', on_scroll_events);
	}

    $('#mobile_menu_icon').click(function() {
    	$('#mobile_top_menu').toggle('fast');
    });

});

function on_scroll_events() {
	dynamically_change_menu();
	if(typeof load_more_feeds != 'undefined') {
		load_more_feeds();
	}
	if(typeof retention_modal_article_page_check != 'undefined') {
		retention_modal_article_page_check();
	}
}

function init_menu() {
	//copy prototype unto menus
	var menu_prototype = $('#desktop_top_menu_prototype').html();
	$('#desktop_top_menu_sticky').html(menu_prototype);
	$('#desktop_top_menu_non_sticky').html(menu_prototype);
}

function load_more_feeds() {
	if ($(window).scrollTop() >= ($(document).height() - $(window).height())*0.5) {
		$('.auto_load_feed').click();
	}
}

function dynamically_change_menu() {
	if(is_mobile()) {
		return;
	}

	var $desktop_menu_top = $('#desktop_top_menu_sticky');

	//changing menus
	var menu_marker_pos = $('#menu_start_marker').offset().top - 15;
	var window_pos = $(window).scrollTop();

	if(
	   window_pos >= menu_marker_pos && 
	   !$desktop_menu_top.is(':visible')
	) {
		$desktop_menu_top.toggle();
	} else if(
	   window_pos < menu_marker_pos && 
	   $desktop_menu_top.is(':visible')
	) {
		$desktop_menu_top.toggle();
	}
}
</script>