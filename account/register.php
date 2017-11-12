<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("../vendor/bjeavons/zxcvbn-php/src/Matchers/MatchInterface.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/Match.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/DigitMatch.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/Bruteforce.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/YearMatch.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/SpatialMatch.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/SequenceMatch.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/RepeatMatch.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/DictionaryMatch.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/L33tMatch.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matchers/DateMatch.php");
require("../vendor/bjeavons/zxcvbn-php/src/Matcher.php");
require("../vendor/bjeavons/zxcvbn-php/src/Searcher.php");
require("../vendor/bjeavons/zxcvbn-php/src/ScorerInterface.php");
require("../vendor/bjeavons/zxcvbn-php/src/Scorer.php");
require("../vendor/bjeavons/zxcvbn-php/src/Zxcvbn.php");
require('authconnect.php');

//For testing:  My#Password1!2@345

//Registration essentials
$email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
$confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

//Account extras
$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
$first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
$params = array("Username" => "{$username}", "FirstName" => "{$first_name}", "LastName" => "{$last_name}");

$register = $auth->register($email, $password, $confirm_password);
$uid = $auth->getUID($email);
$dbh->query("INSERT INTO user_info(uid,username,first_name,last_name) VALUES('".$uid."','".$params['Username']."','".$params['FirstName']."','".$params['LastName']."' )");

if (!$register['error']) {
    header('Location: ./');
    exit();
} else {
    echo $register['message'];
}

?>