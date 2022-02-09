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

	$hash = $_REQUEST["hash"];
	$sql = "SELECT games.name FROM players INNER JOIN games ON players.game = games.name WHERE players.hash=\""
		. $hash . "\" AND games.started IS NOT NULL";
	$result = $conn->query($sql);
	$games = array();
	while ($row = $result->fetch_assoc()) {
		array_push($games,$row["name"]);
	}
	echo json_encode($games);
?>
