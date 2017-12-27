<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Your browser failed</title>
    <?include "header_essentials.php"?>
	<style type="text/css">
		body {
			position: absolute;
			top: 10;
			left: 0;
			right: 0;
			color: #ffffff;
			width: 100%;
			padding-top: 35px;
			font-size: 20px;
			text-align: center;
		}
		body p {
			color: #8C8C8C;
		}
		// table {
		// 	margin: 35px auto;
		// 	text-align: center;
		// }
	</style>
</head>
<body>
	<h1>
		BROWSER FAIL!
	</h1>
	<h3>
		Your browser is unable to comply with the latest web standards.
	</h3>
	<p>
		<small>To ensure that we can offer the latest and greatest features, here is a list of our officially supported browsers:</small>
	</p>
	<p>
		<a href='https://ie.microsoft.com'>Internet Explorer 10+</a><br/><br/>
		<a href='https://www.google.com/chrome/'>Google Chrome</a><br/><br/>
		<a href='http://www.mozilla.org/firefox/'>Firefox</a><br/><br/>
		<a href='http://www.apple.com/safari/'>Safari</a><br/><br/>
	</p>
</body>
</html>
