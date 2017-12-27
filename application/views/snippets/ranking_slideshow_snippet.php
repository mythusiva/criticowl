<?
	$CI = &get_instance();
	$CI->load->model('slideshow_model');
	$slideshow_data = $CI->slideshow_model->get_slideshow_by_location($identifier);
	
	if(empty($slideshow_data) && $identifier === 'top_5_movies_this_year') {
		$identifier = 'top_5_movies_last_year';
		$slideshow_data = $CI->slideshow_model->get_slideshow_by_location($identifier);
	}
?>
<?if(count($slideshow_data) > 0):?>
<style type="text/css">
	.carousel-caption {
		text-align: center;
	}
	.owl-buttons,.owl-pagination {
		position: absolute;
		margin: -45px auto;
		width: 100%;
	}
	.carousel-readmore-btn {
		float: right;
		margin: 5px;
	}
	.owl-prev {
		float: left;
	}
	.owl-next {
		float: right;
	}
	.owl_carousel {
	}
	.slide-image {
		background-color: #101010;
		background-position: center center;
		background-repeat: no-repeat;
		background-size: contain;
	}
	.ranking_slideshow_container {
	}
	.owl-theme .owl-controls .owl-buttons div {
		background: #FFFFFF !important;
	}
</style>
<div>
	<div class="title-medium title-box"><?=$slideshow_data[0]['slideshow_title']?></div>
	<div id="<?=$identifier?>_ranking_slideshow" class="<?=$identifier?>_ranking_slideshow ranking_slideshow_container">
			
		<div id="<?=$identifier?>_carousel" class='owl_carousel'>

		<?foreach($slideshow_data as $slide):?>

			<div class="owl_carousel_slide">
				<div onclick="window.location='<?=$slide['read_more_link']?>';" class="slide-image pointer" style="height: <?=(isset($slide['height']) && $slide['height'] > 0)?$slide['height']:'350'?>px; background-image: url(<?=get_compressed_image_url($slide['image_url'])?>);"></div>
			</div>
		 
		<?endforeach;?>
		
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#<?=$identifier?>_carousel").owlCarousel({
			autoPlay: 6000,
			singleItem:false,
			items:<?=isset($num_slides)?$num_slides:3?>,
			itemsDesktop : false,
			itemsDesktopSmall : false,
			stopOnHover:true,
			lazyLoad:true,
			navigation:true,
			pagination:false,
			navigationText: ["<i class='icon-chevron-left'></i>","<i class='icon-chevron-right'></i>"]
		});
	});
</script>
<?endif;?>