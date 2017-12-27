<?foreach($results as $a):?>
	<?if($a['article_type'] === POST_ARTICLE):?>
		<?=$this->load->view('snippets/preview_article_content_snippet',$a,TRUE)?>
	<?elseif($a['article_type'] === POST_REVIEW):?>
		<?=$this->load->view('snippets/preview_review_content_snippet',$a,TRUE)?>
	<?endif;?>
<?endforeach;?>