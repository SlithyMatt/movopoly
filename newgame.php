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
		$gamename = $_REQUEST["gameName"];
		// check for validity
		// TODO prevent SQL insertion
		if ((stripos($gamename,"<") != FALSE) || (stripos($gamename,">") != FALSE)) {
			http_response_code(400);
			die("Invalid Name: " . $gamename);
		}

		// check for other game with the same name
		$sql = "SELECT name FROM games";
		$result = $conn->query($sql);
		$match = FALSE;
		if ($result->num_rows > 0) {
  			while (($row = $result->fetch_assoc()) && !$match) {
				if (strcmp($gamename,$row["name"]) == 0) {
					$match = TRUE;
				}
			}
		}
		if ($match) {
			http_response_code(409);
			die("Name already being used: " . $gamename);
		}

		$sql = "INSERT INTO games (name,originator) VALUES (\""
			. $gamename . "\",\""
			. $_REQUEST["originatorHash"] . "\")";
		$conn->query($sql);

		$sql = "INSERT INTO players(id,name,hash,game) VALUES (uuid(),\""
			. $_REQUEST["originatorName"] . "\",\""
			. $_REQUEST["originatorHash"] . "\",\""
			. $gamename . "\")";
		$conn->query($sql);

	} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$sql = "SELECT name FROM games WHERE started IS NULL";
		$result = $conn->query($sql);
		$games = array();
		while ($row = $result->fetch_assoc()) {
			array_push($games,$row["name"]);
		}
		echo json_encode($games);
	}
?>
