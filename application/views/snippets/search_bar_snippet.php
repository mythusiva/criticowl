<?
	if(!isset($searchbar_id)){
		$searchbar_id = "randomid_".time();
	}
?>
<style type="text/css">
	.search_container {
		width: 100%;
	}
	.search_box {
		margin: 5px 0px;
	}
	.search_input {
		width:100%;
	}
	.search_btn {
		float:right;
		margin-bottom:10px;
	}
	.search_results_box {
		margin-top: 5px;
		overflow-y: auto;
		max-height: 800px;
	}
	.top_searches_container {
		text-align: justify;
		padding: 0px 5px;
	}
	.top_searches_container span {
		padding-right: 5px;
	}
	.text-underline {
		text-decoration: underline;
	}
	.top_searched_text {
		white-space: nowrap;
	}
	.search_container table {
		width: 100%;
	}
</style>
<div class="search_container">
	<table>
    <tr>
        <td><input id="<?=$searchbar_id?>_search_input" type='text' placeholder="Search movies, articles, tags, and more" class="search_input" /></td>
        <td style="width:60px"><span id="<?=$searchbar_id?>_search_btn" class='search_btn btn'><i class="icon-search"></i></span></td>
    </tr>
	</table>
	<div class="top_searches_container search_result_card">
		<?	
			$ci = &get_instance();
			$ci->load->model('search_model');
			$top_searched = $ci->search_model->get_top_searched();
		?>
		<?foreach ($top_searched as $row):?>
			<span>
				<a class="top_searched_text text-underline" data-attr-search-id="<?=$searchbar_id?>" data-attr-text="<?=$row['search_text']?>" href="#"><small><?=$row['search_text']?></small></a>
			</span>
		<?endforeach;?>		
	</div>
	<div id="<?=$searchbar_id?>_search_results" class="search_results_box">
	</div>
</div>

<script type="application/x-javascript">
	$(document).ready(function() {
		$("#<?=$searchbar_id?>_search_btn").click(function() {
			var search_txt = $("#<?=$searchbar_id?>_search_input").val();
			if(search_txt == "") {
				return;
			}
			$.ajax({
				url: '/search/global_search',
				data: {search_terms: search_txt},
				success: function(data) {
					$('#<?=$searchbar_id?>_search_results').hide();
					$('#<?=$searchbar_id?>_search_results').html(data);
					$('#<?=$searchbar_id?>_search_results').slideDown();
					scroll_to_id('<?=$searchbar_id?>_search_results');
				}
			});
		});
		$('#<?=$searchbar_id?>_search_input').keypress(function(e) {
			if (e.which == 13) {
				$("#<?=$searchbar_id?>_search_btn").click();
				return false;			  
			}
		});		
		$('.top_searched_text').click(function() {
			var search_id = $(this).attr('data-attr-search-id');
			var search_text = $(this).attr('data-attr-text');

			$('#'+search_id+'_search_input').val(search_text);
			$("#"+search_id+"_search_btn").click();
		});
	});
</script>