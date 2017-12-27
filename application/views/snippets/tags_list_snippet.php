<?
	$ci = &get_instance();
	$ci->load->model('tag_model');
?>
<?$tags = $ci->tag_model->get_list_of_tags_for_article($article_fk);?>
<div class="tag_labels_container">
	<?foreach ($tags as $tag_name):?>
		<span class="tag_label"><i class="icon-tag"></i> <?=$tag_name?></span>
	<?endforeach;?>
</div>