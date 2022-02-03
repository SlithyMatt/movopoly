<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Movopoly</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="lib/jquery-ui.css">
	<script src="lib/jquery.js"></script>
	<script src="lib/jquery-ui.js"></script>
	<script>
	</script>
</head>
<body>


<!-- Start of first page: #one -->
<div data-role="page" id="one">

	<div data-role="toolbar" data-type="header">
		<img src="movopoly_logo_small.png" />
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<h2>Would you like to play a game?</h2>
		<a href="newgame.html">Start a New Game</a>

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
				echo "<h3>Join a Game</h3>";
				foreach($pending as $game) {
					echo "<a href=\"join.php?id=", $game["id"], "\">", $game["name"], "</a><br>";
				}
			}

			if (count($started) > 0) {
				echo "<h3>Return to a Game</h3>";
				foreach($started as $game) {
					echo "<a href=\"return.php?id=", $game["id"], "\">", $game["name"], "</a><br>";
				}
			}
		?>

	</div><!-- /content -->

</div><!-- /page one -->


</body>
</html>
