<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Movopoly</title>
	<link rel="stylesheet" href="/jqm/css/themes/default/jquery.mobile.css">
	<link rel="stylesheet" href="/jqm/demos/_assets/css/jqm-demos.css">
	<link rel="shortcut icon" href="../favicon.ico">
	<script src="/jqm/external/jquery/jquery.js"></script>
	<script src="/jqm/demos/_assets/js/"></script>
	<script src="/jqm/js/"></script>
</head>
<body>

<!-- Start of first page: #one -->
<div data-role="page" id="one">

	<div data-role="toolbar" data-type="header">
		<img src="movopoly_logo_small.png" />
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<h2>Would you like to play a game?</h2>
		<h3>Show internal pages:</h3>
		<p><a href="#two" class="ui-button ui-shadow ui-corner-all">Show page "two"</a></p>
		<p><a href="#popup" class="ui-button ui-shadow ui-corner-all" data-rel="dialog" data-transition="pop">Show page "popup" (as a dialog)</a></p>
	</div><!-- /content -->

</div><!-- /page one -->

<!-- Start of second page: #two -->
<div data-role="page" id="two" data-theme="a">

	<div data-role="toolbar" data-type="header">
		<h1>Two</h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<h2>Two</h2>
		<p><a href="#one" data-direction="reverse" class="ui-button ui-shadow ui-corner-all ui-button-b">Back to page "one"</a></p>

	</div><!-- /content -->

</div><!-- /page two -->

<!-- Start of third page: #popup -->
<div data-role="page" id="popup">

	<div data-role="toolbar" data-type="header" data-theme="b">
		<h1>Chance</h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<h2>You won a Beauty Contest.</h2>
		<h3>Collect $50</h3>
		<p><a href="#one" data-rel="back" class="ui-button ui-shadow ui-corner-all ui-button-inline">Back to page "one" <span class="ui-icon ui-icon-back"></span></a></p>
	</div><!-- /content -->


</div><!-- /page popup -->

</body>
</html>
