function global_show_notification(msg) {
     var $notif_block = $('#notification_block');
     
     $notif_block.hide();
     
     html = "<div id='notifications' class='notification_area alert alert-block'><button type='button' class='close' data-dismiss='alert'>×</button>"+msg+"</div>";
     
     $notif_block.html(html).slideDown();
}
function is_mobile() {
	return $('.is_mobile').is(':visible');
}
function get_offset_amount() {
	if(is_mobile()) {
		return 160;
	} else {
		return 80;
	}
}
function scroll_to_id(id) {
	$('html, body').animate({
            scrollTop: $('#'+id).offset().top-get_offset_amount()}, 500);
}