<?php
		$name = $_FILES['file']['name'];
		
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
?>


 
<form action="file.php" method="POST" enctype="multipart/form-data">

<input type="file" name="file"><br><br>
<input type="submit" value="Submit">

</form>