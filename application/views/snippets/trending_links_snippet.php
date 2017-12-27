<?$trending_articles = get_trending_articles();?>
<div class="trending_item_links">
	<!-- <span><i class="icon-fire"></i>Trending pages: </span> -->
	<?foreach ($trending_articles as $trending_article_link):?>
		<?$link[] = "<a class='link' href='/{$trending_article_link['url']}'>{$trending_article_link['title']}</a>";?>
	<?endforeach;?>
	<?
		$long_list = implode(" . ", $link);
	?>

	<div id="trending_items_marquee" class='marquee' style="display:none;"><?=$long_list?></div>
</div>

<script>
	$(document).ready(function(){
		$('#trending_items_marquee').show();
		$('#trending_items_marquee').marquee({
			pauseOnHover: true,
			duration: 25000,
			duplicated: true
		});
	});
</script>