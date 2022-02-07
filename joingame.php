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

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$game = $_REQUEST["game"];
		$sql = "SELECT * FROM games WHERE name='" . $game . "'";
		$result = $conn->query($sql);

		if ($result->num_rows == 0) {
			http_response_code(400);
			die("Unknown game: " . $game);
		}

		$sql = "SELECT * FROM players WHERE game='" . $game . "'";
		$result = $conn->query($sql);
		
		if ($result->num_rows == 0) {
			$sql = "INSERT INTO players(id,name,hash,game) VALUES (uuid(),'"
				. $_REQUEST["name"] . "','"
				. $_REQUEST["hash"] . "','"
				. $game . "')";
			$conn->query($sql);
		}
	}
?>
