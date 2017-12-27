<?$id="all_movies_list_snippet_id_".time()?>
<div id="<?=$id?>" class='panel movies_list_container'>
	<div class='titlebar'>
		All Movies
	</div>
	
	<div class='panel-content all_movies_list'>
		<div class="jump_to_filter">
			<?foreach ($media_list as $m): ?>
				<?
					if(isset($completed[$m['heading_letter']])) {
						continue;
					}
					$completed[$m['heading_letter']] = TRUE;
				?>
				<a onclick="scroll_to_id('all_movies_starting_with_<?=$m['heading_letter']?>')"> <?=$m['heading_letter']?> </a>
			<?endforeach;?>
		</div>
		<?
			$heading = "";
		?>
		<?foreach($media_list as $m):?>
			<?
				if($m['heading_letter'] !== $heading) {
					$new_heading = TRUE;
					$heading = $m['heading_letter'];
				} else {
					$new_heading = FALSE;
				}
			?>
			<?if($new_heading):?>
				<div id="all_movies_starting_with_<?=$m['heading_letter']?>">
					<h5><?=$m['heading_letter'];?></h5>
				</div>
			<?endif;?>
			<div class="box-seperator">
				<a href='/movies/<?=$m['uri_segment']?>'><?=$m['name']?></a>
				<br />
			</div>
		<?endforeach;?>
	</div>
</div>