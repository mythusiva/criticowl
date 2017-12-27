<style type="text/css">
.footer {
	margin: 0px auto;
	padding: 15px 0px;
	/*width: 80%;*/
	font-family: 'Asap', sans-serif;
	text-align: center;
	color: #232323;
	background-color: #333333;
}
.site_footer_link {
	padding: 10px;
	font-size: 15px;
}
.footer div {
	color: #C1C1C1;
	font-weight: bold;
	padding-bottom: 20px;
}
</style>

<div class="is_mobile"></div>

<div class="footer">
	<div>
		&copy; 2013 - <?=date("Y")?> CriticOwl. All Rights Reserved.
	</div>
	<div>
		<span class="site_footer_link"><a href="/about-us">About Us</a></span>
		<span class="site_footer_link"><a href="/terms-and-conditions">Terms and Conditions</a></span>
		<span class="site_footer_link"><a href="/privacy-policy">Privacy Policy</a></span>
		<span class="site_footer_link"><a href="/contact-us">Contact Us</a></span>
	</div>
	<div>
		<a href='http://rayantek.com' target="_blank" >A Rayantek Production</a>
	</div>
<!--[if lt IE 7 ]> <div class="ie-detection ie6 ie"></div> <![endif]-->
<!--[if IE 7 ]>    <div class="ie-detection ie7 ie"></div> <![endif]-->
<!--[if IE 8 ]>    <div class="ie-detection ie8 ie"></div> <![endif]-->
<!--[if IE 9 ]>    <div class="ie-detection ie9 ie"></div> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <div class="ie-detection"></div> <!--<![endif]-->
</div>

<script type="text/javascript">
	$(document).ready(function(){
		if (Function('/*@cc_on return document.documentMode===10@*/')()){
		    document.documentElement.className+=' ie';
		}
		// Then..
		if($('.ie-detection').hasClass('ie')){
			window.location = "/browser_fail";
		} // to check for IE10 and below.

	});
</script>


