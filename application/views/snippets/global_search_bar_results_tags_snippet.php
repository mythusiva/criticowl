<style type="text/css">
.search_result_tags_card .search-tags-url {
}
.search_result_tags_card .search-tags-desc {
	font-size: 15px;
}
</style>
<div class="search_result_card search_result_tags_card">
	<h4>Matched Tags</h4>
	<?foreach ($data as $result):?>
		<div class="search-result-block">
			<div class="sea	margin-bottom: 5px;rch-tags-title">
				<a href="<?=$result['url']?>"><?=$result['title']?></a>
			</div>
			<div class="search-tags-url search-url">
				<?=$result['url']?>
			</div>
			<div class="search-tags-desc">
				<?=$result['description']?>
			</div>
		</div>
	<?endforeach;?>
</div>
<script type="application/x-javascript">
</script>