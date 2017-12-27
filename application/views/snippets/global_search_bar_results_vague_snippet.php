<style type="text/css">
.search_result_vague_card .search-vague-url {
}
.search_result_vague_card .search-vague-desc {
	font-size: 15px;
}
</style>
<div class="search_result_card search_result_vague_card">
	<h4>Related Matches</h4>
	<?foreach ($data as $result):?>
		<div class="search-result-block">
			<div class="sea	margin-bottom: 5px;rch-vague-title">
				<a href="<?=$result['url']?>"><?=$result['title']?></a>
			</div>
			<div class="search-vague-url search-url">
				<?=$result['url']?>
			</div>
			<div class="search-vague-desc">
				<?=$result['description']?>
			</div>
		</div>
	<?endforeach;?>
</div>
<script type="application/x-javascript">
</script>