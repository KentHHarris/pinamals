<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('authconnect.php');

$email = $_SESSION['email'];
$hash = $dbh->query("SELECT hash FROM sessions WHERE uid = (SELECT id FROM users WHERE email = '$email');", PDO::FETCH_ASSOC)->fetch()['hash'];

$logout = $auth->logout($hash);

if (!$logout['error']) {
    $_SESSION['email'] = null;
    header('Location: ../home/');
    exit();
} else {
    echo $logout['message'];
}

?>