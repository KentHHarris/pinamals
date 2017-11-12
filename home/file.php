<?php
		$name = $_FILES['file']['name'];
		$lat = $_POST['la'];
        $lo = $_POST['lo'];
        $cat = $_POST['Category'];
        $anim = $_POST['Animal'];
        $userId = $_POST['uid'];
		$tmp_name = $_FILES['file']['tmp_name'];
		
        move_uploaded_file($tmp_name,$locations.$name) or die('Failed to upload the file.');

        $file_directory = $locations.$name;
        addSighting($userId,$lat,$lo,$anim,$cat,$file_directory);
    
    //Adds a sighting to the sighting database, needs user, latitude,longitude,animal,category,species


    function addSighting($userID, $lat, $longi, $animal, $category,$file_directory) {
        $servername = "xq7t6tasopo9xxbs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
        $username = "pm3gaxazmj304hlq";
        $password = "ob6dpkek4vwj75w7";
        $dbname = "ou71kwcm2qpd3o88";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        $result = $conn->query("SELECT username FROM user_info WHERE uid = $userID; ");
        
        if ($result->num_rows > 0) {
        // output data of each row
        $row = $result->fetch_assoc();
        }
        
        //$sql = "INSERT INTO sightings (user,lat,Animal)VALUES ('John', 65.4,'squirrle')";
        $sql = "INSERT INTO sightings (uid,user,lat,longitude,Category,Animal,file_dir) VALUES ('".$userID."','".$row[username]."','".$lat."','".$longi."','".$category."','".$animal."','".$file_directory."')";
        //$sql = "INSERT INTO sightings (user,lat) VALUES ('Andcast','22.013')";

        if ($conn->query($sql) === TRUE) {
           header('Location: ./');
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
  }

?>



 
<form action="file.php" method="POST" enctype="multipart/form-data">

<input type="file" name="file"><br><br>
<input type="submit" value="Submit">

</form>