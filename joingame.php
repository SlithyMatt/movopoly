<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

		$game = $_REQUEST["game"];
		$hash = $_REQUEST["hash"];
		$sql = "SELECT * FROM games WHERE name=\"" . $game . "\"";
		$result = $conn->query($sql);

		if ($result->num_rows == 0) {
			http_response_code(400);
			die("Unknown game: " . $game);
		}

		$sql = "SELECT * FROM players WHERE game=\"" . $game . "\" AND hash=\"" . $hash . "\"";
		$result = $conn->query($sql);

		if ($result->num_rows == 0) {
			$sql = "SELECT id FROM spaces WHERE type=0";
			$result = $conn->query($sql);
			$go_space = $result->fetch_assoc()["id"];

			$sql = "INSERT INTO players(id,name,hash,game,space) VALUES (uuid(),\""
				. $_REQUEST["name"] . "\",\""
				. $hash . "\",\""
				. $game . "\",\""
				. $go_space . "\")";
			$conn->query($sql);
		} else {
			$sql = "UPDATE players SET name=\"" . name
				. "\" WHERE id=\"" . $result->fetch_assoc()["id"] . "\"";
			$conn->query($sql);
		}
	}
?>
