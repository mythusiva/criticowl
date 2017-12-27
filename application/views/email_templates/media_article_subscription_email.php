<style type="text/css">
	.email_block {
		background-color: #F4ECE3;
		color: #222222;
		width: 100%;
		padding: 50px;
	}
	.email_footer {
		color: #646464;
	}
</style>
<html>
	<head></head>
	<body>
		<div class="email_block">
			<p>
				<?
					$title_url_segment = get_uri_prefix_by_media_type($preview_data['media_type']);
					$title_url = base_url().$title_url_segment."/".$preview_data['media_uri_segment'];
					$page_url = base_url().strtolower($preview_data['article_type'])."/".$preview_data['uri_segment'];
				?>
				<table>
					<tr>
						<td style="width: 30%">
							<small>Title: <a href='<?=$title_url?>'><?=$preview_data['media_title']?></a></small><br>
							<small>Media: <?=ucfirst(strtolower($preview_data['media_type']))?></small><br>
							<small>Release date: <?=format_date($preview_data['release_date'])?></small>
						</td>
						<td style="width: 70%">
							<h4><a href="<?=$page_url?>"><?=$preview_data['title']?></a></h4>
							<?if(!empty($preview_data['preview_text'])):?>
							<blockquote>
								<p>
									<?=$preview_data['preview_text']?>
								</p>
							</blockquote>
							<?endif;?>
							<small><?=date('l jS \of F Y h:i:s A',strtotime($preview_data['date_posted']))?></small><br/>
							<a href="<?=$page_url?>">Full Page &rarr;</a>
						</td>
					</tr>
				</table>
			</p>
			<p class="email_footer">
				Want to stop receiving updates on this topic? <a href="<?=base_url()?>subscription/remove/<?=$unsubscribe_token?>/<?=md5($email_address)?>">Click here</a>
			</p>
		</div>
	</body>
</html>
