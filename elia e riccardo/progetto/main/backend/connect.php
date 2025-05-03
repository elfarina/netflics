<?php
//configurazione della connessione al database
$serverName = "localhost";
$userName = "admin_user";
$password = "admin";
$dbName = "netteflics";
$conn = mysqli_connect($serverName, $userName, $password, $dbName ) OR die('Could not connect to MySQL: ' . mysqli_connect_error() );

?>