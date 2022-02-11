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
		$action = $_REQUEST["action"];
		$game = $_REQUEST["game"];
		$hash = $_REQUEST["hash"];

		$sql = "SELECT originator,started FROM games WHERE name=\"" . $game . "\"";
		$result = $conn->query($sql);

		if ($result->num_rows == 0) {
			http_response_code(400);
			die("Unknown game: " . $game);
		}
		$row = $result->fetch_assoc();

		if ($action == "leave") {
			if ($hash == $row["originator"]) {
				http_response_code(403);
				die("You cannot leave game you started: " . $game);
			}
			if ($row["started"] != NULL) {
				http_response_code(403);
				die("You cannot leave game in progress: " . $game);
			}
			$sql = "DELETE FROM players WHERE game=\"" . $game . "\" AND hash=\"" . $hash ."\"";
			$conn->query($sql);
		} else {
			if ($hash != $row["originator"]) {
				http_response_code(403);
				die("You are not authorized to modify game: " . $game);
			}
			switch ($action) {
				case "start":
					if ($row["started"] != NULL) {
						http_response_code(403);
						die("Game is already started: " . $game);
					}
					$sql = "SELECT id,hash FROM players WHERE game=\"" . $game . "\"";
					$result = $conn->query($sql);

					$num_players = $result->num_rows;
					if ($num_players < 2) {
						http_response_code(400);
						die("Not enough players in game: " . $game);
					}
					$players = array();
					while ($row = $result->fetch_assoc()) {
						if ($row["hash"] == $hash) {
							array_unshift($players,$row["id"]);
						} else {
							array_push($players,$row["id"]);
						}
					}
					$time = date("Y-m-d H:i:s");
					$sql = "UPDATE games SET current=\"" . $players[0]
						. "\", started=\"" . $time . "\" WHERE name=\"" . $game . "\"";
					$conn->query($sql);

					for ($i = 0; $i < $num_players-1; $i++) {
						$sql = "UPDATE players SET next=\"" . $players[$i+1]
							. "\" WHERE id=\"" . $players[$i] . "\"";
						$conn->query($sql);
					}
					$sql = "UPDATE players SET next=\"" . $players[0]
						. "\" WHERE id=\"" . $players[$num_players-1] . "\"";
					$conn->query($sql);

					$sql = "SELECT id FROM spaces WHERE type=1";
					$result = $conn->query($sql);
					$sql = "";
					while ($row = $result->fetch_assoc()) {
						$sql = $sql . "INSERT INTO deeds (id,property,game) VALUES (uuid(),\""
							. $row["id"] . "\",\""
							. $game . "\");";
					}
					$conn->query($sql);
					break;
				case "cancel":
					$sql = "DELETE FROM games WHERE name=\"" . $game . "\"";
					$conn->query($sql);
					$sql = "DELETE FROM players WHERE game=\"" . $game . "\"";
					$conn->query($sql);
					break;
				default:
					http_response_code(400);
					die("Invalid Action: " . $action);
			}
		}
	} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$game = $_REQUEST["game"];

		$sql = "SELECT originator,started FROM games WHERE name=\"" . $game . "\"";
		$result = $conn->query($sql);
		$hash = "";
		$started = NULL;
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$hash = $row["originator"];
			$started = $row["started"];
		}

		$sql = "SELECT name,hash FROM players WHERE game=\"" . $game . "\"";
		$result = $conn->query($sql);
		$players = array();
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				if ($row["hash"] == $hash) {
					array_unshift($players,$row["name"]);
				} else {
					array_push($players,$row["name"]);
				}
			}
		}

		$response = array("originatorHash"=>$hash,
								"started"=>$started,
								"players"=>array_values($players));

		echo json_encode($response);
	}
?>
