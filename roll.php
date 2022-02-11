<?php
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
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

		$roll = rand(1,6);
		$response = array("roll"=>$roll);

		$sql = "UPDATE games SET roll=\"" . $roll . "\" WHERE name=\"" . $game . "\"";
		$conn->query($sql);

		$sql = "SELECT current FROM games WHERE name=\"" . $game . "\"";
		$result = $conn->query($sql);
		$playerid = $result->fetch_assoc()["current"];

		$sql = "SELECT space FROM players WHERE id=\"" . $playerid . "\"";
		$result = $conn->query($sql);
		$space = $result->fetch_assoc()["space"];

		$sql = "SELECT id,name,next,type FROM spaces";
		$result = $conn->query($sql);
		$board = array();
		while ($row = $result->fetch_assoc()) {
			$board[$row["id"]] = $row;
		}

		for ($i = 0; $i < $roll; $i++) {
			$space = $board[$space]["next"];
		}

		$response["space"] = $board[$space]["name"];

		switch ($board[$space]["type"] == 1) {
			case 0:
				$state = "go";
				break;
			case 1:
				$sql = "SELECT player FROM deeds WHERE property=\"" . $space . "\"";
				$result = $conn->query($sql);
				$owner = $result->fetch_assoc()["player"];
				if ($owner == $hash) {
					$state = "own";
				} else if ($owner == NULL) {
					$state = "forSale";
				} else {
					$state = "rent";
				}
				break;
			case 2:
				$state = "chance";
				break;
			default:
				$state = "";
		}

		$sql = "UPDATE players SET space=\"" . $space . "\", state=\"" . $state . "\" WHERE id=\"" . $playerid . "\"";
		$conn->query($sql);

		echo json_encode($response);
	}
?>
