<?php
//configurazione della connessione al database
$serverName = "localhost";
$userName = "readonly_user";
$password = "user";
$dbName = "netteflics";
$conn = mysqli_connect($serverName, $userName, $password, $dbName ) OR die('Could not connect to MySQL: ' . mysqli_connect_error() );

?>