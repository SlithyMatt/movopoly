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


?>
