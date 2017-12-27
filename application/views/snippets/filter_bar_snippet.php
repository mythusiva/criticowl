<style type="text/css">
	.filter_bar_container {
		display: block;
		background: #8D968F;
		padding: 2px;
		color: #FFFFFF;
		font-size: 14px;
	}
	.left_filter_bar {
		display: block;
		float: left;
	}
	.left_filter_bar_options {
		display: inline-block;
		padding: 2px 5px;
	}
	.right_filter_bar {
		display: block;
		float: right;
		padding: 5px;
	}
	.right_filter_bar_options {
		display: inline-block;
		padding: 2px 5px;
	}
</style>
<div class='filter_bar_container'>
	<div class='left_filter_bar'>
		<span class="left_filter_bar_options inline">
			<label class="checkbox inline">
				<input id="filter_bar_news_checkbox" type="checkbox" checked='checked' class="available_filter_option" value='<?=POST_NEWS?>' > News
			</label>
		</span>
		<span class="left_filter_bar_options inline">
			<label class="checkbox inline">
				<input id="filter_bar_articles_checkbox" type="checkbox" checked='checked' class="available_filter_option" value='<?=POST_ARTICLE?>' > Articles
			</label>
		</span>
		<span class="left_filter_bar_options inline">
			<label class="checkbox inline">
				<input id="filter_bar_reviews_checkbox" type="checkbox" checked='checked' class="available_filter_option" value='<?=POST_REVIEW?>' > Reviews
			</label>
		</span>
	</div>
	<div class='right_filter_bar'>
		<span class="right_filter_bar_options inline">
			Page <span id='filter_bar_page_number'>1</span> 
			(<span id='filter_bar_total_number_results'>0</span> results)
		</span>
		<span id='filter_bar_throbber' class='right_filter_bar_options throbber' style='display: none;' ></span>
</div>
	<div style="clear: both;"></div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		filter_bar_callToAction = 0;
		filter_bar_enabled_filters = getSelectedFilters();
		
		selectAllFilters(); //init
		
		$('.available_filter_option').click(function() {
			filter_bar_callToAction++;
			filter_bar_enabled_filters = getSelectedFilters();
			$('#filter_bar_throbber').show();
			callUpdateToFilteredPages(filter_bar_callToAction);
		});
	});
	function updateNumberOfResults(amnt) {
		$('#filter_bar_total_number_results').html(amnt);
	}
	function updateCurrentPageNumber(amnt) {
		$('#filter_bar_page_number').html(amnt);		
	}
	function getSelectedFilters() {
		var selected_filters = [];
		var selected = $('.available_filter_option:checked').each(function() {
			selected_filters.push($(this).val());
		});
		
		if(selected_filters.length == 0) {
			selectAllFilters();
		}
		
		return selected_filters;
	}
	function callUpdateToFilteredPages(id) {
		setTimeout(function(){
			if(filter_bar_callToAction == id) {
				filterBarChanged(filter_bar_enabled_filters);
			} 
			$('#filter_bar_throbber').hide();
		},3000);
	}
	function selectAllFilters() {
		$('.available_filter_option').each(function() {
			$(this).attr('checked','checked');
		});
	}
</script>