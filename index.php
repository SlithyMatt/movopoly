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
		<a href="newgame.php">Start a New Game</a>

		<?php
			$servername = "localhost";
			$username = "movopoly";
			$password = "m0v0p0ly";
			$dbname = "movopoly";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT id, name, started FROM games";
			$result = $conn->query($sql);

			$pending = array();
			$started = array();

			while ($row = $result->fetch_assoc()) {
				if (is_null($row["started"])) {
					array_push($pending,$row);
				} else {
					array_push($started,$row);
				}
			}

			if (count($pending) > 0) {

			}

			if (count($started) > 0) {
				
			}
		?>

	</div><!-- /content -->

</div><!-- /page one -->


</body>
</html>
