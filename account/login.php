<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('authconnect.php');

$email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

$login = $auth->login($email, $password, $remember = false);

if (!$login['error']) {
    header('Location: ../home/');
    exit();
} else {
    echo $login['message'];
}

?>