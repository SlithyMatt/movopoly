<html>
<head>
   <meta charset="utf-8">
   <title>Movopoly Admin Panel</title>
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

      if (array_key_exists("game",$_REQUEST)) {
         $game = $_REQUEST["game"];


      }
   ?>
</head>
<body>
   <h1>Movopoly Admin Panel</h1>
   <h3>If you don't know what you're doing please leave!</h3>
   &nbsp;<br>
   <?php
      $sql = "SELECT name FROM games WHERE started IS NULL";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
         echo "<b>Delete Games:</b>";

      }
   ?>
</body>
</html>
