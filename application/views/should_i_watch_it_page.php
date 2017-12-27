<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Should I Watch It ? - Is the movie worth watching ? We'll let you know.</title>
    <meta name="description" content="Everytime you wonder \"Should I Watch it ?\", think CriticOwl's amazing feature. Simply enter the name of the exact title name you wish to watch and CriticOwl will search it's database as well as the world wide web to let you know if it's worth watching. All this happens in a matter of seconds!">
    <?include "header_essentials.php"?>
</head>
<style type="text/css">
@media only screen and (max-width: <?=VIEWPORT_CHANGE_SIZE?>px) {
	.search-container {
		width: 100%;
		background-color: #FFF0CE;
		padding: 10px 0px;
	}
	.search-container table {
		width: 100%;
	}
	.search-box input {
		padding: 10px;
		margin: auto;
		font-size: 18px;
		width: 90%;
	}
	.content_wrapper {
		// margin: 25px 0px 100px 0px;
	}
	#loading_section {
		text-align: center;
		padding-top: 50px;
	}
	#not_found {
		text-align: center;
		padding-top: 25px;
		font-size: 20px;
	}
	#search_movie_btn {
		display: block;
		width: 50%;
		margin: 10px;
	}
}
@media only screen and (min-width: <?=VIEWPORT_CHANGE_SIZE + 1?>px) {
	.search-container {
		width: 100%;
		background-color: #FFF0CE;
		padding: 10px 0px;
	}
	.search-container table {
		width: 100%;
	}
	.search-box input {
		padding: 10px;
		margin: auto;
		font-size: 18px;
		width: 750px;
	}
	.content_wrapper {
		margin: 25px 0px 100px 0px;
	}
	#loading_section {
		text-align: center;
		padding-top: 50px;
	}
	#not_found {
		text-align: center;
		padding-top: 50px;
		font-size: 25px;
	}
}
</style>
<body>
	<?$config['access_level'] = 'USER';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'should_i_watch_it'))?>
	<div class="main_body">
		<div class="">
			<div id="notification_block">
				<?if(!empty($notification)):?>
				<div id="notifications" class="notification_area alert alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<?=$notification?>
				</div>
				<?endif;?>
			</div>
			<div class="content_wrapper">
				<div class='search-container'>
					<table>
						<tr>
							<td align="center" class='search-box'>
								<input id="search_movie_txt" placeholder="What are you thinking about watching ?" type="text" />
								<a id="search_movie_btn" class='btn btn-large' href='#' ><i class="icon-search"></i></a>
							</td>
						</tr>
					</table>
				</div>
				
				<div id="loading_section" style='display: none;'>
					<img src='/img/ajax-loader.gif' />
				</div>
				
				<div id="result">

				</div>
				
				<div id="not_found" style='display: none;'>
					Sorry, we could not find the movie you were looking for ...<br /><br />
					Please check your spelling and try again.
				</div>
				
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>

</html>

<script type="text/javascript">
	var $search_btn = $('#search_movie_btn');
	var $search_txt = $('#search_movie_txt');
	var $thobber = $('#loading_section');
	var $result = $('#result');
	var $not_found = $('#not_found');

  $(document).ready(function(){
  
	$search_btn.click(function() {
		$thobber.toggle();
		search_movie($('#search_movie_txt').val(),update_result);
	});
	$search_txt.keyup(function (e) {
		if (e.keyCode == 13) {
			$search_btn.click();
		}
	});
  });
  
  function search_movie(movie,callback) {
	$result.hide();
	$not_found.hide();
	$.ajax({
		type: "GET",
		url: '/should_i_watch_it/find_movie',
		data: {movie_name:movie}
	}).done(function(data) {
		callback(data);
	});
  }
  
  function update_result(html) {
	$thobber.toggle();
	if (html != 'nothing') {	
		$('#result').html(html).fadeIn();
	} else {
		//show unable to find msg
		$not_found.show();
	}
	scroll_to_id("result");
  }
</script>
