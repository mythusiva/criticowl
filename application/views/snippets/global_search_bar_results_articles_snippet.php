<style type="text/css">
.search_result_article_card .search-article-url {
}
.search_result_article_card .search-article-desc {
	font-size: 15px;
}
</style>
<div class="search_result_card search_result_article_card">
	<h4>Matched Articles</h4>
	<?foreach ($data as $result):?>
		<div class="search-result-block">
			<div class="sea	margin-bottom: 5px;rch-article-title">
				<a href="<?=$result['url']?>"><?=$result['title']?></a>
			</div>
			<div class="search-article-url search-url">
				<?=$result['url']?>
			</div>
			<div class="search-article-desc">
				<?=$result['description']?>
			</div>
		</div>
	<?endforeach;?>
</div>
<script type="application/x-javascript">
</script>