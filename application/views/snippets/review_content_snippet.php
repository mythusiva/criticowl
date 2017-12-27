<?
if(!isset($url)) {
	$url = '/'.get_uri_prefix_by_media_type($media_type).$media_uri_segment;
}
?>
<div class='review_page_container'>
	<div class="heading title-medium article_page_heading">
		<div class='review-top-label'>
			<span class="label"><?=date('Y-m-d',strtotime($date_posted))?></span> 
			<?if(!empty($author)):?>
				<span class="label"><?="Author: {$author}"?></span>
			<?endif;?>
			<?if($media_type !== MEDIA_NONE):?>
				<span class="label"><a href="<?=$url?>" class="no_link"><?=$media_title?></a></span> 
			<?endif;?>
			<span class="label label-important">Review</span> 
		</div>
		<div class="review-title"><?=$title?></div>
	</div>
	<div class="content">
		<div class='social_media_box'>
			<div class="addthis_sharing_toolbox"></div>
		</div><br />
		<?=$content?>
		<div class='social_media_box'>
			<div class="addthis_sharing_toolbox"></div>
		</div>
	</div>
	<div class="review_page_tags">
		<?=$this->load->view('snippets/tags_list_snippet',array('article_fk' => $article_pk));?>
	</div>

	<?$sources_array = get_url_array_from_list($sources);?>
	<?if(!empty($sources_array)):?>
	<div class='sources_list_block'>
		Sources:
		<?foreach($sources_array as $hostname => $source):?>
			<span><a href='<?=$source?>' target="_blank"><?=$hostname?></a></span>
		<?endforeach;?>
	</div>
	<?endif;?>
</div>