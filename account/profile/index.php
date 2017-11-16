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


if(isset($_SESSION['email'])) {
    
    $email = $_SESSION['email'];
    $uid = $auth->getUID($email);
    
    $usrn = $dbh->query("SELECT username FROM user_info WHERE uid = $uid; ", PDO::FETCH_ASSOC)->fetch()['username'];
    $frst = $dbh->query("SELECT first_name FROM user_info WHERE uid = $uid; ", PDO::FETCH_ASSOC)->fetch()['first_name'];
    $lst = $dbh->query("SELECT last_name FROM user_info WHERE uid = $uid; ", PDO::FETCH_ASSOC)->fetch()['last_name'];
    $nposts = $dbh->query("SELECT num_of_posts FROM user_info WHERE uid = $uid; ", PDO::FETCH_ASSOC)->fetch()['num_of_posts'];
    $npoints = $dbh->query("SELECT points_allocated FROM user_info WHERE uid = $uid; ", PDO::FETCH_ASSOC)->fetch()['points_allocated'];
    $userParams = array('username' => $usrn, 'first_name' => $frst, 'last_name' => $lst, 'num_of_posts' => $nposts, 'points_allocated' => $npoints);

} else {
    
    $userParams['first_name'] = '';
    $userParams['last_name'] = '';
    $userParams['username'] = '';
    $userParams['num_of_posts'] = 0;
    $userParams['points_allocated'] = 0;
    
}

function searchForUser($username, $dbh) {
    $sth = $dbh->prepare("SELECT first_name, last_name, num_of_posts, points_allocated FROM user_info WHERE username = '$username'; ");
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
        $userParams['num_of_posts'] = $result[0]['num_of_posts'];
        $userParams['points_allocated'] = $result[0]['points_allocated'];
        //echo 'first name ' . $userParams['first_name'];
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
        
        <?php if (isset($_SESSION['email']) || $userParams['username'] !== '') { ?>
            <p><?php echo $userParams['first_name'] . ' ' . $userParams['last_name']; ?></p>
            <p><?php echo $userParams['username']; ?></p>
            <p><?php echo $userParams['num_of_posts']; ?></p>
            <p><?php echo $userParams['points_allocated']; ?></p> 
        <?php } ?>
        
    </body>
    
</html>