<?
if(!isset($id_amazon_modal)) {
	$id_amazon_modal = '_'.md5($title.time()).'_';
}
if(!isset($btn_size)) {
	$btn_size = 'small';
}
?>

<style type="text/css">
.amazon_link_btn {
	margin: 20px 10px;
}
.amazon_links_container {
	text-align: justify;
	padding: 20px;
}
</style>

<!-- Button to trigger modal -->
<a href="#amazonGetItModal<?=$id_amazon_modal?>" role="button" class="btn btn-<?=$btn_size?>" data-title="<?=$title?>" data-toggle="modal"><i class='icon-shopping-cart'></i><?=(isset($label)?" ".$label:' Buy')?></a>
 
<!-- Modal -->
<div id="amazonGetItModal<?=$id_amazon_modal?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="amazonGetItModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="amazonGetItModalLabel">Choose your marketplace: </h3>
  </div>
  <div class="modal-body">
	<div class="amazon_links_container">
	<?$affiliate_ids = get_amazon_affiliate_ids();?>
	<?foreach ($affiliate_ids as $details):?>
		<?$url = get_amazon_search_link($details['affiliate_id'],$details['domain'],$title);?>
		<a target="_blank" class="amazon_link_btn" href="<?=$url?>"><img width="140" src=<?=get_compressed_image_url($details['logo'])?> /></a>
	<?endforeach;?>
	</div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
</div>