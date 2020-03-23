<?php
$servername = "localhost";
$username = "user";
$password = "password";

$pdo = new PDO("mysql:host=$servername;dbname=movies", $username, $password);
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




