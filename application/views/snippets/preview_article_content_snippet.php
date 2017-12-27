<?
	$url = '/'.get_uri_prefix_by_media_type($media_type).$media_uri_segment;
?>
<div class='article_container'>
	<table style="width:100%;">
		<tr>
			
			<td style="width: 25%;">
				<div onclick="window.location='/article/<?=$uri_segment?>'" class='pointer tile_img_thumbnail' style="background-image: url('<?=encodeURIComponent(get_compressed_image_url($image_link))?>');"></div>
			</td>
			<td style="width: 75%; vertical-align: middle; padding: 0px 15px;">
				<div class="preview-article-title center">
					<a href="/article/<?=$uri_segment?>"><?=$title?></a>
				</div>
				<div class="preview_tile_date center">
					<small><?=date(DATE_RFC850,strtotime($date_posted))?></small>
				</div>
				<p class='article-content'>
					<?=$preview_text?>
				</p>
				<?=$this->load->view('snippets/tags_list_snippet',array('article_fk' => $id));?>
			</td>
			
		</tr>
	</table>
</div>