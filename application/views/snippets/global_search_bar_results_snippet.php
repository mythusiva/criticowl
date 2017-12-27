<div class="search_results_container">
	<?if(empty($results)):?>
		<p>
			Hmmm, it looks like we were unable to find anything. <br />
			Please check your spelling or try using more words.
		</p>
	<?else:?>

		<?if(!empty($results['siwi'])):?>
			<?=$this->load->view('snippets/global_search_bar_results_siwi_snippet',$results['siwi']);?>
		<?endif;?>
		
		<?if(!empty($results['movie_review'])):?>
			<?=$this->load->view('snippets/global_search_bar_results_review_snippet',$results['movie_review']);?>
		<?endif;?>
		
		<?if(!empty($results['movie_articles'])):?>
			<?=$this->load->view('snippets/global_search_bar_results_articles_snippet',array('data' => $results['movie_articles']));?>
		<?endif;?>

		<?if(!empty($results['tags'])):?>
			<?=$this->load->view('snippets/global_search_bar_results_tags_snippet',array('data' => $results['tags']));?>
		<?endif;?>
		
		<?if(!empty($results['other'])):?>
			<?=$this->load->view('snippets/global_search_bar_results_vague_snippet',array('data' => $results['other']));?>
		<?endif;?>

	<?endif;?>
</div>
<script type="application/x-javascript">
</script>