<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>All database operations</title>
</head>
<body>
	<h1>Database operations</h1>

	<form action="" method="POST">
	
	<select name="opt">
	<option value="read">Read</option>
	<option value="create">Create</option>
	<option value="update">Update</option>
	<option value="delete">Delete</option>
	</select>
		<input type="text" placeholder="Enter id to update" name="id">
		<input type="text" placeholder="Country" name="country">
		<input type="text" placeholder="Language" name="language">
		<input type="text" placeholder="Capital" name="capital">
		<input type="submit" value="Submit now" name="submit">
	</form>
	
</body>
</html>

<?php

	define('DB_SERVER', "localhost");
	define('DB_USER', "root");
	define('DB_PASSWORD', "");
	define('DB_DATABASE', "test");
	define('DB_DRIVER', "mysql");

try {
    $db = new PDO(DB_DRIVER . ":dbname=" . DB_DATABASE . ";host=" . DB_SERVER, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    
	    function readDb($db)
	    {
	    	$stmt = $db->query("SELECT * FROM countries");
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				while($row = $stmt->fetch()) {
				 	echo $row['idCountry'] . "." . $row['name'] . "<br>";
				 	echo $row['capital'] . "<br>";
				 	echo $row['language'] . "<br><hr>";
				 	$db = null;
				 }
	    }

	    function insertDB($db, $country, $capital, $language)
	    {
	    	$stmt = $db->prepare("INSERT INTO countries
	    		(name, capital, language) 
	    		VALUES 
	    		(:country, :capital, :language)");

		    $stmt->bindParam(':country', $country);
		    $stmt->bindParam(':capital', $capital);
		    $stmt->bindParam(':language', $language);
		    if($stmt->execute()) {
		      echo '1 row has been inserted';
		      $db = null;
		    } 
	    }

	    function updateDB($db, $id, $country, $capital, $language){
				$stmt = $db->prepare("UPDATE countries SET name=:country, capital=:capital, language=:lang WHERE idCountry=:id");

			    $stmt->bindParam(':country', $country);
			    $stmt->bindParam(':capital', $capital);
			    $stmt->bindParam(':lang', $language);
			    $stmt->bindParam(':id', $id);

			    if($stmt->execute()) {
					echo 'Update successful.';  
				} 
	    }

	    function deleteDB($db, $id){
	    		
	    		$stmt = $db->prepare("DELETE FROM countries WHERE idCountry=:id");
	    		$stmt->bindParam(':id', $id);

				if($stmt->execute()) {
					echo 'Delete successful';
					$db = null;
				} 
	    }

    if (isset($_POST['submit'])) {
    	$country = $_POST['country'];
    	$capital = $_POST['capital'];
    	$language = $_POST['language'];	
    	$id = $_POST['id']; 
    	
    	$selected_val = $_POST['opt'];  // Storing Selected Value In Variable
		//echo "You have selected :" .$selected_val;  // Displaying Selected Value
	    	if ($selected_val == "read") {
	    		readDb($db); 
	    	}
	    	elseif ($selected_val == "create") {
	    		insertDB($db, $country, $capital, $language);
	    	}
	    	elseif ($selected_val == "update") {
	    		updateDB($db, $id, $country, $capital, $language);
	    	}
	    	elseif ($selected_val == "delete") {
	    		deleteDB($db, $id);
	    	}

		}

	} //End try
catch(PDOException $e) {
    trigger_error('Error:' . $e->getMessage(), E_USER_ERROR);
}