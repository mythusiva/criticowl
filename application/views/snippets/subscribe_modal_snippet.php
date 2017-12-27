<?
if(!isset($id)) {
	$id = '_'.$media_fk.'_';
}
if(!isset($btn_size)) {
	$btn_size = 'small';
}
?>

<style type="text/css">
	.invalid_field {
		color: #E53434;
	}
	#subscribeModal<?=$id?> {
		text-align: left;
	}
</style>

<!-- Button to trigger modal -->
<a href="#subscribeModal<?=$id?>" role="button" class="btn btn-<?=$btn_size?>" data-media-fk="<?=$media_fk?>" data-toggle="modal"><i class='icon-envelope'></i><?=(isset($label)?" ".$label:' Subscribe')?></a>
 
<!-- Modal -->
<div id="subscribeModal<?=$id?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="subscribeModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="subscribeModalLabel">Subscription</h3>
  </div>
  <div class="modal-body">
		
		<p style="text-align: center">
			<label id="subscribeModal_emailaddress_label<?=$id?>"><strong>Your Email Address : </strong> <input id="subscribeModal_emailaddress_field<?=$id?>" type="text" style="width: 70%" /></label>
		</p>
		<p>
			Any news posts, articles or reviews for "<?=$media_title?>" will be sent you immediately via email. You can also unsubscribe at any time by clicking on the link inside your email.
		</p>

  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button id="subscribeModalSave<?=$id?>" class="btn btn-primary">Submit</button>
  </div>
</div>
<script type="application/x-javascript">
	$(document).ready(function() {
		$('#subscribeModalSave<?=$id?>').click(function() {
			email = $('#subscribeModal_emailaddress_field<?=$id?>').val();
			subscribeModalSubscribe<?=$id?>(email);
		});	
	});
	function subscribeModalSubscribe<?=$id?>(email_address) {
		$.ajax({
			type: "POST",
			url: '/subscription/subscribe',
			data: {media_fk:<?=$media_fk?>,email_address:email_address},
			success: function(data) {
				if(data.status == 'valid') {
					$('#subscribeModal<?=$id?>').modal('hide');
					global_show_notification('Successfully subscribed to updates!');
					window.location = "#";
				} else {
					$('#subscribeModal_emailaddress_label<?=$id?>').addClass('invalid_field');
				}
			},
			dataType: 'json'
		});		
	}
</script>