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

		$sql = "SELECT * FROM players WHERE game=\"" . $game . "\" AND hash=\"" . $hash . "\"";
		$result = $conn->query($sql);
		$player = $result->fetch_assoc();

		$response = array("money"=>$player["money"],
								"moneyEffect"=>"",  // TODO add column
								"turnState"=>$player["state"]);

		$sql = "SELECT current FROM games WHERE name=\"" . $game . "\"";
		$result = $conn->query($sql);
		$currentid = $result->fetch_assoc()["current"];

		$nextid = "";
		if ($currentid == $player["id"]) {
			$response["currentPlayer"] = "YOU";
			$nextid = $player["next"];
		} else {
			$sql = "SELECT name,next FROM players WHERE id=\"" . $currentid . "\"";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$response["currentPlayer"] = $row["name"];
			$nextid = $row["next"];
		}

		if ($nextid == $player["id"]) {
			$response["nextPlayer"] = "YOU";
		} else {
			$sql = "SELECT name FROM players WHERE id=\"" . $nextid . "\"";
			$result = $conn->query($sql);
			$response["nextPlayer"] = $result->fetch_assoc()["name"];
		}		

		$sql = "SELECT name,imgfilename FROM spaces WHERE id=\"" . $player["space"] . "\"";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$response["spaceName"] = $row["name"];
		$response["spaceImage"] = $row["imgfilename"];

		echo json_encode($response);
	}
?>
