<!DOCTYPE html>
<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("../../vendor/phpauth/phpauth/Auth.php");
require("../../vendor/phpauth/phpauth/Config.php");

$dbh = new PDO("mysql:host=xq7t6tasopo9xxbs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=ou71kwcm2qpd3o88", "pm3gaxazmj304hlq", "ob6dpkek4vwj75w7") or die("Cannot connect to the database.");  
$config = new PHPAuth\Config($dbh);
$auth   = new PHPAuth\Auth($dbh, $config);


if($_SESSION['logged_in'] == true) {
    
    $email = $_SESSION['email'];
    $uid = $auth->getUID($email);
    
    $usrn = $dbh->query("SELECT username FROM user_info WHERE uid = $uid; ", PDO::FETCH_ASSOC)->fetch()['username'];
    $frst = $dbh->query("SELECT first_name FROM user_info WHERE uid = $uid; ", PDO::FETCH_ASSOC)->fetch()['first_name'];
    $lst = $dbh->query("SELECT last_name FROM user_info WHERE uid = $uid; ", PDO::FETCH_ASSOC)->fetch()['last_name'];
    $userParams = array('username' => $usrn, 'first_name' => $frst, 'last_name' => $lst);

} else {
    
    $userParams['first_name'] = '';
    $userParams['last_name'] = '';
    $userParams['username'] = '';
}

function searchForUser($username, $dbh) {
    
    $sth = $dbh->prepare("SELECT first_name, last_name FROM user_info WHERE username = '$username'; ");
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    return $result;
    
}

if (isset($_GET['user'])) {
    
    $username = $_GET['user'];
    $result = searchForUser($username, $dbh);
    
    if($result) {
        $userParams['first_name'] = $result[0]['first_name'];
        $userParams['last_name'] = $result[0]['last_name'];
        $userParams['username'] = $username;
    }
}

?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Account - Profile</title>
    </head>
    
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input name="user" id="user" type="text" placeholder="Enter a username to search for a user.">
        </form>
        
        <p><?php echo $userParams['first_name'] . ' ' . $userParams['last_name'] ?></p>
        <p><?php echo $userParams['username'] ?></p>
        
    </body>
    
</html>