<!DOCTYPE html>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require("../account/authconnect.php");

if (!$_SESSION['logged_in']) {
    //shows login button
    $value = 'Login';
    $href = '../account/';
} else {
    //shows profile button
    $sessionEmail = $_SESSION['email'];
    $uid = $auth->getUID($sessionEmail);
    $value = 'Logout';
    $href = '../account/logout.php';
}

?>

<html lang="en">
    
    <head>
        <meta charset="utf-8">    
        <meta http-equiv="refresh" content="160">
        <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
          <style>
          .navbar {
            margin-bottom: 0;
            border-radius: 0;
            background-color: #006400;
            color: #FFFF;
            padding: 1% 0;
            font-size: 1.2em;
            border: 0;
          }
          .navbar-brand{
            float:left;
            min-height: 55px;
            padding: 0 15px 5px;
          }
        .navbar-inverse .navbar-nav .active a,
        .navbar-inverse .navbar-nav .active a:focus,
        .navbar-inverse .navbar-nav .active a:hover{
          color: #FFF;
          background-color: #006400;
        }

        </style>
        <link rel="stylesheet" type="text/css" href="mystyle.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"
           integrity="sha512-M2wvCLH6DSRazYeZRIm1JnYyh22purTM+FDB5CsyxtQJYeKq83arPe5wgbNmcFXGqiSH2XR8dT/fJISVA1r/zQ=="
           crossorigin=""/>
        <title>Pinamals</title>
    </head>
    
    <body>
    
    <!-- The Modal -->
    <div id="myModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <span class="close">&times;</span>
          <h2>Add A Sighting</h2>
        </div>
        <div class="modal-body">
         
          <form action="file.php" method="POST" enctype="multipart/form-data">
          <p>Category</p>
            <select name="Category">
            	<option value="zoo">Zoo</option>
            	<option value="wild">Wild</option>
            	<option value="domestic">Domestic</option>
            </select>
              
          <p>Animal</p>
            <select name="Animal">
    			<?php 
					$handle = fopen("drop.txt", "r");
					if ($handle) {
    					while (($line = fgets($handle)) !== false) {
        					echo "<option value=".$line.">".$line."</option>";
    					}
    					fclose($handle);
					} 
					else {
						// error opening the file.
					} 
				?> 
  			</select>
              
          <p>Image</p>
				<input type="file" name="file"><br><br>
                <input id="la" name="la" value = "0" hidden >
                <input id="lo" name="lo" value = "0" hidden >
                <input id="uid" name="uid" value = '<?php echo $uid ?>' hidden >
				<input type="submit" value="Submit">
          </form>
        </div>
      </div>
    </div>
        
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" name="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="img/w3newbie.png"></a>
          </div>
          <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="#">Home</a></li>
              <li><a id="buttonAdd">Add</a><li>
              <li><a href="./about/">About</a></li>
              <li><a href="<?php echo "../account/profile/" ?>">Profile</a></li>
              <li><a href="<?php echo $href ?>"><?php echo $value ?></a></li>
            </ul>
        </div>
        </div>
      </nav>
        
    </body>
    
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"
       integrity="sha512-lInM/apFSqyy1o6s89K4iQUKg6ppXEgsVxT35HbzUupEVRh2Eu9Wdl4tHj7dZO0s1uvplcYGmt3498TtHq+log=="
       crossorigin=""></script>
     <div id="mapid"></div>
    <p id="demo"></p>
    
    <?php
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
    $result = $conn->query("SELECT user,lat,longitude FROM sightings;"); 
    
    $lats = array();
    $longs = array();
    $users = array();
    $count = 0;           
    while($row = mysqli_fetch_assoc($result))
        {
           $lats[$count] = $row['lat'];
           $longs[$count] = $row['longitude'];
           $users[$count] = $row['user'];
        $count += 1;
        }

          
    ?>
    
    <script type=text/javascript>

    getLocation();    
    var x = document.getElementById("demo");
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(startMapOnCurrentPosistion);
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }
        
    function startMapOnCurrentPosistion(position) {
        var la = position.coords.latitude;
        var lo = position.coords.latitude;
        var mymap = L.map('mapid').setView([la, position.coords.longitude], 12);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets-satellite',
        accessToken: 'pk.eyJ1IjoiYW5kY2FzdCIsImEiOiJjajl2cmx6OHQxYzZwMnJwYzd6MGx4YTBzIn0.Rio1VOW1ZAVkCxwZ2Oz2NQ'
    }).addTo(mymap);
        document.getElementById("la").value = position.coords.latitude;
        document.getElementById("lo").value = position.coords.longitude;
       
        <?php
            $count = count($lats);
            $arrLats = json_encode($lats);
            $arrLongs = json_encode($longs);
            $arrUsers = json_encode($users);
            echo " var jsArrLat = ".$arrLats. ";\n";
            echo " var jsArrLong = ".$arrLongs. ";\n";
            echo " var jsArrUser = ".$arrUsers. ";\n";
            echo " var counter = ".$count.";\n";
        ?>
        var i = 0;
        while(i < counter){
            var marker = new L.marker([jsArrLat[i],jsArrLong[i]]).addTo(mymap)
           .bindPopup(jsArrUser[i])
           .openPopup();
            i+=1;
        }
    
   }
   
    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the button that opens the modal
    var btn = document.getElementById("buttonAdd");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal 
    btn.onclick = function() {
        <?php if ($_SESSION['logged_in'] == false) { ?>
            alert("Must be logged in to add a pin.");
        <?php } else { ?>
            modal.style.display = "block";
        <?php } ?>
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    </script>
    
</html>
