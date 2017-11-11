<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("../vendor/phpauth/phpauth/Auth.php");
require("../vendor/phpauth/phpauth/Config.php");

$dbh = new PDO("mysql:host=localhost;dbname=pinamals", "root", "root") or die("Cannot connect to the database.");  
$config = new PHPAuth\Config($dbh);
$auth   = new PHPAuth\Auth($dbh, $config);

?>