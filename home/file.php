<?php
		$name = $_FILES['file']['name'];
		$lat = $_POST['la'];
        $lo = $_POST['lo'];
        $cat = $_POST['Category'];
        $spec = $_POST['Species']; 
        $anim = $_POST['Animal'];
        $userId = $_POST['userId'];
		$tmp_name = $_FILES['file']['tmp_name'];
		
        
		if (isset($name)) {
			if (!empty($name)){
				$locations = './files/';
			
				if (move_uploaded_file($tmp_name,$locations.$name)){
					echo 'uploaded!';
				}
				else {
					echo 'there was an error.';
				}
			}	
			
			else {
				echo "please choose a file.";
			}
		}
    addSighting($userId,$lat,$lo,$anim,$cat,$spec);

    //Adds a sighting to the sighting database, needs user, latitude,longitude,animal,category,species


    function addSighting($userID, $lat, $longi, $animal, $category, $specie) {
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
        echo $userID;
        $result = $conn->query("SELECT username FROM user_info WHERE uid = $userID; ");
        
        if ($result->num_rows > 0) {
        // output data of each row
        $row = $result->fetch_assoc();
        } else {
            echo "0 results";
    }
        
        //$sql = "INSERT INTO sightings (user,lat,Animal)VALUES ('John', 65.4,'squirrle')";
        $sql = "INSERT INTO sightings (user,lat,longitude,Category,Species,Animal) VALUES ('".$row[username]."','".$lat."','".$longi."','".$category."','".$specie."','".$animal."')";
        //$sql = "INSERT INTO sightings (user,lat) VALUES ('Andcast','22.013')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
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