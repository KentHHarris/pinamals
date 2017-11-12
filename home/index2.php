<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"
   integrity="sha512-M2wvCLH6DSRazYeZRIm1JnYyh22purTM+FDB5CsyxtQJYeKq83arPe5wgbNmcFXGqiSH2XR8dT/fJISVA1r/zQ=="
   crossorigin=""/>
</head>
<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"
   integrity="sha512-lInM/apFSqyy1o6s89K4iQUKg6ppXEgsVxT35HbzUupEVRh2Eu9Wdl4tHj7dZO0s1uvplcYGmt3498TtHq+log=="
   crossorigin=""></script>
 <div id="mapid"></div>
<p id="demo"></p>
<script>
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
      
    var mymap = L.map('mapid').setView([position.coords.latitude, position.coords.longitude], 13);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.pencil',
    accessToken: 'pk.eyJ1IjoiYW5kY2FzdCIsImEiOiJjajl2cmx6OHQxYzZwMnJwYzd6MGx4YTBzIn0.Rio1VOW1ZAVkCxwZ2Oz2NQ'
}).addTo(mymap);
    
    var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(mymap);
    marker.bindPopup("<b>I am here!</b>").openPopup();
}
</script>
<?php

  function addSighting($user, $lat, $longi, $animal) {
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

    //$sql = "INSERT INTO sightings (user,lat,Animal)VALUES ('John', 65.4,'squirrle')";
    $sql = "INSERT INTO sightings (user,lat,longitude,Animal) VALUES ('".$user."','".$lat."','".$longi."','".$user."')";
    //$sql = "INSERT INTO sightings (user,lat) VALUES ('Andcast','22.013')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
  }
        addSighting('Andcast',12,12,'squirrle');

?>
